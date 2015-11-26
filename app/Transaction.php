<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
  protected $fillable = ['account_id', 'vendor_id', 'category_id', 'business_expense', 'charitable_deduction', 'description', 'amount', 'timestamp'];

  public function category() 
  {
    return $this->belongsTo('App\Category');
  }

  public function vendor()
  {
    return $this->belongsTo('App\Vendor');
  }

  public function account()
  {
      return $this->belongsTo('App\Account');
  }
}
