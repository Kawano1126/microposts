<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * このユーザーが所有する投稿。（ Micropostモデルとの関係を定義）
     */
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    /**
     * このユーザーに関係するモデルの件数をロードする。
     */
    public function loadRelationshipCounts()
    {
        $this->loadCount(['microposts', 'followings', 'followers','favorites']);
    }
    /**
     * このユーザーがフォロー中のユーザー。（Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    /**
     * このユーザーをフォロー中のユーザー。（Userモデルとの関係を定義）
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    /**
 * このユーザーが「お気に入り」しているユーザー。（Userモデルとの関係を定義）
 */
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'user_id', 'favorite_id')->withTimestamps();
    }

/**
 * このユーザーを「お気に入り」しているユーザー。（Userモデルとの関係を定義）
 */
    public function unfavorites()
    {
    return $this->belongsToMany(User::class, 'user_favorites', 'favorite_id', 'user_id')->withTimestamps();
    }

    /*
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    /**
     * $userIdで指定されたユーザーをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow(int $userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            return false;
        } else {
            $this->followings()->attach($userId);
            return true;
        }
    }

    /**
     * $userIdで指定されたユーザーをアンフォローする。
     *
     * @param  int $userId
     * @return bool
     */
    public function unfollow(int $userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            $this->followings()->detach($userId);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 指定された$userIdのユーザーをこのユーザーがフォロー中であるか調べる。フォロー中ならtrueを返す。
     *
     * @param  int $userId
     * @return bool
     */
    public function is_following(int $userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    /**
     * このユーザーとフォロー中ユーザーの投稿に絞り込む。
     */
    public function feed_microposts()
    {
        // このユーザーがフォロー中のユーザーのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザーのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザーが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    //  お気に入り機能

    /**
     * $userIdで指定されたユーザーをお気に入りする。
     *
     * @param int $userId
     * @return bool
     */
    public function favorite(int $userId)
    {
        $exist = $this->is_favoriting($userId);
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            return false;
        } else {
            $this->favorites()->attach($userId);
            return true;
        }
    }

    /**
     * $userIdで指定されたユーザーのお気に入りを解除する。
     *
     * @param int $userId
     * @return bool
     */
    public function unfavorite(int $userId)
    {
        $exist = $this->is_favoriting($userId);
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            $this->favorites()->detach($userId);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 指定された$userIdのユーザーをこのユーザーがお気に入りしているか調べる。お気に入りしていればtrue。
     *
     * @param int $userId
     * @return bool
     */
    public function is_favoriting(int $userId)
    {
        return $this->favorites()->where('favorite_id', $userId)->exists();
    }
}