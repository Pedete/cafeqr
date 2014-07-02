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

  $emailOk = base64_decode($_GET['e']);
  
  $updateSQL = sprintf("UPDATE clientes SET activo=1 WHERE email=%s",
                       GetSQLValueString($emailOk, "text"));

  mysql_select_db($database_cafeqr, $cafeqr);
  $Result1 = mysql_query($updateSQL, $cafeqr) or die(mysql_error());

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Validacion de cuenta CafeQR</title>
</head>

<body>
<h2>Has validado tu email correctamente</h2>
<div>Ya puedes logearte correctamente en la app</div>
</body>
</html>