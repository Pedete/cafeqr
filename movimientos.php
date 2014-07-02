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

$colname_movimientos = "-1";
if (isset($_POST['idCliente'])) {
  $colname_movimientos = $_POST['idCliente'];
}


if($row_cliente['idCliente']!='')
{
		mysql_select_db($database_cafeqr, $cafeqr);
		$query_movimientos = sprintf("SELECT * FROM transacciones WHERE idCliente = %s ORDER BY fechaTransaccion DESC LIMIT %s,%s", 
			GetSQLValueString($colname_movimientos, "int"),
			GetSQLValueString($_POST["desde"], "int"),
			GetSQLValueString($_POST["hasta"], "int"));
		$movimientos = mysql_query($query_movimientos, $cafeqr) or die(mysql_error());
		$row_movimientos = mysql_fetch_assoc($movimientos);
		$totalRows_movimientos = mysql_num_rows($movimientos);
		
	
		if($totalRows_movimientos == 0){
			$respuesta = array("status"=>0,"texto"=>"No hay movimientos");
		}else{
			$i=0;
			do{
				if($row_movimientos["transaccion"] > 0){
					$tipo = "Recarga";	
				}else if($row_movimientos["transaccion"] < 0){
					$tipo = "Consumicion";	
				}
				
				$datos[$i]= array("transaccion"=>$row_movimientos["transaccion"],"fecha"=>$row_movimientos["fechaTransaccion"],"tipo"=>$tipo);
				$i++;
				
			}while($row_movimientos = mysql_fetch_assoc($movimientos));
			$respuesta = array("status"=>1,"limitend"=>$_POST["hasta"],"datos"=>$datos);
		}
		
}
echo json_encode($respuesta);


mysql_free_result($cliente);

mysql_free_result($movimientos);
?>
