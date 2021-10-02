<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserMortage;
use App\Models\House;
use Illuminate\Support\Str;
use Auth;
class UserMortgageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mortgage=UserMortage::select('user_mortages.id','user_mortages.email','user_mortages.phone_number',
        'houses.name as house_name','user_mortages.name','user_mortages.time_period','user_mortages.payment',
        'user_mortages.created_at')
        ->leftJoin('houses','houses.id','=','user_mortages.house_id')
        ->orderBy('id','DESC')
        ->paginate(25);
        return view('backend.user-mortgage.index')->with('mortgage',$mortgage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        return view('backend.room.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request,[
            'name'=>'string|required',
            'house_id'=>'required|exists:houses,id',
            'images'=>'string|required',
            'status'=>'required|in:active,inactive',
        ]);

        $data=$request->all();
        $data['created_by'] = Auth::user()->id;
        $data['type'] = 'room';
        // return $slug;
        $status=Room::create($data);
        if($status){
            request()->session()->flash('success','Facility successfully added');
        }
        else{
            request()->session()->flash('error','Error occurred while adding facility');
        }
        return redirect()->route('room.index');
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
        $data=array();
        $data['facility']=Room::findOrFail($id);
        return view('backend.facility.edit',$data);
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
        $query=Room::findOrFail($id);
        $this->validate($request,[
            'name'=>'string|required',
            'house_id'=>'required|exists:houses,id',
            'images'=>'string|required',
            'status'=>'required|in:active,inactive',
        ]);
        $data=$request->all();
        $data['updated_by'] = Auth::user()->id;
        $status=$query->fill($data)->save();
        if($status){
            request()->session()->flash('success','Room successfully updated');
        }
        else{
            request()->session()->flash('error','Error occurred while updating room');
        }
        return redirect()->route('room.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query=Room::findOrFail($id);
        $status=$query->delete();
        if($status){
            request()->session()->flash('success','Room successfully deleted');
        }
        else{
            request()->session()->flash('error','Error occurred while deleting room');
        }
        return redirect()->route('room.index');
    }
}
