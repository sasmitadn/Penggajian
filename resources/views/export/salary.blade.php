<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaji Anda</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <style>
        :root {
            --body-font: 'Poppins', sans-serif;
        }

        /* Bootstrap Override */
        body {
            --bs-font-sans-serif: 'Poppins', sans-serif;
            --bs-body-font-family: var(--bs-font-sans-serif);
            --bs-body-font-size: 1rem;
            --bs-body-font-weight: 400;
            --bs-body-line-height: 2;
            --bs-body-color: #41403E;
            --bs-primary: #0070E4;
            --bs-primary-rgb: 0, 112, 228;
            --bs-border-color: #eeeeee;
        }

        @page {
            size: A4;
        }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body onload="window.print()">

    <section id="invoice">
        <div class="">
            <div class="text-center border-top border-bottom mb-5 py-3">
                <h2 class="display-5 fw-bold">Slip Gaji </h2>
                <p class="m-0">Nomor Transaksi: #{{ $data->id }}</p>
                <p class="m-0">Tanggal:
                    {{ parseDate($data->start_date, 'd M Y') . ' - ' . parseDate($data->end_date, 'd M Y') }}</p>
            </div>

            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-primary">Kepada</p>
                    <h4>{{ $data->user?->name }}</h4>
                    <ul class="list-unstyled">
                        <li>Jabatan : {{ $data->user?->category?->name }}</li>
                        <li>{{ $data->user?->email }}</li>
                        <li>{{ $data->user?->address }}</li>
                    </ul>
                </div>
                <div class="mt-md-0">
                    <p class="text-primary">Dari</p>
                    <h4>Griya Sandang Konveksi</h4>
                    <ul class="list-unstyled">
                        <li>Indonesia, Jawa Tengah</li>
                        <li>Begajah Sukoharjo</li>
                    </ul>
                </div>
            </div>

            <table class="table border my-5">
                <thead>
                    <tr class="bg-primary-subtle">
                        <th scope="col">No.</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Nominal</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Gaji Pokok (Hari)</td>
                        <td>Rp. {{ number_format($data->price_daily, 0, '.', ',') }}</td>
                        <td>{{ number_format($data->total_days, 0, '.', ',') }}</td>
                        <td>Rp. {{ number_format($data->amount_salary, 0, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Gaji Lembur (Jam)</td>
                        <td>Rp. {{ number_format($data->price_overtime, 0, '.', ',') }}</td>
                        <td>{{ parseNumber($data->total_overtime) }}</td>
                        <td>Rp. {{ number_format($data->amount_overtime, 0, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                        <td></td>
                        <td class="">Sub-Total</td>
                        <td>Rp. {{ number_format($data->subtotal, 0, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                        <td></td>
                        <td class="">Potongan</td>
                        <td>Rp. {{ number_format($data->amount_deductions, 0, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                        <td></td>
                        <td class="text-primary fw-bold">Grand-Total</td>
                        <td class="text-primary fw-bold">Rp. {{ number_format($data->net_salary, 0, '.', ',') }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- <div class="d-md-flex justify-content-between my-5">
                <div>
                    <h5 class="fw-bold my-4">Hubungi Kami</h5>
                    <ul class="list-unstyled">
                        <li><iconify-icon class="social-icon text-primary fs-5 me-2" icon="mdi:location"
                                style="vertical-align:text-bottom"></iconify-icon> Indonesia, Jawa Tengah, Sukoharjo
                        </li>
                        <li><iconify-icon class="social-icon text-primary fs-5 me-2" icon="solar:phone-bold"
                                style="vertical-align:text-bottom"></iconify-icon> (510) 710-3464</li>
                        <li><iconify-icon class="social-icon text-primary fs-5 me-2" icon="ic:baseline-email"
                                style="vertical-align:text-bottom"></iconify-icon> sm@gmail.com</li>
                    </ul>
                </div>
                <div>
                    <h5 class="fw-bold my-4">Informasi Pembayaran</h5>
                    <ul class="list-unstyled">
                        <li><span class="fw-semibold">Pembayaran: </span> Tunai</li>
                        <li><span class="fw-semibold">Nama: </span> -</li>
                        <li><span class="fw-semibold">Rekening: </span> - </li>

                    </ul>
                </div>
            </div> --}}

            <div class="text-center my-5">
                <p class="text-muted"><span class="fw-semibold">Terimakasih! </span> telah menjadi bagian dari Kami.</p>
            </div>

            <div id="footer-bottom">
                <div class="container border-top">
                    <div class="row mt-3">
                        <div class="col-6 copyright">
                            <p>©{{ parseDate(Date::now(), 'Y') }} all right reserved. </p>
                        </div>
                        <div class="col-6 text-muted text-end">
                            <p>{{ $data->hash }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <script>
        function handlePrint() {
            window.print();
            setTimeout(() => {
                window.location.href = document.referrer ||
                '/'; // balik ke halaman sebelumnya atau home kalau referrer kosong
            }, 500); // kasih delay biar print dialog sempat muncul dulu
        }
    </script>

</body>

</html>
