<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $search=$request->search;
        $criteria=$request->criteria;

        if($search=='')
        {
            $Persons=Person::orderBy('id','desc')->paginate(3);
        }else
        {
            $Persons=Person::where($criteria, 'like', '%'. $search . '%')->orderBy('id','desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $Persons->total(),
                'current_page' => $Persons->currentPage(),
                'per_page' => $Persons->perPage(),
                'last_page' => $Persons->lastPage(),
                'from' => $Persons->firstItem(),
                'to' => $Persons->lastItem()
            ],
            'persons' => $Persons
        ];


    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $person = new Person();
        $person->fill([
            'name' => $request->name,
            'document_type' => $request->document_type,
            'document_num' => $request->document_num,
            'address' => $request->address,
            'phone'=> $request->phone,
            'mail'=>$request->mail
        ]);

        $person->save();

    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $person = Person::findOrFail($request->id);
        $person->fill([
            'name' => $request->name,
            'document_type' => $request->document_type,
            'document_num' => $request->document_num,
            'address' => $request->address,
            'phone'=> $request->phone,
            'mail'=>$request->mail
        ]);
        $person->save();
    }

}
