<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Supplier;
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
            $supplier=Supplier::join('people','suppliers.id','=','people.id')
            ->select('people.id','people.name','people.document_type','people.document_num','people.address','people.phone',
            'people.mail','suppliers.contact','suppliers.contact_phone')
            ->orderBy('people.id','desc')->paginate(3);
        }else
        {
            $supplier=Supplier::join('people','suppliers.id','=','people.id')
            ->select('people.id','people.name','people.document_type','people.document_num','people.address','people.phone',
            'people.mail','suppliers.contact','suppliers.contact_phone')
            ->orderBy('people.id','desc')->where($criteria, 'like', '%'. $search . '%')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $supplier->total(),
                'current_page' => $supplier->currentPage(),
                'per_page' => $supplier->perPage(),
                'last_page' => $supplier->lastPage(),
                'from' => $supplier->firstItem(),
                'to' => $supplier->lastItem()
            ],
            'supplier' => $supplier
        ];


    }

    public function selectSupplier(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filter = $request->filter;
        $suppliers = Supplier::join('people','supplier.id','=','people.id')
        ->where('people.name', 'like', '%'.$filter.'%')
        ->orWhere('people.document_num','like','%'.$filter.'%')
        ->select('people.id','people.name','people.document_num')
        ->orderBy('people.name', 'asc')->get();

        return ['suppliers' => $suppliers];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        try
        {
            DB::beginTransaction();
            $person = new Person();
            $person->fill($request->all());
            $person->save();
            $supplier = new Supplier();
            $supplier->fill($request->all());
            $supplier->id = $person->id;
            $supplier->save();

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
            
            DB::beginTransaction();
            $supplier = new Supplier();
            $supplier = Supplier::findOrFail($request->id);
            $supplier -> fill($request->all());
            $supplier->save();
            $person = new Person();
            $person = Person::findOrFail($supplier->id);
            $person -> fill($request->all());
            $person->save();

            DB::commit();
        }catch(Exception $e)
        {
            DB::rollBack();

        }
        
    }
}
