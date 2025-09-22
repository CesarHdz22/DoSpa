<?php 
session_start();
include_once("conexion.php");
if(empty($_SESSION['Id_Usuario'])){header("location: index.html");}else{
  $idU = $_SESSION['Id_Usuario'];
  $Nombre = $_SESSION['nombre'];
  $Amat = $_SESSION['amat'];
  $Apat = $_SESSION['apat'];

  $carrito_json = $_POST['carrito'];
  $carrito = json_decode($carrito_json, true);

  // Validación mínima
  if (!is_array($carrito)) {
      echo "Carrito inválido.";
      exit;
  }

  // Guardar en sesión para que trabajes con él desde otras páginas si quieres
  $_SESSION['carrito_temp'] = $carrito;

  // Total (si también enviaste total por seguridad lo puedes tomar de $_POST['total'])
  $total = 0;
  $idProducto = 0;
  foreach ($carrito as $item) {
      $precio = isset($item['precio']) ? floatval($item['precio']) : 0;
      $cant   = isset($item['cantidad']) ? intval($item['cantidad']) : 0;
      $total += $precio * $cant;
      $idProducto = $item['id'];
  }

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DO SPA - Base</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/editarP.css">
  <link rel="icon" href="img/DO_SPA_logo.png" type="image/png">
  <!-- Iconos de FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">
</head>
<body>
  <div class="dashboard">
    <!-- Barra lateral -->
    <aside class="sidebar">
      <div class="logo">DO SPA</div>
      <ul id="menu">
        <li><a href="main.php"><i class="fas fa-home"></i> Panel</a></li>
        <li><a href="ventas.php"><i class="fas fa-file-invoice-dollar"></i> Historial Ventas</a></li>
        <li><a href="inscripciones.php"><i class="fas fa-clipboard-list"></i> Historial Inscripciones</a></li>
        <li><a href="agenda.php"><i class="fas fa-calendar-days"></i> Agenda</a></li>
        <li><a href="talleres-cursos.php"><i class="fas fa-chalkboard-teacher"></i>Talleres/Cursos</a></li> 
        <li><a href="alumnas-maestras.php"><i class="fa-solid fa-users"></i><span>Alumnas / Maestras</span></a></li>
        <li class="active"><a href="inventario.php"><i class="fas fa-layer-group"></i> Inventario</a></li>
      </ul>
    </aside>


    <!-- Contenido principal -->
    <main class="main">
      <!-- Barra superior -->
      <header class="topbar">
        <div class="user-info">
          <span><?php echo $Nombre." ".$Apat." ".$Amat ?></span>
          <a href="muerte.php"><img src="img/logout.png" width="20px"></a>
        </div>
      </header>

      <!-- 🔹 Aquí irá el contenido específico de cada página -->
      <section id="edicion" class="page">
        <h2 style="text-align:center; margin-bottom:20px;">Editar Producto</h2>
        <div class="edicion-wrapper">
            <form action="eProductos.php" method="post" class="edicion-form" onsubmit="return validarProducto()">
            <?php
                $sql = "SELECT * FROM productos WHERE id_producto = '$idProducto'";
                $resultado1 = mysqli_query($conexion,$sql);
                while($mostrar = mysqli_fetch_array($resultado1)){
            ?>
            <input style = "display:none;" type="text" name="id" id="id" value="<?php echo $mostrar['id_producto'] ?>" readonly required>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo $mostrar['nombre'] ?>" required>

            <label for="pu">Precio Unitario:</label>
            <input type="text" name="pu" id="pu" value="<?php echo $mostrar['precio_unitario'] ?>" required>

            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" value="<?php echo $mostrar['Stock'] ?>" required>

            <button type="submit" class="btn-submit">Guardar Cambios</button>
            <?php
                }
            ?>
            </form>
  </div>
</section>
      

      
    </main>
  </div>
  <script src="librerias/tables.js"></script>


</body>
</html>
<script>
    function validarProducto() {
    // Obtener valores
    var nombre = document.getElementById("nombre").value.trim();
    var precio = document.getElementById("pu").value.trim();
    var stock  = document.getElementById("stock").value.trim();

    // Expresiones regulares
    var precioRegex = /^[0-9]+(\.[0-9]{1,2})?$/; 
    var stockRegex  = /^[0-9]+$/;                 

    // Validación Nombre
    if (nombre === "") {
        alert("El nombre no puede estar vacío.");
        document.getElementById("nombre").focus();
        return false;
    }

    // Validación Precio Unitario
    if (precio === "") {
        alert("El precio unitario no puede estar vacío.");
        document.getElementById("pu").focus();
        return false;
    } else if (!precioRegex.test(precio)) {
        alert("El precio unitario solo puede contener números y un punto decimal.");
        document.getElementById("pu").value = "";
        document.getElementById("pu").focus();
        return false;
    }

    // Validación Stock
    if (stock === "") {
        alert("El stock no puede estar vacío.");
        document.getElementById("stock").focus();
        return false;
    } else if (!stockRegex.test(stock)) {
        alert("El stock debe ser un número entero mayor o igual a 0.");
        document.getElementById("stock").value = "";
        document.getElementById("stock").focus();
            return false;
        }

        // Si todo está bien
        return true;
    }
</script>

<?php
}
?>