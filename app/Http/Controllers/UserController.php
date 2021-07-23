<?php

namespace App\Http\Controllers;
use Ramsey\Uuid\Uuid;
use App\User;
use App\Lapangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function login(Request $request)
    {


            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ]);
            $credentials = request(['username', 'password']);
            if(!Auth::attempt($credentials))
                return response()->json([
                    'message' => 'Username atau Password Salah',
                    'kode'=> '0'
                ]);

                $user = $request->user();
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                if ($request->remember_me)
                   $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();


                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'kode'=> '1',
                    'email'=> Auth::user()->email,
                    'nama'=>Auth::user()->name,
                    'role'=> Auth::user()->role,
                    'user_id'=> Auth::user()->id,
                    'message' => 'Selamat Datang '.$request->username,
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ]);
    }
    public function register(Request $request)
    {

       $nama = $request->nama;
       $username = $request->username;
       $email = $request->email;
       $alamat = $request->alamat;
       $telpon = $request->telpon;
       $role = $request->role;

                Validator::extend('valid_username', function($attr, $value){

                    return preg_match('/^\S*$/u', $value);

                });


            $validator = Validator::make($request->all(), [
                'username' => 'required|valid_username|'
            ],['valid_username' => 'please enter valid username.']);

    if ($validator->passes()) {

        $data_user=DB::table('users')->where('email',$email)->first();
        $user=DB::table('users')->where('username',$username)->first();
       // var_dump($data_user);
        if($data_user){
            $message = 'Akun anda sudah terdaftar silahkan login';
            $kode  ='0';

        }else if($user){
            $message = 'Username sudah ada';
            $kode  ='0';

        }else{
            $user = new User([
                'name' => $nama,
                'username' => $username,
                'password' => bcrypt($request->password),
                'role' => $role,
                'last_login' => now(),
                'uuid' => Uuid::uuid4(),
                'foto' => '',
                'alamat' => $alamat,
                'telpon' =>$telpon,
                'email'=>$email
            ]);
            $user->save();
            $message="Berhasil Register";
            $kode="1";
        }
        

    }else{
        $message="Username tidak boleh ada spasi";
        $kode="0";

    }
    return response()->json([
                'message' => $message,
                'kode' => $kode
            ]);

    }


    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response([
            'kode' => "1",
            'message' => 'Successfully logged out'
        ]);
    }


     public function update_password(request $request){


                $password = $request->password;
                $password_baru = bcrypt($request->password_baru);
                $cek_password = User::find(Auth::user()->id);
                $validator = Validator::make($request->all(), [
                    'password' => 'required|string',

                ]);

        if ($validator->fails()) {
            $message = "Pastikan Data Di Input Semua";
            $kode = "0";
        } else {
            if(password_verify($password,$cek_password->password)){

                DB::beginTransaction();
                try{
                $user = User::find(Auth::user()->id);
                $user->password= $password_baru;
                $user->save();
                $message = "Berhasil Ubah Password";
                $kode = "1";
                DB::commit();

                }catch (\Exception $e){
                    DB::rollback();
                }

            }else{
                $message = "Password Lama Salah";
                $kode = "3";
            }

        }
        return response()->json([
            'message' => $message,
            'kode' => $kode
        ]);
        //adadadsadasdsadassdasdw




    }

    public function edit_foto(request $request)
    {

                if (!$request->hasFile('foto')) {
                   return response()->json([
                    'message' => 'foto tidak ada',
                    'nama' => '',
                    'kode' => '0'
                ]);
                }
                $file = $request->file('foto');
                if (!$file->isValid()) {
                    return response()->json([
                        'message' => 'foto tidak ada',
                        'nama' => '',
                        'kode' => '0'
                    ]);
                }
                $fileName = time() . ".jpg";
                $path = public_path() . '/foto_profil/';
                $file->move($path, $fileName);

                $user =User::find(Auth::user()->id);
                $user->foto= $fileName;
                $user->save();
                 $message = "Berhasil Ubah foto";
                 $kode = "1";
        return response()->json([
            'message' => $message,
            'nama' => $fileName,
            'kode' => $kode
        ]);
    }


    public function edit_nohp(request $request)
    {
                $phone= $request->phone;
                $user =User::where('id',Auth::user()->id)->first();
                $user->telpon= $phone;


                if($user->save()){
                    $message = "Berhasil Ubah No Hendphone";
                    $kode = "1";
                }else{
                    $message = "Berhasil Ubah No Hendphone";
                    $kode = "1";

                }

        return response()->json([
            'message' => $message,
            'nama' => $phone,
            'kode' => $kode
        ]);
    }
    public function get_data_user(request $request)
    {
        $data = User::where('id',Auth::user()->id)->get();
        return response([
            'kode' => true,
            'message' => "semua data lapangan" ,
            'isi' => $data
        ], 200);
             
    }

    

}
