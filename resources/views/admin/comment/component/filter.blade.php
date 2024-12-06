
<form action="{{ route('comment.index') }}">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div style="width: 700px" class="wg-filter wg-filter-comment">
            <fieldset class="search" style="width: 70%">
                <input type="text" name="keyword" placeholder="Tìm kiếm tại đây..." class="input-filter"
                    tabindex="2" value="{{ request('keyword') ?: old('keyword') }}" aria-required="true">
                    {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
            </fieldset>
            <fieldset class="rating" style="width: 20%">
                <div class="select">
                    @php
                    $rating = request('rating') ?: old('rating');
                @endphp
                    <select class="input-filter" name="rating">
                        @foreach (Config('general.rating') as $key => $val)
                            <option value="{{ $key }}" {{ $rating == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </fieldset>
            <div class="button-submit">
                <button class="btn-filter " type="submit"><span>Tìm kiếm</span></button>
            </div>
           
        </div>
    </div>
</form>
