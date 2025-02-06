<style>
    .custom-swal {
        width: 90%;
        /* Default untuk layar kecil */
        max-width: 300px;
        /* Batas maksimal */
    }
</style>

@if (session('success'))
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('') }}",
            icon: 'success',
            confirmButtonText: 'OK'
            customClass: {
                popup: 'custom-swal',
            },
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            title: 'Gagal!',
            text: "{{ session('') }}",
            icon: 'error',
            confirmButtonText: 'OK'
            customClass: {
                popup: 'custom-swal',
            },
        });
    </script>
@endif

@if (session('any'))
    <script>
        Swal.fire({
            title: 'Terjadi Kesalahan!',
            text: "{{ session('any') }}",
            icon: 'warning',
            confirmButtonText: 'OK'
            customClass: {
                popup: 'custom-swal',
            },
        });
    </script>
@endif
