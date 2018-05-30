"use strict";
/**
 * 
 */

/**
 * Application regroupant toutes les fonctions utiles au formulaires
 */
let application = (function(){

	return{

		/**
		 * Méthode lancée au chargement de la page
		 */
		run : function(){
			if(application.isset($("#formSuivi"))){
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
			
			/**$(document).on('change', '.file-field input[type="file"]', function () {
				var file_field = $(this).closest('.file-field');
				var path_input = file_field.find('input.file-path');
				var files      = $(this)[0].files;      
				var file_names = [];
				for (var i = 0; i < files.length; i++) {
					file_names.push(files[i].name);
				}
				path_input.val(file_names.join(", "));
				path_input.trigger('change');
				console.log(file_names);
				alert("J'existe");
			});*/

			/**
			 * Méthode lancée lors de l'ajout/suppression d'un fichier dans l'input file
			 */ 
			$("input:file").change(function (){
				let inp = document.getElementById('fileToUpload');
				let res = "";
				for (let i = 0; i < inp.files.length; i++) {
					let name = inp.files.item(i).name;
					res += " - "+name;
					//alert("here is a file name: " + name);
				}
				//On modifie le texte selon les fichiers enrengistrés.
				if(res == "") $("#nomFichier").text("- Aucun fichier sélectionné");
				else $("#nomFichier").text(res);
			});
		},
	}

})();

//Lancement de l'application dès lors que la page est chargée
$(document).ready(function () {
	application.run();
});