@if (Auth::id() != $user->id)
    @if (Auth::user()->is_favoriting($user->id))
        {{-- お気に入り解除ボタン --}}
        <form method="POST" action="{{ route('user.unfavorite', ['id' => $user->id]) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-error btn-block normal-case"
                onclick="return confirm('id = {{ $user->id }} のお気に入りを外します。よろしいですか？')">unfavorite</button>
        </form>
    @else
        {{-- お気に入り追加ボタン --}}
        <form method="POST" action="{{ route('user.favorite', ['id' => $user->id]) }}">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">Favorite</button>
        </form>
    @endif
@endif
