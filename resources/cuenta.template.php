<?php require resource_path('partials/header.php'); ?>

<div class="min-h-screen bg-gray-50 py-8">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Título principal -->
    <div class="text-center mb-8">
      <h1 class="text-4xl font-normal text-gray-600 mb-4">Mi Cuenta</h1>
      <div class="w-full h-1 bg-gradient-to-r from-red-400 via-red-400 to-red-500 rounded-full"></div>
    </div>

    <!-- Contenedor principal -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
      
      <!-- Información Personal -->
      <div class="p-8">
        <div class="space-y-6">
          
          <!-- Nombres -->
          <div class="flex flex-col sm:flex-row sm:items-center border-b border-gray-100 pb-4">
            <div class="w-full sm:w-32 mb-2 sm:mb-0">
              <label class="text-lg font-medium text-gray-800">Nombre:</label>
            </div>
            <div class="flex-1">
              <span class="text-lg text-gray-700"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
            </div>
          </div>

          <!-- Correo -->
          <div class="flex flex-col sm:flex-row sm:items-center border-b border-gray-100 pb-4">
            <div class="w-full sm:w-32 mb-2 sm:mb-0">
              <label class="text-lg font-medium text-gray-800">Correo:</label>
            </div>
            <div class="flex-1">
              <span class="text-lg text-blue-600"><?php echo htmlspecialchars($_SESSION['usuario_email']); ?></span>
            </div>
          </div>

          <!-- Número -->
          <div class="flex flex-col sm:flex-row sm:items-center pb-4">
            <div class="w-full sm:w-32 mb-2 sm:mb-0">
              <label class="text-lg font-medium text-gray-800">Número:</label>
            </div>
            <div class="flex-1">
              <span class="text-lg text-gray-700">1011322853</span>
            </div>
          </div>

        </div>
      </div>

      <!-- Separador -->
     <hr class="w-48 h-1 mx-auto my-4 bg-gray-100 border-0 rounded-sm md:my-10 dark:bg-gray-700">

      <!-- Información Académica -->
      <div class="p-8">
        <div class="space-y-6">
          
          <!-- Curso Actual -->
          <div class="flex flex-col sm:flex-row sm:items-center border-b border-gray-100 pb-4">
            <div class="w-full sm:w-32 mb-2 sm:mb-0">
              <label class="text-lg font-medium text-gray-800">Curso Actual:</label>
            </div>
            <div class="flex-1">
              <span class="text-lg text-gray-700">Connectivity 2</span>
            </div>
          </div>

          <!-- Vigencia -->
          <div class="flex flex-col sm:flex-row sm:items-center pb-4">
            <div class="w-full sm:w-32 mb-2 sm:mb-0">
              <label class="text-lg font-medium text-gray-800">Vigencia:</label>
            </div>
            <div class="flex-1">
              <span class="text-lg text-gray-700">2027-05-28</span>
            </div>
          </div>

        </div>
      </div>

      <!-- Otra línea separadora -->
      <hr class="w-48 h-1 mx-auto my-4 bg-gray-100 border-0 rounded-sm md:my-10 dark:bg-gray-700">

      <!-- Información de Sede -->
      <div class="p-8">
        <div class="flex flex-col sm:flex-row sm:items-center">
          <div class="w-full sm:w-32 mb-2 sm:mb-0">
            <label class="text-lg font-medium text-blue-700">Sede Original:</label>
          </div>
          <div class="flex-1">
            <span class="text-lg text-gray-700">TUNAL</span>
          </div>
        </div>
      </div>

      <!-- Línea final -->
      <div class="w-full h-1 bg-gradient-to-r from-red-400 via-orange-400 to-red-500"></div>

      <!-- Botón de Notificaciones -->
      <div class="p-8">
        <button class="bg-green-500 hover:bg-green-600 text-white font-medium px-6 py-3 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
          Mostrar Notificaciones
        </button>
      </div>

    </div>

    <!-- Información adicional o acciones rápidas (opcional) -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
      
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Perfil Completo</h3>
        <p class="text-sm text-gray-600">Tu información está actualizada</p>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Curso Activo</h3>
        <p class="text-sm text-gray-600">Connectivity 2 vigente hasta 2027</p>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Sede Asignada</h3>
        <p class="text-sm text-gray-600">TUNAL - Sede original</p>
      </div>

    </div>
  </div>
</div>

<?php require resource_path('partials/new.footer.php'); ?>