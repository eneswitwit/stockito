<?php

// namespace
namespace App\Managers;

// use
use App\Models\FTPFile;
use App\Models\Media;
use App\Services\UploadService;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Constraint;
use Intervention\Image\Image;
use Log;
use Intervention\Image\ImageManager;
use SleepingOwl\Admin\Form\Element\Upload;

/**
 * Class MediaManager
 *
 * @package App\Managers
 */
class MediaManager implements FTPFilesManagerInterface
{
    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @var Image|null
     */
    protected $image = null;

    /**
     * @var Image
     */
    protected $thumbnail;

    /**
     * MediaManager constructor.
     *
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * @var string
     */
    protected static $defaultMediaImage = 'images/default-media.png';

    /**
     * @param string $path
     */
    public function read(string $path): void
    {
        $this->image = $this->imageManager->make($path);
    }

    /**
     * @param Media $media
     */
    public function readMedia(Media $media): void
    {
        $this->read(Storage::disk('brands')->path($media->getFilePath()));
    }

    /**
     * @param Media $media
     *
     * @return Media
     */
    public function setSizes(Media $media): Media
    {
        $media->width = $this->image->width();
        $media->height = $this->image->height();
        $media->orientation = $media->width < $media->height ? Media::PORTRAIT : Media::LANDSCAPE;
        return $media;
    }

    /**
     * @return Image
     */
    public function makeThumbnail(): Image
    {
        if ($this->image->width() > $this->image->height()) {
            $this->thumbnail = $this->image->resize(640, null, function (Constraint $constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            $this->thumbnail = $this->image->resize(null, 640, function (Constraint $constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        return $this->thumbnail;
    }

    /**
     * @param Media $media
     * @param Image $image
     *
     * @return bool
     */
    protected function storeThumbnail(Media $media, Image $image): bool
    {
        return Storage::disk('brands_thumbnail')->put($media->getFilePath(), (string)$image->encode());
    }

    /**
     * @param Media $media
     *
     * @return bool
     */
    public function makeAndStoreThumbnail(Media $media): bool
    {
        return $this->storeThumbnail($media, $this->makeThumbnail());
    }

    /**
     * @param Media $media
     * @param UploadedFile $file
     *
     * @return bool
     *
     */
    public function storeMedia(Media $media, UploadedFile $file): bool
    {
        /*if($file->guessExtension() === null || $file->guessExtension() === '') {
            if($file->getClientOriginalExtension() === 'pdf') {
                return \Storage::disk('s3')->putFileAs($media->brand->getImagePath(), $file,
                    Media::FILE_PREFIX . $file->hashName() . 'ai');
            } else {
                return \Storage::disk('s3')->putFileAs($media->brand->getImagePath(), $file,
                    Media::FILE_PREFIX . $file->hashName() . $file->getClientOriginalExtension());
            }
        } else {*/
            return \Storage::disk('s3')->putFileAs($media->brand->getImagePath(), $file,
                Media::FILE_PREFIX . UploadService::hashName($file));
        //}
    }

    /**
     * @param Media $media
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function storeImageWithThumbnail(Media $media, UploadedFile $file): bool
    {
        return $this->storeMedia($media, $file) && $this->makeAndStoreThumbnail($media);
    }

    /**
     * @param Media $media
     *
     * @return string
     */
    public static function getThumbnailUrl(Media $media): string
    {

        if ($media->thumbnail) {
            return route('image-thumbnail', ['name' => $media->thumbnail]);
        }

        return asset(self::$defaultMediaImage);
    }

    /**
     * @param Media $media
     *
     * @return string
     */
    public static function getMediaUrl(Media $media): string
    {
        switch ($media->content_type) {
            case 'image/jpeg':
                return route('image', ['name' => $media->file_name]);
                break;
            default:
                return asset(self::$defaultMediaImage);
        }
    }

    /**
     * @param Media $media
     *
     * @return bool
     */
    public static function hasMediaFile(Media $media): bool
    {
        return Storage::disk('brands')->exists($media->getFilePath());
    }

    /**
     * @param Media $media
     *
     * @return bool
     */
    public static function hasThumbnail(Media $media): bool
    {
        return Storage::disk('brands_thumbnail')->exists($media->getFilePath());
    }

    /**
     * @param FTPFile $ftpFile
     *
     * @return Media
     */
    public function getMediaForFTPFile(FTPFile $ftpFile): Media
    {
        $file = new File($ftpFile->file);
        $brand = $ftpFile->ftpUser->brand;

        $media = (new Media())
            ->where('file_name', $file->getFilename())
            ->where('dir', $brand->id)
            ->first();

        if ($media === null) {
            $media = new Media();
        }

        $media->brand()->associate($brand);
        $media->file_size = $ftpFile->size;
        $media->content_type = $file->getMimeType();
        $media->dir = $brand->id;
        $media->file_name = $file->getFilename();
        $media->origin_name = $file->getFilename();
        $media->width = 0;
        $media->height = 0;

        if ($file->getMimeType() === Media::JPEG_MIME) {
            $this->readMedia($media);

            $media = $this->setSizes($media);
        }

        return $media;
    }

    /**
     * @param Media $media
     *
     * @return Media
     */
    public function readMetaData(Media $media): Media
    {
        try {
            $media->title = $media->getIPTC() ? $media->getIPTC()->getTitle() : '';
        } catch (\Exception $exception) {
        }
        $media->keywords = $media->getEXIF() && $media->getEXIF()->getKeywords() ? implode(', ',
            $media->getEXIF()->getKeywords()) : '';
        $media->source = $media->getEXIF() && $media->getEXIF()->getSource() ? $media->getEXIF()->getSource() : '';
        return $media;
    }
}
