<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Receipt Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .receipt {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 24px;
            font-family: 'Courier New', monospace;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .receipt-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 32px;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .dashed {
            border-top: 1px dashed #ccc;
            margin: 16px 0;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="receipt shadow-sm">
        <div class="receipt-header">
            <h6 class="mb-0">STRUK PEMBAYARAN</h6>
            <small>PT. Pembayaran Digital Indonesia</small>
        </div>

        <div class="dashed"></div>

        <div class="receipt-line">
            <span>Nama Tagihan</span>
            <span>Listrik PLN</span>
        </div>
        <div class="receipt-line">
            <span>Tanggal</span>
            <span>07 Juni 2025</span>
        </div>
        <div class="receipt-line">
            <span>Waktu</span>
            <span>10:42 WIB</span>
        </div>
        <div class="receipt-line">
            <span>Metode</span>
            <span>Virtual Account</span>
        </div>
        <div class="receipt-line">
            <span>Bank</span>
            <span>BCA</span>
        </div>
        <div class="receipt-line">
            <span>Nomor Referensi</span>
            <span>INV12345678</span>
        </div>

        <div class="dashed"></div>

        <div class="receipt-line fw-bold">
            <span>Total Bayar</span>
            <span>Rp150.000</span>
        </div>

        <div class="dashed"></div>

        <div class="receipt-footer">
            Terima kasih telah melakukan pembayaran 💳<br>
            Simpan struk ini sebagai bukti transaksi.
        </div>
    </div>

</body>

</html>
