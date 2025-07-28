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

/* Estilos específicos para usuarios */
.usuario-item {
    background-color: #fff;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
}

.usuario-item:hover {
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.usuario-info h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
}

.usuario-info p {
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.usuario-stats {
    display: flex;
    gap: 0.75rem;
    margin-top: 0.75rem;
}

.stat-item {
    background-color: #f3f4f6;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.stat-activa {
    background-color: #dcfce7;
    color: #166534;
}

.stat-completada {
    background-color: #e0e7ff;
    color: #3730a3;
}

.stat-cancelada {
    background-color: #fee2e2;
    color: #991b1b;
}

.btn-danger {
    background-color: #ef4444;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: background-color 0.2s;
}

.btn-danger:hover {
    background-color: #dc2626;
}
</style>

<div class="border-b border-gray-200 pb-8 mb-8">
    <img src="assets/images/3shape-intraoral-logo.png" alt="" width="50" height="50">
    <h1>Gestión de Usuarios</h1>
    <?php if (isset($_SESSION['usuario_nombre']) && $_SESSION['usuario_nombre']): ?>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-16">
    <div class="container">
        <div class="nav">
            <a href="index.php">🏠 Inicio</a>
            <a href="mis_reservas.php">📋 Reservas</a>
            <a href="admin.php">⚙️ Administración</a>
        </div>
        
        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="card">
                <h2 class="text-xl font-bold mb-4">➕ Agregar Usuario</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="agregar">
                    
                    <div class="form-group mb-4">
                        <label for="nombre" class="block mb-2">Nombre Completo:</label>
                        <input type="text" name="nombre" id="nombre" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="email" class="block mb-2">Email:</label>
                        <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="telefono" class="block mb-2">Teléfono:</label>
                        <input type="tel" name="telefono" id="telefono" class="w-full px-3 py-2 border rounded">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="password" class="block mb-2">Contraseña:</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded pr-10" required>
                            <span id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer">👁️</span>
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Agregar Usuario</button>
                </form>
            </div>
            
            <div class="card col-span-1 lg:col-span-1">
                <h2 class="text-xl font-bold mb-4">📝 Lista de Usuarios</h2>
                
                <div class="space-y-4">
                    <!-- Ejemplo de usuario -->
                    <div class="usuario-item">
                        <div class="usuario-info">
                            <h4>Juan Pérez</h4>
                            <p><strong>Email:</strong> juan@example.com</p>
                            <p><strong>Teléfono:</strong> 1234567890</p>
                            <p><strong>Registrado:</strong> 15/05/2023</p>
                            
                            <div class="usuario-stats">
                                <span class="stat-item">Total: 5</span>
                                <span class="stat-item stat-activa">Activas: 2</span>
                                <span class="stat-item stat-completada">Completadas: 3</span>
                                <span class="stat-item stat-cancelada">Canceladas: 0</span>
                            </div>
                        </div>
                        
                        <div>
                            <form method="POST" action="" class="inline">
                                <input type="hidden" name="action" value="eliminar">
                                <input type="hidden" name="usuario_id" value="1">
                                <button type="submit" class="btn-danger" onclick="return confirm('¿Estás seguro?')">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Más usuarios... -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Mostrar/ocultar contraseña
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? '👁️' : '🙈';
    });
</script>

<?php require __DIR__ . '/partials/new.footer.php'; ?>