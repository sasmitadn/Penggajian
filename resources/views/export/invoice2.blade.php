<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            --bs-primary: #0070E4;
            background-color: #f8f9fa;
        }

        .invoice-box {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #0d6efd;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: 500;
        }

        .table th {
            background-color: #f1f1f1;
        }

        footer {
            text-align: center;
            font-size: 14px;
            color: #888;
            margin-top: 40px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background-color: white;
            }

            .container {
                width: 100%;
                max-width: none;
                padding: 0;
            }

            .invoice-box {
                box-shadow: none;
                border-radius: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container-fluid py-5">
        <div class="invoice-box">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <div class="logo">Bukti Pembayaran</div>
                    <small>Griya Sandang Konveksi</small>
                </div>
                <div class="text-end">
                    <h4 class="invoice-title">Tagihan</h4>
                    <p class="mb-0">#INV-2025001</p>
                    <small>06 Juni 2025</small>
                </div>
            </div>

            <!-- Body -->
            <div class="mb-4">
                <div class="row">
                    <div class="col-6">
                        <h6>Tagihan Kepada:</h6>
                        <p class="mb-0">PT Pelanggan Jaya</p>
                        <p>Jl. Mawar No. 123, Jakarta</p>
                    </div>
                    <div class="col-6 text-end">
                        <h6>Dari:</h6>
                        <p class="mb-0">YourCompany</p>
                        <p>support@yourcompany.com</p>
                    </div>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Jasa Konsultasi IT</td>
                        <td class="text-center">1</td>
                        <td class="text-end">Rp5.000.000</td>
                        <td class="text-end">Rp5.000.000</td>
                    </tr>
                    <tr>
                        <td>Pengembangan Website</td>
                        <td class="text-center">1</td>
                        <td class="text-end">Rp10.000.000</td>
                        <td class="text-end">Rp10.000.000</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">Rp15.000.000</th>
                    </tr>
                </tfoot>
            </table>

            <!-- Footer -->
            <footer>
                <p>Terima kasih telah mempercayai layanan kami.</p>
                <p>&copy; 2025 YourCompany. All rights reserved.</p>
            </footer>
        </div>
    </div>
</body>

</html>
