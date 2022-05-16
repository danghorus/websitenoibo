<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Work;
use App\Models\User;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $works = Work::latest()->paginate(50);
        $users = User::all();
    
        return view('works.index',compact('works', 'users'));
    }
     
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('works.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'work_name' => 'required',
            'work_detail' => 'required',
        ]);
    
        Work::create($request->all());
     
        return redirect()->route('works.index')
                        ->with('success','Tạo mới công việc thành công');
    }
     
    /**
     * Display the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function show(Work $work)
    {
        return view('works.show',compact('work'));
    } 
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        return view('works.edit',compact('work'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Work $work)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
    
        $work->update($request->all());
    
        return redirect()->route('works.index')
                        ->with('success','Work updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work $work)
    {
        $work->delete();
    
        return redirect()->route('works.index')
                        ->with('success','Work deleted successfully');
    }
}
