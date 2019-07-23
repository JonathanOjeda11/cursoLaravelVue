<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
 protected $fillable = [
     'categoryid','code','name','sale_price', 'stock', 'description', 'status'
 ];
 
 public function category()
 {
     return $this->belongsTo('App\Category');
 }
}
