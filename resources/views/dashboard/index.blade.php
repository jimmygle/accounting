@extends('layouts.master')

@section('title', 'Categories')

@section('content')

    <div class="col-sm-5">
        <h3>Account Balances</h3>
        <table class="table table-bordered table-hover">
            <thead>
                <tr><th>Account</th><th>Balance</th></tr>
            </thead>
            <tbody>
            @foreach ($accounts as $account)
                <tr>
                    <td><a href="/transactions?account={{ $account->slug }}">{{ $account->name }}</a></td>
                    <td>{{ money_format('%(!i', $account->balance) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr class="danger"><th>NET WORTH</th><th>{{ money_format('%(!i', array_sum($accounts->pluck('balance')->toArray())) }}</th></tr>
            </tfoot>
        </table>
    </div>

@endsection