<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sides;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\SidesRequest;
use Illuminate\Support\Facades\Auth;


class SidesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myAPI_URL = config('app.url')."/api/sides/list";
        $res=Http::withBasicAuth('student','student')->get($myAPI_URL);
        $mySides=json_decode(json_encode($res->json()['data']),FALSE);
        $user = Auth::user();
        return view('sides.list',['sides'=>$mySides,'user'=>$user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sides.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $myAPI_URL=config('app.url')."/api/sides/new";
        $response=Http::post($myAPI_URL, $request);
        return redirect('/sides/list');
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mySides = Sides::find($id);

        if($mySides == null){
            $error_message = "Sides id=".$id." not found";
            return view('sides.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
        if($mySides->count()>0)
        return view('sides.edit',['sides' => $mySides,]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'id_sides' => 'required',
            'description' => 'required',
            'price' => 'required',
            'photo' => 'required'

        ]);
        if($validated)
        {
            $mod_sides = Sides::find($id);
            if($mod_sides!= null)
            {
                $mod_sides->id_sides=$request->id_sides;
                $mod_sides->description = $request->description;
                $mod_sides->price = $request->price;
                $mod_sides->photo = $request->photo;
                $mod_sides->save();
                return redirect('zarzadzanie/sides/list');
            }
            else
            {
                $error_message = "Sides id=$id not found";
                return view('sides.message',['message'=>$error_message,'type_of_message'=>"Error"]);
    
            }
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mod_sides = Sides::find($id);
        if($mod_sides != null)
        {
            $mod_sides->delete();
            return redirect('/sides/list');

        }
        else{
            $error_message = "Delete Sides id=$id not found";
            return view('sides.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
    }
    public function ListSides()
    {
        $mySides = Sides::all();
        $data = ["data"=>$mySides];
        return response()->json($data,200);
    }

    public function NewSides(SidesRequest $request){
        $data = $request->validated();

        $mod_sides = new Sides();
        $mod_sides->id_sides = $data['id_sides'];
        $mod_sides->description = $data['description'];
        $mod_sides->price = $data['price'];
        $mod_sides->photo = $data['photo'];

        $mod_sides->save();
        response()->json(['data'=>$mod_sides]);
        return redirect('/zarzadzanie/sides/list');
    }

    public function DeleteSides($id){
        $mod_sides = Sides::find($id);
        if($mod_sides != null){
            $mod_sides->delete();
            response()->json(['data'=>$mod_sides]);
            return redirect('/zarzadzanie/sides/list');
        }else
        response()->json(['data'=>[]]);
        return redirect('/zarzadzanie/sides/list');
    }

    public function UpdateSides($id, SidesRequest $request){
        $data = $request->validated();
        $mod_sides = Sides::find($id);
        if($mod_sides != null){
            $mod_sides->id_sides = $data['id_sides'];
            $mod_sides->description = $data['description'];
            $mod_sides->price = $data['price'];
            $mod_sides->photo = $data['photo'];
            $mod_sides->save();
            return response()->json(['data'=>[]]);
        }
    }
}
