$(function() {
	// Validação do formulario
	$('form').unbind().bind('submit', function() {
		
		// Valida a senha
		if($('#senha_atual').val().length <= 0) {
			$.msgBox({message:'Campo senha atual é obrigatório', type:'error'});
			return false;
		}
		
		// Valida a senha
		if($('#senha_nova').val().length <= 0) {
			$.msgBox({message:'Campo senha nova  é obrigatório', type:'error'});
			return false;
		}
		
		// Valida se as senhas estão iguais
		if($('#senha_nova').val() != $('#senha_confirmar').val()) {
			$.msgBox({message:'Campos de senha nova estão diferentes', type:'error'});
			return false;
		}
		
		// Retorna a permissão para o formulário
		return true;
	});
});