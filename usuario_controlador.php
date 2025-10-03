<?php
session_start();
include_once("conexion.php");

// Redirigir si no hay sesión
if (empty($_SESSION['Id_Usuario'])) {
    header("location: index.html");
    exit;
}

// Normalizar variables de sesión
$Id_Usuario = $_SESSION['Id_Usuario'] ?? $_SESSION['id_usuario'] ?? null;
$Nombre     = $_SESSION['nombre']     ?? $_SESSION['Nombre'] ?? '';
$Apat       = $_SESSION['apat']       ?? $_SESSION['Apat']   ?? '';
$Amat       = $_SESSION['amat']       ?? $_SESSION['Amat']   ?? '';
$Cargo      = $_SESSION['Cargo']      ?? $_SESSION['cargo']  ?? '';

mysqli_set_charset($conexion, 'utf8mb4');

// Helpers
function e($s){ return htmlspecialchars((string)($s ?? ''), ENT_QUOTES, 'UTF-8'); }
function post($k,$d=null){ return $_POST[$k] ?? $d; }
function is_post(){ return ($_SERVER['REQUEST_METHOD'] === 'POST'); }

if (is_post()) {
    $action = post('action','');
    $redirect = post('redirect','alumnas-maestras.php');

    // ==================== ACCIONES ====================

    // Activar registros
    if ($action === 'activate_alumna') {
        $id = intval(post('id_alumna'));
        $res = mysqli_query($conexion, "UPDATE alumnas SET estatus = 1 WHERE id_alumna = $id");
        $_SESSION['flash_'.($res?'ok':'err')] = $res ? 'Alumna activada.' : 'Error: '.mysqli_error($conexion);
        header("Location: $redirect"); exit;
    }

    if ($action === 'activate_maestra') {
        $id = intval(post('id_maestra'));
        $res = mysqli_query($conexion, "UPDATE maestras SET estatus = 1 WHERE id_maestra = $id");
        $_SESSION['flash_'.($res?'ok':'err')] = $res ? 'Maestra activada.' : 'Error: '.mysqli_error($conexion);
        header("Location: $redirect"); exit;
    }

    if ($action === 'activate_cliente') {
        $id = intval(post('id_cliente'));
        $res = mysqli_query($conexion, "UPDATE clientes SET estatus = 1 WHERE id_cliente = $id");
        $_SESSION['flash_'.($res?'ok':'err')] = $res ? 'Cliente activado.' : 'Error: '.mysqli_error($conexion);
        header("Location: $redirect"); exit;
    }

    // ==================== ELIMINAR ====================
    if ($action === 'delete_usuario' && strcasecmp(trim($Cargo),'Admin')===0) {
        $id = intval(post('id'));
        $res = mysqli_query($conexion, "DELETE FROM usuarios WHERE Id_Usuario = $id");
        header('Content-Type: application/json');
        echo json_encode(['success' => $res ? true : false]);
        exit;
    }

    if ($action === 'delete_cliente') {
        $id = intval(post('id'));
        $res = mysqli_query($conexion, "UPDATE clientes SET estatus = 0 WHERE id_cliente = $id");
        header('Content-Type: application/json');
        echo json_encode(['success' => $res ? true : false]);
        exit;
    }

    if ($action === 'delete_maestra') {
        $id = intval(post('id'));
        $res = mysqli_query($conexion, "UPDATE maestras SET estatus = 0 WHERE id_maestra = $id");
        header('Content-Type: application/json');
        echo json_encode(['success' => $res ? true : false]);
        exit;
    }

    if ($action === 'delete_alumna') {
        $id = intval(post('id'));
        $res = mysqli_query($conexion, "UPDATE alumnas SET estatus = 0 WHERE id_alumna = $id");
        header('Content-Type: application/json');
        echo json_encode(['success' => $res ? true : false]);
        exit;
    }

    // ==================== EDITAR ====================
    if ($action === 'edit_usuario' && strcasecmp(trim($Cargo),'Admin')===0) {
        $id = intval(post('Id_Usuario'));
        $sql = "UPDATE usuarios 
                SET Nombre=?, Apat=?, Amat=?, Correo=?, User=?, Cargo=?"
                . (post('Pass') !== '' ? ", Pass=? " : " ")
                . "WHERE Id_Usuario=?";
        if (post('Pass') !== '') {
            $pass = password_hash(post('Pass'), PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "sssssssi",
                post('Nombre'), post('Apat'), post('Amat'),
                post('Correo'), post('User'), post('Cargo'),
                $pass, $id
            );
        } else {
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssi",
                post('Nombre'), post('Apat'), post('Amat'),
                post('Correo'), post('User'), post('Cargo'),
                $id
            );
        }
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Usuario actualizado.' : 'Error: '.mysqli_error($conexion);
        header("Location: $redirect"); exit;
    }

    if ($action === 'edit_cliente') {
        $id = intval(post('id_cliente'));
        $sql = "UPDATE clientes SET nombre=?, apat=?, amat=?, correo=?, telefono=?, direccion=? WHERE id_cliente=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssi",
            post('nombre'), post('apat'), post('amat'),
            post('correo'), post('telefono'), post('direccion'),
            $id
        );
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Cliente actualizado.' : 'Error: '.mysqli_error($conexion);
        header("Location: $redirect"); exit;
    }

    if ($action === 'edit_maestra') {
        $id = intval(post('id_maestra'));
        $sql = "UPDATE maestras SET nombre=?, base=?, acuerdo=?, gastos=?, porcentaje_ganancia=? WHERE id_maestra=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sssddi",
            post('nombre'), post('base'), post('acuerdo'),
            (post('gastos')===''?null:floatval(post('gastos'))),
            (post('porcentaje_ganancia')===''?null:floatval(post('porcentaje_ganancia'))),
            $id
        );
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Maestra actualizada.' : 'Error: '.mysqli_error($conexion);
        header("Location: $redirect"); exit;
    }

    if ($action === 'edit_alumna') {
        $id = intval(post('id_alumna'));
        $sql = "UPDATE alumnas SET nombre=?, apat=?, amat=?, telefono=?, correo=?, direccion=?, descuento_aplicado=?, tipo_descuento=? WHERE id_alumna=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sssssisisi",
            post('nombre'), post('apat'), post('amat'),
            post('telefono'), post('correo'), post('direccion'),
            intval(post('descuento_aplicado',0)), post('tipo_descuento'),
            $id
        );
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Alumna actualizada.' : 'Error: '.mysqli_error($conexion);
        header("Location: $redirect"); exit;
    }

    // ==================== AGREGAR ====================
    if ($action === 'add_alumna') {
        $sql = "INSERT INTO alumnas (nombre, apat, amat, telefono, correo, direccion, descuento_aplicado, tipo_descuento) VALUES (?,?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conexion, $sql);
        $desc_ap = intval(post('descuento_aplicado',0));
        mysqli_stmt_bind_param($stmt, "ssssssis",
            post('nombre'), post('apat'), post('amat'),
            post('telefono'), post('correo'), post('direccion'),
            $desc_ap, post('tipo_descuento')
        );
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Alumna agregada.' : 'Error: '.mysqli_error($conexion);
        header("Location: $redirect"); exit;
    }

    if ($action === 'add_maestra') {
        $sql = "INSERT INTO maestras (nombre, base, acuerdo, gastos, porcentaje_ganancia) VALUES (?,?,?,?,?)";
        $stmt = mysqli_prepare($conexion, $sql);
        $gastos = (post('gastos','')===''?null:floatval(post('gastos')));
        $porc = (post('porcentaje_ganancia','')===''?null:floatval(post('porcentaje_ganancia')));
        mysqli_stmt_bind_param($stmt, "sssdd",
            post('nombre'), post('base'), post('acuerdo'),
            $gastos, $porc
        );
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Maestra agregada.' : 'Error: '.mysqli_error($conexion);
        header("Location: $redirect"); exit;
    }

    if ($action === 'add_cliente') {
        $sql = "INSERT INTO clientes (nombre, apat, amat, correo, telefono, direccion) VALUES (?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss",
            post('nombre'), post('apat'), post('amat'),
            post('correo'), post('telefono'), post('direccion')
        );
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Cliente agregado.' : 'Error: '.mysqli_error($conexion);
        header("Location: $redirect"); exit;
    }

    if ($action === 'add_usuario' && strcasecmp(trim($Cargo),'Admin')===0) {
        $sql = "INSERT INTO usuarios (Nombre, Apat, Amat, Correo, User, Pass, Cargo) VALUES (?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conexion, $sql);
        $pass = password_hash(post('Pass'), PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "sssssss",
            post('Nombre'), post('Apat'), post('Amat'),
            post('Correo'), post('User'), $pass, post('Cargo')
        );
        $ok = mysqli_stmt_execute($stmt);
        $_SESSION['flash_'.($ok?'ok':'err')] = $ok ? 'Usuario agregado.' : 'Error: '.mysqli_error($conexion);
        header("Location: $redirect"); exit;
    }
}

// ==================== LISTADOS ====================
$mostrar_alumnas  = isset($_GET['alumnas'])  ? intval($_GET['alumnas'])  : 1;
$mostrar_maestras = isset($_GET['maestras']) ? intval($_GET['maestras']) : 1;
$mostrar_clientes = isset($_GET['clientes']) ? intval($_GET['clientes']) : 1;

$sqlAlumnas  = "SELECT id_alumna, nombre, apat, amat, telefono, correo, direccion, descuento_aplicado, tipo_descuento, estatus FROM alumnas WHERE estatus=$mostrar_alumnas ORDER BY id_alumna ASC";
$sqlMaestras = "SELECT id_maestra, nombre, base, acuerdo, gastos, porcentaje_ganancia, estatus FROM maestras WHERE estatus=$mostrar_maestras ORDER BY id_maestra ASC";
$sqlClientes = "SELECT * FROM clientes WHERE estatus=$mostrar_clientes ORDER BY id_cliente ASC";
$sqlUsuarios = "SELECT * FROM usuarios";

$alumnas  = mysqli_query($conexion, $sqlAlumnas);
$maestras = mysqli_query($conexion, $sqlMaestras);
$clientes = mysqli_query($conexion, $sqlClientes);
$usuarios = mysqli_query($conexion, $sqlUsuarios);
?>
