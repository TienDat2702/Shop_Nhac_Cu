<div class="wg-table table-all-user">
    @if ($postCategories->isNotEmpty())
    <table style="table-layout: auto;" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Danh mục Cha</th>
                <th>Tiêu đề</th>
                <th width="200px">Ảnh</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            
                @foreach ($postCategories as $index => $item)
                    <tr>
                        <td>{{ $postCategories->currentPage() * $postCategories->perPage() - $postCategories->perPage() + $index + 1 }}
                        </td>
                        <td>
                            <div class="parent_id">
                                <a href="#" class="body-title-2">{{ ($item->parent) ? $item->parent->name : 'Không' }}</a>
                            </div>
                        </td>
                        <td>
                            <div class="name">
                                <a href="#" class="body-title-2">{{ $item->name }}</a>
                            </div>
                        </td>
                        <td>
                            <span><img class="img-fluid" src="{{ asset('uploads/posts/post_categories/' . $item->image ) }}" alt=""></span>
                        </td>
                        <td>
                            {{ $item->description }}
                        </td>
                        <td>
                            <label class="toggle">
                                <input id="toggleswitch" class="toggleswitch" name="publish" type="checkbox"
                                    value="{{ $item->publish }}" data-id="{{ $item->id }}" data-model="PostCategory"
                                    {{ $item->publish == 1 ? 'checked' : '' }}>
                                <span class="roundbutton"></span>
                            </label>
                        </td>
                        <td>
                            <div class="list-icon-function">
                                <a href="{{ route('postCatagory.edit', $item->id)}}">
                                    <div class="item edit">
                                        <i class="icon-edit-3"></i>
                                    </div>
                                </a>
                                <form class="form-delete" action="{{ route('postCatagory.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete item text-danger delete">
                                        <i class="icon-trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>
    @else
        <div class="not-search text-center">
            <i class="fa-solid fa-circle-exclamation"></i>
            <h3>Không tìm thấy  {{ request('keyword') ? '"'.request('keyword').'"' : ''}} </h3>
        </div>
    @endif
</div>
