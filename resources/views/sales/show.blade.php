@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Sale Details #{{ $sale->id }}</h1>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Customer: {{ $sale->user->name }}</h5>
                    <p>Date: {{ $sale->sale_date }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h4>Total: {{ $sale->total_amount }} BDT</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h4>Items</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Discount</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ number_format($item->discount, 2) }}</td>
                        <td>{{ number_format(($item->quantity * $item->unit_price) - $item->discount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h4>Notes</h4>
        </div>
        <div class="card-body">
            <div id="notes-container">
                @foreach($sale->notes as $note)
                <div class="card mb-2">
                    <div class="card-body">
                        <p>{{ $note->content }}</p>
                        <small class="text-muted">Added on {{ $note->created_at->format('d M Y H:i') }}</small>
                    </div>
                </div>
                @endforeach
            </div>
            
            <form id="addNoteForm" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label for="noteContent" class="form-label">Add Note</label>
                    <textarea class="form-control" id="noteContent" name="content" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add Note</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Add note form submission
    $('#addNoteForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route('sales.addNote', $sale->id) }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#noteContent').val('');
                $('#notes-container').prepend(`
                    <div class="card mb-2">
                        <div class="card-body">
                            <p>${response.data.content}</p>
                            <small class="text-muted">Added just now</small>
                        </div>
                    </div>
                `);
                alert(response.message);
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });
});
</script>
@endpush
@endsection