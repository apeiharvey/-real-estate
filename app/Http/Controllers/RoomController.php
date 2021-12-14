<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\House;
use Illuminate\Support\Str;
use Auth;
class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['website_key'] = $this->website_key;
        $data['rooms'] =Room::select('rooms.id','rooms.images','rooms.status','houses.name as house_name','rooms.name as room_name')
        ->leftJoin('houses','houses.id','=','rooms.house_id')
        ->where('type','room')
        ->where('website_key',Session::get('website_key'))
        ->orderBy('id','DESC')
        ->paginate(10);
        return view('backend.room.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $data['website_key'] = $this->website_key;
        return view('backend.room.create',$data);
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
        $data['website_key'] = Session::get('website_key');
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
        $data['room']=Room::findOrFail($id);
        $data['houses']=House::where('website_key',Session::get('website_key'))->get();
        $data['website_key'] = $this->website_key;
        return view('backend.room.edit',$data);
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
        $data['website_key'] = Session::get('website_key');
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
