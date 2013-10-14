<?php
	// Força a codificação
	header("Content-Type: text/html; charset=utf-8");
	
	// Seta o tipo do erro
	error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
	ini_set("display_error", "On");

	// Define path to application directory
	defined("APPLICATION_PATH") || define("APPLICATION_PATH", dirname(__FILE__) . "/application");
	
	// Monta o diretório 
	$root = $_SERVER['DOCUMENT_ROOT'];
	$project = str_replace($root, "", dirname(__FILE__));
	
	function write_php_ini($array, $file) {
		$res = array();
		foreach($array as $key => $val) {
			if(is_array($val)) {
				$res[] = "[$key]";
				foreach($val as $skey => $sval) 
					$res[] = "$skey = " . $sval;
			}
			else {
				$res[] = "$key = " . $sval;
			}
		}
		safefilerewrite($file, implode("\r\n", $res));
	}
	
	//
	function safefilerewrite($fileName, $dataToSave) {
		if ($fp = fopen($fileName, 'w')) {
			$startTime = microtime();
			do {
				$canWrite = flock($fp, LOCK_EX);
				
				// If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
				if(!$canWrite) usleep(round(rand(0, 100)*1000));
			} while ((!$canWrite)and((microtime()-$startTime) < 1000));

			//file was locked so now we can store information
			if ($canWrite) {
				fwrite($fp, $dataToSave);
				flock($fp, LOCK_UN);
			}
			fclose($fp);
		}
	}
?>
<html>
	<head>
		<title>Framework Install</title>
		<style>
			ul li {
				padding-left: 20px;
				height: 16px;
				line-height: 16px;
				
				list-style: none;
				
				margin: 10px 0px;
			}
			li.false {
				background: transparent url(http://cdn1.iconfinder.com/data/icons/splashyIcons/error_fuck.png) no-repeat 0px 0px;
			}
			
			li.true {
				background: transparent url(http://cdn1.iconfinder.com/data/icons/splashyIcons/okay.png) no-repeat 0px 0px;
			}
			
			input.text {
				width: 250px;
				
				-webkit-border-radius: 4px;
				-moz-border-radius: 4px;
				border-radius: 4px;
				
				border: 1px solid #C6C6C6;
				
				padding: 4px 5px;
			}
			
			input:focus {
				outline: none;
			}
			
			input[type="submit"] {
				-webkit-border-radius: 2px;
				-moz-border-radius: 2px;
				border-radius: 2px;
				
				border: 1px solid #a9a9a9;
				
				background-image: linear-gradient(bottom, rgb(222,222,222) 40%, rgb(246,246,246) 50%);
				background-image: -o-linear-gradient(bottom, rgb(222,222,222) 40%, rgb(246,246,246) 50%);
				background-image: -moz-linear-gradient(bottom, rgb(222,222,222) 40%, rgb(246,246,246) 50%);
				background-image: -webkit-linear-gradient(bottom, rgb(222,222,222) 40%, rgb(246,246,246) 50%);
				background-image: -ms-linear-gradient(bottom, rgb(222,222,222) 40%, rgb(246,246,246) 50%);

				background-image: -webkit-gradient(
					linear,
					left bottom,
					left top,
					color-stop(0.4, rgb(222,222,222)),
					color-stop(0.5, rgb(246,246,246))
				);
				
				height: 22px;
			}
		</style>
	</head>
	<body>
	<?php
		// Busca o passo
		$step = $_GET['step'];

		// Verifica o passo
		switch($step) {
			// Finalização do instalador
			case 4:
				echo "
					<h1>Instalação concluida</h1>
					<br>
					
					<h2>Finalização</h2>
					Para finalizar a instalação, é necessario:<br>
					<br>
					1 - Remover a permissão de escrita dos arquivos <strong>.htacces</strong> e <strong>application/config/application.ini</strong><br>
					2 - Apaguar o arquivo <strong>install.php</strong> do diretório do projeto
					
					<h2>Acessos</h2>
					Site: <a href=\"http://localhost" . $project . "\" target=\"_blank\">http://localhost" . $project . "</a><br>
					Painel: <a href=\"http://localhost" . $project . "/user\" target=\"_blank\">http://localhost" . $project . "/user</a><br>
					
					<h2>Painel</h2>
					Usuário: user<br>
					Senha: 123mudar<br>
				";
				break;
			// Passo que configura o banco de dados
			case 3:
				// Cria o arquivo .htaccess
				$htaccess = file_get_contents(APPLICATION_PATH . "/../.htaccess");
				$htaccess = str_replace("RewriteBase /tryumframework/trunk", "RewriteBase " . $project, $htaccess);
				$htaccess = str_replace("SetEnv APPLICATION_ENV trunk", "SetEnv APPLICATION_ENV development", $htaccess);
				file_put_contents(APPLICATION_PATH . "/../.htaccess", $htaccess);
				
				// Le o arquivo .ini
				$application_ini = parse_ini(APPLICATION_PATH . "/configs/application.ini");
				$application_ini['development : production']['tryum.config.basepath'] = $project;
				$application_ini['development : production']['resources.db.enabled'] = "1";
				$application_ini['development : production']['resources.db.params.host'] = $_POST['db_host'];
				$application_ini['development : production']['resources.db.params.username'] = $_POST['db_user'];
				$application_ini['development : production']['resources.db.params.password'] = $_POST['db_pass'];
				$application_ini['development : production']['resources.db.params.dbname'] = $_POST['db_name'];
				
				write_php_ini($application_ini, APPLICATION_PATH . "/configs/application.ini");
				
				
				// Renomeia o documento do workbench
				rename(APPLICATION_PATH . "/docs/database/model.mwb", APPLICATION_PATH . "/docs/database" . $project . ".mwb");
				
				// Executa a configuração do banco de dados
				$con = mysql_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pass']); 
				mysql_select_db($_POST['db_name'], $con);
				if($con !== FALSE) { 
					// Load and explode the sql file 
					$sqlFile = file_get_contents(APPLICATION_PATH . "/docs/database/esqueleto.sql");
					$sqlArray = explode(";", $sqlFile); 

					//Process the sql file by statements 
					foreach($sqlArray as $stmt) { 
						if(strlen($stmt) > 3) { 
							$result = mysql_query($stmt); 
							if(!$result) { 
								$sqlErrorCode = mysql_errno(); 
								$sqlErrorText = mysql_error(); 
								$sqlStmt = $stmt; 
								
								die($sqlErrorCode . ": " . $sqlErrorText);
							} 
						} 
					} 
				} 
				else {
					die("Não foi possivel conexão com o banco de dados");
				}
				
				// Redireciona para o index
				header("Location: install.php?step=4");
				
				break;
				
			// Passo que configura o banco de dados
			case 2:
				// Mostra o HTML
				echo "
					<form action=\"install.php?step=3\" method=\"post\">
						<input type=\"text\" name=\"db_host\" value=\"\" placeholder=\"Host do banco de dados\" class=\"text\"><br>
						<input type=\"text\" name=\"db_user\" value=\"\" placeholder=\"Usuário do banco de dados\" class=\"text\"><br>
						<input type=\"text\" name=\"db_pass\" value=\"\" placeholder=\"Senha do banco de dados\" class=\"text\"><br>
						<input type=\"text\" name=\"db_name\" value=\"\" placeholder=\"Nome do banco de dados\" class=\"text\"><br><br>
						<input type=\"submit\" value=\"Próximo\" " . $next . ">
					</form>
				";
				break;
				
			// Passo que verifica as condições de instalação
			case 1:
			default:
				// Verifica se o application.ini é gravavel
				$application_ini = "false";
				if(is_writable(APPLICATION_PATH . "/configs/application.ini")) {
					$application_ini = "true";
				}
				
				// Verifica se o .htaccess é gravavel
				$htaccess = "false";
				if(is_writable(APPLICATION_PATH . "/../.htaccess")) {
					$htaccess = "true";
				}
				
				// Verifica se o tmp é gravavel
				$tmp = "false";
				if(is_writable(APPLICATION_PATH . "/tmp")) {
					$tmp = "true";
				}
				
				// Verifica se o uploads é gravavel
				$uploads = "false";
				if(is_writable(APPLICATION_PATH . "/../arquivos/uploads")) {
					$uploads = "true";
				}
				
				// Verifica se o docs é gravavel
				$docs = "false";
				if(is_writable(APPLICATION_PATH . "/../application/docs")) {
					$docs = "true";
				}
				
				// Verifica se pode passar para o proximo passo
				$next = "disabled=\"disabled\"";
				if(($application_ini == "true") && ($htaccess == "true") && ($tmp == "true") && ($uploads == "true") && ($docs == "true")) {
					$next = "";
				}
								
				// Mostra o HTML
				echo "
					<ul>
						<li class=\"" . $application_ini . "\">application/config/application.ini</li>
						<li class=\"" . $htaccess . "\">.htaccess</li>
						<li class=\"" . $tmp . "\">application/tmp</li>
						<li class=\"" . $uploads . "\">arquivos/uploads</li>
						<li class=\"" . $docs . "\">application/docs</li>
						<li></li>
					</ul>
					<form action=\"install.php?step=2\" method=\"post\">
						<input type=\"hidden\" name=\"project\" value=\"" . $project . "\">
						<input type=\"hidden\" name=\"uploads\" value=\"" . $uploads . "\">
						<input type=\"hidden\" name=\"tmp\" value=\"" . $tmp . "\">
						<input type=\"hidden\" name=\"htaccess\" value=\"" . $htaccess . "\">
						<input type=\"hidden\" name=\"application\" value=\"" . $application_ini . "\">
						<input type=\"hidden\" name=\"docs\" value=\"" . $docs . "\">
						<input type=\"submit\" value=\"Próximo\" " . $next . ">
					</form>
				";
				break;
		}
	?>
	</body>
</html>

<?php
    function parse_ini ( $filepath ) {
    $ini = file( $filepath );
    if ( count( $ini ) == 0 ) { return array(); }
    $sections = array();
    $values = array();
    $globals = array();
    $i = 0;
    foreach( $ini as $line ){
        $line = trim( $line );
        // Comments
        if ( $line == '' || $line{0} == ';' ) { continue; }
        // Sections
        if ( $line{0} == '[' ) {
            $sections[] = substr( $line, 1, -1 );
            $i++;
            continue;
        }
        // Key-value pair
        list( $key, $value ) = explode( '=', $line, 2 );
        $key = trim( $key );
        $value = trim( $value );
        if ( $i == 0 ) {
            // Array values
            if ( substr( $line, -1, 2 ) == '[]' ) {
                $globals[ $key ][] = $value;
            } else {
                $globals[ $key ] = $value;
            }
        } else {
            // Array values
            if ( substr( $line, -1, 2 ) == '[]' ) {
                $values[ $i - 1 ][ $key ][] = $value;
            } else {
                $values[ $i - 1 ][ $key ] = $value;
            }
        }
    }
    for( $j=0; $j<$i; $j++ ) {
        $result[ $sections[ $j ] ] = $values[ $j ];
    }
    return $result + $globals;
}
?>