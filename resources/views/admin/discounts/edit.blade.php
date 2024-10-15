@extends('admin.layout.layout')

@section('content')
    <h1>Edit Discount Code</h1>
    <form action="{{ route('admin.discounts.update', $discount) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="code">Code</label>
            <input type="text" name="code" id="code" value="{{ $discount->code }}" required>
        </div>
        <div>
            <label for="discount_rate">Discount Rate</label>
            <input type="number" name="discount_rate" id="discount_rate" value="{{ $discount->discount_rate }}" required>
        </div>
        <div>
            <label for="max_value">Max Value</label>
            <input type="number" name="max_value" id="max_value" value="{{ $discount->max_value }}" required>
        </div>
        <div>
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" value="{{ $discount->start_date }}" required>
        </div>
        <div>
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" value="{{ $discount->end_date }}" required>
        </div>
        <button type="submit">Update</button>
    </form>
@endsection

