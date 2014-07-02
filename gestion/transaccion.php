<?php require_once('../Connections/cafeqr.php'); ?>
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

$colname_clientes = "-1";
if (isset($_POST['token'])) {
  $colname_clientes = $_POST['token'];
}
mysql_select_db($database_cafeqr, $cafeqr);
$query_clientes = sprintf("SELECT * FROM clientes WHERE token = %s", GetSQLValueString($colname_clientes, "text"));
$clientes = mysql_query($query_clientes, $cafeqr) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);





if($row_clientes['idCliente']!='')
	{
	
	mysql_select_db($database_cafeqr, $cafeqr);
	$query_saldo = "SELECT sum( transacciones.transaccion) as saldo FROM transacciones WHERE idCliente = ".$row_clientes['idCliente']."";
	$saldo = mysql_query($query_saldo, $cafeqr) or die(mysql_error());
	$row_saldo = mysql_fetch_assoc($saldo);
	$totalRows_saldo = mysql_num_rows($saldo);
	

		if($_POST['transaccion'] < 0){
			$t = $_POST['transaccion'] * -1;
		}else{
			$t = $row_saldo["saldo"];
		}
		if($row_saldo["saldo"] >= $t){
		$saldo = $row_saldo["saldo"] + $_POST['transaccion'];
		
		@mysql_free_result($saldo);
		
	$insertSQL = sprintf("INSERT INTO transacciones (idCliente, transaccion, idEmpleado, idTipoTransaccion) VALUES (%s, %s, %s, %s)",
						   GetSQLValueString($row_clientes['idCliente'], "int"),
						   GetSQLValueString($_POST['transaccion'], "int"),
						   GetSQLValueString($_POST['idEmpleado'], "int"),
						   GetSQLValueString($_POST['tipoTransaccion'], "int"));
	
	  mysql_select_db($database_cafeqr, $cafeqr);
	  $Result1 = mysql_query($insertSQL, $cafeqr) or die(mysql_error());
	  
	  $respuesta=array("status"=>1,"texto"=>"Transacción realizada correctamente","saldo"=>$saldo,"idCliente"=>$row_clientes['idCliente']);
	  
	  
	  
	}else{
		$respuesta=array("status"=>2,"texto"=>"Saldo insuficiente");
		
	}
	
	}
	
else
	{
		$respuesta=array("status"=>0,"texto"=>"Transacción errónea");
	}


echo json_encode($respuesta);


mysql_free_result($clientes);



?>