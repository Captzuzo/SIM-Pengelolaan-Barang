<!-- resources/views/vendor/filament/components/logout-confirmation.blade.php -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const logoutLinks = document.querySelectorAll('form[action$="logout"] button[type="submit"]');

        logoutLinks.forEach(function (btn) {
            btn.addEventListener("click", function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Yakin mau logout?',
                    text: "Anda akan keluar dari aplikasi.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, logout',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        e.target.closest('form').submit(); // submit form logout
                    }
                });
            });
        });
    });
</script>
