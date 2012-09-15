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
 ****	VIEWTASK.html JS
 */ 
 
 
 function makeTasks(){
	 var i = 1;
	 while (localStorage.getItem(i.toString()) !== null ){
		 task = localStorage.getItem(i.toString());
		 alert (task);
		 task = jQuery.parseJSON(task);
		 $("#viewtasks").prepend("<div class='taskLine'><div class='taskName'>"+task.taskName+"</div><div class='taskDeadline'>"+task.taskDeadline+"</div></div>");
		 i++;
	 }
 }
 
 $(document).ready(makeTasks());