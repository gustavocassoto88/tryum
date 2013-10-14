$(function() {
	// Ajusta o foco no campo usuário
	$('input[type="text"], input[type="password"]')
		.unbind()
		.bind('focus', function() {
			if($(this).val() == 'Nome do usuário') {
				$(this).val('');
			}
		})
		.bind('focusout', function() {
			if($(this).val() == '') {
				$(this).val('Nome do usuário');
			}
		});
	
	// Centraliza a div
	$(window).on('resize', function() {
		$('#site_contents').center();
	});
	$('#site_contents').center();
	
	// Cria o evento de recuperar a senha
	$('#submit').parent().find('a').bind('click', function() {
		window.location = $(this).attr('href') + $('#login').val();
		
		return false;
	});
});
