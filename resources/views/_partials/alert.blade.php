<style>
    /* Responsif untuk layar kecil */
    @media (max-width: 300px) {
        .swal2-popup {
            width: 90%;
        }
    }
</style>

@if (session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif


@if (session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@if (session('any'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            Swal.fire({
                title: 'Terjadi Kesalahan!',
                text: "{{ session('any') }}",
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif
