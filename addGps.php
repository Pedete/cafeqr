<?php require_once('Connections/cafeqr.php'); ?>
<?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if($_POST['idTransaccion'] != "")
{
	$updateSQL = sprintf("UPDATE transacciones SET lon=%s, lat=%s WHERE idTransaccion=%s",
						   GetSQLValueString($_POST['lon'], "text"),
						   GetSQLValueString($_POST['lat'], "text"),
						   GetSQLValueString($_POST['idTransaccion'], "int"));
	
	mysql_select_db($database_cafeqr, $cafeqr);
	$Result1 = mysql_query($updateSQL, $cafeqr) or die(mysql_error());
	
	$respuesta=array("status"=>1,"texto"=>"Posición registrada");
  
}else{
	$respuesta=array("status"=>0,"texto"=>"Error");
}

?>