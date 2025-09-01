<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFavoriteController extends Controller
{
    /**
     * 投稿をお気に入りするアクション
     */
    public function store($id)
    {
        // 認証済みユーザーが、投稿（micropost_id = $id）をお気に入り登録
        Auth::user()->favorite($id);
        return back();
    }

    /**
     * 投稿のお気に入りを解除するアクション
     */
    public function destroy($id)
    {
        // 認証済みユーザーが、投稿（micropost_id = $id）のお気に入りを解除
        Auth::user()->unfavorite($id);
        return back();
    }
}

