<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Support\Facades\Auth;

//use app\Models\Employee;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myAPI_URL = config('app.url')."/api/employee/list";
        $res=Http::withBasicAuth('student','student')->get($myAPI_URL);
        $myEmployees=json_decode(json_encode($res->json()['data']),FALSE);
        $user = Auth::user();

        return view('employee.list',['employee'=>$myEmployees,'user'=>$user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $myAPI_URL=config('app.url')."/api/employee/new";
        $response=Http::post($myAPI_URL, $request);
        return redirect('/employee/list');
    }
    

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $myEmployee = Employee::find($id);

        if($myEmployee == null){
            $error_message = "Employee id=".$id." not found";
            return view('employee.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
        if($myEmployee->count()>0)
        return view('employee.edit',['employee' => $myEmployee,]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'id_employee' => 'required',
            'name' => 'required',
            'last_name' => 'required',
        ]);
        if($validated)
        {
            $mod_employee = Employee::find($id);
            if($mod_employee!= null)
            {
                $mod_employee->id_employee=$request->id_employee;
                $mod_employee->name = $request->name;
                $mod_employee->last_name = $request->last_name;
                $mod_employee->save();
                return redirect('/zarzadzanie/employee/list');
            }
            else
            {
                $error_message = "Employee id=$id not found";
                return view('employee.message',['message'=>$error_message,'type_of_message'=>"Error"]);
    
            }
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mod_employee = Employee::find($id);
        if($mod_employee != null)
        {
            $mod_employee->delete();
            return redirect('/employee/list');

        }
        else{
            $error_message = "Delete Employee id=$id not found";
            return view('employee.message',['message'=>$error_message,'type_of_message'=>'Error']);

        }
    }

    public function ListEmployees()
    {
        $myEmployees = Employee::all();
        $data = ["data"=>$myEmployees];
        return response()->json($data,200);
    }

    public function NewEmployee(EmployeeRequest $request){
        $data = $request->validated();

        $mod_employee = new Employee();
        $mod_employee->id_employee = $data['id_employee'];
        $mod_employee->name = $data['name'];
        $mod_employee->last_name = $data['last_name'];

        $mod_employee->save();
        response()->json(['data'=>$mod_employee]);
        return redirect('/zarzadzanie/employee/list');
    }

    public function DeleteEmployee($id){
        $mod_employee = Employee::find($id);
        if($mod_employee != null){
            $mod_employee->delete();
            response()->json(['data'=>$mod_employee]);
            return redirect ('/zarzadzanie/employee/list');
        }else
        response()->json(['data'=>[]]);
        return redirect ('/zarzadzanie/employee/list');
    }

    public function UpdateEmployee($id, EmployeeRequest $request){
        $data = $request->validated();
        $mod_employee = Employee::find($id);
        if($mod_employee != null){
            $mod_employee->id_employee = $data['id_employee'];
            $mod_employee->name = $data['name'];
            $mod_employee->last_name = $data['last_name'];
            $mod_employee->save();
            return response()->json(['data'=>[]]);
        }
    }

}
