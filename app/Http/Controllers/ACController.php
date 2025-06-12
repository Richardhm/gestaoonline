<?php

namespace App\Http\Controllers;

use App\Models\AC;
use Illuminate\Http\Request;

class ACController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $acs = AC::orderBy('id', 'desc')->get();
        return view('acs.index', compact('acs'));
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
    // Store
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
        ]);
        \App\Models\AC::create($request->only('nome','telefone','situacao'));
        return redirect()->route('acs.index')->with('msg','AC cadastrada!');
    }

    /**
     * Display the specified resource.
     */
    public function show(AC $aC)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AC $aC)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AC $ac)
    {
        $ac->update($request->only('nome','telefone','situacao'));
        return redirect()->route('acs.index')->with('msg','AC atualizada!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AC $ac)
    {
        $ac->delete();
        return back()->with('msg','AC excluída!');
    }
}
