<?php

// namespace
namespace App\Services;

// use
use App\Managers\MediaManager;
use App\Managers\Processors\Mp4FileProcessor;
use App\Models\Media;
use App\Models\Brand;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use App\Classes\ImageMetadataParser;
use PHPExif\Reader\Reader;
use Illuminate\Support\Str;
use Log;

/**
 * Class UploadService
 *
 * @package App\Services
 */
class UploadService
{
    /**
     * @var MediaManager
     */
    protected $mediaManager;

    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * UploadService constructor.
     *
     * @param MediaManager $mediaManager
     */
    public function __construct(MediaManager $mediaManager, ImageManager $imageManager)
    {
        $this->mediaManager = $mediaManager;
        $this->imageManager = $imageManager;
    }

    /**
     * @param Brand $brand
     *
     * @return array
     */
    public static function calculateUsedStorage(Brand $brand): array
    {
        $sData = [];
        $disk = \Storage::disk('s3');
        $sData['used'] = array_sum(array_map(function ($file) {
            return (int)$file['size'];
        }, $disk->listContents($brand->getImagePath(), true /*<- recursive*/)));
        $sData['all'] = $brand->getProduct() ? $brand->getProduct()->storage : 0;
        return $sData;
    }

    /**
     * @param Brand $brand
     *
     * @return int
     */
    public static function calculateUsedStorageFull(Brand $brand): int
    {
        $dir = storage_path('app/brands/' . $brand->getImagePath());
        return self::folderSize($dir);
    }

    /**
     * @param Brand $brand
     *
     * @return array
     */
    public static function formatedUsedStorage(Brand $brand): array
    {
        $sData = self::calculateUsedStorage($brand);

        foreach ($sData as $key => $data) {
            $sData[$key . 'Formated'] = self::getUnitsOfBytes($sData[$key]);
        }

        return $sData;
    }

    /**
     * @param string $dir
     * @param array $extensions
     *
     * @return int
     */
    protected static function folderSize(string $dir, array $extensions = []): int
    {
        $size = 0;
        foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
            if ($extensions) {
                if (is_file($each) && \in_array((new \SplFileInfo($each))->getExtension(), $extensions)) {
                    $size += filesize($each);
                } else {
                    $size += self::folderSize($each, $extensions);
                }
            } else {
                if (is_file($each)) {
                    $size += filesize($each);
                } else {
                    $size += self::folderSize($each);
                }
            }

        }
        return $size;
    }

    /**
     * @param int $bytes
     * @param int $precision
     *
     * @return string
     */
    public static function getUnitsOfBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1000));
        $pow = min($pow, \count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= 1000 ** $pow;
//         $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }


    /**
     * @param Media $media
     *
     * @return bool
     * @throws \Exception
     */
    public static function removeMedia(Media $media): bool
    {
        $status = true;
        if (file_exists(storage_path('app/brands/' . $media->getFilePath()))) {
            $status = $status && unlink(storage_path('app/brands/' . $media->getFilePath()));
        }

        if (file_exists(storage_path('app/brands_thumbnail/' . $media->getFilePath()))) {
            $status = $status && unlink(storage_path('app/brands_thumbnail/' . $media->getFilePath()));
        }

        \Storage::disk('s3')->delete($media->getFilePath());
        \Storage::disk('s3')->delete($media->getFilePath() . '.mp4');

        return $status && $media->delete();
    }

    /**
     * Get a filename for the file.
     *
     * @param $file
     * @param  string  $path
     * @return string
     */
    public static function hashName($file, $path = null) {

        if ($path) {
            $path = rtrim($path, '/').'/';
        }

        $hash = $file->hashName() ? substr($file->hashName(), 0, strpos($file->hashName(), ".")) : Str::random(40);

        return $path.$hash.'.'.$file->getClientOriginalExtension();
    }

    /**
     * @param Request $request
     * @param Brand $brand
     *
     * @return Media
     * @throws \Exception
     */
    public function uploadMedia(Request $request, Brand $brand): Media
    {

        if ($brand->user->cant('upload', Media::class)) {
            throw new \LogicException('You can\'t upload more files');
        }
        $file = $request->file('file');
        if (self::calculateUsedStorage($brand)['used'] + $file->getSize() >= $brand->getProduct()->storage) {
            throw new \LogicException('This file bigger than you have the free space');
        }

        // change file hash name to extension wise
        $media = new Media([
            'file_name' => Media::FILE_PREFIX . $this->hashName($file),
            'origin_name' => $file->getClientOriginalName(),
            'content_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'brand_id' => $brand->id,
            'dir' => $brand->getImagePath()
        ]);

        if (\in_array(strtolower($file->getClientOriginalExtension()), ['ai', 'eps'])) {

            $status = $this->mediaManager->storeMedia($media, $file);
            $image = new \Imagick();
            $image->setResolution(300, 300);
            $image->readImage($file->getRealPath());
            $jpeg = storage_path('app/brands_thumbnail/' . $media->brand->id . '/' . $media->file_name . '.jpeg');

            // create good thumbnail
            $dimensions = $image->getImageGeometry();
            $width = $dimensions['width'];
            $height = $dimensions['height'];
            $maxWidth = 600;
            $maxHeight = 600;

            if ($height >= $width) {
                //Portrait
                if ($height > $maxHeight) {
                    $cmd = escapeshellcmd("convert -resize 'x{$maxHeight}' -density 600 -flatten {$file->getRealPath()} -colorspace rgb {$jpeg}");
                } else {
                    $cmd = escapeshellcmd("convert -resize 'x{$height}' -density 600 -flatten {$file->getRealPath()} -colorspace rgb {$jpeg}");
                }
            } elseif ($height < $width) {
                if ($width > $maxWidth) {
                    $cmd = escapeshellcmd("convert -resize '{$maxWidth}' -density 600 -flatten {$file->getRealPath()} -colorspace rgb {$jpeg}");
                } else {
                    $cmd = escapeshellcmd("convert -resize '{$width}' -density 600 -flatten {$file->getRealPath()} -colorspace rgb {$jpeg}");
                }
            }

            exec($cmd, $out, $return_var);

            $media->thumbnail = $media->file_name . '.jpeg';
            $media->width = $image->getImageWidth();
            $media->height = $image->getImageHeight();

        } elseif (\in_array(strtolower($file->getClientOriginalExtension()), ['mp4', 'mov'])) {
            $media = $this->setVideoData($media, $file, $brand);
            $status = $this->mediaManager->storeMedia($media, $file);

        } elseif (\in_array(strtolower($file->getClientOriginalExtension()), ['jpeg', 'jpg'])) {

            $this->mediaManager->read($file->getRealPath());
            $status = $this->mediaManager->storeMedia($media, $file);
            $media = $this->mediaManager->setSizes($media);
            $status = $status && $this->mediaManager->makeAndStoreThumbnail($media);
            $media->thumbnail = $media->file_name;
            $iptc = new ImageMetadataParser($file);

            if ($iptc->parseIPTC()) {
                $media->setIPTC($iptc);
            }

            $media->setEXIF(Reader::factory(Reader::TYPE_NATIVE)->read($file));

        } else {
            throw new \LogicException('Can\'t upload file. File type not allowed.');
        }

        if (!$status) {
            throw new \LogicException('Can\'t upload file');
        }

        try {
            $media->title = $media->getIPTC() ? $media->getIPTC()->getTitle() : '';
        } catch (\Exception $exception) {
        }

        $media->keywords = $media->getEXIF() && $media->getEXIF()->getKeywords() ? implode(', ',
                $media->getEXIF()->getKeywords()) : '';

        $media->source = $media->getEXIF() && $media->getEXIF()->getSource() ? $media->getEXIF()->getSource() : '';

        $media->save();

        return $media;
    }

    /**
     * @param \Intervention\Image\Image $image
     *
     * @return \Intervention\Image\Image
     */
    public static function makeThumbnail(\Intervention\Image\Image $image): \Intervention\Image\Image
    {
        $width = $image->width();
        $height = $image->height();
        $image->width() < $image->height() ? $width = null : $height = null;
        return $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    }

    /**
     * @param Media $media
     * @param $file
     *
     * @return Media
     */
    public static function setVideoData(Media $media, $file, $brand)
    {
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries' => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'timeout' => 3600, // The timeout for the underlying process
            'ffmpeg.threads' => 2, // The number of threads that FFMpeg should use
        ]);

        $video = $ffmpeg->open($file->getRealPath());
        $frameImage = $video->frame(TimeCode::fromSeconds(1));
        $frameImage->save(storage_path('app/brands_thumbnail/' . $brand->id . '/' . $file->getBasename() . '.jpg'));
        $media->thumbnail = $file->getBasename() . '.jpg';
        $media->width = 0;
        $media->height = 0;
        $media->orientation = $media->width >= $media->height ? Media::LANDSCAPE : Media::PORTRAIT;
        return $media;
    }
}
