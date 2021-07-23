<?php

namespace App\Http\Controllers;
use App\Optik;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class OptikController extends Controller
{
    public function add_optik(Request $request)
    {

       $nama_optik = $request->nama_optik;
       $alamat = $request->alamat;
       $phone = $request->phone;
       $lat = $request->lat;
       $lng = $request->lng;
       $status=$request->status;
       $informasi=$request->informasi;
       $jam_oprasional=$request->jam_oprasional;
       $status_bpjs=$request->status_bpjs;
       
       


            $this->validate($request,[
                'nama_optik'=>'required',
                'alamat'=>'required',
                'phone'=>'required',
                'lat'=>'required',
                'lng'=>'required'
            ]);

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
     $path = public_path() . '/foto_optik/';
     $file->move($path, $fileName);


    $uuid = Uuid::uuid4();
    DB::beginTransaction();
    try{
        $lapangan = new Optik;
        $lapangan->nama_optik = $nama_optik;
        $lapangan->alamat = $alamat;
        $lapangan->jam_oprasional = $jam_oprasional;
        $lapangan->phone = $phone;
        $lapangan->lat = $lat;
        $lapangan->lng = $lng;
        $lapangan->foto = $fileName;
        $lapangan->informasi =$informasi;
        $lapangan->status =$status;
        $lapangan->status_bpjs =$status_bpjs;
        
        $lapangan->save();

        DB::commit();
        return response()->json([
                        'message' => 'Berhasil',
                        'kode' => '1'
                    ]);
    }catch (\Exception $e){
      dd($e);
        DB::rollback();
        return response()->json([
            'message' => 'Gagal',
            'kode' => '0'
        ]);
    }
 }

 public function edit_optik(Request $request)
 {

    $nama_optik = $request->nama_optik;
    $id = $request->id;
    $alamat = $request->alamat;
    $phone = $request->phone;
    $lat = $request->lat;
    $lng = $request->lng;
    $status=$request->status;
    $informasi=$request->informasi;
    $jam_oprasional=$request->jam_oprasional;
    $status_bpjs=$request->status_bpjs;
    

         $this->validate($request,[
             'nama_optik'=>'required',
             'alamat'=>'required',
             'phone'=>'required',
             'lat'=>'required',
             'lng'=>'required'
         ]);

 if (!$request->hasFile('foto')) {
    $lapangan = new Optik;
    $lapangan =Optik::where('id',$id)->first();
    $lapangan->nama_optik = $nama_optik;
    $lapangan->alamat = $alamat;
    $lapangan->jam_oprasional = $jam_oprasional;
    $lapangan->phone = $phone;
    $lapangan->lat = $lat;
    $lapangan->lng = $lng;
    $lapangan->informasi =$informasi;
    $lapangan->status =$status;
    $lapangan->status_bpjs =$status_bpjs;
        
    $lapangan->save();

    DB::commit();
    return response()->json([
                    'message' => 'Berhasil',
                    'kode' => '1'
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
  $path = public_path() . '/foto_optik/';
  $file->move($path, $fileName);


 $uuid = Uuid::uuid4();
 DB::beginTransaction();
 try{
     $lapangan = new Optik;
     $lapangan =Optik::where('id',$id)->first();
     $lapangan->nama_optik = $nama_optik;
     $lapangan->alamat = $alamat;
     $lapangan->jam_oprasional = $jam_oprasional;
      $lapangan->phone = $phone;
        $lapangan->lat = $lat;
        $lapangan->lng = $lng;
        $lapangan->foto = $fileName;
        $lapangan->informasi =$informasi;
        $lapangan->status =$status;
        $lapangan->status_bpjs =$status_bpjs;
        
     $lapangan->save();

     DB::commit();
     return response()->json([
                     'message' => 'Berhasil',
                     'kode' => '1'
                 ]);
 }catch (\Exception $e){
dd($e);
     DB::rollback();
     return response()->json([
         'message' => 'Gagal',
         'kode' => '0'
     ]);
 }
}

public function get_data_optik(Request $request){

    try{
        $data = Optik::get();
        return response([
            'kode' => true,
            'message' => "semua data lapangan" ,
            'isi' => $data
        ], 200);
    }catch (\Exception $e){
     dd($e);

    }



}
public function get_detail_optik(Request $request){
    $id = $request->id;

    $data = Optik::where('id',$id)->get();
   return response([
       'kode' => true,
       'message' => "semua data lapangan" ,
       'isi' => $data
   ], 200);

}

public function hapus_optik(Request $request){
    $id = $request->id;


    DB::beginTransaction();
    try{
        $lapangan = Optik::where('id',$id);
        $lapangan->delete();
        $message = "Berhasil Hapus Data";
        $kode = "1";

        DB::commit();
    }catch (\Exception $e){
   dd($e);
        DB::rollback();
        $message = "Gagal Hapus Data";
        $kode = "0";
    }

    return response()->json([
        'message' => $message,
        'kode' => $kode
    ]);


}



}
