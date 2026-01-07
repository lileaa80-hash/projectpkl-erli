<!DOCTYPE html>
<html>
<body>
    <h1>Pembayaran Berhasil!</h1>
    <p>Halo, pesanan Anda dengan ID <strong>#{{ $order->id }}</strong> telah kami terima.</p>
    <p>Status: <span style="color: green;">PAID</span></p>
    <p>Tanggal Laporan: {{ $startDate }} sampai {{ $endDate }}</p>
    <hr>
    <p>Terima kasih telah berbelanja di Gadget Murah!</p>
</body>
</html>