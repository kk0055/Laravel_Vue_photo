<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Photo;
use App\Models\Comment;
use App\Http\Requests\StoreComment;

class PhotoController extends Controller
{
    public function __construct()
    {
    
        $this->middleware('auth')->except(['index', 'download','show']);
    }

    public function index()
    {
        //withで「N+1」問題を回避
        //with --引数で渡したリレーションが定義されたテーブルの情報を先にまとめて取得
        $photos = Photo::with(['owner'])
        ->orderBy(Photo::CREATED_AT, 'desc')->paginate();

        
       return $photos;
    }



    /**
     * 写真投稿
     * @param StorePhoto $request
     * @return \Illuminate\Http\Response
     */
    public function create(StorePhoto $request)
    {
        $extension = $request->photo->extension();

        $photo = new Photo();

        // インスタンス生成時に割り振られたランダムなID値と
        // 本来の拡張子を組み合わせてファイル名とする
        $photo->filename = $photo->id . '.' . $extension;

        // S3にファイルを保存する
        // 第三引数の'public'はファイルを公開状態で保存するため
        Storage::cloud()
            ->putFileAs('', $request->photo, $photo->filename, 'public');

        // データベースエラー時にファイル削除を行うため
        // トランザクションを利用する
        DB::beginTransaction();

        try {
            Auth::user()->photos()->save($photo);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            // DBとの不整合を避けるためアップロードしたファイルを削除
            Storage::cloud()->delete($photo->filename);
            throw $exception;
        }

        // リソースの新規作成なので
        // レスポンスコードは201(CREATED)を返却する
        return response($photo, 201);
    }

  
    public function download(Photo $photo)
    {
        // 写真の存在チェック
        if (! Storage::cloud()->exists($photo->filename)) {
            abort(404);
        }

        $disposition = 'attachment; filename="' . $photo->filename . '"';
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => $disposition,
        ];

        return response(Storage::cloud()->get($photo->filename), 200, $headers);
    }

    //写真詳細
    public function show(string $id)
    {
        $photo = Photo::where('id',$id)
        //Photoモデルのcommentsメソッド Commentモデルのauthorメソッド 
        ->with(['owner','comments.author'])->first();

        
        return $photo ?? abort(404);
    }

    public function addComment(Photo $photo, StoreComment $request)
    {
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = Auth::user()->id;
        $photo->comments()->save($comment);
        // $photo->comments()->save();

        $new_comment = Comment::where('id',$comment->id)->with('author')->first();
      
        dd($photo);
        return response($new_comment, 201);

    }
}
