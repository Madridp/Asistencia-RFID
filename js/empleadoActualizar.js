var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList;
var SpeechRecognitionEvent = SpeechRecognitionEvent || webkitSpeechRecognitionEvent;

var textError = document.querySelector('.error');
var campoNombre = document.querySelector('#nombre');
var campoPuesto = document.querySelector('#id_puesto');
var campoTelefono = document.querySelector('#telefono');

var escuchaNombre = document.querySelector('#start_nombre');
var escuchaPuesto = document.querySelector('#start_puesto');
var escuchaTelefono = document.querySelector('#start_telefono');
var escuchaGuardar = document.querySelector('#start_guardar');
var escuchaCancelar = document.querySelector('#start_cancelar');

var formulario_agregarEmpleado = document.getElementById("formulario_actualizarEmpleado");

var frase = 'guardar';
var frase1 = 'cancelar';
var activo = false;


function inicioNombre(){
	
	
	if(activo){
		escuchaNombre.src = 'img/mic.gif';
		activo = false;
	}
	else{
		escuchaNombre.src = 'img/mic-animate.gif';
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
				campoNombre.value=resultadoEscucha;
			}
			else{
				textError.textContent = "No escuchamos nada. Intentalo de nuevo!"

			}
			
			console.log('Confidencial: ' + event.results[0][0].confidence);
		}
	}
	reconocimiento.onspeechend = function() {
		reconocimiento.stop();
		escuchaNombre.src = 'img/mic.gif';
		activo = false;
	}
	reconocimiento.onerror = function(event) {
		textError.textContent = 'Algo fallo intentalo de nuevo';
		console.log('error' +event.error);
	}
}
function inicioPuesto(){
	
	
	if(activo){
		escuchaPuesto.src = 'img/mic.gif';
		activo = false;
	}
	else{
		escuchaPuesto.src = 'img/mic-animate.gif';
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
				// campoPuesto.value=resultadoEscucha;

				let combo = document.getElementById("id_puesto");
				if ( combo != null ){
					seleccionarPuestoCorrecto(combo, resultadoEscucha);
				}
			}
			else{
				textError.textContent = "No escuchamos nada. Intentalo de nuevo!"

			}
			
			console.log('Confidencial: ' + event.results[0][0].confidence);
		}
	}
	reconocimiento.onspeechend = function() {
		reconocimiento.stop();
		escuchaPuesto.src = 'img/mic.gif';
		activo = false;
	}
	reconocimiento.onerror = function(event) {
		textError.textContent = 'Algo fallo intentalo de nuevo';
		console.log('error' +event.error);
	}
}
function inicioTelefono(){
	
	
	if(activo){
		escuchaTelefono.src = 'img/mic.gif';
		activo = false;
	}
	else{
		escuchaTelefono.src = 'img/mic-animate.gif';
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
				campoTelefono.value=resultadoEscucha;
			}
			else{
				textError.textContent = "No escuchamos nada. Intentalo de nuevo!"

			}
			
			console.log('Confidencial: ' + event.results[0][0].confidence);
		}
	}
	reconocimiento.onspeechend = function() {
		reconocimiento.stop();
		escuchaTelefono.src = 'img/mic.gif';
		activo = false;
	}
	reconocimiento.onerror = function(event) {
		textError.textContent = 'Algo fallo intentalo de nuevo';
		console.log('error' +event.error);
	}
}

if ( escuchaNombre != null ){
	escuchaNombre.addEventListener('click', inicioNombre);
}

if ( escuchaPuesto != null ){
	escuchaPuesto.addEventListener('click', inicioPuesto);
}

if ( escuchaTelefono != null ){
	escuchaTelefono.addEventListener('click', inicioTelefono);
}


function guardar(){
	if (activo){
		escuchaGuardar.src = 'img/mic.gif';
		activo = false;
	}else{
		escuchaGuardar.src = 'img/mic-animate.gif';
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
			if(resultadoEscucha === 'guardar'){
				console.log('crear');
				//document.location="validar.php";
				// formulario_actualizarEmpleado.submit();
				if ( document.getElementById("guardar") ){
					document.getElementById("guardar").click();
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
		escuchaGuardar.src = 'img/mic.gif';
		activo = false;
	}
	reconocimiento.onerror = function(event) {
		textError.textContent = 'Algo fallo intentalo de nuevo';
		console.log('error' +event.error);
	}
	
}

if ( escuchaGuardar != null ){
	escuchaGuardar.addEventListener('click', guardar);
}


if ( document.getElementById('guardar') ){
	document.getElementById('guardar').addEventListener("click",(e)=>{
		e.preventDefault();

		let id_empleado = document.getElementById("id").value;
		let nombre = document.getElementById("nombre").value;
		let puesto = document.getElementById("id_puesto").value;
        let telefono = document.getElementById("telefono").value;
		let id_usuario = document.getElementById("id_usuario").value;

		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'empleado_actualizar.php', true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.onload = function () {
			console.log(this.responseText);

			if ( this.responseText ==  1 ){
				decir("empleado actualizado correctamente");
				document.getElementById('mensaje_error').innerText = "";
				window.location.href = "empleados.php";
			}else{
				decir("No se pudo actualizar");
				document.getElementById('mensaje_error').innerText = "Datos invalidos";
			}
		};
		xhr.send("nombre=" + nombre + "&id_puesto=" + puesto + "&telefono=" + telefono + "&id=" + id_empleado + "&id_usuario=" + id_usuario);
	});
}


function cancelar(){
	if (activo){
		escuchaCancelar.src = 'img/mic.gif';
		activo = false;
	}else{
		escuchaCancelar.src = 'img/mic-animate.gif';
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
			if(resultadoEscucha === 'cancelar'){
				console.log('crear');
				//document.location="validar.php";
				//formulario_agregarEmpleado.submit();
				if ( document.getElementById("cancelar") ){
					document.getElementById("cancelar").click();
					//decir("Proceso cancelado");
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
		escuchaCancelar.src = 'img/mic.gif';
		activo = false;
	}
	reconocimiento.onerror = function(event) {
		textError.textContent = 'Algo fallo intentalo de nuevo';
		console.log('error' +event.error);
	}
	
}

if ( escuchaCancelar != null ){
	escuchaCancelar.addEventListener('click', cancelar);
}

if ( document.getElementById('cancelar') ){
	document.getElementById('cancelar').addEventListener("click",(e)=>{
		e.preventDefault();
		
		decir("Proceso cancelado");
		window.location.href = "empleados.php";
	});
}

function decir(aux){
	speechSynthesis.speak(new SpeechSynthesisUtterance(aux));
}

function seleccionarPuestoCorrecto(combo, texto_escuchado){
	
	for (var i = 0; i < combo.length; i++) {
		//  Aca haces referencia al "option" actual
		var opt = combo[i];
	
		// Haces lo que te de la gana aca
		//let texto_option = opt.value;
		let texto_option = opt.text;

		// seleccionar el option que coincida con el texto escuchado
		if ( texto_option.toUpperCase() == texto_escuchado.toUpperCase() ){
			opt.selected = true;
			break;
		}
	}

}