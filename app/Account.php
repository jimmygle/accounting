<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['name', 'slug', 'business_account'];

    public function transactions() {
        return $this->hasMany('App\Transaction');
    }
}
