<?php
// Configuración de la página con modo oscuro para reducir la fatiga visual
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depuración de Servidor</title>
    <style>
        body {
            background-color: #1e1e2e;
            color: #cdd6f4;
            font-family: "Menlo", monospace;
            line-height: 1.6;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #89b4fa;
            border-bottom: 2px solid #89b4fa;
            padding-bottom: 10px;
        }
        h2 {
            color: #94e2d5;
            margin-top: 30px;
        }
        pre {
            background-color: #181825;
            border: 1px solid #45475a;
            border-radius: 5px;
            padding: 15px;
            overflow: auto;
            font-size: 14px;
            color: #a6e3a1;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        p {
            margin: 10px 0;
            background-color: #313244;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }
        strong {
            color: #f38ba8;
            font-weight: bold;
        }
        .container {
            background-color: #2a2b3c;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Información de rutas del servidor</h1>
        <p><strong>DOCUMENT_ROOT:</strong> <?php echo $_SERVER['DOCUMENT_ROOT']; ?></p>
        <p><strong>REQUEST_URI:</strong> <?php echo $_SERVER['REQUEST_URI']; ?></p>
        <p><strong>SERVER_NAME:</strong> <?php echo $_SERVER['SERVER_NAME']; ?></p>
        <p><strong>HTTP_HOST:</strong> <?php echo $_SERVER['HTTP_HOST']; ?></p>
        <p><strong>SCRIPT_FILENAME:</strong> <?php echo $_SERVER['SCRIPT_FILENAME']; ?></p>
    </div>
    
    <div class="container">
        <h2>Todas las variables del servidor:</h2>
        <pre><?php print_r($_SERVER); ?></pre>
    </div>
</body>
</html>