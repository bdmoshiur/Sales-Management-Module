@extends('layouts.app')

@section('content')
<div class="container">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Sales List</h1>
    <div class="btn-group">
        <a href="{{ route('sales.create') }}" class="btn btn-primary">
            <i class="fas fa-shopping-cart"></i> Add Sale
        </a>
    </div>
</div>
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name">
                    </div>
                    <div class="col-md-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name">
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Date From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Filter</button>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary mt-3">Reset</a>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>{{ $sale->id }}</td>
                                <td>{{ $sale->user->name }}</td>
                                <td>{{ $sale->sale_date }}</td>
                                <td>
                                    <ul>
                                        @foreach($sale->items as $item)
                                            <li>{{ $item->product->name }} ({{ $item->quantity }} x {{ $item->unit_price }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $sale->total_amount }}</td>
                                <td>
                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="row mt-3">
    <div class="col-md-6">
        <p class="mt-2">
            Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} entries
        </p>
    </div>
    <div class="col-md-6 text-end">
        {{ $sales->links('pagination::bootstrap-5') }}
    </div>
</div>

        </div>
    </div>
    
    <div class="mt-3">
        <a href="{{ route('trash.sales') }}" class="btn btn-warning">View Trash</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#filterForm').submit(function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const url = '{{ route('sales.index') }}?' + formData;
        
        window.location.href = url;
    });
});
</script>
@endpush