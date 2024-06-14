<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Penduduk;
use App\Models\PenerimaanBansos;
use Illuminate\Http\Request;

class BansosController extends Controller
{
    public function getPenerima(){
        try {
            $penduduk = PenerimaanBansos::with('penduduk')->select('*')->get();   
            return response()->json(['data' => $penduduk]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage]);
        }
    }

    public function search(){
        $pdd = Penduduk::select('nik','nama', 'alamat_lengkap', 'tanggal_lahir', 'jenis_kelamin')->where('nama', 'like', '%'. request('cari') . '%')->get();
        
        foreach($pdd as $item){
            $id[] = $item->nik;
        }
        $penduduk = PenerimaanBansos::with('penduduk')->where('penduduk_nik', $id)->get();
        return response()->json(['data' => $penduduk]);
    }

    public function penerimaBantuan(){
        return view('penerima_bantuan.index', [
            'title' => 'Daftar Penerima Bantuan',
        ]);
    }

    public function rumusnaivebayes(){
        // probabilitas class
        $totaldata = PenerimaanBansos::count();
        $totalya = PenerimaanBansos::where('validasi', 'Ya')->count();
        $totaltidak = PenerimaanBansos::where('validasi', 'Tidak')->count();

        // probabilitas kategori
        $kriteria = Kriteria::first();
        
        $probabilitas[] = [
            // penghasilan
            'penghasilan_kurang_ya' => $penghasilan_kurang_ya = PenerimaanBansos::where('penghasilan', '<', $kriteria->penghasilan)->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'penghasilan_samadengan_ya' => $penghasilan_samadengan_ya = PenerimaanBansos::where('penghasilan', '=', $kriteria->penghasilan)->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'penghasilan_lebih_ya' => $penghasilan_lebih_ya = PenerimaanBansos::where('penghasilan', '>', $kriteria->penghasilan)->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'totalpenghasilan_ya' => $penghasilan_kurang_ya+$penghasilan_samadengan_ya+$penghasilan_lebih_ya,
            
            'penghasilan_kurang_tidak' => $penghasilan_kurang_tidak = PenerimaanBansos::where('penghasilan', '<', $kriteria->penghasilan)->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'penghasilan_samadengan_tidak' => $penghasilan_samadengan_tidak = PenerimaanBansos::where('penghasilan', '=', $kriteria->penghasilan)->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'penghasilan_lebih_tidak' => $penghasilan_lebih_tidak = PenerimaanBansos::where('penghasilan', '>', $kriteria->penghasilan)->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'totalpenghasilan_tidak' => $penghasilan_kurang_tidak+$penghasilan_samadengan_tidak+$penghasilan_lebih_tidak,

            // status
            'statusdisabilitas_ya' => $statusdisabilitas_ya = PenerimaanBansos::where('status', 'Disabilitas')->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'statuslansia_ya' => $statuslansia_ya = PenerimaanBansos::where('status', 'Lansia')->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'statusibuhamil_ya' => $statusibuhamil_ya = PenerimaanBansos::where('status', 'Ibu Hamil')->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'totalstatus_ya' => $statusdisabilitas_ya+$statuslansia_ya+$statusibuhamil_ya,
            
            'statusdisabilitas_tidak' => $statusdisabilitas_tidak = PenerimaanBansos::where('status', 'Disabilitas')->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'statuslansia_tidak' => $statuslansia_tidak = PenerimaanBansos::where('status', 'Lansia')->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'statusibuhamil_tidak' => $statusibuhamil_tidak = PenerimaanBansos::where('status', 'Ibu Hamil')->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'totalstatus_tidak' => $statusdisabilitas_tidak+$statuslansia_tidak+$statusibuhamil_tidak,

            // polriasn
            'polriasnya_ya' => $polriasnya_ya = PenerimaanBansos::where('polri_asn', 'Ya')->where('validasi', 'Ya')->count(), // untuk validasi yaa 
            'polriasntidak_ya' => $polriasntidak_ya = PenerimaanBansos::where('polri_asn', 'Tidak')->where('validasi', 'Ya')->count(), // untuk validasi yaa 
            'totalpolriasn_ya' => $polriasnya_ya+$polriasntidak_ya,
            
            'polriasnya_tidak' => $polriasnya_tidak = PenerimaanBansos::where('polri_asn', 'Ya')->where('validasi', 'Tidak')->count(), // untuk validasi tidak 
            'polriasntidak_tidak' => $polriasntidak_tidak = PenerimaanBansos::where('polri_asn', 'Tidak')->where('validasi', 'Tidak')->count(), // untuk validasi tidak 
            'totalpolriasn_tidak' => $polriasnya_tidak+$polriasntidak_tidak,

            // penerima bantuan lain
            'pblya_ya' => $pblya_ya = PenerimaanBansos::where('pbl', 'Penerima')->where('validasi', 'Ya')->count(), // untuk validasi yaa 
            'pbltidak_ya' => $pbltidak_ya = PenerimaanBansos::where('pbl', 'Bukan')->where('validasi', 'Ya')->count(), // untuk validasi yaa 
            'totalpbl_ya' => $pblya_ya+$pbltidak_ya,
            
            'pblya_tidak' => $pblya_tidak = PenerimaanBansos::where('pbl', 'Penerima')->where('validasi', 'Tidak')->count(), // untuk validasi tidak 
            'pbltidak_tidak' => $pbltidak_tidak = PenerimaanBansos::where('pbl', 'Bukan')->where('validasi', 'Tidak')->count(), // untuk validasi tidak 
            'totalpbl_tidak' => $pblya_tidak+$pbltidak_tidak,

            // DTKS
            'dtksya_ya' => $dtksya_ya = PenerimaanBansos::where('dtks', 'Belum')->where('validasi', 'Ya')->count(), // untuk validasi yaa 
            'dtkstidak_ya' => $dtkstidak_ya = PenerimaanBansos::where('dtks', 'Sudah')->where('validasi', 'Ya')->count(), // untuk validasi yaa 
            'totaldtks_ya' => $dtksya_ya+$dtkstidak_ya,
            
            'dtksya_tidak' => $dtksya_tidak = PenerimaanBansos::where('dtks', 'Belum')->where('validasi', 'Tidak')->count(), // untuk validasi tidak 
            'dtkstidak_tidak' => $dtkstidak_tidak = PenerimaanBansos::where('dtks', 'Sudah')->where('validasi', 'Tidak')->count(), // untuk validasi tidak 
            'totaldtks_tidak' => $dtksya_tidak+$dtkstidak_tidak,
            
        ];
        return $hasil[] = [
            'totaldata' => $totaldata,
            'totalya' => $totalya,
            'totaltidak' => $totaltidak,
            'probabilitas' => $probabilitas[0],
        ];
    }

    public function cek(){
        $cekdata = PenerimaanBansos::count();
        if ($cekdata > 0) {
            $hasil = $this->rumusnaivebayes();

            return view('cek_status_penerima.index', [
                'title' => 'Cek status Penerima',
                'totaldata' => $hasil['totaldata'],
                'totalya' => $hasil['totalya'],
                'totaltidak' => $hasil['totaltidak'],
                'probabilitas' => $hasil['probabilitas']
            ]);
        }else{
            return redirect('/');
        }
    }

    public function json(){
        $columns = ['id', 'nama', 'nik', 'alamat_lengkap', 'tanggal_lahir', 'jenis_kelamin' ];
        $orderBy = $columns[request()->input("order.0.column")];
        $data = PenerimaanBansos::with('penduduk')->select('*');

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

    public function edit($id){
        $penerima_bantuan = PenerimaanBansos::with('penduduk')->where('penduduk_nik', $id)->first();
        return view('penerima_bantuan.edit', [
            'title' => "Penerima Bantuan",
            'data' => $penerima_bantuan,
        ]);
    }

    public function show($id){
        // dd($id);
        ################################################################################################
        // PERHITUNGAN 
        $hasil = $this->rumusnaivebayes();

        $pb = PenerimaanBansos::find($id);
        $kriteria = Kriteria::first();
        ######################################################################################################################################
        // rumus perhitungan ya
        
        switch (true) {
            case $pb->penghasilan < $kriteria->penghasilan:
                $nilaipot_ya = $hasil['probabilitas']['penghasilan_kurang_ya']/$hasil['probabilitas']['totalpenghasilan_ya'];
                break;
            case $pb->penghasilan == $kriteria->penghasilan:
                $nilaipot_ya = $hasil['probabilitas']['penghasilan_samadengan_ya']/$hasil['probabilitas']['totalpenghasilan_ya'];
                break;
            case $pb->penghasilan > $kriteria->penghasilan:
                $nilaipot_ya = $hasil['probabilitas']['penghasilan_lebih_ya']/$hasil['probabilitas']['totalpenghasilan_ya'];
                break;
            
            default:
                # code...
                break;
        }

        switch (true) {
            case $pb->status == "Disabilitas":
                $nilaistatus_ya = $hasil['probabilitas']['statusdisabilitas_ya']/$hasil['probabilitas']['totalstatus_ya'];
                break;
            case $pb->status == "Lansia":
                $nilaistatus_ya = $hasil['probabilitas']['statuslansia_ya']/$hasil['probabilitas']['totalstatus_ya'];
                break;
            case $pb->status == "Ibu Hamil":
                $nilaistatus_ya = $hasil['probabilitas']['statusibuhamil_ya']/$hasil['probabilitas']['totalstatus_ya'];
                break;
            
            default:
                # code...
                break;
        }
        
        switch (true) {
            case $pb->polri_asn == "Ya":
                $nilaipolri_asn_ya = $hasil['probabilitas']['polriasnya_ya']/$hasil['probabilitas']['totalpolriasn_ya'];
                break;
            case $pb->polri_asn == "Tidak":
                $nilaipolri_asn_ya = $hasil['probabilitas']['polriasntidak_ya']/$hasil['probabilitas']['totalpolriasn_ya'];
                break;
            
            default:
                # code...
                break;
        }
        
        switch (true) {
            case $pb->pbl == "Penerima":
                $nilaipbl_ya = $hasil['probabilitas']['pblya_ya']/$hasil['probabilitas']['totalpbl_ya'];
                break;
            case $pb->pbl == "Bukan":
                $nilaipbl_ya = $hasil['probabilitas']['pbltidak_ya']/$hasil['probabilitas']['totalpbl_ya'];
                break;
            
            default:
                # code...
                break;
        }
        
        switch (true) {
            case $pb->dtks == "Belum":
                $nilaidtks_ya = $hasil['probabilitas']['dtksya_ya']/$hasil['probabilitas']['totaldtks_ya'];
                break;
            case $pb->dtks == "Sudah":
                $nilaidtks_ya = $hasil['probabilitas']['dtkstidak_ya']/$hasil['probabilitas']['totaldtks_ya'];
                break;
            
            default:
                # code...
                break;
        }
        

        ######################################################################################################################################
        // rumus perhitungan tidak

        switch (true) {
            case $pb->penghasilan < $kriteria->penghasilan:
                $nilaipot_tidak = $hasil['probabilitas']['penghasilan_kurang_tidak']/$hasil['probabilitas']['totalpenghasilan_tidak'];
                break;
            case $pb->penghasilan == $kriteria->penghasilan:
                $nilaipot_tidak = $hasil['probabilitas']['penghasilan_samadengan_tidak']/$hasil['probabilitas']['totalpenghasilan_tidak'];
                break;
            case $pb->penghasilan > $kriteria->penghasilan:
                $nilaipot_tidak = $hasil['probabilitas']['penghasilan_lebih_tidak']/$hasil['probabilitas']['totalpenghasilan_tidak'];
                break;
            
            default:
                # code...
                break;
        }

        switch (true) {
            case $pb->status == "Disabilitas":
                $nilaistatus_tidak = $hasil['probabilitas']['statusdisabilitas_tidak']/$hasil['probabilitas']['totalstatus_tidak'];
                break;
            case $pb->status == "Lansia":
                $nilaistatus_tidak = $hasil['probabilitas']['statuslansia_tidak']/$hasil['probabilitas']['totalstatus_tidak'];
                break;
            case $pb->status == "Ibu Hamil":
                $nilaistatus_tidak = $hasil['probabilitas']['statusibuhamil_tidak']/$hasil['probabilitas']['totalstatus_tidak'];
                break;
            
            default:
                # code...
                break;
        }
        
        switch (true) {
            case $pb->polri_asn == "Ya":
                $nilaipolri_asn_tidak = $hasil['probabilitas']['polriasnya_tidak']/$hasil['probabilitas']['totalpolriasn_tidak'];
                break;
            case $pb->polri_asn == "Tidak":
                $nilaipolri_asn_tidak = $hasil['probabilitas']['polriasntidak_tidak']/$hasil['probabilitas']['totalpolriasn_tidak'];
                break;
            
            default:
                # code...
                break;
        }
        
        switch (true) {
            case $pb->pbl == "Penerima":
                $nilaipbl_tidak = $hasil['probabilitas']['pblya_tidak']/$hasil['probabilitas']['totalpbl_tidak'];
                break;
            case $pb->pbl == "Bukan":
                $nilaipbl_tidak = $hasil['probabilitas']['pbltidak_tidak']/$hasil['probabilitas']['totalpbl_tidak'];
                break;
            
            default:
                # code...
                break;
        }
        
        switch (true) {
            case $pb->dtks == "Belum":
                $nilaidtks_tidak = $hasil['probabilitas']['dtksya_tidak']/$hasil['probabilitas']['totaldtks_tidak'];
                break;
            case $pb->dtks == "Sudah":
                $nilaidtks_tidak = $hasil['probabilitas']['dtkstidak_tidak']/$hasil['probabilitas']['totaldtks_tidak'];
                break;
            
            default:
                # code...
                break;
        }


        $hasilya = $hasil['totalya']*$nilaipot_ya*$nilaistatus_ya*$nilaipolri_asn_ya*$nilaipbl_ya*$nilaidtks_ya; //masukan rumusnya disini
        $hasiltidak = $hasil['totaltidak']*$nilaipot_tidak*$nilaistatus_tidak*$nilaipolri_asn_tidak*$nilaipbl_tidak*$nilaidtks_tidak; //masukan rumusnya disini
        $masukanrumus[] = [
            'penduduk' => $pb->penduduk_nik,
            'ya' => $hasilya,
            'tidak' => $hasiltidak,
            'hasil' =>  $hasilya > $hasiltidak ? "DITERIMA" : "TIDAK DITERIMA"
        ];
        return view('penerima_bantuan.show', [
            'title' => "Penerima Bantuan",
            'data' => PenerimaanBansos::with('penduduk')->find($id),
            'hasil' => $masukanrumus[0]['hasil']
        ]);
    }

    public function update(Request $request, $id){
        $bansos = PenerimaanBansos::findOrFail($id);
        $bansos->penghasilan = preg_replace('/[^0-9]/', '', $request->penghasilan);
        $bansos->status = $request->status;
        $bansos->polri_asn = $request->polri_asn;
        $bansos->pbl = $request->pbl;
        $bansos->dtks = $request->dtks;
        $bansos->update();

        $penduduk = Penduduk::findOrFail($bansos->penduduk_nik);
        $penduduk->nama = $request->nama;
        $penduduk->alamat_lengkap = $request->alamat_lengkap;
        $penduduk->tanggal_lahir = $request->tanggal_lahir;
        $penduduk->jenis_kelamin = $request->jenis_kelamin;
        $penduduk->update();
        return redirect('/cek');
    }

    public function destroy($id)
    {
        Penduduk::destroy($id);
        return redirect('/cek');
    }
}
