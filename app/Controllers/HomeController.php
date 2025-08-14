<?php

namespace App\Controllers;

use Framework\Validator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_telefono'] = $usuario['telefono'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
        } else {
            $_SESSION['usuario_id'] = null;
            $_SESSION['usuario_nombre'] = null;
            $_SESSION['usuario_email'] = null;
            $_SESSION['usuario_telefono'] = null;
            $_SESSION['usuario_rol'] = null;
        }

        // Generar fechas de la semana laboral (lunes a viernes)
        $fechas_semana = $this->generarFechasSemanaLaboral();
        $hoy = date('Y-m-d');

        // Obtener todas las reservas de la semana para carros
        $fechas_str = "'" . implode("','", array_keys($fechas_semana)) . "'";
        $espacios_ocupados_semana = db()->query(
            "SELECT numero_espacio, usuario_id, fecha_reserva FROM reservas 
             WHERE fecha_reserva IN ($fechas_str) AND tipo_vehiculo = 'carro' AND estado = 'activa'"
        )->get();

        // Organizar espacios ocupados por fecha
        $ocupados_por_fecha = [];
        foreach ($espacios_ocupados_semana as $espacio) {
            $ocupados_por_fecha[$espacio['fecha_reserva']][$espacio['numero_espacio']] = $espacio['usuario_id'];
        }

        // Obtener espacios ocupados para motos de toda la semana
        $motos_ocupadas_semana = db()->query(
            "SELECT numero_espacio, fecha_reserva, COUNT(*) as ocupados FROM reservas 
             WHERE fecha_reserva IN ($fechas_str) AND tipo_vehiculo = 'moto' AND estado = 'activa' 
             GROUP BY numero_espacio, fecha_reserva"
        )->get();

        // Organizar motos ocupadas por fecha
        $moto_ocupados_por_fecha = [];
        foreach ($motos_ocupadas_semana as $moto) {
            $moto_ocupados_por_fecha[$moto['fecha_reserva']][$moto['numero_espacio']] = $moto['ocupados'];
        }

        // Obtener espacios ocupados para motos grandes de toda la semana
        $motos_grandes_ocupadas_semana = db()->query(
            "SELECT numero_espacio, usuario_id, fecha_reserva FROM reservas 
             WHERE fecha_reserva IN ($fechas_str) AND tipo_vehiculo = 'moto_grande' AND estado = 'activa'"
        )->get();

        // Organizar motos grandes ocupadas por fecha
        $moto_grande_ocupados_por_fecha = [];
        foreach ($motos_grandes_ocupadas_semana as $moto_grande) {
            $moto_grande_ocupados_por_fecha[$moto_grande['fecha_reserva']][$moto_grande['numero_espacio']] = $moto_grande['usuario_id'];
        }

        // Crear mapas de ocupación para hoy (para mostrar en la vista)
        $ocupados_map = $ocupados_por_fecha[$hoy] ?? [];
        $moto_ocupados = $moto_ocupados_por_fecha[$hoy] ?? [];
        $moto_grande_ocupados_map = $moto_grande_ocupados_por_fecha[$hoy] ?? [];

        // Obtener reservas de toda la semana
        $reservas_semana = [];
        foreach ($fechas_semana as $fecha => $info) {
            if ($_SESSION['usuario_rol'] == 'admin') {
                // Admin ve todas las reservas
                $reservas = db()->query(
                    'SELECT r.*, u.nombre, u.email FROM reservas r 
                     JOIN usuarios u ON r.usuario_id = u.id 
                     WHERE r.fecha_reserva = :fecha AND r.estado = "activa" 
                     ORDER BY r.numero_espacio',
                    ['fecha' => $fecha]
                )->get();
            } elseif ($_SESSION['usuario_rol']) {
                // Usuario normal solo ve sus reservas
                $reservas = db()->query(
                    'SELECT r.*, u.nombre, u.email FROM reservas r 
                     JOIN usuarios u ON r.usuario_id = u.id 
                     WHERE r.fecha_reserva = :fecha AND r.usuario_id = :usuario_id AND r.estado = "activa" 
                     ORDER BY r.numero_espacio',
                    [
                        'fecha' => $fecha,
                        'usuario_id' => $currentUser['id']
                    ]
                )->get();
            } else {
                $reservas = [];
            }
            $reservas_semana[$fecha] = $reservas;
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
                $placa_vehiculo = strtoupper(trim($_POST['placa_vehiculo']));
                
                $fecha_reserva = $this->convertirFormatoFecha($fecha_reserva);
                
                // Validar que la fecha sea un día hábil
                if (!$this->esDiaHabil($fecha_reserva)) {
                    throw new \Exception("Solo se pueden hacer reservas para días hábiles (lunes a viernes)");
                }
                
                // Determinar el usuario (admin puede reservar para otros)
                $usuario_reserva = ($_SESSION['usuario_rol'] == 'admin' && isset($_POST['usuario_id'])) 
                    ? $_POST['usuario_id'] 
                    : ($currentUser['id'] ?? null);

                if (!$usuario_reserva) {
                    throw new \Exception("Debe iniciar sesión para hacer reservas");
                }

                // NUEVA VALIDACIÓN: Verificar que el usuario no tenga ya una reserva para esa fecha
                $reserva_existente = db()->query(
                    'SELECT id FROM reservas 
                     WHERE usuario_id = :usuario_id AND fecha_reserva = :fecha AND estado = "activa"',
                    [
                        'usuario_id' => $usuario_reserva,
                        'fecha' => $fecha_reserva
                    ]
                )->first();

                if ($reserva_existente) {
                    throw new \Exception("Ya tienes una reserva para esta fecha. Solo se permite una reserva por día por usuario.");
                }

                // Verificar disponibilidad del espacio
                if ($tipo_vehiculo == 'carro') {
                    $conflicto = db()->query(
                        'SELECT id FROM reservas 
                         WHERE numero_espacio = :espacio AND fecha_reserva = :fecha AND estado = "activa"',
                        [
                            'espacio' => $numero_espacio,
                            'fecha' => $fecha_reserva
                        ]
                    )->first();

                    if ($conflicto) {
                        throw new \Exception("El espacio ya está reservado para esa fecha");
                    }
                } elseif ($tipo_vehiculo == 'moto_grande') {
                    // Para motos grandes, verificar que el espacio no esté ocupado
                    $conflicto = db()->query(
                        'SELECT id FROM reservas 
                         WHERE numero_espacio = :espacio AND fecha_reserva = :fecha AND estado = "activa"',
                        [
                            'espacio' => $numero_espacio,
                            'fecha' => $fecha_reserva
                        ]
                    )->first();

                    if ($conflicto) {
                        throw new \Exception("El espacio ya está reservado para esa fecha");
                    }
                } else {
                    // Para motos, verificar límite de cupos
                    $cupos_maximos = [
                        '476' => 6, '476a' => 1,
                        '475' => 6, '475a' => 1,
                        '474' => 6, '474b' => 1, '474a' => 1,
                        '441' => 4
                    ];

                    $cupos_ocupados = db()->query(
                        'SELECT COUNT(*) as total FROM reservas 
                         WHERE numero_espacio = :espacio AND fecha_reserva = :fecha AND estado = "activa"',
                        [
                            'espacio' => $numero_espacio,
                            'fecha' => $fecha_reserva
                        ]
                    )->get();

                    $max_cupos = $cupos_maximos[$numero_espacio] ?? 1;
                    if ($cupos_ocupados && $cupos_ocupados[0]['total'] >= $max_cupos) {
                        throw new \Exception("No hay cupos disponibles en ese espacio para la fecha seleccionada");
                    }
                }

                // Crear la reserva
                db()->query(
                    'INSERT INTO reservas (usuario_id, numero_espacio, tipo_vehiculo, fecha_reserva, placa_vehiculo, estado, fecha_creacion) 
                     VALUES (:usuario_id, :numero_espacio, :tipo_vehiculo, :fecha_reserva,:placa_vehiculo, "activa", NOW())',
                    [
                        'usuario_id' => $usuario_reserva,
                        'numero_espacio' => $numero_espacio,
                        'tipo_vehiculo' => $tipo_vehiculo,
                        'fecha_reserva' => $fecha_reserva,
                        'placa_vehiculo' => $placa_vehiculo
                    ]
                );

                $mensaje = "Reserva creada exitosamente para el espacio $numero_espacio el " . $this->formatearFecha($fecha_reserva);
                $tipo_mensaje = "success";

                // Recargar datos después de la reserva
                redirect($_SERVER['REQUEST_URI']);

            } catch (\Exception $e) {
                $mensaje = "Error al crear la reserva: " . $e->getMessage();
                $tipo_mensaje = "error";
            }
        }

        // Extraer reservas de hoy y mañana
        $hoy = date('Y-m-d');
        $manana = date('Y-m-d', strtotime('+1 day'));
        
        $reservas_hoy = isset($reservas_semana[$hoy]) ? $reservas_semana[$hoy] : [];
        $reservas_manana = isset($reservas_semana[$manana]) ? $reservas_semana[$manana] : [];

        // Cargar la vista con todos los datos necesarios
        view('home', [
            'title' => 'Agendar Parqueadero - Semana Laboral',
            'fechas_semana' => $fechas_semana,
            'ocupados_por_fecha' => $ocupados_por_fecha,
            'moto_ocupados_por_fecha' => $moto_ocupados_por_fecha,
            'moto_grande_ocupados_por_fecha' => $moto_grande_ocupados_por_fecha,
            'reservas_semana' => $reservas_semana,
            'reservas_hoy' => $reservas_hoy,
            'reservas_manana' => $reservas_manana,
            'usuarios' => $usuarios,
            'hoy' => $hoy,
            'mensaje' => $mensaje,
            'tipo_mensaje' => $tipo_mensaje,
            // Agregar estos mapas para la vista
            'ocupados_map' => $ocupados_map,
            'moto_ocupados' => $moto_ocupados,
            'moto_grande_ocupados_map' => $moto_grande_ocupados_map
        ]);
    }

    /**
     * Genera las fechas de la semana laboral actual (lunes a viernes)
     */
    private function generarFechasSemanaLaboral()
    {
        $fechas = [];
        $hoy = new \DateTime();
        
        // Encontrar el lunes de esta semana
        $lunes = clone $hoy;
        $dia_semana = $lunes->format('N'); // 1 = lunes, 7 = domingo
        
        if ($dia_semana > 1) {
            $lunes->sub(new \DateInterval('P' . ($dia_semana - 1) . 'D'));
        }
        
        // Generar fechas de lunes a viernes
        $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        
        for ($i = 0; $i < 5; $i++) {
            $fecha = clone $lunes;
            $fecha->add(new \DateInterval('P' . $i . 'D'));
            
            $fecha_str = $fecha->format('Y-m-d');
            $fechas[$fecha_str] = [
                'fecha' => $fecha_str,
                'dia_nombre' => $dias_semana[$i],
                'dia_mes' => $fecha->format('d'),
                'mes_nombre' => $this->obtenerNombreMes($fecha->format('n')),
                'es_hoy' => $fecha_str === date('Y-m-d'),
                'es_pasado' => $fecha_str < date('Y-m-d')
            ];
        }
        
        return $fechas;
    }

    /**
     * Verifica si una fecha es día hábil (lunes a viernes)
     */
    private function esDiaHabil($fecha)
    {
        $dia_semana = date('N', strtotime($fecha)); // 1 = lunes, 7 = domingo
        return $dia_semana >= 1 && $dia_semana <= 5;
    }

    /**
     * Obtiene el nombre del mes en español
     */
    private function obtenerNombreMes($numero_mes)
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        return $meses[$numero_mes];
    }

    /**
     * Formatea una fecha para mostrar de forma legible
     */
    private function formatearFecha($fecha)
    {
        $timestamp = strtotime($fecha);
        $dia_nombre = $this->obtenerNombreDia(date('N', $timestamp));
        $dia = date('d', $timestamp);
        $mes = $this->obtenerNombreMes(date('n', $timestamp));
        
        return "$dia_nombre $dia de $mes";
    }

    /**
     * Obtiene el nombre del día en español
     */
    private function obtenerNombreDia($numero_dia)
    {
        $dias = [
            1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves',
            5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'
        ];
        return $dias[$numero_dia];
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

    public function store()
    {
        Validator::make($_POST, [
            'numero_espacio' => 'required',
            'fecha_reserva' => 'required',
            'placa_vehiculo' => 'required',
            'tipo_vehiculo' => 'required',
        ]);

        $fecha_reserva = $this->convertirFormatoFecha($_POST['fecha_reserva']);

        // Validar que la fecha sea un día hábil
        if (!$this->esDiaHabil($fecha_reserva)) {
            redirect('/', 'Solo se pueden hacer reservas para días hábiles (lunes a viernes)', 'error');
        }

        // Obtener usuario actual
        $currentUser = session()->get('user');
        if (!$currentUser) {
            redirect('login', 'Debes iniciar sesión para hacer reservas');
        }

        // NUEVA VALIDACIÓN: Verificar que el usuario no tenga ya una reserva para esa fecha
        $reserva_existente = db()->query(
            'SELECT id FROM reservas 
             WHERE usuario_id = :usuario_id AND fecha_reserva = :fecha AND estado = "activa"',
            [
                'usuario_id' => $currentUser['id'],
                'fecha' => $fecha_reserva // Usar fecha convertida
            ]
        )->first();

        if ($reserva_existente) {
           redirect('/', 'Ya tienes una reserva activa', 400); // código 400 para errores del cliente
        }

        // Obtener datos completos del usuario
        $usuario = db()->query('SELECT * FROM usuarios WHERE id = :id', [
            'id' => $currentUser['id']
        ])->first();

        // Crear la reserva
        db()->query(
            'INSERT INTO reservas (usuario_id, numero_espacio, fecha_reserva, placa_vehiculo, estado, tipo_vehiculo) 
             VALUES (:usuario_id, :numero_espacio, :fecha_reserva, :placa_vehiculo, "activa", :tipo_vehiculo)',
            [
                'usuario_id' => $currentUser['id'],
                'numero_espacio' => $_POST['numero_espacio'],
                'fecha_reserva' => $fecha_reserva, // Usar fecha convertida
                'placa_vehiculo' => $_POST['placa_vehiculo'],
                'tipo_vehiculo' => $_POST['tipo_vehiculo']
            ]
        );

        // Preparar datos para el correo
        $datosReserva = [
            'fecha' => $fecha_reserva, // Usar fecha convertida
            'placa' => $_POST['placa_vehiculo'],
            'tipo_vehiculo' => $_POST['tipo_vehiculo'],
            'numero_espacio' => $_POST['numero_espacio']
        ];

        // Enviar correo de confirmación
        $this->enviarCorreoConfirmacion($usuario['email'], $usuario['nombre'], $datosReserva);

        redirect('/', 'Reserva creada exitosamente. Se ha enviado un correo de confirmación.');
    }

    private function enviarCorreoConfirmacion($to, $nombre, $datosReserva)
    {
        $mail = new PHPMailer(true);
        
        try {
            // Configuración SMTP (usando tus datos)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'killiam1119@gmail.com';
            $mail->Password = 'oqon pjgg ekvm yptj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];
            
            // Configuración del correo
            $mail->setFrom('killiam1119@gmail.com', 'Sistema de Parqueadero');
            $mail->addAddress($to, $nombre);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de Reserva de Parqueadero';

            // Cuerpo del mensaje (usando tu diseño HTML)
            $mail->Body = $this->crearCuerpoHTML($nombre, $datosReserva);
            $mail->AltBody = $this->crearTextoPlano($nombre, $datosReserva);

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Error al enviar correo: ' . $e->getMessage());
            return false;
        }
    }

    private function crearCuerpoHTML($nombre, $datosReserva)
    {
        // Formatear la fecha para el correo
        $fechaFormateada = $this->formatearFecha($datosReserva['fecha']);
        
        return '
        <div style="max-width: 500px; margin: 0 auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(44,62,80,0.08); font-family: Arial, sans-serif; overflow: hidden;">
            <div style="background: #3498db; padding: 24px 0; text-align: center;">
                <img src="https://img.icons8.com/color/96/000000/car--v1.png" alt="Logo Auto" style="width: 64px; height: 64px; margin-bottom: 10px;">
                <h1 style="color: #fff; margin: 0; font-size: 2em;">¡Reserva Confirmada!</h1>
            </div>
            <div style="padding: 30px 24px 24px 24px; color: #333;">
                <p style="font-size: 1.1em;">Hola <strong>' . htmlspecialchars($nombre) . '</strong>,</p>
                <p style="margin-bottom: 18px;">Tu reserva de parqueadero se ha registrado correctamente. Aquí tienes los detalles:</p>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 18px;">
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Fecha:</td>
                        <td style="padding: 8px 0;">' . htmlspecialchars($fechaFormateada) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Espacio:</td>
                        <td style="padding: 8px 0;">' . htmlspecialchars($datosReserva['numero_espacio']) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Placa:</td>
                        <td style="padding: 8px 0;">' . htmlspecialchars($datosReserva['placa']) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Tipo de vehículo:</td>
                        <td style="padding: 8px 0;">' . htmlspecialchars($datosReserva['tipo_vehiculo']) . '</td>
                    </tr>
                </table>
                <div style="background: #e8f4f8; border-left: 4px solid #3498db; padding: 12px 18px; border-radius: 6px; margin-bottom: 18px;">
                    <strong>Recuerda:</strong> Presenta este correo si te lo solicitan en la entrada.
                </div>
                <p style="font-size: 0.95em; color: #888; margin-bottom: 0;">Gracias por usar el Sistema de Parqueadero.<br>Este es un correo automático, por favor no respondas a este mensaje.</p>
            </div>
        </div>';
    }

    private function crearTextoPlano($nombre, $datosReserva)
    {
        $fechaFormateada = $this->formatearFecha($datosReserva['fecha']);
        
        return "Confirmación de Reserva\n\n" .
               "Hola {$nombre},\n\n" .
               "Tu reserva ha sido registrada con éxito:\n\n" .
               "Fecha: {$fechaFormateada}\n" .
               "Espacio: {$datosReserva['numero_espacio']}\n" .
               "Placa: {$datosReserva['placa']}\n" .
               "Tipo de vehículo: {$datosReserva['tipo_vehiculo']}\n\n" .
               "Recuerda: Presenta este correo si te lo solicitan en la entrada.\n\n" .
               "Gracias por usar el Sistema de Parqueadero.\n" .
               "Este es un mensaje automático, por favor no respondas a este correo.";
    }

    /**
     * Convierte fecha de formato MM/DD/YYYY a YYYY-MM-DD
     */
    private function convertirFormatoFecha($fecha)
    {
        // Si la fecha ya está en formato YYYY-MM-DD, devolverla tal como está
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            return $fecha;
        }
        
        // Si está en formato MM/DD/YYYY, convertirla
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $fecha, $matches)) {
            $mes = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $dia = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $año = $matches[3];
            return "$año-$mes-$dia";
        }
        
        // Si no coincide con ningún formato esperado, intentar con strtotime
        $timestamp = strtotime($fecha);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }
        
        // Si todo falla, devolver la fecha original
        return $fecha;
    }
}
