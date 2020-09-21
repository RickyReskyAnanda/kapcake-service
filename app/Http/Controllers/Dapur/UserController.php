<?php

namespace App\Http\Controllers\Dapur;

use Auth;
use DB;
use App\User;
use App\Perangkat;
use Carbon\Carbon;
use App\Http\Resources\Kasir\UserLogin as UserLoginResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public $successStatus = 200;
    public $userLimit = 1;

    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'operating_system' => 'required|string',
            'mac' => 'required|string',
            'remember_me' => 'nullable|boolean'
        ]);

        $credentials = request(['email', 'password']);
        $credentials['is_active'] = 1;
        $credentials['deleted_at'] = null;
        $bisnisId = (User::where('email', $request->email)->first())->bisnis_id ?? 0;
        if($bisnisId == 0 )
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ], 401);

        $jumlahPerangkat = Perangkat::where('bisnis_id',  $bisnisId)
                                    ->where('perangkat', 'kitchen')
                                    ->where('is_aktif', '1')
                                    ->where('email','<>',$request->email)
                                    ->count();
        $kondisi = '';
        if($jumlahPerangkat >= $this->userLimit){
            return response()->json([
                'status' => 'failed',
                'message' => 'Limited Access'
            ], 403);
        }else{
            $perangkat  = Perangkat::where('bisnis_id',  $bisnisId)
                                        ->where('perangkat', 'pos')
                                        ->where('email', $request->email)
                                        ->where('mac', $request->mac)
                                        ->get();

            if(count($perangkat) == 0){
                $kondisi = 'create';
            }else if(count($perangkat) > 0){
                $kondisi = 'next';
            }
        }
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $user->load([
                'outlet'
                ]
            );

            if($kondisi == 'create')
                $perangkat[0] = Perangkat::create([
                    'bisnis_id' => $user->bisnis_id,
                    'email' => $user->email,
                    'operating_system' => $request->operating_system,
                    'perangkat' => 'pos',
                    'mac' => $request->mac,
                    'is_aktif' =>  '1'
                ]);
            else
                Perangkat::where('bisnis_id',  $bisnisId)
                        ->where('perangkat', 'pos')
                        ->where('email', $request->email)
                        ->where('mac', $request->mac)
                        ->update([
                            'is_aktif' => 1
                        ]);

            return response()->json([ 
                'status' => 'success',
                'data' => [
                    'user' => new UserLoginResource($user),
                    'perangkat' => $perangkat[0],
                    'token' => $user->api_token
                ],
                'message' => 'Berhasil Login'
            ], $this->successStatus);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function profil()
    {
        $user = auth()->user();
        $user->load([
            'bisnis',
            'outlet',
            'role.aplikasi' => function($query){
                $query->where('aplikasi_id','1');    
                $query->with('otorisasi.child');
            }]
        );
        return response()->json([ 
            'bisnis' => $user->bisnis ? new BisnisResource($user->bisnis): [], 
            'user' => new UserLoginResource($user), 
            'outlet' => $user->bisnis->outlet ? OutletResource::collection($user->bisnis->outlet) : [], 
        ], $this->successStatus);
    }

    public function updateProfil(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'pin' => 'required',
            'alamat' => 'nullable',
        ]);
        DB::beginTransaction();
        try {   
            $request->user()->update($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function updatePassword(Request $request){
        $data = $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|different:password_lama',
            'confirm' => 'required|same:password_baru',
        ]);
        DB::beginTransaction();
        try {   
            if(\Hash::check($request->password_lama, $request->user()->password)){
                $request->user()->update([
                    'password' => bcrypt($request->password_baru)
                ]);
            }else{
                DB::rollback();
                return response('wrong',500); 
            }
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }
}
