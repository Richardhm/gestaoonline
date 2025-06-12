<?php
namespace App\Http\Controllers;

use App\Models\AC;
use App\Models\ACN2;
use App\Models\AR;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        // Apenas 1 query otimizada, carrega todos os relacionamentos
        $acs = \App\Models\AC::with([
            'acn2s.ars'
        ])->get();

        return view('dashboard', compact('acs'));
    }





    public function importarJson(Request $request)
    {
        try {
            $request->validate([
                'arquivo_json' => 'required|file|mimes:json'
            ]);

            $dados = json_decode(file_get_contents($request->file('arquivo_json')), true);

            if (!is_array($dados)) {
                return back()->with('erro', 'Arquivo JSON inválido.');
            }



            foreach ($dados as $acDado) {
                $ac = AC::create($acDado['ac']);
                if (!empty($acDado['acn2s'])) {
                    foreach ($acDado['acn2s'] as $acn2Dado) {
                        $acn2Fields = $acn2Dado['acn2'];
                        $acn2Fields['ac_id'] = $ac->id;
                        $acn2 = ACN2::create($acn2Fields);
                        if (!empty($acn2Dado['ars'])) {
                            foreach ($acn2Dado['ars'] as $arDado) {
                                $arFields = $arDado['ar'];
                                $arFields['acn2_id'] = $acn2->id;
                                AR::create($arFields);
                            }
                        }
                    }
                }
            }

            return back()->with('msg', 'Importação concluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('erro', 'Falha ao importar JSON: ' . $e->getMessage());
        }
    }
}
