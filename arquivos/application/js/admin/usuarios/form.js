$(function() {
	// Validação do formulario
	$('form').unbind().bind('submit', function() {
		
		// Valida o nome
		if($('#nome').val().length <= 0) {
			$.msgBox({message:'Campo nome é obrigatório', type:'error'});
			return false;
		}
		
		// Valida o email
		if($('#email').val().length <= 0) {
			$.msgBox({message:'Campo email é obrigatório', type:'error'});
			return false;
		}
		
		// Valida o login
		if($('#login').val().length <= 0) {
			$.msgBox({message:'Campo login é obrigatório', type:'error'});
			return false;
		}
		
		// Busca o id do usuário e verifica se existe usuario
		var idusuario = $.getUrlVar('idusuario');
		if(idusuario == undefined) {
			// Valida se as senhas estão iguais
			if($('#senha').val().length <= 0) {
				$.msgBox({message:'Campo senha é obrigatório', type:'error'});
				return false;
			}
		}
		
		// Valida se as senhas estão iguais
		if($('#senha').val() != $('#resenha').val()) {
			$.msgBox({message:'Campos de senha estão diferentes', type:'error'});
			return false;
		}
		
		// Valida a empresa
		if($('#idempresa').val().length <= 0) {
			$.msgBox({message:'Campo empresa é obrigatório', type:'error'});
			return false;
		}
		
		// Retorna a permissão para o formulário
		return true;
	});
	
	// Cria o elemento de repetir senha
	var div = $('<div id="element-resenha"></div>');
	var label = $('<label id="element-resenha" class="required" for="resenha">Confirme Senha</label>');
	var input = $('<input id="resenha" class="varchar string password" type="password" field-type="password" value="" name="resenha"></input>');
	
	div.append(label);
	div.append(input);
	$('#element-senha').after(div);
});