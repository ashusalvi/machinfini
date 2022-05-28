<?php

namespace App\Http\Controllers\company\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {  
        return view('company.company.dashboard');
    }
}