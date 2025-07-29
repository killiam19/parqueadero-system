<?php require resource_path('partials/header.php'); ?>

<div class="border-b border-gray-200 pb-8 mb-8">
    <h2 class="text-4xl font-semibold text-gray-900 sm:text-5xl text-center">Iniciar Sesión</h2>
</div>

<div class="w-full max-w-xl mx-auto">
    <form action="/login" method="POST">
        <div class="mb-4">
            <label class="text-sm font-semibold text-gray-900">Email</label>
            <div class="mt-2">
                <input 
                    type="text" 
                    name="email" 
                    class="w-full outline-1 outline-gray-300 rounded-md px-3 py-2 text-gray-900" 
                    value="<?= old('email') ?>">
            </div>
        </div>

        <div class="mb-4">
            <label class="text-sm font-semibold text-gray-900">Contraseña</label>
            <div class="mt-2">
                <input 
                    type="password" 
                    name="password" 
                    class="w-full outline-1 outline-gray-300 rounded-md px-3 py-2 text-gray-900">
            </div>
        </div>

        <div class="mt-4">
            
            <p class="text-gray-500 dark:text-gray-400">¿Aún no tienes una cuenta?<a href="/register" class="inline-flex items-center font-medium text-blue-600 dark:text-blue-500 hover:underline">
            Regístrate
            <svg class="w-4 h-4 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
            </a></p>

            <button type="submit" class="w-full rounded-md bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-2 text-center text-sm font-semibold">
                Iniciar sesión &rarr;
            </button>
        </div>
    </form>
<br>
    <?= errors() ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<?php require resource_path('partials/new.footer.php'); ?>