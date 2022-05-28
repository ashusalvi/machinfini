<?php

namespace App\Http\Controllers\channelPartner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Earning;
use App\Payment;
use App\Course;

class AffiliteMarketingReportController extends Controller
{
    protected $table = 'affilite_marketing_reports';
    protected $guarded = [];
    protected $primaryKey='id';


    public function index()
    {
        $earnings = Earning::where('payment_status','success')->orderBy('id', 'DESC')->get();

        return view('admin.earning.index',compact('earnings'));
    }

}