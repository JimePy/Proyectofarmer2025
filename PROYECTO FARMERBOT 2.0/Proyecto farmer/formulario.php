<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <style>
       
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Bungee&display=swap');
        body {
            font-family: "Outfit", sans-serif;
            
            background-color:rgba(31, 60, 14, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .form-container h2 {
            font-family: "Bungee", sans-serif;
            color: #333;
            text-align: center;
            margin-bottom: 25px;
            font-size: 2em;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1rem;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group input[type="tel"]:focus,
        .form-group select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        .phone-group {
            display: flex;
            gap: 10px;
        }

        .phone-group select {
            flex-shrink: 0;
            width: 150px;
        }

        .phone-group input[type="tel"] {
            flex-grow: 1;
        }

        button[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }

        input:invalid {
            border-color: #dc3545;
        }
        input:valid {
            border-color: #28a745;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Formulario</h2>
        <form action="procesar_formulario.php" method="POST">

            <div class="form-group">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Juan Pérez" required>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" placeholder="ejemplo@dominio.com" required>
            </div>

            <div class="form-group">
                <label for="telefono">Número de Teléfono:</label>
                <div class="phone-group">
                    <select id="pais" name="pais" required>
                        <option value="">Selecciona un país</option>
                        <option value="+1">EEUU (+1)</option>
                        <option value="+54">Argentina (+54)</option>
                        <option value="+55">Brasil (+55)</option>
                        <option value="+56">Chile (+56)</option>
                        <option value="+57">Colombia (+57)</option>
                        <option value="+506">Costa Rica (+506)</option>
                        <option value="+593">Ecuador (+593)</option>
                        <option value="+503">El Salvador (+503)</option>
                        <option value="+34">España (+34)</option>
                        <option value="+502">Guatemala (+502)</option>
                        <option value="+504">Honduras (+504)</option>
                        <option value="+52">México (+52)</option>
                        <option value="+505">Nicaragua (+505)</option>
                        <option value="+507">Panamá (+507)</option>
                        <option value="+595" selected>Paraguay (+595)</option>
                        <option value="+51">Perú (+51)</option>
                        <option value="+1-787">Puerto Rico (+1-787)</option>
                        <option value="+1-809">Rep. Dominicana (+1-809)</option>
                        <option value="+598">Uruguay (+598)</option>
                        <option value="+58">Venezuela (+58)</option>
                    </select>
                    <input type="tel" id="telefono" name="telefono" placeholder="Ej: 987654321" required>
                </div>
            </div>

            <button type="submit">Registrarme</button>

        </form>

        <?php
        // Este bloque PHP solo es para mostrar mensajes si el formulario fue redirigido aquí
        // desde procesar_formulario.php con un mensaje de éxito o error.
        // Es una forma simple de mostrar feedback sin JavaScript complejo.
        if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']);
            $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'info';
            echo "<div class='message $type'>$message</div>";
        }
        ?>

    </div>

</body>
</html>