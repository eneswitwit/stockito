<?php

// namespace
namespace App\Models;

// use
use App\Models\Media\Category;
use App\Support\Database\CacheQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Log;

/**
 * App\Models\Brand
 *
 * @property-read \App\Models\User $user
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Creative[] $brands
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invite[] $invites
 * @property int $id
 * @property int $user_id
 * @property string $brand_name
 * @property string $company_name
 * @property string $address_1
 * @property string $address_2
 * @property string $city
 * @property string $zip
 * @property int $country_id
 * @property string $eur_uid
 * @property string $homepage
 * @property string $phone
 * @property string $contact_first_name
 * @property string $contact_last_name
 * @property string $contact_title
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static Builder|Brand whereAddress1($value)
 * @method static Builder|Brand whereAddress2($value)
 * @method static Builder|Brand whereBrandName($value)
 * @method static Builder|Brand whereCity($value)
 * @method static Builder|Brand whereCompanyName($value)
 * @method static Builder|Brand whereContactFirstName($value)
 * @method static Builder|Brand whereContactLastName($value)
 * @method static Builder|Brand whereContactTitle($value)
 * @method static Builder|Brand whereCountryId($value)
 * @method static Builder|Brand whereCreatedAt($value)
 * @method static Builder|Brand whereEurUid($value)
 * @method static Builder|Brand whereHomepage($value)
 * @method static Builder|Brand whereId($value)
 * @method static Builder|Brand wherePhone($value)
 * @method static Builder|Brand whereUpdatedAt($value)
 * @method static Builder|Brand whereUserId($value)
 * @method static Builder|Brand whereZip($value)
 * @property-read \App\Models\Country $country
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activities
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $targetActivity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Creative[] $creatives
 * @property-read \App\Models\License $license
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $originActivity
 * @property-read string $name
 * @property int|null $used_storage
 * @property-read string $format_used_storage
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Brand whereUsedStorage($value)
 * @property int|null $plan_id
 * @property-read \App\Models\Plan|null $plan
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Brand wherePlanId($value)
 * @property int|null $ftp_user_id
 * @property-read \App\Models\FTPUser|null $ftpUser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Brand whereFtpUserId($value)
 * @property int $used_storage_photo
 * @property int $used_storage_illustration
 * @property int $used_storage_video
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Brand whereUsedStorageIllustration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Brand whereUsedStoragePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Brand whereUsedStorageVideo($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invoice[] $invoices
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PeopleAttribute[] $peopleAttributes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Supplier[] $suppliers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media\Category[] $categories
 * @property string|null $logo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Brand whereLogo($value)
 */
class Brand extends AbstractBrandModel
{

    use CacheQueryBuilder;

    const HEAD_OF_TEAM_PERMISSION = 1;
    const ACTIVE_EDITING_USER_PERMISSION = 2;
    const SEARCH_ONLY_USER_PERMISSION = 3;

    public static $permission_names = [
        self::HEAD_OF_TEAM_PERMISSION => 'Head of Team',
        self::ACTIVE_EDITING_USER_PERMISSION => 'Active Editing User',
        self::SEARCH_ONLY_USER_PERMISSION => 'Search Only User'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'brand_name',
        'company_name',
        'address_1',
        'address_2',
        'city',
        'zip',
        'eur_uid',
        'homepage',
        'phone',
        'contact_first_name',
        'contact_last_name',
        'contact_title',
        'user_id',
        'country_id',
        'plan_id',
        'ftp_user_id'
    ];

    /**
     * @var array
     */
    protected $appends = [
        'format_used_storage'
    ];

    /**
     * @return array
     */
    public static function getPermissions(): array
    {
        return [
            self::SEARCH_ONLY_USER_PERMISSION,
            self::ACTIVE_EDITING_USER_PERMISSION,
            self::HEAD_OF_TEAM_PERMISSION
        ];
    }

    /**
     * @return HasMany
     */
    public function peopleAttributes(): HasMany
    {
        return $this->hasMany(PeopleAttribute::class);
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return HasMany
     */
    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    /**
     * @return HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function ftpUser(): BelongsTo
    {
        return $this->belongsTo(FTPUser::class, 'ftp_user_id');
    }

    /**
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * @return HasMany
     */
    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class);
    }

    /**
     * @return BelongsToMany
     */
    public function creatives(): BelongsToMany
    {
        return $this->belongsToMany(Creative::class)->withPivot('role', 'position');
    }

    /**
     * @return HasMany
     */
    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
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
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * @return string
     */
    public function targetActivityText(): string
    {
        return $this->brand_name;
    }

    /**
     * @return string
     */
    public function originActivityText(): string
    {
        return $this->brand_name;
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getNameAttribute($value): string
    {
        return $this->brand_name;
    }

    /**
     * @return string
     */
    public function getFormatUsedStorageAttribute(): string
    {
        $size = $this->used_storage;

        if (empty($size)) {
            return '0';
        }
        if ($size < 1024) {
            return "{$size}  KB";
        } elseif ($size > 1024 && $size < 1048576) {
            $mb = round(($size / 1000), 2);
            return "{$mb}  MB";
        } elseif ($size > 1048576) {
            $gb = round(($size / 1000000), 2);
            return "{$gb}  GB";
        }
    }

    /**
     * @return BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * @return string
     */
    public function getUserTypeName(): string
    {
        return 'Brand';
    }

    /**
     * @return string
     */
    public function getImagePath(): string
    {
        return (string)$this->id;
    }

    /**
     * @return Brand
     * @throws \Exception
     */
    public function makeHomeDir(): self
    {

        // local storage
        $dir = storage_path('app/brands/' . $this->getImagePath());
        if (!mkdir($dir) && !is_dir($dir)) {
            throw new \Exception('Can\'t create homedir');
        }

        // s3
        \Storage::disk('s3')->deleteDirectory($this->getImagePath());
        \Storage::disk('s3')->makeDirectory($this->getImagePath());

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getPlans(): ?Collection
    {
        $user = $this->user;
        $subscription = $user->subscription('main');
        return $subscription ? (new Plan)->where('stripe_id', $subscription->stripe_plan)->get() : null;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        $user = $this->user;

        if ($user) {
            $subscription = (new Subscription())->where('user_id', $user->id)->where(function (Builder $builder) {
                $builder->orWhere('ends_at', null)->orWhere('ends_at', '>', Carbon::now());
            })->orderBy('created_at', 'DESC')->first();
        } else {
            return null;
        }
        return $subscription && $subscription->plan ? $subscription->plan->product : null;
    }

    /**
     * @return null|string
     */
    public function getLogoUrl(): ?string
    {
        return Storage::url($this->logo);
    }

    /**
     * @return string
     */
    public static function getLogoPath(): string
    {
        return 'public/brands_logo';
    }
}