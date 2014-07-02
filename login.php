<?php require_once('Connections/cafeqr.php'); ?>
<?php
header('Content-Type: application/json');
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$X_login = "-1";
if (isset($_POST['email'])) {
  $X_login = $_POST['email'];
}
$Y_login = "-1";
if (isset($_POST['pass'])) {
  $Y_login = $_POST['pass'];
}
mysql_select_db($database_cafeqr, $cafeqr);
$query_login = sprintf("SELECT * FROM clientes WHERE clientes.email=%s AND clientes.pass=%s", GetSQLValueString($X_login, "text"),GetSQLValueString(md5($Y_login), "text"));
$login = mysql_query($query_login, $cafeqr) or die(mysql_error());
$row_login = mysql_fetch_assoc($login);
$totalRows_login = mysql_num_rows($login);

	if($row_login['email']!='' && $row_login['activo'] == 1)
		{
			$respuesta=array("status"=>1,"datos"=>array("idCliente"=>$row_login['idCliente'],"nombre"=>$row_login['nombre'],"apellidos"=>$row_login['apellidos'],"email"=>$row_login['email']));
		}
		else if($row_login['email']!='' && $row_login['activo'] == 0)
		{
			$respuesta=array("status"=>2,"texto"=>"No has validado el correo");
		}
		else
		{
			$respuesta=array("status"=>0,"texto"=>"Login incorrecto");
		}
		
echo json_encode ($respuesta);		
mysql_free_result($login);
?>
