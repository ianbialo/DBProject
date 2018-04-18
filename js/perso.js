/**
 * 
 */

application = (function(){
	
	//Booléen permettant de récupérer les informations sur la structure si il est mentionné "autre"
	let changed = true;
	
	//Booléen utilisé dans l'identification du parrain
	let changed2 = true;
	
	//Nombre de co-fondateurs
	let nbCo = 0;
	
	//Iterateur utilisé dans la récupération des co-fondateurs
	let valNbCo = 0;

	return{
		run : function(){
			if(application.isset($("#selecteur"))){
				$("#selecteur").find("#divautre").toggle();
				$("#parrain").find("#divautre2").toggle();
				application.listener();
			}
		},

		isset : function(element){
			return element.length > 0;
		},

		listener : function(){

			$("#boutoncofin").on("click",function(){
				
				//S'il y a moins de 5 co-fondateurs
				if(nbCo < 5){
					
					//Ajout
					let x = valNbCo;
					$( ".cofin" ).append( $('<div id="divco'+x+'">').append('<input type="button" id="btnco'+x+'" name="btnco'+x+'" value="-">').append('<input type="text" id="nomco'+x+'" name="nomco'+x+'" maxlength="90" placeholder="Nom" required></input>').append('<input type="text" id="prenomco'+x+'" name="prenomco'+x+'" maxlength="90" placeholder="Prenom" required></input>') );
					nbCo++;
					valNbCo++;
					
					//Ajoute à l'input caché le (nouveau) nombre de co-fondateurs
					$( "#nbCo" ).val(nbCo);

					//Ajout du listener sur le nouveau bonton créé : supprime l'input et décrémente la valeur de l'input caché
					$( "#btnco"+x ).on("click",function(){
						$( "#divco"+x ).remove();
						nbCo--;
						$( "#nbCo" ).val(nbCo);
					})
				}
			}),

			$("#vousetes").change(function() {
				if($( this ).val()==0){
					$("#selecteur").find("#divautre").toggle();
					$("#selecteur").find("#divautre").find("#autre").prop('required',true);
					application.changed = false;
				}
				else {
					if(application.changed == false){
						$("#selecteur").find("#divautre").toggle();
						$("#selecteur").find("#divautre").find("#autre").prop('required',false);
						application.changed = true;
					}

				}
			});

			$('#parrain').change(function() {
				
				//Si le bouton vient d'être changé sur "Vrai"
				if(changed2 && ($('input[name=group1]:checked', '#parrain').val() == 1)){
					$("#parrain").find("#divautre2").toggle();
					changed2 = false;
				}
				
				//Si le bouton vient d'être changé sur "Faux"
				if(!changed2 && ($('input[name=group1]:checked', '#parrain').val() == 0)){
					$("#parrain").find("#divautre2").toggle();
					changed2 = true;
				}
				
				//Si le bouton est vrai, ajoute l'attribut required sur les inputs, sinon les enlève
				if($('input[name=group1]:checked', '#parrain').val() == 1) {
					$("#parrain").find("#divautre2").find("#nomparrain").prop('required',true);
					$("#parrain").find("#divautre2").find("#prenomparrain").prop('required',true);
				}
				else {
					$("#parrain").find("#divautre2").find("#nomparrain").prop('required',false);
					$("#parrain").find("#divautre2").find("#prenomparrain").prop('required',false);
				}
			});
		}


	}

})();

$(document).ready(function () {
	application.run();
});