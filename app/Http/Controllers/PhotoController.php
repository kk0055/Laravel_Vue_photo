<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePhoto;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * 写真投稿
     * @param StorePhoto $request
     * @return \Illuminate\Http\Response
     */
    public function create(StorePhoto $request)
    {
           $photo = new Photo();

             if($request->hasFile('photo')){
          
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME); 
            $extension = $request->file('photo')->getClientOriginalExtension();
    
            //Create New file name
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $request->file('photo')->storeAs('public/photos',$fileNameToStore);
            
            $photo->filename = $fileNameToStore ;
            $photo->save();

            dd($photo);
        }else {
            $fileNameToStore = 'noimage.jpg';

            $photo->save();
            dd($photo);
        }

          
          
    }

}
