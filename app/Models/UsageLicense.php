<?php

// namespace
namespace App\Models;

// use
use App\Support\Database\CacheQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class UsageLicense
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $license_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $usage
 * @property string|null $printrun
 * @property string|null $bill_file
 * @property string|null $bill_file_origin_name
 * @property \Carbon\Carbon|null $start_at
 * @property \Carbon\Carbon $expired_at
 * @property string $invoice_number
 * @property string $invoice_number_by
 * @property string|null $any_limitations
 * @property string|null $territory
 *
 * @property-read \App\Models\License $license
 */
class UsageLicense extends Model
{

    use CacheQueryBuilder;

    /**
     * @var array
     */
    protected $fillable = [
        'license_id',
        'usage',
        'printrun',
        'bill_file',
        'bill_file_origin_name',
        'expired_at',
        'start_at',
        'any_limitations',
        'territory',
        'invoice_number',
        'invoice_number_by',
    ];

    /**
     * @var array
     */
    protected $dates = ['expired_at', 'start_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function license(): HasOne
    {
        return $this->hasOne(License::class, 'id', 'license_id');
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
     * @param \Illuminate\Http\Request $request
     * @param bool $addFile
     *
     * @return \App\Models\UsageLicense
     */
    public function fillFromRequest(Request $request, $addFile = false): self
    {
        $this->fill([
            'invoice_number' => $request->input('invoiceNumber'),
            'invoice_number_by' => $request->input('invoiceNumberBy'),
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
        return \Storage::disk('bill_licenses')->download($this->license->media->brand->id . '/' . $this->bill_file,
            $this->bill_file_origin_name);
    }
}
