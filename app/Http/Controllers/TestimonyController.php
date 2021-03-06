<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimony;
use Illuminate\Support\Str;
use Auth;
class TestimonyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query=Testimony::orderBy('id','DESC')->paginate(10);
        return view('backend.testimony.index')->with('testimonies',$query);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.testimony.create');
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
            'testimony_name'=>'string|required',
            'text'=>'string|required',
            'image'=>'string|required',
            'status'=>'required|in:active,inactive',
        ]);

        $data=$request->all();
        $data['created_by'] = Auth::user()->id;
        // return $slug;
        $status=Testimony::create($data);
        if($status){
            request()->session()->flash('success','Testimony successfully added');
        }
        else{
            request()->session()->flash('error','Error occurred while adding testimony');
        }
        return redirect()->route('testimony.index');
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
        $data['testimony']=Testimony::findOrFail($id);
        return view('backend.testimony.edit',$data);
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
        $query=Testimony::findOrFail($id);
        $this->validate($request,[
            'testimony_name'=>'string|required',
            'text'=>'string|required',
            'image'=>'string|required',
            'status'=>'required|in:active,inactive',
        ]);
        $data=$request->all();
        $data['updated_by'] = Auth::user()->id;
        $status=$query->fill($data)->save();
        if($status){
            request()->session()->flash('success','Testimony successfully updated');
        }
        else{
            request()->session()->flash('error','Error occurred while updating testimony');
        }
        return redirect()->route('testimony.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query=Testimony::findOrFail($id);
        $status=$query->delete();
        if($status){
            request()->session()->flash('success','Testimony successfully deleted');
        }
        else{
            request()->session()->flash('error','Error occurred while deleting testimony');
        }
        return redirect()->route('testimony.index');
    }
}
