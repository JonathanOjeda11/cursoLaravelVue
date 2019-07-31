<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
	protected $table = 'sales'
    protected $fillable =[
        'client_id', 
        'user_id',
        'voucher_type',
        'voucher_serie',
        'voucher_num',
        'date',
        'tax',
        'total',
        'status'
    ];

}
