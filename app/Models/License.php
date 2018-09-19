<?php

// namespace
namespace App\Models;

// use
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * App\Models\License
 *
 * @property int $id
 * @property \Carbon\Carbon $expired_at
 * @property int $license_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Media $media
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License soonExpiring()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereLicenseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\User|null $destroyer
 * @property-read \App\Models\User|null $editor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereUpdatedBy($value)
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\User|null $deletedBy
 * @property-read \App\Models\User|null $updatedBy
 * @property string|null $usage
 * @property string|null $printrun
 * @property string|null $bill_file
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereBillFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License wherePrintrun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereUsage($value)
 * @property \Carbon\Carbon|null $start_at
 * @property string $invoice_number
 * @property string $invoice_number_by
 * @property string|null $any_limitations
 * @property string|null $territory
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereAnyLimitations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereInvoiceNumberBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereTerritory($value)
 * @property int|null $media_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereMediaId($value)
 * @property-read \App\Models\Media|null $mediaBelongs
 * @property string|null $bill_file_origin_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereBillFileOriginName($value)
 */
class License extends AbstractUserIdentitiesModel
{
    public const RF = 1;
    public const RM = 2;
    public const RE = 3;
    public const BO = 4;

    /**
     * @var array
     */
    protected $fillable = [
        'usage',
        'printrun',
        'bill_file',
        'license_type',
        'expired_at',
        'start_at',
        'any_limitations',
        'territory',
        'invoice_number',
        'invoice_number_by',
        'media_id'
    ];

    /**
     * @var array
     */
    protected $dates = ['expired_at', 'start_at'];


    /**
     * @return HasOne
     */
    public function media(): HasOne
    {
        return $this->hasOne(Media::class);
    }

    /**
     * @return BelongsTo
     */
    public function mediaBelongs(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * @return array
     */
    public static function getLicenses(): array
    {
        return [
            self::RF,
            self::RM,
            self::RE,
            self::BO,
        ];
    }

    /**
     * @return array
     */
    public static function getLicensesWithTitle(): array
    {
        return [
            self::RF => 'RF',
            self::RM => 'RM',
            self::RE => 'RE',
            self::BO => 'BO',
        ];
    }

    /**
     * @return array
     */
    public static function getLicensesWithTitleLong(): array
    {
        return [
            self::RF => 'RF (Royalty Free)',
            self::RM => 'RM (Rights Managed)',
            self::RE => 'RE (Rights Easy)',
            self::BO => 'BO (Buy Out/Shooting)',
        ];
    }

    /**
     * @return array
     */
    public static function getLicensesWithColor(): array
    {
        return [
            self::RF => 'green',
            self::RM => 'red',
            self::RE => 'orange',
            self::BO => 'blue',
        ];
    }

    /**
     * @return string
     */
    public function getLicenseTitle(): string
    {
        return self::getLicensesWithTitle()[$this->license_type];
    }

    /**
     * @return string
     */
    public function getLicenseColor(): string
    {
        return self::getLicensesWithColor()[$this->license_type];
    }

    /**
     * @param Builder $q
     *
     * @return Builder
     */
    public function scopeSoonExpiring(Builder $q): Builder
    {
        return $q->orderBy('expired_at', 'DESC');
    }

    /**
     * @param Request $request
     * @param bool $addFile
     *
     * @return License
     */
    public function fillFromRequest(Request $request, $addFile = false): self
    {
        $this->fill([
            'license_type' => (int)$request->input('type'),
            'invoice_number' => $request->input('invoiceNumber'),
            'invoice_number_by' => $request->input('invoiceNumberBy'),
            'media_id' => null
        ]);

        switch ((int)$request->input('type')) {
            case self::RF:
                $this->fill([
                    'printrun' => $request->input('printrun')
                ]);
                break;
            case self::RM:
                $this->fill([
                    'usage' => $request->input('usage'),
                    'printrun' => $request->input('printrun'),
                    'start_at' => Carbon::parse($request->input('startDate')),
                    'expired_at' => Carbon::parse($request->input('expireDate')),
                    'any_limitations' => $request->input('anyLimitations'),
                ]);
                break;
            case self::RE:
                $this->fill([
                    'start_at' => Carbon::parse($request->input('startDate')),
                    'expired_at' => Carbon::parse($request->input('expireDate')),
                    'territory' => $request->input('territory'),
                ]);
                break;
            case self::BO:
                $this->fill([
                    'start_at' => Carbon::parse($request->input('startDate')),
                    'expired_at' => Carbon::parse($request->input('expireDate')),
                    'any_limitations' => $request->input('anyLimitations'),
                ]);
                break;
        }

        if ((bool)$request->input('removeBill', false)) {
            $this->bill_file = null;
            $this->bill_file_origin_name = null;
        }

        if ($addFile && $request->hasFile('billFile')) {
            $file = $request->file('billFile');
            $this->bill_file = $file->hashName();
            $this->bill_file_origin_name = $file->getClientOriginalName();
        }

        return $this;
    }

    /**
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getFile(): string
    {
        return \Storage::disk('brands')->get('licenses/' . $this->bill_file);
    }

    /**
     * @return StreamedResponse
     */
    public function downloadFile(): StreamedResponse
    {
        return \Storage::disk('bill_licenses')->download($this->media->brand->id . '/' . $this->bill_file,
            $this->bill_file_origin_name);
    }
}
