<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{

    protected $table = 'incomes';

    protected $fillable = [
    	'supplier_id',
    	'user_id',
    	'voucher_type',
    	'voucher_serie',
    	'voucher_num',
    	'date',
    	'tax',
    	'total',
    	'status'



    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function supplier()
    {
    	return $this->belongsTo('App\Supplier');
    }
}
