<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beverage;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\BeverageRequest;
use Illuminate\Support\Facades\Auth;


class BeverageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myAPI_URL = config('app.url')."/api/beverage/list";
        $res=Http::withBasicAuth('student','student')->get($myAPI_URL);
        $myBeverages=json_decode(json_encode($res->json()['data']),FALSE);
        $user = Auth::user();

        return view('beverage.list',['beverage'=>$myBeverages,'user'=>$user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('beverage.add');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BeverageRequest $request)
    {
        $myAPI_URL=config('app.url')."/api/beverage/new";
        $response=Http::post($myAPI_URL, $request);
        return redirect('/zarzadzanie/beverage/list');
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $myBeverage = Beverage::find($id);

        if($myBeverage == null){
            $error_message = "Beverage id=".$id." not found";
            return view('beverage.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
        if($myBeverage->count()>0)
        return view('beverage.edit',['beverage' => $myBeverage,]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'id_beverage' => 'required',
            'description' => 'required',
            'price' => 'required',
            'photo' => 'required'

        ]);
        if($validated)
        {
            $mod_beverage = Beverage::find($id);
            if($mod_beverage!= null)
            {
                $mod_beverage->id_beverage=$request->id_beverage;
                $mod_beverage->description = $request->description;
                $mod_beverage->price = $request->price;
                $mod_beverage->photo = $request->photo;
                $mod_beverage->save();
                return redirect('/zarzadzanie/beverage/list');
            }
            else
            {
                $error_message = "Beverage id=$id not found";
                return view('beverage.message',['message'=>$error_message,'type_of_message'=>"Error"]);
    
            }
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mod_beverage = Beverage::find($id);
        if($mod_beverage != null)
        {
            $mod_beverage->delete();
            return redirect('/beverage/list');

        }
        else{
            $error_message = "Delete Beverage id=$id not found";
            return view('beverage.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
    }
    public function ListBeverages()
    {
        $myBeverages = Beverage::all();
        $data = ["data"=>$myBeverages];
        return response()->json($data,200);
    }

    public function NewBeverage(BeverageRequest $request){
        $data = $request->validated();

        $mod_beverage = new Beverage();
        $mod_beverage->id_beverage = $data['id_beverage'];
        $mod_beverage->description = $data['description'];
        $mod_beverage->price = $data['price'];
        $mod_beverage->photo = $data['photo'];

        $mod_beverage->save();
        //redirect('/zarzadzanie/beverage/list');
        response()->json(['data'=>$mod_beverage]);
        return redirect('/zarzadzanie/beverage/list');
    }

    public function DeleteBeverage($id){
        $mod_beverage = Beverage::find($id);
        if($mod_beverage != null){
            $mod_beverage->delete();
            response()->json(['data'=>$mod_beverage]);
            return redirect('/zarzadzanie/beverage/list');
        }else
        response()->json(['data'=>[]]);
        return redirect('/zarzadzanie/beverage/list');
    }

    public function UpdateBeverage($id, BeverageRequest $request){
        $data = $request->validated();
        $mod_beverage = Beverage::find($id);
        if($mod_beverage != null){
            $mod_beverage->id_beverage = $data['id_beverage'];
            $mod_beverage->description = $data['description'];
            $mod_beverage->price = $data['price'];
            $mod_beverage->photo = $data['photo'];
            $mod_beverage->save();
            return response()->json(['data'=>[]]);
        }
    }

}
