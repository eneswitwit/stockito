<?php

namespace App\Http\Controllers\Admin;

use AdminSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Voucher;
use Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Repositories\ProductRepository;
use App\Repositories\VoucherRepository;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers\Admin
 */
class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    protected $product;

    /**
     * @var ProductRepository
     */
    protected $voucher;

    /**
     * ProductController constructor.
     *
     * @param ProductRepository $product
     * @param VoucherRepository $voucher
     */
    public function __construct(ProductRepository $product, VoucherRepository $voucher)
    {
        $this->product = $product;
        $this->voucher = $voucher;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return AdminSection::view(view('admin.product.index', [
            'product_interval' => $this->product->interval()
        ]));
    }

    public function addPlan(Request $request): JsonResponse
    {
        $validator = $this->validatorPlan($request->all());

        if ($validator->passes()) {
            try {
                $plan = $this->product->create(array(
                    'price' => $request->input('price'),
                    'description' => $request->input('title'),
                    'title' => $request->input('title'),
                    'interval' => $request->input('interval'),
                    'storage' => $request->input('storage'),
                ));
            } catch (\Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()]);
            }

            return new JsonResponse(['success' => trans('admin.successfully_new_subscription')]);
        }

        return new JsonResponse(['error' => $validator->errors()->all()]);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function PlansData()
    {
        return Datatables::of($this->product->query())->make(true);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function VouchersData()
    {
        return Datatables::of($this->voucher->query())->make(true);
    }

    /**
     * @param Plan $id
     *
     * @return JsonResponse
     */
    public function getPlan(Plan $id): JsonResponse
    {
        return new JsonResponse($id);
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function editPlan($id, Request $request)
    {
        $validator = $this->validatorPlan($request->all());

        if ($validator->passes()) {
            try {
                $plan = $this->product->update(
                    $id,
                    [
                        'title' => $request->input('title'),
                        'storage' => $request->input('storage'),
                        'price' => $request->input('price'),
                        'interval' => $request->input('interval'),
                        'description' => $request->input('title')
                    ]
                );
            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Exception error']);
            }
            return new JsonResponse(['success' => trans('admin.successfully_edit_plan')]);
        }

        return new JsonResponse(['error' => $validator->errors()->all()]);
    }

    /**
     * @param Plan $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePlan($id)
    {
        try {
            $plan = $this->product->delete($id);
            Session::flash('message', 'Successfully plan deleted!');
            return redirect()->route('admin.product');
        } catch (\Exception $e) {
            Session::flash('message', 'An error has occurred');
            return redirect()->route('admin.product');
        }

    }

    /**
     * @return mixed
     */
    public function getVouchers()
    {
        return Datatables::of(Plan::query())->make(true);
    }

    /**
     * @param Voucher $id
     *
     * @return JsonResponse
     */
    public function getVoucher(Voucher $id): JsonResponse
    {
        return new JsonResponse($id);
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function editVoucher($id, Request $request)
    {
        $validator = $this->validatorVoucher($request->all());

        if ($validator->passes()) {
            $this->voucher->update($id, [
                    'percent' => $request->input('sale')
                ]
            );
            return new JsonResponse(['success' => trans('admin.successfully_edit_voucher')]);
        }

        return new JsonResponse(['error' => $validator->errors()->all()]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addVoucher(Request $request): JsonResponse
    {
        $validator = $this->validatorVoucher($request->all());
        $code = $request->input('code', str_random(14));

        if ($validator->passes()) {
            try {
                $params = [
                    'code' => $code,
                ];
                if ($request->input('type') === 'percent') {
                    $params['percent'] = $request->input('sale');
                } elseif ($request->input('type') === 'amount') {
                    $params['amount'] = $request->input('sale');
                } else {
                    throw new \Exception('Creation error');
                }

                $this->voucher->create($params);
            } catch (\Exception $e) {
//                return new JsonResponse(['error' => 'Duplicate code']);
                return new JsonResponse(['error' => $e->getMessage()]);
            }

            return new JsonResponse(['success' => trans('admin.successfully_new_voucher')]);
        }

        return new JsonResponse(['error' => $validator->errors()->all()]);
    }

    /**
     * Get a validator for an incoming edit and save Voucher request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorVoucher(array $data)
    {
        return Validator::make($data, [
                'sale' => 'required|numeric'
            ]
        );
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Validation\Validator
     */
    protected function validatorPlan(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'price' => 'required|numeric',
            'storage' => 'required|numeric',
            'video_storage' => 'required|numeric',
            'interval' => 'required',
        ]);
    }


    public function VouchersDelete(Voucher $id)
    {
        try {
            $vaucher = $this->voucher->delete($id->id);
            Session::flash('message', 'Successfully Voucher deleted!');
            return redirect()->route('admin.product');
        } catch (\Exception $e) {
            Session::flash('message', 'An error has occurred');
            return redirect()->route('admin.product');
        }
    }
}