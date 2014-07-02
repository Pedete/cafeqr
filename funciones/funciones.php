<?php
function enviarEmail($emailCliente){
	
		// Varios destinatarios
		$para  = $emailCliente;
		
		// título
		$título = 'Valida tu cuenta de email para cafeQR';
		$email = base64_encode($emailCliente);
		// mensaje
		$mensaje = '
			<h2>Valida tu email para CAFEQR</h2>
			<div>Haz clic en el link de abajo para validar tu cuenta</div>
			<a href="http://www.ideasactivas.net/cafeqr/servicios/web/verificarEmail.php?e='.$email.'">Valida tu cuenta</a>
		
		';
		
		// Para enviar un correo HTML, debe establecerse la cabecera Content-type
		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Cabeceras adicionales
		$cabeceras .= 'To:'.$emailCliente. "\r\n";
		$cabeceras .= 'From: no-responda@ideasactivas.net' . "\r\n";
		//$cabeceras .= 'Cc: ' . "\r\n";
		//$cabeceras .= 'Bcc: birthdaycheck@example.com' . "\r\n";
		
		// Enviarlo
		mail($para, $título, $mensaje, $cabeceras);
			
	
}
function verificarEmail($email){ 
  if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$email))
  { 
      return "false"; 
  } else { 
       return "true"; 
  } 
}

function recuperarPass($emailCliente){
	
		// Varios destinatarios
		$para  = $emailCliente;
		
		// título
		$título = 'Recuperacón de contraseña';
		$email = base64_encode($emailCliente);
		// mensaje
		$mensaje = '
			<h2>Recuperación de contraseña para cafeQR</h2>
			<div>Haz clic en el link de abajo para recuperar tu contraseña</div>
			<a href="http://www.ideasactivas.net/cafeqr/servicios/web/nuevaPass.php?e='.$email.'">Recupera tu contraseña</a>
		';
		
		// Para enviar un correo HTML, debe establecerse la cabecera Content-type
		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Cabeceras adicionales
		$cabeceras .= 'To:'.$emailCliente. "\r\n";
		$cabeceras .= 'From: no-responda@ideasactivas.net' . "\r\n";
		//$cabeceras .= 'Cc: ' . "\r\n";
		//$cabeceras .= 'Bcc: birthdaycheck@example.com' . "\r\n";
		
		// Enviarlo
		mail($para, $título, $mensaje, $cabeceras);
}

?>