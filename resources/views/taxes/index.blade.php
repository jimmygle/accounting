@extends('layouts.master')

@section('title', 'Taxes')

@section('content')

    <div class="col-sm-6">
        <table class="table">
            <thead>
                <tr><th colspan="2">Business Taxes</th></tr>
            </thead>
            <tbody>
                <tr class="success"><td>Total Revenue</td><td>{{ $revenue }}</td></tr>
                <tr><td>Washington B&O (1.5%)</td><td>{{ $revenue * 0.015 }}</td></tr>
                <tr><td>FICA (7.5%)</td><td>{{ $revenue * 0.075 }}</td></tr>
                <tr class="info"><td><strong>TAX LIABILITY</strong></td><td><strong>{{ ($revenue * 0.015) + ($revenue * 0.075) }}</strong></td></tr>
            </tbody>
        </table>
    </div>

@endsection