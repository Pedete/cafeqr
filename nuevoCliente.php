<?php require_once('Connections/cafeqr.php'); ?>
<?php
include ("funciones/funciones.php");
//header('Content-Type: application/json');
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

$X_nuevoCliente = "-1";
if (isset($_POST['email'])) {
  $X_nuevoCliente = $_POST['email'];
}
mysql_select_db($database_cafeqr, $cafeqr);
$query_nuevoCliente = sprintf("SELECT clientes.email FROM clientes WHERE clientes.email = %s", GetSQLValueString($X_nuevoCliente, "text"));
$nuevoCliente = mysql_query($query_nuevoCliente, $cafeqr) or die(mysql_error());
$row_nuevoCliente = mysql_fetch_assoc($nuevoCliente);
$totalRows_nuevoCliente = mysql_num_rows($nuevoCliente);

//verificamos email
$email  = verificarEmail($_POST['email']);


$edad =  edad();

if($_POST['email'] == ""){
		$respuesta=array("status"=>2,"texto"=>"Email vacio");
}
else if( $edad <18){
	
	$respuesta=array("status"=>3,"texto"=>"No tienes 18 años");
}
else if($row_nuevoCliente['email']=='' &&  $email == "true" && $edad >=18 )
	{
		
		$insertSQL = sprintf("INSERT INTO clientes (nombre, apellidos, email, pass, dispositivo, sexo, fechaNacimiento) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['apellidos'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString(md5($_POST['pass']), "text"),
					   GetSQLValueString($_POST['dispositivo'], "text"),
					   GetSQLValueString($_POST['sexo'], "text"),
					   GetSQLValueString($_POST['fechaNacimiento'], "date"));

  		mysql_select_db($database_cafeqr, $cafeqr);
  		$Result1 = mysql_query($insertSQL, $cafeqr) or die(mysql_error());
  
  		enviarEmail($_POST['email']);
		$respuesta=array("status"=>1,"texto"=>"Cliente guardado correctamente");
		}
		
else if($email == "false"){
	$respuesta=array("status"=>3,"texto"=>"Email no válido");
}
else
	{
		$respuesta=array("status"=>0,"texto"=>"Cliente ya existe");
	}

echo json_encode($respuesta);

mysql_free_result($nuevoCliente);

function edad (){
	$dia=date(j);
	$mes=date(n);
	$ano=date(Y);
 
//fecha de nacimiento
 $fecha = explode("-",$_POST["fechaNacimiento"]);
$dianaz=$fecha[2];
$mesnaz=$fecha[1];
$anonaz=$fecha[0];
 
//si el mes es el mismo pero el día inferior aun no ha cumplido años, le quitaremos un año al actual
 
if (($mesnaz == $mes) && ($dianaz > $dia)) {
$ano=($ano-1); }
 
//si el mes es superior al actual tampoco habrá cumplido años, por eso le quitamos un año al actual
 
if ($mesnaz > $mes) {
$ano=($ano-1);}
 
//ya no habría mas condiciones, ahora simplemente restamos los años y mostramos el resultado como su edad
 
$edad=($ano-$anonaz);
 
return $edad;
	
}
?>
