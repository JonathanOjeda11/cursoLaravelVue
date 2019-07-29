<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomeDetail extends Model
{

	protected $table = 'income_detail';
    protected $fillable = [
    	'entry_id',
    	'article_id',
    	'amount',
    	'price'


    ];
}
