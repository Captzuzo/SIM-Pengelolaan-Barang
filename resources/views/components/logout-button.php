<div x-data="{ showLogoutModal: false }" x-cloak>
    <!-- Tombol Logout di User Menu -->
    <button x-on:click="showLogoutModal = true"
        class="flex w-full items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
        <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5 mr-2" />
        Logout
    </button>

    <!-- Modal Konfirmasi Logout -->
    <div x-show="showLogoutModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-80 text-center shadow-xl">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                Yakin ingin logout?
            </h2>
            <div class="flex justify-center gap-4">
                <button @click="showLogoutModal = false"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-white rounded">
                    Batal
                </button>
                <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>