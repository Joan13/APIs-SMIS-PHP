$(document).ready(function() {

$('#progress_bar_div').fadeIn();

	pupil_marks();

	function pupil_marks() {
		let cycle = $('#cycle').val();
		let class_id = $('#class_id').val();
		let order_id = $('#order_id').val();
		let section_id = $('#section_id').val();
		let option_id = $('#option_id').val();
		let school_year = $('#school_year').val();
		let pupil_id = $('#pupil_id').val();
		
		$.post('pages/ajax/pupil.marks.ajax.php', { cycle:cycle, class_id:class_id, order_id:order_id, section_id:section_id, option_id:option_id, school_year:school_year, pupil_id:pupil_id }, function(data) {
			$('#print_marks_div').html(data);
			$('#progress_bar_div').fadeOut();
		});
	}

	function printContent(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		
		window.print();
		
		document.body.innerHTML = originalContents;
	}

	$('.print_bulletins_brouillon').on('click', () => {
		printContent("bulletins_draft_class");
		//printContent("print_pupil_bulletin");
	});
});
