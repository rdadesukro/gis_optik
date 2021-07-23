<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Foto;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class FotoController extends Controller
{
    public function add_foto(Request $request)
    {

       $nama = $request->nama;
       $optik_id = $request->optik_id;
    
       
    
      

            $this->validate($request,[
                'nama'=>'required',
                'optik_id'=>'required'
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
     $path = public_path() . '/foto_slider/';
     $file->move($path, $fileName);


    $uuid = Uuid::uuid4();
    DB::beginTransaction();
    try{
        $lapangan = new Foto;
        $lapangan->optik_id = $optik_id;
        $lapangan->nama = $nama;
        $lapangan->foto = $fileName;
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

 public function edit_foto(Request $request)
 {

    
    $id = $request->id;
    $nama = $request->nama;
    $optik_id = $request->optik_id;
 
    
         $this->validate($request,[
             'nama'=>'required',
             'optik_id'=>'required'
         ]);

 if (!$request->hasFile('foto')) {
    $lapangan =Foto::where('id',$id)->first();
    $lapangan->optik_id = $optik_id;
    $lapangan->nama = $nama;
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
  $path = public_path() . '/foto_slider/';
  $file->move($path, $fileName);


 $uuid = Uuid::uuid4();
 DB::beginTransaction();
 try{
    $lapangan =Foto::where('id',$id)->first();
    $lapangan->optik_id = $optik_id;
    $lapangan->nama = $nama;
    $lapangan->foto = $fileName;
    $lapangan->save();

     DB::commit();
     return response()->json([
                     'message' => 'Berhasil',
                     'kode' => '1'
                 ]);
 }catch (\Exception $e){
//dd($e);
     DB::rollback();
     return response()->json([
         'message' => 'Gagal',
         'kode' => '0'
     ]);
 }
}
public function get_foto(Request $request){
    //  dd($request->id);
     $id = $request->id;
      $data = Foto::where('optik_id',$id)->get();
     return response([
         'kode' => true,
         'message' => "semua data jenis" ,
         'isi' => $data
     ], 200);
  
  }
  

}
