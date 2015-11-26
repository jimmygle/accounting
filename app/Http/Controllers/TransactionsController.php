<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Transaction;
use App\Account;
use App\Vendor;
use App\Category;

class TransactionsController extends Controller
{

    protected $transactions;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Transaction $transactions)
    {
        // Setup query
        $query = $transactions->orderBy('timestamp', 'ASC')->with('category', 'vendor');

        $activeFilters = [];

        // Account filter
        if ($accountFilter = $request->input('account')) {
            $query->whereHas('account', function ($q) use ($accountFilter) {
                $q->where('slug', '=', $accountFilter);
            });
            $activeFilters['account'] = [
                'name' => Account::where('slug', '=', $accountFilter)->first()->name,
                'removedUri' => http_build_query($request->except('account'))
            ];
        }

        // Vendor filter
        if ($vendorFilter = $request->input('vendor')) {
            $query->whereHas('vendor', function ($q) use ($vendorFilter) {
                $q->where('slug', '=', $vendorFilter);
            });
            $activeFilters['vendor'] = [
                'name' => Vendor::where('slug', '=', $vendorFilter)->first()->name,
                'removedUri' => http_build_query($request->except('vendor'))
            ];
        }

        // Category filter
        if ($categoryFilter = $request->input('category')) {
            $query->whereHas('category', function ($q) use ($categoryFilter) {
                $q->where('slug', '=', $categoryFilter);
            });
            $activeFilters['category'] = [
                'name' => Category::where('slug', '=', $categoryFilter)->first()->name,
                'removedUri' => http_build_query($request->except('category'))
            ];
        }

        // Business filter
        if (($businessFilter = $request->input('business')) === "") {
            $query->where('business_expense', '=', '1');
            $activeFilters['business expense'] = [
                'name' => '',
                'removedUri' => http_build_query($request->except('business'))
            ];
        }

        // Charity filter
        if (($charityFilter = $request->input('charity')) === "") {
            $query->where('charitable_deduction', '=', '1');
            $activeFilters['charitable deduction'] = [
                'name' => '',
                'removedUri' => http_build_query($request->except('charity'))
            ];
        }

        // Execute query
        $this->transactions = $query->get();

        $this->calculateRowBalances();
        $rows = $this->transactions->reverse();
        return view('transactions.index', [
            'rows' => $rows,
            'balance' => money_format('%(!i', @$rows[0]->balance),
            'filters' => $activeFilters
        ])->withInput($request->all());
    }

    /**
     * Calculate running balance of rows
     *
     * @return void
     */
    public function calculateRowBalances()
    {
        $this->transactions->each(function($row, $key) {
          // Get previous row
          $previousRow = $this->transactions->get($key - 1);
          $previousRowBalance = 0;
          if ($previousRow instanceof Transaction) {
            $previousRowBalance = $previousRow->balance;
          }

          $row->balance = $row->amount + $previousRowBalance;
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Account $accounts, Vendor $vendors, Category $categories)
    {
        $type = key(array_where($request->only(['transfer', 'income']), function ($key, $value) {
            return $value !== null;
        }));

        return view('transactions.createOrShowOrUpdate', [
            'vendors' => $vendors->orderBy('name')->get(), 
            'categories' => $categories->orderBy('name')->get(),
            'type' => $type ?: 'expense',
            'accounts' => $accounts->orderBy('name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Transaction $transaction)
    {
        if ($request->input('type') == 'transfer') {
            return $this->storeTransfer($request);
        }
        $this->validate($request, [
            'type' => 'required|in:expense,transfer,income',
            'account' => 'required:exists:accounts,id',
            'vendor' => 'required|exists:vendors,id',
            'category' => 'exists:categories,id',
            'description' => 'max:255',
            'timestamp' => 'required|date_format:Y-m-d\TH:i|before:' . date('Y-m-d\TH:i'),
            'amount' => 'required|numeric'
        ]);

        $input = $request->except(['_token']);
        if ($input['type'] == 'expense' && $input['amount'] > 0) {
            $input['amount'] = $input['amount'] * -1;
        }
        $input['account_id'] = $input['account'];
        $input['vendor_id'] = $input['vendor'];
        $input['category_id'] = $input['category'];
        unset($input['type'], $input['account'], $input['vendor'], $input['category']);

        $transaction->fill($input)->save();

        return redirect('transactions')->with('success', 'Transaction successfully added.');
    }

    protected function storeTransfer(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|in:expense,transfer,income',
            'from_account' => 'required:exists:accounts,id',
            'to_account' => 'required:exists:accounts,id',
            'description' => 'max:255',
            'timestamp' => 'required|date_format:Y-m-d\TH:i|before:' . date('Y-m-d\TH:i'),
            'amount' => 'required|numeric'
        ]);

        $input = $request->except(['_token']);
        $originalAmount = $input['amount'];
        unset($input['type'], $input['vendor'], $input['category']);

        // Save the expense
        $input['account_id'] = $input['from_account'];
        if ($input['amount'] > 0) {
            $input['amount'] = $input['amount'] * -1;
        }
        (new Transaction)->fill($input)->save();

        // Save the income
        $input['account_id'] = $input['to_account'];
        $input['amount'] = $originalAmount;
        (new Transaction)->fill($input)->save();

        return redirect('transactions')->with('success', 'Transactions successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param Transaction $transaction
     * @param Account $accounts
     * @param Vendor $vendors
     * @param Category $categories
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction, Account $accounts, Vendor $vendors, Category $categories, $id)
    {
        $transaction = $transaction->with(['category', 'vendor'])->where('id', '=', $id)->firstOrFail();
        $transaction->timestamp = str_replace(' ', 'T', $transaction->timestamp);
        if ($transaction->amount < 0) {
            $type = 'expense';
            $transaction->amount = $transaction->amount * -1;
        } elseif ($transaction->amount > 0) {
            $type = 'income';
        }
        return view('transactions.createOrShowOrUpdate', [
            'transaction' => $transaction,
            'accounts' => $accounts->orderBy('name')->get(),
            'vendors' => $vendors->orderBy('name')->get(),
            'categories' => $categories->orderBy('name')->get(),
            'type' => $type
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect('transactions/' . $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Transaction $transaction
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction, $id)
    {
        $this->validate($request, [
            'type' => 'required|in:expense,transfer,income',
            'account' => 'required:exists:accounts,id',
            'vendor' => 'required|exists:vendors,id',
            'category' => 'exists:categories,id',
            'description' => 'required|max:255',
            'timestamp' => 'required|date_format:Y-m-d\TH:i|before:' . date('Y-m-d\TH:i'),
            'amount' => 'required|numeric'
        ]);

        $input = $request->except(['_token', '_method']);
        if ($input['type'] == 'expense' && $input['amount'] > 0) {
            $input['amount'] = $input['amount'] * -1;
        }
        $input['account_id'] = $input['account'];
        $input['vendor_id'] = $input['vendor'];
        $input['category_id'] = $input['category'];
        $input['timestamp'] = str_replace('T', ' ', $input['timestamp']);
        unset($input['type'], $input['account'], $input['vendor'], $input['category']);

        $transaction->where('id', '=', $id)->firstOrFail()->fill($input)->save();

        return redirect('transactions')->with('success', 'Transaction successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Transaction $transaction
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction, $id)
    {
        $transaction->where('id', '=', $id)->firstOrFail()->delete();
        return redirect('transactions')->with('success', 'Successfully deleted transaction.');
    }
}
