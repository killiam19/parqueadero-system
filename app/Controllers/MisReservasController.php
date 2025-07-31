<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MisReservasController extends Controller
{
    public function usuarioTieneReservaPorDia($usuario_id, $fecha)
    {
        // Assuming $db is the database connection object
        $db = \Config\Database::connect();
        $query = 'SELECT COUNT(*) as total FROM reservas WHERE usuario_id = :usuario_id AND fecha_reserva = :fecha AND estado = "activa"';
        $statement = $db->prepare($query);
        $statement->bind_param('ii', $usuario_id, $fecha);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] > 0;
    }

    // Other methods can be added here
}
