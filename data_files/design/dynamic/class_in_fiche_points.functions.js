$(document).ready(function() {

	function printContent(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		
		window.print();
		
		document.body.innerHTML = originalContents;
	  }
  
	  $('.printFicheClasse').on('click', function() {
		  printContent("ficheClasse");
	});

	$('#fiche_des_points').on('click', () => {
		$('.show_third_party').slideToggle(400);
	});

	$('#fiche_de_conduite').on('click', () => {
		$('.show_third_party_conduite').slideToggle(400);
	});

});
