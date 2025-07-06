@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Product Details</h1>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Name:</strong>
                        <p>{{ $product->name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>{{ $product->description ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Price:</strong>
                        <p>${{ number_format($product->price, 2) }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <p>
                            @if($product->trashed())
                                <span class="badge bg-danger">Deleted</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <small>
                        Created: {{ $product->created_at->format('M d, Y H:i') }} | 
                        Updated: {{ $product->updated_at->format('M d, Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notes</h5>
                </div>
                <div class="card-body">
                    <div id="notes-container">
                        @foreach($product->notes as $note)
                        <div class="note-item mb-3 border-bottom pb-3">
                            <div class="d-flex justify-content-between">
                                <div class="note-content">
                                    {{ $note->content }}
                                </div>
                                <small class="text-muted">
                                    {{ $note->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($product->notes->isEmpty())
                            <p class="text-muted">No notes yet</p>
                        @endif
                    </div>
                    
                    <form id="add-note-form" class="mt-4">
                        @csrf
                        <div class="form-group">
                            <label for="note-content" class="form-label">Add Note</label>
                            <textarea class="form-control" id="note-content" name="content" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">
                            <i class="fas fa-plus"></i> Add Note
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add note via AJAX
    $('#add-note-form').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("products.addNote", $product->id) }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    // Clear the textarea
                    $('#note-content').val('');
                    
                    // Add the new note to the container
                    const noteHtml = `
                        <div class="note-item mb-3 border-bottom pb-3">
                            <div class="d-flex justify-content-between">
                                <div class="note-content">
                                    ${response.data.content}
                                </div>
                                <small class="text-muted">
                                    Just now
                                </small>
                            </div>
                        </div>
                    `;
                    
                    if ($('#notes-container').find('.note-item').length === 0) {
                        $('#notes-container').html(noteHtml);
                    } else {
                        $('#notes-container').prepend(noteHtml);
                    }
                    
                    // Show success message
                    toastr.success('Note added successfully');
                }
            },
            error: function(xhr) {
                toastr.error('Error: ' + xhr.responseJSON.message);
            }
        });
    });
});
</script>
@endpush