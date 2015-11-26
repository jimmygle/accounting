@extends('layouts.master')

@section('title', 'Vendors')

@section('content')

    <form class="form-inline" method="post" action="/vendors">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="sr-only" for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
        </div>
        <div class="form-group">
            <label class="sr-only" for="slug">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug">
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>

    <hr>

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Transactions</th>
            <th>Last Modified</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($vendors as $vendor)
            <tr>
                <td><a href="/vendors/{{ $vendor->slug }}">{{ $vendor->name }}</a></td>
                <td>{{ $vendor->slug }}</td>
                <td><a href="/transactions/?vendor={{ $vendor->slug }}">{{ $vendor->transactions->count() }}</a></td>
                <td>{{ date('j-M-y', strtotime($vendor->updated_at)) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection