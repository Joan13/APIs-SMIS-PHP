$(document).ready(function() {

	$("#validate_insert_course").on('click', function() {
		let course_name = $("#course_name").val();
		let cycle_school_course = $("#cycle_school_course").val();
		let class_school_course = $("#class_school_course").val();
		let class_section_course = $("#class_section_course").val();
		let class_option_course = $("#class_option_course").val();
		let school_year_course = $("#school_year_course").val();
		let maxima = $("#maxima").val();

		if(course_name == '' || maxima == '' || class_school_course == "" || cycle_school_course == "" || school_year_course == "")
		{
			$('#information-div-global').fadeIn(400);
		}
		else
		{
			$.post('pages/ajax/new_course.php',
			{
				course_name:course_name,
				cycle_school_course:cycle_school_course,
				class_school_course:class_school_course,
				school_year_course:school_year_course,
				class_section_course:class_section_course,
				class_option_course:class_option_course,
				maxima:maxima
			},
			function() {
				$("#course_name").val('');
				// $("#cycle_school_course").val('');
				// $("#class_school_course").val('');
				// $("#school_year_course").val('');
				// $("#class_section_course").val('');
				// $("#class_option_course").val('');
				$("#maxima").val('');
				$('#success-information-div-global').fadeIn(400);
			});
		}
	});

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
		let statut_scolaire = $("#statut_scolaire").val();

		// alert(statut_scolaire);

		if(first_name_pupil == '' || second_name_pupil == '' || class_school_pupil == '' || cycle_school_pupil == '' || school_year_pupil == '')
		{
			$('#information-div-global').fadeIn(400);
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
				contact_4_pupil:contact_4_pupil,
				statut_scolaire:statut_scolaire
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
				// $("#cycle_school_pupil").val('');
				// $("#class_school_pupil").val('');
				// $("#class_order_pupil").val('');
				// $("#class_section_pupil").val('');
				// $("#class_option_pupil").val('');
				// $("#school_year_pupil").val('');
				$("#email_address_pupil").val('');
				$("#physical_address_pupil").val('');
				$("#contact_1_pupil").val('');
				$("#contact_2_pupil").val('');
				$("#contact_3_pupil").val('');
				$("#contact_4_pupil").val('');
				$('#success-information-div-global').fadeIn(400);
			});
		}
	});

	$("#validate_insert_worker").on('click', function() {
		let first_name = $("#first_name_personnel_enter").val();
		let second_name = $("#second_name_personnel_enter").val();
		let last_name = $("#last_name_personnel_enter").val();
		let gender = $("#gender").val();
		let birth_date = $("#worker_birth_date").val();
		let birth_place = $("#worker_birth_place").val();
		let select_poste = $("#select_poste").val();
		let civil_state = $("#civil_state").val();
		let mariage_state = $("#mariage_state").val();
		let children_number = $("#children_number").val();
		let secondary_in = $("#secondary_in").val();
		let graduated_in = $("#graduated_in").val();
		let bachelor_in = $("#bachelor_in").val();
		let masters_in = $("#masters_in").val();
		let phd_in = $("#phd_in").val();
		let email_address = $("#email_address").val();
		let physical_address = $("#physical_address").val();
		let contact_1 = $("#contact_1").val();
		let contact_2 = $("#contact_2").val();
		let contact_3 = $("#contact_3").val();
		let contact_4 = $("#contact_4").val();

		if(first_name == '' || second_name == '' || gender == '' || select_poste == '' || civil_state == '')
		{

		}
		else
		{
			$.post('pages/ajax/new_worker.php',
			{
				first_name:first_name,
				second_name:second_name,
				last_name:last_name,
				gender:gender,
				birth_date:birth_date,
				birth_place:birth_place,
				select_poste:select_poste,
				civil_state:civil_state,
				mariage_state:mariage_state,
				children_number:children_number,
				secondary_in:secondary_in,
				graduated_in:graduated_in,
				bachelor_in:bachelor_in,
				masters_in:masters_in,
				phd_in:phd_in,
				email_address:email_address,
				physical_address:physical_address,
				contact_1:contact_1,
				contact_2:contact_2,
				contact_3:contact_3,
				contact_4:contact_4
			},
			function() {
				$("#first_name_personnel_enter").val('');
				$("#second_name_personnel_enter").val('');
				$("#last_name_personnel_enter").val('');
				$("#gender").val('');
				$("#worker_birth_date").val('');
				$("#worker_birth_place").val('');
				$("#select_poste").val('');
				$("#civil_state").val('');
				$("#mariage_state").val('');
				$("#children_number").val('');
				$("#secondary_in").val('');
				$("#graduated_in").val('');
				$("#bachelor_in").val('');
				$("#masters_in").val('');
				$("#phd_in").val('');
				$("#email_address").val('');
				$("#physical_address").val('');
				$("#contact_1").val('');
				$("#contact_2").val('');
				$("#contact_3").val('');
				$("#contact_4").val('');
				$('.action_done_success_div').fadeIn(1);
				$('.action_done_success_div').fadeOut(5000);
			});
		}
	});


	$('#validate_insert_class').on('click', function() {
		let class_number = $('#class_number').val();
		if(class_number == '')
		{
			$('#information-div-global').fadeIn(400);
		}
		else
		{
			$.post('pages/ajax/new_class.php', {class_number:class_number}, function() {
				$('#class_number').val('');
				$('#success-information-div-global').fadeIn(400);
			});
		}
	});

	$('#validate_insert_study_cycle').on('click', function() {
		let study_cycle = $('#study_cycle').val();
		if(study_cycle == '')
		{
			$('#information-div-global').fadeIn(400);
		}
		else
		{
			$.post('pages/ajax/new_cycle.php', {study_cycle:study_cycle}, function() {
				$('#study_cycle').val('');
				$('#success-information-div-global').fadeIn(400);
			});
		}
	});

	$('#validate_insert_class_order').on('click', function() {
		let class_order = $('#class_order').val();
		if(class_order == '')
		{
			$('#information-div-global').fadeIn(400);
		}
		else
		{
			$.post('pages/ajax/new_order.php', {class_order:class_order}, function() {
				$('#class_order').val('');
				$('#success-information-div-global').fadeIn(400);
			});
		}
	});

	$('#validate_insert_section').on('click', function() {
		let section_name = $('#section_name').val();
		if(section_name == '')
		{
			$('#information-div-global').fadeIn(400);
		}
		else
		{
			$.post('pages/ajax/new_section.php', {section_name:section_name}, function() {
				$('#section_name').val('');
				$('#success-information-div-global').fadeIn(400);
			});
		}
	});

	$('#validate_insert_option').on('click', function() {
		let option_name = $('#option_name').val();
		if(option_name == '')
		{
			$('#information-div-global').fadeIn(400);
		}
		else
		{
			$.post('pages/ajax/new_option.php', {option_name:option_name}, function() {
				$('#option_name').val('');
				$('#success-information-div-global').fadeIn(400);
			});
		}
	});

	$('#validate_insert_school_year').on('click', function() {
		let school_year = $('#school_year').val();
		if(school_year == '')
		{
			$('#information-div-global').fadeIn(400);
		}
		else
		{
			$.post('pages/ajax/new_school_year.php', {school_year:school_year}, function() {
				$('#school_year').val('');
				$('#success-information-div-global').fadeIn(400);
			});
		}
	});

	$('#les_classes').on('click', function() {
		$('.show_third_party').slideToggle(400);
	});

	$('#les_eleves').on('click', function() {
		$('.div_new_worker').fadeOut(-2000);
		$('.div_reglages').fadeOut(-2000);
		$('.div_new_course').fadeOut(-2000);
		$('.divv_info').fadeOut(-2000);
		$('.div_new_pupil').fadeIn(200);
	});

	$('#le_personnel').on('click', function() {
		$('.div_new_pupil').fadeOut(-2000);
		$('.div_reglages').fadeOut(-2000);
		$('.div_new_course').fadeOut(-2000);
		$('.divv_info').fadeOut(-2000);
		$('.div_new_worker').fadeIn(200);
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

	    $('.button_parametres').on('click', function() {
	    	$('#rd_party').fadeIn(400);
	    });

	    $('#annuler_change_password').on('click', function() {
	    	$('#rd_party').fadeOut(400);
	    });
  
	  $('#old_password').keyup(function(){
		if ($('#old_password').val().length > 5)
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
		  $('#information-div-global').fadeIn(400);
		}
		else
		{
		  if(new_password1.length < 6 || new_password2.length < 6)
		  {
			alert("Au moins 6 caracteres pour le nouveau mot de passe");
		  }
		  else
		  {
			$.post('pages/ajax/password_edit.php', {poste_number:poste_number, old_password:old_password, new_password2:new_password2}, function(){
			  $('#success-information-div-global').fadeIn(400);
			});
		  }
		}
	  });

	  $('#validate_insert_domain').on('click', function(){
		let domain_name_domain = $("#domain_name_domain").val();
		let school_year_domain = $('#school_year_domain').val();
  
		if(domain_name_domain == '' || school_year_domain == '')
		{
		  $('#information-div-global').fadeIn(400);
		}
		else
		{
			$.post('pages/ajax/add_domain.php', {domain_name_domain:domain_name_domain, school_year_domain:school_year_domain}, function(){
			  $('#success-information-div-global').fadeIn(400);
			});
		}
	  });

	  $('#validate_insert_sub_domain').on('click', function(){
		let domain_name_domain = $("#domain_name").val();
		let sub_domain_name = $('#sub_domain_name').val();
  
		if(domain_name_domain == '' || sub_domain_name == '')
		{
		  $('#information-div-global').fadeIn(400);
		}
		else
		{
			$.post('pages/ajax/add_domain.php', {domain_name:domain_name, sub_domain_name:sub_domain_name}, function(){
			  $('#success-information-div-global').fadeIn(400);
			});
		}
	  });
  
});
