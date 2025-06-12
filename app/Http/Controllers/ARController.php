<?php

namespace App\Http\Controllers;

use App\Models\AR;
use Illuminate\Http\Request;

class ARController extends Controller
{
    public function index()
    {
        $ars = AR::with('acn2')->get();
        $acn2s = \App\Models\ACN2::all();
        return view('ars.index', compact('ars', 'acn2s'));
    }

    public function create()
    {
        // Busca todas as ACN2 para preencher o select
        $acn2s = ACN2::all();
        return view('ars.create', compact('acn2s'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'acn2_id' => 'required|exists:acn2s,id',
            'nome' => 'required|string',
            'tipo' => 'nullable|string',
            'situacao' => 'nullable|integer',
            'open' => 'nullable|boolean'
        ]);

        AR::create($request->all());

        return redirect()->route('ars.index')->with('success', 'AR criado com sucesso!');
    }

    public function show(AR $ar)
    {
        return view('ars.show', compact('ar'));
    }

    public function edit(AR $ar)
    {
        $acn2s = ACN2::all();
        return view('ars.edit', compact('ar', 'acn2s'));
    }

    public function update(Request $request, AR $ar)
    {

        $request->validate([
            'acn2_id' => 'required|exists:acn2s,id',
            'nome' => 'required|string',
            'tipo' => 'nullable|string',
            'situacao' => 'nullable|integer',
            'open' => 'nullable|boolean'
        ]);

        $ar->update($request->all());

        return redirect()->route('ars.index')->with('success', 'AR atualizado com sucesso!');
    }

    public function destroy(AR $ar)
    {
        $ar->delete();
        return redirect()->route('ars.index')->with('success', 'AR removido com sucesso!');
    }
}
