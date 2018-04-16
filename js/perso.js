/**
 * 
 */

application = (function(){
	let changed = true;
	let changed2 = true;

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