<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Diet;
use App\Models\History;
use App\Models\Product;
use App\Models\Tip;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class ApiController extends Controller
{


    public function login(Request $request)
    {
        $user=User::where('email',$request->email)->first();
        if ($user && Hash::check($request->password, $user->password)&&$user->is_user==1) {
            return response()->json([
                'data'=>$user->id,
                'status'=>200,
                'message'=>'Sukses'
            ]);
        }else{
            return response()->json([
                'status'=>500,
                'message'=>'Gagal Login, Cek Email atau Password'
            ]);
        }
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required',
            'password' => 'required',
            'gender' => 'required',
            'usia' => 'required',
        ]);
       try{
        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->gender=$request->gender;
        $user->usia=$request->usia;
        $user->is_user=1;
        $user->save();
        return response()->json([
             'data'=>$user->id,
             'status'=>200,
             'message'=>'Sukses'
         ]);
       }catch(Exception $e){
        return response()->json([
            'status'=>500,
            'message'=>$e->getMessage(),
        ]);
       }
    }

    public function home(Request $request)
    {
        $id=$request->id;
        $user=User::find($id);
        $data['icon']=substr($user->name,0,2);
        $data['name']=$user->name;
        return response()->json([
            'data'=>$data,
            'status'=>200,
            'message'=>'Sukses'
        ]);
    }

    public function hitung(Request $request)
    {
        try{
        $history=new History();
        $history->id_user=$request->id_user;
        $history->usia=$request->berat;
        $history->tinggi=$request->tinggi;
        $history->berat=$request->usia;
        $history->indeks=$request->indeks;
        $history->hasil=$request->hasil;
        $history->keterangan=$request->keterangan;
        $history->type=$request->type;
        $history->blood=$request->blood;
        $history->save();
        return response()->json([
            'status'=>200,
            'message'=>'Sukses'
        ]);
      }catch(Exception $e){
       return response()->json([
           'status'=>500,
           'message'=>$e->getMessage(),
       ]);
      }
    }

    public function history(Request $request)
    {
        $id=$request->id_user;
        $user=History::where('id_user',$id)->get();
        return response($user);
    }
    
    public function goldar($id)
    {
        $data=Diet::find($id);
        return response($data);

    }

    public function getPost()
    {
        $data=Diet::where('type',1)->get();
        return response($data);
    }
}
