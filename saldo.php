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

$colname_cliente = "-1";
if (isset($_POST['idCliente'])) {
  $colname_cliente = $_POST['idCliente'];
}
mysql_select_db($database_cafeqr, $cafeqr);
$query_cliente = sprintf("SELECT * FROM clientes WHERE idCliente = %s", 
	GetSQLValueString($colname_cliente, "int"));
$cliente = mysql_query($query_cliente, $cafeqr) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

$colname_saldo = "-1";
if (isset($_POST['idCliente'])) {
  $colname_saldo = $_POST['idCliente'];
}
mysql_select_db($database_cafeqr, $cafeqr);
$query_saldo = sprintf("SELECT sum(transacciones.transaccion) as Saldo FROM transacciones WHERE idCliente = %s", GetSQLValueString($colname_saldo, "int"));
$saldo = mysql_query($query_saldo, $cafeqr) or die(mysql_error());
$row_saldo = mysql_fetch_assoc($saldo);
$totalRows_saldo = mysql_num_rows($saldo);


if($row_cliente['idCliente'] == ""){
	$respuesta = array("status"=>0,"texto"=>"Error con el idCliente");
	
}else{
	$token = md5 ((date("U")*$row_cliente['idCliente']));
	$token = substr($token, 3, 6);
	
	
	
	$updateSQL = sprintf("UPDATE clientes SET token=%s WHERE idCliente=%s",
                       GetSQLValueString($token, "text"),
                       GetSQLValueString($_POST['idCliente'], "int"));

 	 mysql_select_db($database_cafeqr, $cafeqr);
  	 $Result1 = mysql_query($updateSQL, $cafeqr) or die(mysql_error());
	
	$respuesta = array("status"=>1,"token"=>$token,"saldo"=>$row_saldo['Saldo']);
}

echo json_encode($respuesta);


mysql_free_result($cliente);

mysql_free_result($saldo);
?>
