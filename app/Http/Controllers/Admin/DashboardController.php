<?php

namespace App\Http\Controllers\Admin;

use AdminSection;
use App\Http\Controllers\Controller;
use App\Models\Creative;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Share;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Display\Column\Link;
use SleepingOwl\Admin\Display\Column\Text;
use SleepingOwl\Admin\Display\DisplayDatatablesAsync;

/**
 * Class CreativeController
 *
 * @package App\Http\Controllers\Admin
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $countCreative = Creative::count();
        $countBrand = Brand::count();
        $loginsUser = User::whereNotNull('last_login')
            ->orderBy('last_login', 'desc')->limit(10)->get();

        $uploadsActivity = Activity::whereIn('type',
            [Activity::UPLOAD_MEDIA_TYPE, Activity::EDIT_MEDIA_TYPE, Activity::DELETE_MEDIA_TYPE])
            ->orderBy('created_at', 'DECS')->limit(10)->get();

        $activities = Activity::orderBy('created_at', 'DESC')->limit(10)->get();
        $shares = (new Share)->orderBy('created_at', 'DESC')->limit(10)->get();
        $products = Product::all();

        return AdminSection::view(view('admin.dashboard', [
            'countCreative' => $countCreative,
            'countBrand' => $countBrand,
            'loginsUser' => $loginsUser,
            'uploads' => $uploadsActivity,
            'shares' => $shares,
            'products' => $products,
            'activities' => $activities,
        ]));
    }
}