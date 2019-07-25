<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Suppliers;
use App\Person;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $search=$request->search;
        $criteria=$request->criteria;

        if($search=='')
        {
            $Supplier=Suppliers::join('people','suppliers.id','=','people.id')
            ->select('people.id','people.name','people.document_type','people.document_num','people.address','people.phone',
            'people.mail','suppliers.contact','suppliers.contact_phone')
            ->orderBy('people.id','desc')->paginate(3);
        }else
        {
            $Supplier=Supplier::join('people','suppliers.id','=','people.id')
            ->select('people.id','people.name','people.document_type','people.document_num','people.address','people.phone',
            'people.mail','suppliers.contact','suppliers.contact_phone')
            ->orderBy('people.id','desc')->where($criteria, 'like', '%'. $search . '%')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $Supplier->total(),
                'current_page' => $Supplier->currentPage(),
                'per_page' => $Supplier->perPage(),
                'last_page' => $Supplier->lastPage(),
                'from' => $Supplier->firstItem(),
                'to' => $Supplier->lastItem()
            ],
            'supplier' => $Supplier
        ];


    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        try
        {
            DB:beginTransaction();
            $person = new Person();
            $person->fill($request->all());
            $person->save();
            $supplier = new Supplier();
            $supplier->fill($request->all());
            $supplier-save();

            DB::commit();

        }catch(Exception $e)
        {
           DB::rollBack();


        }
        
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        try
        {
            DB:beginTransaction();
            $supplier = Suppliers::findOrFail($supplier->id);
            $person = Suppliers::findOrFail($person->id);
            $supplier = new Suppliers();
            $person = new Person();
            $person -> fill($request->all());
            $supplier -> fill($request->all());
            $supplier->save();
            $person->save();

            DB::commit();
        }catch(Exception $e)
        {
            DB::rollBack();

        }
        
    }
}
