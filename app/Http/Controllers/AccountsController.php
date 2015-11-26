<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Account;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Account $accounts)
    {
        $accounts = $accounts->orderBy('name')->with('transactions')->get();
        return view('accounts.index')->with('accounts', $accounts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('accounts');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Account $account
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Account $account)
    {
        $this->validate($request, [
            'name' => 'required|unique:accounts',
            'slug' => 'required|unique:accounts',
        ]);

        $input = $request->except(['_token']);
        $account->fill($input)->save();

        return redirect('accounts')->with('success', $account->name . ' account successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param Account $account
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account, $slug)
    {
        $account = $account->where('slug', '=', $slug)->firstOrFail();
        return view('accounts.showOrUpdate')->with('account', $account);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        return redirect('accounts/' . $slug);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Account $account
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account, $slug)
    {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required'
        ]);

        $input = $request->except(['_token', '_method']);
        $account = $account->where('slug', '=', $slug)->firstOrFail();
        $account->fill($input)->save();

        return redirect('accounts/' . $account->slug)->with('success', 'Account successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Account $account
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account, $slug)
    {
        $account = $account->where('slug', '=', $slug)->firstOrFail();
        $accountName = $account->name;
        $account->delete();
        return redirect('accounts')->with('success', 'Successfully deleted "' . $accountName . '" account.');
    }
}
