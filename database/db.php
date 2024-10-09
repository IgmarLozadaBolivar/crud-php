<?php

    # Conexión a la base de datos
    function conectar_database()
    {
        $host = 'localhost';
        $dbname = 'database-php';
        $user = 'root';
        $pass = '1122809631';
        
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;","$user","$pass");
        return $pdo;
    }

    # Verificar datos antes de enviar a la base de datos
    function verificar_datos($filtro, $cadena)
    {
        if(preg_match("/^".$filtro."$/", $cadena))
        {
            return false;
        } else
        {
            return true;
        }
    }

    # Limpiar cadenas de texto
    function limpiar_cadenas($cadena)
    {
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        $cadena = str_ireplace("<script>", "", $cadena );
        $cadena = str_ireplace("</script>", "", $cadena );
        $cadena = str_ireplace("<script src", "", $cadena);
		$cadena = str_ireplace("<script type=", "", $cadena);
		$cadena = str_ireplace("SELECT * FROM", "", $cadena);
		$cadena = str_ireplace("DELETE FROM", "", $cadena);
		$cadena = str_ireplace("INSERT INTO", "", $cadena);
		$cadena = str_ireplace("DROP TABLE", "", $cadena);
		$cadena = str_ireplace("DROP DATABASE", "", $cadena);
		$cadena = str_ireplace("TRUNCATE TABLE", "", $cadena);
		$cadena = str_ireplace("SHOW TABLES;", "", $cadena);
		$cadena = str_ireplace("SHOW DATABASES;", "", $cadena);
		$cadena = str_ireplace("<?php", "", $cadena);
		$cadena = str_ireplace("?>", "", $cadena);
		$cadena = str_ireplace("--", "", $cadena);
		$cadena = str_ireplace("^", "", $cadena);
		$cadena = str_ireplace("<", "", $cadena);
		$cadena = str_ireplace("[", "", $cadena);
		$cadena = str_ireplace("]", "", $cadena);
		$cadena = str_ireplace("==", "", $cadena);
		$cadena = str_ireplace(";", "", $cadena);
		$cadena = str_ireplace("::", "", $cadena);
		$cadena = trim($cadena);
		$cadena = stripslashes($cadena);
		return $cadena;
    }

?>