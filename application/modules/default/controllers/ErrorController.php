<?php

/**
 * Controlador de erros
 * 
 * @name ErrorController
 */
class ErrorController extends Zend_Controller_Action {
	/**
	 * Ação do erro
	 * 
	 * @name errorAction
	 */
	public function errorAction() {
		// Desabilita o layout e busca o handler de erro
		$this->_helper->layout->disableLayout();
		$errors = $this->_getParam("error_handler");

		// Verifica se foi possivel bucar o erro
		if(!$errors || !$errors instanceof ArrayObject) {
			$this->view->message = "You have reached the error page";
			return;
		}

		// Verifica o tipo do erro
		switch($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				// 404 error -- controller or action not found
				$this->getResponse()->setHttpResponseCode(404);
				$priority = Zend_Log::NOTICE;
				$this->view->message = "Page not found";
				break;
			default:
				// application error
				$this->getResponse()->setHttpResponseCode(500);
				$priority = Zend_Log::CRIT;
				$this->view->message = "Application error";
				break;
		}

		// Verifica se existe o erro
		if($log = $this->getLog()) {
			$log->log($this->view->message, $priority, $errors->exception);
			$log->log('Request Parameters', $priority, $errors->request->getParams());
		}

		// Verifica se deve exibir o erro
		if($this->getInvokeArg("displayExceptions") == TRUE) {
			$this->view->exception = $errors->exception;
		}

		// Salva no banco de dados
		try {
			$session = new Zend_Session_Namespace("login");
			$model = new user_Model_Erros();
			$data = array();
			$data['data_execucao'] = date("Y-m-d H:i:s");
			$data['mensagem'] = $errors->exception->getMessage();
			$data['parametros'] = json_encode($errors->request->getParams());
			$data['idusuario'] = $session->logged_usuario['idusuario'] > 0 ? $session->logged_usuario['idusuario'] : NULL;
			$data['trace'] = $errors->exception->getTraceAsString();
			$model->insert($data);
		}
		catch(Exception $e) {
			$extras = $e->getMessage();
		}
		
		// Assina as variaveis
		$this->view->request = $errors->request;
		$this->view->extras = $extras;
		$this->view->assign("has_exception", TRUE);
		$this->view->assign("exception_message", $errors->exception->getMessage());
		$this->view->assign("trace", $errors->exception->getTraceAsString());
		$this->view->assign("params", $this->view->escape(var_export($errors->request->getParams(), TRUE)));
	}

	/**
	 * Busca o log
	 * 
	 * @name getLog
	 */
	public function getLog() {
		// Busca o bootstrap
		$bootstrap = $this->getInvokeArg("bootstrap");
		if(!$bootstrap->hasResource("Log")) {
			return false;
		}
		
		// Busca o log
		$log = $bootstrap->getResource("Log");
		
		// Retorna o log
		return $log;
	}
}

