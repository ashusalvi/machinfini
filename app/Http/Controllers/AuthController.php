<?php

namespace App\Http\Controllers;

use App\Mail\SendPasswordResetLink;
use App\User;
use App\CompanyCoupon;
use App\ICCoupon;
use App\Model\CompanyEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Session;

class AuthController extends Controller
{

    public function login(Request $request,$code = NULL){
        if ($code != NULL) {
            // $decrypted_code = decrypt($code);
            $user_code = User::where('reference_code',$code)->with('channelPartner')->first();
            if (!empty($user_code) > 0)
            {   
                $partner_logo = $user_code->channelPartner->logo;
                session(['ref_code' => $code]);
                if ($partner_logo != '' || $partner_logo != null) {
                    session(['partner_logo' => $partner_logo]);
                }
            }
        }
        
        $title = __t('login');
        return view_template('login', compact('title'));
    }

    public function loginPost(Request $request){
      
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $this->validate($request, $rules);

        $credential = [
            'email'     => $request->email,
            'password'     => $request->password
        ];

        if ( Auth::attempt($credential, $request->remember_me)){
            $auth = Auth::user();
            
            // if ($auth->user_type == 'company-admin') {
            //   return redirect(route('companyAdminDashboard'));
            // }

            // if ($auth->user_type == 'company-instructor') {
            //     return redirect(route('companyInstructorDashboard'));
            // }

            // if ($auth->user_type == 'company-employee') {
            //     return redirect(route('companyEmployeeDashboard'));
            // }

            if (session('cp_id_link') != NULL) 
            {   
                $redirect_url = 'courses/'.session('cp_slug').'/'.session('affilite_id').'/'.session('cp_id_link').'/'.session('session_link');
                
                return redirect()->intended(route('course',[session('cp_slug'),session('affilite_id'),session('cp_id_link'),session('session_link')]));
            }
            
            if(!empty(Auth::user()->referancechannelPartner)){
                $partner_logo = Auth::user()->referancechannelPartner->logo;
                if ($partner_logo != '' || $partner_logo != null) {
                session(['partner_logo' => $partner_logo]);
                }
            }

            if (Auth::user()->reference_code != null ) {
                $partner_logo = Auth::user()->channelPartner->logo;
                if ($partner_logo != '' || $partner_logo != null) {
                    session(['partner_logo' => $partner_logo]);
                }
            }
            
            if ($request->_redirect_back_to ){
                return redirect($request->_redirect_back_to);
            }

            if ($auth->isAdmin()){
                return redirect()->intended(route('admin'));
            }else{
                return redirect()->intended(route('dashboard'));
            }
        }

       

        return redirect()->back()->with('error', __t('login_failed'))->withInput($request->input());
    }


    public function register(){
        $title = __t('signup');
        return view_template('register', compact('title'));
    }

    public function registerPost(Request $request){
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'reference_code' => [
                Rule::exists('users')->where(function ($query) use ($request) {
                    $query->where('reference_code', $request->reference_code)->orWhere('channel_partner_code', $request->reference_code);
                }),
            ],
            'code' => [
                Rule::exists('company_coupon')->where('code', $request->code),'nullable'
            ],
        ];

        $this->validate($request, $rules);
        
        $ref_code = '';
        if(Session::has('ref_code')) {
            $ref_code = session('ref_code');
        }else{
            if($request->reference_code != ''){
                $ref_code = $request->reference_code;
            }
        }

        $instructor_id = NULL;
        if($ref_code != ''){
            $user_id = User::where('reference_code',$ref_code)->orWhere('channel_partner_code', $ref_code)->first();
            $instructor_id  = $user_id->id;
        }
        
        $rand = NULL;
        if($request->user_as == 'instructor'){
            $rand = substr(md5(microtime()),rand(0,26),6);
            $instructor_id = NULL;
        }

        $created_user_array = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->number,
            'date_of_birth' => $request->date,
            'user_type' => $request->user_as,
            'active_status' => 1,
            'apply_reference_code' => $instructor_id,
            'reference_code' => $rand,
        ];

        if ($request->code != '') {
            $company_coupon = CompanyCoupon::where('code',$request->code)->first();
            $company_coupon_id = $company_coupon->id;
            $created_user_array = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->number,
                'date_of_birth' => $request->date,
                'active_status' => 1,
                'apply_reference_code' => $instructor_id,
                'reference_code' => $rand,
                'user_type' => 'company-employee',
                'company_id' => $company_coupon->company_id,
                'company_coupon_id' => $company_coupon_id
            ];
        }

        $user = User::create($created_user_array);

        if ($request->code != '') {
            $employee = new CompanyEmployee;
            $employee->company_id = $company_coupon->company_id;
            $employee->department_id = $company_coupon->department;
            $employee->user_id = $user->id;
            $employee->employee_id = $user->id;
            $employee->save();
        }

        if ($user){
            $this->loginPost($request);
        }
        return back()->with('error', __t('failed_try_again'))->withInput($request->input());
    }

    public function logoutPost(){
        Auth::logout();
        Session::flush();
        return redirect('login');
    }

    public function forgotPassword(){
        $title = __t('forgot_password');
        return view(theme('auth.forgot_password'), compact('title'));
    }

    public function sendResetToken(Request $request){
        $this->validate($request, ['email' => 'required']);

        $email = $request->email;

        $user = User::whereEmail($email)->first();
        if ( ! $user){
            return back()->with('error', __t('email_not_found'));
        }

        $user->reset_token = str_random(32);
        $user->save();

        try {
            Mail::to($email)->send(new SendPasswordResetLink($user));
            return back()->with('success',  __t('Forgot password mail send success'));
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function passwordResetForm(){
        $title = __t('reset_your_password');
        return view(theme('auth.reset_form'), compact('title'));
    }

    public function passwordReset(Request $request, $token){
        if(config('app.is_demo')){
            return redirect()->back()->with('error', 'This feature has been disable for demo');
        }
        $rules = [
            'password'  => 'required|confirmed',
            'password_confirmation'  => 'required',
        ];
        $this->validate($request, $rules);

        $user = User::whereResetToken($token)->first();
        if ( ! $user){
            return back()->with('error', __t('invalid_reset_token'));
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect(route('login'))->with('success', __t('password_reset_success'));
    }

    /**
     * Social Login Settings
     */

    public function redirectFacebook(){
        return Socialite::driver('facebook')->redirect();
    }
    public function redirectGoogle(){
        return Socialite::driver('google')->redirect();
    }
    public function redirectTwitter(){
        return Socialite::driver('twitter')->redirect();
    }
    public function redirectLinkedIn(){
        return Socialite::driver('linkedin')->redirect();
    }

    public function callbackFacebook(){
        try {
            $socialUser = Socialite::driver('facebook')->user();
            $user = $this->getSocialUser($socialUser, 'facebook');
            auth()->login($user);
            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e){
            return redirect(route('login'))->with('error', $e->getMessage());
        }
    }

    public function callbackGoogle(){
        try {
            $socialUser = Socialite::driver('google')->user();
            $user = $this->getSocialUser($socialUser, 'google');
            auth()->login($user);
            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e){
            return redirect(route('login'))->with('error', $e->getMessage());
        }
    }
    public function callbackTwitter(){
        try {
            $socialUser = Socialite::driver('twitter')->user();
            $user = $this->getSocialUser($socialUser, 'twitter');
            auth()->login($user);
            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e){
            return redirect(route('login'))->with('error', $e->getMessage());
        }
    }
    public function callbackLinkedIn(){
        try {
            $socialUser = Socialite::driver('linkedin')->user();
            $user = $this->getSocialUser($socialUser, 'linkedin');
            auth()->login($user);
            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e){
            return redirect(route('login'))->with('error', $e->getMessage());
        }
    }

    public function getSocialUser($providerUser, $provider = ''){
        $user = User::whereProvider($provider)->whereProviderUserId($providerUser->getId())->first();

        if ($user) {
            return $user;
        } else {

            $user = User::whereEmail($providerUser->getEmail())->first();
            if ($user) {

                $user->provider_user_id = $providerUser->getId();
                $user->provider = $provider;
                $user->save();

            }else{
                $user = User::create([
                    'email'             => $providerUser->getEmail(),
                    'name'              => $providerUser->getName(),
                    'user_type'         => 'user',
                    'active_status'     => 1,
                    'provider_user_id'  => $providerUser->getId(),
                    'provider'          => $provider,
                ]);
            }

            return $user;
        }
    }

}