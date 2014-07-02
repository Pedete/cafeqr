<?php require_once('Connections/cafeqr.php'); ?>
<?php
include ("funciones/funciones.php");
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

$X_recuperarPass = "-1";
if (isset($_GET['email'])) {
  $X_recuperarPass = $_GET['email'];
}
mysql_select_db($database_cafeqr, $cafeqr);
$query_recuperarPass = sprintf("SELECT * FROM clientes WHERE clientes.email=%s", GetSQLValueString($X_recuperarPass, "text"));
$recuperarPass = mysql_query($query_recuperarPass, $cafeqr) or die(mysql_error());
$row_recuperarPass = mysql_fetch_assoc($recuperarPass);
$totalRows_recuperarPass = mysql_num_rows($recuperarPass);

if($_GET['email'] == "")
{
		$respuesta=array("status"=>0,"texto"=>"Email vacio");
}
else if($row_recuperarPass['email']==$_GET['email'])
{
	recuperarPass($_GET['email']);
	$respuesta=array("status"=>1);
}
else
{
	$respuesta=array("status"=>0);
	
}

echo json_encode($respuesta);

mysql_free_result($recuperarPass);
?>
