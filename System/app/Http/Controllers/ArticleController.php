<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
       // if(!$request->ajax()) return redirect('/');
        $search=$request->search;
        $criteria=$request->criteria;

        if($search=='')
        {
            $articles=Article::join('categories','articles.categoryid','=','categories.id')
            ->select('articles.id','articles.categoryid','articles.code','articles.name','categories.name as category_name',
            'articles.sale_price','articles.stock','articles.description', 'articles.status')
            ->orderBy('articles.id','desc')->paginate(10);
        }else
        {
            $articles=Article::join('categories','articles.categoryid','=','categories.id')
            ->select('articles.id','articles.categoryid','articles.code','articles.name','categories.name as category_name',
            'articles.sale_price','articles.stock','articles.description', 'articles.status')
            ->where('articles.'.$criteria, 'like', '%'. $search . '%')
            ->orderBy('articles.id','desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total' => $articles->total(),
                'current_page' => $articles->currentPage(),
                'per_page' => $articles->perPage(),
                'last_page' => $articles->lastPage(),
                'from' => $articles->firstItem(),
                'to' => $articles->lastItem()
            ],
            'articles' => $articles
        ];


    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $article = new Article();
        $article->categoryid = $request->categoryid;
        $article->code = $request->code;
        $article->name = $request->name;
        $article->sale_price = $request->sale_price;
        $article->stock = $request->stock;
        $article->description = $request->description;
        $article->status = '1';
        $article->save();
    }

    public function update(Request $request)
    { 
        if(!$request->ajax()) return redirect('/');
        $article = Article::findOrFail($request->id);
        $article->categoryid = $request->categoryid;
        $article->code = $request->code;
        $article->name = $request->name;
        $article->sale_price = $request->sale_price;
        $article->stock = $request->stock;
        $article->description = $request->description;
        $article->status = '1';
        $article->save();

        
    }

    public function desactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $article = Article::findOrFail($request->id);
        $article->status='0';
        $article->save();
    }

    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');  
        $article = Article::findOrFail($request->id);
        $article->status='1';
        $article->save();
    }

}
