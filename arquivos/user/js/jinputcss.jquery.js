// Plugin de estilização de campo input file
$.fn.extend({
	jinputcss: function(a){
		
		// Recupera o elemento file
		var nativeInput 	= $(this);
		// Parametro que define a classe de estilo do elemento
		var cssBox 			= a.cssBox == undefined ? "customfile" : a.cssBox;
		// Parametro que define a classe de estilo do botão para o elemento
		var cssButton		= a.cssButton == undefined ? "customfile-button" : a.cssButton;
		// Parametro que define a classe de estilo do texto descritivo do elemento
		var cssText			= a.cssText == undefined ? "customfile-feedback" : a.cssButton;
		// Parametro que define a classe de estilo do botão para o elemento quando o mouse estiver sobre o ele
		var cssButtonHover	= a.cssButtonHover == undefined ? "customfile-hover" : a.cssButtonHover;
		// Parametro que define a classe de estilo do botão para o elemento quando o foco estiver sobre ele
		var cssKeyFocus		= a.cssKeyFocus == undefined ? "customfile-focus" : a.cssKeyFocus;
		// Parametro que define o texto padrão para aparecer na descrição
		var defaultText 	= a.defaultText == undefined ? "Nenhum arquivo foi selecionado" : a.defaultText;
		// Parametro que define o texto padrão para aparecer na descrição após arquivo for selecionado
		var defaultFileText = a.defaultFileText == undefined ? "customfile-feedback-populated" : a.defaultFileText;
		// Parametro que define o texto que aparecerá no botão
		var buttonText 		= a.buttonText == undefined ? "Selecionar" : a.buttonText;
		// Parametro que define o texto que aparecerá no botão após o arquivo for selecionado
		var buttonFileText	= a.buttonFileText == undefined ? buttonText : a.buttonFileText;
		// Parametro que define se haverá animação
		var anime			= a.anime == undefined ? true : a.anime;
		// Determina o tempo da transição
		var efectTimer 		= a.timer == undefined ? 1000 : a.timer; 
		
		// Atribui estilo para ocultar o elemento input
		nativeInput.css({
			'opacity'	: 0,
			'position'	: "absolute",
			'top'		: -100,
			'cursor'	: "pointer",
			'z-index'	: 99999
		});
		
		// Cria os elemento necessários para a estilização
		var upload = $('<div class="'+cssBox+'" area-hidden="true"></div>');
		
		// Criando um botão customizado
		var uploadButton = $('<span class="'+cssButton+'">'+buttonText+'</span>').appendTo(upload);
		
		// Criando campo do conteúdo do arquivo customizado
		var uploadFeedback = $('<span class="'+cssText+'">'+defaultText+'</span>').appendTo(upload);
		uploadFeedback.data('width', uploadFeedback.width());
		
		// Insere o conteúdo após o arquivo file
		upload.insertAfter(nativeInput);
		
		uploadButton.on('click', function() {
			nativeInput.trigger('click');
		});
		
		uploadFeedback.on('click', function() {
			nativeInput.trigger('click');
		});
		
		//Mantém o upload nativo abaixo do cursor estando o cursor dentro das limitações da tag upload
//		upload.mousemove(function(e){
//			nativeInput.css({
//				top : e.pageY - 3,
//				left : e.pageX - $(nativeInput).outerWidth() + 20
//			});
//		});
		
		upload
			// Move o input nativo para o fim do body quando o mouse sobre o campo do campo do upload
			.mouseover(function(){
				nativeInput.appendTo('body');
			})
			// Move o input nativo para o lugar original quando o mouse estiver fora do campo
			.mouseout(function(){
				nativeInput.insertBefore(upload);
		});
		
//		nativeInput
//			// add hover mouse class on mouseover
//			.mouseover(function(){
//				uploadButton.addClass(cssButtonHover);
//			})
//			// remove mouse hover class on mouseout
//			.mouseout(function(){
//				uploadButton.removeClass(cssButtonHover);
//			})
//			// add keyboard focus class on focus
//			.focus(function(){
//				uploadButton.addClass(cssKeyFocus);
//			})
//			// remove keyboard focus class on blur
//			.blur(function(){
//				uploadButton.removeClass(cssKeyFocus);
//		});
		
		nativeInput.change(function(){
			
			// find text after the last backslash in path for filename
			var fileName = $(this).val().split(/\\/).pop();
			// find text after the last period in path for extension
			var fileExt = 'customfile-ext-' + fileName.split('.').pop().toLowerCase();
			
			// Recupera o tamanho do campo 
			var uploadWidth = upload.css('width').replace('px','') - uploadButton.css('width').replace('px','');
			// Recupera o tamanho do botao
			var buttonWidth = Number(uploadButton.css('width').replace('px',''));
			// Recupera o tamanho do texto do arquivo 
			var contentSize = fileName.length;
			
			// Efeito de sumir o texto do campo upload
			uploadFeedback.fadeOut(efectTimer / 10);
			
			// Caso anime seje verdadeiro
			if(anime){
				
				// Valida alguns valores padrões para tamanho do campo conforme quantidade de caracteres
				var percent;
				if(contentSize < 20){ percent = 15 }
				else if(contentSize >= 20 && contentSize < 50){ percent = 10 }
				else { percent = 8 };
				
				// Anima o tamanho do campo upload
				$(upload).promise().done(function(){
					$(this).animate({
						width : ((contentSize * percent) + buttonWidth) + "px"
					},efectTimer);
				});
			};
			
			// Set o tempo em que ocorrerá a transição dos textos do upload
			setTimeout(function(){
			
				// update the feedback
				uploadFeedback
					// add class to containter to show populated state
					.addClass(defaultFileText)
					// set feedback text to filename
					.text(fileName)
					// add file extension class
					.addClass(fileExt);
					// change text of button
					uploadButton.text(buttonFileText);
				
				// Efeito no texto do upload
				uploadFeedback.fadeIn();
			
			},efectTimer);
			// remove a classe hover e focus do elemento
			uploadButton.removeClass(cssButtonHover);
			uploadButton.removeClass(cssKeyFocus);
		});
		
	}
});