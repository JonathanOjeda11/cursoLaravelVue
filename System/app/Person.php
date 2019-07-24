<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'people';
    protected $fillable = ['name','document_type','document_num','address','phone','mail'];

    public function supplier()
    {
        return $this->hasOne('App\Proveedor');
    }
}
