/*
 *	BlackBerry 10 OS Tablet App - Hackathon Campinas / Brazil
 *  App: 		"App Name"
 *	Authors:	Andre Vitor Terron
 *				Andre Seiji Tamanaha
 *				Thiago Yukio Itagaki
 *	Version: 	1.0.0.0
 *	Data: 		15/09/2012
 */

/*
 ****	General JS
 */ 

var isOnline = false;
var loginID = localStorage.getItem("loginID");
var loginUser = localStorage.getItem("loginUser");

window.addEventListener("offline",function(e){
	isOnline = false;
},false);
window.addEventListener("online",function(e){
	isOnline = true;
	sendTempTasks();
},false);

function showNotification(error){
	// Mostra notificação
}


/*
 ****	TASK.html JS
 */
function sendTask(task){
	$.ajax({
		cache: false,
		data: JSON.stringify(task),
		dataType: "json",
		url: "http://andreterron.com/weachieve/create.php",
		type: "POST",
		complete: function(xhr,status){
			switch(status){
				case "success":
					return true;
					break;
				case "error":
				case "notmodified":
				case "timeout":
				case "abort":
				case "parsererror":
					return false;
					break;
			}
		}
	 });
}
 
function createTask(){
	var tempTasks = localStorage.getItem("tempTasks");
	var tempTask = new Object();
	 
	// Cria objeto da Task atual
	tempTask.logID = loginID;
	tempTask.taskName = $("input[name='taskName']").val();
	tempTask.taskGroup = $("input[name='taskGroup']").val();
	tempTask.taskPrivacy = $("select[name='taskPrivacy]").val();
	tempTask.taskDeadline = $("input[name='taskDeadline']").val();
	 
	// Se estiver online envia a Task para o servidor
	if (isOnline){
		taskSent = sendTask(tempTask);
	}
	
	// Apaga a userID para economizar espaço de armazenamento
	delete tempTask.userID;
	
	// Se não tiver enviado a Task para o servidor armazena a Task JSON localmente em um arquivo temporário compartilhado
	if (!taskSent) tempTasks = tempTasks+";"+JSON.stringify(tempTask);
	// Se a Task foi recebida pelo servidor, grava a Task JSON localmente em uma variavel própria
	else localStorage.setItem(tempTask.taskID,JSON.stringify(tempTask));
	
	// Grava as Tasks temporárias novamente
	localStorage.setItem("tempTasks",tempTasks);
}

function sendTempTasks(){
	var tempTasks = localStorage.getItem("tempTasks");
	
	// Checa se TempTasks não está vazia e explode os valores
	if (tempTasks){
		tempTasks = tempTasks.slip(";");
		// Para cada task temporária, envia-a para o servidor e exclui caso seja enviado
		for (var n in tempTasks){
			tempTasks[n] = jQuery.parseJSON(tempTasks[n]);
			tempTasks[n].logID = loginID;
			taskSent = sendTask(tempTasks[n]);
			if (taskSent) delete tempTasks[n];
			else delete tempTasks[n].logID;
		}
	}
	
	// Reagrupa as tasks que restaram
	tempTaskString = "";
	for (var n in tempTasks){
		if (n) tempTaskString += ";";
		tempTaskString += JSON.stringigy(tempTasks[n]);
	}
	// Armazena localmente as Tasks temporariamente
	localStorage.setItem("tempTasks",tempTaskString);
}

/*
 ****	LOGIN.html JS
 */

function logUser(){
	var user = new Object();
	user.userLogin = $("input[name='loginUser']").val();
	user.userPassword = $("input[name='loginPassword']").val();
	
	if (user.userLogin == localStorage.getItem("fakeLogin") && user.userPassword == localStorage.getItem("fakePass")){
		window.location = "index.html";
	}
/*	$.ajax({
		cache: false,
		data: JSON.stringify(user),
		dataType: "jsonp",
		jsonp: false,
		jsonpCallback: "jsonLogin",
		crossDomain: true,
		url: "http://andreterron.com/weachieve/login.php",
		type: "POST",
		complete: function(xhr,status){
			switch(status){
				case "success":
					alert ("success");
					return true;
					break;
				case "error":
				case "notmodified":
				case "timeout":
				case "abort":
				case "parsererror":
					alert (status+"\n"+xhr);
					console.log(xhr);
					return false;
					break;
			}
		}
		success: function(response){
			console.log(response);
		}
	});*/
}

$("input[name='loginButton']").live("click",logUser);
 
 /*
 ****	General JS
 */ 

$(document).ready(function(){
	if ((!loginID || !loginUser) && window.location.pathname!="/login.html" ) window.location="login.html";
});