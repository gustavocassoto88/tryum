<?php

/**
 * Controlador dos usuarios
 *
 * @name UsuariosController
 */
class user_UsuariosController extends Tryum_Controller_Action {
	/**
	 * Armazena o model padrão da tela
	 *
	 * @access protected
	 * @name $_model
	 * @var Default_Model_Usuarios
	 */
	protected $_model = NULL;

	/**
	 *
	 */
	public function init() {
		// Inicializa o model da tela
		$this->_model = new user_Model_Usuarios();
		
		// Chama o parent
		parent::init();
	}
	
	/**
	 * Hook para a edição do usuário
	 * 
	 * @name doBeforeUpdate
	 * @param array $data Valores à serem editados
	 */
	public function doBeforeUpdate($data) {
		if($data['senha'] == md5('')) {
			unset($data['senha']);
		}
		
		return $data;
	}
	
	/**
	 * Hook para execução antes da listagem dos usuários
	 * 
	 * @name doBeforeList
	 * @return Zend_Db_Select
	 */
	public function doBeforeList() {
		// Busca a sessão
		$session = new Zend_Session_Namespace("login");
		
		// Cria o select
		$select = $this->_model->select()
			->where("idperfil <= ?", $session->logged_usuario['idperfil']);
		
		// Retorna o select para criar a lista
		return $select;
	}

	/**
	 * Acao login
	 *
	 * @name loginAction
	 */
	public function loginAction() {
		// Desabilita o layout
		$this->_helper->layout->disableLayout();
		//$this->_helper->viewRenderer->setNoRender(TRUE);

		// Busca a instancia do zend_auth
		$auth = Zend_Auth::getInstance();

		// Verifica se ja está logado
		if($auth->hasIdentity()) {
			$this->_helper->redirector(NULL, NULL, "user");
		}

		// Cria as sessões
		$session = new Zend_Session_Namespace("login");
		$messages = new Zend_Session_Namespace("messages");

		// Cria o formulario de login
		$form = $this->createLoginForm();

		// Verifica se tem dados vindo do formulario
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
				
			// Verifica se o formulario é valido
			if($form->isValid($data)) {
				// Busca as informações do login
				$login = $form->getValue("login");
				$senha = $form->getValue("senha");

				// Busca a instancia de Zend_Auth
				$objAuth = Zend_Auth::getInstance();
				$authAdapter = new Zend_Auth_Adapter_DbTable(
				Zend_Registry::get("db"),
					"usuarios",
					"login",
					"senha",
					"md5(?)"
				);

				// Define os dados para processar o login
				$authAdapter->setIdentity($login)
				->setCredential($senha);

				// Efetua o login
				$result = $objAuth->authenticate($authAdapter);

				// Verifica se o login está correto
				if($result->isValid()) {
					// Armazena os dados do usuário em sessão, apenas desconsiderando a senha do usuário
					$info = $authAdapter->getResultRowObject(NULL, "senha");
					$objAuth->getStorage()->write($info);
						
					// Busca as informações do usuario
					$usuario_row = $this->_model->fetchRow(array("login = ?" => $login));
						
					// Converte para array
					$usuario_array = $usuario_row->toArray();
						
					// Adiciona as informações à sessão
					$session->logged_usuario = $usuario_array;
						
					// Redireciona o usuario
					$this->_helper->redirector(NULL, NULL, "user");
				}
				else {
					// Armazena o erro
					$messages->error = "Os dados informados (e-mail e/ou senha) não são válidos.";
						
					// Dados inválidos
					$this->_helper->redirector(NULL, NULL, "user");
				}
			}
			else {
				// Armazena o erro
				$messages->error = "Os dados informados (e-mail e/ou senha) não são válidos.";

				// Dados inválidos
				$this->_helper->redirector(NULL, NULL, "user");
			}
		}
	}

	/**
	 * Ação que efetua o logout do usuario
	 *
	 * @name logoutAction
	 */
	public function logoutAction() {
		//
		$this->_helper->layout->disableLayout();

		//
		$session = new Zend_Session_Namespace("login");

		// Remove as credenciais
		Zend_Auth::getInstance()->clearIdentity();

		// Redireciona
		$this->_helper->redirector("login", "usuarios", "user");
	}

	/**
	 * Cria o formulario de login
	 *
	 * @access protected
	 * @name createLoginForm
	 * @return Zend_Form
	 */
	protected function createLoginForm() {
		// Cria o formulario
		$form = new Zend_Form();

		// Cria o action
		$url = $this->view->url(array('module'=>"default", 'controller'=>"usuarios", 'action'=>"login"), NULL, TRUE);
		$form->setAction($url);

		// Cria o input de login
		$login = new Zend_Form_Element_Text("login");
		$login->setLabel("Login/email")
		->setRequired(TRUE)
		->addFilter("StripTags")
		->addFilter("StringTrim")
		->addValidator("NotEmpty");

		// Cria o input de senha
		$senha = new Zend_Form_Element_Password("senha");
		$senha->setLabel("Senha")
		->setRequired(TRUE)
		->addFilter("StripTags")
		->addFilter("StringTrim")
		->addValidator("NotEmpty");
		
		// Cria o botão de entrar
		$submit = new Zend_Form_Element_Submit("submit");
		$submit->setLabel("Entrar")
		->setAttrib("id", "submit");

		// Adiciona os elementos
		$form->addElements(array($login, $senha, $submit));

		// Retorna o formulario
		return $form;
	}
	
	/**
	 * Ação para trocar as senhas
	 * 
	 * @name trocarsenhaAction
	 */
	public function trocarsenhaAction() {
		// Cria a sessão
		$session = new Zend_Session_Namespace("login");
		
		// Verifica se é post
		if($this->_request->isPost()) {
			// Busca os dados do formulario
			$senha_atual = md5($this->_request->getParam("senha_atual"));
			$senha_nova = $this->_request->getParam("senha_nova");
			$senha_confirmar = $this->_request->getParam("senha_confirmar");
			
			// Armazena o id do usuario logado
			$idusuario = $session->logged_usuario['idusuario'];
			
			// Verifica se a senha atual está correta
			$result = $this->_model->fetchAll(array('idusuario = ?' => $idusuario, 'senha = ?' => $senha_atual))->toArray();
			if(count($result) > 0) {
				$data = array();
				$data['senha'] = md5($senha_nova);
				$this->_model->update($data, array('idusuario = ?' => $idusuario));
			}
			
			// Redireciona o usuário à consulta
			$this->_helper->redirector("index", "index", "user");
		}
	}
	
	/**
	 * Ação que faz a requisiçao de uma nova senha
	 * 
	 * @name resetAction
	 */
	public function resetAction() {
		// Cria a sessão
		$messages = new Zend_Session_Namespace("messages");
		
		// Busca o usuario
		$login = $this->_request->getParam("login", NULL);
		
		// Busca a chave 
		$key = $this->_request->getParam("chave", NULL);
		
		// Verifica se existe chave
		if($key !== NULL) {
			// Busca as informações do usuario no banco
			$result = $this->_model->fetchRow(array('chave = ?' => $key));
			if($result === NULL) {
				// Salva o erro
				$messages->error = "Não foi possivel encontrar a chave";
				
				// Redireciona o usuário à consulta
				$this->_helper->redirector("index", "index", "user");
			}
			
			// 
			$idusuario = $result['idusuario'];
			$email = $result['email'];
			$nome = $result['nome'];
			
			// Gera uma nova senha
			$CaracteresAceitos = 'abcdxywzABCDZYWZ0123456789';
			$max = strlen($CaracteresAceitos)-1;
			$password = null;
			for($i=0; $i < 8; $i++) { 
				$password .= $CaracteresAceitos[mt_rand(0, $max)]; 
			}
			
			// Atualiza a senha e remove a chave
			$data = array();
			$data['chave'] = "";
			$data['senha'] = md5($password);
			
			// Salva a chave no banco
			$this->_model->update($data, array('idusuario = ?' => $idusuario));
			
			// Cria a mensagem
			$mensagem = "Sua nova senha é <b>" . $password . "</b>";
			
			// Busca o conteudo do email
			$mailBody = file_get_contents(APPLICATION_PATH . "/../arquivos/user/mail/senha.html");
			$mailBody = str_replace('$mensagem', $mensagem, $mailBody);
			
			// Envia o email
			$mail = new Tryum_Mail();
			$mail->addTo($email, $nome);
			$mail->setSubject("Nova requisição de senha");
			$mail->setBodyHtml($mailBody);
			$mail->send(); 
			
			$messages->success = "Foi lhe enviado um email com os proximos procedimentos";
			
			// Redireciona o usuário à consulta
			$this->_helper->redirector("index", "index", "user");
		}
		
		// Verifica se tem login
		if($login != NULL) {
			// Gera a chave
			$key = md5(rand(100000000, 999999999) . time());
			
			// Busca as informações do usuario no banco
			$result = $this->_model->fetchRow(array('login = ?' => $login));
			
			if($result === NULL) {
				// Salva o erro
				$messages->error = "Não foi possivel encontrar o usuário";

				// Redireciona o usuário à consulta
				$this->_helper->redirector("index", "index", "user");
			}
			
			// Armazena as informações do usuário
			$idusuario = $result['idusuario'];
			$email = $result['email'];
			$nome = $result['nome'];
			
			// Verifica se tem email
			if(strlen($email) <= 0) {
				// Salva o erro
				$messages->error = "O usuário não possui email cadastrado";
				
				// Redireciona o usuário à consulta
				$this->_helper->redirector("index", "index", "user");
			}
			
			// Salva a chave no banco
			$this->_model->update(array('chave' => $key), array('idusuario = ?' => $idusuario));
			
			// Cria a URL para ativação da nova senha
			$url = "http://" . $_SERVER['SERVER_NAME'] . substr($_SERVER['REDIRECT_URL'], 0, strpos($_SERVER['REDIRECT_URL'], "/login")) . "/chave/" . $key;
			
			// Cria a mensagem
			$mensagem = "Uma nova senha foi requisitada, se você foi o requisitante, clique no link abaixo para ativar uma nova senha <br><br><a href=" . $url . "> " . $url . " </a>";
			
			// Busca o conteudo do email
			$mailBody = file_get_contents(APPLICATION_PATH . "/../arquivos/user/mail/senha.html");
			$mailBody = str_replace('$mensagem', $mensagem, $mailBody);
			
			// Envia o email
			$mail = new Tryum_Mail();
			$mail->addTo($email, $nome);
			$mail->setSubject("Nova requisição de senha");
			$mail->setBodyHtml($mailBody);
			$mail->send(); 
			
			$messages->success = "Foi lhe enviado um email com os proximos procedimentos";
			
			// Redireciona o usuário à consulta
			$this->_helper->redirector("index", "index", "user");
		}
	}
}
