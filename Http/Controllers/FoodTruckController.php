<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodTruck;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\FoodTruckRequest;
use App\Models\FoodTruckBurger;
use App\Models\FoodTruckBeverage;
use App\Models\FoodTruckSides;
use App\Models\Sides;
use Illuminate\Support\Facades\Auth;


class FoodTruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myAPI_URL = config('app.url')."/api/foodtruck/list";
        $res=Http::withBasicAuth('student','student')->get($myAPI_URL);
        $myFoodTrucks=json_decode(json_encode($res->json()['data']),FALSE);
        $user = Auth::user();

        return view('foodtruck.list',['foodtruck'=>$myFoodTrucks,'user'=>$user]);
    }
    public function unindex()
    {
        $myAPI_URL = config('app.url')."/api/foodtruck/list";
        $res=Http::withBasicAuth('student','student')->get($myAPI_URL);
        $myFoodTrucks=json_decode(json_encode($res->json()['data']),FALSE);
        return view('foodtruck.unauthorizedlist',['foodtruck'=>$myFoodTrucks,]);
    }

   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('foodtruck.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $myAPI_URL=config('app.url')."/api/foodtruck/new";
        $response=Http::post($myAPI_URL, $request);
        return redirect('/foodtruck/list');
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $myFoodTruck = FoodTruck::find($id);

        if($myFoodTruck == null){
            $error_message = "Foodtruck id=".$id." not found";
            return view('foodtruck.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
        if($myFoodTruck->count()>0)
        return view('foodtruck.edit',['foodtruck' => $myFoodTruck,]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'id_foodtruck' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'name' => 'required'

        ]);
        if($validated)
        {
            $mod_foodtruck = foodtruck::find($id);
            if($mod_foodtruck!= null)
            {
                $mod_foodtruck->id_foodtruck=$request->id_foodtruck;
                $mod_foodtruck->city = $request->city;
                $mod_foodtruck->postal_code = $request->postal_code;
                $mod_foodtruck->name = $request->name;
                $mod_foodtruck->save();
                return redirect('/zarzadzanie/foodtruck/list');
            }
            else
            {
                $error_message = "foodtruck id=$id not found";
                return view('foodtruck.message',['message'=>$error_message,'type_of_message'=>"Error"]);
    
            }
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mod_foodtruck = FoodTruck::find($id);
        if($mod_foodtruck != null)
        {
            $mod_foodtruck->delete();
            return redirect('/foodtruck/list');

        }
        else{
            $error_message = "Delete Foodtruck id=$id not found";
            return view('foodtruck.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
    }
    
    public function ListFoodTrucks()
    {
        $myFoodTrucks = FoodTruck::all();
        $data = ["data"=>$myFoodTrucks];
        return response()->json($data,200);
    }

    public function ListFoodTruck($id)
    {
        $myFoodTruck = FoodTruck::find($id);
        $data = ["data"=>$myFoodTruck];
        return response()->json($data,200);
    }

    public function NewFoodTruck(FoodTruckRequest $request){
        $data = $request->validated();

        $mod_foodtruck = new FoodTruck();
        $mod_foodtruck->id_foodtruck = $data['id_foodtruck'];
        $mod_foodtruck->city = $data['city'];
        $mod_foodtruck->postal_code = $data['postal_code'];
        $mod_foodtruck->name = $data['name'];
        $mod_foodtruck->id_supervisor = $data['id_supervisor'];

        $mod_foodtruck->save();
        response()->json(['data'=>$mod_foodtruck]);
        return redirect('/zarzadzanie/foodtruck/list');
    }

    public function DeleteFoodTruck($id){
        $mod_foodtruck = FoodTruck::find($id);
        if($mod_foodtruck != null){
            $mod_foodtruck->delete();
            response()->json(['data'=>$mod_foodtruck]);
            return redirect('/zarzadzanie/foodtruck/list');
        }else
        response()->json(['data'=>[]]);
        return redirect('/zarzadzanie/foodtruck/list');

    }

    public function UpdateFoodTruck($id, FoodTruckRequest $request){
        $data = $request->validated();
        $mod_foodtruck = FoodTruck::find($id);
        if($mod_foodtruck != null){
            $mod_foodtruck->id_foodtruck = $data['id_foodtruck'];
            $mod_foodtruck->city = $data['city'];
            $mod_foodtruck->postal_code = $data['postal_code'];
            $mod_foodtruck->name = $data['name'];
            $mod_foodtruck->id_supervisor = $data['id_supervisor'];
            $mod_foodtruck->save();
            return response()->json(['data'=>[]]);
        }
    }


    public function getBurgers($id){
        $myFoodTruckBurger = FoodTruck::with('foodTruckBurger')
            ->find($id);
    
        $data = ["data" => $myFoodTruckBurger];
        return response()->json($data, 200);
    }

    function findBurgersByFoodTruck($id_foodtruck)
{
    // Use the where clause to filter based on id_foodtruck
    $burgers = FoodTruckBurger::where('id_foodtruck', $id_foodtruck)
    ->leftJoin('burger', 'foodtruckburger.id_burger', '=', 'burger.id_burger')
        ->get(['burger.id_burger', 'burger.description', 'burger.price','burger.photo']);

        $data = ["data" => $burgers];
    return response()->json($data, 200);
}
function findBeveragesByFoodTruck($id_foodtruck)
{
    // Use the where clause to filter based on id_foodtruck
    $bevereges = FoodTruckBeverage::where('id_foodtruck', $id_foodtruck)
    ->leftJoin('beverage', 'foodtruckbeverage.id_beverage', '=', 'beverage.id_beverage')
        ->get(['beverage.id_beverage', 'beverage.description', 'beverage.price','beverage.photo']);

        $data = ["data" => $bevereges];
        return response()->json($data, 200);
}
/*function findSidesByFoodTruck($id_foodtruck)
{
    // Use the where clause to filter based on id_foodtruck
    $sides = FoodTruckSides::where('id_foodtruck', $id_foodtruck)
    ->leftJoin('sides', 'foodtrucksides.id_sides', '=', 'sides.id_sides')
        ->pluck('sides.id_sides', 'sides.description', 'sides.price','sides.photo');

        $data = ["data" => $sides];
        return response()->json($data, 200);
}*/

function findSidesByFoodTruck($id_foodtruck)
{
    // Use the where clause to filter based on id_foodtruck
    $sides = FoodTruckSides::where('id_foodtruck', $id_foodtruck)
        ->leftJoin('sides', 'foodtrucksides.id_sides', '=', 'sides.id_sides')
        ->get(['sides.id_sides', 'sides.description', 'sides.price', 'sides.photo']);

    $data = ["data" => $sides];
    return response()->json($data, 200);
}

function showMenuByFoodTruck($id_foodtruck){
    $burgers = $this->findBurgersByFoodTruck($id_foodtruck);
    $beverages = $this->findBeveragesByFoodTruck($id_foodtruck);
    $sides = $this->findSidesByFoodTruck($id_foodtruck);

    $myAPI_URLburger = config('app.url')."/api/burgers/$id_foodtruck";
    $myAPI_URLsides = config('app.url')."/api/sides/$id_foodtruck";
    $myAPI_URLbeverage = config('app.url')."/api/beverages/$id_foodtruck";

    $resburger=Http::withBasicAuth('student','student')->get($myAPI_URLburger);
    $resbeverage=Http::withBasicAuth('student','student')->get($myAPI_URLbeverage);
    $ressides=Http::withBasicAuth('student','student')->get($myAPI_URLsides);
    $myBurgers=json_decode(json_encode($resburger->json()['data']),FALSE);
    $myBeverages=json_decode(json_encode($resbeverage->json()['data']),FALSE);
    $mySides=json_decode(json_encode($ressides->json()['data']),FALSE);

    return view('menu.list',['burger'=>$myBurgers,'beverage'=>$myBeverages,'sides'=>$mySides]);


}

}
