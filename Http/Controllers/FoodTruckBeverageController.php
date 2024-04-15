<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodTruckBeverage;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\FoodTruckBeverageRequest;
use Illuminate\Support\Facades\Auth;


class FoodTruckBeverageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myAPI_URL = config('app.url')."/api/foodtruckbeverage/list";
        $res=Http::withBasicAuth('student','student')->get($myAPI_URL);
        $myFoodTruckBeverages=json_decode(json_encode($res->json()['data']),FALSE);
        $user = Auth::user();

        return view('foodtruckbeverage.list',['foodtruckbeverage'=>$myFoodTruckBeverages,'user'=>$user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('foodtruckbeverage.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $myAPI_URL=config('app.url')."/api/foodtruckbeverage/new";
        $response=Http::post($myAPI_URL, $request);
        return redirect('/zarzadzanie/foodtruckbeverage/list');
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $myFoodTruckBeverage = FoodTruckBeverage::find($id);

        if($myFoodTruckBeverage == null){
            $error_message = "ID=".$id." not found";
            return view('foodtruckbeverage.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
        if($myFoodTruckBeverage->count()>0)
        return view('foodtruckbeverage.edit',['foodtruckbeverage' => $myFoodTruckBeverage,]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            
            'id_foodtruck' => 'required',
            'id_beverage' => 'required'

        ]);
        if($validated)
        {
            $mod_foodtruckbeverage = FoodTruckBeverage::find($id);
            if($mod_foodtruckbeverage!= null)
            {
                $mod_foodtruckbeverage->id=$id;
                $mod_foodtruckbeverage->id_foodtruck = $request->id_foodtruck;
                $mod_foodtruckbeverage->id_beverage = $request->id_beverage;
                $mod_foodtruckbeverage->save();
                return redirect('/zarzadzanie/foodtruckbeverage/list');
            }
            else
            {
                $error_message = "Food Truck Beverage id=$id not found";
                return view('foodtruckbeverage.message',['message'=>$error_message,'type_of_message'=>"Error"]);
    
            }
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mod_foodtruckbeverage = FoodTruckBeverage::find($id);
        if($mod_foodtruckbeverage != null)
        {
            $mod_foodtruckbeverage->delete();
            return redirect('/foodtruckbeverage/list');

        }
        else{
            $error_message = "Delete Food Truck Beverage id=$id not found";
            return view('foodtruckbeverage.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
    }

    public function ListFoodTrucksBeverage()
    {
        $myFoodTrucksBeverage = FoodTruckBeverage::all();
        $data = ["data"=>$myFoodTrucksBeverage];
        return response()->json($data,200);
    }

    public function NewFoodTruckBeverage(FoodTruckBeverageRequest $request){
        $data = $request->validated();

        $mod_foodtruckbeverage = new FoodTruckBeverage();
        $mod_foodtruckbeverage->id_foodtruck = $data['id_foodtruck'];
        $mod_foodtruckbeverage->id_beverage = $data['id_beverage'];

        $mod_foodtruckbeverage->save();
        response()->json(['data'=>$mod_foodtruckbeverage]);
        return redirect('/zarzadzanie/foodtruckbeverage/list');
    }

    public function DeleteFoodTruckBeverage($id){
        $mod_foodtruckbeverage = FoodTruckBeverage::find($id);
        if($mod_foodtruckbeverage != null){
            $mod_foodtruckbeverage->delete();
            response()->json(['data'=>$mod_foodtruckbeverage]);
            return redirect('/zarzadzanie/foodtruckbeverage/list');
        }else
        response()->json(['data'=>[]]);
        return redirect('/zarzadzanie/foodtruckbeverage/list');

    }

    public function UpdateFoodTruckBeverage($id, FoodTruckBeverageRequest $request){
        $data = $request->validated();
        $mod_foodtruckbeverage = FoodTruckBeverage::find($id);
        if($mod_foodtruckbeverage != null){
            $mod_foodtruckbeverage->id = $id;
            $mod_foodtruckbeverage->id_foodtruck = $data['id_foodtruck'];
            $mod_foodtruckbeverage->id_beverage = $data['id_beverage'];
            $mod_foodtruckbeverage->save();
            return response()->json(['data'=>[]]);
        }
    }

    
}
