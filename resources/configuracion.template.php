<?php require resource_path('partials/header.php'); ?>

<div class="min-h-screen bg-gray-50 py-8">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Título principal -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Configuración</h1>
    </div>

    <!-- Grid de opciones de configuración -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      
      <!-- Editar información personal -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer group">
        <div class="flex items-center justify-between">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-blue-600 group-hover:text-blue-700 mb-2">
              Editar información personal
            </h3>
            <p class="text-sm text-gray-600 leading-relaxed">
              Cambia tus datos personales, solo serán usados para el registro y permanecerán privados
            </p>
          </div>
          <div class="ml-4">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </div>
        </div>
      </div>

      <!-- Cambiar contraseña -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer group">
        <div class="flex items-center justify-between">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-blue-600 group-hover:text-blue-700 mb-2">
              Cambiar contraseña
            </h3>
            <p class="text-sm text-gray-600 leading-relaxed">
              Cambia tu contraseña
            </p>
          </div>
          <div class="ml-4">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </div>
        </div>
      </div>

      <!-- Cambiar segunda clave -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer group">
        <div class="flex items-center justify-between">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-blue-600 group-hover:text-blue-700 mb-2">
              Cambiar segunda clave
            </h3>
            <p class="text-sm text-gray-600 leading-relaxed">
              Cambia o establece tu segunda clave
            </p>
          </div>
          <div class="ml-4">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </div>
        </div>
      </div>

      <!-- Puedes agregar más opciones aquí -->
      <!-- Notificaciones -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer group">
        <div class="flex items-center justify-between">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-blue-600 group-hover:text-blue-700 mb-2">
              Configurar notificaciones
            </h3>
            <p class="text-sm text-gray-600 leading-relaxed">
              Gestiona tus preferencias de notificaciones
            </p>
          </div>
          <div class="ml-4">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </div>
        </div>
      </div>

      <!-- Seguridad -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer group">
        <div class="flex items-center justify-between">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-blue-600 group-hover:text-blue-700 mb-2">
              Configuración de seguridad
            </h3>
            <p class="text-sm text-gray-600 leading-relaxed">
              Administra la seguridad de tu cuenta
            </p>
          </div>
          <div class="ml-4">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </div>
        </div>
      </div>

      <!-- Privacidad -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer group">
        <div class="flex items-center justify-between">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-blue-600 group-hover:text-blue-700 mb-2">
              Configuración de privacidad
            </h3>
            <p class="text-sm text-gray-600 leading-relaxed">
              Controla quién puede ver tu información
            </p>
          </div>
          <div class="ml-4">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Script para funcionalidad de contraseña (mantenido de tu código original) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script>
  const loginPasswordInput = document.getElementById('login-password');
  const loginToggleBtn = document.getElementById('toggle-login-password');
  const loginEyeIcon = document.getElementById('login-eye-icon');
  
  if (loginToggleBtn) {
    loginToggleBtn.addEventListener('click', function() {
      if (loginPasswordInput.type === 'password') {
        loginPasswordInput.type = 'text';
        loginEyeIcon.classList.remove('fa-eye');
        loginEyeIcon.classList.add('fa-eye-slash');
      } else {
        loginPasswordInput.type = 'password';
        loginEyeIcon.classList.remove('fa-eye-slash');
        loginEyeIcon.classList.add('fa-eye');
      }
    });
  }

  // Funcionalidad para hacer clickeable las tarjetas
  document.querySelectorAll('.cursor-pointer').forEach(card => {
    card.addEventListener('click', function() {
      // Aquí puedes agregar la lógica de navegación
      const title = this.querySelector('h3').textContent.trim();
      console.log('Navegando a:', title);
      
      // Ejemplo de navegación (personaliza según tu estructura de rutas)
      switch(title) {
        case 'Editar información personal':
          // window.location.href = '/configuracion/perfil';
          break;
        case 'Cambiar contraseña':
          // window.location.href = '/configuracion/password';
          break;
        case 'Cambiar segunda clave':
          // window.location.href = '/configuracion/segunda-clave';
          break;
        // Agrega más casos según necesites
      }
    });
  });
</script>

<?php require resource_path('partials/new.footer.php'); ?>