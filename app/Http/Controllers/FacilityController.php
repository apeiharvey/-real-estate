<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Str;
use Auth;
use Session;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['website_key'] = $this->website_key;
        $data['facilities'] =Room::where('type','facility')
        ->where('website_key',Session::get('website_key'))
        ->orderBy('id','DESC')
        ->paginate(10);
        return view('backend.facility.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['website_key'] = $this->website_key;
        return view('backend.facility.create',$data);
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
            'images'=>'string|required',
            'status'=>'required|in:active,inactive',
        ]);

        $data=$request->all();
        $data['created_by'] = Auth::user()->id;
        $data['type'] = 'facility';
        $data['website_key'] = Session::get('website_key');
        // return $slug;
        $status=Room::create($data);
        if($status){
            request()->session()->flash('success','Promo successfully added');
        }
        else{
            request()->session()->flash('error','Error occurred while adding promo');
        }
        return redirect()->route('promo.index');
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
        $data['website_key'] = $this->website_key;
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
            'images'=>'string|required',
            'status'=>'required|in:active,inactive',
        ]);
        $data=$request->all();
        $data['updated_by'] = Auth::user()->id;
        $data['website_key'] = Session::get('website_key');
        $status=$query->fill($data)->save();
        if($status){
            request()->session()->flash('success','Facility successfully updated');
        }
        else{
            request()->session()->flash('error','Error occurred while updating facility');
        }
        return redirect()->route('promo.index');
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
            request()->session()->flash('success','Facility successfully deleted');
        }
        else{
            request()->session()->flash('error','Error occurred while deleting facility');
        }
        return redirect()->route('promo.index');
    }
}
