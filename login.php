<?php
session_start();
include_once("conexion.php");

if (!empty($_POST['user']) && !empty($_POST['pass'])) {
    $usuario = trim($_POST['user']);
    $contra  = trim($_POST['pass']);

    $sql = "SELECT Id_Usuario, Nombre, Apat, Amat, Cargo, Pass FROM usuarios WHERE User = ?";
    if ($stmt = mysqli_prepare($conexion, $sql)) {
        mysqli_stmt_bind_param($stmt, 's', $usuario);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res && $row = mysqli_fetch_assoc($res)) {
            $stored = $row['Pass'] ?? '';

            // Caso 1: parece un hash bcrypt válido (ej. $2y$...)
            if (preg_match('/^\$2[aby]\$/', $stored) && strlen($stored) >= 60) {
                if (password_verify($contra, $stored)) {
                    // actualizar hash si hace falta (algoritmo/params cambiaron)
                    if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
                        $newHash = password_hash($contra, PASSWORD_DEFAULT);
                        $upd = mysqli_prepare($conexion, "UPDATE usuarios SET Pass = ? WHERE Id_Usuario = ?");
                        mysqli_stmt_bind_param($upd, 'si', $newHash, $row['Id_Usuario']);
                        mysqli_stmt_execute($upd);
                    }

                    // iniciar sesión
                    $_SESSION['Id_Usuario']  = $row['Id_Usuario'];
                    $_SESSION['Cargo']       = $row['Cargo'];
                    $_SESSION['nombre']      = $row['Nombre'];
                    $_SESSION['apat']        = $row['Apat'];
                    $_SESSION['amat']        = $row['Amat'];
                    $_SESSION['tipoCliente'] = "";
                    header('Location: main.php'); exit;
                } else {
                    echo "<script>alert('Contraseña incorrecta'); window.history.go(-1);</script>";
                    exit;
                }

            // Caso 2: no parece hash bcrypt -> puede ser texto plano (legacy) o hash truncado
            } else {
                // Si coincide exactamente con lo guardado -> era texto plano -> rehasheamos y actualizamos
                if ($contra === $stored && $stored !== '') {
                    $nuevoHash = password_hash($contra, PASSWORD_DEFAULT);
                    $upd = mysqli_prepare($conexion, "UPDATE usuarios SET Pass = ? WHERE Id_Usuario = ?");
                    mysqli_stmt_bind_param($upd, 'si', $nuevoHash, $row['Id_Usuario']);
                    mysqli_stmt_execute($upd);

                    // iniciar sesión
                    $_SESSION['Id_Usuario']  = $row['Id_Usuario'];
                    $_SESSION['Cargo']       = $row['Cargo'];
                    $_SESSION['nombre']      = $row['Nombre'];
                    $_SESSION['apat']        = $row['Apat'];
                    $_SESSION['amat']        = $row['Amat'];
                    $_SESSION['tipoCliente'] = "";
                    header('Location: main.php'); exit;
                }

                // Si llega aquí: ni password_verify funcionó, ni coincide en texto plano.
                // Probable causa: hash en BD está TRUNCADO (muy frecuente si la columna es demasiado corta)
                // Avisamos al admin/desarrollador para que revise la columna Pass en la BD.
                if (strpos($stored, '$2') === 0 && strlen($stored) < 60) {
                    // hash detectado pero corto -> truncado
                    echo "<script>
                            alert('Error: el hash de la contraseña en la base de datos parece estar truncado. \
                                   Revisa que la columna Pass tenga suficiente longitud (p. ej. VARCHAR(255)).');
                            window.history.go(-1);
                          </script>";
                    exit;
                }

                // Si no hay stored (vacío) o cualquier otro caso:
                echo "<script>alert('Contraseña incorrecta o formato de contraseña no soportado.'); window.history.go(-1);</script>";
                exit;
            }

        } else {
            echo "<script>alert('Usuario inexistente'); window.history.go(-1);</script>";
            exit;
        }
    } else {
        echo "<script>alert('Error en el servidor. Intente más tarde.'); window.history.go(-1);</script>";
        exit;
    }
} else {
    echo "<script>alert('Favor de llenar todos los datos'); window.history.go(-1);</script>";
    exit;
}
