var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList;
var SpeechRecognitionEvent = SpeechRecognitionEvent || webkitSpeechRecognitionEvent;

var textError = document.querySelector('.error');
var campoBuscar = document.querySelector('#buscar');
var escuchaBuscar = document.querySelector('#start_buscar');
var activo = false;


function inicioBuscar(){
	
	
	if(activo){
		escuchaBuscar.src = 'img/mic.gif';
		activo = false;
	}
	else{
		escuchaBuscar.src = 'img/mic-animate.gif';
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
				campoBuscar.value=resultadoEscucha;
				buscar_ahora2(resultadoEscucha);
				console.log('LLega a buscar');
				decir("Resultados encontrados");
			}
			else{
				textError.textContent = "No escuchamos nada. Intentalo de nuevo!"

			}
			
			console.log('Confidencial: ' + event.results[0][0].confidence);
		}
	}
	reconocimiento.onspeechend = function() {
		reconocimiento.stop();
		escuchaBuscar.src = 'img/mic.gif';
		activo = false;
	}
	reconocimiento.onerror = function(event) {
		textError.textContent = 'Algo fallo intentalo de nuevo';
		console.log('error' +event.error);
	}
}

if ( escuchaBuscar != null ){
	escuchaBuscar.addEventListener('click', inicioBuscar);
}

function buscar_ahora2 (buscar){
	// var parametros = {"buscar": buscar};
	/*
	$ajax({
		data:parametros,
		type:'POST',
		url: 'buscar.php',
		success: function (data){
			console.log(data);
			document.getElementById("datos_buscador").innerHTML = data;
		}
	})
	*/

	let request = new XMLHttpRequest();
	request.addEventListener("load", requestPeticionBuscarEmpleado2);
	request.open("GET", "buscar.php?buscar=" + buscar);
	request.send();
}

function requestPeticionBuscarEmpleado2(){
	if ( this.responseText != "" ){
		console.clear();
		let data = JSON.parse(this.responseText);
		
		actualizarTablaEmpleados2(data);
	}
}

function actualizarTablaEmpleados2(data){
	let body = document.getElementById("datos_buscador");
	body.innerHTML = ""; // limpiar la tabla

	if ( data != null ){
		if ( data.length > 0 ){
			console.log(data);
			for(i = 0; i < data.length; i++){
				let fila_nueva = document.createElement("tr");
				
				let td_id = document.createElement("td");
				td_id.appendChild(document.createTextNode(data[i].id_empleado));

				let td_nombre = document.createElement("td");
				td_nombre.appendChild(document.createTextNode(data[i].nombre));

				let td_telefono = document.createElement("td");
				td_telefono.appendChild(document.createTextNode(data[i].telefono));

				let td_puesto = document.createElement("td");
				td_puesto.appendChild(document.createTextNode(data[i].puesto_nombre));

				let td_editar = document.createElement("td");
				let a_editar = document.createElement('a');
				a_editar.setAttribute("class", "btn btn-warning");
				a_editar.setAttribute("href", "empleado_editar.php?id=" + data[i].id_empleado);
				a_editar.text = "Editar";
				let i_editar = document.createElement("i");
				i_editar.setAttribute("class", "fa fa-edit");
				a_editar.appendChild(i_editar);
				td_editar.appendChild(a_editar);

				let td_eliminar = document.createElement("td");
				let a_eliminar = document.createElement('a');
				a_eliminar.setAttribute("class", "btn btn-danger");
				a_eliminar.setAttribute("href", "empleado_eliminar.php?id=" + data[i].id_empleado);
				a_eliminar.text = "Eliminar";
				let i_eliminar = document.createElement("i");
				i_eliminar.setAttribute("class", "fa fa-trash");
				a_eliminar.appendChild(i_eliminar);
				td_eliminar.appendChild(a_eliminar);

				fila_nueva.appendChild(td_id);
				fila_nueva.appendChild(td_nombre);
				fila_nueva.appendChild(td_telefono);
				fila_nueva.appendChild(td_puesto);
				fila_nueva.appendChild(td_editar);
				fila_nueva.appendChild(td_eliminar);

				body.appendChild(fila_nueva);
			}
		}else{
			let fila_nueva = document.createElement("tr");
			let td = document.createElement("td");

			td.colSpan = 6;
			td.appendChild(document.createTextNode("** NO HAY DATOS **"));

			fila_nueva.appendChild(td);
			body.appendChild(fila_nueva);
		}
	}
}

function decir(aux){
	speechSynthesis.speak(new SpeechSynthesisUtterance(aux));
}