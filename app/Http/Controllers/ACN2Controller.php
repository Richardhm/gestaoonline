<?php

namespace App\Http\Controllers;

use App\Models\AC;
use App\Models\ACN2;
use Illuminate\Http\Request;

class ACN2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $acn2s = ACN2::with('ac')->orderBy('id', 'desc')->get();
        $acs = AC::all();
        return view('acn2s.index', compact('acn2s', 'acs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ac_id' => 'required|exists:acs,id',
            'nome' => 'required',
        ]);
        ACN2::create($request->only('ac_id','nome','tipo','situacao'));
        return redirect()->route('acn2s.index')->with('msg','AC N2 cadastrada!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ACN2 $aCN2)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ACN2 $aCN2)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ACN2 $acn2)
    {
        $request->validate([
            'ac_id' => 'required|exists:acs,id',
            'nome' => 'required',
        ]);
        $acn2->update($request->only('ac_id','nome','telefone','situacao'));
        return redirect()->route('acn2s.index')->with('msg','AC N2 atualizada!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ACN2 $acn2)
    {
        $acn2->delete();
        return back()->with('msg','AC N2 excluída!');
    }
}
