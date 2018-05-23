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
			
			/**var mysql = require('mysql');

			var con = mysql.createConnection({
			  host: "localhost",
			  user: "hr",
			  password: "ib1234admin"
			});

			con.connect(function(err) {
			  if (err) throw err;
			  console.log("Connected!");
			});*/
			
			/**require(["mysql"],function(mysql){
				var con = mysql.createConnection({
					  host: "localhost",
					  user: "hr",
					  password: "ib1234admin"
					});

					con.connect(function(err) {
					  if (err) throw err;
					  console.log("Connected!");
					});
			});*/
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

			$(document).on('change', '.file-field input[type="file"]', function () {
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
			});

			$("input:file").change(function (){
				let inp = document.getElementById('fileToUpload');
				let res = "";
				for (let i = 0; i < inp.files.length; i++) {
					let name = inp.files.item(i).name;
					res += " - "+name;
					//alert("here is a file name: " + name);
				}
				if(res == "") $("#nomFichier").text("- Aucun fichier sélectionné");
				else $("#nomFichier").text(res);
			});


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