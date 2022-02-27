$(document).ready(function() {

	$("#validate_insert_pupil").on('click', function() {
		let first_name_pupil = $("#first_name_pupil").val();
		let second_name_pupil = $("#second_name_pupil").val();
		let last_name_pupil = $("#last_name_pupil").val();
		let gender_pupil = $("#gender_pupil").val();
		let birth_date_pupil = $("#pupil_birth_date").val();
		let birth_place_pupil = $("#pupil_birth_place").val();
		let father_name = $("#pupil_father_name").val();
		let mother_name = $("#pupil_mother_name").val();
		let parents_state = $("#parents_state").val();
		let parents_alive = $("#parents_alive").val();
		let lives_with = $("#lives_with").val();
		let father_work_pupil = $("#father_work_pupil").val();
		let mother_work_pupil = $("#mother_work_pupil").val();
		let cycle_school_pupil = $("#cycle_school_pupil").val();
		let class_school_pupil = $("#class_school_pupil").val();
		let class_order_pupil = $("#class_order_pupil").val();
		let class_section_pupil = $("#class_section_pupil").val();
		let class_option_pupil = $("#class_option_pupil").val();
		let school_year_pupil = $("#school_year_pupil").val();
		let email_address_pupil = $("#email_address_pupil").val();
		let physical_address_pupil = $("#physical_address_pupil").val();
		let contact_1_pupil = $("#contact_1_pupil").val();
		let contact_2_pupil = $("#contact_2_pupil").val();
		let contact_3_pupil = $("#contact_3_pupil").val();
		let contact_4_pupil = $("#contact_4_pupil").val();

		if(first_name_pupil == '' || second_name_pupil == '' || gender_pupil == '' || class_school_pupil == '' || cycle_school_pupil == '' || school_year_pupil == '')
		{
			$('.action_done_error_div').fadeIn();
			$('.action_done_error_div').fadeOut(5000);
		}
		else
		{
			$.post('pages/ajax/new_pupil.php',
			{
				first_name_pupil:first_name_pupil,
				second_name_pupil:second_name_pupil,
				last_name_pupil:last_name_pupil,
				gender_pupil:gender_pupil,
				birth_date_pupil:birth_date_pupil,
				birth_place_pupil:birth_place_pupil,
				father_name:father_name,
				mother_name:mother_name,
				parents_state:parents_state,
				parents_alive:parents_alive,
				lives_with:lives_with,
				father_work_pupil:father_work_pupil,
				mother_work_pupil:mother_work_pupil,
				cycle_school_pupil:cycle_school_pupil,
				class_school_pupil:class_school_pupil,
				class_order_pupil:class_order_pupil,
				class_section_pupil:class_section_pupil,
				class_option_pupil:class_option_pupil,
				school_year_pupil:school_year_pupil,
				email_address_pupil:email_address_pupil,
				physical_address_pupil:physical_address_pupil,
				contact_1_pupil:contact_1_pupil,
				contact_2_pupil:contact_2_pupil,
				contact_3_pupil:contact_3_pupil,
				contact_4_pupil:contact_4_pupil
			},
			function() {
				$("#first_name_pupil").val('');
				$("#second_name_pupil").val('');
				$("#last_name_pupil").val('');
				$("#gender_pupil").val('');
				$("#pupil_birth_date").val('');
				$("#pupil_birth_place").val('');
				$("#pupil_father_name").val('');
				$("#pupil_mother_name").val('');
				$("#parents_state").val('');
				$("#parents_alive").val('');
				$("#lives_with").val('');
				$("#father_work_pupil").val('');
				$("#mother_work_pupil").val('');
				$("#cycle_school_pupil").val('');
				$("#class_school_pupil").val('');
				$("#class_order_pupil").val('');
				$("#class_section_pupil").val('');
				$("#class_option_pupil").val('');
				$("#school_year_pupil").val('');
				$("#email_address_pupil").val('');
				$("#physical_address_pupil").val('');
				$("#contact_1_pupil").val('');
				$("#contact_2_pupil").val('');
				$("#contact_3_pupil").val('');
				$("#contact_4_pupil").val('');
				$('.action_done_success_div').fadeIn(1);
				$('.action_done_success_div').fadeOut(5000);
			});
		}
	});

	$('#validate_insert_class').on('click', function() {
		let class_number = $('#class_number').val();
		if(class_number == '')
		{
      $('.action_done_error_div').fadeIn();
			$('.action_done_error_div').fadeOut(5000);
		}
		else
		{
			$.post('pages/ajax/new_class.php', {class_number:class_number}, function() {
				$('#class_number').val('');
				$('.action_done_success_div').fadeIn(1);
				$('.action_done_success_div').fadeOut(5000);
			});
		}
	});

	$('#validate_insert_study_cycle').on('click', function() {
		let study_cycle = $('#study_cycle').val();
		if(study_cycle == '')
		{
      $('.action_done_error_div').fadeIn();
			$('.action_done_error_div').fadeOut(5000);
		}
		else
		{
			$.post('pages/ajax/new_cycle.php', {study_cycle:study_cycle}, function() {
				$('#study_cycle').val('');
				$('.action_done_success_div').fadeIn(1);
				$('.action_done_success_div').fadeOut(5000);
			});
		}
	});

	$('#validate_insert_class_order').on('click', function() {
		let class_order = $('#class_order').val();
		if(class_order == '')
		{
      $('.action_done_error_div').fadeIn();
			$('.action_done_error_div').fadeOut(5000);
		}
		else
		{
			$.post('pages/ajax/new_order.php', {class_order:class_order}, function() {
				$('#class_order').val('');
				$('.action_done_success_div').fadeIn(1);
				$('.action_done_success_div').fadeOut(5000);
			});
		}
	});

	$('#validate_insert_section').on('click', function() {
		let section_name = $('#section_name').val();
		if(section_name == '')
		{
      $('.action_done_error_div').fadeIn();
			$('.action_done_error_div').fadeOut(5000);
		}
		else
		{
			$.post('pages/ajax/new_section.php', {section_name:section_name}, function() {
				$('#section_name').val('');
				$('.action_done_success_div').fadeIn(1);
				$('.action_done_success_div').fadeOut(5000);
			});
		}
	});

	$('#validate_insert_option').on('click', function() {
		let option_name = $('#option_name').val();
		if(option_name == '')
		{
      $('.action_done_error_div').fadeIn();
			$('.action_done_error_div').fadeOut(5000);
		}
		else
		{
			$.post('pages/ajax/new_option.php', {option_name:option_name}, function() {
				$('#option_name').val('');
				$('.action_done_success_div').fadeIn(1);
				$('.action_done_success_div').fadeOut(5000);
			});
		}
	});

	$('#validate_insert_school_year').on('click', function() {
		let school_year = $('#school_year').val();
		if(school_year == '')
		{
      $('.action_done_error_div').fadeIn();
			$('.action_done_error_div').fadeOut(5000);
		}
		else
		{
			$.post('pages/ajax/new_school_year.php', {school_year:school_year}, function() {
				$('#school_year').val('');
				$('.action_done_success_div').fadeIn(1);
				$('.action_done_success_div').fadeOut(5000);
			});
		}
	});

	// $('#les_eleves').on('click', function() {
	// 	$('.div_new_worker').fadeOut(-2000);
	// 	$('.div_reglages').fadeOut(-2000);
	// 	$('.div_new_course').fadeOut(-2000);
	// 	$('.divv_info').fadeOut(-2000);
	// 	$('.div_new_pupil').fadeIn(200);
	// });

	$('#open_new_worker').on('click', function() {
		$('.div_new_pupil').fadeOut(-2000);
		$('.div_reglages').fadeOut(-2000);
		$('.div_new_course').fadeOut(-2000);
		$('.div_new_worker').fadeToggle(200);
	});

	$('#reglages_de_base').on('click', function() {
		$('.div_new_worker').fadeOut(-2000);
		$('.div_new_pupil').fadeOut(-2000);
		$('.div_new_course').fadeOut(-2000);
		$('.divv_info').fadeOut(-2000);
		$('.div_reglages').fadeIn(200);
	});

	$('#les_cours').on('click', function() {
		$('.div_new_worker').fadeOut(-2000);
		$('.div_new_pupil').fadeOut(-2000);
		$('.div_reglages').fadeOut(-2000);
		$('.divv_info').fadeOut(-2000);
		$('.div_new_course').fadeIn(200);
	});

    $('.button_parametres').on('click', function(){
		$('#show_third_party_config').fadeIn(400);
	  });
  
	  $('#annuler_change_password').on('click', function(){
		$('#show_third_party_config').fadeOut(400);
	  });
  
	  $('#old_password').keyup(function(){
		if($('#old_password').val().length > 5)
		{
		  $('.info_old_password').fadeIn();
		}
	  });
  
	  $('#new_password2').keyup(function(){
		if($('#new_password2').val() != $('#new_password1').val())
		{
		  $('.info_new_password').fadeIn();
		}
		else
		{
		  $('.info_new_password').fadeOut();
		}
	  });
  
	  $('#submit_change_password').on('click', function(){
		let poste_number = $(this).val();
		let old_password = $('#old_password').val();
		let new_password1 = $('#new_password1').val();
		let new_password2 = $('#new_password2').val();
  
		if(poste_number == '' || old_password == '' || new_password1 == '' || new_password2 == '')
		{
		  alert("Vous devez remplir tous les champs");
		}
		else
		{
		  if(new_password1.length < 6 || new_password2.length < 6)
		  {
			alert("Au moins 6 caractères pour le nouveau mot de passe");
		  }
		  else
		  {
			$.post('pages/ajax/password_edit.php', {poste_number:poste_number, old_password:old_password, new_password2:new_password2}, function(){
			  alert("Informations envoyées avec succès");
			});
		  }
		}
	  });
  
});
