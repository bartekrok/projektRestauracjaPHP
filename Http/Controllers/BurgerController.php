<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Burger;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\BurgerRequest;
use Illuminate\Support\Facades\Auth;


class BurgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myAPI_URL = config('app.url')."/api/burger/list";
        $res=Http::withBasicAuth('student','student')->get($myAPI_URL);
        $myBurgers=json_decode(json_encode($res->json()['data']),FALSE);
        $user = Auth::user();

        return view('burger.list',['burger'=>$myBurgers,'user'=>$user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('burger.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $myAPI_URL=config('app.url')."/api/burger/new";
        $response=Http::post($myAPI_URL, $request);
        return redirect('/burger/list');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $myBurger = Burger::find($id);

        if($myBurger == null){
            $error_message = "Burger id=".$id." not found";
            return view('burger.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
        if($myBurger->count()>0)
        return view('burger.edit',['burger' => $myBurger,]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'id_burger' => 'required',
            'description' => 'required',
            'price' => 'required',
            'photo' => 'required'

        ]);
        if($validated)
        {
            $mod_burger = Burger::find($id);
            if($mod_burger!= null)
            {
                $mod_burger->id_burger=$request->id_burger;
                $mod_burger->description = $request->description;
                $mod_burger->price = $request->price;
                $mod_burger->photo = $request->photo;
                $mod_burger->save();
                return redirect('zarzadzanie/burger/list');
            }
            else
            {
                $error_message = "Burger id=$id not found";
                return view('burger.message',['message'=>$error_message,'type_of_message'=>"Error"]);
    
            }
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mod_burger = Burger::find($id);
        if($mod_burger != null)
        {
            $mod_burger->delete();
            return redirect('/burger/list');

        }
        else{
            $error_message = "Delete Burger id=$id not found";
            return view('burger.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
    }
    public function ListBurgers()
    {
        $myBurgers = Burger::all();
        $data = ["data"=>$myBurgers];
        return response()->json($data,200);
    }

    public function NewBurger(BurgerRequest $request){
        $data = $request->validated();

        $mod_burger = new Burger();
        $mod_burger->id_burger = $data['id_burger'];
        $mod_burger->description = $data['description'];
        $mod_burger->price = $data['price'];
        $mod_burger->photo = $data['photo'];

        $mod_burger->save();
        response()->json(['data'=>$mod_burger]);
        return redirect('/zarzadzanie/burger/list');
    }
    public function DeleteBurger($id){
        $mod_burger = Burger::find($id);
        if($mod_burger != null){
            $mod_burger->delete();
            response()->json(['data'=>$mod_burger]);
            return redirect('/zarzadzanie/burger/list');
        }else
        response()->json(['data'=>[]]);
        return redirect('/zarzadzanie/burger/list');
    }

    public function UpdateBurger($id, BurgerRequest $request){
        $data = $request->validated();
        $mod_burger = Burger::find($id);
        if($mod_burger != null){
            $mod_burger->id_burger = $data['id_burger'];
            $mod_burger->description = $data['description'];
            $mod_burger->price = $data['price'];
            $mod_burger->photo = $data['photo'];
            $mod_burger->save();
            return response()->json(['data'=>[]]);
        }
    }
}
