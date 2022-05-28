<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use App\Payment;
use App\CompanyCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\AffiliteMarketing;
use App\AffiliteMarketingReportController;
use App\AffiliteMarketingClickReport;

class CartController extends Controller
{

    public function addToCart(Request $request){
        
        $course_id = $request->course_id;
        $course = Course::find($course_id);

        $cartData = (array) session('cart');
        $cartData[$course->id] = [
            'hash'              => str_random(),
            'course_id'         => $course->id,
            'title'             => $course->title,
            'price'             => $course->get_price,
            'original_price'    => $course->price,
            'price_plan'        => $course->price_plan,
            'course_url'        => route('course', $course->slug),
            'thumbnail'      => media_image_uri($course->thumbnail_id)->thumbnail,
            'price_html'      => $course->price_html(false),
        ];
        session(['cart' => $cartData]);
        if ( ! Auth::check()){
            if ($request->ajax()){
                return ['success' => 0, 'message' => 'unauthenticated'];
            }
            return redirect(route('login'));
        }


        $user = User::where('id',Auth::user()->id)->first();
        if ($user->company_coupon_id != null) {
            $company_coupon = CompanyCoupon::find($user->company_coupon_id);
            $cartData[$course->id] = [
                'hash' => str_random(),
                'course_id' => $course->id,
                'title' => $course->title,
                'price' => $company_coupon->price,
                'original_price' => $course->price,
                'price_plan' => $course->price_plan,
                'course_url' => route('course', $course->slug),
                'thumbnail' => media_image_uri($course->thumbnail_id)->thumbnail,
                'price_html' => $course->price_html(false),
            ];
        }

    
        if ($request->ajax()){
            return ['success' => 1, 'cart_html' => view_template_part('template-part.minicart') ];
        }

        if ($request->cart_btn === 'buy_now'){
            return redirect(route('checkout'));
        }
    }

    /**
     * @param Request $request
     * @return array
     *
     * Remove From Cart
     */
    public function removeCart(Request $request){
        $cartData = (array) session('cart');
        if (array_get($cartData, $request->cart_id)){
            unset($cartData[$request->cart_id]);
        }
        session(['cart' => $cartData]);
        return ['success' => 1, 'cart_html' => view_template_part('template-part.minicart') ];
    }

    public function checkout(Request $request){
        
        $session_link = session('session_link');
        if ($session_link == 1 ) 
        {    
            $cp_id          = session('cp_id_link');
            $session        = session('session_link');
            $id    = session('affilite_id');

            if (session('click_aff_id') != session('affilite_id') || session('click_cp_id') != session('cp_id_link')) 
            {   
                session(['cp_id_link' => $cp_id,'session_link' => $session,'affilite_id'=>$id]);
                session(['save_click_record_affilite' => 1,'click_aff_id' => session('affilite_id'),'click_cp_id' => session('cp_id_link')]);
                
                $affilite_click_record                  = new AffiliteMarketingClickReport();
                $affilite_click_record->cp_id           = session('cp_id_link');
                $affilite_click_record->student_link    = Auth::user()->id;
                $affilite_click_record->affiliate_id    = session('affilite_id');
                $affilite_click_record->save();
            }
        }

        $title = __('checkout');
        return view(theme('checkout'), compact('title'));
    }





}