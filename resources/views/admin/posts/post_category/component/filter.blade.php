<form action="{{ route('postCatagory.search') }}">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter">
            <fieldset class="search">
                <input type="text" name="keyword" placeholder="Search here..." class="input-filter" name="name"
                    tabindex="2" value="{{ request('keyword') ?: old('keyword') }}" aria-required="true">
            </fieldset>
            <fieldset class="publish">
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
            <div class="button-submit">
                <button class="btn-filter " type="submit"><span>Tìm kiếm</span></button>
            </div>
        </div>
        <a class="tf-button style-1 w208" href="{{ route('postCatagory.create') }}"><i class="icon-plus"></i>Add new</a>
    </div>
</form>
