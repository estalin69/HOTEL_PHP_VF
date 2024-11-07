<?php
session_start();
include_once('../modelo/conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit();
}

// Procesar el formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si se está editando una persona
    if (isset($_POST['id_persona'])) {
        $id_persona = $_POST['id_persona'];
        $dni = $_POST['dni'];
        $nombres = $_POST['nombres'];
        $ape_paterno = $_POST['ape_paterno'];
        $ape_materno = $_POST['ape_materno'];
        $telefono = $_POST['telefono'];
        $genero = $_POST['genero'];
        $correo = $_POST['correo'];
        $pasaporte = $_POST['pasaporte'];
        $fk_id_procedencia = $_POST['fk_id_procedencia'];

        // Actualizar en la base de datos
        $query_update = "UPDATE personas SET dni='$dni', nombres='$nombres', ape_paterno='$ape_paterno', ape_materno='$ape_materno', telefono='$telefono', genero='$genero', correo='$correo', pasaporte='$pasaporte', fk_id_procedencia='$fk_id_procedencia' WHERE id_persona='$id_persona'";

        if ($conn->query($query_update) === TRUE) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $query_update . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['delete_id'])) {
        // Código para eliminar persona
        $delete_id = $_POST['delete_id'];
        $query_delete = "DELETE FROM personas WHERE id_persona='$delete_id'";

        if ($conn->query($query_delete) === TRUE) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $query_delete . "<br>" . $conn->error;
        }
    } else {
        // Código para agregar nueva persona
        $dni = $_POST['dni'];
        $nombres = $_POST['nombres'];
        $ape_paterno = $_POST['ape_paterno'];
        $ape_materno = $_POST['ape_materno'];
        $telefono = $_POST['telefono'];
        $genero = $_POST['genero'];
        $correo = $_POST['correo'];
        $pasaporte = $_POST['pasaporte'];
        $fk_id_procedencia = $_POST['fk_id_procedencia'];

        // Insertar en la base de datos
        $query_insert = "INSERT INTO personas (dni, nombres, ape_paterno, ape_materno, telefono, genero, correo, pasaporte, fk_id_procedencia) 
                         VALUES ('$dni', '$nombres', '$ape_paterno', '$ape_materno', '$telefono', '$genero', '$correo', '$pasaporte', '$fk_id_procedencia')";

        if ($conn->query($query_insert) === TRUE) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $query_insert . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Personas - Gestión de Hotel</title>
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

    <div class="container mt-4">
        <h2>Lista de Personas</h2>
        <div class="text-end mb-3">
            <!-- Botón para abrir el modal -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearPersonaModal">Crear Persona</button>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>DNI</th>
                    <th>Nombres</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Teléfono</th>
                    <th>Género</th>
                    <th>Correo</th>
                    <th>Pasaporte</th>
                    <th>Procedencia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consultar la base de datos para obtener las personas y sus provincias
                $query_personas = "SELECT p.id_persona, p.dni, p.nombres, p.ape_paterno, p.ape_materno, p.telefono, p.genero, p.correo, p.pasaporte, pr.provincia, p.fk_id_procedencia 
                                   FROM personas p 
                                   LEFT JOIN procedencia pr ON p.fk_id_procedencia = pr.id_procedencia";
                $result_personas = $conn->query($query_personas);

                if ($result_personas->num_rows > 0) {
                    while ($row = $result_personas->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id_persona'] . "</td>";
                        echo "<td>" . $row['dni'] . "</td>";
                        echo "<td>" . $row['nombres'] . "</td>";
                        echo "<td>" . $row['ape_paterno'] . "</td>";
                        echo "<td>" . $row['ape_materno'] . "</td>";
                        echo "<td>" . $row['telefono'] . "</td>";
                        echo "<td>" . $row['genero'] . "</td>";
                        echo "<td>" . $row['correo'] . "</td>";
                        echo "<td>" . $row['pasaporte'] . "</td>";
                        echo "<td>" . $row['provincia'] . "</td>";
                        echo '<td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editarPersonaModal" data-id="' . $row['id_persona'] . '" data-dni="' . $row['dni'] . '" data-nombres="' . $row['nombres'] . '" data-ape_paterno="' . $row['ape_paterno'] . '" data-ape_materno="' . $row['ape_materno'] . '" data-telefono="' . $row['telefono'] . '" data-genero="' . $row['genero'] . '" data-correo="' . $row['correo'] . '" data-pasaporte="' . $row['pasaporte'] . '" data-fk_id_procedencia="' . $row['fk_id_procedencia'] . '">Editar</a>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarPersonaModal" data-id="' . $row['id_persona'] . '">Eliminar</button>
                          </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11' class='text-center'>No hay personas registradas</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para crear persona -->
    <div class="modal fade" id="crearPersonaModal" tabindex="-1" aria-labelledby="crearPersonaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearPersonaModalLabel">Crear Nueva Persona</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action=""> 
                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombres" class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="nombres" name="nombres" required>
                        </div>
                        <div class="mb-3">
                            <label for="ape_paterno" class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control" id="ape_paterno" name="ape_paterno" required>
                        </div>
                        <div class="mb-3">
                            <label for="ape_materno" class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control" id="ape_materno" name="ape_materno" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="genero" class="form-label">Género</label>
                            <select class="form-select" id="genero" name="genero" required>
                                <option value="">Seleccione</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="pasaporte" class="form-label">Pasaporte</label>
                            <input type="text" class="form-control" id="pasaporte" name="pasaporte">
                        </div>
                        <div class="mb-3">
                            <label for="fk_id_procedencia" class="form-label">Procedencia</label>
                            <select class="form-select" id="fk_id_procedencia" name="fk_id_procedencia" required>
                                <option value="">Seleccione</option>
                                <?php
                                // Consultar las provincias para el select
                                $query_procedencia = "SELECT id_procedencia, provincia FROM procedencia";
                                $result_procedencia = $conn->query($query_procedencia);
                                if ($result_procedencia->num_rows > 0) {
                                    while ($row = $result_procedencia->fetch_assoc()) {
                                        echo "<option value='" . $row['id_procedencia'] . "'>" . $row['provincia'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar persona -->
    <div class="modal fade" id="editarPersonaModal" tabindex="-1" aria-labelledby="editarPersonaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarPersonaModalLabel">Editar Persona</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" id="id_persona" name="id_persona" required>
                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="dni_edit" name="dni" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombres" class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="nombres_edit" name="nombres" required>
                        </div>
                        <div class="mb-3">
                            <label for="ape_paterno" class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control" id="ape_paterno_edit" name="ape_paterno" required>
                        </div>
                        <div class="mb-3">
                            <label for="ape_materno" class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control" id="ape_materno_edit" name="ape_materno" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono_edit" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label for="genero" class="form-label">Género</label>
                            <select class="form-select" id="genero_edit" name="genero" required>
                                <option value="">Seleccione</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo_edit" name="correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="pasaporte" class="form-label">Pasaporte</label>
                            <input type="text" class="form-control" id="pasaporte_edit" name="pasaporte">
                        </div>
                        <div class="mb-3">
                            <label for="fk_id_procedencia" class="form-label">Procedencia</label>
                            <select class="form-select" id="fk_id_procedencia_edit" name="fk_id_procedencia" required>
                                <option value="">Seleccione</option>
                                <?php
                                // Consultar las provincias para el select
                                $query_procedencia = "SELECT id_procedencia, provincia FROM procedencia";
                                $result_procedencia = $conn->query($query_procedencia);
                                if ($result_procedencia->num_rows > 0) {
                                    while ($row = $result_procedencia->fetch_assoc()) {
                                        echo "<option value='" . $row['id_procedencia'] . "'>" . $row['provincia'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar persona -->
    <div class="modal fade" id="eliminarPersonaModal" tabindex="-1" aria-labelledby="eliminarPersonaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminarPersonaModalLabel">Eliminar Persona</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <p>¿Está seguro que desea eliminar esta persona?</p>
                        <input type="hidden" id="delete_id" name="delete_id" required>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para llenar el modal de edición con los datos de la persona seleccionada
        const editarModal = document.getElementById('editarPersonaModal');
        editarModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget; // Botón que disparó el modal
            const id_persona = button.getAttribute('data-id');
            const dni = button.getAttribute('data-dni');
            const nombres = button.getAttribute('data-nombres');
            const ape_paterno = button.getAttribute('data-ape_paterno');
            const ape_materno = button.getAttribute('data-ape_materno');
            const telefono = button.getAttribute('data-telefono');
            const genero = button.getAttribute('data-genero');
            const correo = button.getAttribute('data-correo');
            const pasaporte = button.getAttribute('data-pasaporte');
            const fk_id_procedencia = button.getAttribute('data-fk_id_procedencia');

            // Actualizar el modal con los valores existentes
            const modalId = editarModal.querySelector('#id_persona');
            const modalDni = editarModal.querySelector('#dni_edit');
            const modalNombres = editarModal.querySelector('#nombres_edit');
            const modalApePaterno = editarModal.querySelector('#ape_paterno_edit');
            const modalApeMaterno = editarModal.querySelector('#ape_materno_edit');
            const modalTelefono = editarModal.querySelector('#telefono_edit');
            const modalGenero = editarModal.querySelector('#genero_edit');
            const modalCorreo = editarModal.querySelector('#correo_edit');
            const modalPasaporte = editarModal.querySelector('#pasaporte_edit');
            const modalProcedencia = editarModal.querySelector('#fk_id_procedencia_edit');

            modalId.value = id_persona;
            modalDni.value = dni;
            modalNombres.value = nombres;
            modalApePaterno.value = ape_paterno;
            modalApeMaterno.value = ape_materno;
            modalTelefono.value = telefono;
            modalGenero.value = genero;
            modalCorreo.value = correo;
            modalPasaporte.value = pasaporte;
            modalProcedencia.value = fk_id_procedencia;
        });

        // Script para llenar el modal de eliminación con el id de la persona seleccionada
        const eliminarModal = document.getElementById('eliminarPersonaModal');
        eliminarModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget; // Botón que disparó el modal
            const delete_id = button.getAttribute('data-id');

            // Actualizar el modal con el id de eliminación
            const modalDeleteId = eliminarModal.querySelector('#delete_id');
            modalDeleteId.value = delete_id;
        });
    </script>
</body>

</html>
