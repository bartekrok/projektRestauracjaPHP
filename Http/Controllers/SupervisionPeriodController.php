<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupervisionPeriod;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\SupervisionPeriodRequest;


class SupervisionPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myAPI_URL = config('app.url')."/api/supervisionperiod/list";
        $res=Http::withBasicAuth('student','student')->get($myAPI_URL);
        $mySupervisionPeriods=json_decode(json_encode($res->json()['data']),FALSE);
        return view('supervisionperiod.list',['supervisionperiod'=>$mySupervisionPeriods,]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supervisionperiod.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $myAPI_URL=config('app.url')."/api/supervisionperiod/new";
        $response=Http::post($myAPI_URL, $request);
        return redirect('/supervisionperiod/list');
    }

  

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mySupervisionPeriod = SupervisionPeriod::find($id);

        if($mySupervisionPeriod == null){
            $error_message = "supervisionperiod id=".$id." not found";
            return view('supervisionperiod.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
        if($mySupervisionPeriod->count()>0)
        return view('supervisionperiod.edit',['supervisionperiod' => $mySupervisionPeriod,]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'id_supervisionperiod' => 'required',
            'description' => 'required',
            'price' => 'required',
            'photo' => 'required'

        ]);
        if($validated)
        {
            $mod_supervisionperiod = SupervisionPeriod::find($id);
            if($mod_supervisionperiod!= null)
            {
                $mod_supervisionperiod->id_supervision=$request->id_supervision;
                $mod_supervisionperiod->id_employee=$request->id_employee;
                $mod_supervisionperiod->id_foodtruck=$request->id_foodtruck;
                $mod_supervisionperiod->description = $request->description;
                $mod_supervisionperiod->start_date = $request->start_date;
                $mod_supervisionperiod->end_date = $request->end_date;
                $mod_supervisionperiod->save();
                return redirect('/supervisionperiod/list');
            }
            else
            {
                $error_message = "supervisionperiod id=$id not found";
                return view('supervisionperiod.message',['message'=>$error_message,'type_of_message'=>"Error"]);
    
            }
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mod_supervisionperiod = SupervisionPeriod::find($id);
        if($mod_supervisionperiod != null)
        {
            $mod_supervisionperiod->delete();
            return redirect('/supervisionperiod/list');

        }
        else{
            $error_message = "Delete supervisionperiod id=$id not found";
            return view('supervisionperiod.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
    }
    public function ListSupervisionPeriods()
    {
        $mySupervisionPeriods = SupervisionPeriod::all();
        $data = ["data"=>$mySupervisionPeriods];
        return response()->json($data,200);
    }

    public function NewSupervisionPeriod(SupervisionPeriodRequest $request){
        $data = $request->validated();

        $mod_supervisionperiod = new SupervisionPeriod();
        $mod_supervisionperiod->id_supervisionperiod = $data['id_supervisionperiod'];
        $mod_supervisionperiod->id_employee = $data['id_employee'];
        $mod_supervisionperiod->id_foodtruck = $data['id_foodtruck'];
        $mod_supervisionperiod->start_date = $data['start_date'];
        $mod_supervisionperiod->end_date = $data['end_date'];

        $mod_supervisionperiod->save();
        response()->json(['data'=>$mod_supervisionperiod]);
        return redirect('zarzadzanie/supervisionperiod/list');
    }

    public function DeleteSupervisionPeriod($id){
        $mod_supervisionperiod = SupervisionPeriod::find($id);
        if($mod_supervisionperiod != null){
            $mod_supervisionperiod->delete();
            response()->json(['data'=>$mod_supervisionperiod]);
            return redirect('zarzadzanie/supervisionperiod/list');
        }else
        response()->json(['data'=>[]]);
        return redirect('zarzadzanie/supervisionperiod/list');
    }

    public function UpdateSupervisionPeriod($id, SupervisionPeriodRequest $request){
        $data = $request->validated();
        $mod_supervisionperiod = SupervisionPeriod::find($id);
        if($mod_supervisionperiod != null){
            $mod_supervisionperiod->id_supervisionperiod = $data['id_supervisionperiod'];
            $mod_supervisionperiod->id_employee = $data['id_employee'];
            $mod_supervisionperiod->id_foodtruck = $data['id_foodtruck'];
            $mod_supervisionperiod->start_date = $data['start_date'];
            $mod_supervisionperiod->end_date = $data['end_date'];
            $mod_supervisionperiod->save();
            return response()->json(['data'=>[]]);
        }
    }
}
