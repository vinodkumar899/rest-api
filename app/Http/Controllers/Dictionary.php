<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
class Dictionary extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
         $messages = [
            'text.required' => 'Text is required',
        ];
        $validator = Validator::make($request->all(), [
            'text' =>'required',
        ],$messages);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'errors'=>$validator->errors(),'message'=>'Parameter Validation Failed'],400); 
        }
        else
        {
            $details=DB::table('dictionary')->select('id','Word','Meaning');
            if(isset($request->strict_search) && $request->strict_search)
            {   
                $details=$details->where('Word',$request->text);
            }
            else
            {
                $details=$details->whereRaw('LOWER(Word) REGEXP ? ',['^'.strtolower($request->text)]);
            }
            $details=$details->get();
            return response()->json(['status'=>TRUE,'response'=>$details],200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $messages = [
            'id.required' => 'Id is required',
            'meaning.required' => 'Meaning is required',
        ];
        $validator = Validator::make($request->all(), [
            'id' =>'required',
            'meaning' =>'required',
        ],$messages);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'errors'=>$validator->errors(),'message'=>'Parameter Validation Failed'],400); 
        }
        else
        {
            $id=$request->id;
            DB::table('dictionary')->where('id',$id)->update(['Meaning'=>$request->meaning]);
            $details=DB::table('dictionary')->select('Word')->where('id',$id)->first();
            if(isset($request->new_meanings) && !empty($request->new_meanings))
            {
                foreach ($request->new_meanings as $key => $value) {
                    $data['Meaning']=$value;
                    $data['Word']=isset($details->Word) && !empty($details->Word) ? $details->Word : '';
                    DB::table('dictionary')->insert($data);
                }
            }
            return response()->json(['status'=>TRUE,'response'=>'request submitted successfully'],200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
