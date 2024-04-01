<?php

namespace App\Http\Controllers;

use App\Models\DesaKelurahan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index(){
        return view('wilayah.index', [
            'title' => 'Wilayah',
        ]);
    }

    public function json_kec(){
        $columns = ['id', 'kode','nama_kecamatan' ];
        $orderBy = $columns[request()->input("order.0.column")];
        $data = Kecamatan::select('*');

        if(request()->input("search.value")){
            $data = $data->where(function($query){
                $query->whereRaw('kode like ? ', ['%'.request()->input("search.value").'%'])
                ->orWhereRaw('nama_kecamatan like ? ', ['%'.request()->input("search.value").'%']);
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

    public function create_kecamatan(){
        return view('wilayah.create', [
            'title' => 'Buat kecamatan',
        ]);
    }

    public function store_kecamatan(Request $request){
        $data = new Kecamatan();
        $data->id = intval((microtime(true) * 15000));
        $data->kode = $request->kode;
        $data->nama_kecamatan = $request->nama_kecamatan;
        $data->save();
        return redirect('/wilayah');
    }

    public function edit_kecamatan($id){
        return view('wilayah.edit',[
            'title' => 'Ubah kecamatan',
            'kecamatan' => Kecamatan::find($id)
        ]);
    }

    public function update_kecamatan(Request $request, $id){
        $data = Kecamatan::find($id);
        $data->kode = $request->kode;
        $data->nama_kecamatan = $request->nama_kecamatan;
        $data->update();
        return redirect('/wilayah');
    }

    public function destroy_kecamatan($id)
    {
        Kecamatan::destroy($id);
        return redirect('/wilayah');
    }
    ###############################################################################################

    public function desakelurahan($id){
        return view('wilayah.desakelurahan', [
            'title' => 'Desa kelurahan',
            'kecamatan' => Kecamatan::find($id),
            'desakelurahan' => DesaKelurahan::where('kec_id', $id)->get()
        ]);
    }

    public function jsonDesakelurahan($id){
        $desakelurahan = DesaKelurahan::where('kec_id', $id)->get();
        if ($desakelurahan->count() > 0) {
            return response()->json(['data' => $desakelurahan]);
        } else {
            return response()->json(['message' => 'tidak ada data desa/kelurahan di kecamatan ini'], 404);
        }
    }

    public function getDesakelurahan($id){
        try {
            $get = DesaKelurahan::findOrFail($id);
            return response()->json(['data' => $get]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'data desa/kelurahan tidak ditemukan'], 404);
        }
    }

    public function store_dskl(Request $request){
        $validate = $request->validate([
            'kec_id' =>'required',
            'kode' =>'required',
            'nama_desakelurahan' =>'required'
        ]);

        $id = $request->id;
        if ($id == null) {
            $validate['id'] = intval((microtime(true) * 10000));
            DesaKelurahan::create($validate);
            return response()->json(['message' => 'Data berhasil ditambahkan']);    
        } else {
            $get = DesaKelurahan::findOrFail($id);
            $get->update($validate);
            return response()->json(['message' => 'Data berhasil diubah']);
        }
    }
}
