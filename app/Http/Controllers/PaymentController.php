<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use App\User;
use App\Enroll;
use App\Course;
use App\Earning;
use App\Coupon;
use App\channelPartnerEarning;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    public function index(Request $request){
        $ids = $request->bulk_ids;

        //Update
        if ($request->bulk_action_btn === 'update_status' && $request->status && is_array($ids) && count($ids)){
            foreach ($ids as $id){
                Payment::find($id)->save_and_sync(['status' => $request->status]);
            }

            return back()->with('success', __a('bulk_action_success'));
        }
        //Delete
        if ($request->bulk_action_btn === 'delete' && is_array($ids) && count($ids)){
            if(config('app.is_demo')) return back()->with('error', __a('demo_restriction'));

            foreach ($ids as $id){
                Payment::find($id)->delete_and_sync();
            }
            return back()->with('success', __a('bulk_action_success'));
        }
        //END Bulk Actions

        $title = __a('payments');

        $payments = Payment::query();
        if ($request->q){
            $payments = $payments->where(function($q)use($request) {
                $q->where('name', 'like', "%{$request->q}%")
                    ->orWhere('email', 'like', "%{$request->q}%");
            });
        }
        if ($request->filter_status){
            $payments = $payments->where('status', $request->filter_status);
        }
        $payments = $payments->orderBy('id', 'desc')->paginate(20);

        return view('admin.payments.payments', compact('title', 'payments'));
    }

    public function view($id){
        $title = __a('payment_details');
        $payment = Payment::find($id);
        return view('admin.payments.payment_view', compact('title', 'payment'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * Delete the Payment
     */
    public function delete($id){
        if(config('app.is_demo')) return back()->with('error', __a('demo_restriction'));

        $payment = Payment::find($id);
        if ($payment){
            $payment->delete_and_sync();
        }
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * Update the payment status, and it's related data
     */

    public function updateStatus(Request $request, $id){
        $payment = Payment::find($id);
        if ($payment){
            $payment->status = $request->status;
            $payment->save_and_sync();
        }

        return back()->with('success', __a('success'));
    }

    public function PaymentGateways(){
        $title = __a('payment_settings');
        return view('admin.payments.gateways.payment_gateways', compact('title'));
    }

    public function PaymentSettings(){
        $title = __a('payment_settings');
        return view('admin.payments.gateways.payment_settings', compact('title'));
    }

    public function thankYou(){
        session(['cart' => []]);
        $title = __t('payment_thank_you');
        return view(theme('payment-thank-you'), compact('title'));
    }

    public function thankYouPost($transction_id,$user_id,$courses,$erning,$coupons = null,$coure_affilite_marketing = null,$amount,$razorpay_signature=null){
        
        $user = Auth::loginUsingId($user_id);
        $user_data = User::where('id',$user_id)->first();

        $payment['name'] = $user_data->name;
        $payment['email'] = $user_data->email;
        $payment['user_id'] = $user_data->id;
        $payment['amount'] = $amount;
        $payment['payment_method'] = 'cashfree';
        $payment['status'] = 'success';
        $payment['currency'] = 'INR';
        $payment['local_transaction_id'] = $transction_id;
        $payment['charge_id_or_token'] = $transction_id;
        
        $insertPayment = Payment::insertGetId($payment);
        $erning_array = explode ("_", $erning);
        $count = 0;
        $coupon_array = [];
        $affilite_marketing_array = [];
        if($coupons != '0') {
            foreach (explode ("_", $coupons) as $value) {
                $temp_coupon = explode ("-", $value);
                $coupon_array[$temp_coupon[1]] = $temp_coupon[0];
            }
        }
        
        if($coure_affilite_marketing != 0)
        {
            $value = explode ("_", $coure_affilite_marketing);
            $affilite_marketing_array[$value[0]] = $value[1];
            
        }
        
        
        foreach (explode ("_", $courses) as $value) {

                    $affilite_marketing_is_instructor = 0;
                    // get course details
                    $Course = Course::where('id',$value)->first();
                    $apply_coupon = '';
                    if (count($coupon_array) > 0) {
                        $apply_coupon = $coupon_array[$value];
                    }
                    
                    if($coure_affilite_marketing == 0){
                        $coupon = Coupon::where('code',$apply_coupon)->first();
                        if($coupon != null && $coupon->channel_partner != null){
                            $insertchannelPartner = channelPartnerEarning::create([
                                'user_id' => $coupon->channel_partner,
                                'student_id' => Auth::user()->id,
                                'course_id' => $Course->id,
                                'coupon_code' => $apply_coupon,
                                'amount' => $erning_array[$count],
                                'partnership_amount' => $erning_array[$count] * ($coupon->percentage/100)
                            ]);
                        }
                    }

                    $instructor_amount = $erning_array[$count] * 0.50;
                    $admin_amount = $erning_array[$count] * 0.50;

                    $instructor_share = 50.00;
                    $admin_share = 50.00;
                    if (isset($affilite_marketing_array[$value])) 
                    {   
                        $cp_admin_amount = $erning_array[$count];
                        if(Auth::user()->apply_reference_code != NULL)
                        {  
                            $referance_user_old = User::where('id',Auth::user()->apply_reference_code)->orwhere('channel_partner_code', Auth::user()->apply_reference_code)->first();

                            $referance_user = User::where('id',$affilite_marketing_array[$value])->first();
                            
                            if ($referance_user_old->id != $affilite_marketing_array[$value]) 
                            {
                                $insertchannelPartner = channelPartnerEarning::create([
                                    'user_id' => $referance_user->id,
                                    'student_id' => Auth::user()->id,
                                    'course_id' => $Course->id,
                                    'coupon_code' => $apply_coupon,
                                    'amount' => $erning_array[$count],
                                    'partnership_amount' => $cp_admin_amount * ($referance_user->channel_partner_per/100)
                                ]);
                            }

                        }
                        else
                        {

                            $referance_user = User::where('id',$affilite_marketing_array[$value])->first();
                            $insertchannelPartner = channelPartnerEarning::create([
                                'user_id' => $referance_user->id,
                                'student_id' => Auth::user()->id,
                                'course_id' => $Course->id,
                                'coupon_code' => $apply_coupon,
                                'amount' => $erning_array[$count],
                                'partnership_amount' => $cp_admin_amount * ($referance_user->channel_partner_per/100)
                            ]);

                            $affilite_marketing_is_instructor = $referance_user->id;
                        }
                    }

                    if ($Course->user_id == $affilite_marketing_is_instructor) {
                        $instructor_amount = $erning_array[$count] * 0.75;
                        $admin_amount = $erning_array[$count] * 0.25;

                        $instructor_share = 75.00;
                        $admin_share = 25.00;
                    }
                    
                    if(Auth::user()->apply_reference_code != NULL){
                        $referance_user = User::where('id',Auth::user()->apply_reference_code)->orwhere('channel_partner_code', Auth::user()->apply_reference_code)->first();

                        if($Course->user_id == Auth::user()->apply_reference_code ){
                            $instructor_amount = $erning_array[$count] * 0.75;
                            $admin_amount = $erning_array[$count] * 0.25;

                            $instructor_share = 75.00;
                            $admin_share = 25.00;
                        }else if($referance_user->user_type == 'admin'){
                            $instructor_amount = $erning_array[$count] * 0.25;
                            $admin_amount = $erning_array[$count] * 0.75;

                            $instructor_share = 25.00;
                            $admin_share = 75.00;
                        }else if($referance_user->user_type == 'channel_partner'){
                            $cp_admin_amount = $erning_array[$count];
                            $insertchannelPartner = channelPartnerEarning::create([
                                'user_id' => $referance_user->id,
                                'student_id' => Auth::user()->id,
                                'course_id' => $Course->id,
                                'coupon_code' => $apply_coupon,
                                'amount' => $erning_array[$count],
                                'partnership_amount' => $cp_admin_amount * ($referance_user->channel_partner_per/100)
                            ]);
                        }
                    }

                    // enter in erning table
                    $edata['instructor_id'] = $Course->user_id;
                    $edata['course_id'] = $value;
                    $edata['payment_id'] = $insertPayment;
                    $edata['payment_status'] = 'success';
                    $edata['amount'] = $erning_array[$count];
                    $edata['instructor_amount'] = $instructor_amount;
                    $edata['admin_amount'] = $admin_amount;
                    $edata['instructor_share'] = $instructor_share;
                    $edata['admin_share'] = $admin_share;
                    $edata['coupon'] = $apply_coupon;

                    $erning = Earning::insert($edata);
                    $carbon = Carbon::now()->toDateTimeString();

                    // Enroll::insert(
                    //     ['course_id' => $value, 'user_id' => $user_data->id,'course_price' => $_POST['orderAmount'], 'payment_id' => $insertPayment, 'status' => 'success', 'enrolled_at' => $carbon ]
                    // );

                    $isEnrolled = $user->isEnrolled($value);

                    if ( ! $isEnrolled){
                        $carbon = Carbon::now()->toDateTimeString();
                        $user->enrolls()->attach($value, ['status' => 'success','course_price' =>  $erning_array[$count], 'payment_id' => $insertPayment, 'enrolled_at' => $carbon ]);
                        //$user->enroll_sync();
                    }
                    
                    $count++;
                }

        session(['cart' => []]);
        $title = __t('payment_thank_you');
        return view(theme('payment-thank-you'), compact('title'));

    }

}