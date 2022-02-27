$(document).ready(() => {
	$('.editFicheEleve').on('click', () => {
		$('.main_info').fadeToggle(400);
		$('.div_new_pupil').fadeToggle(400);
	})

	$("#validate_edit_pupil").on('click', function () {
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
		let is_inactive = $("#is_inactive").val();
		let pupil_id = $("#pupil_id_edit").val();
		let permanent_number = $("#permanent_number").val();
		let identification_number = $("#identification_number").val();
		let nationality = $("#nationality").val();

		if (first_name_pupil == '' || second_name_pupil == '' || class_school_pupil == '' || cycle_school_pupil == '' || school_year_pupil == '') {
			$('#information-div-global').fadeIn(400);
		}
		else {
			$.post('pages/ajax/edit_pupil.php',
				{
					pupil_id: pupil_id,
					first_name_pupil: first_name_pupil,
					second_name_pupil: second_name_pupil,
					last_name_pupil: last_name_pupil,
					gender_pupil: gender_pupil,
					birth_date_pupil: birth_date_pupil,
					birth_place_pupil: birth_place_pupil,
					father_name: father_name,
					mother_name: mother_name,
					parents_state: parents_state,
					parents_alive: parents_alive,
					lives_with: lives_with,
					father_work_pupil: father_work_pupil,
					mother_work_pupil: mother_work_pupil,
					cycle_school_pupil: cycle_school_pupil,
					class_school_pupil: class_school_pupil,
					class_order_pupil: class_order_pupil,
					class_section_pupil: class_section_pupil,
					class_option_pupil: class_option_pupil,
					school_year_pupil: school_year_pupil,
					identification_number: identification_number,
					permanent_number: permanent_number,
					nationality: nationality,
					email_address_pupil: email_address_pupil,
					physical_address_pupil: physical_address_pupil,
					contact_1_pupil: contact_1_pupil,
					contact_2_pupil: contact_2_pupil,
					contact_3_pupil: contact_3_pupil,
					contact_4_pupil: contact_4_pupil,
					is_inactive: is_inactive
				},
				function () {
					// $("#first_name_pupil").val('');
					// $("#second_name_pupil").val('');
					// $("#last_name_pupil").val('');
					// $("#gender_pupil").val('');
					// $("#pupil_birth_date").val('');
					// $("#pupil_birth_place").val('');
					// $("#pupil_father_name").val('');
					// $("#pupil_mother_name").val('');
					// $("#parents_state").val('');
					// $("#parents_alive").val('');
					// $("#lives_with").val('');
					// $("#father_work_pupil").val('');
					// $("#mother_work_pupil").val('');
					// $("#cycle_school_pupil").val('');
					// $("#class_school_pupil").val('');
					// $("#class_order_pupil").val('');
					// $("#class_section_pupil").val('');
					// $("#class_option_pupil").val('');
					// $("#school_year_pupil").val('');
					// $("#email_address_pupil").val('');
					// $("#physical_address_pupil").val('');
					// $("#contact_1_pupil").val('');
					// $("#contact_2_pupil").val('');
					// $("#contact_3_pupil").val('');
					// $("#contact_4_pupil").val('');
					$('#success-information-div-global').fadeIn(400);
				});
		}
	});
})