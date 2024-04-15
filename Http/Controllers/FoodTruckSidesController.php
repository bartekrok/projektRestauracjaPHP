<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodTruckSides;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\FoodTruckSidesRequest;
use Illuminate\Support\Facades\Auth;


class FoodTruckSidesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myAPI_URL = config('app.url')."/api/foodtrucksides/list";
        $res=Http::withBasicAuth('student','student')->get($myAPI_URL);
        $myFoodTruckSides=json_decode(json_encode($res->json()['data']),FALSE);
        $user = Auth::user();

        return view('foodtrucksides.list',['foodtrucksides'=>$myFoodTruckSides,'user'=>$user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('foodtrucksides.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $myAPI_URL=config('app.url')."/api/foodtrucksides/new";
        $response=Http::post($myAPI_URL, $request);
        return redirect('/foodtrucksides/list');
    }

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $myFoodTruckSides = FoodTruckSides::find($id);

        if($myFoodTruckSides == null){
            $error_message = "Food Truck Sides id=".$id." not found";
            return view('foodtrucksides.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
        if($myFoodTruckSides->count()>0)
        return view('foodtrucksides.edit',['foodtrucksides' => $myFoodTruckSides,]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            
            'id_foodtruck' => 'required',
            'id_sides' => 'required'

        ]);
        if($validated)
        {
            $mod_foodtrucksides = FoodTruckSides::find($id);
            if($mod_foodtrucksides!= null)
            {
                $mod_foodtrucksides->id=$request->id;
                $mod_foodtrucksides->id_foodtruck = $request->id_foodtruck;
                $mod_foodtrucksides->id_sides = $request->id_sides;
                $mod_foodtrucksides->save();
                return redirect('/zarzadzanie/foodtrucksides/list');
            }
            else
            {
                $error_message = "Food Truck sides id=$id not found";
                return view('foodtrucksides.message',['message'=>$error_message,'type_of_message'=>"Error"]);
    
            }
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mod_foodtrucksides = FoodTruckSides::find($id);
        if($mod_foodtrucksides != null)
        {
            $mod_foodtrucksides->delete();
            return redirect('/foodtrucksides/list');

        }
        else{
            $error_message = "Delete Food Truck sides id=$id not found";
            return view('foodtrucksides.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
    }

    public function ListFoodTrucksSides()
    {
        $myFoodTrucksSides = FoodTruckSides::all();
        $data = ["data"=>$myFoodTrucksSides];
        return response()->json($data,200);
    }

    public function NewFoodTruckSides(FoodTruckSidesRequest $request){
        $data = $request->validated();

        $mod_foodtrucksides = new FoodTruckSides();
        $mod_foodtrucksides->id_foodtruck = $data['id_foodtruck'];
        $mod_foodtrucksides->id_sides = $data['id_sides'];

        $mod_foodtrucksides->save();
        response()->json(['data'=>$mod_foodtrucksides]);
        return redirect('zarzadzanie/foodtrucksides/list');
    }

    public function DeleteFoodTruckSides($id){
        $mod_foodtrucksides = FoodTruckSides::find($id);
        if($mod_foodtrucksides != null){
            $mod_foodtrucksides->delete();
            response()->json(['data'=>$mod_foodtrucksides]);
            return redirect('zarzadzanie/foodtrucksides/list');
        }else
        response()->json(['data'=>[]]);
        return redirect('zarzadzanie/foodtrucksides/list');
    }

    public function UpdateFoodTruckSides($id, FoodTruckSidesRequest $request){
        $data = $request->validated();
        $mod_foodtrucksides = FoodTruckSides::find($id);
        if($mod_foodtrucksides != null){
            $mod_foodtrucksides->id = $id;
            $mod_foodtrucksides->id_foodtruck = $data['id_foodtruck'];
            $mod_foodtrucksides->id_sides = $data['id_sides'];
            $mod_foodtrucksides->save();
            return response()->json(['data'=>[]]);
        }
    }
}
