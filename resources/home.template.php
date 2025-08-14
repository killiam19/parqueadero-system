<?php require __DIR__ . '/partials/header.php'; ?>
<style>
.radio-inputs {
  display: flex;
  justify-content: center;
  align-items: center;
  max-width: 350px;
  -webkit-user-select: none;
  -moz-user-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
.radio-inputs > * {
  margin: 6px;
}
.radio-input:checked + .radio-tile {
  border-color: #2260ff;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
  color: #2260ff;
}
.radio-input:checked + .radio-tile:before {
  transform: scale(1);
  opacity: 1;
  background-color: #2260ff;
  border-color: #2260ff;
}
.radio-input:checked + .radio-tile .radio-icon svg {
  fill: #2260ff;
}
.radio-input:checked + .radio-tile .radio-label {
  color: #2260ff;
}
.radio-input:focus + .radio-tile {
  border-color: #2260ff;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1), 0 0 0 4px #b5c9fc;
}
.radio-input:focus + .radio-tile:before {
  transform: scale(1);
  opacity: 1;
}
.radio-tile {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 80px;
  min-height: 80px;
  border-radius: 0.5rem;
  border: 2px solid #b5bfd9;
  background-color: #fff;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
  transition: 0.15s ease;
  cursor: pointer;
  position: relative;
}
.radio-tile:before {
  content: "";
  position: absolute;
  display: block;
  width: 0.75rem;
  height: 0.75rem;
  border: 2px solid #b5bfd9;
  background-color: #fff;
  border-radius: 50%;
  top: 0.25rem;
  left: 0.25rem;
  opacity: 0;
  transform: scale(0);
  transition: 0.25s ease;
}
.radio-tile:hover {
  border-color: #2260ff;
}
.radio-tile:hover:before {
  transform: scale(1);
  opacity: 1;
}
.radio-icon svg {
  width: 2rem;
  height: 2rem;
  fill: #494949;
}
.radio-label {
  color: #707070;
  transition: 0.375s ease;
  text-align: center;
  font-size: 13px;
}
.radio-input {
  clip: rect(0 0 0 0);
  -webkit-clip-path: inset(100%);
  clip-path: inset(100%);
  height: 1px;
  overflow: hidden;
  position: absolute;
  white-space: nowrap;
  width: 1px;
}

/* Estilos para mensajes de validación */
.mensaje {
  padding: 12px 16px;
  margin: 16px 0;
  border-radius: 8px;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
}

.mensaje.success {
  background-color: #d1fae5;
  border: 1px solid #10b981;
  color: #065f46;
}

.mensaje.error {
  background-color: #fee2e2;
  border: 1px solid #ef4444;
  color: #991b1b;
}

.mensaje.warning {
  background-color: #fef3c7;
  border: 1px solid #f59e0b;
  color: #92400e;
}

.mensaje::before {
  font-family: "Font Awesome 5 Free";
  font-weight: 900;
  font-size: 16px;
}

.mensaje.success::before {
  content: "\f00c"; /* check icon */
}

.mensaje.error::before {
  content: "\f071"; /* exclamation triangle */
}

.mensaje.warning::before {
  content: "\f06a"; /* exclamation circle */
}

/* Estilos para ocultar/mostrar secciones */
.seccion-oculta {
  display: none !important;
}

.seccion-visible {
  display: block !important;
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.datepicker-principal {
  background: #f8fafc;
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 32px;
  text-align: center;
}

.datepicker-principal h2 {
  color: #1e293b;
  margin-bottom: 16px;
  font-size: 1.5rem;
  font-weight: 600;
}

.datepicker-principal .relative {
  max-width: 320px;
  margin: 0 auto;
}
</style>
 
<div class="border-b border-gray-200 pb-8 mb-8">
    <img src="assets/images/3shape-intraoral-logo.png" alt="" width="50" height="50">

    <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-black">Sistema de Agendamiento de Parqueadero de 3Shape</h1>
    <?php if (isset($_SESSION['usuario_nombre']) && $_SESSION['usuario_nombre']): ?>
    <p class="mb-6 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">Te damos la bienvenida, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
    <?php endif; ?>
    <a href="/about" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900">
        Conoce más
        <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
      </svg>
    </a>

    <button id="open-carousel-btn" style="display: block; margin: 20px auto; padding: 10px 20px; font-size: 16px; cursor: pointer; background-color: #2260ff; color: white; border: none; border-radius: 5px;">Ver distribución de parqueadero</button>

    <!-- Modal Start -->
    <div id="carousel-modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
      <div style="background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 8px; max-width: 650px; position: relative;">
        <button id="close-carousel-btn" aria-label="Cerrar" style="position: absolute; top: 10px; right: 10px; background: #f44336; border: none; color: white; font-size: 20px; padding: 5px 10px; cursor: pointer; border-radius: 5px;">&times;</button>
        
        <div id="image-carousel" style="max-width: 600px; margin: 0 auto; position: relative; overflow: hidden; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="carousel-images" style="display: flex; transition: transform 0.5s ease;">
                <img src="assets/images/Parking.png" alt="Image 2" style="width: 100%; flex-shrink: 0;">
                <img src="assets/images/Mapa Parqueadero Completo.png" alt="Image 1" style="width: 100%; flex-shrink: 0;">
            </div>
            <div style="text-align: center; margin-top: 8px; font-weight: bold; color: #444;">Mapa conceptual / Referencia de espacios</div>
            <!-- Navigation buttons -->
            <button id="prev-btn" aria-label="Previous" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.3); border: none; color: white; font-size: 24px; padding: 8px 12px; cursor: pointer; border-radius: 50%;">&#10094;</button>
            <button id="next-btn" aria-label="Next" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.3); border: none; color: white; font-size: 24px; padding: 8px 12px; cursor: pointer; border-radius: 50%;">&#10095;</button>
        </div>
      </div>
    </div>

    <script>
        (function() {
            const carousel = document.getElementById('image-carousel');
            const imagesContainer = carousel.querySelector('.carousel-images');
            const images = imagesContainer.querySelectorAll('img');
            const totalImages = images.length;
            let currentIndex = 0;

            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');

            function updateCarousel() {
                const offset = -currentIndex * 100;
                imagesContainer.style.transform = 'translateX(' + offset + '%)';
            }

            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + totalImages) % totalImages;
                updateCarousel();
            });

            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % totalImages;
                updateCarousel();
            });

            // Modal open/close logic
            const openBtn = document.getElementById('open-carousel-btn');
            const modal = document.getElementById('carousel-modal');
            const closeBtn = document.getElementById('close-carousel-btn');

            openBtn.addEventListener('click', () => {
                modal.style.display = 'block';
                currentIndex = 0; // Reset to first image when opening
                updateCarousel();
            });

            closeBtn.addEventListener('click', () => {
                modal.style.display = 'none';
            });

            // Close modal when clicking outside the modal content
            window.addEventListener('click', (event) => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        })();
    </script>
</div>

<!-- Sección principal del datepicker -->
<div class="datepicker-principal">
    <h2><i class="fas fa-calendar-alt"></i> Selecciona la fecha de tu reserva</h2>
    <p class="mb-4 text-gray-600">Primero elige la fecha en la que deseas reservar tu espacio de parqueadero</p>
    
    <div class="relative max-w-sm" style="margin: 0 auto;">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
            </svg>
        </div>
        <input datepicker id="default-datepicker" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Selecciona la fecha en la que deseas reservar">
    </div>
    
    <div id="mensaje-seleccionar-fecha" class="mt-4 text-sm text-gray-500">
        <i class="fas fa-arrow-up"></i> Selecciona una fecha para ver los espacios disponibles
    </div>
</div>

<!-- Contenedor de mapas y formulario - inicialmente oculto -->
<div id="seccion-mapas-reserva" class="seccion-oculta">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-16">
        <div class="container">
            
            <!-- Mostrar mensajes de validación y confirmación -->
            <?php if (isset($mensaje) && !empty($mensaje)): ?>
                <div class="mensaje <?php echo isset($tipo_mensaje) ? $tipo_mensaje : 'error'; ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            
            <!-- Mostrar mensaje adicional si hay parámetros en la URL (para redirects) -->
            <?php if (isset($_GET['message'])): ?>
                <div class="mensaje <?php echo isset($_GET['type']) ? $_GET['type'] : 'error'; ?>">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                </div>
            <?php endif; ?>
            
            <!-- Mostrar fecha seleccionada -->
            <div id="fecha-seleccionada-info" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">
                    <i class="fas fa-calendar-check"></i> Fecha seleccionada: <span id="fecha-mostrar"></span>
                </h3>
                <p class="text-blue-600 text-sm">Ahora selecciona el tipo de vehículo y el espacio que deseas reservar</p>
            </div>
            
            <div class="grid">
                <div>
                    <div class="card" style="margin-bottom: 32px; width: 100%; max-width: 100vw;">
                        <div style="margin-bottom: 18px;">
                            <label style="font-weight:bold; font-size:1.1rem; margin-right: 18px;">Tipo de Vehículo:</label>
                            <div class="radio-inputs">
                                <label>
                                    <input class="radio-input" type="radio" name="tipo_vehiculo_selector" value="moto">
                                    <span class="radio-tile">
                                        <span class="radio-icon">
                                            <!-- Moto SVG -->
                                            <svg stroke="currentColor" xml:space="preserve" viewBox="0 0 467.168 467.168" fill="none" width="32" height="32"><g><g><path d="M76.849,210.531C34.406,210.531,0,244.937,0,287.388c0,42.438,34.406,76.847,76.849,76.847 c30.989,0,57.635-18.387,69.789-44.819l18.258,14.078c0,0,134.168,0.958,141.538-3.206c0,0-16.65-45.469,4.484-64.688 c2.225-2.024,5.021-4.332,8.096-6.777c-3.543,8.829-5.534,18.45-5.534,28.558c0,42.446,34.403,76.846,76.846,76.846 c42.443,0,76.843-34.415,76.843-76.846c0-42.451-34.408-76.849-76.843-76.849c-0.697,0-1.362,0.088-2.056,0.102 c5.551-3.603,9.093-5.865,9.093-5.865l-5.763-5.127c0,0,16.651-3.837,12.816-12.167c-3.848-8.33-44.19-58.28-44.19-58.28 s7.146-15.373-7.634-26.261l-7.098,15.371c0,0-18.093-12.489-25.295-10.084c-7.205,2.398-18.005,3.603-21.379,8.884l-3.358,3.124 c0,0-0.95,5.528,4.561,13.693c0,0,55.482,17.05,58.119,29.537c0,0,3.848,7.933-12.728,9.844l-3.354,4.328l-8.896,0.479 l-16.082-36.748c0,0-15.381,4.082-23.299,10.323l1.201,6.24c0,0-64.599-43.943-125.362,21.137c0,0-44.909,12.966-76.37-26.897 c0,0-0.479-12.968-76.367-10.565l5.286,5.524c0,0-5.286,0.479-7.444,3.841c-2.158,3.358,1.2,6.961,18.494,6.961 c0,0,39.153,44.668,69.17,42.032l42.743,20.656l18.975,32.42c0,0,0.034,2.785,0.23,7.045c-4.404,0.938-9.341,1.979-14.579,3.09 C139.605,232.602,110.832,210.531,76.849,210.531z M390.325,234.081c29.395,0,53.299,23.912,53.299,53.299 c0,29.39-23.912,53.294-53.299,53.294c-29.394,0-53.294-23.912-53.294-53.294C337.031,257.993,360.932,234.081,390.325,234.081z M76.849,340.683c-29.387,0-53.299-23.913-53.299-53.295c0-29.395,23.912-53.299,53.299-53.299 c22.592,0,41.896,14.154,49.636,34.039c-28.26,6.011-56.31,11.99-56.31,11.99l3.619,19.933l55.339-2.444 C124.365,322.116,102.745,340.683,76.849,340.683z M169.152,295.835c1.571,5.334,3.619,9.574,6.312,11.394l-24.696,0.966 c1.058-3.783,1.857-7.666,2.338-11.662L169.152,295.835z"></path></g></g></svg>
                                        </span>
                                        <span class="radio-label">Moto</span>
                                    </span>
                                </label>
                                <label>
                                    <input class="radio-input" type="radio" name="tipo_vehiculo_selector" value="moto_grande">
                                    <span class="radio-tile">
                                        <span class="radio-icon">
                                            <!-- Moto Grande SVG -->
                                            <svg stroke="currentColor" xml:space="preserve" viewBox="0 0 512 512" fill="none" width="32" height="32">
                                                <g>
                                                    <path d="M402.1,234.3c-19.8,0-37.8,7.2-51.8,19.1l-41.2-20.6c5.3-8.9,8.4-19.3,8.4-30.5c0-32.4-26.3-58.7-58.7-58.7
                                                    c-32.4,0-58.7,26.3-58.7,58.7c0,11.2,3.1,21.6,8.4,30.5l-41.2,20.6c-14-11.9-32-19.1-51.8-19.1C51.1,234.3,0,285.4,0,349.9
                                                    s51.1,115.6,115.6,115.6c51.9,0,95.1-34.2,109.6-81.1h61.6c14.5,46.9,57.7,81.1,109.6,81.1c64.5,0,115.6-51.1,115.6-115.6
                                                    S466.6,234.3,402.1,234.3z M115.6,432.8c-45.8,0-82.9-37.1-82.9-82.9s37.1-82.9,82.9-82.9s82.9,37.1,82.9,82.9
                                                    S161.4,432.8,115.6,432.8z M276.1,202.3c0-13.6,11.1-24.7,24.7-24.7s24.7,11.1,24.7,24.7s-11.1,24.7-24.7,24.7
                                                    S276.1,215.9,276.1,202.3z M402.1,432.8c-45.8,0-82.9-37.1-82.9-82.9s37.1-82.9,82.9-82.9s82.9,37.1,82.9,82.9
                                                    S447.9,432.8,402.1,432.8z"/>
                                                    <circle cx="115.6" cy="349.9" r="33"/>
                                                    <circle cx="402.1" cy="349.9" r="33"/>
                                                    <path d="M341.3,98.7h-34.1V64.6c0-9.1-7.4-16.4-16.4-16.4s-16.4,7.4-16.4,16.4v34.1h-34.1c-9.1,0-16.4,7.4-16.4,16.4
                                                    s7.4,16.4,16.4,16.4h34.1v34.1c0,9.1,7.4,16.4,16.4,16.4s16.4-7.4,16.4-16.4v-34.1h34.1c9.1,0,16.4-7.4,16.4-16.4
                                                    S350.4,98.7,341.3,98.7z"/>
                                                </g>
                                            </svg>
                                        </span>
                                        <span class="radio-label">Moto Grande</span>
                                    </span>
                                </label>
                                <label>
                                    <input class="radio-input" type="radio" name="tipo_vehiculo_selector" value="carro" checked>
                                    <span class="radio-tile">
                                        <span class="radio-icon">
                                            <!-- Carro SVG -->
                                            <svg stroke="currentColor" xml:space="preserve" viewBox="0 0 324.018 324.017" fill="none" width="32" height="32"><g><g><path d="M317.833,197.111c3.346-11.148,2.455-20.541-2.65-27.945c-9.715-14.064-31.308-15.864-35.43-16.076l-8.077-4.352 l-0.528-0.217c-8.969-2.561-42.745-3.591-47.805-3.733c-7.979-3.936-14.607-7.62-20.475-10.879 c-20.536-11.413-34.107-18.958-72.959-18.958c-47.049,0-85.447,20.395-90.597,23.25c-2.812,0.212-5.297,0.404-7.646,0.59 l-6.455-8.733l7.34,0.774c2.91,0.306,4.267-1.243,3.031-3.459c-1.24-2.216-4.603-4.262-7.519-4.57l-23.951-2.524 c-2.91-0.305-4.267,1.243-3.026,3.459c1.24,2.216,4.603,4.262,7.519,4.57l3.679,0.386l8.166,11.05 c-13.823,1.315-13.823,2.139-13.823,4.371c0,18.331-2.343,22.556-2.832,23.369L0,164.443v19.019l2.248,2.89 c-0.088,2.775,0.823,5.323,2.674,7.431c5.981,6.804,19.713,7.001,21.256,7.001c4.634,0,14.211-2.366,20.78-4.153 c-0.456-0.781-0.927-1.553-1.3-2.392c-0.36-0.809-0.603-1.668-0.885-2.517c-0.811-2.485-1.362-5.096-1.362-7.845 c0-14.074,11.449-25.516,25.515-25.516s25.52,11.446,25.52,25.521c0,6.068-2.221,11.578-5.773,15.964 c-0.753,0.927-1.527,1.828-2.397,2.641c-1.022,0.958-2.089,1.859-3.254,2.641c29.332,0.109,112.164,0.514,168.708,1.771 c-0.828-0.823-1.533-1.771-2.237-2.703c-0.652-0.854-1.222-1.75-1.761-2.688c-2.164-3.744-3.5-8.025-3.5-12.655 c0-14.069,11.454-25.513,25.518-25.513c14.064,0,25.518,11.449,25.518,25.513c0,5.126-1.553,9.875-4.152,13.878 c-0.605,0.922-1.326,1.755-2.04,2.594c-0.782,0.922-1.616,1.781-2.527,2.584c5.209,0.155,9.699,0.232,13.546,0.232 c19.563,0,23.385-1.688,23.861-5.018C324.114,202.108,324.472,199.602,317.833,197.111z"></path></g></g></svg>
                                        </span>
                                        <span class="radio-label">Carro</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Información sobre restricción de una reserva por día -->
                        <?php if (isset($_SESSION['usuario_nombre']) && $_SESSION['usuario_nombre']): ?>
                        <div style="background: #e0f2fe; border: 1px solid #0288d1; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 14px; color: #01579b;">
                            <i class="fas fa-info-circle" style="margin-right: 5px;"></i>
                            <strong>Recordatorio:</strong> Solo se permite una reserva por día por usuario. Si ya tienes una reserva activa para una fecha, no podrás crear otra para el mismo día, a menos que canceles tu reserva actual.
                        </div>
                        <?php endif; ?>
                        
                        <div id="mapa-carro" style="display:block;">
                            <div class="mapa-titulo" style="justify-content: flex-start;">
                                <span style="color:#2563eb;font-size:2.2rem;">&#128205;</span>
                                Mapa de Espacios de Parqueadero (Carros)-Disponibilidad para la fecha seleccionada
                            </div>
                            <div class="mapa-leyenda" style="justify-content: flex-start;">
                                <span><span style="display:inline-block;width:18px;height:18px;background:#22c55e;border-radius:4px;"></span> Disponible</span>
                                <span><span style="display:inline-block;width:18px;height:18px;background:#f6e6ea;border-radius:4px;"></span> Ocupado</span>
                                <span><span style="display:inline-block;width:18px;height:18px;background:#2563eb;border-radius:4px;"></span> Seleccionado</span>
                            </div>
                            <div class="mapa-espacios-bg">
                                <div id="mapa-espacios-carro">
                                    <?php for ($i = 281; $i >= 272; $i--): ?>
                                        <?php
                                            $estado = 'disponible';
                                            if (isset($ocupados_map[$i])) {
                                                if ($ocupados_map[$i] == $_SESSION['usuario_id']) {
                                                    $estado = 'seleccionado';
                                                } else {
                                                    $estado = 'ocupado';
                                                }
                                            }
                                        ?>
                                        <button type="button" class="espacio-btn <?php echo $estado; ?>" data-espacio="<?php echo $i; ?>" data-tipo="carro" <?php echo ($estado=='ocupado'?'disabled':''); ?>
                                        <?php if($estado=='seleccionado'): ?> style="background:#2563eb;color:#fff;border:2px solid #2563eb;box-shadow:0 0 0 4px #2563eb33;"<?php endif; ?>
                                        >
                                            <span class="icono-auto"><i class="fa-solid fa-car-side"></i></span>
                                            <span><?php echo $i; ?></span>
                                        </button>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div id="ayuda-espacio-carro" style="font-size: 13px; color: #888; margin-top: 3px; text-align:center;">Selecciona un espacio disponible (verde).</div>
                        </div>
                        
                        <div id="mapa-moto" style="display:none;">
                            <div class="mapa-titulo" style="justify-content: flex-start;">
                                <span style="color:#2563eb;font-size:2.2rem;">&#128205;</span>
                                Mapa de Espacios de Parqueadero (Motos)-Disponibilidad para la fecha seleccionada
                            </div>
                            <div class="mapa-leyenda" style="justify-content: flex-start;">
                                <span><span style="display:inline-block;width:18px;height:18px;background:#22c55e;border-radius:4px;"></span> Disponible</span>
                                <span><span style="display:inline-block;width:18px;height:18px;background:#f6e6ea;border-radius:4px;"></span> Lleno</span>
                                <span><span style="display:inline-block;width:18px;height:18px;background:#2563eb;border-radius:4px;"></span> Seleccionado</span>
                            </div>
                            <div class="mapa-espacios-bg" style="display: flex; flex-direction: column; align-items: center;">
                                <div id="mapa-motos-grid">
                                    <!-- Columna 1 -->
                                    <?php
                                        $id = 476;
                                        $ocupados = $moto_ocupados[$id] ?? 0;
                                        $max = 7;
                                        $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                        $info = "$ocupados/$max cupos reservados";
                                    ?>
                                    <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="476" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:1;grid-row:1;position:relative;">
                                        <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                        <span>476</span>
                                        <span class="tooltip-cupos"><?php echo $info; ?></span>
                                    </button>
                                  
                                    <!-- Columna 2 -->
                                    <?php
                                        $id = 475;
                                        $ocupados = $moto_ocupados[$id] ?? 0;
                                        $max = 7;
                                        $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                        $info = "$ocupados/$max cupos reservados";
                                    ?>
                                    <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="475" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:2;grid-row:1;position:relative;">
                                        <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                        <span>475</span>
                                        <span class="tooltip-cupos"><?php echo $info; ?></span>
                                    </button>
                
                                    <!-- Columna 3 -->
                                    <?php
                                        $id = 474;
                                        $ocupados = $moto_ocupados[$id] ?? 0;
                                        $max = 7;
                                        $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                        $info = "$ocupados/$max cupos reservados";
                                    ?>
                                    <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="474" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:3;grid-row:1;position:relative;">
                                        <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                        <span>474</span>
                                        <span class="tooltip-cupos"><?php echo $info; ?></span>
                                    </button>
                                     <?php
                                        $id = 441;
                                        $ocupados = $moto_ocupados[$id] ?? 0;
                                        $max = 5;
                                        $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                        $info = "$ocupados/$max cupos reservados";
                                    ?>
                                    <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="441" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:3;grid-row:2;position:relative;">
                                        <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                        <span>441</span>
                                        <span class="tooltip-cupos"><?php echo $info; ?></span>
                                    </button>   
                                </div>
                            </div>
                            <div id="ayuda-espacio-moto" style="font-size: 13px; color: #888; margin-top: 3px; text-align:center;">Selecciona un espacio disponible (verde). Pasa el mouse para ver los cupos ocupados.</div>
                        </div>
                        
                        <div id="mapa-moto-grande" style="display:none;">
                            <div class="mapa-titulo" style="justify-content: flex-start;">
                                <span style="color:#2563eb;font-size:2.2rem;">&#128205;</span>
                                Mapa de Espacios de Parqueadero (Motos Grandes)-Disponibilidad para la fecha seleccionada
                            </div>
                            <div class="mapa-leyenda" style="justify-content: flex-start;">
                                <span><span style="display:inline-block;width:18px;height:18px;background:#22c55e;border-radius:4px;"></span> Disponible</span>
                                <span><span style="display:inline-block;width:18px;height:18px;background:#f6e6ea;border-radius:4px;"></span> Ocupado</span>
                                <span><span style="display:inline-block;width:18px;height:18px;background:#2563eb;border-radius:4px;"></span> Seleccionado</span>
                            </div>
                            <div class="mapa-espacios-bg">
                                <div id="mapa-espacios-moto-grande" style="display: flex; justify-content: center; gap: 10px;">
                                    <?php for ($i = 270; $i <= 271; $i++): ?>
                                        <?php
                                            $estado = 'disponible';
                                            if (isset($moto_grande_ocupados_map[$i])) {
                                                if ($moto_grande_ocupados_map[$i] == $_SESSION['usuario_id']) {
                                                    $estado = 'seleccionado';
                                                } else {
                                                    $estado = 'ocupado';
                                                }
                                            }
                                        ?>
                                        <button type="button" class="espacio-btn moto-grande-btn <?php echo $estado; ?>" data-espacio="<?php echo $i; ?>" data-tipo="moto_grande" <?php echo ($estado=='ocupado'?'disabled':''); ?>
                                        <?php if($estado=='seleccionado'): ?> style="background:#2563eb;color:#fff;border:2px solid #2563eb;box-shadow:0 0 0 4px #2563eb33;"<?php endif; ?>
                                        >
                                            <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                            <span><?php echo $i; ?></span>
                                        </button>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div id="ayuda-espacio-moto-grande" style="font-size: 13px; color: #888; margin-top: 3px; text-align:center;">Selecciona un espacio disponible (verde) para motos grandes.</div>
                        </div>
                    </div>

                    <div id="formulario-reserva" class="card form-reserva-oculto">
                        <h2 style="margin-bottom: 18px;"><i class="fas fa-lock"></i> Reservar Espacio <span id="espacio-seleccionado-titulo"></span></h2>
                        
                        <!-- Validación del lado del cliente para mostrar advertencia -->
                        <div id="advertencia-reserva-existente" style="display: none; background: #fff3cd; border: 1px solid #ffc107; padding: 10px; border-radius: 6px; margin-bottom: 15px; color: #856404;">
                            <i class="fas fa-exclamation-triangle" style="margin-right: 5px;"></i>
                            <strong>Atención:</strong> Solo puedes tener una reserva activa por día. Si ya tienes una reserva para la fecha seleccionada, esta acción será rechazada.
                        </div>
                        
                        <form method="POST" action="" id="form-reserva">
                            <input type="hidden" name="action" value="reservar">
                            <input type="hidden" name="numero_espacio" id="numero_espacio" required>
                            <input type="hidden" name="tipo_vehiculo" id="tipo_vehiculo" value="carro">
                            <!-- Campo de fecha oculto que se sincroniza con el datepicker principal -->
                            <input type="hidden" name="fecha_reserva" id="fecha_reserva" required>
                            
                            <?php if ($_SESSION['usuario_rol'] == 'admin'): ?>
                                <div class="form-group">
                                    <label for="usuario_id">Usuario:</label>
                                    <select name="usuario_id" id="usuario_id" required>
                                        <option value="">Seleccione un usuario</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <option value="<?php echo $usuario['id']; ?>"><?php echo htmlspecialchars($usuario['nombre']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="placa_vehiculo">Placa del Vehículo:</label>
                                <input type="text" name="placa_vehiculo" id="placa_vehiculo" placeholder="ABC123" maxlength="10" required style="text-transform: uppercase;">
                                <small style="color: #666; font-size: 12px; display: block; margin-top: 4px;">
                                    Ingresa la placa sin espacios ni guiones
                                </small>
                            </div>
                            
                            <?php if (!isset($_SESSION['usuario_nombre']) || !$_SESSION['usuario_nombre']): ?>
                                <button type="button" class="text-white bg-blue-400 dark:bg-blue-500 cursor-not-allowed font-medium rounded-lg text-sm px-5 py-2.5 text-center" disabled>
                                    <i class="fas fa-sign-in-alt" style="margin-right: 5px;"></i>
                                    Debe iniciar sesión para reservar
                                </button>
                            <?php else: ?>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" id="btn-reservar">
                                    <i class="fas fa-calendar-plus" style="margin-right: 5px;"></i>
                                    Reservar Cupo
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
              
                <div>         
                <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><i class="far fa-calendar-alt"></i> Reservas para hoy</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400"> 
                        <?php if (empty($reservas_hoy)): ?>
                                            <p class="font-normal text-gray-700 dark:text-white">
                                                <?php echo ($_SESSION['usuario_rol'] == 'admin') ? 'No hay reservas para hoy.' : 'No tienes reservas para hoy.'; ?>
                                            </p>
                                        <?php else: ?>
                                            <?php foreach ($reservas_hoy as $reserva): ?>
                                                <div class="reserva-item">
                                                    <h4><?php echo htmlspecialchars($reserva['nombre']); ?></h4>
                                                    <p><strong>Espacio: </strong><?php echo htmlspecialchars($reserva['numero_espacio']); ?></p>
                                                    <p><strong>Placa:</strong> <?php echo htmlspecialchars($reserva['placa_vehiculo']); ?></p>
                                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($reserva['email']); ?></p>
                                                    <p><strong>Tipo:</strong> <?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($reserva['tipo_vehiculo']))); ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                    </p>
                </a>
                <br>
                <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><i class="far fa-calendar-alt"></i> Reservas para Mañana</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">  
                        <?php if (empty($reservas_manana)): ?>
                             <p class="font-normal text-gray-700 dark:text-white">
                                <?php echo ($_SESSION['usuario_rol'] == 'admin') ? 'No hay reservas para mañana.' : 'No tienes reservas para mañana.'; ?>
                            </p>
                        <?php else: ?>
                            <?php foreach ($reservas_manana as $reserva): ?>
                                <div class="reserva-item">
                                    <h4><?php echo htmlspecialchars($reserva['nombre']); ?></h4>
                                    <p><strong>Espacio: </strong><?php echo htmlspecialchars($reserva['numero_espacio']); ?></p>
                                    <p><strong>Placa:</strong> <?php echo htmlspecialchars($reserva['placa_vehiculo']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($reserva['email']); ?></p>
                                    <p><strong>Tipo:</strong> <?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($reserva['tipo_vehiculo']))); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </p>
                </a>

                </div>
            </div>
        </div>
    </div>
</div>
    
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const datepicker = document.getElementById('default-datepicker');
        const seccionMapas = document.getElementById('seccion-mapas-reserva');
        const fechaMostrar = document.getElementById('fecha-mostrar');
        const fechaReserva = document.getElementById('fecha_reserva');
        const mensajeSeleccionar = document.getElementById('mensaje-seleccionar-fecha');
        
        // Función para mostrar los mapas cuando se selecciona una fecha
        function mostrarMapasConFecha(fechaSeleccionada) {
            if (fechaSeleccionada) {
                // Formatear la fecha para mostrar
                const fecha = new Date(fechaSeleccionada + 'T00:00:00');
                const opciones = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                };
                const fechaFormateada = fecha.toLocaleDateString('es-ES', opciones);
                
                // Actualizar elementos
                fechaMostrar.textContent = fechaFormateada;
                fechaReserva.value = fechaSeleccionada;
                
                // Mostrar sección de mapas con animación
                seccionMapas.classList.remove('seccion-oculta');
                seccionMapas.classList.add('seccion-visible');
                
                // Ocultar mensaje de seleccionar fecha
                mensajeSeleccionar.style.display = 'none';
                
                // Scroll suave a la sección de mapas
                setTimeout(() => {
                    seccionMapas.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }, 300);
            }
        }
        
        // Escuchar cambios en el datepicker
        datepicker.addEventListener('changeDate', function(e) {
            const fechaSeleccionada = e.target.value;
            mostrarMapasConFecha(fechaSeleccionada);
        });
        
        // También escuchar el evento input para mayor compatibilidad
        datepicker.addEventListener('input', function(e) {
            const fechaSeleccionada = e.target.value;
            if (fechaSeleccionada && fechaSeleccionada.length === 10) { // formato YYYY-MM-DD
                mostrarMapasConFecha(fechaSeleccionada);
            }
        });
        
        // Si ya hay una fecha seleccionada al cargar la página, mostrar los mapas
        if (datepicker.value) {
            mostrarMapasConFecha(datepicker.value);
        }
    });

    // Convertir placa a mayúsculas automáticamente
    document.getElementById('placa_vehiculo').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    function validarReservaExistente() {
        const fecha = document.getElementById('fecha_reserva').value;
        const advertencia = document.getElementById('advertencia-reserva-existente');
        
        // Mostrar advertencia para usuarios normales (no admin)
        <?php if ($_SESSION['usuario_rol'] != 'admin'): ?>
        if (fecha) {
            // Verificar si ya existe una reserva para esta fecha
            fetch('check-reservation.php?fecha=' + fecha + '&usuario_id=<?php echo $_SESSION['usuario_id']; ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        advertencia.style.display = 'block';
                        document.getElementById('btn-reservar').disabled = true;
                        document.getElementById('btn-reservar').textContent = 'Ya tienes una reserva para esta fecha';
                        document.getElementById('btn-reservar').classList.add('bg-gray-400', 'cursor-not-allowed');
                        document.getElementById('btn-reservar').classList.remove('bg-blue-700', 'hover:bg-blue-800');
                    } else {
                        advertencia.style.display = 'none';
                        document.getElementById('btn-reservar').disabled = false;
                        document.getElementById('btn-reservar').innerHTML = '<i class="fas fa-calendar-plus" style="margin-right: 5px;"></i>Reservar Cupo';
                        document.getElementById('btn-reservar').classList.remove('bg-gray-400', 'cursor-not-allowed');
                        document.getElementById('btn-reservar').classList.add('bg-blue-700', 'hover:bg-blue-800');
                    }
                })
                .catch(error => {
                    console.error('Error verificando reserva:', error);
                    advertencia.style.display = 'none';
                });
        }
        <?php endif; ?>
    }

    // Selector de tipo de vehículo y mapas
    const tipoVehiculoRadios = document.getElementsByName('tipo_vehiculo_selector');
    const mapaCarro = document.getElementById('mapa-carro');
    const mapaMoto = document.getElementById('mapa-moto');
    const mapaMotoGrande = document.getElementById('mapa-moto-grande');
    const formTipoVehiculo = document.getElementById('tipo_vehiculo');

    // Mostrar el mapa correcto según selección
    Array.from(tipoVehiculoRadios).forEach(radio => {
        radio.addEventListener('change', function() {
            // Ocultar todos los mapas
            mapaCarro.style.display = 'none';
            mapaMoto.style.display = 'none';
            mapaMotoGrande.style.display = 'none';
            
            if (this.value === 'carro') {
                mapaCarro.style.display = 'block';
                if (formTipoVehiculo) formTipoVehiculo.value = 'carro';
            } else if (this.value === 'moto') {
                mapaMoto.style.display = 'block';
                if (formTipoVehiculo) formTipoVehiculo.value = 'moto';
            } else if (this.value === 'moto_grande') {
                mapaMotoGrande.style.display = 'block';
                if (formTipoVehiculo) formTipoVehiculo.value = 'moto_grande';
            }
            
            // Limpiar selección previa
            document.getElementById('numero_espacio').value = '';
            document.getElementById('formulario-reserva').classList.add('form-reserva-oculto');
        });
    });

    // Selección dinámica de espacio y mostrar formulario
    function activarSeleccionEspacios(selector, tipo) {
        const botones = document.querySelectorAll(selector);
        const inputEspacio = document.getElementById('numero_espacio');
        const formReserva = document.getElementById('formulario-reserva');
        const tituloEspacio = document.getElementById('espacio-seleccionado-titulo');
        const tipoVehiculoInput = document.getElementById('tipo_vehiculo');
        
        botones.forEach(btn => {
            btn.addEventListener('click', function() {
                if (btn.hasAttribute('disabled')) return;
                
                // Quitar seleccionado de todos los botones
                document.querySelectorAll('.moto-btn, .moto-grande-btn, .espacio-btn:not(.moto-btn):not(.moto-grande-btn)').forEach(b => b.classList.remove('seleccionado'));
                
                btn.classList.add('seleccionado');
                inputEspacio.value = btn.getAttribute('data-espacio');
                tituloEspacio.textContent = btn.getAttribute('data-espacio');
                if (tipoVehiculoInput) tipoVehiculoInput.value = tipo;
                formReserva.classList.remove('form-reserva-oculto');
                formReserva.classList.add('form-reserva-visible');
                
                // Scroll suave al formulario
                window.scrollTo({ top: formReserva.offsetTop - 40, behavior: 'smooth' });
                
                // Mostrar info de cupos en móviles para motos
                if (tipo === 'moto') {
                    mostrarInfoCuposMovil(btn.getAttribute('data-cupos'));
                }
                
                // Validar reserva existente cuando se selecciona un espacio
                validarReservaExistente();
            });
        });
    }
    
    // Activar selección para cada tipo de vehículo
    activarSeleccionEspacios('#mapa-espacios-carro .espacio-btn.disponible, #mapa-espacios-carro .espacio-btn.seleccionado', 'carro');
    activarSeleccionEspacios('.moto-btn.disponible, .moto-btn.seleccionado', 'moto');
    activarSeleccionEspacios('.moto-grande-btn.disponible, .moto-grande-btn.seleccionado', 'moto_grande');

    // Función para mostrar info de cupos en móviles (solo para motos normales)
    function mostrarInfoCuposMovil(info) {
        if (window.innerWidth <= 768 && info) {
            alert('Información del espacio: ' + info);
        }
    }

    // Manejo del formulario con validación adicional
    const formReserva = document.getElementById('form-reserva');
    if (formReserva) {
        formReserva.addEventListener('submit', function(e) {
            const fecha = document.getElementById('fecha_reserva').value;
            const espacio = document.getElementById('numero_espacio').value;
            const placa = document.getElementById('placa_vehiculo').value;
            
            // Validaciones básicas
            if (!fecha || !espacio || !placa) {
                e.preventDefault();
                alert('Por favor completa todos los campos requeridos.');
                return;
            }
            
            // Validar formato de placa (básico)
            if (placa.length < 5 || placa.length > 10) {
                e.preventDefault();
                alert('La placa debe tener entre 5 y 10 caracteres.');
                return;
            }
            
            // Confirmar reserva
            const confirmacion = confirm(`¿Confirmas que deseas reservar el espacio ${espacio} para el ${fecha} con la placa ${placa}?`);
            if (!confirmacion) {
                e.preventDefault();
                return;
            }
            
            // Mostrar indicador de carga
            const btnReservar = document.getElementById('btn-reservar');
            if (btnReservar) {
                btnReservar.disabled = true;
                btnReservar.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 5px;"></i>Procesando...';
            }
        });
    }

    // Auto-ocultar mensajes después de unos segundos
    const mensajes = document.querySelectorAll('.mensaje');
    mensajes.forEach(mensaje => {
        setTimeout(() => {
            mensaje.style.opacity = '0';
            mensaje.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                mensaje.style.display = 'none';
            }, 500);
        }, 5000); // 5 segundos
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="js/dropdown.js"></script>
<?php require __DIR__ . '/../resources/partials/new.footer.php'; ?>
