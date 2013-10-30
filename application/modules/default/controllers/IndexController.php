<?php

/**
*
*/
class IndexController extends Zend_Controller_Action {
	/**
	 *
	 */
	public function init() {
	/* Initialize action controller here */
	}

	/**
	 *
	 */
	public function indexAction() {
		
	}

	/**
	 *
	 */
	public function contatoAction() {
		
	}

	/**
	 * Cria os thumbs
 	 * 
	 * @name thumbAction
	 */
	public function thumbAction() {
		// Desabilita o layout
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		// Busca os parametros
		$file = $this->_request->getParam("imagem", "");
		$type = $this->_request->getParam("tipo", "");
		$width = $this->_request->getParam("largura", "");
		$height = $this->_request->getParam("altura", "");
		$crop = $this->_request->getParam("crop", 1);

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
				$canvas->hexa($cor);
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

