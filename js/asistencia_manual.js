var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList;
var SpeechRecognitionEvent = SpeechRecognitionEvent || webkitSpeechRecognitionEvent;

var textError = document.querySelector('.error');
var campoNombre = document.querySelector('#id_empleado');
var campoRegistro = document.querySelector('#tipo_asistencia');


var escuchaNombre = document.querySelector('#start_nombreEmpleado');
var escuchaRegistro = document.querySelector('#start_tipoRegistro');

var escuchaGuardar = document.querySelector('#start_guardar');
var escuchaCancelar = document.querySelector('#start_cancelar');

var formulario_agregarEmpleado = document.getElementById("registrar_asistencia_manual");

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
				//campoNombre.value=resultadoEscucha;
                let combo2 = document.getElementById("id_empleado");
				if ( combo2 != null ){
					seleccionarEmpleadoCorrecto(combo2, resultadoEscucha);
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
		escuchaNombre.src = 'img/mic.gif';
		activo = false;
	}
	reconocimiento.onerror = function(event) {
		textError.textContent = 'Algo fallo intentalo de nuevo';
		console.log('error' +event.error);
	}
}
function inicioRegistro(){
	
	
	if(activo){
		escuchaRegistro.src = 'img/mic.gif';
		activo = false;
	}
	else{
		escuchaRegistro.src = 'img/mic-animate.gif';
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
				//campoPuesto.value=resultadoEscucha;
				let combo = document.getElementById("tipo_asistencia");
				if ( combo != null ){
					seleccionarRegistroCorrecto(combo, resultadoEscucha);
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
		escuchaRegistro.src = 'img/mic.gif';
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

if ( escuchaRegistro != null ){
	escuchaRegistro.addEventListener('click', inicioRegistro);
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
				//formulario_agregarEmpleado.submit();
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

		//let id_empleado = document.getElementById("id").value;
		let nombre_empleado = document.getElementById("id_empleado").value;
		let registro = document.getElementById("tipo_asistencia").value;
        let fecha = document.getElementById("fecha_hora").value;
		
		if ( nombre_empleado == ""){
			decir("No ha indicado el nombre.");
			return;
		}

		if ( registro == ""){
			decir("¨No ha indicado el registro")
			return;
		}

        if ( fecha == ""){
			decir("¨No ha indicado la fecha")
			return;
		}

		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'asistencia_guardar.php', true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.onload = function () {
			console.log(this.responseText);

			if ( this.responseText ==  1 ){
				decir("Registro agregado correctamente");
				document.getElementById('mensaje_error').innerText = "";
				window.location.href = "lista_asistencia.php";
			}else{
				decir("No se pudo agregar");
				document.getElementById('mensaje_error').innerText = "Datos invalidos";
			}
		};
		xhr.send("id_empleado=" + nombre_empleado + "&tipo_asistencia=" + registro + "&fecha_hora=" + fecha);
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
function seleccionarEmpleadoCorrecto(combo2, texto_escuchado){
	
	for (var i = 0; i < combo2.length; i++) {
		//  Aca haces referencia al "option" actual
		var opt = combo2[i];
	
		// Haces lo que te de la gana aca
		//let texto_option = opt.value;
		let texto_option = opt.text;

		// seleccionar el option que coincida con el texto escuchado
		if ( texto_option.toUpperCase().includes(texto_escuchado.toUpperCase()) ){
			opt.selected = true;
			break;
		}
	}

}

function seleccionarRegistroCorrecto(combo, texto_escuchado){
	
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