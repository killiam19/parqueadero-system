<?php require __DIR__ . '/partials/header.php'; ?>
<style>
/* Estilos del radio-input (copiados de home) */
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
/* ... (otros estilos de radio-input copiados de home) ... */

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
</style>

<div class="border-b border-gray-200 pb-8 mb-8">
    <img src="assets/images/3shape-intraoral-logo.png" alt="" width="50" height="50">
    <h1>Panel de Administración</h1>
    <?php if (isset($_SESSION['usuario_nombre']) && $_SESSION['usuario_nombre']): ?>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-16">
    <div class="container">
        <div class="nav">
            <a href="index.php">🏠 Inicio</a>
            <a href="mis_reservas.php">📋 Reservas</a>
            <a href="usuarios.php">👥 Usuarios</a>
        </div>
        
        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Estadísticas -->
            <div class="grid grid-cols-2 gap-4 col-span-2">
                <div class="stat-card blue">
                    <h3>42</h3>
                    <p>Usuarios Registrados</p>
                </div>
                <div class="stat-card green">
                    <h3>15</h3>
                    <p>Cupos Disponibles</p>
                </div>
                <div class="stat-card orange">
                    <h3>27</h3>
                    <p>Reservas Activas</p>
                </div>
                <div class="stat-card red">
                    <h3>156</h3>
                    <p>Total Reservas</p>
                </div>
            </div>
            
            <!-- Configuración -->
            <div class="card">
                <h2 class="text-xl font-bold mb-4">🔧 Configuración</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="actualizar_config">
                    
                    <div class="form-group mb-4">
                        <label for="total_cupos" class="block mb-2">Total de Cupos:</label>
                        <input type="number" name="total_cupos" id="total_cupos" class="w-full px-3 py-2 border rounded" value="50" min="1" max="100" required>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Actualizar</button>
                </form>
                
                <hr class="my-4">
                
                <h3 class="text-lg font-semibold mb-2">🧹 Mantenimiento</h3>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="limpiar_reservas">
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition" onclick="return confirm('¿Estás seguro?')">
                        Limpiar Reservas Antiguas
                    </button>
                </form>
            </div>
            
            <!-- Usuarios activos -->
            <div class="card">
                <h2 class="text-xl font-bold mb-4">👑 Usuarios Activos</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Reservas</th>
                            <th>Activas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Juan Pérez</td>
                            <td>12</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>María Gómez</td>
                            <td>8</td>
                            <td>2</td>
                        </tr>
                        <!-- Más filas... -->
                    </tbody>
                </table>
            </div>
            
            <!-- Reservas recientes -->
            <div class="card col-span-2">
                <h2 class="text-xl font-bold mb-4">📅 Reservas Recientes</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Espacio</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>15/05/2023</td>
                            <td>Juan Pérez</td>
                            <td>281</td>
                            <td>Activa</td>
                        </tr>
                        <tr>
                            <td>14/05/2023</td>
                            <td>María Gómez</td>
                            <td>276</td>
                            <td>Completada</td>
                        </tr>
                        <!-- Más filas... -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>