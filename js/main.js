var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList;
var SpeechRecognitionEvent = SpeechRecognitionEvent || webkitSpeechRecognitionEvent;


var textError = document.querySelector('.error');
var campoUsuario = document.querySelector('#usuario');
var campoContrasena = document.querySelector('#contrasena');
var escuchaUsuario = document.querySelector('#start_usuario');
var escuchaContrasena = document.querySelector('#start_contrasena');

var escuchaIngresar = document.querySelector('#start_ingresar');

var frase = 'ingresar';
//var btn_ingresar = document.getElementById("ingresar");
var formulario_login = document.getElementById("formulario_login");

var activo = false;

function inicioUsuario(){
	
	
	if(activo){
		escuchaUsuario.src = 'img/mic.gif';
		activo = false;
	}
	else{
		escuchaUsuario.src = 'img/mic-animate.gif';
		activo = true;

		var reconocimiento = new SpeechRecognition();
		reconocimiento.lang = 'es-GT';
		reconocimiento.interimResults = false;
		reconocimiento.maxAlternatives = 1;

		reconocimiento.start();

		reconocimiento.onresult = function(event){

			var resultadoEscucha = event.results[0][0].transcript;
			textError.textContent = resultadoEscucha;
			if(resultadoEscucha != ''){
				console.log('correcto');
				campoUsuario.value=resultadoEscucha;
			}
			else{
				textError.textContent = "No escuchamos nada. Intentalo de nuevo!"

			}
			
			console.log('Confidencial: ' + event.results[0][0].confidence);
		}
	}
	reconocimiento.onspeechend = function() {
		reconocimiento.stop();
		escuchaUsuario.src = 'img/mic.gif';
		activo = false;
	}
	reconocimiento.onerror = function(event) {
		textError.textContent = 'Algo fallo intentalo de nuevo';
		console.log('error' +event.error);
	}
}
function inicioContrasena(){
	
	
	if(activo){
		escuchaContrasena.src = 'img/mic.gif';
		activo = false;
	}
	else{
		escuchaContrasena.src = 'img/mic-animate.gif';
		activo = true;

		var reconocimiento = new SpeechRecognition();
		reconocimiento.lang = 'es-GT';
		reconocimiento.interimResults = false;
		reconocimiento.maxAlternatives = 1;

		reconocimiento.start();

		reconocimiento.onresult = function(event){

			var resultadoEscucha = event.results[0][0].transcript;
			textError.textContent = resultadoEscucha;
			if(resultadoEscucha != ''){
				console.log('correcto');
				campoContrasena.value=resultadoEscucha;
			}
			else{
				textError.textContent = "No escuchamos nada. Intentalo de nuevo!"

			}
			
			console.log('Confidencial: ' + event.results[0][0].confidence);
		}
	}
	reconocimiento.onspeechend = function() {
		reconocimiento.stop();
		escuchaContrasena.src = 'img/mic.gif';
		activo = false;
	}
	reconocimiento.onerror = function(event) {
		textError.textContent = 'Algo fallo intentalo de nuevo';
		console.log('error' +event.error);
	}
}

if ( escuchaContrasena != null ){
	escuchaUsuario.addEventListener('click', inicioUsuario);
}

if ( escuchaContrasena != null ){
	escuchaContrasena.addEventListener('click', inicioContrasena);
}

function ingresar(){
	if (activo){
		escuchaIngresar.src = 'img/mic.gif';
		activo = false;
	}else{
		escuchaIngresar.src = 'img/mic-animate.gif';
		activo = true;

		var gramatica = '#JSGF V1.0; grammar phrase; public <frase> = ' + frase +';';
		var reconocimiento = new SpeechRecognition();
		var listaReconocido = new SpeechGrammarList();
		listaReconocido.addFromString(gramatica,1);
		reconocimiento.grammar = listaReconocido;
		reconocimiento.lang = 'es-GT';
		reconocimiento.interimResults = false;
		reconocimiento.maxAlternatives = 1;
		reconocimiento.start();

		reconocimiento.onresult = function(event){

			var resultadoEscucha = event.results[0][0].transcript;
			textError.textContent = resultadoEscucha;
			if(resultadoEscucha === 'ingresar'){
				console.log('crear');
				//document.location="validar.php";
				//formulario_login.submit();
				//document.getElementById('ingresar');
				if ( document.getElementById("ingresar") ){
					document.getElementById("ingresar").click();
				}
			}
			else{
				console.log('nada');
			}
			console.log('Confidencial: ' + event.results[0][0].confidence);
		}
	}
	reconocimiento.onspeechend = function() {
		reconocimiento.stop();
		escuchaIngresar.src = 'img/mic.gif';
		activo = false;
	}
	reconocimiento.onerror = function(event) {
		textError.textContent = 'Algo fallo intentalo de nuevo';
		console.log('error' +event.error);
	}
	
}

if ( escuchaIngresar != null ){
	escuchaIngresar.addEventListener('click', ingresar);
}


/*let rec;
	if (!("webkitSpeechRecognition" in window)){
		alert("disculpas, no puedes usar la API");
	}else{
		rec = new webkitSpeechRecognition();
		rec.lang = "es-GT";
		rec.continuos = true;
		rec.interim = true;
		rec.addEventListener("result", iniciar);
	}

function iniciar(event){
	for( i = event.resultIndex; i < event.results.length; i++){
		document.getElementById('usuario').innerHTML = event.results[i][0].transcript;
	}
}
rec.start();*/

if ( document.getElementById('ingresar') ){
	document.getElementById('ingresar').addEventListener("click",(e)=>{
		e.preventDefault();
		// let formulario = document.getElementById("formulario_ingresar");
		// formulario.submit();
		// decir(document.getElementById("aux").value);
		// e.stopPropagation();

		let usuario = document.getElementById("usuario").value;
		let contrasenia = document.getElementById("contrasena").value;

		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'validar.php', true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.onload = function () {
			console.log(this.responseText);

			if ( this.responseText ==  1 ){
				decir(document.getElementById("aux").value);
				document.getElementById('mensaje_error').innerText = "";
				window.location.href = "empleados.php";
			}else{
				decir("Usuario invalido");
				document.getElementById('mensaje_error').innerText = "Datos invalidos";
			}
		};
		xhr.send("usuario=" + usuario + "&contrasena=" + contrasenia);
	});
}

function decir(aux){
	speechSynthesis.speak(new SpeechSynthesisUtterance(aux));
}

