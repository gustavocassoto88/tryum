$(function() {
	// Configura os botões do bbcode
	myBbcodeSettings = {
		markupSet: [
			{name:'Negrito', key:'B', openWith:'[b]', closeWith:'[/b]'},
			{name:'Italico', key:'I', openWith:'[i]', closeWith:'[/i]'},
			{name:'Sublinhado', key:'U', openWith:'[u]', closeWith:'[/u]'},
			{separator:'---------------' },
			{name:'Titulo 1', key:'2', openWith:'[h2]', closeWith:'[/h2]'},
			{name:'Titulo 2', key:'3', openWith:'[h3]', closeWith:'[/h3]'},
			{name:'Titulo 3', key:'4', openWith:'[h4]', closeWith:'[/h4]'}
		]
	}

	// Adiciona o bbcode ao textarea
	$('#texto').addClass('bbcode-area').markItUp(myBbcodeSettings);
});