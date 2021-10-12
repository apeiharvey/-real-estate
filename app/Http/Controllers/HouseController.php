<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use Illuminate\Support\Str;
use Auth;
class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()

    {
        $houses=House::orderBy('id','DESC')->paginate(10);
        return view('backend.house.index')->with('houses',$houses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.house.create');
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
            'images_thumbnail'=>'string|required',
            'status'=>'required|in:active,inactive',
            'price'=>'numeric|min:1'
        ]);

        $data=$request->all();
        $data['created_by'] = Auth::user()->id;
        // return $slug;
        $status=House::create($data);
        if($status){
            request()->session()->flash('success','Property successfully added');
        }
        else{
            request()->session()->flash('error','Error occurred while adding property');
        }
        return redirect()->route('unit-type.index');
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
        $house=House::findOrFail($id);
        return view('backend.house.edit')->with('house',$house);
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
        $house=House::findOrFail($id);
        $this->validate($request,[
            'name'=>'string|required',
            'images_thumbnail'=>'string|required',
            'status'=>'required|in:active,inactive',
            'price'=>'numeric|min:1'
        ]);
        $data=$request->all();
        $data['updated_by'] = Auth::user()->id;
        $status=$house->fill($data)->save();
        if($status){
            request()->session()->flash('success','House successfully updated');
        }
        else{
            request()->session()->flash('error','Error occurred while updating house');
        }
        return redirect()->route('unit-type.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $house=House::findOrFail($id);
        $status=$house->delete();
        if($status){
            request()->session()->flash('success','House successfully deleted');
        }
        else{
            request()->session()->flash('error','Error occurred while deleting house');
        }
        return redirect()->route('property.index');
    }
}
