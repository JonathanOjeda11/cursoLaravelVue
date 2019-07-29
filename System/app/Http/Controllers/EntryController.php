<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Income;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EntryController extends Controller
{
     public function index(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        
        $search=$request->search;
        $criteria=$request->criteria;

        if($search=='')
        {
            $income=Income::join('people','income.supplier_id','=','people.id')
            ->join('users','income.user_id', '=', 'users.id')
            ->select('income.id','income.voucher_type','income.voucher_serie','income.voucher_num','income.date','income.tax',
            'income.total','income.status','people.name','users.user')
            ->orderBy('income.id','desc')->paginate(3);
        }else
        {
        	$income=Income::join('people','income.supplier_id','=','people.id')
        	->join('users','income.user_id', '=', 'users.id')
            ->select('income.id','income.voucher_type','income.voucher_serie','income.voucher_num','income.date','income.tax','income.total','income.status','people.name','users.user')
            ->where('income.'.$criteria, 'like', '%'. $search . '%')
            ->orderBy('income.id','desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $income->total(),
                'current_page' => $income->currentPage(),
                'per_page' => $income->perPage(),
                'last_page' => $income->lastPage(),
                'from' => $income->firstItem(),
                'to' => $income->lastItem()
            ],
            'income' => $income
        ];



    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        try
        {
            DB::beginTransaction();

            $time = Carbon::now('America/Monterrey');


            $income = new Income();
            $income->fill($request->all());
            $income->user_id=\Auth::user()->id;
            $income->date=$time->toDateString();
            $income->status='Registrado';
            $income->save();
            
          

            $details=$request->data;

            foreach ($details as $ep => $det) {
            	$details = new IncomeDetails();
            	$details->entry_id = $income->id;
            	$details->article_id=$det['article_id'];
            	$details->amount=$det['amount'];
            	$details->price=$det['price'];
            	$details->save();

            }

           

            DB::commit();

        }catch(Exception $e)
        {
           DB::rollBack();


        }
        
    }

    public function desactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $income = Income::findOrFail($request->id);
        $income->status='Anulado';
        $income->save();
    }

 
}
