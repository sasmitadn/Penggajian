<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>e-Receipt Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f4f6;
            padding: 20px;
        }

        .receipt-container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
            font-family: 'Segoe UI', sans-serif;
            color: #212529;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h5 {
            margin-bottom: 4px;
        }

        .receipt-section {
            margin-bottom: 16px;
        }

        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 0.95rem;
        }

        .receipt-total {
            font-weight: bold;
            font-size: 1.1rem;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .receipt-footer {
            text-align: center;
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 24px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                border: none;
                max-width: 100%;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="receipt-container">
        <div class="receipt-header">
            <h5>Bukti Pembayaran</h5>
            <div class="text-muted small">Griya Sandang Konveksi</div>
        </div>

        <div class="receipt-section">
            <div class="receipt-item">
                <span>Nama</span>
                <span>{{ $data['name'] }}</span>
            </div>
            <div class="receipt-item">
                <span>Nama Tagihan</span>
                <span>{{ $data['title'] }}</span>
            </div>
            <div class="receipt-item">
                <span>Tanggal</span>
                <span>{{ $data['date'] }}</span>
            </div>
            <div class="receipt-item">
                <span>Waktu</span>
                <span>{{ $data['time'] }}</span>
            </div>
            <div class="receipt-item">
                <span>Metode</span>
                <span>{{ $data['payment_method'] }}</span>
            </div>
            <div class="receipt-item">
                <span>Sistem</span>
                <span>{{ $data['system'] }}</span>
            </div>
            <div class="receipt-item">
                <span>Nomor Referensi</span>
                <span>{{ $data['id'] }}</span>
            </div>
        </div>

        <div class="receipt-section receipt-total">
            <div class="receipt-item">
                <span>Total Pembayaran</span>
                <span>{{ $data['total'] }}</span>
            </div>
        </div>

        <div class="receipt-footer">
            Terima kasih telah melakukan pembayaran.<br>
            Simpan ini sebagai bukti transaksi.
        </div>
    </div>

</body>

</html>
