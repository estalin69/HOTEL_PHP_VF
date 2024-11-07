<?php
session_start();
include_once('../modelo/conexion.php');

// Habilitar la visualización de errores solo en desarrollo
if ($_SERVER['SERVER_NAME'] !== 'your_production_domain.com') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Verificar la conexión a la base de datos
if (!$conn) {
    die("Error en la conexión: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar los datos de entrada
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $pass = $_POST['contrasena'];

    // Preparar la consulta para evitar inyecciones SQL
    $stmt = $conn->prepare("SELECT id_usuario, password FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Comparar la contraseña directamente
        if ($pass === $row['password']) {
            $_SESSION['login_user'] = $usuario;
            header("location: main.php");
            exit(); // Terminar el script después de redireccionar
        }
    }
    $error = "Usuario o contraseña incorrectos."; // Mensaje genérico
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="col-md-4">
            <h2 class="text-center text-white">Login</h2>
            <div class="card mt-3">
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-group mb-3">
                            <label for="usuario">Usuario:</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="contrasena">Contraseña:</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                    </form>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>