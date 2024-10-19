<form action="{{ route('post.search', ['config' => $config == 'index' ? 'index' : 'deleted']) }}">
    <div class="fill-deleted">
        <a class="all" href="{{ route('post.index') }}"> Tất cả</a> |
        <a class="trash" href="#">Thùng rác <span>({{ count($countDeleted)}})</span></a>
    </div>
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter wg-filter-postc">
            <fieldset class="search" style="width: 70%">
                <input type="text" name="keyword" placeholder="Tìm kiếm tại đây..." class="input-filter"
                    tabindex="2" value="{{ request('keyword') ?: old('keyword') }}" aria-required="true">
                    {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
            </fieldset>
            <fieldset class="publish" style="width: 40%">
                <div class="select">
                    @php
                    $publish = request('publish') ?: old('publish');
                @endphp
                    <select class="input-filter" name="publish">
                        @foreach (Config('general.publish') as $key => $val)
                            <option value="{{ $key }}" {{ $publish == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </fieldset>
            {{-- <fieldset class="deleted flex items-center">
                <input type="checkbox" name="deleted_at" class="input-filter"> 
                <span>Đã xóa</span>
            </fieldset> --}}
            <div class="button-submit">
                <button class="btn-filter " type="submit"><span>Tìm kiếm</span></button>
            </div>
           
        </div>
        <a class="tf-button style-1 w208" href="{{ route('post.create') }}"><i class="icon-plus"></i>Add new</a>
    </div>
</form>
