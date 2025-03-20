<?php

namespace Modules\DeliveryCharges\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\DeliveryCharges\Entities\DeliveryCharge;

class DeliveryChargesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $delivery_charges = DeliveryCharge::all();
        return view('deliverycharges::index',compact('delivery_charges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('deliverycharges::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'country' => 'required|regex:/^[\pL\s]+$/u',
            'rate' => 'required|integer',
            'roro' => 'required|integer',
            'container' => 'required|integer'
        ]);

        DeliveryCharge::create([
            'country_name' => $request->country,
            'rate' => $request->rate,
            'roro'=>$request->roro,
            'container'=>$request->container
        ]);
        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.delivery-charges.index',)->with($notification);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('deliverycharges::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $delivery_charge_data = DeliveryCharge::find($id);
        return view('deliverycharges::edit',compact('delivery_charge_data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'country' => 'required|regex:/^[\pL\s]+$/u',
            'rate' => 'required|integer',
            'roro' => 'required|integer',
            'container' => 'required|integer'
        ]);

        DeliveryCharge::where('id',$id)->update([
            'country_name' => $request->country,    
            'rate' => $request->rate,
            'roro'=>$request->roro,
            'container'=>$request->container
        ]);
        $notification= trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.delivery-charges.index',)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
