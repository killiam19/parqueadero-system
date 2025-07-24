<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl flex h-16 items-center justify-center">
        <div class="flex gap-4">
            <a href="/"      class="<?= requestIs('/')      ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium">Inicio <i class="fas fa-home" style="color:white"></i></a>
            <a href="/about" class="<?= requestIs('/about') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium">Acerca de</a>
            <a href="/mis-reservas" class="<?= requestIs('/mis-reservas') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium">Mis reservas <i class="fas fa-calendar-alt"></i></a>

                       <?php if (isAuthenticated()): ?>
            <form action="/logout" method="POST">                
                <button type="submit" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium cursor-pointer">
                    Cerrar sesión
                </button>
            </form>
            <?php else: ?>
            <a href="/login" class="<?= requestIs('login') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium">Iniciar sesión</a>
            <?php endif ; ?>
        </div>
    </div>
</nav>