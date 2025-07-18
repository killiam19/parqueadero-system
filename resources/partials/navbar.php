<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl flex h-16 items-center justify-center">
        <div class="flex gap-4">
            <a href="/"      class="<?= $_SERVER['REQUEST_URI'] === '/'      ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium">Inicio <i class="fas fa-home" style="color:white"></i></a>
            <a href="/about" class="<?= $_SERVER['REQUEST_URI'] === '/about' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium">Mis reservas <i class="fas fa-calendar-alt"></i></a>
            <a href="/links" class="<?= $_SERVER['REQUEST_URI'] === '/links' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium">Cerrar Sesión <i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
</nav>