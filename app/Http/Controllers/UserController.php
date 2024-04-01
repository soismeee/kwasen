<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DataDesaKelurahan;
use App\Models\DesaKelurahan;
use App\Models\User;
use App\Models\UserDesaKelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index', [
            'title' => 'Data pengguna',
        ]);
    }
    
    public function json(){
        $columns = ['id', 'name', 'username', 'role', 'status' ];
        $orderBy = $columns[request()->input("order.0.column")];
        $data = User::select('id', 'name', 'username', 'role', 'status');

        if(request()->input("search.value")){
            $data = $data->where(function($query){
                $query->whereRaw('name like ? ', ['%'.request()->input("search.value").'%'])
                ->orWhereRaw('username like ? ', ['%'.request()->input("search.value").'%']);
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
        return view('user.create', [
            'title' => 'Tambah data pengguna',
            'desa_kelurahan' => DesaKelurahan::with('kecamatan')->orderBy('kec_id', 'ASC')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate(
            [
                'username' => 'required|unique:users',
                'password' => 'required',
                'name' => 'required',
                'role' => 'required',
            ],
            [
                'username.required' => 'Username tidak boleh kosong',
                'username.unique' => 'Username sudah ada',
                'password.required' => 'Password tidak boleh kosong',
                'role.required' => 'Hak akses harus dipilih',
            ]
        );
        $validate['id'] = intval((microtime(true) * 10000));
        $validate['password'] = Hash::make($request->password);
        $validate['status'] = 1;
        User::create($validate);

        if($request->role == 2){
            $userdskl = new UserDesaKelurahan();
            $userdskl->id = intval((microtime(true) * 10000));
            $userdskl->user_id = $validate['id'];
            $userdskl->desa_kelurahan_id = $request->desa_kelurahan_id;
            $userdskl->save();
        }
        return redirect('/usr');
    }

    public function edit($id)
    {
        return view('user.edit', [
            'title' => 'Edit user',
            'user' => User::find($id),
            'userdskl' => UserDesaKelurahan::where('user_id', $id)->first(),
            'desa_kelurahan' => DesaKelurahan::with('kecamatan')->orderBy('kec_id', 'ASC')->get()
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = $request->validate(
            [
                'username' => 'required',
                'name' => 'required',
                'role' => 'required',
            ],
            [
                'username.required' => 'Username tidak boleh kosong',
                'username.unique' => 'Username sudah ada',
                'role.required' => 'Hak akses harus dipilih',
            ]
        );
        if ($request->password !== null) {
            $rules['password'] = Hash::make($request->password);
        }
        User::where('id', $id)->update($rules);
        if ($request->role == 2) {
            $userdsklupdate = UserDesaKelurahan::find($request->userdsklid);
            $userdsklupdate->desa_kelurahan_id = $request->desa_kelurahan_id;
            $userdsklupdate->update();
        }
        return redirect('/usr');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect('/usr');
    }
}
