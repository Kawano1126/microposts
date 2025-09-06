<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

    class FavoritesController extends Controller
{
    /** Micropost をお気に入りに追加（URLの {id} を使用） */
    public function store(string $id)
    {
        \Auth::user()->favorite((int) $id);
        return back();
    }

    /** Micropost のお気に入りを解除（URLの {id} を使用） */
    public function destroy(string $id)
    {
        \Auth::user()->unfavorite((int) $id);
        return back();
    }
}