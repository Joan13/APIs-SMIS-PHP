<?php

	if(session_in() == 0)
	{
		Header('Location: index.php?_=login');
	}

	if(session_in() == 7 && $page != 'home.study.director') {
		Header('Location: ./?_=home.study.director');
	} else if(session_in() == 7 && $page == 'home.study.director') {
		
	} else {
		Header('Location: ./?_=login');
	}

?>

		<div class="main_middle_container">
			<div style="padding-left: 15px;">

				<div class="divv_info">
					<span style="text-decoration: underline;">Cliquez sur un item du menu pour commencer</span><br/><br/>
					<li>Gerez les informations en rapport avec vos élèves</li><br/>
					<li>Cliquez sur "LES CLASSES" pour consulter les informations<br/>en rapport avec les classes. Allez ensuite vers une des années disponibles </li><br/>
					<li>Ensuite, cliquez sur "ENTRER LES POINTS" pour enregistrer les cotes</li><br/>
					<li>Ensuite, cliquez sur "FICHE DES POINTS" pour voir les fiches des cotes par periode</li><br/>
					<li>Allez dans les paramètres pour regler vos configurations de compte</li><br/>
				</div>

				<div class="divdiv div_new_teacher" style="display:none;"><br/><br/>
					
				<table style="border-collapse:collapse;border:1px solid rgba(0, 0, 0, 0.2);">
					<tr>
						<td style="padding-left:15px;border:1px solid rgba(0, 0, 0, 0.2);">
							<br/><h1>Ajouter un Enseignant</h1>
							<label class="labell">Noms de l'enseignant(e) : </label><br/>
							<input type="text" class="selects" id="worker_names" placeholder="Ex : Andema Choya Fredriech" />

							<br/><label class="labell">Sexe : </label><br/>
							<select class="selects select_pupil_section" id="gender_worker">
								<option value="" class="login_item"> -- Selectionner ici --</option>
								<option value="0" class="login_item">Femme</option>
								<option value="1" class="login_item">Homme</option>
							</select>

							<br/><label class="labell">Tache de base : </label><br/>
							<select class="selects select_pupil_section" id="todo_worker">
								<option value="" class="login_item"> -- Selectionner ici --</option>
								<option value="Enseignant" class="login_item">Enseignant(e)</option>
							</select>

							<br/><label class="labell">Annee scolaire : </label><br/>
							<select class="selects select_pupil_option" id="worker_year">
								<option value="" class="login_item"> -- Selectionner ici --</option>
								<?php

								$options00 = "SELECT * FROM school_years";
								$options11 = $database_connect->query($options00);
								while($optionss = $options11->fetchObject())
								{
									?><option value="<?= $optionss->year_id ?>" class="login_item"><?= $optionss->year_name ?></option><?php
								}
								?>
							</select><br/>
							<button id="validate_insert_worker" class="validate_login">Enregistrer l'enseignant</button><br/>
						</td>

						<td style="padding-left:15px;border:1px solid rgba(0, 0, 0, 0.2);">
							<br/><h1>Attribuer un cours</h1>

							<label class="labell">Noms de l'enseignant(e) : </label><br/>
							<select class="selects select_pupil_option" id="worker_id">
								<option value="" class="login_item"> -- Sélectionner ici --</option>

									<?php

									$years000 = "SELECT * FROM workers_info";
									$years111 = $database_connect->query($years000);
									while($yearsss = $years111->fetchObject())
									{
										?>
										<option value="<?=$yearsss->worker_id?>"><?= $yearsss->full_name ?></option><br/>
										<?php
									}
									
									?>
							</select><br/>
							
							<label class="labell">Intitule du cours : </label><br/>
							<select class="selects select_pupil_option" id="course_attributed">
								<option value="" class="login_item"> -- Selectionner ici --</option>

								<?php

								if(count_classes_completed_exist() != 0)
								{
									$query_fetch00 = "SELECT id_classes, cycle_id, class_id, order_id, section_id, option_id, school_year, classes_alignment FROM classes_completed WHERE school_year=? ORDER BY classes_alignment";
									$query_fetch11 = $database_connect->prepare($query_fetch00);
									$query_fetch11->execute(array(1));
									while($query_fetch = $query_fetch11->fetchObject())
									{
										$cycle_name = find_cycle_name($query_fetch->cycle_id);
										$class_number = find_class_number($query_fetch->class_id);
										$order_name = find_order_name($query_fetch->order_id);
										$section_name = find_section_name($query_fetch->section_id);
										$option_name = find_option_name($query_fetch->option_id);

										if($class_number == 1)
										{
											$class_number = "1 ère";
										}
										else
										{
											$class_number = "$class_number ème";
										}

										?>
										<optgroup label="<?= $class_number ."". $order_name ."". $section_name ?>">
											<?php
											if(count_courses_exist($query_fetch->cycle_id, $query_fetch->class_id, $query_fetch->section_id, $query_fetch->option_id, 1) != 0)
											{
												$query_fetch005 = "SELECT course_id, course_name, cycle_id, class_id, section_id, option_id, total_marks, school_year FROM courses WHERE cycle_id=? AND class_id=? AND section_id=? AND option_id=? AND school_year=? ORDER BY course_id ASC";
												$query_fetch115 = $database_connect->prepare($query_fetch005);
												$query_fetch115->execute(array($query_fetch->cycle_id, $query_fetch->class_id, $query_fetch->section_id, $query_fetch->option_id, 1));
												while($query_fetch5 = $query_fetch115->fetchObject())
												{
													?>
													
													<option value="<?= $query_fetch5->course_id ?>"><?= $query_fetch5->course_name ?></option>
													
													<?php
												}
											}
											?>
										</optgroup>
										<?php
									}
								}

								?>
							</select><br/>
							<button id="validate_insert_attribution" class="validate_login">Enregistrer l'attribution</button><br/>


							<br/><br/><br/><br/>
							<span style="color:gray;">Actualisez la page avant de proceder a l'attribution d'un cours.</span><br/><br/><br/>
						</td>
					</tr>

				
				</table>

				</div><br/>
			</div>
		</div>

<?php


// <tr>
// <td style="padding-left:15px;border:1px solid rgba(0, 0, 0, 0.2);" colspan="2">
// 		<br/><h1>Attribution des cours</h1>

// 		<table style="border-collapse: collapse;">
// 			<tr>
// 				<th style="width: 25px;">No</th>
// 				<th style="padding-left: 5px;">Noms de l'enseignant(e)</th>
// 				<th style="padding-left: 5px;">Cours</th>
// 				<!-- <th style="padding-left: 5px;">Classe</th> -->
// 			</tr>

// 			<?php

// 				$num = 0;
// 				$worker = 0;

// 				$years000 = "SELECT * FROM workers_info";
// 				$years111 = $database_connect->query($years000);
// 				while($yearsss = $years111->fetchObject())
// 				{
// 					$woo = $yearsss->worker_id;
// 					$worker = $yearsss->worker_id;

// 					$years0005 = "SELECT * FROM attribution_teachers WHERE worker_id='$woo'";
// 					$years1115 = $database_connect->query($years0005);
// 					while($yearsss5 = $years1115->fetchObject())
// 					{
// 						$cours = $yearsss5->course_id;

// 						$query_fetch005 = "SELECT * FROM courses WHERE course_id='$cours'";
// 						$query_fetch115 = $database_connect->query($query_fetch005);
// 						$query_fetch5 = $query_fetch115->fetchObject();

// 						$num = $num + 1;
// 						?>
<!-- // 						<tr>
// 						<td style="padding-left: 5px;"><?= $num ?></td>
// 						<td style="padding-left: 5px;"><?php if ($worker == $yearsss->worker_id) { echo $yearsss->full_name; } ?><br/></td>
// 						<td style="padding-left: 5px;"><?= $query_fetch5->course_name ?></td>
// 						</tr> -->
						<?php

// 						$worker = 0;
// 					}
// 				}
				
// 				?>
<!-- // 		</table> -->
<!-- // 		<br/><br/> -->
<!-- // 		<button id="validate_insert_attribution" class="validate_login">Enregistrer l'attribution</button><br/> -->


<!-- // 		<br/><br/><br/><br/> -->
<!-- // 		<span style="color:gray;">Actualisez la page avant de proceder a l'attribution d'un cours.</span><br/><br/><br/> -->
<!-- // 	</td>
// </tr> -->
