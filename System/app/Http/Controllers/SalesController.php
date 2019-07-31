<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\SalDetail;
use Carbon\Carbon,
class SalesController extends Controller
{
    
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $search = $request->search;
        $criteria = $request->criteria;
        
        if ($search==''){
            $sales = Sale::join('people','sales.client_id','=','people.id')
            ->join('users','sales.user_id','=','users.id')
            ->select('sales.id','sales.voucher_type','sales.voucher_serie',
            'sales.voucher_num','sales.date','sales.tax','sales.total',
            'sales.status','people.name','users.user')
            ->orderBy('sales.id', 'desc')->paginate(3);
        }
        else{
            $sales = Sale::join('people','sales.client_id','=','people.id')
            ->join('users','sales.user_id','=','users.id')
            ->select('sales.id','sales.voucher_type','sales.voucher_serie',
            'sales.voucher_num','sales.date','sales.tax','sales.total',
            'sales.status','people.name','users.user')
            ->where('sales.'.$criteria, 'like', '%'. $search . '%')
            ->orderBy('sales.id', 'desc')->paginate(3);
        }
        
        return [
            'pagination' => [
                'total'        => $sales->total(),
                'current_page' => $sales->currentPage(),
                'per_page'     => $sales->perPage(),
                'last_page'    => $sales->lastPage(),
                'from'         => $sales->firstItem(),
                'to'           => $sales->lastItem(),
            ],
            'sales' => $sales
        ];
    }
    public function getHeader(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $sale = Sale::join('people','sales.client_id','=','people.id')
        ->join('users','sales.user_id','=','users.id')
        ->select('sales.id','sales.voucher_type','sales.voucher_serie',
        'sales.voucher_num','sales.date','sales.tax','sales.total',
        'sales.status','people.name','users.user')
        ->where('sales.id','=',$id)
        ->orderBy('sales.id', 'desc')->take(1)->get();
        
        return ['sale' => $sale];
    }
    public function getDetails(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $details = SaleDetail::join('articles','sale_detail.article_id','=','articles.id')
        ->select('sale_detail.amount','sale_detail.price','sale_detail.discount',
        'articles.name as article')
        ->where('sale_detail.sales_id','=',$id)
        ->orderBy('sale_detail.id', 'desc')->get();
        
        return ['details' => $details];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $mytime= Carbon::now('America/Monterrey');

            $sale = new Sale();
            $sale->client_id = $request->client_id;
            $sale->user_id = \Auth::user()->id;
            $sale->voucher_type = $request->voucher_type;
            $sale->voucher_serie = $request->voucher_serie;
            $sale->voucher_num = $request->voucher_num;
            $sale->date = $mytime->toDateString();
            $sale->tax = $request->tax;
            $sale->total = $request->total;
            $sale->status = 'Registrado';
            $sale->save();

            $details = $request->data;//Array de details
            //Recorro todos los elementos

            foreach($details as $ep=>$det)
            {
                $detalle = new DetalleVenta();
                $detalle->sale_id = $sale->id;
                $detalle->article_id = $det['article_id'];
                $detalle->amount = $det['amount'];
                $detalle->price = $det['price'];
                $detalle->discount = $det['discount'];         
                $detalle->save();
            }          

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function desactivate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sale = Sale::findOrFail($request->id);
        $sale->status = 'Anulado';
        $sale->save();
    }

}
