
<?php require __DIR__ . '/partials/header.php'; ?>
<style>
.radio-inputs {
  display: flex;
  justify-content: center;
  align-items: center;
  max-width: 350px;
  -webkit-user-select: none;
  -moz-user-select: none;
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
</style>
 
  <div class="border-b border-gray-200 pb-8 mb-8">
 <h1>🚗 Sistema de Agendamiento de Parqueadero de 3Shape <img src="assets/images/3shape-intraoral-logo.png" alt="" width="50" height="50"></h1>
                <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-16">

    <div class="container">
        
        <div class="nav">
            <a href="mis_reservas.php">📋 Mis Reservas</a>
            <?php if ($_SESSION['usuario_rol'] == 'admin'): ?>
                <a href="usuarios.php">👥 Usuarios</a>
                <a href="admin.php">⚙️ Administración</a>
            <?php endif; ?>
        </div>
        
        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
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
                    <div id="mapa-carro" style="display:block;">
                        <div class="mapa-titulo" style="justify-content: flex-start;">
                            <span style="color:#2563eb;font-size:2.2rem;">&#128205;</span>
                            Mapa de Espacios de Parqueadero (Carros)
                        </div>
                        <div class="mapa-leyenda" style="justify-content: flex-start;">
                            <span><span style="display:inline-block;width:18px;height:18px;background:#22c55e;border-radius:4px;"></span> Disponible</span>
                            <span><span style="display:inline-block;width:18px;height:18px;background:#b0b0b0;border-radius:4px;"></span> Ocupado</span>
                            <span><span style="display:inline-block;width:18px;height:18px;background:#f6e6ea;border-radius:4px;"></span> Seleccionado</span>
                        </div>
                        <div class="mapa-espacios-bg">
                            <div id="mapa-espacios-carro">
                                <?php for ($i = 281; $i >= 270; $i--): ?>
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
                            Mapa de Espacios de Parqueadero (Motos)
                        </div>
                        <div class="mapa-leyenda" style="justify-content: flex-start;">
                            <span><span style="display:inline-block;width:18px;height:18px;background:#22c55e;border-radius:4px;"></span> Disponible</span>
                            <span><span style="display:inline-block;width:18px;height:18px;background:#b0b0b0;border-radius:4px;"></span> Ocupado</span>
                            <span><span style="display:inline-block;width:18px;height:18px;background:#f6e6ea;border-radius:4px;"></span> Seleccionado</span>
                        </div>
                        <div class="mapa-espacios-bg" style="display: flex; flex-direction: column; align-items: center;">
                            <div id="mapa-motos-grid">
                                <!-- Columna 1 -->
                                <?php
                                    $id = 476;
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 6;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="476" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:1;grid-row:1;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>476</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <?php
                                    $id = '476a';
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 1;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="476a" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:1;grid-row:2;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>1</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <!-- Columna 2 -->
                                <?php
                                    $id = 475;
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 6;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="475" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:2;grid-row:1;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>475</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <?php
                                    $id = '475a';
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 1;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="475a" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:2;grid-row:2;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>1</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <!-- Columna 3 -->
                                <?php
                                    $id = 474;
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 6;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="474" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:3;grid-row:1;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>474</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <?php
                                    $id = '474a';
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 1;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="474a" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:3;grid-row:2;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>1</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <?php
                                    $id = '474b';
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 1;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="474b" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:3;grid-row:3;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>1</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <?php
                                    $id = 441;
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 4;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="441" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:3;grid-row:4;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>441</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                            </div>
                        </div>
                        <div id="ayuda-espacio-moto" style="font-size: 13px; color: #888; margin-top: 3px; text-align:center;">Selecciona un espacio disponible (verde). Pasa el mouse para ver los cupos ocupados.</div>
                    </div>
                </div>

                <div id="formulario-reserva" class="card form-reserva-oculto">
                    <h2 style="margin-bottom: 18px;"><i class="fas fa-lock"></i> Reservar Espacio <span id="espacio-seleccionado-titulo"></span></h2>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="reservar">
                        <input type="hidden" name="numero_espacio" id="numero_espacio" required>
                        <input type="hidden" name="tipo_vehiculo" id="tipo_vehiculo" value="carro">
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
                            <label for="fecha_reserva">Fecha de Reserva:</label>
                            <input type="date" name="fecha_reserva" id="fecha_reserva" min="<?php echo $hoy; ?>" max="<?php echo $manana; ?>" value="<?php echo $hoy; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="hora_inicio">Hora de Inicio:</label>
                            <input type="time" name="hora_inicio" id="hora_inicio" required>
                        </div>
                        <div class="form-group">
                            <label for="hora_fin">Hora de Fin:</label>
                            <input type="time" name="hora_fin" id="hora_fin" required>
                        </div>
                        <div class="form-group">
                            <label for="placa_vehiculo">Placa del Vehículo:</label>
                            <input type="text" name="placa_vehiculo" id="placa_vehiculo" placeholder="ABC123" maxlength="10" required>
                        </div>
                        <button type="submit">Reservar Cupo</button>
                    </form>
                </div>
            </div>
            
            <div>
                <div class="card">
                    <h2><i class="far fa-calendar-alt"></i> Reservas para Mañana</h2>
                    
                    <?php if (empty($reservas_manana)): ?>
                        <p><?php echo ($_SESSION['usuario_rol'] == 'admin') ? 'No hay reservas para mañana.' : 'No tienes reservas para mañana.'; ?></p>
                    <?php else: ?>
                        <?php foreach ($reservas_manana as $reserva): ?>
                            <div class="reserva-item">
                                <h4><?php echo htmlspecialchars($reserva['nombre']); ?></h4>
                                <p><strong>Horario:</strong> <?php echo date('H:i', strtotime($reserva['hora_inicio'])); ?> - <?php echo date('H:i', strtotime($reserva['hora_fin'])); ?></p>
                                <p><strong>Placa:</strong> <?php echo htmlspecialchars($reserva['placa_vehiculo']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($reserva['email']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Actualizar cupos disponibles cuando cambie la fecha
        document.getElementById('fecha_reserva').addEventListener('change', function() {
            const fecha = this.value;
            if (fecha) {
                fetch('get-cupos.php?fecha=' + fecha)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('.cupos-info').innerHTML = 
                            '<strong>Cupos disponibles para ' + fecha + ': ' + data.disponibles + '/10</strong>';
                    });
            }
        });
        // Habilitar el input de fecha para que el usuario pueda elegir entre hoy y mañana
        document.getElementById('fecha_reserva').readOnly = false;

        // Selector de tipo de vehículo y mapas
        const tipoVehiculoRadios = document.getElementsByName('tipo_vehiculo_selector');
        const mapaCarro = document.getElementById('mapa-carro');
        const mapaMoto = document.getElementById('mapa-moto');
        const formTipoVehiculo = document.getElementById('tipo_vehiculo');

        // Mostrar el mapa correcto según selección
        Array.from(tipoVehiculoRadios).forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'carro') {
                    mapaCarro.style.display = 'block';
                    mapaMoto.style.display = 'none';
                    if (formTipoVehiculo) formTipoVehiculo.value = 'carro';
                } else {
                    mapaCarro.style.display = 'none';
                    mapaMoto.style.display = 'block';
                    if (formTipoVehiculo) formTipoVehiculo.value = 'moto';
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
                    // Quitar seleccionado de todos los botones de motos
                    document.querySelectorAll('.moto-btn').forEach(b => b.classList.remove('seleccionado'));
                    // Quitar seleccionado de todos los botones de carros
                    document.querySelectorAll('.espacio-btn:not(.moto-btn)').forEach(b => b.classList.remove('seleccionado'));
                    btn.classList.add('seleccionado');
                    inputEspacio.value = btn.getAttribute('data-espacio');
                    tituloEspacio.textContent = btn.getAttribute('data-espacio');
                    if (tipoVehiculoInput) tipoVehiculoInput.value = tipo;
                    formReserva.classList.remove('form-reserva-oculto');
                    formReserva.classList.add('form-reserva-visible');
                    window.scrollTo({ top: formReserva.offsetTop - 40, behavior: 'smooth' });
                    // Mostrar info de cupos en móviles
                    mostrarInfoCuposMovil(btn.getAttribute('data-cupos'));
                });
            });
        }
        activarSeleccionEspacios('#mapa-espacios-carro .espacio-btn.disponible, #mapa-espacios-carro .espacio-btn.seleccionado', 'carro');
        activarSeleccionEspacios('.moto-btn.disponible, .moto-btn.seleccionado', 'moto');

        // Refrescar el mapa de motos tras una reserva exitosa
        const formReserva = document.querySelector('#formulario-reserva form');
        if (formReserva) {
            formReserva.addEventListener('submit', function(e) {
                setTimeout(() => {
                    location.reload();
                }, 500);
            });
        }
    </script>
</body>

</div>

 <?php require __DIR__ . '/../resources/partials/new.footer.php'; ?>