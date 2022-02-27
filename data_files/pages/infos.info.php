<?php

	if(session_in() == 0)
	{
		Header('Location: index.php?_=login');
	}

	$cycle_name = find_cycle_name($_GET['cycle']);
	$class_number = find_class_number($_GET['class_id']);
	$true_class_number = find_class_number($_GET['class_id']);
	$order_name = find_order_name($_GET['order_id']);
	$section_name = find_section_name($_GET['section_id']);
	$option_name = find_option_name($_GET['option_id']);
	$school_year = find_school_year($_GET['school_year']);
	$periode = $_GET['periode'];

	$toUpper_class_name = strtoupper($cycle_name);

	// if($_GET['typeuser'] == 1)

	if(selected_pupil_exists($_GET['pupil_id']) != 0)
	{
		$query_fetch000 = "SELECT * FROM pupils_info WHERE pupil_id=?";
		$query_fetch110 = $database_connect->prepare($query_fetch000);
		$query_fetch110->execute(array($_GET['pupil_id']));
		$query_fetch0 = $query_fetch110->fetchObject();
	}

	if($class_number == 1)
	{
		$class_number = "1 ère";
	}
	else
	{
		$class_number = $class_number." ème";
	}

	if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL")
	{
		$class_identity = "$class_number $cycle_name $order_name";
	}
	if($toUpper_class_name == "PRIMAIRE")
	{
		$class_identity = "$class_number $cycle_name $order_name";
	}

	if($toUpper_class_name == "SECONDAIRE")
	{
		if($true_class_number >= 1)
		{
			if($option_name == "")
			{
				$option_name = "";
			}
			else
			{
				$option_name = ", ".$option_name;
			}

			$class_identity = $class_number." ".$section_name."".$order_name."".$option_name;
		}
		else
		{
			$class_identity = "$class_number $order_name";
		}
	}

	$gender = $query_fetch0->gender;
	if($gender == 0)
	{
		$adopted = "Adoptée";
		$gender = "Feminin";
	}
	else
	{
		$adopted = "Adopté";
		$gender = "Maculin";
	}

	$ttt = $query_fetch0->birth_date;
	$tt_year = substr($ttt, 0, 4);
	$tt_month = generate_month(substr($ttt, 5, 2));
	$tt_day = substr($ttt, 8, 2);
	$birth_date = "le ".$tt_day." ".$tt_month." ".$tt_year;

	$parents_state = $query_fetch0->parents_state;
	if($parents_state == 0)
	{
		$parents_state = "Ensemble";
	}
	else if($parents_state == 1)
	{
		$parents_state = "Divorcés";
	}
	else
	{
		$parents_state = "Célibataires";
	}

	$parents_alive = $query_fetch0->parents_alive;
	if($parents_alive == 0)
	{
		$parents_alive = "Les deux parents en vie";
	}
	elseif($parents_alive == 1)
	{
		$parents_alive = "Seul le père en vie";
	}
	else
	{
		$parents_alive = "Seule la mère en vie";
	}

	$lives_with = $query_fetch0->lives_with;
	if($lives_with == 0)
	{
		$lives_with = "Les deux parents";
	}
	elseif($lives_with == 1)
	{
		$lives_with = "Le père";
	}
	elseif($lives_with == 2)
	{
		$lives_with = "La mère";
	}
	else
	{
		$lives_with = $adopted;
	}

	?>

	<div class="main_middle_container">
		<h2 style="text-align: center;">Fiche de l'élève / <u class="editFicheEleve" style="cursor: pointer;">Editer la fiche</u></h2>
		<div class="main_info">
			<table class="ttablee" style="border-collapse: collapse;margin-left: 15%;width:70%;">
				<tr>
					<th colspan="2" style="padding-left: 50px;">Informations de base</th>
				</tr>
				<tr>
					<td>Nom</td>
					<td>
						<span class="info_important"><?=strtoupper($query_fetch0->first_name)?></span>
					</td>
				</tr>
				<tr>
					<td>Post-nom</td>
					<td><span class="info_important"><?=strtoupper($query_fetch0->second_name)?></span></td>
				</tr>
				<tr>
					<td>Prenom</td><td><span class="info_important"><?=ucwords($query_fetch0->last_name)?></span></td>
				</tr>
				<tr>
					<td>Sexe</td><td><span class="info_important"><?=$gender?></span></td>
				</tr>
				<tr>
					<td>Lieu et date de naissance</td>
					<td><span class="info_important"><?=ucwords($query_fetch0->birth_place)?>, <?=$birth_date?></span>
					</td>
				</tr>
				<tr>
					<td>Classe</td><td><span class="info_important"><?=$class_identity?></span></td>
				</tr>
				<tr>
					<td>Annee scolaire</td><td><span class="info_important"><?=$school_year?></span></td>
				</tr>

				<tr>
					<th colspan="2" style="padding-left: 50px;">A propos de la famille</th>
				</tr>

				<tr>
					<td>Noms du père</td><td><span class="info_important"><?=ucwords($query_fetch0->father_names)?></span></td>
				</tr>
				<tr>
					<td>Noms de la mère</td><td><span class="info_important"><?=ucwords($query_fetch0->mother_names)?></span></td>
				</tr>
				<tr>
					<td>Statut des parents</td><td><span class="info_important"><?=$parents_state?></span></td>
				</tr>
				<tr>
					<td>Vie des parents</td><td><span class="info_important"><?=$parents_alive?></span></td>
				</tr>
				<tr>
					<td>Vie avec</td><td><span class="info_important"><?=$lives_with?></span></td>
				</tr>
				<tr>
					<td>Travail principal du père</td><td><span class="info_important"><?=ucwords($query_fetch0->father_principal_work)?></span></td>
				</tr>
				<tr>
					<td>Travail principal de la mère</td><td><span class="info_important"><?=ucwords($query_fetch0->mother_principal_work)?></span></td>
				</tr>
				<tr>
					<th colspan="2" style="padding-left: 50px;">Adresses et contacts</th>
				</tr>
				<tr>
					<td>Adresse électronique de contact</td><td><span class="info_important"><?=$query_fetch0->email_address?></span></td>
				</tr>
				<tr>
					<td>Adresse physique</td><td><span class="info_important"><?=$query_fetch0->physical_address?></span></td>
				</tr>
				<tr>
					<td>Contact phone 1</td><td><span class="info_important"><?=$query_fetch0->contact_phone_1?></span></td>
				</tr>
				<tr>
					<td>Contact phone 2</td><td><span class="info_important"><?=$query_fetch0->contact_phone_2?></span></td>
				</tr>
				<tr>
					<td>Contact phone 3</td><td><span class="info_important"><?=$query_fetch0->contact_phone_3?></span></td>
				</tr>
				<tr>
					<td>Contact phone 4</td>
					<td>
						<span class="info_important"><?=$query_fetch0->contact_phone_4?></span>
					</td>
				</tr>
			</table><br/><br/><br/>
		</div>

		<div class="divdiv div_new_pupil">
			<h1 class="ttle title_new_worker">Editer les informations sur l'élève <?= strtoupper($query_fetch0->first_name." ".$query_fetch0->second_name)." ".$query_fetch0->last_name ?></h1>

			<button id="pupil_id_edit" value="<?= $query_fetch0->pupil_id ?>" style="display: none;"></button>

			<table id="tata" style="width: 90%; margin-left: 5%; background-color: inherit; border: 1px solid grey; padding: 25px;">
				<tr>
					<th colspan="2"><h3 class="subdivdivTtitle">Identité de base</h3></th>
				</tr>
				<tr>
					<td>
						<label class="labell">Nom</label><br/>
						<input class="innput" id="first_name_pupil" placeholder="Nom" value="<?= $query_fetch0->first_name ?>" />
					</td>
					<td>
						<label class="labell">Post-nom</label><br/>
						<input class="innput" id="second_name_pupil" placeholder="Post-nom" value="<?= $query_fetch0->second_name ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<label class="labell">Prénom</label><br/>
						<input class="innput" id="last_name_pupil" placeholder="Prénom" value="<?= $query_fetch0->last_name ?>" />
					</td>
					<td>
						<label class="labell">Sexe</label><br/>
						<select id="gender_pupil" class="selects selects_entries">
							<option value="0"> -- Choisir sexe -- </option>
							<option <?= ($query_fetch0->gender == 0) ? 'selected' : ''; ?> value="0">Féminin</option>
							<option <?= ($query_fetch0->gender == 1) ? 'selected' : ''; ?> value="1">Masculin</option>
						</select>						
					</td>
				</tr>
				<tr>
					<td>
						<label class="labell">Date de naissance</label><br/>
						<span class="separator_before_inputs">Né(e) le </span>
						<input type="date" id="pupil_birth_date" class="klkl class_date" value="<?= $query_fetch0->birth_date ?>" />							
					</td>
					<td>
						<label class="separator_inputs">Lieu de naissance (ville)</label><br/>
						<input class="innput" id="pupil_birth_place" placeholder="Lieu de naissance" value="<?= $query_fetch0->birth_place ?>" />							
					</td>
				</tr>
				<tr>
					<td>
						<label class="labell">Noms du père</label><br/>
						<input class="innput" id="pupil_father_name" placeholder="Noms du père" value="<?= $query_fetch0->father_names ?>" />
					</td>
					<td>
						<label class="labell">Noms de la mère</label><br/>
						<input class="innput" id="pupil_mother_name" placeholder="Noms de la mère" value="<?= $query_fetch0->mother_names ?>" />	
					</td>
				</tr>
				<tr>
					<td>
						<label class="labell">Status des parents : </label><br/>
						<select class="selects select_pupil_cycle" id="parents_state">
							<option value="0" class="login_item"> -- Sélectionner ici --</option>
							<option <?= ($query_fetch0->parents_state == 0) ? 'selected' : ''; ?> value="0" class="login_item">Ensemble</option>
							<option <?= ($query_fetch0->parents_state == 1) ? 'selected' : ''; ?> value="1" class="login_item">Divorcés</option>
							<option <?= ($query_fetch0->parents_state == 2) ? 'selected' : ''; ?> value="2" class="login_item">Célibataire</option>
						</select>							
					</td>
					<td>
						<label class="labell">Vie des parents : </label><br/>
						<select class=" selects select_pupil_cycle" id="parents_alive">
							<option value="0" class="login_item"> -- Sélectionner ici --</option>
							<option <?= ($query_fetch0->parents_alive == 0) ? 'selected' : ''; ?> value="0" class="login_item">Les deux parents en vie</option>
							<option <?= ($query_fetch0->parents_alive == 1) ? 'selected' : ''; ?> value="1" class="login_item">Seul le père en vie</option>
							<option <?= ($query_fetch0->parents_alive == 2) ? 'selected' : ''; ?> value="2" class="login_item">Seule la mère en vie</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label class="labell">Vis avec : </label><br/>
						<select class="selects select_pupil_cycle" id="lives_with">
							<option value="0" class="login_item"> -- Sélectionner ici --</option>
							<option <?= ($query_fetch0->lives_with == 0) ? 'selected' : ''; ?> value="0" class="login_item">Les deux parents</option>
							<option <?= ($query_fetch0->lives_with == 1) ? 'selected' : ''; ?> value="1" class="login_item">Le père</option>
							<option <?= ($query_fetch0->lives_with == 2) ? 'selected' : ''; ?> value="2" class="login_item">La mère</option>
							<option <?= ($query_fetch0->lives_with == 3) ? 'selected' : ''; ?> value="3" class="login_item">Adopté (e)</option>
						</select>							
					</td>
					<td>
						<label class="labell">Nationalité : </label><br/>
						<input class="innput" id="nationality" placeholder="Nationalité" value="<?= $query_fetch0->nationality ?>" />		
					</td>
				</tr>
				<tr>
					<td>
						<label class="labell">Travail pricipal du père : </label><br/>
						<input class="innput" id="father_work_pupil" placeholder="Travail du père" value="<?= $query_fetch0->father_principal_work ?>" />
					</td>
					<td>
						<label class="labell">Travail principal de la mère : </label><br/>
						<input class="innput" id="mother_work_pupil" placeholder="Travail de la mère" value="<?= $query_fetch0->mother_principal_work ?>" />		
					</td>
				</tr>
				<tr><th colspan="2"><h3 class="subdivdivTtitle">Orientation scolaire</h3></th></tr>
				<tr>
					<td>
						<label class="labell">Cycle d'étude : </label><br/>
						<select class="selects select_pupil_cycle" id="cycle_school_pupil">
							<option value="0" class="login_item"> -- Sélectionner ici --</option>
							<?php

							if(count_cycles_exist() != 0) {
								$cycles00 = "SELECT cycle_id, cycle_name FROM cycle";
								$cycles11 = $database_connect->query($cycles00);
								while($cycless = $cycles11->fetchObject())
								{
									?><option <?= ($query_fetch0->cycle_school == $cycless->cycle_id) ? 'selected' : ''; ?> value="<?= $cycless->cycle_id ?>" class="login_item"><?= $cycless->cycle_name ?></option><?php
								}
							}
							?>
						</select>
					</td>
					<td>
						<label class="labell">Classe : </label><br/>
						<select class="selects select_pupil_cycle" id="class_school_pupil">
							<option value="0" class="login_item"> -- Sélectionner ici --</option>
							<?php

							if(count_classes_exist() != 0) {
								$classes00 = "SELECT class_id, class_number FROM classes";
								$classes11 = $database_connect->query($classes00);
								while($classess = $classes11->fetchObject())
								{
									?><option <?= ($query_fetch0->class_school == $classess->class_id) ? 'selected' : ''; ?> value="<?= $classess->class_id ?>" class="login_item"><?= $classess->class_number ?></option><?php
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label class="labell">Ordre de classe : </label><br/>
						<select class="selects select_pupil_cycle" id="class_order_pupil">
							<option value="0" class="login_item"> -- Sélectionner ici --</option>
							<?php

							if(count_orders_exist() != 0) {
								$orders00 = "SELECT order_id, order_name FROM class_order";
								$orders11 = $database_connect->query($orders00);
								while($orderss = $orders11->fetchObject())
								{
									?><option <?= ($query_fetch0->class_order == $orderss->order_id) ? 'selected' : ''; ?> value="<?= $orderss->order_id ?>" class="login_item"><?= $orderss->order_name ?></option><?php
								}
							}
							?>
						</select>
					</td>
					<td>
						<label class="labell">Section : </label><br/>
						<select class="selects select_pupil_section" id="class_section_pupil">
							<option value="0" class="login_item"> -- Sélectionner ici --</option>
							<?php

							if(count_sections_exist() != 0)
							{
								$sections00 = "SELECT section_id, section_name FROM sections";
								$sections11 = $database_connect->query($sections00);
								while($sectionss = $sections11->fetchObject())
								{
									?><option <?= ($query_fetch0->class_section == $sectionss->section_id) ? 'selected' : ''; ?> value="<?= $sectionss->section_id ?>" class="login_item"><?= $sectionss->section_name ?></option><?php
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label class="labell">Option : </label><br/>
						<select class="selects select_pupil_option" id="class_option_pupil">
							<option value="0" class="login_item"> -- Sélectionner ici --</option>
							<?php

							if(count_options_exist() != 0)
							{
								$options00 = "SELECT option_id, option_name FROM options";
								$options11 = $database_connect->query($options00);
								while($optionss = $options11->fetchObject())
								{
									?><option <?= ($query_fetch0->class_option == $optionss->option_id) ? 'selected' : ''; ?> value="<?= $optionss->option_id ?>" class="login_item"><?= $optionss->option_name ?></option><?php
								}
							}
							?>
						</select>
					</td>
					<td>
						<label class="labell">Année scolaire : </label><br/>
						<select class="selects select_pupil_cycle" id="school_year_pupil">
							<option value="0" class="login_item"> -- Sélectionner ici --</option>
							<?php
							if(count_school_years_exist() != 0)
							{
								$years00 = "SELECT year_id, year_name FROM school_years";
								$years11 = $database_connect->query($years00);
								while($yearss = $years11->fetchObject())
								{
									?><option <?= ($query_fetch0->school_year == $yearss->year_id) ? 'selected' : ''; ?> value="<?= $yearss->year_id ?>" class="login_item"><?= $yearss->year_name ?></option><?php
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label class="labell">Numéro permanant de l'élève : </label><br/>
						<input maxlength="20" class="innput" id="permanent_number" placeholder="Numéro permanent" value="<?= $query_fetch0->permanent_number ?>" />
					</td>
					<td>
						<label class="labell">Numéro d'identification de l'élève : </label><br/>
						<input maxlength="20" class="innput" id="identification_number" placeholder="Numréro d'identification" value="<?= $query_fetch0->identification_number ?>" />
					</td>
				</tr>
				<tr><th colspan="2"><h3 class="subdivdivTtitle">Contacts</h3></th></tr>
				<tr>
					<td>
						<label class="labell">Adresse Electronique</label><br/>
						<input id="email_address_pupil" type="text" class="innput" placeholder="E-mail" value="<?= $query_fetch0->email_address ?>" /><br/>
					</td>
					<td>
						<label class="labell">Adresse physique</label><br/>
						<input id="physical_address_pupil" type="text" class="innput" placeholder="Adresse physique" value="<?= $query_fetch0->physical_address ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<label class="labell">Numéro de téléphone 1</label><br/>
						<input id="contact_1_pupil" type="number" class="selects" placeholder="+ 000 000 000 000" value="<?= $query_fetch0->contact_phone_1 ?>" />
					</td>
					<td>
						<label class="labell">Numéro de téléphone 2</label><br/>
						<input id="contact_2_pupil" type="number" class="selects" placeholder="+ 000 000 000 000" value="<?= $query_fetch0->contact_phone_2 ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<label class="labell">Numéro de téléphone 3</label><br/>
						<input id="contact_3_pupil" type="number" class="selects" placeholder="+ 000 000 000 000" value="<?= $query_fetch0->contact_phone_3 ?>" />
					</td>
					<td>
						<label class="labell">Numéro de téléphone 4</label><br/>
						<input id="contact_4_pupil" type="number" class="selects" placeholder="+ 000 000 000 000" value="<?= $query_fetch0->contact_phone_4 ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<label class="labell">Rendre l'eleve inactif : </label><br/>
						<select class="selects select_pupil_cycle" id="is_inactive">
							<option value="0" class="login_item"> -- Sélectionner ici --</option>
							<option <?= ($query_fetch0->is_inactive == 0) ? 'selected' : ''; ?> value="0" class="login_item">Actif</option>
							<option <?= ($query_fetch0->is_inactive == 1) ? 'selected' : ''; ?> value="1" class="login_item">Inactif (effacer)</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<button id="validate_edit_pupil" class="validate_login" style="width: 100%;">Enregistrer les modifications sur l'élève</button>
					</td>
				</tr>
			</table>
		</div><br/><br/><br/>
	</div>

