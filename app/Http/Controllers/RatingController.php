<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Vendor;
use App\Models\VendorRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $vendor_id = auth()->user()->user_ref_id;

        $vendor_data = Vendor::where('id', $vendor_id)->first();

        $start_1_count = Rating::whereHas('product', function($query) use ($vendor_id) {
                                    $query->where('vendor_id',$vendor_id);
                                })
                               ->where('rating',1)
                               ->count();
        $start_2_count = Rating::whereHas('product', function($query) use ($vendor_id) {
                                    $query->where('vendor_id',$vendor_id);
                                })
                               ->where('rating',2)
                               ->count();
        $start_3_count = Rating::whereHas('product', function($query) use ($vendor_id) {
                                    $query->where('vendor_id',$vendor_id);
                                })
                               ->where('rating',3)
                               ->count();
        $start_4_count = Rating::whereHas('product', function($query) use ($vendor_id) {
                                    $query->where('vendor_id',$vendor_id);
                                })
                               ->where('rating',4)
                               ->count();
        $start_5_count = Rating::whereHas('product', function($query) use ($vendor_id) {
                                    $query->where('vendor_id',$vendor_id);
                                })
                               ->where('rating',5)
                               ->count();
        $total_user_rated = $start_1_count+$start_2_count+$start_3_count+$start_4_count+$start_5_count;
        $sum_of_max_rating_of_user_count = $total_user_rated * 5;
        $sum_of_rating = ($start_1_count*1) + ($start_2_count*2) + ($start_3_count*3) + ($start_4_count*4) + ($start_5_count*5);
        $rating = empty($sum_of_max_rating_of_user_count)?0:round(($sum_of_rating*5)/$sum_of_max_rating_of_user_count,2);

        $rating_list = Rating::with(['product','order_detail','order_detail.customer','order_detail.customer.school'])
                             ->whereHas('product', function($query) use ($vendor_id) {
                                 $query->where('vendor_id',$vendor_id);
                             })
                             ->orderBy('created_at','DESC')
                             ->get();


        $start_1_count_vendor = VendorRating::where('vendor_id',$vendor_id)
            ->where('rating',1)
            ->count();
        $start_2_count_vendor = VendorRating::where('vendor_id',$vendor_id)
            ->where('rating',2)
            ->count();
        $start_3_count_vendor = VendorRating::where('vendor_id',$vendor_id)
            ->where('rating',3)
            ->count();
        $start_4_count_vendor = VendorRating::where('vendor_id',$vendor_id)
            ->where('rating',4)
            ->count();
        $start_5_count_vendor = VendorRating::where('vendor_id',$vendor_id)
            ->where('rating',5)
            ->count();

        $total_user_rated_vendor = $start_1_count_vendor+$start_2_count_vendor+$start_3_count_vendor+$start_4_count_vendor+$start_5_count_vendor;
        $sum_of_max_rating_of_user_count_vendor = $total_user_rated_vendor * 5;
        $sum_of_rating_vendor = ($start_1_count_vendor*1) + ($start_2_count_vendor*2) + ($start_3_count_vendor*3) + ($start_4_count_vendor*4) + ($start_5_count_vendor*5);
        $rating_vendor = empty($sum_of_max_rating_of_user_count_vendor)?0:round(($sum_of_rating_vendor*5)/$sum_of_max_rating_of_user_count_vendor,2);

        $rating_list_vendor = VendorRating::with(['order_detail','order_detail.customer','order_detail.customer.school'])
            ->where('vendor_id',$vendor_id)
            ->orderBy('created_at','DESC')
            ->get();

        $data = array(
            "vendor_data" => $vendor_data,
            "disk" => Storage::disk('gcs'),
            "start_1_count" => $start_1_count,
            "start_2_count" => $start_2_count,
            "start_3_count" => $start_3_count,
            "start_4_count" => $start_4_count,
            "start_5_count" => $start_5_count,
            "rating" => $rating,
            "rating_list" => $rating_list,
            "start_1_count_vendor" => $start_1_count_vendor,
            "start_2_count_vendor" => $start_2_count_vendor,
            "start_3_count_vendor" => $start_3_count_vendor,
            "start_4_count_vendor" => $start_4_count_vendor,
            "start_5_count_vendor" => $start_5_count_vendor,
            "rating_vendor" => $rating_vendor,
            "rating_list_vendor" => $rating_list_vendor
        );
        return view('pages.rating.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function show(Rating $rating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rating $rating)
    {
        //
    }
}
