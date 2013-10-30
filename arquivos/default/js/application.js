$(function(){

	// Validação formulários
	$('form').validate({
		onChange : true,
		prepare : {
			placeholder : function(value) {
				return value == $(this).attr('placeholder') ? '' : value;
			}
		},
		eachInvalidField : function(){
			$(this).removeClass('sucess').addClass('error');
			var type = $(this).attr('type');
			if(type == 'radio'){
				$(this).closest('.bg-radio').find('label span').addClass('error');
			}
		},
		eachValidField : function(){
			$(this).removeClass('error').addClass('sucess');
			var type = $(this).attr('type');
			if(type == 'radio'){
				$(this).closest('.bg-radio').find('label span').removeClass('error');
			}
		},
		conditional : {
			confirmemail : function(){
				return $(this).val() == $('#email').val();
			},
			confirmsenha : function(){
				return $(this).val() == $('#senha').val();
			},
			confirmnovasenha : function(){
				return $(this).val() == $('#novasenha').val();
			}
		},
		valid : function(){
			$('.box-interesse p span').fadeOut();
		},
		invalid : function(){
			$('.box-interesse p span').fadeIn();
		}
	});

	// Scroll
	$('.scroll-pane').jScrollPane({
        showArrows: true,
        autoReinitialise: true,
        verticalDragMaxHeight: 25,
        mouseWheelSpeed: 16
    });

 	$('#imagezoom').addimagezoom({
		zoomrange: [3, 10],
		magnifiersize: [249,249],
		magnifierpos: 'right',
		cursorshade: true
	});

	// Abas Produto
	$('#tabs').tabs({
		show:{
			effect: 'fadeIn', duration: 500
		}
	});

	// Posiciona lista de amigos
	var timer;
	$(window).on('load scroll orientationchange', function(){
		clearTimeout(timer);
		var
			element = $(this),

			scrollTop = element.scrollTop(),

			headerHeight = $('header').height(),

			footerHeight = $('footer').height(),

			documentHeight = $(document).height(),

			windowHeight = element.height(),

			footerTop = $('footer').offset().top,

			asideHeight = windowHeight - headerHeight;

		timer = setTimeout(function(){

			$('aside.left').css('height', asideHeight + 'px');

			if(scrollTop >= (documentHeight - windowHeight - footerHeight)){
				$('.right').height(windowHeight - (headerHeight + ((footerTop - (scrollTop +windowHeight)) * (-1))));
			}else{
				$('.right').height(windowHeight - headerHeight);
			}
		}, 10);
	});

	// Galeria (detalhamento produto)
	// $('.galeria ul li a').on('click',function(){
	// 	var src = $(this).find('img').data('img');
	// 	$('#imagezoom').attr('src',src);
	// });

	// Abrir menu (perfil - topo)
	$('.menu-perfil a.menu-opcoes').on('click',function(event){
		event.preventDefault();
		event.stopPropagation();
		$('.menu-perfil ul').toggleClass('ativo');
	});
	$(document).on('click',function(event){
		$('.menu-perfil ul').removeClass('ativo');
	});

	// Muda Type do input de senha
	$('#senhausuario,#confirmarsenhausuario,#senhafornecedor,#confirmarsenhafornecedor').on('focus',function(){
		var valor = $(this).val();
		if(valor == '* Senha' || valor == '* Confirmar Senha'){
			$(this).val('');
			$(this).get(0).setAttribute('type', 'password');
		}
	});
	$('#senhausuario,#confirmarsenhausuario,#senhafornecedor,#confirmarsenhafornecedor').blur(function(){
		var valor = $(this).attr('data-valor');
		if($(this).val() == ''){
			$(this).val(valor);	
			$(this).get(0).setAttribute('type', 'text');
		}else{
			$(this).get(0).setAttribute('type', 'password');
		}
	});

	// Class last
	$('.galeria ul li:last').addClass('last');
	
	$('.amigos ul').each(function(){
		$(this).find('li:nth-child(3n+3)').addClass('right-border');
	});

	$('.box-interesse.painel').find('div.interesse:nth-child(9n+10)').addClass('direita');

	$('.box-interesse.box-demanda').find('div.interesse:nth-child(9n+9)').addClass('direita');

	// Upload de imagem
	$('.adicionar-imagem input').on('change',function(){
		var URL2 = window.URL || window.webkitURL;
		if(URL2){
			var	element = $(this);
			var image = $('<img>', {
				src : URL.createObjectURL(element.get(0).files[0]),
				width: '140',
				height: '140'
			});
			$(this).closest('.adicionar-imagem').find('div').html(image);
		}else{
			$(this).closest('imagem-adicionada').find('div').html(document.basePath + '/common/default/img/imagem-adicionada.jpg');
		}
	});

	// Lista de Amigos (perfil)
	$('.select-lista-amigos a').on('click',function(event){
		event.preventDefault();
		var tipo = $(this).data('tipo');
		$(this).closest('.select-lista-amigos').find('a').removeClass('ativo');
		$(this).addClass('ativo');

		$('.amigos ul').each(function(){
			if($(this).hasClass(tipo)){
				$(this).slideDown();
			}else{
				$(this).slideUp();
			}
		});
	});

	// Interesse (publicar oferta)
	$('.box-interesse .interesse input').on('click',function(){
		if($(this).closest('.interesse').hasClass('ativo')){
			$(this).closest('.interesse').removeClass('ativo');
			$(this).attr("checked",false);
		}else{
			$(this).closest('.interesse').addClass('ativo');
			$(this).attr("checked",true);
		}
	});

	// Ativa pop up
	$('.btn-amarelo.publicar-oferta,.meus-dados,.button-social,.gerar-demanda,.visualizar-reclamacao,.reclame-aqui').on('click',function(event){
		
		event.preventDefault();

		if($(this).data('tipo') == 'demanda'){
			var 
				alturaTela 		= $(window).height(),
				alturaLightbox 	= $('.lightbox-gerar-demanda').height() + 60,
				conta 			= parseInt((alturaTela - alturaLightbox) / 2);
		}else{
			var 
				alturaTela 		= $(window).height(),
				alturaLightbox 	= $('.lightbox').height() + 60,
				conta 			= parseInt((alturaTela - alturaLightbox) / 2);
		}		
		
		if($(this).data('tipo') == 'share'){

			$('.lightbox-share.share').css('margin-top',conta + 'px');
			$('.lightbox-share.share').find('div').html('<iframe src="' + $(this).data('url') + '">');
			$('.bg-lightbox-share.share').slideDown(400);
			$('.lightbox-share.share').fadeIn(600);

		}else if($(this).data('tipo') == 'demanda'){

			$('.lightbox-gerar-demanda').css('margin-top',conta + 'px');
			$('.bg-lightbox-gerar-demanda').slideDown(400);
			$('.lightbox-gerar-demanda').fadeIn(600);

		}else if($(this).data('reclamacao') != ''){
			
			$.ajax({
				url: document.basePath + '/index/reclamacao',
				type: 'GET',
				data: {
					idreclamacao: $(this).data('reclamacao')
				},
				complete: function() {
					$('.lightbox').css('margin-top',conta + 'px');
					event.preventDefault();
					$('.bg-lightbox').slideDown(400);
					$('.lightbox').fadeIn(600);
				},
				success: function(data){
					$('.detalhes-reclamacao-oferta').html(data.reclamacao);
				}
			});

		}else{

			$('.lightbox').css('margin-top',conta + 'px');
			$('.bg-lightbox').slideDown(400);
			$('.lightbox').fadeIn(600);

		}
	});

	// Fecha o PopUp quando clicado fora do mesmo e quando clicado no 'fechar(X)'
	$('a#close,.bg-lightbox,a#close2,.bg-lightbox-gerar-demanda').on('click',function(event){
		event.preventDefault();
		$('.bg-lightbox,.bg-lightbox-gerar-demanda').slideUp(400);
		$('.lightbox,.lightbox-gerar-demanda').fadeOut(200);
	});

	// Fecha o PopUp quando telclado esc
	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			$('.bg-lightbox').slideUp(400);
			$('.lightbox').fadeOut(200);
		}
	});

	// Checkbox Button
	$('.bg-checkbox input,.tipo-usuario label').on('click',function(){
		var tipo = $(this).data('class');

		if(tipo == 'select-tipo-usuario'){
			$(this).closest('.' + tipo).find('.bg-checkbox').removeClass('ativo');
			$(this).closest('.bg-checkbox').addClass('ativo');
		}else{
			if($(this).closest('.bg-checkbox').hasClass('ativo')){
				$(this).closest('.bg-checkbox').removeClass('ativo');
			}else{
				$(this).closest('.bg-checkbox').addClass('ativo');
			}
		}
	});

	// Form Cadastro
	document.usuario 	= $('.cadastro .usuario').html();
	document.fornecedor = $('.cadastro .fornecedor').html();
	$('.tipo-usuario input').on('click',function(){
		var tipo = $(this).attr('id');
		if(tipo == 'usuario'){
			$('.cadastro .fornecedor').slideUp().html('');
			$('.cadastro .usuario').html(document.usuario).slideDown();
		}else{
			$('.cadastro .usuario').slideUp().html('');
			$('.cadastro .fornecedor').html(document.fornecedor).slideDown();
		}
	});

	//Buscar Endereço pelo Cep
	$(document).on('blur','#cep',function(){
		if($.trim($('#cep').val()) != ''){
			$.getScript('http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep=' + $('#cep').val().replace('-', '').replace('.', ''),function(){
				$('#endereco').val(unescape(resultadoCEP['tipo_logradouro']) + ' ' + unescape(resultadoCEP['logradouro']));
				$('#bairro').val(unescape(resultadoCEP['bairro']));
				$('#uf_cidade_fornecedor').val(unescape(resultadoCEP['uf']) + ' / ' + unescape(resultadoCEP['cidade']));
				$('#numero').focus();
			});
		}
	});

	// Gerar Demanda
	$('.avancar').on('click',function(event){
		event.preventDefault();
		var checked = $('input:checked').length;
		if(checked <= 0){
			$('form.box-gerar-demanda p.alert').fadeIn();
		}else{
			$('a.btn-select.demanda').hide();
			$('a.btn-select.interesse').show();

			$('form.box-gerar-demanda p.alert').fadeOut();
			$('.demanda-001').slideUp().removeClass('ativo');
			$('.demanda-002').slideDown();
		}
	});
	$('a.btn-select.interesse').on('click',function(event){
		event.preventDefault();
		$('a.btn-select.demanda').show();
		$('a.btn-select.interesse').hide();
		
		$('.demanda-002').slideUp();
		$('.demanda-001').slideDown();
	});

	// Facebook
	$(document).on('click', '#facebook', function(event){
		event.preventDefault();
		var url = "",
			link = $('.detalhes-oferta').data('url'),
			dataDescription = $('.detalhes-oferta p.descricao-oferta').text(),
			img = $('.detalhes-oferta').data('img'),
			dataTitle = $('.detalhes-oferta h3.titulo-oferta').text();
		url +='http://www.facebook.com/sharer.php?s=100';
		url +='&p[url]=' + link;
		url +='&p[images][0]=' + img;
		url +='&p[title]=' + dataTitle;
		url +='&p[summary]=' + dataDescription;
		window.open(url, 'sharer', 'toolbar=0,status=0,width=656,height=1');
	});

	// Twitter
	$(document).on('click', '#twitter', function(event){
		event.preventDefault();
		var postTwitter = "",
			linkt = "http://twitter.com/home?status=Vale Bonus - "
			dataDescriptiont = $.trim($('.detalhes-oferta p.descricao-oferta').text().substr(0, 60).replace(/(.*)[^\s]+$/, '$1...')),
			dataTitlet = $('.detalhes-oferta h3.titulo-oferta').text();

		$.getJSON( "http://is.gd/create.php?callback=?", {
			url: $('.detalhes-oferta').data('url'),
			format: "json"
		}).done(function(data){
			postTwitter += linkt;
			postTwitter += ' ' + dataTitlet + '- ';
			postTwitter += dataDescriptiont + ' - ' + data.shorturl;
			window.open(postTwitter, 'sharer', 'toolbar=0,status=0,width=700,height=260');
		});		
	});

});

//Select
function displayResult(selTag,id){
    var x = selTag.options[selTag.selectedIndex].text;
    $(id).val(x);    
}

//Moeda
function formatar_moeda(campo,separador_milhar,separador_decimal,tecla){
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? tecla.which : tecla.keyCode;
	if (whichCode == 13) return true;
	if (whichCode == 8) return true;
	key = String.fromCharCode(whichCode);
	if (strCheck.indexOf(key) == -1) return false;
	len = campo.value.length;
	for(i = 0; i < len; i++)
	if ((campo.value.charAt(i) != '0') && (campo.value.charAt(i) != separador_decimal)) break;
	aux = '';
	for(; i < len; i++)
	if (strCheck.indexOf(campo.value.charAt(i))!=-1) aux += campo.value.charAt(i);
	aux += key;
	len = aux.length;
	if (len == 0) campo.value = '';
	if (len == 1) campo.value = '0'+ separador_decimal + '0' + aux;
	if (len == 2) campo.value = '0'+ separador_decimal + aux;
	if (len > 2) {
		aux2 = '';
		for (j = 0, i = len - 3; i >= 0; i--) {
			if (j == 3) {
				aux2 += separador_milhar;
				j = 0;
			}
			aux2 += aux.charAt(i);
			j++;
		}
		campo.value = '';
		len2 = aux2.length;
		for (i = len2 - 1; i >= 0; i--)
		campo.value += aux2.charAt(i);
		campo.value += separador_decimal + aux.substr(len - 2, len);
	}
	return false;
}

// function getLatLong($opcao) {
//     var $geo = new google.maps.Geocoder();
 
//     $geo.geocode({
//         address: $opcao.endereco
//     },
//         function($resultado, $status){
//             if($status == google.maps.GeocoderStatus.OK) {
//                 // Criamos nossas latitude e longitude
//                 var $coords = $resultado[0].geometry.location;
 
//                 // Opcoes do mapa
//                 var $opcoes = {
//                     zoom: $opcao.zoom,
//                     center: $coords,
//                     mapTypeId: google.maps.MapTypeId.ROADMAP
//                 }
 
//                 // Criamos o mapa
//                 var $mapDom = document.getElementById($opcao.dom);
//                 var $map = new google.maps.Map($mapDom, $opcoes);
 
//                 // Adicionamos o "marker" aquele ponto vermelho
//                 var $mark = new google.maps.Marker({
//                     position: $coords,
//                     map: $map
//                 });
//             }
//         });
 
// }
 
// getLatLong({
// 	endereco: [
// 		'Rua André Gallo, 110 - Londrina, PR',
// 		'Rua Cambará, 585 - Londrina, PR',
// 	]
//     zoom: 15,
//     dom: 'mapContent'
// });

	
	latitude  = new Array;
	longitude = new Array;
	for(var i = 0; i < 3; i++){
		
		




	};


	

	
	lat[0] = -12.991461; lng[0] = -38.459198;


	title = new Array; 
	title[0] =  'Distribuidor em: Salvador – BA';

	content = new Array;
	content[0] = '<p><strong>Bahiafix</strong></p><p>Salvador - BA</p><p>CEP: 41.810-110</p><p>(71) 3351-4640 / 3491-1899</p><a href="mailto:bahiafix@hotmail.com">bahiafix@hotmail.com</a>';


	var map = null;
	function load() {
	    map = new google.maps.Map(document.getElementById("mapContent"),{
        	center: new google.maps.LatLng(lat,lng),
        	zoom: 16,
        	mapTypeId: 'roadmap'
    	});
	}

	var tm1 = null;
	var tm2 = null;
	function addEnd(lat, lng, title, content){
	    var point = new google.maps.LatLng(lat,lng);
	    var marker = new google.maps.Marker({
	        map: map,
	        position: point,
	        title: title,
	        icon: "http://www.google.com/intl/en_us/mapfiles/ms/icons/blue-dot.png"
	    });
	    var infowindow = new google.maps.InfoWindow({
	        content: content,
	        size: new google.maps.Size(200, 200)
	    });
	    google.maps.event.addListener(marker, 'click', function() {
	        infowindow.close();
	        infowindow.open(map, marker);
	    });
	}
	$(function(){
	    if(jQuery("#mapContent").length){
	        load();
	    }
	});