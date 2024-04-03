<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BansosController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PengajuanPKHController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

route::get('/', [HomeController::class, 'index'])->middleware('auth');
route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
route::get('/profile', [HomeController::class, 'profile'])->name('profile')->middleware('auth');
route::get('/login', [AuthController::class, 'login'])->name('login');
route::post('auth', [AuthController::class, 'authenticate'])->name('auth')->middleware('guest');
route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
route::post('/change_user', [HomeController::class, 'update'])->name('change_user')->middleware('auth');
route::patch('/status/{id}', [AuthController::class, 'status'])->name('status')->middleware('auth');

route::resource('/usr', UserController::class)->middleware('auth');
route::post('/json_p', [UserController::class, 'json'])->name('json_p')->middleware('auth');

route::resource('/periode', PeriodeController::class)->middleware('auth');
route::post('/json_prd', [PeriodeController::class, 'json'])->middleware('auth');

route::get('/wilayah', [WilayahController::class, 'index'])->middleware('auth');
route::get('/create_kec', [WilayahController::class, 'create_kecamatan'])->middleware('auth');
route::get('/edit_kec/{id}', [WilayahController::class, 'edit_kecamatan'])->middleware('auth');
route::get('/list_sdkl/{id}', [WilayahController::class, 'desakelurahan'])->middleware('auth');
route::get('/get_sdkl/{id}', [WilayahController::class, 'jsonDesakelurahan'])->middleware('auth');
route::get('/getid_dskl/{id}', [WilayahController::class, 'getDesakelurahan'])->middleware('auth');
route::put('/update_kec/{id}', [WilayahController::class, 'update_kecamatan'])->middleware('auth');
route::post('/json_kec', [WilayahController::class, 'json_kec'])->middleware('auth');
route::post('/save_dskl', [WilayahController::class, 'store_dskl'])->middleware('auth');
route::post('/save_kec', [WilayahController::class, 'store_kecamatan'])->middleware('auth');
route::delete('del_kec/{id}', [WilayahController::class, 'destroy_kecamatan'])->middleware('auth');

route::resource('/kriteria', KriteriaController::class)->middleware('auth');

route::get('/dpp/{id}', [PeriodeController::class, 'show'])->middleware('auth');
route::get('/json_dpp/{id}', [PeriodeController::class, 'getDataPeriode'])->middleware('auth');
route::get('/cek', [BansosController::class, 'cek'])->name('cek')->middleware('auth');
route::get('/pb', [BansosController::class, 'PenerimaBantuan'])->name('pb')->middleware('auth');
route::get('/lap', [HomeController::class, 'laporan'])->name('lap')->middleware('auth');
route::get('/get_lap', [HomeController::class, 'getLaporan'])->name('get_lap')->middleware('auth');
route::get('/cetak', [HomeController::class, 'cetakLaporan'])->name('cetak')->middleware('auth');
route::post('/json_pb', [BansosController::class, 'json'])->name('json_pb')->middleware('auth');

// user desa kelurahan
route::get('/get_pb', [BansosController::class, 'getPenerima'])->name('get_pb')->middleware('auth');
route::get('/pengajuan', [PengajuanPKHController::class, 'index'])->name('pengajuan')->middleware('auth');
route::get('/search', [BansosController::class, 'search'])->name('search')->middleware('auth');
route::post('/save', [PengajuanPKHController::class, 'store'])->name('save')->middleware('auth');
route::patch('/update_data/{id}', [BansosController::class, 'update'])->name('update_data')->middleware('auth');

route::get('/spb/{id}', [BansosController::class, 'show'])->name('spb')->middleware('auth');
route::get('/epb/{id}/edit', [BansosController::class, 'edit'])->name('spb')->middleware('auth');
route::post('/json_cek', [PengajuanPKHController::class, 'json'])->name('json_cek')->middleware('auth');

route::delete('/del_p/{id}', [BansosController::class, 'destroy'])->name('del_p')->middleware('auth');