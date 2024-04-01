<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DesaKelurahan;
use App\Models\Kriteria;
use App\Models\Penduduk;
use App\Models\PenerimaanBansos;
use App\Models\UserDesaKelurahan;
use Illuminate\Http\Request;

class BansosController extends Controller
{
    public function getPenerima(){
        try {
            if (auth()->user()->role == 1) {
                $penduduk = PenerimaanBansos::with('penduduk')->select('*')->get();   
            } else {
                $penduduk = PenerimaanBansos::with('penduduk')->select('*')->where('user_id', auth()->user()->id)->get();   
            }
            return response()->json(['data' => $penduduk]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage]);
        }
    }

    public function search(){
        if (auth()->user()->role == 1) {
            $pdd = Penduduk::select('id','nama', 'nik', 'alamat_lengkap', 'tanggal_lahir', 'jekel', 'nama_desa_kelurahan')->where('nama', request('cari'))->get();
        } else {   
            $userdskl = UserDesaKelurahan::where('user_id', auth()->user()->id)->first();
            $nama_dskl = $userdskl->nama_desa_kelurahan;
            $pdd = Penduduk::select('id','nama', 'nik', 'alamat_lengkap', 'tanggal_lahir', 'jekel', 'nama_desa_kelurahan')->where('nama_desa_kelurahan', $nama_dskl)->where('nama', request('cari'))->get();
        }
        
        foreach($pdd as $item){
            $id[] = $item->id;
        }
        // dd($penduduk);
        $penduduk = PenerimaanBansos::with('penduduk')->where('penduduk_id', $id)->get();
        return response()->json(['data' => $penduduk]);
    }

    public function penerimaBantuan(){
        return view('penerima_bantuan.dinas.index', [
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
            // penghasilan ortu
            'penghasilanortu_kurang_ya' => $penghasilanortu_kurang_ya = PenerimaanBansos::where('penghasilan_ortu', '<', 1000000)->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'penghasilanortu_samadengan_ya' => $penghasilanortu_samadengan_ya = PenerimaanBansos::where('penghasilan_ortu', '=', 1000000)->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'penghasilanortu_lebih_ya' => $penghasilanortu_lebih_ya = PenerimaanBansos::where('penghasilan_ortu', '>', 1000000)->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'totalpenghasilanortu_ya' => $penghasilanortu_kurang_ya+$penghasilanortu_samadengan_ya+$penghasilanortu_lebih_ya,
            
            'penghasilanortu_kurang_tidak' => $penghasilanortu_kurang_tidak = PenerimaanBansos::where('penghasilan_ortu', '<', 1000000)->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'penghasilanortu_samadengan_tidak' => $penghasilanortu_samadengan_tidak = PenerimaanBansos::where('penghasilan_ortu', '=', 1000000)->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'penghasilanortu_lebih_tidak' => $penghasilanortu_lebih_tidak = PenerimaanBansos::where('penghasilan_ortu', '>', 1000000)->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'totalpenghasilanortu_tidak' => $penghasilanortu_kurang_tidak+$penghasilanortu_samadengan_tidak+$penghasilanortu_lebih_tidak,

            // usia 
            'usiasatusdsepuluh_ya' => $usiasatusdsepuluh_ya = PenerimaanBansos::where('usia', '>', 0)->where('usia', '<=', 10)->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'usiasebelassdduapuluh_ya' => $usiasebelassdduapuluh_ya = PenerimaanBansos::where('usia', '>', 10)->where('usia', '<=', 20)->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'usiaduasatusdtigapuluh_ya' => $usiaduasatusdtigapuluh_ya = PenerimaanBansos::where('usia', '>', 20)->where('usia', '<=', 30)->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'totalusia_ya' => $usiasatusdsepuluh_ya+$usiasebelassdduapuluh_ya+$usiaduasatusdtigapuluh_ya,
            
            'usiasatusdsepuluh_tidak' => $usiasatusdsepuluh_tidak = PenerimaanBansos::where('usia', '>', 0)->where('usia', '<=', 10)->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'usiasebelassdduapuluh_tidak' => $usiasebelassdduapuluh_tidak = PenerimaanBansos::where('usia', '>', 10)->where('usia', '<=', 20)->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'usiaduasatusdtigapuluh_tidak' => $usiaduasatusdtigapuluh_tidak = PenerimaanBansos::where('usia', '>', 20)->where('usia', '<=', 30)->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'totalusia_tidak' => $usiasatusdsepuluh_tidak+$usiasebelassdduapuluh_tidak+$usiaduasatusdtigapuluh_tidak,

            // status
            'statusyatim_ya' => $statusyatim_ya = PenerimaanBansos::where('status', 'Yatim')->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'statuspiatu_ya' => $statuspiatu_ya = PenerimaanBansos::where('status', 'Piatu')->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'statusyatimpiatu_ya' => $statusyatimpiatu_ya = PenerimaanBansos::where('status', 'Yatim Piatu')->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'totalstatus_ya' => $statusyatim_ya+$statuspiatu_ya+$statusyatimpiatu_ya,
            
            'statusyatim_tidak' => $statusyatim_tidak = PenerimaanBansos::where('status', 'Yatim')->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'statuspiatu_tidak' => $statuspiatu_tidak = PenerimaanBansos::where('status', 'Piatu')->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'statusyatimpiatu_tidak' => $statusyatimpiatu_tidak = PenerimaanBansos::where('status', 'Yatim Piatu')->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'totalstatus_tidak' => $statusyatim_tidak+$statuspiatu_tidak+$statusyatimpiatu_tidak,

            // kia
            'kiaada_ya' => $kiaada_ya = PenerimaanBansos::where('kia', 'ya')->where('validasi', 'Ya')->count(), // untuk validasi yaa 
            'kiatidak_ya' => $kiatidak_ya = PenerimaanBansos::where('kia', 'tidak')->where('validasi', 'Ya')->count(), // untuk validasi yaa 
            'totalkia_ya' => $kiaada_ya+$kiatidak_ya,
            
            'kiaada_tidak' => $kiaada_tidak = PenerimaanBansos::where('kia', 'ya')->where('validasi', 'Tidak')->count(), // untuk validasi tidak 
            'kiatidak_tidak' => $kiatidak_tidak = PenerimaanBansos::where('kia', 'tidak')->where('validasi', 'Tidak')->count(), // untuk validasi tidak 
            'totalkia_tidak' => $kiaada_tidak+$kiatidak_tidak,

            // alamat
            'alamatbtg_ya' => $alamatbtg_ya = PenerimaanBansos::where('alamat', 'Batang')->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'alamatbukanbtg_ya' => $alamatbukanbtg_ya = PenerimaanBansos::where('alamat', '!==', 'Batang')->where('validasi', 'Ya')->count(), // untuk validasi yaa
            'totalalamat_ya' => $alamatbtg_ya+$alamatbukanbtg_ya,
            
            'alamatbtg_tidak' => $alamatbtg_tidak = PenerimaanBansos::where('alamat', 'Batang')->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'alamatbukanbtg_tidak' => $alamatbukanbtg_tidak = PenerimaanBansos::where('alamat', '!==', 'Batang')->where('validasi', 'Tidak')->count(), // untuk validasi tidak
            'totalalamat_tidak' => $alamatbtg_tidak+$alamatbukanbtg_tidak,
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
            
            // cadangan jika dibutuhkan untuk ditampilkan
            // $penerimaanbansos = PenerimaanBansos::all();
            // foreach($penerimaanbansos as $pb){
            //     ######################################################################################################################################
            //     // rumus perhitungan tidak
            //     switch (true) {
            //         case $pb->penghasilan_ortu < 1000000:
            //             $nilaipot_ya = $hasil['probabilitas']['penghasilanortu_kurang_ya']/$hasil['probabilitas']['totalpenghasilanortu_ya'];
            //             break;
            //         case $pb->penghasilan_ortu == 1000000:
            //             $nilaipot_ya = $hasil['probabilitas']['penghasilanortu_samadengan_ya']/$hasil['probabilitas']['totalpenghasilanortu_ya'];
            //             break;
            //         case $pb->penghasilan_ortu > 1000000:
            //             $nilaipot_ya = $hasil['probabilitas']['penghasilanortu_lebih_ya']/$hasil['probabilitas']['totalpenghasilanortu_ya'];
            //             break;
                    
            //         default:
            //             # code...
            //             break;
            //     }
                
            //     switch (true) {
            //         case $pb->usia > 0 || $pb->usia <= 10:
            //             $nilaiusia_ya = $hasil['probabilitas']['usiasatusdsepuluh_ya']/$hasil['probabilitas']['totalusia_ya'];
            //             break;
            //         case $pb->usia > 10 || $pb->usia <= 20:
            //             $nilaiusia_ya = $hasil['probabilitas']['usiasebelassdduapuluh_ya']/$hasil['probabilitas']['totalusia_ya'];
            //             break;
            //         case $pb->usia > 20 || $pb->usia <= 30:
            //             $nilaiusia_ya = $hasil['probabilitas']['usiaduasatusdtigapuluh_ya']/$hasil['probabilitas']['totalusia_ya'];
            //             break;
                    
            //         default:
            //             # code...
            //             break;
            //     }

            //     switch (true) {
            //         case $pb->status == "Yatim":
            //             $nilaistatus_ya = $hasil['probabilitas']['statusyatim_ya']/$hasil['probabilitas']['totalstatus_ya'];
            //             break;
            //         case $pb->status == "Piatu":
            //             $nilaistatus_ya = $hasil['probabilitas']['statuspiatu_ya']/$hasil['probabilitas']['totalstatus_ya'];
            //             break;
            //         case $pb->status == "Yatim Piatu":
            //             $nilaistatus_ya = $hasil['probabilitas']['statusyatimpiatu_ya']/$hasil['probabilitas']['totalstatus_ya'];
            //             break;
                    
            //         default:
            //             # code...
            //             break;
            //     }
                
            //     switch (true) {
            //         case $pb->kia == "ya":
            //             $nilaikia_ya = $hasil['probabilitas']['kiaada_ya']/$hasil['probabilitas']['totalkia_ya'];
            //             break;
            //         case $pb->kia == "tidak":
            //             $nilaikia_ya = $hasil['probabilitas']['kiatidak_ya']/$hasil['probabilitas']['totalkia_ya'];
            //             break;
                    
            //         default:
            //             # code...
            //             break;
            //     }
                
            //     switch (true) {
            //         case $pb->alamat == "Batang":
            //             $nilaialamat_ya = $hasil['probabilitas']['alamatbtg_ya']/$hasil['probabilitas']['totalalamat_ya'];
            //             break;
            //         case $pb->alamat !== "Batang":
            //             $nilaialamat_ya = $hasil['probabilitas']['alamatbukanbtg_ya']/$hasil['probabilitas']['totalalamat_ya'];
            //             break;
                    
            //         default:
            //             # code...
            //             break;
            //     }

            //     ######################################################################################################################################
            //     // rumus perhitungan tidak

            //     switch (true) {
            //         case $pb->penghasilan_ortu < 1000000:
            //             $nilaipot_tidak = $hasil['probabilitas']['penghasilanortu_kurang_tidak']/$hasil['probabilitas']['totalpenghasilanortu_tidak'];
            //             break;
            //         case $pb->penghasilan_ortu == 1000000:
            //             $nilaipot_tidak = $hasil['probabilitas']['penghasilanortu_samadengan_tidak']/$hasil['probabilitas']['totalpenghasilanortu_tidak'];
            //             break;
            //         case $pb->penghasilan_ortu > 1000000:
            //             $nilaipot_tidak = $hasil['probabilitas']['penghasilanortu_lebih_tidak']/$hasil['probabilitas']['totalpenghasilanortu_tidak'];
            //             break;
                    
            //         default:
            //             # code...
            //             break;
            //     }
                
            //     switch (true) {
            //         case $pb->usia > 0 || $pb->usia <= 10:
            //             $nilaiusia_tidak = $hasil['probabilitas']['usiasatusdsepuluh_tidak']/$hasil['probabilitas']['totalusia_tidak'];
            //             break;
            //         case $pb->usia > 10 || $pb->usia <= 20:
            //             $nilaiusia_tidak = $hasil['probabilitas']['usiasebelassdduapuluh_tidak']/$hasil['probabilitas']['totalusia_tidak'];
            //             break;
            //         case $pb->usia > 20 || $pb->usia <= 30:
            //             $nilaiusia_tidak = $hasil['probabilitas']['usiaduasatusdtigapuluh_tidak']/$hasil['probabilitas']['totalusia_tidak'];
            //             break;
                    
            //         default:
            //             # code...
            //             break;
            //     }

            //     switch (true) {
            //         case $pb->status == "Yatim":
            //             $nilaistatus_tidak = $hasil['probabilitas']['statusyatim_tidak']/$hasil['probabilitas']['totalstatus_tidak'];
            //             break;
            //         case $pb->status == "Piatu":
            //             $nilaistatus_tidak = $hasil['probabilitas']['statuspiatu_tidak']/$hasil['probabilitas']['totalstatus_tidak'];
            //             break;
            //         case $pb->status == "Yatim Piatu":
            //             $nilaistatus_tidak = $hasil['probabilitas']['statusyatimpiatu_tidak']/$hasil['probabilitas']['totalstatus_tidak'];
            //             break;
                    
            //         default:
            //             # code...
            //             break;
            //     }
                
            //     switch (true) {
            //         case $pb->kia == "ya":
            //             $nilaikia_tidak = $hasil['probabilitas']['kiaada_tidak']/$hasil['probabilitas']['totalkia_tidak'];
            //             break;
            //         case $pb->kia == "tidak":
            //             $nilaikia_tidak = $hasil['probabilitas']['kiatidak_tidak']/$hasil['probabilitas']['totalkia_tidak'];
            //             break;
                    
            //         default:
            //             # code...
            //             break;
            //     }
                
            //     switch (true) {
            //         case $pb->alamat == "Batang":
            //             $nilaialamat_tidak = $hasil['probabilitas']['alamatbtg_tidak']/$hasil['probabilitas']['totalalamat_tidak'];
            //             break;
            //         case $pb->alamat !== "Batang":
            //             $nilaialamat_tidak = $hasil['probabilitas']['alamatbukanbtg_tidak']/$hasil['probabilitas']['totalalamat_tidak'];
            //             break;
                    
            //         default:
            //             # code...
            //             break;
            //     }

            //     $hasilya = $hasil['totalya']*$nilaipot_ya*$nilaiusia_ya*$nilaistatus_ya*$nilaialamat_ya; //masukan rumusnya disini
            //     $hasiltidak = $hasil['totaltidak']*$nilaipot_tidak*$nilaiusia_tidak*$nilaistatus_tidak*$nilaialamat_tidak; //masukan rumusnya disini

            //     $masukanrumus[] = [
            //         'penduduk' => $pb->penduduk_id,
            //         'ya' => $hasilya,
            //         'tidak' => $hasiltidak,
            //         'hasil' =>  'hasil' //if else disini
            //     ];
            // }

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
        $columns = ['id', 'nama', 'nik', 'alamat_lengkap', 'tanggal_lahir', 'jekel', 'nama_desa_kelurahan' ];
        $orderBy = $columns[request()->input("order.0.column")];
        if (auth()->user()->role == 1) {
            $data = PenerimaanBansos::with('penduduk')->select('*');
        }else{
            $data = PenerimaanBansos::with('penduduk')->select('*')->where('user_id', auth()->user()->id);
        }

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

    public function edit($id){
        $penerima_bantuan = PenerimaanBansos::with('penduduk')->where('penduduk_id', $id)->first();
        return view('penerima_bantuan.edit', [
            'title' => "Penerima Bantuan",
            'dskl' =>DesaKelurahan::select('id', 'kec_id', 'nama_desakelurahan')->with('kecamatan')->get(),
            'data' => $penerima_bantuan,
        ]);
    }

    public function show($id){
        // dd($id);
        ################################################################################################
        // PERHITUNGAN 
        $hasil = $this->rumusnaivebayes();

        $pb = PenerimaanBansos::find($id);
        ######################################################################################################################################
        // rumus perhitungan ya
        
        if ($pb->penghasilan_ortu) {
            # code...
        } else {
            # code...
        }
        
        switch (true) {
            case $pb->penghasilan_ortu < 1000000:
                $nilaipot_ya = $hasil['probabilitas']['penghasilanortu_kurang_ya']/$hasil['probabilitas']['totalpenghasilanortu_ya'];
                break;
            case $pb->penghasilan_ortu == 1000000:
                $nilaipot_ya = $hasil['probabilitas']['penghasilanortu_samadengan_ya']/$hasil['probabilitas']['totalpenghasilanortu_ya'];
                break;
            case $pb->penghasilan_ortu > 1000000:
                $nilaipot_ya = $hasil['probabilitas']['penghasilanortu_lebih_ya']/$hasil['probabilitas']['totalpenghasilanortu_ya'];
                break;
            
            default:
                # code...
                break;
        }
        
        switch (true) {
            case $pb->usia > 0 || $pb->usia <= 10:
                $nilaiusia_ya = $hasil['probabilitas']['usiasatusdsepuluh_ya']/$hasil['probabilitas']['totalusia_ya'];
                break;
            case $pb->usia > 10 || $pb->usia <= 20:
                $nilaiusia_ya = $hasil['probabilitas']['usiasebelassdduapuluh_ya']/$hasil['probabilitas']['totalusia_ya'];
                break;
            case $pb->usia > 20 || $pb->usia <= 30:
                $nilaiusia_ya = $hasil['probabilitas']['usiaduasatusdtigapuluh_ya']/$hasil['probabilitas']['totalusia_ya'];
                break;
            
            default:
                # code...
                break;
        }

        switch (true) {
            case $pb->status == "Yatim":
                $nilaistatus_ya = $hasil['probabilitas']['statusyatim_ya']/$hasil['probabilitas']['totalstatus_ya'];
                break;
            case $pb->status == "Piatu":
                $nilaistatus_ya = $hasil['probabilitas']['statuspiatu_ya']/$hasil['probabilitas']['totalstatus_ya'];
                break;
            case $pb->status == "Yatim Piatu":
                $nilaistatus_ya = $hasil['probabilitas']['statusyatimpiatu_ya']/$hasil['probabilitas']['totalstatus_ya'];
                break;
            
            default:
                # code...
                break;
        }
        
        switch (true) {
            case $pb->kia == "ya":
                $nilaikia_ya = $hasil['probabilitas']['kiaada_ya']/$hasil['probabilitas']['totalkia_ya'];
                break;
            case $pb->kia == "tidak":
                $nilaikia_ya = $hasil['probabilitas']['kiatidak_ya']/$hasil['probabilitas']['totalkia_ya'];
                break;
            
            default:
                # code...
                break;
        }
        
        switch (true) {
            case $pb->alamat == "Batang":
                $nilaialamat_ya = $hasil['probabilitas']['alamatbtg_ya']/$hasil['probabilitas']['totalalamat_ya'];
                break;
            case $pb->alamat !== "Batang":
                $nilaialamat_ya = $hasil['probabilitas']['alamatbukanbtg_ya']/$hasil['probabilitas']['totalalamat_ya'];
                break;
            
            default:
                # code...
                break;
        }

        ######################################################################################################################################
        // rumus perhitungan tidak

        switch (true) {
            case $pb->penghasilan_ortu < 1000000:
                $nilaipot_tidak = $hasil['probabilitas']['penghasilanortu_kurang_tidak']/$hasil['probabilitas']['totalpenghasilanortu_tidak'];
                break;
            case $pb->penghasilan_ortu == 1000000:
                $nilaipot_tidak = $hasil['probabilitas']['penghasilanortu_samadengan_tidak']/$hasil['probabilitas']['totalpenghasilanortu_tidak'];
                break;
            case $pb->penghasilan_ortu > 1000000:
                $nilaipot_tidak = $hasil['probabilitas']['penghasilanortu_lebih_tidak']/$hasil['probabilitas']['totalpenghasilanortu_tidak'];
                break;
            
            default:
                # code...
                break;
        }
        
        switch (true) {
            case $pb->usia > 0 || $pb->usia <= 10:
                $nilaiusia_tidak = $hasil['probabilitas']['usiasatusdsepuluh_tidak']/$hasil['probabilitas']['totalusia_tidak'];
                break;
            case $pb->usia > 10 || $pb->usia <= 20:
                $nilaiusia_tidak = $hasil['probabilitas']['usiasebelassdduapuluh_tidak']/$hasil['probabilitas']['totalusia_tidak'];
                break;
            case $pb->usia > 20 || $pb->usia <= 30:
                $nilaiusia_tidak = $hasil['probabilitas']['usiaduasatusdtigapuluh_tidak']/$hasil['probabilitas']['totalusia_tidak'];
                break;
            
            default:
                # code...
                break;
        }

        switch (true) {
            case $pb->status == "Yatim":
                $nilaistatus_tidak = $hasil['probabilitas']['statusyatim_tidak']/$hasil['probabilitas']['totalstatus_tidak'];
                break;
            case $pb->status == "Piatu":
                $nilaistatus_tidak = $hasil['probabilitas']['statuspiatu_tidak']/$hasil['probabilitas']['totalstatus_tidak'];
                break;
            case $pb->status == "Yatim Piatu":
                $nilaistatus_tidak = $hasil['probabilitas']['statusyatimpiatu_tidak']/$hasil['probabilitas']['totalstatus_tidak'];
                break;
            
            default:
                # code...
                break;
        }
        
        switch (true) {
            case $pb->kia == "ya":
                $nilaikia_tidak = $hasil['probabilitas']['kiaada_tidak']/$hasil['probabilitas']['totalkia_tidak'];
                break;
            case $pb->kia == "tidak":
                $nilaikia_tidak = $hasil['probabilitas']['kiatidak_tidak']/$hasil['probabilitas']['totalkia_tidak'];
                break;
            
            default:
                # code...
                break;
        }
        
        switch (true) {
            case $pb->alamat == "Batang":
                $nilaialamat_tidak = $hasil['probabilitas']['alamatbtg_tidak']/$hasil['probabilitas']['totalalamat_tidak'];
                break;
            case $pb->alamat !== "Batang":
                $nilaialamat_tidak = $hasil['probabilitas']['alamatbukanbtg_tidak']/$hasil['probabilitas']['totalalamat_tidak'];
                break;
            
            default:
                # code...
                break;
        }

        $hasilya = $hasil['totalya']*$nilaipot_ya*$nilaiusia_ya*$nilaistatus_ya*$nilaialamat_ya; //masukan rumusnya disini
        $hasiltidak = $hasil['totaltidak']*$nilaipot_tidak*$nilaiusia_tidak*$nilaistatus_tidak*$nilaialamat_tidak; //masukan rumusnya disini
        $masukanrumus[] = [
            'penduduk' => $pb->penduduk_id,
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
        $bansos->penghasilan_ortu = preg_replace('/[^0-9]/', '', $request->penghasilan_ortu);
        $bansos->status = $request->status;
        // $bansos->kia = $request->kia == null ? 'tidak' : 'ya';
        // $bansos->alamat = $request->alamat;
        $bansos->update();

        $penduduk = Penduduk::findOrFail($bansos->penduduk_id);
        $penduduk->nama = $request->nama;
        $penduduk->nik = $request->nik;
        $penduduk->alamat_lengkap = $request->alamat_lengkap;
        $penduduk->tanggal_lahir = $request->tanggal_lahir;
        $penduduk->jekel = $request->jekel;
        $penduduk->desa_kelurahan_id = $request->nama_desa_kelurahan;
        $penduduk->update();
        return redirect('/pb');
    }

    public function destroy($id)
    {
        Penduduk::destroy($id);
        return redirect('/cek');
    }
}
