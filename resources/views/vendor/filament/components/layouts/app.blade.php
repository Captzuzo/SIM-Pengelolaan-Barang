<div x-data="{ showLogoutModal: false }" x-cloak>
    <div x-show="showLogoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg p-6 w-80 text-center shadow-xl">
            <h2 class="text-lg font-semibold mb-4">Yakin ingin logout?</h2>
            <div class="flex justify-center gap-4">
                <button @click="showLogoutModal = false" class="px-4 py-2 bg-gray-200 rounded">Batal</button>
                <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
@include('components.logout-confirmation')