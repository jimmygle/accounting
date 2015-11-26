@extends('layouts.master')

@section('title', $account->name)

@section('content')
    @include('partials.validationErrors')

    <div class="col-sm-10">
        <form class="form-horizontal" method="POST" action="/accounts/{{ $account->slug }}">
            <input name="_method" type="hidden" value="PUT">
            {{ csrf_field() }}

            <div class="form-group">
                <label class="col-sm-2 control-label">ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{ $account->id }}" disabled="disabled">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name', @$account->name) }}">
                </div>
            </div>
            <div class="form-group">
                <label for="slug" class="col-sm-2 control-label">Slug</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="slug" id="slug" placeholder="Slug" value="{{ old('slug', @$account->slug) }}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>
                            @if (old('business_account', @$account->business_account))
                                <input type="checkbox" name="business_account" value="1" checked="checked"> Business Account
                            @else
                                <input type="checkbox" name="business_account" value="1"> Business Account
                            @endif
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Created</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{ $account->created_at }}" disabled="disabled">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Updated</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{ $account->updated_at }}" disabled="disabled">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-sm-2 text-center">
        <p><a href="/transactions/?category={{ $account->slug }}">Transactions &nbsp;<span class="badge"> {{ $account->transactions->count() }}</span></a></p>
        @if ($account->transactions->count() == 0)
            <hr>
            <form method="post" action="/accounts/{{ $account->slug }}">
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