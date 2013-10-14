<?php

/**
 * Classe de envio de emails
 * 
 * @name Tryum_Mail
 */
class Tryum_Mail extends Zend_Mail {
	/**
	 * Armazena o transporte do email
	 *
	 * @access private 
	 * @name _transport
	 * @var Zend_Mail_Transport_Smtp
	 */
	private $_transport = NULL;
	
	/**
	 * Método de inicialização da classe
	 * 
	 * @name init
	 * @param string $encode Codificação do email
	 */
	public function __construct($encode=NULL) {
		// Busca as configurações
		$config = Zend_Registry::get("config");
		
		// Verifica as configurações padrão
		$ssl = "ssl";
		if(isset($config->tryum->email->ssl)) {
			$ssl = $config->tryum->email->ssl;
		}
		
		$port = "465";
		if(isset($config->tryum->email->port)) {
			$port = $config->tryum->email->port;
		}
		
		$auth = "login";
		if(isset($config->tryum->email->auth)) {
			$auth = $config->tryum->email->auth;
		}
		
		if(isset($config->tryum->email->encode)) {
			$encode = $config->tryum->email->encode;
		}
		
		// Constroi o parent
		parent::__construct($encode);
		
		// Cria a configuração do email
		$email_conf = array(
			'auth' => $auth,
			'username' => $config->tryum->email->username,
			'password' => $config->tryum->email->password,
			'ssl' => $ssl,
			'port' => $port
		);
		
		// Cria o objeto de transport
		$this->_transport = new Zend_Mail_Transport_Smtp($config->tryum->email->smtp, $email_conf);
		
		// Verifica se possui email e nome default
		if((isset($config->tryum->email->default->email)) && (isset($config->tryum->email->default->nome))) {
			parent::setFrom($config->tryum->email->default->email, $config->tryum->email->default->nome);
		}
	}
	
	/**
	 * Método para enviar o email
	 * 
	 * @name send
	 */
	public function send() {
		// Envia o email
		parent::send($this->_transport);
	}
	
	/**
	 * Adiciona imagens ao corpo do email
	 * 
	 * @name addEmbeddedImage
	 * @param string $image Imagem á ser anexada
	 * @param string $id ID da imagem
	 * @param string $path Caminho relativo usado no email
	 */
	public function addEmbeddedImage($image, $id, $path) {
		$attach = parent::createAttachment(
			file_get_contents($image),
			"image/png",
			Zend_Mime::DISPOSITION_INLINE,
			Zend_Mime::ENCODING_BASE64,
			$path
		);
		
		$attach->id = $id;
		
		return $this;
	}
}
