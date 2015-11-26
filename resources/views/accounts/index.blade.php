@extends('layouts.master')

@section('title', 'Accounts')

@section('content')

    <form class="form-inline" method="post" action="/accounts">
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
        @foreach ($accounts as $account)
            <tr>
                <td><a href="/accounts/{{ $account->slug }}">{{ $account->name }}</a></td>
                <td>{{ $account->slug }}</td>
                <td><a href="/transactions/?account={{ $account->slug }}">{{ $account->transactions->count() }}</a></td>
                <td>{{ date('j-M-y', strtotime($account->updated_at)) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection