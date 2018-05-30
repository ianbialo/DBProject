"use strict";
/**
 * 
 */

/**
 * Application regroupant toutes les fonctions utiles au formulaires 
 */
let application = (function(){

	/**
	 * Booléen permettant de récupérer les informations sur la structure si il est mentionné "autre"
	 */
	let changed = true;

	/**
	 * Booléen utilisé dans l'identification du parrain
	 */
	let changed2 = true;

	/**
	 * Nombre de co-fondateurs
	 */
	let nbCo = 0;

	/**
	 * Iterateur utilisé dans la récupération des co-fondateurs
	 */
	let valNbCo = 0;

	/**
	 * Nombre de fichier visible à l'écran
	 */
	let nbFile = 0;

	/**
	 * Iterateur utilisé dans la récupération des fichiers
	 */
	let valNbFile = 0;

	return{
		
		/**
		 * Méthode lancée au chargement de la page
		 */
		run : function(){
			if(application.isset($("#selecteur"))){
				$("#selecteur").find("#divautre").toggle();
				$("#parrain").find("#divautre2").toggle();
				application.listener();
			}
		},

		/**
		 * Méthode déterminant si un élément existe sur la page
		 */
		isset : function(element){
			return element.length > 0;
		},

		/**
		 * Méthode initialisant tout les listeners sur les éléments de la page
		 */
		listener : function(){
			
			/**
			 * Méthode enclenchée dès lors que le formulaire est soumis
			 */
			$('#formFormulaire').submit(function() {
				
				//On vérifie qu'il n'existe pas des fichiers joints identiques
				let nbFiles = $("#nbFile").val();
				let res = [];
				for(let a = 0; a < nbFiles; a++){
					let files = $("#fileToUpload"+a)[0].files;

					for (let i = 0; i < files.length; i++)
					{
						if(res.indexOf(files[i].name) > -1) {
							alert("Deux fichiers identiques ont été joints. Veuillez en supprimer un.");
							return false;
						}
						else res.push(files[i].name);
					}
				}
				
				
				return true;
			});
			
			
			
			/**
			 * Méthode enclenché si on appuie sur le bouton d'ajout de co-fondateur
			 */
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

			/**
			 * Méthode enclenché si on appuie sur le bouton d'ajout de pièce jointe
			 */
			$("#boutonfileajout").on("click",function(){

				//S'il y a moins de 5 co-fondateurs
				if(nbFile < 5){

					//Ajout
					let x = valNbFile;
					$( ".coFile" ).append( $('<div id="divFile'+x+'">').append('<input type="button" id="btnFile'+x+'" name="btnFile'+x+'" value="-">').append('<input type="file" name="fileToUpload'+x+'" id="fileToUpload'+x+'" required>'));
					nbFile++;
					valNbFile++;

					//Ajoute à l'input caché le (nouveau) nombre de co-fondateurs
					$( "#nbFile" ).val(nbFile);

					//Ajout du listener sur le nouveau bonton créé : supprime l'input et décrémente la valeur de l'input caché
					$( "#btnFile"+x ).on("click",function(){
						$( "#divFile"+x ).remove();
						nbFile--;
						$( "#nbFile" ).val(nbFile);
					})
				}
			}),

			
			/**
			 * Méthode enclenché si on interagit avec le combobox lié au type de la structure
			 */
			$("#vousetes").change(function() {
				
				//Si l'option "autre" est sélectionné
				if($( this ).val()==0){
					$("#selecteur").find("#divautre").toggle();
					$("#selecteur").find("#divautre").find("#autre").prop('required',true);
					application.changed = false;
				}
				else {
					//Si l'optin précédente était "autre"
					if(application.changed == false){
						$("#selecteur").find("#divautre").toggle();
						$("#selecteur").find("#divautre").find("#autre").prop('required',false);
						application.changed = true;
					}

				}
			});

			/**
			 * Méthode enclenché si on interagit avec les radio buttons liés au parrain
			 */
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

//Lancement de l'application dès lors que la page est chargée
$(document).ready(function () {
	application.run();
});