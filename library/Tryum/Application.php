<?php

/** Zend_Application */
require_once("Zend/Application.php");

/** Debug */
require_once("Tryum/Debug.php");

/**
 * Classe que instancia toda a aplicação
 * 
 * @name Tryum_Application
 * @see Zend_Application
 */
class Tryum_Application extends Zend_Application {
	/**
	 * Inicializa a aplicação
	 *
	 * @param  string $environment
	 * @param  string|array|Zend_Config $options String path to configuration file, or array/Zend_Config of configuration options
	 * @throws Zend_Application_Exception When invalid options are provided
	 * @return void
	 */
	public function __construct($environment, $options=null) {
		// Verifica se é a criação de imagens
		if($_GET['param'] == "tbimage") {
			// Cria os thumbs
			$this->_createThumb();
		}
		elseif($_GET['param'] == "minifycss") {
			// Executa o minify no CSS
			//$this->_minifyCSS();
		}
		else {
			// Inicializa o debug
			$debug = new Tryum_Debug();
			
			// Continua o carregamento
			parent::__construct($environment, $options);
			
			// Registra o debug
			Zend_Registry::set("debug", $debug);
		}
	}
	
	/**
	 * Executa o minify css
	 * 
	 * @access protected
	 * @name _minifyCSS
	 */
	protected function _minifyCSS() {
		// Monta o caminho do arquivo
		var_dump($_REQUEST);
	}
	
	/**
	 * Cria thumbs
	 * 
	 * @access protected
	 * @name _createThumb
	 */
	protected function _createThumb() {
		// Inclui a classe da manipulação de imagens
		include("Tryum/Image/Canvas.php");

		// Busca os parametros
		$file = $_GET["imagem"];
		$type = $_GET["tipo"];
		$width = $_GET["largura"];
		$height = $_GET["altura"];
		$crop = $_GET["crop"];
		
		// Monta o caminho do arquivo
		$file = APPLICATION_PATH . "/../arquivos/uploads/" . $type . "/" . $file;
		
		// Cria o objeto canvas
		$canvas = new Tryum_Image_Canvas($file);
		
		// Verifica se foi passada somente a largura
		if(($width != "") && ($height == "")) {
			$canvas->redimensiona($width);
		}
		// Verifica se foi passada somente a altura
		elseif($width == "" && $height != "") {
			$canvas->redimensiona('',$height);
		}
		// Verifica se foram passadas as duas dimensoes
		elseif($width != "" && $height != "") {
			if($crop == 0) {
				$canvas->redimensiona($width, $height);
			}
			elseif($crop == 1) {
				$canvas->redimensiona($width, $height, "crop");
			}
			elseif($crop == 2) {
				$canvas->hexa("FFFFFF");
				$canvas->redimensiona($width, $height, "preenchimento");
			}
		}
		else {
			$canvas->redimensiona($thumbs->largura, $thumbs->altura, "preenchimento");
		}
		
		// Mostra a imagem
		$canvas->grava("", 75);
	}
}
