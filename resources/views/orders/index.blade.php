@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-4">

    <h3 class="mb-4 fw-bold">📦 Pesanan Saya</h3>

    @if($orders->isEmpty())
        <div class="alert alert-info">
            Kamu belum memiliki pesanan.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No Order</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_number ?? '#'.$order->id }}</td>
                                <td>Rp {{ number_format($order->total_amount) }}</td>
                                <td>
                                    <span class="badge
                                        @if($order->status === 'pending') bg-warning
                                        @elseif($order->status === 'paid') bg-success
                                        @elseif($order->status === 'cancelled') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('orders.show', $order->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>

                                    {{-- Tombol Hapus (hanya jika pending) --}}
                                    @if($order->status === 'pending')
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?');">
                                        @csrf
                                                @method('DELETE')
                                                 <button type="submit" class="btn btn-sm btn-outline-danger">
                                                 Batal
                                            </button>
                                      </form>

                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection