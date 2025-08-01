<?php require resource_path('partials/header.php'); ?>

<style>
/* Estilos específicos para admin */
.stat-card {
    background: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    text-align: center;
    transition: all 0.2s;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.stat-card h3 {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.stat-card p {
    color: #6b7280;
    font-size: 0.875rem;
}

.stat-card.blue {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.stat-card.green {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.stat-card.orange {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.stat-card.red {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    padding: 0.75rem 1rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.table th {
    background-color: #f9fafb;
    font-weight: 600;
    color: #4b5563;
}

.table tr:hover {
    background-color: #f9fafb;
}

.mensaje {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.5rem;
    text-align: center;
    font-weight: 600;
}

.mensaje.success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.mensaje.error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.card {
    background: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
}

.form-group input, .form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.375rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-warning {
    background-color: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background-color: #d97706;
}

.btn-danger {
    background-color: #ef4444;
    color: white;
}

.btn-danger:hover {
    background-color: #dc2626;
}

.grid {
    display: grid;
    gap: 1.5rem;
}

.grid-cols-1 {
    grid-template-columns: 1fr;
}

.grid-cols-2 {
    grid-template-columns: repeat(2, 1fr);
}

@media (max-width: 768px) {
    .grid-cols-2 {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="border-b border-gray-200 pb-8 mb-8">
    <div class="flex items-center justify-center mb-4">
        <img src="assets/images/3shape-intraoral-logo.png" alt="3Shape Logo" width="50" height="50" class="mr-4">
        <h1 class="text-4xl font-bold text-gray-900">Panel de Administración</h1>
    </div>
    <?php if (isset($_SESSION['usuario_nombre']) && $_SESSION['usuario_nombre']): ?>
    <p class="text-center text-gray-600">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
    <?php endif; ?>
</div>

<div class="container mx-auto px-4">
    <div class="nav text-center mb-8">
        <a href="/" class="inline-block px-4 py-2 mx-2 text-blue-600 border border-blue-600 rounded hover:bg-blue-600 hover:text-white transition">🏠 Inicio</a>
        <a href="/mis-reservas" class="inline-block px-4 py-2 mx-2 text-blue-600 border border-blue-600 rounded hover:bg-blue-600 hover:text-white transition">📋 Reservas</a>
        <a href="/admin/usuarios" class="inline-block px-4 py-2 mx-2 text-blue-600 border border-blue-600 rounded hover:bg-blue-600 hover:text-white transition">👥 Usuarios</a>
    </div>
    
    <?php if (isset($mensaje)): ?>
        <div class="mensaje <?php echo $tipo_mensaje; ?>">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>
    
    <!-- Estadísticas principales -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card blue">
            <h3><?php echo $stats['total_usuarios']; ?></h3>
            <p>Usuarios Registrados</p>
        </div>
        <div class="stat-card green">
            <h3><?php echo $cupos_manana; ?></h3>
            <p>Cupos Disponibles Mañana</p>
        </div>
        <div class="stat-card orange">
            <h3><?php echo $stats['reservas_activas']; ?></h3>
            <p>Reservas Activas</p>
        </div>
        <div class="stat-card red">
            <h3><?php echo $stats['total_reservas']; ?></h3>
            <p>Total Reservas</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Configuración -->
        <div class="card">
            <h2 class="text-xl font-bold mb-4">🔧 Configuración del Sistema</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="actualizar_config">
                
                <div class="form-group">
                    <label for="total_cupos">Total de Cupos de Parqueadero:</label>
                    <input type="number" name="total_cupos" id="total_cupos" value="<?php echo $config['total_cupos']; ?>" min="1" max="100" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Actualizar Configuración</button>
            </form>
            
            <hr class="my-6">
            
            <h3 class="text-lg font-semibold mb-4">🧹 Mantenimiento</h3>
            <p class="text-gray-600 mb-4">Marca como completadas las reservas de días anteriores:</p>
            <form method="POST" action="">
                <input type="hidden" name="action" value="limpiar_reservas">
                <button type="submit" class="btn btn-warning" onclick="return confirm('¿Estás seguro de que quieres marcar como completadas todas las reservas de días anteriores?')">
                    Limpiar Reservas Antiguas
                </button>
            </form>
        </div>
        
        <!-- Usuarios activos -->
        <div class="card">
            <h2 class="text-xl font-bold mb-4">👑 Usuarios Más Activos</h2>
            <?php if (empty($usuarios_activos)): ?>
                <p class="text-gray-500">No hay usuarios con reservas.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Total Reservas</th>
                            <th>Activas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios_activos as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td><?php echo $usuario['total_reservas']; ?></td>
                                <td><?php echo $usuario['reservas_activas']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Estadísticas detalladas -->
        <div class="card">
            <h2 class="text-xl font-bold mb-4">📊 Estadísticas Detalladas</h2>
            
            <div class="mb-6">
                <h4 class="font-semibold mb-2">Resumen de Reservas</h4>
                <ul class="space-y-2">
                    <li class="flex justify-between py-2 border-b border-gray-200">
                        <span>Total de Reservas:</span>
                        <span class="font-semibold"><?php echo $stats['total_reservas']; ?></span>
                    </li>
                    <li class="flex justify-between py-2 border-b border-gray-200">
                        <span>Reservas Activas:</span>
                        <span class="font-semibold text-green-600"><?php echo $stats['reservas_activas']; ?></span>
                    </li>
                    <li class="flex justify-between py-2 border-b border-gray-200">
                        <span>Reservas Completadas:</span>
                        <span class="font-semibold text-blue-600"><?php echo $stats['reservas_completadas']; ?></span>
                    </li>
                    <li class="flex justify-between py-2">
                        <span>Reservas Canceladas:</span>
                        <span class="font-semibold text-red-600"><?php echo $stats['reservas_canceladas']; ?></span>
                    </li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold mb-2">Configuración Actual</h4>
                <ul class="space-y-2">
                    <li class="flex justify-between py-2 border-b border-gray-200">
                        <span>Total de Cupos:</span>
                        <span class="font-semibold"><?php echo $config['total_cupos']; ?></span>
                    </li>
                    <li class="flex justify-between py-2 border-b border-gray-200">
                        <span>Cupos Ocupados Mañana:</span>
                        <span class="font-semibold"><?php echo ($config['total_cupos'] - $cupos_manana); ?></span>
                    </li>
                    <li class="flex justify-between py-2">
                        <span>Última Actualización:</span>
                        <span class="font-semibold"><?php echo date('d/m/Y H:i', strtotime($config['fecha_actualizacion'])); ?></span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Reservas recientes -->
        <div class="card">
            <h2 class="text-xl font-bold mb-4">📅 Reservas Recientes (Últimos 7 días)</h2>
            <?php if (empty($reservas_recientes)): ?>
                <p class="text-gray-500">No hay reservas en los últimos 7 días.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Activas</th>
                            <th>Completadas</th>
                            <th>Canceladas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas_recientes as $reserva): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($reserva['fecha'])); ?></td>
                                <td><?php echo $reserva['total_reservas']; ?></td>
                                <td class="text-green-600"><?php echo $reserva['activas']; ?></td>
                                <td class="text-blue-600"><?php echo $reserva['completadas']; ?></td>
                                <td class="text-red-600"><?php echo $reserva['canceladas']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require resource_path('partials/new.footer.php'); ?>