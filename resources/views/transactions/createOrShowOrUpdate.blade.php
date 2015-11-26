@extends('layouts.master')

@if (isset($transaction))
    @section('title', 'Update ' . ucfirst($type))
@else
    @section('title', 'New ' . ucfirst($type))
@endif

@section('content')
    @include('partials.validationErrors')

    <div class="col-sm-10">
        @if (isset($transaction))
            <form class="form-horizontal" method="post" action="/transactions/{{ $transaction->id }}">
            <input type="hidden" name="_method" value="PUT">
        @else
            <form class="form-horizontal" method="post" action="/transactions">
        @endif
            {{ csrf_field() }}
            <input type="hidden" name="type" value="{{ old('type', $type) }}">

            @if (isset($transaction))
                <!-- ID -->
                <div class="form-group">
                    <label for="id" class="col-sm-2 control-label">ID</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="id" value="{{ $transaction->id }}" disabled="disabled">
                    </div>
                </div>
            @endif

            @if ($type == 'transfer')
                <!-- Transfer From Account -->
                <div class="form-group">
                    <label for="from_account" class="col-sm-2 control-label">From</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="from_account" name="from_account">
                            @foreach ($accounts as $account)
                                @if (old('from_account', 1) == $account->id)
                                    <option value="{{ $account->id }}" selected="selected">{{ $account->name }} &nbsp;&nbsp;&nbsp; ${{ money_format('%(!i', $account->transactions->sum('amount')) }}</option>
                                @else
                                    <option value="{{ $account->id }}">{{ $account->name }} &nbsp;&nbsp;&nbsp; ${{ money_format('%(!i', $account->transactions->sum('amount')) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Transfer To Account -->
                <div class="form-group">
                    <label for="to_account" class="col-sm-2 control-label">To</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="to_account" name="to_account">
                            @foreach ($accounts as $account)
                                @if (old('to_account') == $account->id)
                                    <option value="{{ $account->id }}" selected="selected">{{ $account->name }} &nbsp;&nbsp;&nbsp; ${{ money_format('%(!i', $account->transactions->sum('amount')) }}</option>
                                @else
                                    <option value="{{ $account->id }}">{{ $account->name }} &nbsp;&nbsp;&nbsp; ${{ money_format('%(!i', $account->transactions->sum('amount')) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            @else
                <!-- Account -->
                <div class="form-group">
                    <label for="account" class="col-sm-2 control-label">Account</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="account" name="account">
                            @foreach ($accounts as $account)
                                @if (old('account', (@$transaction->account->id || 1)) == $account->id)
                                    <option value="{{ $account->id }}" selected="selected">{{ $account->name }} &nbsp;&nbsp;&nbsp; ${{ money_format('%(!i', $account->transactions->sum('amount')) }}</option>
                                @else
                                    <option value="{{ $account->id }}">{{ $account->name }} &nbsp;&nbsp;&nbsp; ${{ money_format('%(!i', $account->transactions->sum('amount')) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($type !== 'transfer')
            <!-- Vendor -->
            <div class="form-group">
                <label for="vendor" class="col-sm-2 control-label">Vendor</label>
                <div class="col-sm-10">
                    <select class="form-control" id="vendor" name="vendor">
                        @foreach ($vendors as $vendor)
                            @if (old('vendor', @$transaction->vendor->id) == $vendor->id)
                                <option value="{{ $vendor->id }}" selected="selected">{{ $vendor->name }}</option>
                            @else
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="category" class="col-sm-2 control-label">Category</label>
                <div class="col-sm-10">
                    <select class="form-control" id="category" name="category">
                        <option value="">--Vendor's Default--</option>
                        @foreach ($categories as $category)
                            @if (old('category', @$transaction->category->id) == $category->id)
                                <option value="{{ $category->id }}" selected="selected">{{ $category->name }}</option>
                            @else
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="description" name="description" maxlength="255" value="{{ old('description', @$transaction->description) }}">
                </div>
            </div>

            <!-- Timestamp -->
            <div class="form-group">
                <label for="timestamp" class="col-sm-2 control-label">Timestamp</label>
                <div class="col-sm-10">
                    <input type="datetime-local" class="form-control" id="timestamp" name="timestamp" value="{{ old('timestamp', @$transaction->timestamp) ? old('timestamp', @$transaction->timestamp) : date('Y-m-d\TH:i') }}">
                </div>
            </div>

            <!-- Amount -->
            <div class="form-group">
                <label for="amount" class="col-sm-2 control-label">Amount</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon" id="dollar-sign-addon">$</span>
                        <input type="text" class="form-control" name="amount" id="amount" placeholder="00.00" aria-describedby="dollar-sign-addon" value="{{ old('amount', @$transaction->amount) }}">
                    </div>
                </div>
            </div>

            <!-- Business Expense -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        @if (old('business_expense', @$transaction->business_expense))
                            <input type="checkbox" name="business_expense" value="1" checked="checked"> Business Expense
                        @else
                            <input type="checkbox" name="business_expense" value="1"> Business Expense
                        @endif
                    </label>
                    </div>
                </div>
            </div>

            <!-- Charitable Deduction -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>
                            @if (old('charitable_deduction', @$transaction->charitable_deducation))
                                <input type="checkbox" name="charitable_deduction" value="1" checked="checked"> Charitable Deduction
                            @else
                                <input type="checkbox" name="charitable_deduction" value="1"> Charitable Deduction
                            @endif
                        </label>
                    </div>
                </div>
            </div>

            @if (isset($transaction))
                <!-- Created At -->
                <div class="form-group">
                    <label for="created_at" class="col-sm-2 control-label">Created</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="created_at" value="{{ $transaction->created_at }}" disabled="disabled">
                    </div>
                </div>

                <!-- Updated At -->
                <div class="form-group">
                    <label for="updated_at" class="col-sm-2 control-label">Updated</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="updated_at" value="{{ $transaction->updated_at }}" disabled="disabled">
                    </div>
                </div>
            @endif

            <hr/>

            <!-- Save -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary btn-lg">Save</button>
                </div>
            </div>

        </form>
    </div>

    <div class="col-sm-2 text-center">
        @if (isset($transaction))
        <form method="post" action="/transactions/{{ $transaction->id }}">
            <input type="hidden" name="_method" value="DELETE">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-md btn-danger">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                Delete
            </button>
        </form>
        @endif
    </div>
@endsection