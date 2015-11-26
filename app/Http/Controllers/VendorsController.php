<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Vendor;

class VendorsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Vendor $vendors
     * @return \Illuminate\Http\Response
     */
    public function index(Vendor $vendors)
    {
        $vendors = $vendors->orderBy('name')->with('transactions')->get();
        return view('vendors.index')->with('vendors', $vendors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('vendors');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Vendor $vendor
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Vendor $vendor)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories',
            'slug' => 'required|unique:categories',
        ]);

        $input = $request->except(['_token']);
        $vendor->fill($input)->save();

        return redirect('vendors')->with('success', 'Vendor successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @param Vendor $vendor
     * @return \Illuminate\Http\Response
     */
    public function show($slug, Vendor $vendor)
    {
        $vendor = $vendor->where('slug', '=', $slug)->firstOrFail();
        return view('vendors.showOrUpdate')->with('vendor', $vendor);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        return redirect('vendors/' . $slug);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Vendor $vendor
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor, $slug)
    {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required',
        ]);

        $input = $request->except(['_token', '_method']);
        $vendor = $vendor->where('slug', '=', $slug)->firstOrFail();
        $vendor->fill($input)->save();

        return redirect('vendors/' . $vendor->slug)->with('success', 'Vendor successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Vendor $vendor
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor, $slug)
    {
        $vendor = $vendor->where('slug', '=', $slug)->firstOrFail();
        $vendorName = $vendor->name;
        $vendor->delete();
        return redirect('vendors')->with('success', 'Successfully deleted "' . $vendorName . '" vendor.');
    }
}
