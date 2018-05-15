"use strict";
/**
 * 
 */

//Application regroupant toutes les fonctions utiles au formulaires
let application = (function(){

	return{
		
		//Méthode lancée au chargement de la page
		run : function(){
			if(application.isset($("#formSuivi"))){
				application.listener();
			}
		},

		//Méthode déterminant si un élément existe sur la page
		isset : function(element){
			return element.length > 0;
		},
		
		//Méthode initialisant tout les listeners sur les éléments de la page
		listener : function(){
			//$("#dateRep").on("input", function() {
			//    alert("Change to " + this.value);
			//});
			
			$('#formSuivi').submit(function(){
				//alert($("#dateRep").val());
				//alert($("#dateEnvoiConv").val());
				//alert($("#dateRecepConv").val());
				//alert($("#dateRecepRecu").val());
				//alert($("#dateEnvoiCheque").val());
				
			    
			    
			});
		},
	}

})();

//Lancement de l'application dès lors que la page est chargée
$(document).ready(function () {
	application.run();
});