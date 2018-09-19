<?php

// namespace
namespace App\Models;

// use
use App\Classes\ImageMetadataParser;
use App\Events\LicenseChangedForMediaEvent;
use App\Models\Media\Category;
use App\Services\UploadService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Longman\LaravelLodash\Eloquent\UserIdentities;
use PHPExif\Exif;
use PHPExif\Reader\Reader;

/**
 * App\Models\Media
 *
 * @property int $id
 * @property int $brand_id
 * @property int|null $license_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $peoples_attribute
 * @property-read \App\Models\Brand $brand
 * @property-read \App\Models\License|null $license
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @method static Builder|Media whereBrandId($value)
 * @method static Builder|Media whereCreatedAt($value)
 * @method static Builder|Media whereId($value)
 * @method static Builder|Media whereLicenseId($value)
 * @method static Builder|Media whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $file_size
 * @property string|null $content_type
 * @property string|null $dir
 * @property string|null $file_name
 * @property string|null $origin_name
 * @method static Builder|Media whereContentType($value)
 * @method static Builder|Media whereDir($value)
 * @method static Builder|Media whereFileName($value)
 * @method static Builder|Media whereFileSize($value)
 * @method static Builder|Media whereOriginName($value)
 * @property int $published
 * @property string $title
 * @method static Builder|Media wherePublished($value)
 * @method static Builder|Media whereTitle($value)
 * @method static Builder|Media notPublished()
 * @method static Builder|Media published()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Share[] $shares
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $targetActivities
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Media onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Media whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Media withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Media withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\License[] $licenses
 * @property string|null $file_type
 * @property string|null $keywords
 * @property string|null $source
 * @property string|null $language
 * @property int|null $category_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereSource($value)
 * @property-read \App\Models\Media\Category|null $category
 * @property string|null $notes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereNotes($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PeopleAttribute[] $peopleAttributes
 * @property int|null $supplier_id
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereSupplierId($value)
 * @property string $orientation
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereOrientation($value)
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\User $destroyer
 * @property-read \App\Models\User|null $editor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereUpdatedBy($value)
 * @property int $width
 * @property int $height
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereWidth($value)
 * @property string $thumbnail
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media whereThumbnail($value)
 */
class Media extends Model implements TargetActivityInterface
{
    use UserIdentities;

    public const FILE_PREFIX = 'f-';

    public const PORTRAIT = 'portrait';
    public const LANDSCAPE = 'landscape';

    public const PHOTOS_TYPE = 'PHOTOS';
    public const ILLUSTRATIONS_TYPE = 'ILLUSTRATIONS';
    public const VECTORS_TYPE = 'VECTORS';
    public const VIDEO_FOOTAGE_TYPE = 'VIDEO_FOOTAGE';

    public const JPEG_MIME = 'image/jpeg';
    public const MPG_MIME = 'video/mpg';
    public const MP4_MIME = 'video/mp4';
    public const EPS_MIME = 'image/x-eps';
    public const AI_MIME = 'application/pdf';

    /**
     * @var array
     */
    public static $types = [
        self::PHOTOS_TYPE => 'Photo',
        self::ILLUSTRATIONS_TYPE => 'Illustration',
        self::VECTORS_TYPE => 'Vector graphic',
        self::VIDEO_FOOTAGE_TYPE => 'Video'
    ];

    /**
     * @var null
     */
    protected $image = null;

    /**
     * @var null
     */
    protected $iptc = null;

    /**
     * @var null
     */
    protected $exif = null;

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'license_id',
        'brand_id',
        'file_name',
        'origin_name',
        'dir',
        'content_type',
        'file_size',
        'file_type',
        'language',
        'category_id',
        'keywords',
        'source',
        'notes',
        'supplier_id',
        'orientation',
        'peoples_attribute'
    ];

    protected static function boot()
    {
        parent::boot();

        self::updating(function (self $model) {
            if ($model->isDirty('license_id')) {
                if (auth()->check()) {
                    $user = auth()->user();
                } else {
                    $user = $model->brand->user;
                }

                if ($user->brand) {
                    $origin = $user->brand;
                } else {
                    $origin = $user->creative;
                }
                event(new LicenseChangedForMediaEvent($origin, $model));
            }

        });
    }

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return BelongsToMany
     */
    public function peopleAttributes(): BelongsToMany
    {
        return $this->belongsToMany(PeopleAttribute::class);
    }

    /**
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    /**
     * @return HasMany
     */
    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return BelongsToMany
     */
    public function shares(): BelongsToMany
    {
        return $this->belongsToMany(Share::class);
    }

    /**
     * @return MorphMany
     */
    public function targetActivities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'target');
    }

    /**
     * @return string
     */
    public function targetActivityText(): string
    {
        return $this->origin_name;
    }

    /**
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getFile(): string
    {
        return Storage::disk('brands')->get($this->getFilePath());
    }

    /**
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getThumbnailFile(): string
    {
        return Storage::disk('brands_thumbnail')->get($this->getFilePath());
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->dir . '/' . $this->file_name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        switch ($this->content_type) {
            case self::JPEG_MIME:
                return 'Image';
            case self::MPG_MIME:
                return 'Video';
            default:
                return 'Undefined';
        }
    }

    /**
     * @return Media
     */
    public function publish(): self
    {
        $this->published = true;
        return $this;
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function scopeNotPublished(Builder $builder): Builder
    {
        return $builder->where('published', false);
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function scopePublished(Builder $builder): Builder
    {
        return $builder->where('published', true);
    }

    /**
     * @return ImageMetadataParser|null
     */
    public function getIPTC(): ?ImageMetadataParser
    {
        if (!$this->iptc) {
            $this->iptc = new ImageMetadataParser(storage_path('app/brands/' . $this->getFilePath()));
            $this->iptc->parseIPTC();
        }
        return $this->iptc;
    }

    /**
     * @return Exif
     */
    public function getEXIF(): ?Exif
    {
        if (!$this->exif) {
            $exif = Reader::factory(Reader::TYPE_NATIVE)->read(storage_path('app/brands/' . $this->getFilePath()));
            if ($exif) {
                $this->exif = $exif;
            }
        }
        return $this->exif;
    }

    /**
     * @return array
     */
    public function getAllEXIF(): ?array
    {
        if ($this->getEXIF()) {
            return [
                'title' => (string)$this->getEXIF()->getTitle(),
                'camera' => (string)$this->getEXIF()->getCamera(),
                'gps' => (string)$this->getEXIF()->getGPS(),
                'aperture' => (string)$this->getEXIF()->getAperture(),
                'copyright' => (string)$this->getEXIF()->getCopyright(),
                'author' => (string)$this->getEXIF()->getAuthor(),
                'keywords' => $this->getEXIF()->getKeywords() ? implode(',', $this->getEXIF()->getKeywords()) : '',
                'orientation' => (string)$this->getEXIF()->getOrientation(),
            ];
        }

        return null;
    }

    /**
     * @param array $exif
     *
     * @return Media
     */
    public function setExif(array $exif): self
    {
        if (isset($exif['camera'])) {
            $this->getEXIF()->setCamera($exif['camera']);
        }
        if (isset($exif['gps'])) {
            $this->getEXIF()->setCamera($exif['gps']);
        }
        if (isset($exif['aperture'])) {
            $this->getEXIF()->setCamera($exif['aperture']);
        }
        if (isset($exif['copyright'])) {
            $this->getEXIF()->setCamera($exif['copyright']);
        }
        if (isset($exif['author'])) {
            $this->getEXIF()->setCamera($exif['author']);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getTypeTitle(): string
    {
        return self::$types[$this->file_type] ?? '';
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public static function getTypeVar($type): string
    {
        return array_search($type, self::$types);
    }

    /**
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
