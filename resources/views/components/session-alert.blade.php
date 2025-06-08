@if (session('success'))
    <script>
        $.notify({
            icon: 'icon-bell',
            title: 'Berhasil!',
            message: "{{ session('success') }}",
        }, {
            type: 'success',
            placement: {
                from: "bottom",
                align: "right"
            },
            time: 1000,
        });
    </script>
@endif

@if (session('error'))
    <script>
        swal({
            title: "Oops!",
            text: "{{ session('error') }}",
            icon: "error",
            buttons: {
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true,
                },
            },
        });
    </script>
@endif

{{-- dialog confirm before delete --}}
<script>
    $('.deleteBtn').on('click', function(e) {
        e.preventDefault();

        swal({
            title: "Yakin ingin menghapus data?",
            text: "Data akan dihapus permanen!",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Batal",
                    visible: true,
                    className: "btn btn-primary",
                    closeModal: true,
                },
                confirm: {
                    text: "Iya, lanjutkan!",
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true,
                }
            }
        }).then((willSubmit) => {
            if (willSubmit) {
                $(this).closest('form').submit();
            }
        });
    });

    $('.logoutBtn').on('click', function(e) {
        e.preventDefault();

        swal({
            title: "Keluar Akun?",
            text: "Pastikan Anda mengingat kredensial login!",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Batal",
                    visible: true,
                    className: "btn btn-primary",
                    closeModal: true,
                },
                confirm: {
                    text: "Iya, Logout!",
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true,
                }
            }
        }).then((willSubmit) => {
            if (willSubmit) {
                $(this).closest('form').submit();
            }
        });
    });

    $('.sureBtn').on('click', function(e) {
        e.preventDefault();

        swal({
            title: "Apa Anda Yakin?",
            text: "Tidakan ini tidak dapat dibatalkan!",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Batal",
                    visible: true,
                    className: "btn btn-primary",
                    closeModal: true,
                },
                confirm: {
                    text: "Iya, Lanjutkan!",
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true,
                }
            }
        }).then((willSubmit) => {
            if (willSubmit) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
