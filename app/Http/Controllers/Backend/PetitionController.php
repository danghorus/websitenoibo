<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
   
use App\Models\Petition;
use Illuminate\Http\Request;
  
class PetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $petitions = Petition::latest()->paginate(50);
    
        return view('petitions.index',compact('petitions'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
     
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('petitions.create');
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
            'user_fullname' => 'required',
            'petition_reason' => 'required',

        ]);
    
        Petition::create($request->all());
     
        return redirect()->route('petitions.index')
                        ->with('success','Petition created successfully.');
    }
     
    /**
     * Display the specified resource.
     *
     * @param  \App\Petition  $Petition
     * @return \Illuminate\Http\Response
     */
    public function show(Petition $petition)
    {
        return view('petitions.show',compact('petition'));
    } 
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function edit(Petition $petition)
    {
        return view('petitions.edit',compact('petition'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Petition $petition)
    {
        
        $petition -> update([

            'petition_status' => $request->petition_status,
        ]);

    
        return redirect()->route('petitions.index');
                
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Petition $petition)
    {
        $petition->delete();
    
        return redirect()->route('petitions.index')
                        ->with('success','Petition deleted successfully');
    }
}