@extends('layouts.master')

@section('title', 'Categories')

@section('content')

    <form class="form-inline" method="post" action="/categories">
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
            @foreach ($categories as $category)
                <tr>
                    <td><a href="/categories/{{ $category->slug }}">{{ $category->name }}</a></td>
                    <td>{{ $category->slug }}</td>
                    <td><a href="/transactions/?category={{ $category->slug }}">{{ $category->transactions->count() }}</a></td>
                    <td>{{ date('j-M-y', strtotime($category->updated_at)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection