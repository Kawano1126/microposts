@auth
@if (Auth::id() != $micropost->user_id)
    @if (Auth::user()->is_favoriting($micropost->id))
        {{-- お気に入り解除（DELETE /microposts/{id}/unfavorite） --}}
        <form method="POST" action="{{ route('favorites.unfavorite', ['id' => $micropost->id]) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-error btn-block normal-case"
                onclick="return confirm('id = {{ $micropost->id }} のお気に入りを外します。よろしいですか？')">
                unfavorite
            </button>
        </form>
    @else
        {{-- お気に入り追加（POST /microposts/{id}/favorites） --}}
        <form method="POST" action="{{ route('favorites.favorite', ['id' => $micropost->id]) }}">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">Favorite</button>
        </form>
    @endif
@endif
@endauth

