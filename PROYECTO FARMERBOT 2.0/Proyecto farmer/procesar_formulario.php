<?php
// procesar_formulario.php

// Incluimos el archivo de configuración para obtener las credenciales de la BD
require_once 'config.php';

// Inicializar un array para los mensajes de error
$errors = [];

// Solo procesar si la solicitud es POST (el formulario fue enviado)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- 1. Obtener y sanear los datos del formulario ---
    // filter_input_array es una forma segura de obtener datos POST
    $formData = filter_input_array(INPUT_POST, [
        'nombre' => FILTER_SANITIZE_STRING,
        'email' => FILTER_SANITIZE_EMAIL,
        'pais' => FILTER_SANITIZE_STRING,
        'telefono' => FILTER_SANITIZE_STRING
    ]);

    $nombre = trim($formData['nombre']);
    $email = trim($formData['email']);
    $codigo_pais = trim($formData['pais']);
    $telefono = trim($formData['telefono']);

    // --- 2. Validar los datos ---
    if (empty($nombre)) {
        $errors[] = "El nombre es obligatorio.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El correo electrónico no es válido o está vacío.";
    }
    if (empty($codigo_pais)) {
        $errors[] = "Debes seleccionar un país.";
    }
    if (empty($telefono)) {
        $errors[] = "El número de teléfono es obligatorio.";
    }

    // Si no hay errores de validación de los campos...
    if (empty($errors)) {
        // --- 3. Conectar a la base de datos ---
        $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // Verificar la conexión
        if ($mysqli->connect_error) {
            // Loguea el error real por seguridad (no lo muestres al usuario)
            error_log("Error de conexión a la base de datos: " . $mysqli->connect_error);
            $errors[] = "Error interno del servidor. Por favor, inténtalo de nuevo más tarde.";
        } else {
            // Establecer el charset para evitar problemas con tildes y ñ
            $mysqli->set_charset(DB_CHARSET);

            // --- 4. VERIFICAR UNICIDAD del Email ---
            $stmt_check = $mysqli->prepare("SELECT id FROM usuarios WHERE email = ?");
            if ($stmt_check) {
                $stmt_check->bind_param("s", $email); // "s" indica que $email es un string
                $stmt_check->execute();
                $stmt_check->store_result(); // Necesario para obtener el número de filas

                if ($stmt_check->num_rows > 0) {
                    $errors[] = "Este correo electrónico ya está registrado. Por favor, usa otro.";
                }
                $stmt_check->close();
            } else {
                error_log("Error al preparar la consulta de verificación de email: " . $mysqli->error);
                $errors[] = "Error interno del servidor. Por favor, inténtalo de nuevo más tarde.";
            }

            // Si el email es único y no hay otros errores...
            if (empty($errors)) {
                // --- 5. INSERTAR los datos en la base de datos ---
                // Usamos prepared statements para prevenir inyección SQL
                $stmt_insert = $mysqli->prepare("INSERT INTO usuarios (nombre, email, codigo_pais, telefono) VALUES (?, ?, ?, ?)");

                if ($stmt_insert) {
                    $stmt_insert->bind_param("ssss", $nombre, $email, $codigo_pais, $telefono);
                    // "ssss" indica que los 4 parámetros son strings

                    if ($stmt_insert->execute()) {
                        // Éxito: Redirigir al usuario con un mensaje de éxito
                        header("Location: formulario.php?message=" . urlencode("¡Registro exitoso! Gracias por unirte a FarmerBot.") . "&type=success");
                        exit(); // Terminar el script después de la redirección
                    } else {
                        // Error en la inserción
                        error_log("Error al insertar usuario: " . $stmt_insert->error);
                        $errors[] = "No se pudo registrar el usuario. Por favor, inténtalo de nuevo.";
                    }
                    $stmt_insert->close();
                } else {
                    error_log("Error al preparar la consulta de inserción: " . $mysqli->error);
                    $errors[] = "Error interno del servidor. Por favor, inténtalo de nuevo más tarde.";
                }
            }

            // Cerrar la conexión a la base de datos
            $mysqli->close();
        }
    }
}

// Si hay errores, redirigir de vuelta al formulario con los mensajes de error
if (!empty($errors)) {
    $error_message = urlencode(implode("<br>", $errors));
    header("Location: formulario.php?message=$error_message&type=error");
    exit();
}

// Si alguien intenta acceder a procesar_formulario.php directamente sin POST,
// puedes redirigirlos o mostrar un mensaje.
header("Location: formulario.php");
exit();

?>