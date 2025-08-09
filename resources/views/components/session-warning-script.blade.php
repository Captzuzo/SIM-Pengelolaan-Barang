<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const sessionLifetime = {{ config('session.lifetime') * 60 * 1000 }};
    const warningBefore = 1 * 60 * 1000; // 1 menit sebelum timeout

    setTimeout(() => {
        Swal.fire({
            icon: 'warning',
            title: 'Sesi Akan Berakhir!',
            text: 'Sesi Anda akan berakhir dalam 1 menit. Silakan klik OK untuk tetap aktif.',
            confirmButtonText: 'OK',
        }).then(() => {
            window.location.reload(); // refresh halaman untuk perpanjang sesi
        });
    }, sessionLifetime - warningBefore);

    setTimeout(() => {
        window.location.href = "{{ route('filament.auth.login') }}";
    }, sessionLifetime);
</script>
