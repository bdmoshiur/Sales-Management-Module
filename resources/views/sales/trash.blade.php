@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Trash - Deleted Sales</h1>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>{{ $sale->id }}</td>
                                <td>{{ $sale->user->name }}</td>
                                <td>{{ $sale->sale_date }}</td>
                                <td>{{ $sale->total_amount }}</td>
                                <td>{{ $sale->deleted_at }}</td>
                                <td>
                                    <form action="{{ route('sales.restore', $sale->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Restore</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between mt-3">
                <div>
                    Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} entries
                </div>
                <div>
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="{{ route('sales.index') }}" class="btn btn-primary">Back to Sales</a>
    </div>
</div>
@endsection