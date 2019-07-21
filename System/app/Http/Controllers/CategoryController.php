<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
 
    public function index()
    {
        $categories = Category::all();
        return $categories;
    }

    public function store(Request $request)
    {
        $category = new Category();
        $category->name=$request->name;
        $category->descripton=$request->description;
        $category->status='1';
        $category->save();
    }

    
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($request->id);
        $category->name=$request->name;
        $category->descripton=$request->description;
        $category->status='1';
        $category->save();
    }

    public function desactivar(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->status='0';
        $category->save();
    }

    public function activar(Request $request)
    {  
        $category = Category::findOrFail($request->id);
        $category->status='1';
        $category->save();
    }
}

