<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class RestApiController extends Controller
{
    public function similarCode(FullNameRequest $request){
        return  $data = $request->validated();
                response()->json([
                'data'=> [
                "full_name"=> $data['first_name'] . ' ' . $data['last_name']
     ],
     ]);
    }
    
    public function similarCode2($first_name, $last_name){
        return response()->json([
            'data'=> [
            "full_name"=> $first_name . ' ' . $last_name
            ],
            ]);
    }

public function showWelcome()
 {
 return response()->json([
 'data'=> [
 "message"=> "Hello!"
 ],
 ]);
 }

 public function showFullNameFromPath($first_name, $last_name)
 {
 return similarCode2($first_name, $last_name);
 }

 public function showFullNameFromAttributesData(FullNameRequest $request)
 {
 return similarCode($request);
 }

 public function showFullNameFromQueryParams(FullNameRequest $request)
{
return similarCode($request);
}
}
