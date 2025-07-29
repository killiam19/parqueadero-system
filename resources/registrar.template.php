<?php require resource_path('partials/header.php'); ?>

<div class="border-b border-gray-200 pb-8 mb-8">
    <h2 class="text-4xl font-semibold text-gray-900 sm:text-5xl text-center">Regístrate Aquí</h2>
</div>

<div class="w-full max-w-xl mx-auto">
    
<form class="max-w-sm mx-auto" method="POST" action="/register">
    <div class="mb-5">
    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Tu nombre</label>
    <input type="text" id="name" name="name" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" placeholder="Tus nombres y apellidos" required />
  </div>
  <div class="mb-5">
    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Tu correo electrónico</label>
    <input type="email" id="email" name="email" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" placeholder="tucorreo@correo.com" required />
  </div>
  <div class="mb-5">
    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Tu contraseña</label>
    <div class="relative">
      <input type="password" id="password" name="password" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-12 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" required autocomplete="new-password" />
      <button type="button" id="toggle-password" tabindex="-1" class="absolute right-3 top-1/2 -translate-y-1/2 p-0 bg-transparent border-0 text-gray-400 hover:text-blue-600 focus:outline-none">
        <i class="fa-solid fa-eye text-lg" id="password-eye-icon"></i>
      </button>
    </div>
  </div>
  <div class="mb-5">
    <label for="repeat-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Repetir contraseña</label>
    <div class="relative">
      <input type="password" id="repeat-password" name="repeat-password" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-12 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" required autocomplete="new-password" />
      <button type="button" id="toggle-repeat-password" tabindex="-1" class="absolute right-3 top-1/2 -translate-y-1/2 p-0 bg-transparent border-0 text-gray-400 hover:text-blue-600 focus:outline-none">
        <i class="fa-solid fa-eye text-lg" id="repeat-password-eye-icon"></i>
      </button>
    </div>
  </div>
  <div class="flex items-start mb-5">
    <div class="flex items-center h-5">
      <input id="terms" name="terms" type="checkbox" value="1" class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required />
    </div>
    <label for="terms" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">He leído y acepto el <a href="/reglamento" class="text-blue-600 hover:underline dark:text-blue-500">Reglamento del parqueadero</a></label>
  </div>
    <p class="text-gray-500 dark:text-gray-400">¿Ya tienes una cuenta?<a href="/login" class="inline-flex items-center font-medium text-blue-600 dark:text-blue-500 hover:underline">
            Inicia Sesión
            <svg class="w-4 h-4 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
            </a></p>
  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar nueva cuenta</button>
</form>
<br>
    <?= errors() ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script>
  // Toggle para contraseña principal
  const passwordInput = document.getElementById('password');
  const passwordToggleBtn = document.getElementById('toggle-password');
  const passwordEyeIcon = document.getElementById('password-eye-icon');
  if (passwordToggleBtn) {
    passwordToggleBtn.addEventListener('click', function() {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordEyeIcon.classList.remove('fa-eye');
        passwordEyeIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        passwordEyeIcon.classList.remove('fa-eye-slash');
        passwordEyeIcon.classList.add('fa-eye');
      }
    });
  }
  // Toggle para repetir contraseña
  const repeatPasswordInput = document.getElementById('repeat-password');
  const repeatPasswordToggleBtn = document.getElementById('toggle-repeat-password');
  const repeatPasswordEyeIcon = document.getElementById('repeat-password-eye-icon');
  if (repeatPasswordToggleBtn) {
    repeatPasswordToggleBtn.addEventListener('click', function() {
      if (repeatPasswordInput.type === 'password') {
        repeatPasswordInput.type = 'text';
        repeatPasswordEyeIcon.classList.remove('fa-eye');
        repeatPasswordEyeIcon.classList.add('fa-eye-slash');
      } else {
        repeatPasswordInput.type = 'password';
        repeatPasswordEyeIcon.classList.remove('fa-eye-slash');
        repeatPasswordEyeIcon.classList.add('fa-eye');
      }
    });
  }
</script>
<?php require resource_path('partials/new.footer.php'); ?>