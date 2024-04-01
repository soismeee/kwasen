<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DesaKelurahan;
use App\Models\Kecamatan;
use App\Models\Kriteria;
use App\Models\Penduduk;
use App\Models\PenerimaanBansos;
use App\Models\Periode;
use App\Models\UserDesaKelurahan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengajuanYapiController extends Controller
{
    public function index(){
        $periode = Periode::orderBy('created_at', 'desc')->get();
        if ($periode->count() == 0) {return view('home.forbiden', ['title' => 'Forbiden']);}
        $periode_terbaru = $periode['0'];

        $dskl = UserDesaKelurahan::where('user_id', auth()->user()->id)->first();
        $tabeldesakelurahan = DesaKelurahan::find($dskl->desa_kelurahan_id);


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
        
        
        return view('pengajuan_yapi.index', [ 
            'title' => 'Pengajuan YAPI',
            'dskl' => $tabeldesakelurahan,
            'kecamatan' => Kecamatan::find($tabeldesakelurahan->kec_id),
            'kriteria' => Kriteria::first(),
            'tanggal_periode' => $tanggal_periode,
            'maksimal_penerima' => $hasil
        ]);
    }

    public function json(){
        $columns = ['id', 'nama', 'nik', 'alamat_lengkap', 'tanggal_lahir', 'jekel', 'nama_desa_kelurahan' ];
        $orderBy = $columns[request()->input("order.0.column")];
        $data = Penduduk::select('id', 'nama', 'nik', 'alamat_lengkap', 'tanggal_lahir', 'jekel', 'nama_desa_kelurahan');

        if(request()->input("search.value")){
            $data = $data->where(function($query){
                $query->whereRaw('nama like ? ', ['%'.request()->input("search.value").'%'])
                ->orWhereRaw('nik like ? ', ['%'.request()->input("search.value").'%'])
                ->orWhereRaw('alamat_lengkap like ? ', ['%'.request()->input("search.value").'%'])
                ->orWhereRaw('tanggal_lahir like ? ', ['%'.request()->input("search.value").'%'])
                ->orWhereRaw('nama_desa_kelurahan like ? ', ['%'.request()->input("search.value").'%']);
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
                'jekels' => 'required',
                'nama_desa_kelurahans' => 'required',
                'alamat_lengkaps' => 'required',
                'penghasilan_ortus' => 'required',
                'statuss' => 'required',
            ]
        );

        foreach ($request->niks as $key => $value) {
            $today = Carbon::now();
            $kriteria = Kriteria::first();

            $penduduk = new Penduduk();
            $penduduk->id = intval((microtime(true) * 14000));
            $penduduk->nama = $request->namas[$key];
            $penduduk->nik = $request->niks[$key];
            $penduduk->alamat_lengkap = $request->alamat_lengkaps[$key];
            $penduduk->tanggal_lahir = $request->tanggal_lahirs[$key];
            $penduduk->jekel = $request->jekels[$key];
            $penduduk->desa_kelurahan_id = $request->nama_desa_kelurahans[$key];
            $penduduk->save();
            
            $usia = $today->diff($request->tanggal_lahirs[$key])->y;

            // $kia = $request->kias[$key] == $kriteria->kia ? 1 : 0;
            // $pot = preg_replace('/[^0-9]/', '', $request->penghasilan_ortus[$key]) <= $kriteria->penghasilan_ortu ? 1 : 0;
            // $umur = $usia <= $kriteria->usia ? 1 : 0;
            // if ($kia+$pot+$umur > 1) {
            //     $validasi = 'Ya';
            // }else{
            //     $validasi = 'Tidak';
            // }

            $bantuan = new PenerimaanBansos();
            $bantuan->id = intval((microtime(true) * 12000));
            $bantuan->penduduk_id = $penduduk->id;
            $bantuan->user_id = auth()->user()->id;
            $bantuan->usia = $usia;
            $bantuan->penghasilan_ortu = preg_replace('/[^0-9]/', '', $request->penghasilan_ortus[$key]);
            $bantuan->status = $request->statuss[$key];
            $bantuan->kia = $request->kias[$key];
            $bantuan->alamat = "Batang";
            $bantuan->validasi = $request->validasi[$key];
            $bantuan->save();
        }
        return response()->json(['message' => 'Data berhasil di simpan']);
    }
}
