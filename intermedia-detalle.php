<?php
session_start();
require_once "conexion.php";
if (empty($_SESSION['Id_Usuario'])) { http_response_code(403); exit("No autorizado"); }

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($conexion, 'utf8mb4');

$type        = isset($_GET['type']) ? strtolower(trim($_GET['type'])) : '';
$id_agenda   = isset($_GET['id_agenda']) ? (int)$_GET['id_agenda'] : 0;
$id_curso    = isset($_GET['id_curso'])  ? (int)$_GET['id_curso']  : 0;

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

try {
  if ($type === 'taller') {
    if ($id_agenda <= 0) throw new RuntimeException("Falta id_agenda");
    $sql = "SELECT i.fecha, a.id_alumna, CONCAT(a.nombre,' ',a.apat,' ',a.amat) AS alumna
            FROM intermedia_A i
            JOIN alumnas a ON a.id_alumna = i.id_alumna
            WHERE i.id_agenda = ?
            ORDER BY i.fecha DESC, alumna ASC";
    $st = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($st, "i", $id_agenda);

  } elseif ($type === 'curso') {
    if ($id_curso <= 0) throw new RuntimeException("Falta id_curso");
    $sql = "SELECT i.fecha, a.id_alumna, CONCAT(a.nombre,' ',a.apat,' ',a.amat) AS alumna
            FROM intermedia_A i
            JOIN alumnas a ON a.id_alumna = i.id_alumna
            WHERE i.id_curso = ?
            ORDER BY i.fecha DESC, alumna ASC";
    $st = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($st, "i", $id_curso);

  } else {
    throw new RuntimeException("Parámetros inválidos.");
  }

  mysqli_stmt_execute($st);
  $res = mysqli_stmt_get_result($st);

  ob_start(); ?>
  <div style="overflow:auto; max-height:60vh;">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th style="text-align:left; padding:8px; border-bottom:1px solid #e5e7eb;">ID Alumna</th>
          <th style="text-align:left; padding:8px; border-bottom:1px solid #e5e7eb;">Nombre</th>
          <th style="text-align:left; padding:8px; border-bottom:1px solid #e5e7eb;">Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($res) === 0): ?>
          <tr><td colspan="3" style="padding:10px; color:#6b7280;">Sin registros.</td></tr>
        <?php else: while($row = mysqli_fetch_assoc($res)): ?>
          <tr>
            <td style="padding:8px; border-bottom:1px solid #f3f4f6;"><?php echo (int)$row['id_alumna']; ?></td>
            <td style="padding:8px; border-bottom:1px solid #f3f4f6;"><?php echo h($row['alumna']); ?></td>
            <td style="padding:8px; border-bottom:1px solid #f3f4f6;"><?php echo h($row['fecha']); ?></td>
          </tr>
        <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
  <?php
  mysqli_stmt_close($st);
  echo ob_get_clean();

} catch (Throwable $e) {
  http_response_code(400);
  echo '<p class="err" style="color:#b91c1c;">Error: '.h($e->getMessage()).'</p>';
}
