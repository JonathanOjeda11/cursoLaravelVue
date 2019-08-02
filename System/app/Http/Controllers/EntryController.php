<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Income;
use App\IncomeDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EntryController extends Controller
{
     public function getHeader(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $income = Income::join('people','incomes.supplier_id','=','people.id')
        ->join('users','incomes.user_id','=','users.id')
        ->select('incomes.id','incomes.voucher_type','incomes.voucher_serie',
        'incomes.voucher_num','incomes.date','incomes.tax','incomes.total',
        'incomes.status','people.name','users.user')
        ->where('incomes.id','=',$id)
        ->orderBy('incomes.id', 'desc')->take(1)->get();
        
        return ['incomes' => $income];
    }
    public function getDetails(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $details = IncomeDetail::join('articles','income_details.article_id','=','articles.id')
        ->select('income_details.amount','income_details.price','articles.name as article')
        ->where('income_details.entry_id','=',$id)
        ->orderBy('income_details.id', 'desc')->get();
        
        return ['details' => $details];
    }
     public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $search=$request->search;
        $criteria=$request->criteria;

        if($search=='')
        {
            $income=Income::join('people','incomes.supplier_id','=','people.id')
            ->join('users','incomes.user_id', '=', 'users.id')
            ->select('incomes.id','incomes.voucher_type','incomes.voucher_serie','incomes.voucher_num','incomes.date','incomes.tax',
            'incomes.total','incomes.status','people.name','users.user')
            ->orderBy('incomes.id','desc')->paginate(3);
        }else
        {
        	$income=Income::join('people','incomes.supplier_id','=','people.id')
        	->join('users','incomes.user_id', '=', 'users.id')
            ->select('incomes.id','incomes.voucher_type','incomes.voucher_serie','incomes.voucher_num','incomes.date','incomes.tax','incomes.total','incomes.status','people.name','users.user')
            ->where('incomes.'.$criteria, 'like', '%'. $search . '%')
            ->orderBy('incomes.id','desc')->paginate(3);
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
            	$details = new IncomeDetail();
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
