<?php
require_once 'config.php';
session_start();

// Solo permitir acceso a administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Procesar formulario de nuevo usuario
if (isset($_POST['action']) && $_POST['action'] == 'agregar') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $password = $_POST['password'];
    
    if (empty($nombre) || empty($email) || empty($password)) {
        $mensaje = 'El nombre, email y contraseña son obligatorios';
        $tipo_mensaje = 'error';
    } else {
        try {
            $pdo = conectarDB();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, telefono, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $email, $telefono, $hashed_password]);
            $mensaje = 'Usuario agregado exitosamente';
            $tipo_mensaje = 'success';
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $mensaje = 'El email ya está registrado';
                $tipo_mensaje = 'error';
            } else {
                $mensaje = 'Error al agregar usuario: ' . $e->getMessage();
                $tipo_mensaje = 'error';
            }
        }
    }
}

// Procesar eliminación de usuario
if (isset($_POST['action']) && $_POST['action'] == 'eliminar') {
    $usuario_id = $_POST['usuario_id'];
    
    try {
        $pdo = conectarDB();
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$usuario_id]);
        $mensaje = 'Usuario eliminado exitosamente';
        $tipo_mensaje = 'success';
    } catch (Exception $e) {
        $mensaje = 'Error al eliminar usuario: ' . $e->getMessage();
        $tipo_mensaje = 'error';
    }
}

// Obtener todos los usuarios
$pdo = conectarDB();
$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

// Obtener estadísticas de reservas por usuario
$stats = $pdo->query("
    SELECT 
        u.id,
        u.nombre,
        COUNT(r.id) as total_reservas,
        COUNT(CASE WHEN r.estado = 'activa' THEN 1 END) as reservas_activas,
        COUNT(CASE WHEN r.estado = 'completada' THEN 1 END) as reservas_completadas,
        COUNT(CASE WHEN r.estado = 'cancelada' THEN 1 END) as reservas_canceladas
    FROM usuarios u
    LEFT JOIN reservas r ON u.id = r.usuario_id
    GROUP BY u.id, u.nombre
    ORDER BY u.nombre
")->fetchAll(PDO::FETCH_ASSOC);

$stats_indexed = [];
foreach ($stats as $stat) {
    $stats_indexed[$stat['id']] = $stat;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Sistema de Parqueadero</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 10px;
        }
        
        .nav {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .nav a {
            color: #3498db;
            text-decoration: none;
            margin: 0 15px;
            padding: 10px 20px;
            border: 1px solid #3498db;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .nav a:hover {
            background-color: #3498db;
            color: white;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        button {
            background-color: #3498db;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .mensaje {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        
        .mensaje.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .mensaje.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .usuario-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .usuario-info h4 {
            margin-bottom: 8px;
            color: #2c3e50;
        }
        
        .usuario-info p {
            margin-bottom: 5px;
        }
        
        .usuario-stats {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .stat-item {
            background-color: #e9ecef;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .stat-activa {
            background-color: #d4edda;
            color: #155724;
        }
        
        .stat-completada {
            background-color: #e2e3e5;
            color: #383d41;
        }
        
        .stat-cancelada {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 Gestión de Usuarios</h1>
            <p>Administra los usuarios del sistema de parqueadero</p>
        </div>
        
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
        
        <div class="grid">
            <div>
                <div class="card">
                    <h2>➕ Agregar Usuario</h2>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="agregar">
                        
                        <div class="form-group">
                            <label for="nombre">Nombre Completo:</label>
                            <input type="text" name="nombre" id="nombre" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="tel" name="telefono" id="telefono" placeholder="">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Contraseña:</label>
                            <div style="position:relative;">
                                <input type="password" name="password" id="password" required style="padding-right:40px;">
                                <span id="togglePassword" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; color:#888;">👁️</span>
                            </div>
                        </div>
                        
                        <button type="submit">Agregar Usuario</button>
                    </form>
                </div>
            </div>
            
            <div>
                <div class="card">
                    <h2>📝 Lista de Usuarios</h2>
                    
                    <?php if (empty($usuarios)): ?>
                        <p>No hay usuarios registrados.</p>
                    <?php else: ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <div class="usuario-item">
                                <div class="usuario-info">
                                    <h4><?php echo htmlspecialchars($usuario['nombre']); ?></h4>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
                                    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono'] ?: 'No especificado'); ?></p>
                                    <p><strong>Registrado:</strong> <?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></p>
                                    
                                    <?php if (isset($stats_indexed[$usuario['id']])): ?>
                                        <div class="usuario-stats">
                                            <span class="stat-item">
                                                Total: <?php echo $stats_indexed[$usuario['id']]['total_reservas']; ?>
                                            </span>
                                            <span class="stat-item stat-activa">
                                                Activas: <?php echo $stats_indexed[$usuario['id']]['reservas_activas']; ?>
                                            </span>
                                            <span class="stat-item stat-completada">
                                                Completadas: <?php echo $stats_indexed[$usuario['id']]['reservas_completadas']; ?>
                                            </span>
                                            <span class="stat-item stat-cancelada">
                                                Canceladas: <?php echo $stats_indexed[$usuario['id']]['reservas_canceladas']; ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div>
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="action" value="eliminar">
                                        <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                                        <button type="submit" class="btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario? También se eliminarán todas sus reservas.')">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
    <?php require __DIR__ . '/../resources/partials/footer.php'; ?>
</body>
</html>