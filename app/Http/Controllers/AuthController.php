<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(){
        return view('auth/login', [
            'title' => 'Login',
        ]);
    }

    public function authenticate(Request $request)
    {
        // dd($request);
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ],
        [
            'username.required' => 'Username tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
        ]
        );

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (auth()->user()->status == 0) {
                Auth::logout();

                request()->session()->invalidate();
                request()->session()->regenerateToken();
                return redirect()->intended('/login');
            }else{
                return redirect()->intended('/');
            }
        }
        return back()->with('loginError', 'Login Failed!!!');
    }

    public function status(Request $request, $id){
        $rules = $request->validate([
            'status' => 'required',
        ]);

        try {
            $pengguna = User::findOrFail($id);
            $pengguna->status = $request->status;
            $pengguna->update();
            return response()->json(['message' => 'Status pengguna berhasil di ubah']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Status pengguna tidak berhasil di ubah']);
        }
    }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    }
}
