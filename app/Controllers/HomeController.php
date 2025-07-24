<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // Verificar autenticación usando el sistema existente
        // if (!isAuthenticated()) {
        //     redirect('login');
        // }

        // Obtener usuario actual desde la sesión
        $currentUser = session()->get('user');
        
        // if (!$currentUser) {
        //     redirect('login');
        // }

        // Obtener información completa del usuario desde la base de datos
        $usuario = null;
        if ($currentUser) {
            $usuario = db()->query('SELECT * FROM usuarios WHERE id = :id', [
                'id' => $currentUser['id']
            ])->first();
        }

        // if (!$usuario) {
        //     redirect('login');
        // }

        // Actualizar variables de sesión para compatibilidad con el template existente
        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
        } else {
            $_SESSION['usuario_id'] = null;
            $_SESSION['usuario_nombre'] = null;
            $_SESSION['usuario_rol'] = null;
        }

        // Obtener fechas para las consultas
        $hoy = date('Y-m-d');
        $manana = date('Y-m-d', strtotime('+1 day'));

        // Obtener espacios ocupados para carros
        $espacios_ocupados = db()->query(
            'SELECT numero_espacio, usuario_id FROM reservas 
             WHERE fecha_reserva = :fecha AND tipo_vehiculo = "carro" AND estado = "activa"',
            ['fecha' => $hoy]
        )->get();

        $ocupados_map = [];
        foreach ($espacios_ocupados as $espacio) {
            $ocupados_map[$espacio['numero_espacio']] = $espacio['usuario_id'];
        }

        // Obtener espacios ocupados para motos (agrupados)
        $motos_ocupadas = db()->query(
            'SELECT numero_espacio, COUNT(*) as ocupados FROM reservas 
             WHERE fecha_reserva = :fecha AND tipo_vehiculo = "moto" AND estado = "activa" 
             GROUP BY numero_espacio',
            ['fecha' => $hoy]
        )->get();

        $moto_ocupados = [];
        foreach ($motos_ocupadas as $moto) {
            $moto_ocupados[$moto['numero_espacio']] = $moto['ocupados'];
        }

        // Obtener reservas para mañana
        if ($_SESSION['usuario_rol'] == 'admin') {
            // Admin ve todas las reservas
            $reservas_manana = db()->query(
                'SELECT r.*, u.nombre, u.email FROM reservas r 
                 JOIN usuarios u ON r.usuario_id = u.id 
                 WHERE r.fecha_reserva = :fecha AND r.estado = "activa" 
                 ORDER BY r.hora_inicio',
                ['fecha' => $manana]
            )->get();
        } elseif ($_SESSION['usuario_rol']) {
            // Usuario normal solo ve sus reservas
            $reservas_manana = db()->query(
                'SELECT r.*, u.nombre, u.email FROM reservas r 
                 JOIN usuarios u ON r.usuario_id = u.id 
                 WHERE r.fecha_reserva = :fecha AND r.usuario_id = :usuario_id AND r.estado = "activa" 
                 ORDER BY r.hora_inicio',
                [
                    'fecha' => $manana,
                    'usuario_id' => $currentUser['id']
                ]
            )->get();
        } else {
            $reservas_manana = [];
        }

        // Obtener lista de usuarios para admin
        $usuarios = [];
        if ($_SESSION['usuario_rol'] == 'admin') {
            $usuarios = db()->query('SELECT id, nombre FROM usuarios ORDER BY nombre')->get();
        }

        // Procesar reservas si se envió el formulario
        $mensaje = '';
        $tipo_mensaje = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'reservar') {
            try {
                $numero_espacio = $_POST['numero_espacio'];
                $tipo_vehiculo = $_POST['tipo_vehiculo'];
                $fecha_reserva = $_POST['fecha_reserva'];
                $hora_inicio = $_POST['hora_inicio'];
                $hora_fin = $_POST['hora_fin'];
                $placa_vehiculo = strtoupper(trim($_POST['placa_vehiculo']));
                
                // Determinar el usuario (admin puede reservar para otros)
                $usuario_reserva = ($_SESSION['usuario_rol'] == 'admin' && isset($_POST['usuario_id'])) 
                    ? $_POST['usuario_id'] 
                    : ($currentUser['id'] ?? null);

                // Validaciones básicas
                if (strtotime($hora_fin) <= strtotime($hora_inicio)) {
                    throw new \Exception("La hora de fin debe ser posterior a la hora de inicio");
                }

                // Verificar disponibilidad del espacio
                if ($tipo_vehiculo == 'carro') {
                    $conflicto = db()->query(
                        'SELECT id FROM reservas 
                         WHERE numero_espacio = :espacio AND fecha_reserva = :fecha 
                         AND estado = "activa" AND (
                             (hora_inicio <= :hora_inicio AND hora_fin > :hora_inicio) OR
                             (hora_inicio < :hora_fin AND hora_fin >= :hora_fin) OR
                             (hora_inicio >= :hora_inicio AND hora_fin <= :hora_fin)
                         )',
                        [
                            'espacio' => $numero_espacio,
                            'fecha' => $fecha_reserva,
                            'hora_inicio' => $hora_inicio,
                            'hora_fin' => $hora_fin
                        ]
                    )->first();

                    if ($conflicto) {
                        throw new \Exception("El espacio ya está reservado en ese horario");
                    }
                } else {
                    // Para motos, verificar límite de cupos
                    $cupos_maximos = [
                        '476' => 6, '476a' => 1,
                        '475' => 6, '475a' => 1,
                        '474' => 6, '474a' => 1, '474b' => 1,
                        '441' => 4
                    ];

                    $cupos_ocupados = db()->query(
                        'SELECT COUNT(*) as total FROM reservas 
                         WHERE numero_espacio = :espacio AND fecha_reserva = :fecha 
                         AND estado = "activa" AND (
                             (hora_inicio <= :hora_inicio AND hora_fin > :hora_inicio) OR
                             (hora_inicio < :hora_fin AND hora_fin >= :hora_fin) OR
                             (hora_inicio >= :hora_inicio AND hora_fin <= :hora_fin)
                         )',
                        [
                            'espacio' => $numero_espacio,
                            'fecha' => $fecha_reserva,
                            'hora_inicio' => $hora_inicio,
                            'hora_fin' => $hora_fin
                        ]
                    )->get();

                    $max_cupos = $cupos_maximos[$numero_espacio] ?? 1;
                    if ($cupos_ocupados && $cupos_ocupados[0]['total'] >= $max_cupos) {
                        throw new \Exception("No hay cupos disponibles en ese espacio para el horario seleccionado");
                    }
                }

                // Crear la reserva
                db()->query(
                    'INSERT INTO reservas (usuario_id, numero_espacio, tipo_vehiculo, fecha_reserva, hora_inicio, hora_fin, placa_vehiculo, estado, fecha_creacion) 
                     VALUES (:usuario_id, :numero_espacio, :tipo_vehiculo, :fecha_reserva, :hora_inicio, :hora_fin, :placa_vehiculo, "activa", NOW())',
                    [
                        'usuario_id' => $usuario_reserva,
                        'numero_espacio' => $numero_espacio,
                        'tipo_vehiculo' => $tipo_vehiculo,
                        'fecha_reserva' => $fecha_reserva,
                        'hora_inicio' => $hora_inicio,
                        'hora_fin' => $hora_fin,
                        'placa_vehiculo' => $placa_vehiculo
                    ]
                );

                $mensaje = "Reserva creada exitosamente para el espacio $numero_espacio";
                $tipo_mensaje = "success";

                // Recargar datos después de la reserva
                redirect($_SERVER['REQUEST_URI']);

            } catch (\Exception $e) {
                $mensaje = "Error al crear la reserva: " . $e->getMessage();
                $tipo_mensaje = "error";
            }
        }

        // Cargar la vista con todos los datos necesarios
        view('home', [
            'title' => 'Agendar Parqueadero',
            'ocupados_map' => $ocupados_map,
            'moto_ocupados' => $moto_ocupados,
            'reservas_manana' => $reservas_manana,
            'usuarios' => $usuarios,
            'hoy' => $hoy,
            'manana' => $manana,
            'mensaje' => $mensaje,
            'tipo_mensaje' => $tipo_mensaje
        ]);
    }

    public function show()
    {
        if (!isAuthenticated()) {
            redirect('login');
        }

        $home = db()->query('SELECT * FROM usuarios WHERE id = :id', [
            'id' => $_GET['id'] ?? null,
        ])->firstOrFail();
        
        return $home;
    }
}