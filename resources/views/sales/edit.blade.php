@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Sale #{{ $sale->id }}</h1>
    
    <form id="saleForm">
        @csrf
        @method('PUT')
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="user_id" class="form-label">Customer</label>
                <select class="form-select" id="user_id" name="user_id" required>
                    <option value="">Select Customer</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $sale->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="sale_date" class="form-label">Sale Date</label>
                <input type="date" class="form-control" id="sale_date" name="sale_date" 
                       value="{{ $sale->sale_date }}" required>
            </div>
        </div>
        
        <div class="mb-3">
            <h4>Sale Items</h4>
            <div id="items-container">
                @foreach($sale->items as $index => $item)
                <div class="item-row mb-3 border p-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Product</label>
                            <select class="form-select product-select" name="items[{{ $index }}][product_id]" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                        data-price="{{ $product->price }}"
                                        {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control quantity" 
                                   name="items[{{ $index }}][quantity]" min="1" 
                                   value="{{ $item->quantity }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Unit Price</label>
                            <input type="number" step="0.01" class="form-control unit-price" 
                                   name="items[{{ $index }}][unit_price]" 
                                   value="{{ $item->unit_price }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Discount</label>
                            <input type="number" step="0.01" class="form-control discount" 
                                   name="items[{{ $index }}][discount]" 
                                   value="{{ $item->discount }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Total</label>
                            <input type="text" class="form-control item-total" 
                                   value="{{ $item->quantity * $item->unit_price - $item->discount }}" readonly>
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-item">Remove</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-secondary mt-2" id="add-item-btn">Add Item</button>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <h5>Total: <span id="total-amount">{{ $sale->total_amount }}</span> BDT</h5>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Sale</button>
    </form>
</div>

<!-- Template for new item row (hidden) -->
<div id="item-template" class="d-none">
    <div class="item-row mb-3 border p-3">
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Product</label>
                <select class="form-select product-select" name="items[][product_id]" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control quantity" name="items[][quantity]" min="1" value="1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Unit Price</label>
                <input type="number" step="0.01" class="form-control unit-price" name="items[][unit_price]" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Discount</label>
                <input type="number" step="0.01" class="form-control discount" name="items[][discount]" value="0">
            </div>
            <div class="col-md-2">
                <label class="form-label">Total</label>
                <input type="text" class="form-control item-total" readonly>
                <button type="button" class="btn btn-danger btn-sm mt-2 remove-item">Remove</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let itemCount = {{ count($sale->items) }};
    
    // Add new item row
    $('#add-item-btn').click(function() {
        const newItem = $('#item-template').html().replace(/items\[\]/g, `items[${itemCount}]`);
        $('#items-container').append(newItem);
        itemCount++;
    });
    
    // Remove item row
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
        calculateTotal();
    });
    
    // Product selection change - update price
    $(document).on('change', '.product-select', function() {
        const selectedOption = $(this).find('option:selected');
        const price = selectedOption.data('price');
        const row = $(this).closest('.item-row');
        
        row.find('.unit-price').val(price).trigger('input');
    });
    
    // Calculate line item total
    $(document).on('input', '.quantity, .unit-price, .discount', function() {
        const row = $(this).closest('.item-row');
        const quantity = parseFloat(row.find('.quantity').val()) || 0;
        const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
        const discount = parseFloat(row.find('.discount').val()) || 0;
        
        const itemTotal = (quantity * unitPrice) - discount;
        row.find('.item-total').val(itemTotal.toFixed(2));
        
        calculateTotal();
    });
    
    // Calculate grand total
    function calculateTotal() {
        let total = 0;
        
        $('.item-row').each(function() {
            const rowTotal = parseFloat($(this).find('.item-total').val()) || 0;
            total += rowTotal;
        });
        
        $('#total-amount').text(total.toFixed(2));
    }
    
    // Form submission
    $('#saleForm').submit(function(e) {
        e.preventDefault();
        
        const formData = $(this).serializeArray();
        const items = [];
        
        // Process items data
        $('.item-row').each(function(index) {
            const item = {
                product_id: $(this).find('.product-select').val(),
                quantity: $(this).find('.quantity').val(),
                unit_price: $(this).find('.unit-price').val(),
                discount: $(this).find('.discount').val() || 0,
            };
            items.push(item);
        });
        
        // Add items to form data
        formData.push({name: 'items', value: JSON.stringify(items)});
        
        $.ajax({
            url: '{{ route('sales.update', $sale->id) }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                alert(response.message);
                window.location.href = '{{ route('sales.index') }}';
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });
});
</script>
@endpush