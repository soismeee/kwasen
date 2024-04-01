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
            'kriteria' => Kriteria::select('id','penghasilan_ortu', 'usia', 'alamat', 'status', 'kia')->first()
        ]);
    }


    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        try {
            $kriteria = Kriteria::findOrFail($id);
            if ($request->simpan_perubahan == "penghasilan_ortu") {
                $kriteria->penghasilan_ortu = $request->penghasilan_ortu;
            }
            
            if ($request->simpan_perubahan == "usia") {
                $kriteria->usia = $request->usia;
            }

            if ($request->simpan_perubahan == "alamat") {
                $kriteria->alamat = $request->alamat;
            }

            if ($request->simpan_perubahan == "status") {
                $kriteria->status = $request->status;
            }

            if ($request->simpan_perubahan == "kia") {
                $kriteria->kia = $request->kia;
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
