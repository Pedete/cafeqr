<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_cafeqr = "localhost";
$database_cafeqr = "cafeqr";
$username_cafeqr = "root";
$password_cafeqr = "";
@$cafeqr = mysql_pconnect($hostname_cafeqr, $username_cafeqr, $password_cafeqr) or trigger_error(mysql_error(),E_USER_ERROR); 
?>