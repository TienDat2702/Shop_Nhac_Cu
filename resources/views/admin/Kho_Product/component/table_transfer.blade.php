<style>
    /* Tăng kích thước của select */
    .selectpicker {
        height: 45px; /* Điều chỉnh chiều cao */
        font-size: 16px; /* Điều chỉnh kích thước font chữ */
    }
</style>




    <table style="table-layout: auto;" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Showroom Chuyển</th>
                <th>Showroom Nhận</th>
                <th>Sản Phẩm</th>
                <th>Số Lượng</th>
                <th>Thời Gian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $index => $log) <!-- Sử dụng $index để lấy số thứ tự -->
            <tr>
                <td>{{ $index + 1 }}</td> <!-- STT: Tăng từ 1 -->
                <td>{{ $log['from_showroom'] }}</td>
                <td>{{ $log['to_showroom'] }}</td>
                <td>{{ $log['product'] }}</td>
                <td>{{ $log['quantity'] }}</td>
                <td>{{ $log['timestamp'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


