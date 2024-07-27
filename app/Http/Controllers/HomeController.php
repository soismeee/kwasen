<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\PenerimaanBansos;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index(){
        return view('home.index', [
            'title' => 'Halaman Utama',
            'penerima_bantuan' => PenerimaanBansos::where('validasi', 'Ya')->get()->count(),
            'penduduk' => Penduduk::get()->count(),
        ]);
    }

    public function laporan(){
        return view('laporan.index', [
            'title' => 'laporan',
        ]);
    }

    public function getLaporan(){
        try {
            $penduduk = PenerimaanBansos::with('penduduk')->select('*')->get();   
            return response()->json(['data' => $penduduk]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage]);
        }
    }

    public function cetakLaporan(){
        $penduduk = PenerimaanBansos::with('penduduk')->select('*');   
        if(request('status' !==null)){
            $penduduk = PenerimaanBansos::with('penduduk')->select('*')->where('validasi', request('status'));   
        }

        $periode = Periode::orderBy('created_at', 'desc')->get();
        $periode_terbaru = $periode['0'];

        // return $periode_terbaru;
        return view('laporan.cetak', [
            'title' => 'Cetak Laporan',
            'data' => $penduduk->get(),
            'periode' => $periode_terbaru
        ]);
    }

    public function profile(){
        return view('home.profile', [
            'title' => 'Profil user',
        ]);
    }

    public function update(Request $request)
    {
        $id = auth()->user()->id;
        $username = $request->username;
        
        $user = User::find($id);
        $dbusername = $user->username;
        
        if ($username == $dbusername) {
            return redirect('/profile')->with('pesan', 'Data tidak ada yang berubah');
        }else{
            $rules = $request->validate([
                'name' => 'required|max:255',
                'username' => 'required',
            ]);

            if ($request->password !== null) {
                $rules['password'] = Hash::make($request->password);
            }

            User::where('id', $id)->update($rules);
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect('/');
        }
    }
}
