<div class="wg-table table-all-user">
    <table style="table-layout: auto;" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>Ảnh</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($postCataloge as $index => $item)
                <tr>
                    <td>{{$index + 1}}</td>
                    <td class="pname">
                        <div class="name">
                            <a href="#" class="body-title-2">{{ $item->name }}</a>
                        </div>
                    </td>
                    <td>
                        {{-- <span><img src="{{ asset($item->image) }}" alt=""></span> --}}
                    </td>
                    <td>
                        {{ $item->description }}
                    </td>
                    <td>
                        <label class="toggle">
                            <input id="toggleswitch"
                                    class="toggleswitch" 
                                   name="publish"
                                   type="checkbox" 
                                   value="{{ $item->publish }}" 
                                   data-id="{{ $item->id }}"
                                   data-model="PostCategory"
                                   {{ $item->publish == 1 ? 'checked' : '' }}>
                            <span class="roundbutton"></span>
                        </label>                        
                    </td>
                    <td>
                        <div class="list-icon-function">
                            <a href="#">
                                <div class="item edit">
                                    <i class="icon-edit-3"></i>
                                </div>
                            </a>
                            <form action="#" method="POST">
                                <div class="item text-danger delete">
                                    <i class="icon-trash-2"></i>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            

        </tbody>
    </table>
</div>