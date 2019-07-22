<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
 
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $search=$request->search;
        $criteria=$request->criteria;

        if($search=='')
        {
            $categories=Category::orderBy('id','desc')->paginate(3);
        }else
        {
            $categories=Category::where($criteria, 'like', '%'. $search . '%')->orderBy('id','desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $categories->total(),
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'last_page' => $categories->lastPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem()
            ],
            'categories' => $categories
        ];


    }

    public function selectCategory(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $categories = Category::where('status','=','1')
        ->select('id','name')->orderBy('name', 'asc')->get();
        return ['categories'=>$categories];

    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->status='1';
        $category->save();
    }

    
    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $category = Category::findOrFail($request->id);
        $category->name=$request->name;
        $category->description=$request->description;
        $category->status='1';
        $category->save();
    }

    public function desactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $category = Category::findOrFail($request->id);
        $category->status='0';
        $category->save();
    }

    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');  
        $category = Category::findOrFail($request->id);
        $category->status='1';
        $category->save();
    }
}

