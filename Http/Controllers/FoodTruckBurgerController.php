<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodTruckBurger;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\FoodTruckBurgerRequest;
use Illuminate\Support\Facades\Auth;


class FoodTruckBurgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myAPI_URL = config('app.url')."/api/foodtruckburger/list";
        $res=Http::withBasicAuth('student','student')->get($myAPI_URL);
        $myFoodTruckBurgers=json_decode(json_encode($res->json()['data']),FALSE);
        $user = Auth::user();

        return view('foodtruckburger.list',['foodtruckburger'=>$myFoodTruckBurgers,'user'=>$user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('foodtruckburger.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $myAPI_URL=config('app.url')."/api/foodtruckburger/new";
        $response=Http::post($myAPI_URL, $request);
        return redirect('/foodtruckburger/list');
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $myFoodTruckBurger = FoodTruckBurger::find($id);

        if($myFoodTruckBurger == null){
            $error_message = "Food Truck burger id=".$id." not found";
            return view('foodtruckburger.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
        if($myFoodTruckBurger->count()>0)
        return view('foodtruckburger.edit',['foodtruckburger' => $myFoodTruckBurger,]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            
            'id_foodtruck' => 'required',
            'id_burger' => 'required'

        ]);
        if($validated)
        {
            $mod_foodtruckburger = FoodTruckBurger::find($id);
            if($mod_foodtruckburger!= null)
            {
                $mod_foodtruckburger->id=$request->id;
                $mod_foodtruckburger->id_foodtruck = $request->id_foodtruck;
                $mod_foodtruckburger->id_burger = $request->id_burger;
                $mod_foodtruckburger->save();
                return redirect('/zarzadzanie/foodtruckburger/list');
            }
            else
            {
                $error_message = "Food Truck burger id=$id not found";
                return view('foodtruckburger.message',['message'=>$error_message,'type_of_message'=>"Error"]);
    
            }
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mod_foodtruckburger = FoodTruckBurger::find($id);
        if($mod_foodtruckburger != null)
        {
            $mod_foodtruckburger->delete();
            return redirect('/foodtruckburger/list');

        }
        else{
            $error_message = "Delete Food Truck Burger id=$id not found";
            return view('foodtruckburger.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
    }
    public function ListFoodTrucksBurger()
    {
        $myFoodTrucksBurger = FoodTruckBurger::all();
        $data = ["data"=>$myFoodTrucksBurger];
        return response()->json($data,200);
    }

    public function NewFoodTruckBurger(FoodTruckBurgerRequest $request){
        $data = $request->validated();

        $mod_foodtruckburger = new FoodTruckBurger();
        $mod_foodtruckburger->id_foodtruck = $data['id_foodtruck'];
        $mod_foodtruckburger->id_burger = $data['id_burger'];

        $mod_foodtruckburger->save();
        response()->json(['data'=>$mod_foodtruckburger]);
        return redirect('/zarzadzanie/foodtruckburger/list');
    }

    public function DeleteFoodTruckBurger($id){
        $mod_foodtruckburger = FoodTruckBurger::find($id);
        if($mod_foodtruckburger != null){
            $mod_foodtruckburger->delete();
            response()->json(['data'=>$mod_foodtruckburger]);
            return redirect('/zarzadzanie/foodtruckburger/list');
        }else
        response()->json(['data'=>[]]);
        return redirect('/zarzadzanie/foodtruckburger/list');
    }

    public function UpdateFoodTruckBurger($id, FoodTruckBurgerRequest $request){
        $data = $request->validated();
        $mod_foodtruckburger = FoodTruckBurger::find($id);
        if($mod_foodtruckburger != null){
            $mod_foodtruckburger->id = $id;
            $mod_foodtruckburger->id_foodtruck = $data['id_foodtruck'];
            $mod_foodtruckburger->id_burger = $data['id_burger'];
            $mod_foodtruckburger->save();
            return response()->json(['data'=>[]]);
        }
    }
}
