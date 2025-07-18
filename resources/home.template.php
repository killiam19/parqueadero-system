
    <?php require __DIR__ . '/partials/header.php'; ?>
 
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
                        <label style="margin-right: 12px;"><input type="radio" name="tipo_vehiculo_selector" value="carro" checked> Carro</label>
                        <label><input type="radio" name="tipo_vehiculo_selector" value="moto"> Moto</label>
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