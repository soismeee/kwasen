<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Penduduk;
use App\Models\PenerimaanBansos;
use App\Models\Periode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengajuanPKHController extends Controller
{
    public function index(){
        $periode = Periode::orderBy('created_at', 'desc')->get();
        if ($periode->count() == 0) {return view('home.forbiden', ['title' => 'Forbiden']);}
        $periode_terbaru = $periode['0'];


        // set periode
        $hari_ini = date('Y-m-d');
        if ($hari_ini >= $periode_terbaru['tanggal_mulai'] && $hari_ini <= $periode_terbaru['tanggal_akhir']) {
            $tanggal_periode = "masuk";
        } else {
            $tanggal_periode = "tidak";
        }

        // set maksimal penerima
        $penerima = PenerimaanBansos::whereBetween('created_at', [$periode_terbaru['tanggal_mulai'], $periode_terbaru['tanggal_akhir']])->get();
        if ($penerima->count() < $periode_terbaru['maksimal_penerima']) {
            $hasil = "lolos";
        } else {
            $hasil = "tidak";
        }
        
        return view('pengajuan_pkh.index', [ 
            'title' => 'Pengajuan PKH',
            'kriteria' => Kriteria::first(),
            'tanggal_periode' => $tanggal_periode,
            'maksimal_penerima' => $hasil
        ]);
    }

    public function json(){
        $columns = ['id', 'nama', 'nik', 'alamat_lengkap', 'tanggal_lahir', 'jekel' ];
        $orderBy = $columns[request()->input("order.0.column")];
        $data = Penduduk::select('id', 'nama', 'nik', 'alamat_lengkap', 'tanggal_lahir', 'jekel');

        if(request()->input("search.value")){
            $data = $data->where(function($query){
                $query->whereRaw('nama like ? ', ['%'.request()->input("search.value").'%'])
                ->orWhereRaw('nik like ? ', ['%'.request()->input("search.value").'%'])
                ->orWhereRaw('alamat_lengkap like ? ', ['%'.request()->input("search.value").'%'])
                ->orWhereRaw('tanggal_lahir like ? ', ['%'.request()->input("search.value").'%']);
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

    public function store(Request $request){
        $validate = $request->validate(
            [
                'namas' => 'required',
                'niks' => 'required',
                'tanggal_lahirs' => 'required',
                'jenis_kelamins' => 'required',
                'alamat_lengkaps' => 'required',

                'penghasilans' => 'required',
                'statuss' => 'required',
                'polri_asns' => 'required',
                'pbls' => 'required',
                'dtkss' => 'required',
            ]
        );

        foreach ($request->niks as $key => $value) {
            $kriteria = Kriteria::first();

            $penduduk = new Penduduk();
            $penduduk->nik = $request->niks[$key];
            $penduduk->nama = $request->namas[$key];
            $penduduk->alamat_lengkap = $request->alamat_lengkaps[$key];
            $penduduk->tanggal_lahir = $request->tanggal_lahirs[$key];
            $penduduk->jenis_kelamin = $request->jenis_kelamins[$key];
            $penduduk->save();
        

            $bantuan = new PenerimaanBansos();
            $bantuan->id = intval((microtime(true) * 12000));
            $bantuan->penduduk_nik = $penduduk->nik;
            $bantuan->penghasilan = preg_replace('/[^0-9]/', '', $request->penghasilans[$key]);
            $bantuan->status = $request->statuss[$key];
            $bantuan->polri_asn = $request->polri_asns[$key];
            $bantuan->pbl = $request->pbls[$key];
            $bantuan->dtks = $request->dtkss[$key];
            $bantuan->tanggal = date('Y-m-d');
            $bantuan->save();
        }
        return response()->json(['message' => 'Data berhasil di simpan']);
    }
}
