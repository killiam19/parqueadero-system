<?php

namespace App\Controllers;

use Framework\Database;

class AdminController
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function index()
    {
        // Verificar que el usuario sea admin
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        $mensaje = null;
        $tipo_mensaje = null;

        // Procesar actualización de configuración
        if (isset($_POST['action']) && $_POST['action'] == 'actualizar_config') {
            $total_cupos = $_POST['total_cupos'];
            
            if ($total_cupos < 1 || $total_cupos > 100) {
                $mensaje = 'El número de cupos debe estar entre 1 y 100';
                $tipo_mensaje = 'error';
            } else {
                try {
                    $this->db->query("UPDATE configuracion SET total_cupos = ? WHERE id = 1", [$total_cupos]);
                    $mensaje = 'Configuración actualizada exitosamente';
                    $tipo_mensaje = 'success';
                } catch (Exception $e) {
                    $mensaje = 'Error al actualizar configuración: ' . $e->getMessage();
                    $tipo_mensaje = 'error';
                }
            }
        }

        // Procesar limpieza de reservas antiguas
        if (isset($_POST['action']) && $_POST['action'] == 'limpiar_reservas') {
            try {
                $this->db->query("UPDATE reservas SET estado = 'completada' WHERE fecha_reserva < ? AND estado = 'activa'", [date('Y-m-d')]);
                $mensaje = "Se marcaron como completadas las reservas antiguas";
                $tipo_mensaje = 'success';
            } catch (Exception $e) {
                $mensaje = 'Error al limpiar reservas: ' . $e->getMessage();
                $tipo_mensaje = 'error';
            }
        }

        // Obtener estadísticas
        $config = $this->getConfiguracion();
        $stats = $this->getEstadisticas();
        $reservas_recientes = $this->getReservasRecientes();
        $usuarios_activos = $this->getUsuariosActivos();
        $cupos_manana = $this->getCuposDisponibles(date('Y-m-d', strtotime('+1 day')));

        view('admin', [
            'title' => 'Panel de Administración',
            'config' => $config,
            'stats' => $stats,
            'reservas_recientes' => $reservas_recientes,
            'usuarios_activos' => $usuarios_activos,
            'cupos_manana' => $cupos_manana,
            'mensaje' => $mensaje,
            'tipo_mensaje' => $tipo_mensaje
        ]);
    }

    public function usuarios()
    {
        // Verificar que el usuario sea admin
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        $mensaje = null;
        $tipo_mensaje = null;

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
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $this->db->query("INSERT INTO usuarios (nombre, email, telefono, password) VALUES (?, ?, ?, ?)", [$nombre, $email, $telefono, $hashed_password]);
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
                $this->db->query("DELETE FROM usuarios WHERE id = ?", [$usuario_id]);
                $mensaje = 'Usuario eliminado exitosamente';
                $tipo_mensaje = 'success';
            } catch (Exception $e) {
                $mensaje = 'Error al eliminar usuario: ' . $e->getMessage();
                $tipo_mensaje = 'error';
            }
        }

        // Procesar bloqueo de usuario
        if (isset($_POST['action']) && $_POST['action'] == 'bloquear') {
            $usuario_id = $_POST['usuario_id'];
            try {
                $this->db->query("UPDATE usuarios SET bloqueado = 1 WHERE id = ?", [$usuario_id]);
                // Opcional: cancelar reservas activas futuras del usuario bloqueado
                // $this->db->query("UPDATE reservas SET estado = 'cancelada' WHERE usuario_id = ? AND fecha_reserva >= CURDATE() AND estado = 'activa'", [$usuario_id]);
                $mensaje = 'Usuario bloqueado correctamente';
                $tipo_mensaje = 'success';
            } catch (Exception $e) {
                $mensaje = 'Error al bloquear usuario: ' . $e->getMessage();
                $tipo_mensaje = 'error';
            }
        }

        // Procesar desbloqueo de usuario
        if (isset($_POST['action']) && $_POST['action'] == 'desbloquear') {
            $usuario_id = $_POST['usuario_id'];
            try {
                $this->db->query("UPDATE usuarios SET bloqueado = 0 WHERE id = ?", [$usuario_id]);
                $mensaje = 'Usuario desbloqueado correctamente';
                $tipo_mensaje = 'success';
            } catch (Exception $e) {
                $mensaje = 'Error al desbloquear usuario: ' . $e->getMessage();
                $tipo_mensaje = 'error';
            }
        }

        // Obtener todos los usuarios con estadísticas
        $usuarios = $this->getUsuariosConEstadisticas();

        view('usuarios', [
            'title' => 'Gestión de Usuarios',
            'usuarios' => $usuarios,
            'mensaje' => $mensaje,
            'tipo_mensaje' => $tipo_mensaje
        ]);
    }

    private function getConfiguracion()
    {
        return $this->db->query("SELECT * FROM configuracion ORDER BY id DESC LIMIT 1")->first();
    }

    private function getEstadisticas()
    {
        return $this->db->query("
            SELECT 
                COUNT(DISTINCT u.id) as total_usuarios,
                COUNT(r.id) as total_reservas,
                COUNT(CASE WHEN r.estado = 'activa' THEN 1 END) as reservas_activas,
                COUNT(CASE WHEN r.estado = 'completada' THEN 1 END) as reservas_completadas,
                COUNT(CASE WHEN r.estado = 'cancelada' THEN 1 END) as reservas_canceladas
            FROM usuarios u
            LEFT JOIN reservas r ON u.id = r.usuario_id
        ")->first();
    }

    private function getReservasRecientes()
    {
        return $this->db->query("
            SELECT 
                DATE(fecha_reserva) as fecha,
                COUNT(*) as total_reservas,
                COUNT(CASE WHEN estado = 'activa' THEN 1 END) as activas,
                COUNT(CASE WHEN estado = 'completada' THEN 1 END) as completadas,
                COUNT(CASE WHEN estado = 'cancelada' THEN 1 END) as canceladas
            FROM reservas 
            WHERE fecha_reserva >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(fecha_reserva)
            ORDER BY fecha DESC
        ")->get();
    }

    private function getUsuariosActivos()
    {
        return $this->db->query("
            SELECT 
                u.nombre, 
                u.email,
                COUNT(r.id) as total_reservas,
                COUNT(CASE WHEN r.estado = 'activa' THEN 1 END) as reservas_activas
            FROM usuarios u
            LEFT JOIN reservas r ON u.id = r.usuario_id
            GROUP BY u.id, u.nombre, u.email
            HAVING total_reservas > 0
            ORDER BY total_reservas DESC
            LIMIT 5
        ")->get();
    }

    private function getUsuariosConEstadisticas()
    {
        return $this->db->query("
            SELECT 
                u.*,
                COUNT(r.id) as total_reservas,
                COUNT(CASE WHEN r.estado = 'activa' THEN 1 END) as reservas_activas,
                COUNT(CASE WHEN r.estado = 'completada' THEN 1 END) as reservas_completadas,
                COUNT(CASE WHEN r.estado = 'cancelada' THEN 1 END) as reservas_canceladas
            FROM usuarios u
            LEFT JOIN reservas r ON u.id = r.usuario_id
            GROUP BY u.id, u.nombre, u.email, u.telefono, u.fecha_registro, u.rol, u.bloqueado
            ORDER BY u.nombre
        ")->get();
    }

    private function getCuposDisponibles($fecha)
    {
        // Obtener configuración de cupos totales
        $config = $this->getConfiguracion();
        $total_cupos = $config['total_cupos'];

        // Contar reservas activas para esa fecha
        $resultado = $this->db->query("
            SELECT COUNT(*) as reservas_activas
            FROM reservas 
            WHERE fecha_reserva = ? AND estado = 'activa'
        ", [$fecha])->first();
        
        $reservas_activas = $resultado['reservas_activas'];

        return $total_cupos - $reservas_activas;
    }
}
?>
