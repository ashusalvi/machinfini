<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Earning;
use App\Option;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $earning = Earning::where('payment_status','success')->with('enroll.user')->orderBy('id','DESC')->get();

        return view('admin.invoice_list',compact('earning'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request)
    {
        $earning = Earning::where('payment_status','success')->where('id',$request->id)->with('enroll.user')->orderBy('id','DESC')->get();
        
        $option = Option::where('option_key', 'like', 'invoice_%')->get();


        return view('admin.invoice.invoice_detail',compact('earning','option'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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