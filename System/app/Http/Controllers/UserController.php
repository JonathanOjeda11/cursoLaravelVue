<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Person;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $search=$request->search;
        $criteria=$request->criteria;

        if($search=='')
        {
            $person=User::join('people','users.id','=','people.id')->join('roles','users.rol_id', '=', 'roles.id')
            ->select('people.id','people.name','people.document_type','people.document_num','people.address','people.phone',
            'people.mail','users.user','users.password','users.status', 'users.rol_id','roles.name as rol')
            ->orderBy('people.id','desc')->paginate(3);
        }else
        {
            $person=User::join('people','users.id','=','people.id')->join('roles','users.rol_id', '=', 'roles.id')
            ->select('people.id','people.name','people.document_type','people.document_num','people.address','people.phone',
            'people.mail','users.user','users.password','users.status', 'users.rol_id','roles.name as rol')
            ->orderBy('people.id','desc')->where($criteria, 'like', '%'. $search . '%')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $person->total(),
                'current_page' => $person->currentPage(),
                'per_page' => $person->perPage(),
                'last_page' => $person->lastPage(),
                'from' => $person->firstItem(),
                'to' => $person->lastItem()
            ],
            'person' => $person
        ];


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

            $user = new User();
            $user->fill($request->all());
            $user->password = bcrypt($request->password);
            $user->save();

            $user->id = $person->id;

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
            $user = new User();
            $user = Userr::findOrFail($request->id);
            $user -> fill($request->all());
            $user->password = bcrypt($request->password);
            $user->save();
            $person = new Person();
            $person = Person::findOrFail($user->id);
            $person -> fill($request->all());
            $person->save();

            DB::commit();
        }catch(Exception $e)
        {
            DB::rollBack();

        }
        
    }

    public function desactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->status='0';
        $user->save();
    }

    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');  
        $category = User::findOrFail($request->id);
        $category->status='1';
        $category->save();
    }
}
