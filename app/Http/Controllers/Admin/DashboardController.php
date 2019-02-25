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
use Response;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Display\Column\Link;
use SleepingOwl\Admin\Display\Column\Text;
use SleepingOwl\Admin\Display\DisplayDatatablesAsync;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function exportNewsletter()
    {

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $users = User::where('agree_newsletter', '!=', null)->get();

        $columns = array('Email Address', 'Title', 'First Name', 'Last Name', 'Brand Name');

        $callback = function () use ($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                $brand = $user->brand;
                $creative = $user->creative;
                if ($brand) {
                    fputcsv($file, array(
                        $user->email,
                        $brand->contact_title,
                        $brand->contact_first_name,
                        $brand->contact_last_name,
                        $brand->brand_name
                    ));
                } elseif ($creative) {
                    fputcsv($file, array(
                        $user->email,
                        '',
                        $creative->first_name,
                        $creative->last_name,
                        ''
                    ));
                }


            }
            fclose($file);
        };

        return (new StreamedResponse($callback, 200, $headers))->sendContent();
    }
}