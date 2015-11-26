@extends('layouts.master')

@section('title', 'Transactions<span class="label label-info pull-right">$' . $balance . '</span>')

@section('content')
    <div class="btn-group btn-group-lg btn-group-justified" role="group" aria-label="New Transactions">
        <a class="btn btn-danger" href="/transactions/create"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> New Expense</a>
        <a class="btn btn-primary" href="/transactions/create?transfer"><span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true"></span> New Transfer</a>
        <a class="btn btn-success" href="/transactions/create?income"><span class="glyphicon glyphicon-plus" aria-hidden="true"> New Income</a>
    </div>

    <hr>

    @if (count($filters) > 0)
        @foreach ($filters as $name => $value)
            <a href="/transactions/?{{ $value['removedUri'] }}" class="btn btn-primary" title="Remove Filter">{{ ucwords($name) }} <span class="badge">{{ $value['name'] }}</span> <span class="glyphicon glyphicon-remove"></span></a>
        @endforeach
        <hr>
    @endif

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Account</th>
                <th>Vendor</th>
                <th>Category</th>
                <th>Description</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td><a href="/transactions/?{{ http_build_query(array_merge($input, ['account' => $row->account->slug])) }}">{{ $row->account->name }}</a></td>
                    <td>
                        @if ($row->vendor)
                            <a href="/transactions/?{{ http_build_query(array_merge($input, ['vendor' => $row->vendor->slug])) }}">{{ $row->vendor->name }}</a>
                        @endif
                    </td>
                    <td>
                        @if ($row->category)
                            <a href="/transactions/?{{ http_build_query(array_merge($input, ['category' => $row->category->slug])) }}">{{ $row->category->name }}</a>
                        @endif
                    </td>
                    <td>
                        @if ($row->description)
                            <a href="/transactions/{{ $row->id }}">{{ $row->description }}</a>
                        @endif
                        @if ($row->business_expense == true)
                            <a href="/transactions/?{{ http_build_query(array_merge($input, ['business' => ''])) }}"><span class="label label-primary">B</span></a>
                        @endif
                        @if ($row->charitable_deduction == true)
                            <a href="/transactions/?{{ http_build_query(array_merge($input, ['charity' => ''])) }}"><span class="label label-info">C</span></a>
                        @endif
                    </td>
                    <td>{{ date('j-M-y', strtotime($row->timestamp)) }}</td>
                    <td>{{ money_format('%(!i', $row->amount) }}</td> 
                    @if ($row->id == $rows[0]->id)
                        <td class="info">
                            {{ money_format('%(!i', $row->balance) }}
                        </td>
                    @else
                        <td>
                            {{ money_format('%(!i', $row->balance) }}
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection