<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Charge;
use Stripe\Exception\CardException;
use Stripe\Stripe;

class GatewayController extends Controller
{

    /**
     * @param Request $request
     * @return array
     * @throws \Stripe\Exception\ApiErrorException
     *
     * Stripe Charge
     */
    public function stripeCharge(Request $request){
        $stripeToken = $request->stripeToken;
        Stripe::setApiKey(get_stripe_key('secret'));

        // Create the charge on Stripe's servers - this will charge the user's card
        try {
            $cart = cart();
            $amount = $cart->total_amount;
            $user = Auth::user();

            $currency = get_option('currency_sign');

            //Charge from card
            $charge = Charge::create(array(
                "amount"        => get_stripe_amount($amount), // amount in cents, again
                "currency"      => $currency,
                "source"        => $stripeToken,
                "description"   => get_option('site_name')."'s course enrolment"
            ));



            if ($charge->status == 'succeeded'){
                //Save payment into database
                $data = [
                    'name'              => $user->name,
                    'email'             => $user->email,
                    'user_id'           => $user->id,
                    'amount'            => $cart->total_price,
                    'payment_method'        => 'stripe',
                    'total_amount'      => $charge->amount,

                    'currency'              => $currency,
                    'charge_id_or_token'    => $charge->id,
                    'description'           => $charge->description,
                    'payment_created'       => $charge->created,

                    //Card Info
                    'card_last4'        => $charge->source->last4,
                    'card_id'           => $charge->source->id,
                    'card_brand'        => $charge->source->brand,
                    'card_country'      => $charge->source->US,
                    'card_exp_month'    => $charge->source->exp_month,
                    'card_exp_year'     => $charge->source->exp_year,

                    'status'                    => 'success',
                ];

                Payment::create_and_sync($data);
                $request->session()->forget('cart');

                return ['success'=> 1, 'message_html' => $this->payment_success_html()];
            }
        } catch(CardException $e) {
            // The card has been declined
            return ['success'=>0, 'msg'=> __t('payment_declined_msg'), 'response' => $e];
        }
    }

    public function payment_success_html(){
        $html = ' <div class="payment-received text-center">
                            <h1> <i class="fa fa-check-circle-o"></i> '.__t('payment_thank_you').'</h1>
                            <p>'.__t('payment_receive_successfully').'</p>
                            <a href="'.route('home').'" class="btn btn-dark">'.__t('home').'</a>
                        </div>';
        return $html;
    }



    public function bankPost(Request $request){
        $cart = cart();
        $amount = $cart->total_amount;

        $user = Auth::user();
        $currency = get_option('currency_sign');

        //Create payment in database
        $transaction_id = 'tran_'.time().str_random(6);
        // get unique recharge transaction id
        while( ( Payment::whereLocalTransactionId($transaction_id)->count() ) > 0) {
            $transaction_id = 'reid'.time().str_random(5);
        }
        $transaction_id = strtoupper($transaction_id);

        $payments_data = [
            'name'                  => $user->name,
            'email'                 => $user->email,
            'user_id'               => $user->id,
            'amount'                => $amount,
            'payment_method'        => 'bank_transfer',
            'status'                => 'pending',
            'currency'              => $currency,
            'local_transaction_id'  => $transaction_id,

            'bank_swift_code'       => clean_html($request->bank_swift_code),
            'account_number'        => clean_html($request->account_number),
            'branch_name'           => clean_html($request->branch_name),
            'branch_address'        => clean_html($request->branch_address),
            'account_name'          => clean_html($request->account_name),
            'iban'                  => clean_html($request->iban),
        ];
        //Create payment and clear it from session
        Payment::create_and_sync($payments_data);

        $request->session()->forget('cart');

        return redirect(route('payment_thank_you_page'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * Redirect to PayPal for the Payment
     */
    public function paypalRedirect(Request $request){
        if ( ! session('cart')){
            return redirect(route('checkout'));
        }

        $cart = cart();
        $amount = $cart->total_amount;

        $user = Auth::user();
        $currency = get_option('currency_sign');

        //Create payment in database
        $transaction_id = 'tran_'.time().str_random(6);
        // get unique recharge transaction id
        while( ( Payment::whereLocalTransactionId($transaction_id)->count() ) > 0) {
            $transaction_id = 'reid'.time().str_random(5);
        }
        $transaction_id = strtoupper($transaction_id);

        $payments_data = [
            'name'                  => $user->name,
            'email'                 => $user->email,
            'user_id'               => $user->id,
            'amount'                => $amount,
            'payment_method'        => 'paypal',
            'status'                => 'initial',
            'currency'              => $currency,
            'local_transaction_id'  => $transaction_id,
        ];
        //Create payment and clear it from session
        $payment = Payment::create_and_sync($payments_data);
        $request->session()->forget('cart');

        // PayPal settings
        $paypal_action_url = "https://www.paypal.com/cgi-bin/webscr";
        if (get_option('enable_paypal_sandbox'))
            $paypal_action_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

        $paypal_email = get_option('paypal_receiver_email');
        $return_url = route('payment_thank_you_page', $transaction_id);
        $cancel_url = route('checkout');
        $notify_url = route('paypal_notify', $transaction_id);

        $item_name = get_option('site_name')."'s course enrolment";

        $querystring = '';
        // Firstly Append paypal account to querystring
        $querystring .= "?cmd=_xclick&business=".urlencode($paypal_email)."&";
        $querystring .= "item_name=".urlencode($item_name)."&";
        $querystring .= "amount=".urlencode($amount)."&";
        $querystring .= "currency_code=".urlencode($currency)."&";
        $querystring .= "item_number=".urlencode($payment->local_transaction_id)."&";
        // Append paypal return addresses
        $querystring .= "return=".urlencode(stripslashes($return_url))."&";
        $querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
        $querystring .= "notify_url=".urlencode($notify_url);

        // Redirect to paypal IPN
        $URL = $paypal_action_url.$querystring;
        return redirect($URL);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * Redirect to cashfree for the Payment
     */
    public function cashfreeRedirect(Request $request){
        if ( ! session('cart')){
            return redirect(route('checkout'));
        }

        $cart = cart();
        $amount = $cart->total_amount;

        $course_id = '';
        $erning = '';
        $coupons = 0;
        $count = 0;
        
        $coure_affilite_marketing = '0';
        if (session('session_link')) 
        {   
            $coure_affilite_marketing = session('cp_course_id').'_'.session('cp_id_link');
        }
        
        foreach ($cart->courses as $key => $value) {

            if($count == 0){
                $course_id = $course_id . $key;
                $erning = $erning.$value['price'];
            }else{
                $course_id = $course_id .'_'. $key;
                $erning = $erning .'_'.$value['price'];
            }

            if ($count == 0 && isset($value['coupon_code'])){
                if ($coupons == 0) {
                    $coupons = '';
                }
                $coupons = $coupons.''.$value['coupon_code'].'-'.$value['course_id'];
            }else if(isset($value['coupon_code']))
            {   
                if ($coupons == 0) {
                    $coupons = '';
                }
                $coupons = $coupons.'_'.$value['coupon_code'].'-'.$value['course_id'];
            }
            $count++; 
            
        }
        
        $user = Auth::user();
        $currency = get_option('currency_sign');

        //Create payment in database
        $transaction_id = 'tran_'.time().str_random(6);
        // get unique recharge transaction id
        while( ( Payment::whereLocalTransactionId($transaction_id)->count() ) > 0) {
            $transaction_id = 'reid'.time().str_random(5);
        }
        $transaction_id = strtoupper($transaction_id);

        $payments_data = [
            'name'                  => $user->name,
            'email'                 => $user->email,
            'user_id'               => $user->id,
            'amount'                => $amount,
            'payment_method'        => 'cashfree',
            'status'                => 'initial',
            'currency'              => $currency,
            'local_transaction_id'  => $transaction_id,
        ];
        $return_url = 'https://www.machinfini.com/payment-thank-you-post/'.$transaction_id.'/'.$user->id.'/'.$course_id.'/'.$erning.'/'.$coupons.'/'.$coure_affilite_marketing;
        $notify_url = $return_url;
        // local
        $return_url = 'http://127.0.0.1:8000/payment-thank-you-post/'.$transaction_id.'/'.$user->id.'/'.$course_id.'/'.$erning.'/'.$coupons.'/'.$coure_affilite_marketing;
        $notify_url = $return_url;
        $cancel_url = route('checkout');
        

        $item_name = get_option('site_name')."'s course enrolment";
        
        $appId = "77853c478389162fc9165507c35877";
        $secretKey = "be62524201023bd663ad567078ca82d8ce00243f";

        $appId = "32771fc8aa73478fbf74e556517723";
        $secretKey = "2f4e5dd3fd7b451a3120bac18dbd8b881e7c1109";

        $paymentModes = ""; //keep it blank to display all supported modes
        $tokenData = "appId=" . $appId . "&orderId=" . $transaction_id . "&orderAmount=" . $amount . "&returnUrl=" . $return_url . "&paymentModes=" . $paymentModes;
        $token = hash_hmac('sha256', $tokenData, $secretKey, true);
        $paymentToken = base64_encode($token);

        ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashfree</title>
    <style>
    #payment-body {
        padding-bottom: 70px;
        background-color: #0a88ff;
        padding-top: 65px;
        height: -webkit-fill-available;
    }
    </style>
</head>

<body>

    <div id="payment-body">
        <div id="payment-div"></div>
    </div>

    <script src="https://www.cashfree.com/assets/cashfree.sdk.v1.2.js" type="text/javascript"></script>
    <script type="text/javascript">
    (function() {

        var data = {};
        data.orderId = "<?= $transaction_id; ?>";
        data.orderAmount = <?= $amount; ?>;
        data.customerName = "Machinfini Pvt Ltd";
        data.customerPhone = "81697 43615";
        data.customerEmail = "machinfini@gmail.com";
        data.returnUrl = '<?= $return_url; ?>';
        data.notifyUrl = '<?= $return_url; ?>'; //Not using
        data.appId = "<?= $appId; ?>";
        data.paymentToken = "<?= $paymentToken; ?>";

        console.log(data);

        var callback = function(event) {
            var eventName = event.name;
            switch (eventName) {
                case "PAYMENT_REQUEST":
                    console.log(event.message);
                    console.log("error");
                    break;
                default:
                    console.log(event.message);

            };
        }

        var config = {};
        config.layout = {
            view: "inline",
            container: "payment-div",
            width: "800px"
        };
        config.mode = "PROD";
        config.mode = "TEST";
        var response = CashFree.init(config);
        if (response.status == "OK") {
            CashFree.makePayment(data, callback);
        } else {
            //handle error
            // CashFree.makePayment(data, callback);
            console.log("error");
            console.log(response.message);
        }

    })();
    </script>

</body>

</html>


<?php 
    }


    public function payOffline(Request $request){
        $cart = cart();
        $amount = $cart->total_amount;

        $user = Auth::user();
        $currency = get_option('currency_sign');

        //Create payment in database
        $transaction_id = 'tran_'.time().str_random(6);
        // get unique recharge transaction id
        while( ( Payment::whereLocalTransactionId($transaction_id)->count() ) > 0) {
            $transaction_id = 'reid'.time().str_random(5);
        }
        $transaction_id = strtoupper($transaction_id);

        $payments_data = [
            'name'                  => $user->name,
            'email'                 => $user->email,
            'user_id'               => $user->id,
            'amount'                => $amount,
            'payment_method'        => 'offline',
            'status'                => 'onhold',
            'currency'              => $currency,
            'local_transaction_id'  => $transaction_id,
            'payment_note'          => clean_html($request->payment_note),
        ];
        //Create payment and clear it from session
        Payment::create_and_sync($payments_data);
        $request->session()->forget('cart');

        return redirect(route('payment_thank_you_page'));
    }

}