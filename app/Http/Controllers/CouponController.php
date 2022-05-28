<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Course;
use App\Coupon;
use App\Model\Company;
use App\Model\CompanyDepartment;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Rules\couponValidation;

use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        // update coupon code
        $update_coupon = Coupon::where('to_date','<',date('Y-m-d'))->update(['status' => 0]);

        $user = User::where('user_type','instructor')->get();
        $channel_partner = User::where('user_type','channel_partner')->get();
        $course = Course::where('status',1)->get();
        $company = Company::where('status',1)->get();
        $coupons = Coupon::where('is_deleted',0)->get();
        return view('admin.coupon', compact('user', 'course','coupons','channel_partner','company'));
    }
    
    public function cpIndex()
    {   
        // update coupon code
        $update_coupon = Coupon::where('to_date','<',date('Y-m-d'))->update(['status' => 0]);

        $user = User::where('user_type','instructor')->get();
        $channel_partner = User::where('user_type','channel_partner')->get();
        $course = Course::where('status',1)->get();
        $company = Company::where('status',1)->get();
        $coupons = Coupon::where('channel_partner',Auth::user()->id)->where('is_deleted',0)->get();
        return view('channelPartner.coupon', compact('user', 'course','coupons','channel_partner','company'));
    }

    public function getDepartment(Request $request){
        $getDepartments = CompanyDepartment::where('company_id',$request->id)->get();
        $html ='<option value="" style="display:none;">Select Department</option>';
        foreach ($getDepartments as $key => $getDepartment) {
            $html .= '<option value="'.$getDepartment->id.'">'.$getDepartment->name.'</option>';
        }

        echo $html;
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
        $rules = [
            'name' => 'required|max:255',
            'code' => [
                        'required', new couponValidation
                    ],
            'percentage' => 'required',
            'set_for' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ];

        $this->validate($request, $rules);
        
        $data = [
            'name'=> $request->name,
            'code' => $request->code,
            'percentage' => $request->percentage,
            'set_for' =>$request->set_for,
            'instructor_id' =>$request->instructor_name,
            'course_id' => $request->course_name,
            'channel_partner' => $request->channel_partner,
            'company_id' => $request->company,
            'department_id' => $request->department,
            'company_score' => $request->company_score,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
        ];
        $insertCoupon  = Coupon::insert($data);
        return redirect::back();
    }
    
    public function cpStore(Request $request)
    {   
        $rules = [
            'name' => 'required|max:255',
            'code' => [
                        'required','regex:/^[a-zA-Z0-9]+$/u', new couponValidation
                    ],
            'percentage' => 'required|numeric|lte:50|gte:1',
            'from_date' => 'required',
            'to_date' => 'required',
        ];

        $this->validate($request, $rules);
        
        $data = [
            'name'=> $request->name,
            'code' => 'MI'.Auth::user()->id.$request->code,
            'percentage' => $request->percentage,
            'set_for' =>'all',
            'channel_partner' => Auth::user()->id,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
        ];
        $insertCoupon  = Coupon::insert($data);
        return redirect::back();
    }
    
    public function delete(Request $request)
    {	
        $update_coupon = Coupon::where('id',$request->id)->update(['status' => 0,'is_deleted' => 1]);
        return redirect()->route('cp_coupons')->with('message', 'Coupon deleted successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('code',$request->coupon_code)->where('status',1)->first();
        
        if(!empty($coupon)){
            
            if (strtotime(date('Y-m-d')) >= strtotime($coupon->from_date) && strtotime(date('Y-m-d')) <= strtotime($coupon->to_date)) {
                $precent = $coupon->percentage / 100;
                $new_payment_amount = $request->course_price - $request->course_price * $precent;
            
                if($coupon->set_for == 'all'){

                    $data = (array) session('cart');

                    foreach ($data as $key => $value) {
                        if($key == $request->course_id){
                            $value['price'] = $new_payment_amount;
                            $value['coupon_code'] = $request->coupon_code;
                        }
                        $data[$key] = $value;
                    
                    }

                    session(['cart' => $data]);

                    return redirect()->back()->with('message', 'Coupon applyed !');

                }else if($coupon->set_for == 'instructor'){

                    $course_instructor = Course::where('id',$request->course_id)->first();
                
                    if($course_instructor->user_id == $coupon->instructor_id){
                        
                        $data = (array) session('cart');

                        foreach ($data as $key => $value) {
                            if($key == $request->course_id){
                                $value['price'] = $new_payment_amount;
                                $value['coupon_code'] = $request->coupon_code;
                            }
                            $data[$key] = $value;
                        
                        }

                        session(['cart' => $data]);

                        return redirect()->back()->with('message', 'Coupon applyed !');

                    }else{
                        return redirect()->back()->with('error_message', 'In-valid coupon !');
                    }
                    
                }else if($coupon->set_for == 'course'){

                    $course_instructor = Course::where('id',$request->course_id)->first();
                    
                    if($course_instructor->id == $coupon->course_id){
                        
                        $data = (array) session('cart');

                        foreach ($data as $key => $value) {
                            if($key == $request->course_id){
                                $value['price'] = $new_payment_amount;
                                $value['coupon_code'] = $request->coupon_code;
                            }
                            $data[$key] = $value;
                        
                        }

                        session(['cart' => $data]);

                        return redirect()->back()->with('message', 'Coupon applyed !');

                    }else{
                        return redirect()->back()->with('error_message', 'In-valid coupon !');
                    }
                    
                }else if($coupon->set_for == 'channel_partner'){
                    $user = User::where('id',Auth::user()->id)->first();
                    
                    if ($user->apply_reference_code == $coupon->channel_partner) {
                        $data = (array) session('cart');

                        foreach ($data as $key => $value) {
                            if($key == $request->course_id){
                                $value['price'] = $new_payment_amount;
                                $value['coupon_code'] = $request->coupon_code;
                            }
                            $data[$key] = $value;
                        
                        }

                        session(['cart' => $data]);

                        return redirect()->back()->with('message', 'Coupon applyed !');
                    }else{
                        return redirect()->back()->with('error_message', 'In-valid coupon !');
                    }
                }else if($coupon->set_for == 'company'){
                    $user = User::where('id',Auth::user()->id)->first();
                    
                    if ($user->company_id == $coupon->company_id) {
                        $data = (array) session('cart');

                        foreach ($data as $key => $value) {
                            if($key == $request->course_id){
                                $value['price'] = $new_payment_amount;
                                $value['coupon_code'] = $request->coupon_code;
                            }
                            $data[$key] = $value;
                        
                        }

                        session(['cart' => $data]);

                        return redirect()->back()->with('message', 'Coupon applyed !');
                    }else{
                        return redirect()->back()->with('error_message', 'In-valid coupon !');
                    }
                }
            }else{
                return redirect()->back()->with('error_message', 'In-valid coupon!');
            }

        }else{
            return redirect()->back()->with('error_message', 'In-valid coupon!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelCoupon($id)
    {
        $data = (array) session('cart');
        foreach ($data as $key => $value) {
            if($key == $id){
                
                $coupon = Coupon::where('code',$value['coupon_code'])->first();
                $precent = $coupon->percentage;
                $new_payment_amount = ($value['price'] * 100) / (100 - $precent) ;
                
                $value['price'] = $new_payment_amount;
                unset($value['coupon_code']);
                // $value['coupon_code'] = $request->coupon_code;
            }
            $data[$key] = $value;
           
        }
        session(['cart' => $data]);

        return redirect()->back()->with('message', 'Coupon removed !');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}