<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cluster;
use Illuminate\Support\Str;
use Auth;
class ClusterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()

    {
        $clusters=Cluster::orderBy('id','DESC')->paginate(10);
        return view('backend.cluster.index')->with('clusters',$clusters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.cluster.create');
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
            'photo'=>'string|required',
            'status'=>'required|in:active,inactive',
        ]);

        $data=$request->all();
        $data['created_by'] = Auth::user()->id;
        // return $slug;
        $status=House::create($data);
        if($status){
            request()->session()->flash('success','Cluster successfully added');
        }
        else{
            request()->session()->flash('error','Error occurred while adding cluster');
        }
        return redirect()->route('cluster.index');
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
        return view('backend.cluster.edit')->with('house',$house);
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
        $cluster=Cluster::findOrFail($id);
        $this->validate($request,[
            'name'=>'string|required',
            'photo'=>'string|required',
            'status'=>'required|in:active,inactive',
        ]);
        $data=$request->all();
        $data['updated_by'] = Auth::user()->id;
        $status=$cluster->fill($data)->save();
        if($status){
            request()->session()->flash('success','Cluster successfully updated');
        }
        else{
            request()->session()->flash('error','Error occurred while updating cluster');
        }
        return redirect()->route('cluster.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cluster=Cluster::findOrFail($id);
        $status=$cluster->delete();
        if($status){
            request()->session()->flash('success','Cluster successfully deleted');
        }
        else{
            request()->session()->flash('error','Error occurred while deleting cluster');
        }
        return redirect()->route('cluster.index');
    }
}
