@extends('admin.layout.layout')

@section('content')
    <h1>Discount Codes</h1>
    <a href="{{ route('admin.discounts.create') }}">Add New Discount</a>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Discount Rate</th>
                <th>Max Value</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($discounts as $discount)
                <tr>
                    <td>{{ $discount->code }}</td>
                    <td>{{ $discount->discount_rate }}</td>
                    <td>{{ $discount->max_value }}</td>
                    <td>{{ $discount->start_date }}</td>
                    <td>{{ $discount->end_date }}</td>
                    <td>
                        <a href="{{ route('admin.discounts.edit', $discount) }}">Edit</a>
                        <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

