<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        return view('kriteria.index', [
            'title' => 'Kriteria',
            'kriteria' => Kriteria::select('id','penghasilan', 'status', 'polri_asn', 'pbl', 'dtks')->first()
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $kriteria = Kriteria::findOrFail($id);
            if ($request->simpan_perubahan == "penghasilan") {
                $kriteria->penghasilan = $request->penghasilan;
            }
            
            if ($request->simpan_perubahan == "status") {
                $kriteria->status = $request->status;
            }

            if ($request->simpan_perubahan == "polri_asn") {
                $kriteria->polri_asn = $request->polri_asn;
            }

            if ($request->simpan_perubahan == "pbl") {
                $kriteria->pbl = $request->pbl;
            }

            if ($request->simpan_perubahan == "dtks") {
                $kriteria->dtks = $request->dtks;
            }
            $kriteria->update();
            return redirect('/kriteria');

        } catch (\Throwable $th) {
            return redirect('/kriteria');
        }
        
    }

    public function destroy($id)
    {
        //
    }
}
