<?php

namespace App\Controllers;

use Framework\Database;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            $p_nombre = $_POST['p_nombre'];
            $s_nombre = $_POST['s_nombre'];
            $p_apellido = $_POST['p_apellido'];
            $s_apellido = $_POST['s_apellido'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $password = $_POST['password'];
            $nombre_completo = trim("$p_nombre $s_nombre $p_apellido $s_apellido");
            
            if (empty($p_nombre) || empty($p_apellido) || empty($email) || empty($password)) {
                $mensaje = 'El nombre, email y contraseña son obligatorios';
                $tipo_mensaje = 'error';
            } else {
                try {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $this->db->query("INSERT INTO usuarios (p_nombre, s_nombre, p_apellido, s_apellido, email, telefono, password) VALUES (?, ?, ?, ?, ?, ?, ?)", [$p_nombre, $s_nombre, $p_apellido, $s_apellido, $email, $telefono, $hashed_password]);
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
                u.id,
                CONCAT(
                    u.p_nombre, ' ',
                    IFNULL(u.s_nombre, ''), ' ',
                    u.p_apellido, ' ',
                    IFNULL(u.s_apellido, '')
                ) AS nombres,
                u.email, u.telefono, u.fecha_registro, u.rol, u.bloqueado,

                COUNT(r.id) as total_reservas,
                COUNT(CASE WHEN r.estado = 'activa' THEN 1 END) as reservas_activas,
                COUNT(CASE WHEN r.estado = 'completada' THEN 1 END) AS reservas_completadas,
                COUNT(CASE WHEN r.estado = 'cancelada' THEN 1 END) AS reservas_canceladas

            FROM usuarios u
            LEFT JOIN reservas r ON u.id = r.usuario_id
            GROUP BY u.id, u.p_nombre, u.s_nombre, u.p_apellido, u.s_apellido, u.email, u.telefono, u.fecha_registro, u.rol, u.bloqueado
            HAVING total_reservas > 0
            ORDER BY total_reservas DESC
            LIMIT 5
            ")->get();
    }

    private function getUsuariosConEstadisticas()
    {
        return $this->db->query("
            SELECT
                u.id,
                CONCAT(
                    u.p_nombre, ' ',
                    IFNULL(u.s_nombre, ''), ' ',
                    u.p_apellido, ' ',
                    IFNULL(u.s_apellido, '')
                ) AS nombres,
                u.email, u.telefono, u.fecha_registro, u.rol, u.bloqueado,

                COUNT(r.id) as total_reservas,
                SUM(CASE WHEN r.estado = 'activa' THEN 1 ELSE 0 END) as reservas_activas,
                SUM(CASE WHEN r.estado = 'completada' THEN 1 ELSE 0 END) AS reservas_completadas,
                SUM(CASE WHEN r.estado = 'cancelada' THEN 1 ELSE 0 END) AS reservas_canceladas

            FROM usuarios u
            LEFT JOIN reservas r ON r.usuario_id = u.id
            GROUP BY u.id
            ORDER BY U.fecha_registro DESc
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

    public function reporteReservas()
    {

        $sql = "
            SELECT
                r.fecha_reserva AS fecha_reserva, r.hora_inicio, r.hora_fin, r.estado, 
                CONCAT(
                    u.p_nombre, ' ',
                    u.s_nombre, ' ',
                    u.p_apellido, ' ',
                    u.s_apellido
                    
                ) AS usuario,
                 
                u.email,
                r.numero_espacio

            FROM reservas r
            INNER JOIN usuarios u ON r.usuario_id = u.id
            ORDER BY r.fecha_reserva DESC, r.hora_inicio DESC
        
        ";
        
        $reservas = db()->query($sql)->get();

        //crear hoja excel
        $excel = new Spreadsheet();
        $hoja = $excel->getActivesheet();

        //encebezados
        $hoja->setCellValue('A1', 'Fecha');
        $hoja->setCellValue('B1', 'Hora inicio');
        $hoja->setCellValue('C1', 'Hora fin');
        $hoja->setCellValue('D1', 'Estado');
        $hoja->setCellValue('E1', 'Usuario');
        $hoja->setCellValue('F1', 'Email');
        $hoja->setCellValue('G1', 'Espacio');

        //llenar filas
        $fila = 2;

        foreach ($reservas as $r) {
            $hoja->setCellValue('A'.$fila, $r->fecha_reserva);
            $hoja->setCellValue('B'.$fila, $r->hora_inicio);
            $hoja->setCellValue('C'.$fila, $r->hora_fin);
            $hoja->setCellValue('D'.$fila, $r->estado);
            $hoja->setCellValue('E'.$fila, $r->usuario);
            $hoja->setCellValue('F'.$fila, $r->email);
            $hoja->setCellValue('G'.$fila, $r->numero_espacio);
            $fila++;
        }

        //preparar la descarga
        $writer = new Xlsx($excel);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="reporte_reservas.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;

    }

    public function testExcel()
    {
        require base_path('vendor/autoload.php');

        var_dump(class_exists(Spreadsheet::class));
        exit;
    }

}
?>
