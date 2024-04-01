<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanBansos;
use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        return view('periode.index', [
            'title' => 'Periode'
        ]);
    }

    public function json(){
        $columns = ['id', 'periode','tanggal_mulai', 'tanggal_akhir', 'maksimal_penerima' ];
        $orderBy = $columns[request()->input("order.0.column")];
        $data = Periode::select('*')->orderBy('created_at', 'desc');

        if(request()->input("search.value")){
            $data = $data->where(function($query){
                $query->whereRaw('periode like ? ', ['%'.request()->input("search.value").'%'])
                ->orWhereRaw('tanggal_mulai like ? ', ['%'.request()->input("search.value").'%'])
                ->orWhereRaw('tanggal_akhir like ? ', ['%'.request()->input("search.value").'%'])
                ->orWhereRaw('maksimal_penerima like ? ', ['%'.request()->input("search.value").'%']);
            });
        }

        $recordsFiltered = $data->get()->count();
        $data = $data->skip(request()->input('start'))->take(request()->input('length'))->orderBy($orderBy,request()->input("order.0.dir"))->get();
        $recordsTotal = $data->count();
        return response()->json([
            'draw' => request()->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('periode.create', [
            'title' => 'Create periode'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode' =>'required',
            'tanggal_mulai' =>'required',
            'tanggal_akhir' =>'required',
            'maksimal_penerima' =>'required'
        ]);

        Periode::create($request->all());

        return redirect()->route('periode.index')->with('success', 'Periode berhasil ditambahkan');
    }

    public function show($id){
        $periode = Periode::find($id);
        return view('periode.show', [
            'title' => 'Periode',
            'periode' => $periode
        ]);
    }

    public function getDataPeriode($id){
        try {
            $periode = Periode::find($id);
            $penerimabansos = PenerimaanBansos::with('penduduk')->whereBetween('created_at', [$periode->tanggal_mulai, $periode->tanggal_akhir, $periode->tanggal_akhir])->get();
            return response()->json(['data' => $penerimabansos]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage]);
        }
    }

    public function edit($id)
    {
        return view('periode.edit', [
            'title' => 'Edit periode',
            'periode' => Periode::find($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = $request->validate([
            'periode' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
            'maksimal_penerima' => 'required',
        ]);

        Periode::where('id', $id)->update($rules);
        return redirect()->route('periode.index')->with('success', 'Periode berhasil diubah');
    }

    public function destroy($id)
    {
        Periode::destroy($id);
        return redirect('/periode');
    }
}
