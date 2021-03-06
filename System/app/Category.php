<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'description', 'status'];

    public function articles()
    {
        return $this->hasMany('App\Article');
    }
}
