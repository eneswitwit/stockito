<?php

namespace App\Repositories;

use App\Models\Voucher;
use App\Repositories\Contacts\VoucherRepositoryInterface as VoucherRepositoryInterface;

/**
 * Class VoucherRepository
 *
 * @package App\Repositories
 */
class VoucherRepository implements VoucherRepositoryInterface
{
	protected $voucher;

	public function __construct(Voucher $voucher)
	{
		$this->voucher = $voucher;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query()
	{
		return $this->voucher->query();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function all()
	{
		return $this->voucher->all();
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
	 */
	public function get($id)
	{
		$voucher = $this->voucher->find($id);
		return $voucher;
	}


    /**
     * @param array $params
     *
     * @return bool|string
     */
    public function create(array $params)
    {
        try{
            $couponParams = [
                'duration' => 'forever',
                'currency' => 'usd',
                'duration_in_months' => null,
                'max_redemptions' => null,
                'id' => $params['code']
            ];

            if (isset($params['percent'])) {
                $couponParams['percent_off'] = $params['percent'];
            } elseif (isset($params['amount'])) {
                $couponParams['amount_off'] = $params['amount'] * 100;
            } else {
                throw new \Exception('Can\'t create voucher. The amount value or percent value not set');
            }

            \Stripe\Coupon::create($couponParams);

            $this->voucher = new Voucher();
            $this->voucher->sale = isset($params['percent']) ? $params['percent']: $params['amount'];
            $this->voucher->code = $params['code'];
            $this->voucher->duration = "forever";
            $this->voucher->currency = null;
            $this->voucher->duration_in_months = null;
            $this->voucher->max_redemptions = null;
            $this->voucher->percent_off =  isset($params['percent']) ? $params['percent']: $params['amount'];
            $this->voucher->amount_off =  isset($params['percent']) ? $params['percent']: $params['amount'];
            $this->voucher->redeem_by =  null;
            $this->voucher->save();
            return true;
        } catch ( \Exception $e) {
            return  $e->getMessage();
        }
    }

	public function update($id, $attributes)
	{
        //$voucher = $this->voucher->find($id);
	}

	/**
	 * @param $id
	 *
	 * @return bool|null
	 */
	public function delete($id)
	{
		$voucher = $this->voucher->find($id);

		$stripe_id = $voucher->code;
		if (strlen($stripe_id) > 0) {
			$cpn = \Stripe\Coupon::retrieve($stripe_id);
			$cpn->delete();
		}
		$deleteVoucher = $voucher->delete();
		return $deleteVoucher;
	}


	public function async(){
       $coupons =  \Stripe\Coupon::all();
       foreach ($coupons->data as $coupon) {

           $voucher = Voucher::where('code','=', $coupon->id)->first();
           if ($voucher) {
               $voucher->sale = $coupon->percent_off;
               $voucher->save();
           } else {
               $this->voucher = new Voucher();
               $this->voucher->sale = $coupon->percent_off;
               $this->voucher->code = $coupon->id;
               $this->voucher->duration = $coupon->duration;
               $this->voucher->amount_off = $coupon->amount_off;
               $this->voucher->currency = $coupon->currency;
               $this->voucher->duration_in_months = $coupon->duration_in_months;
               $this->voucher->max_redemptions =  $coupon->max_redemptions;
               $this->voucher->percent_off =  $coupon->percent_off;
               $this->voucher->redeem_by =  $coupon->redeem_by;
               $this->voucher->save();
           }
       }
    }
}