<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Photo extends Model
{
    use HasFactory;

    /** プライマリキーの型 */
    protected $keyType = 'string';

     /** JSONに含める属性 */
    protected $visible = [
        'id', 'owner', 'url','comments',
        'likes_count', 'liked_by_user',
    ];
    /** JSONに含めるアクセサ */
    protected $appends = [
        'url','likes_count', 'liked_by_user',
    ];

    protected $perPage = 5;
   

    const ID_LENGTH = 12;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (! Arr::get($this->attributes, 'id')) {
            $this->setId();
        }
    }

        /**
     * ランダムなID値をid属性に代入する
     */
    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    /**
     * ランダムなID値を生成する
     * @return string
     */
    private function getRandomId()
    {
        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );

        $length = count($characters);

        $id = "";

        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }

    /**
 * リレーションシップ - usersテーブル
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
    public function owner()
    {
        
        return $this->belongsTo('App\Models\User','user_id','id','users');
    }

/**
 * アクセサ - url
 * @return string
 */
public function getUrlAttribute()
{
    return Storage::cloud()->url($this->attributes['filename']);
}

public function comments()
{
    return $this->hasMany('App\Models\Comment')->orderBy('id','desc');
}

/**
 * リレーションシップ - usersテーブル
 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
 */
public function likes()
{   // likes テーブルを中間テーブルとした、photos テーブルと users テーブルの多対多の関連性を表す
    return $this->belongsToMany('App\Models\User', 'likes')->withTimestamps();
}

/**
 * アクセサ - likes_count
 * @return int
 */
public function getLikesCountAttribute()
{
    return $this->likes->count();
}

/**
 * アクセサ - liked_by_user
 * @return boolean
 */
public function getLikedByUserAttribute()
{
    if (Auth::guest()) {
        return false;
    }

    return $this->likes->contains(function ($user) {
        return $user->id === Auth::user()->id;
    });
}


}
