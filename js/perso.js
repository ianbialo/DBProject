/**
 * 
 */

application = (function(){
	let changed = true;
	let changed2 = true;
	let nbCo = 0;

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
				//$('<div id="in">').append('<input type="button" value="-">').append('<input type="text" id="" name="" maxlength="90"></input>').insertAfter('#labelcofin');
				if(nbCo < 5){
					let x = nbCo;
					$( ".cofin" ).append( $('<div id="divco'+x+'">').append('<input type="button" id="btnco'+x+'" name="btnco'+x+'" value="-">').append('<input type="text" id="nomco'+x+'" name="nomco'+x+'" maxlength="90" placeholder="Nom" required></input>').append('<input type="text" id="prenomco'+x+'" name="prenomco'+x+'" maxlength="90" placeholder="Prenom" required></input>') );
					nbCo++;
					$( "#nbCo" ).val(nbCo);

					$( "#btnco"+x ).on("click",function(){
						$( "#divco"+x ).remove();
						nbCo--;
						$( "#nbCo" ).val(nbCo);
					})
				}
			}),

			$("#vousetes").change(function() {
				//console.log($( this ).val());
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
				//console.log($('input[name=group1]:checked', '#parrain').val()); 
				$("#parrain").find("#divautre2").toggle();
				if($('input[name=group1]:checked', '#parrain').val() == 1) $("#parrain").find("#divautre2").find("#txtparrain").prop('required',true);
				else $("#parrain").find("#divautre2").find("#txtparrain").prop('required',false);
			});
		}


	}

})();

$(document).ready(function () {
	application.run();
});