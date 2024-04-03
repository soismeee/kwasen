<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DataDesaKelurahan;
use App\Models\Penduduk;
use App\Models\PenerimaanBansos;
use App\Models\User;
use App\Models\UserDesaKelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index(){
        return view('home.index', [
            'title' => 'Dashboard Utama',
            'penerima_bantuan' => PenerimaanBansos::get()->count(),
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
            if (request('dskl') == null) {
                $penduduk = PenerimaanBansos::with('penduduk')->select('*')->get();   
            } else {
                $penduduk = PenerimaanBansos::with('penduduk')->select('*')->where('nama_desa_kelurahan', request('dskl'))->get();   
            }
            
            return response()->json(['data' => $penduduk]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage]);
        }
    }

    public function cetakLaporan(){
        if (request('dskl') == null) {
            $penduduk = PenerimaanBansos::with('penduduk')->select('*')->get();   
        } else {
            $penduduk = PenerimaanBansos::with('penduduk')->select('*')->where('nama_desa_kelurahan', request('dskl'))->get();   
        }
        return view('laporan.cetak', [
            'title' => 'Cetak Laporan',
            'data' => $penduduk
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