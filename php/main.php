<?php
session_start();
include_once('../modelo/conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit();
}   
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Home - Gestión de Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
</head>

<body>
    <div class="sidebar">
        <h5 class="text-white">Menú</h5>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'personas.php') ? 'active' : ''; ?>" href="personas.php">Personas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'habitaciones.php') ? 'active' : ''; ?>" href="habitaciones.php">Habitaciones</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'empleados.php') ? 'active' : ''; ?>" href="empleados.php">Empleados</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Cerrar sesión</a>
            </li>
        </ul>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>