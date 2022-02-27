<?php

	if(session_in() == 0) {
		Header('Location: ./?_=login');
	}

	if(session_in() == 4 && $page != 'home.secretor') {
		Header('Location: ./?_=home.secretor');
	} else if(session_in() == 4 && $page == 'home.secretor') {
		
	} else {
		Header('Location: ./?_=login');
	}

?>

	<div class="main_middle_container">
		<div style="padding-left: 15px;">

			<div class="divv_info">
				<span style="text-decoration: underline;">Cliquez sur un item du menu pour commencer</span><br/><br/>
				<li>Commencez avec les réglages de base pour les configurations de base de votre école</li><br/>
				<li>Cliquez sur "NOUVEL (LE) ELEVE" pour enregistrer de nouveaux élèves</li><br/>
				<!-- <li>Cliquez sur "LE PERSONNEL" pour enregistrer un nouveau travailleur</li><br/> -->
				<li>Cliquez sur "LES CLASSES" pour vérifier les enregistrements effectués</li><br/>
				<li>Allez dans les paramètres pour régler vos configurations de compte</li><br/>
			</div>

			<div class="divdiv div_new_pupil">
				<h1 class="ttle title_new_worker">Enregistrer un élève</h1>

				<table id="tata" style="width: 90%; margin-left: 5%; background-color: inherit; border: 1px solid grey; padding: 25px;">
					<tr>
						<th colspan="2"><h3 class="subdivdivTtitle">IDENTITE DE BASE</h3></th>
					</tr>
					<tr>
						<td>
							<label class="labell">Nom</label><br/>
							<input class="innput" id="first_name_pupil" placeholder="Nom" />
						</td>
						<td>
							<label class="labell">Post-nom</label><br/>
							<input class="innput" id="second_name_pupil" placeholder="Post-nom" />
						</td>
					</tr>
					<tr>
						<td>
							<label class="labell">Prénom</label><br/>
							<input class="innput" id="last_name_pupil" placeholder="Prénom" />
						</td>
						<td>
							<label class="labell">Sexe</label><br/>
							<select id="gender_pupil" class="selects selects_entries">
								<option value=""> -- Choisir sexe -- </option>
								<option value="0">Féminin</option>
								<option value="1">Masculin</option>
							</select>						
						</td>
					</tr>
					<tr>
						<td>
							<label class="labell">Date de naissance</label><br/>
							<span class="separator_before_inputs">Né(e) le </span>
							<input type="date" id="pupil_birth_date" class="klkl class_date" />							
						</td>
						<td>
							<label class="separator_inputs">Lieu de naissance (ville)</label><br/>
							<input class="innput" id="pupil_birth_place" placeholder="Lieu de naissance" />							
						</td>
					</tr>
					<tr>
						<th colspan="2"><h3 class="subdivdivTtitle">A PROPOS DES PARENTS</h3></th>
					</tr>
					<tr>
						<td>
							<label class="labell">Noms du père</label><br/>
							<input class="innput" id="pupil_father_name" placeholder="Noms du père" />
						</td>
						<td>
							<label class="labell">Noms de la mère</label><br/>
							<input class="innput" id="pupil_mother_name" placeholder="Noms de la mère" />	
						</td>
					</tr>
					<tr>
						<td>
							<label class="labell">Status des parents : </label><br/>
					        <select class="selects select_pupil_cycle" id="parents_state">
					        	<option value="0" class="login_item"> -- Sélectionner ici --</option>
					            <option value="0" class="login_item">Ensemble</option>
					            <option value="1" class="login_item">Divorcés</option>
					            <option value="2" class="login_item">Célibataire</option>
					        </select>							
						</td>
						<td>
							<label class="labell">Vie des parents : </label><br/>
					        <select class=" selects select_pupil_cycle" id="parents_alive">
					        	<option value="0" class="login_item"> -- Sélectionner ici --</option>
					        	<option value="0" class="login_item">Les deux parents en vie</option>
					            <option value="1" class="login_item">Seul le père en vie</option>
					            <option value="2" class="login_item">Seule la mère en vie</option>
					        </select>
						</td>
					</tr>
					<tr>
						<td>
							<label class="labell">Vis avec : </label><br/>
					        <select class="selects select_pupil_cycle" id="lives_with">
					        	<option value="0" class="login_item"> -- Sélectionner ici --</option>
					            <option value="0" class="login_item">Les deux parents</option>
					            <option value="1" class="login_item">Le père</option>
					            <option value="2" class="login_item">La mère</option>
					            <option value="3" class="login_item">Adopté (e)</option>
					        </select>							
						</td>
					</tr>
					<tr>
						<td>
							<label class="labell">Travail pricipal du père : </label><br/>
							<input class="innput" id="father_work_pupil" placeholder="Travail du père" />
						</td>
						<td>
							<label class="labell">Travail principal de la mère : </label><br/>
							<input class="innput" id="mother_work_pupil" placeholder="Travail de la mère" />		
						</td>
					</tr>
					<tr><th colspan="2"><h3 class="subdivdivTtitle">ORIENTATION SCOLAIRE</h3></th></tr>
					<tr>
						<td>
							<label class="labell">Cycle d'étude : </label><br/>
				            <select class="selects select_pupil_cycle" id="cycle_school_pupil">
				            	<option value="0" class="login_item"> -- Sélectionner ici --</option>
				            	<?php

				            	if(count_cycles_exist() != 0)
				            	{
				            		$cycles00 = "SELECT cycle_id, cycle_name FROM cycle";
				            		$cycles11 = $database_connect->query($cycles00);
				            		while($cycless = $cycles11->fetchObject())
				            		{
				            			?><option value="<?= $cycless->cycle_id ?>" class="login_item"><?= $cycless->cycle_name ?></option><?php
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

				            	if(count_classes_exist() != 0)
				            	{
				            		$classes00 = "SELECT class_id, class_number FROM classes";
				            		$classes11 = $database_connect->query($classes00);
				            		while($classess = $classes11->fetchObject())
				            		{
				            			?><option value="<?= $classess->class_id ?>" class="login_item"><?= $classess->class_number ?></option><?php
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

				            	if(count_orders_exist() != 0)
				            	{
				            		$orders00 = "SELECT order_id, order_name FROM class_order";
				            		$orders11 = $database_connect->query($orders00);
				            		while($orderss = $orders11->fetchObject())
				            		{
				            			?><option value="<?= $orderss->order_id ?>" class="login_item"><?= $orderss->order_name ?></option><?php
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
				            			?><option value="<?= $sectionss->section_id ?>" class="login_item"><?= $sectionss->section_name ?></option><?php
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
				            			?><option value="<?= $optionss->option_id ?>" class="login_item"><?= $optionss->option_name ?></option><?php
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
				            			?><option value="<?= $yearss->year_id ?>" class="login_item"><?= $yearss->year_name ?></option><?php
				            		}
				            	}
				            	?>
				            </select>
						</td>
					</tr>
					<tr>
						<td colspan="2"><h3 class="subdivdivTtitleSub">STATUT COLAIRE DE L'ELEVE</h3></td>
					</tr>
					<tr>
						<td colspan="2">
							<label class="labell">Choisir statut</label><br/>
							<select id="statut_scolaire" class="selects selects_entries">
								<option value="0"> -- Choisir statut ici -- </option>
								<option selected value="0">Normal</option>
								<option value="1">Reddoublant (D)</option>
								<option value="2">Nouveau (N)</option>
								<option value="1">Nouveau-Reddoublant (ND)</option>
							</select>						
						</td>
					</tr>
					<tr><th colspan="2"><h3 class="subdivdivTtitle">CONTACTS (DES PARENTS DE PREFERENCE)</h3></th></tr>
					<tr>
						<td>
							<label class="labell">Adresse Electronique</label><br/>
							<input id="email_address_pupil" type="text" class="innput" placeholder="E-mail" /><br/>
						</td>
						<td>
							<label class="labell">Adresse physique</label><br/>
							<input id="physical_address_pupil" type="text" class="innput" placeholder="Adresse physique" />
						</td>
					</tr>
					<tr>
						<td>
							<label class="labell">Numéro de téléphone 1</label><br/>
							<input id="contact_1_pupil" type="number" class="selects" placeholder="+ 000 000 000 000" />
						</td>
						<td>
							<label class="labell">Numéro de téléphone 2</label><br/>
							<input id="contact_2_pupil" type="number" class="selects" placeholder="+ 000 000 000 000" />
						</td>
					</tr>
					<tr>
						<td>
							<label class="labell">Numéro de téléphone 3</label><br/>
							<input id="contact_3_pupil" type="number" class="selects" placeholder="+ 000 000 000 000" />
						</td>
						<td>
							<label class="labell">Numéro de téléphone 4</label><br/>
							<input id="contact_4_pupil" type="number" class="selects" placeholder="+ 000 000 000 000" />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<button id="validate_insert_pupil" class="validate_login" style="width: 100%;">Enregistrer l'élève</button>
						</td>
					</tr>
				</table>
			</div>


			<div class="sub_divdiv div_reglages">
				<h1 class="subdivdivTtitle" style="text-align: center; padding-left: 50px; padding-right: 50px;">Enregistrer les informations sur les classes, cycles d'études, ordres des classes, options d'études et années scolaires</h1>

				<table style="width: 90%; margin-left: 5%; background-color: inherit; border: 1px solid grey; padding: 25px;">
					<tr><th style="border: none;" colspan="2"><label class="labell">Enregistrer un cycle d'étude : </label></th></tr>
					<tr>
						<td style="border: none;">
							<input type="text" class="innput" id="study_cycle" placeholder="Intitulé du cycle d'etude" style="width: 96%; height: 38px; margin-top: 14px;" />
						</td>
						<td style="border: none;">
							<button id="validate_insert_study_cycle" class="validate_login" style="width: 100%;">Enregistrer le cycle d'étude</button>
						</td>
					</tr>
					<tr><th style="border: none;" colspan="2"><label class="labell">Ajouter un numéro de classe : </label></th></tr>
					<tr>
						<td style="border: none;">
							<input type="number" class="selects" id="class_number" placeholder="Numéro de la classe. Ex : 1" style="width: 96%; height: 38px; margin-top: 14px;" />
						</td>
						<td style="border: none;">
							<button id="validate_insert_class" class="validate_login" style="width: 100%;">Enregistrer la classe</button>
						</td>
					</tr>
					<tr><th style="border: none;" colspan="2"><label class="labell">Ajouter un ordre de classe : </label></th></tr>
					<tr>
						<td style="border: none;">
							<input type="text" class="innput" id="class_order" placeholder="Ordre de classe en majuscules. Ex : A" style="width: 96%; height: 38px; margin-top: 14px;" />
						</td>
						<td style="border: none;">
							<button id="validate_insert_class_order" class="validate_login" style="width: 100%;">Enregistrer l'ordre de classe</button>
						</td>
					</tr>
					<tr><th style="border: none;" colspan="2"><label class="labell">Enregistrer une section : </label></th></tr>
					<tr>
						<td style="border: none;">
							<input type="text" class="innput" id="section_name" placeholder="Intitulé de la section" style="width: 96%; height: 38px; margin-top: 14px;" />
						</td>
						<td style="border: none;">
							<button id="validate_insert_section" class="validate_login" style="width: 100%;">Enregistrer la section</button>
						</td>
					</tr>
					<tr><th style="border: none;" colspan="2"><label class="labell">Ajouter une option : </label></th></tr>
					<tr>
						<td style="border: none;">
							<input type="text" class="innput" id="option_name" placeholder="Intitulé de l'option" style="width: 96%; height: 38px; margin-top: 14px;" />
						</td>
						<td style="border: none;">
							<button id="validate_insert_option" class="validate_login" style="width: 100%;">Enregistrer l'option</button>
						</td>
					</tr>
					<tr><th style="border: none;" colspan="2"><label class="labell">Ajouter une année scolaire : </label></th></tr>
					<tr>
						<td style="border: none;">
							<input type="text" class="innput" id="school_year" placeholder="Ex : 2018-2019" style="width: 96%; height: 38px; margin-top: 14px;" />
						</td>
						<td style="border: none;">
							<button id="validate_insert_school_year" class="validate_login" style="width: 100%;">Enregistrer l'année scolaire</button>
						</td>
					</tr>
					<tr>
						<td style="border: none; text-align: center;" colspan="2">
							<a href="index.php?_=home.secretor" class="validate_login_bis" style="width: 100%;">Cliquez pour actualiser les informations entrées</a>
						</td>
					</tr>
				</table><br/><br/>
			</div>

			<div class="divdiv div_new_course">
				<br/><h1>Ajouter un cours</h1>

				<table style="width: 90%; margin-left: 5%; background-color: inherit; border: 1px solid grey; padding: 25px;">
					<tr>
						<td style="border: none; width: 33.3%;">
							<label class="labell">Intitulé du cours : </label><br/>
					        <input type="text" class="selects" id="course_name" placeholder="Ex : Informatique" />
						</td>
						<td style="border: none; width: 33.3%;">
							<label class="labell">Cycle d'étude : </label><br/>
				            <select class="selects_migrations select_pupil_cycle" id="cycle_school_course">
				            	<option value="0" class="login_item"> -- Sélectionner ici --</option>
				            	<?php

				            	if(count_cycles_exist() != 0)
				            	{
				            		$cycles00 = "SELECT cycle_id, cycle_name FROM cycle";
				            		$cycles11 = $database_connect->query($cycles00);
				            		while($cycless = $cycles11->fetchObject())
				            		{
				            			?><option value="<?= $cycless->cycle_id ?>" class="login_item"><?= $cycless->cycle_name ?></option><?php
				            		}
				            	}
				            	?>
				            </select>	
						</td>
						<td style="border: none; width: 33.3%;">
							<label class="labell">Classe : </label><br/>
				            <select class="selects_migrations select_pupil_cycle" id="class_school_course">
				            	<option value="0" class="login_item"> -- Sélectionner ici --</option>
				            	<?php

				            	if(count_classes_exist() != 0)
				            	{
				            		$classes00 = "SELECT class_id, class_number FROM classes";
				            		$classes11 = $database_connect->query($classes00);
				            		while($classess = $classes11->fetchObject())
				            		{
				            			?><option value="<?= $classess->class_id ?>" class="login_item"><?= $classess->class_number ?></option><?php
				            		}
				            	}
				            	?>
				            </select>							
						</td>
					</tr>
					<tr>
						<td style="border: none; width: 33.3%;">
							<label class="labell">Section : </label><br/>
				            <select class="selects_migrations select_pupil_section" id="class_section_course">
				            	<option value="0" class="login_item"> -- Sélectionner ici --</option>
				            	<?php

				            	if(count_sections_exist() != 0)
				            	{
				            		$sections00 = "SELECT section_id, section_name FROM sections";
				            		$sections11 = $database_connect->query($sections00);
				            		while($sectionss = $sections11->fetchObject())
				            		{
				            			?><option value="<?= $sectionss->section_id ?>" class="login_item"><?= $sectionss->section_name ?></option><?php
				            		}
				            	}
				            	?>
				            </select>							
						</td>
						<td style="border: none; width: 33.3%;">
							<label class="labell">Option : </label><br/>
				            <select class="selects_migrations select_pupil_option" id="class_option_course">
				            	<option value="0" class="login_item"> -- Sélectionner ici --</option>
				            	<?php

				            	if(count_options_exist() != 0)
				            	{
				            		$options00 = "SELECT option_id, option_name FROM options";
				            		$options11 = $database_connect->query($options00);
				            		while($optionss = $options11->fetchObject())
				            		{
				            			?><option value="<?= $optionss->option_id ?>" class="login_item"><?= $optionss->option_name ?></option><?php
				            		}
				            	}
				            	?>
				            </select>							
						</td>
						<td style="border: none; width: 33.3%;">
							<label class="labell">Année scolaire d'application du cours :</label><br/>
					        <select class="selects_migrations select_pupil_cycle" id="school_year_course">
					        	<option value="0" class="login_item"> -- Sélectionner l'année -- </option>
					        	<?php

					        	if(count_school_years_exist() != 0)
					        	{
					        		$years00 = "SELECT year_id, year_name FROM school_years";
					        		$years11 = $database_connect->query($years00);
					        		while($yearss = $years11->fetchObject())
					        		{
					        			?>
					        			<option value="<?= $yearss->year_id ?>" class="login_item"><?= $yearss->year_name ?></option>
					        			<?php
					        		}
					        	}
					        	?>
					        </select>							
						</td>
					</tr>
					<tr>
						<td style="border: none; width: 33.3%;">
				            <label class="labell">Points maxima : </label><br/>
				            <input type="number" class="selects" id="maxima" placeholder="Ex : 100" />
						</td>
						<td style="border: none;" colspan="2">
							<button id="validate_insert_course" class="validate_login" style="width: 100%;" >Enregistrer le cours</button>	
						</td>
					</tr>
				</table>

				<!-- <br/><h1>Ajouter les domines et les sous-domaines des cours</h1>

				<table style="width: 90%; margin-left: 5%; background-color: inherit; border: 1px solid grey; padding: 25px;">
					<tr>
						<td style="border: none; width: 33.3%;">
							<label class="labell">Intitulé du domaine parent : </label><br/>
							<input type="text" class="selects" id="domain_name_domain" placeholder="Ex : Domaine des sciences" />
						</td>
						<td style="border: none; width: 33.3%;">
							<label class="labell">Intitulé du domaine parent : </label><br/>
							<select class="selects_migrations select_pupil_cycle" id="school_year_domain">
					        	<option value="0" class="login_item"> -- Sélectionner l'année -- </option>
					        	<?php

					        	if(count_school_years_exist() != 0)
					        	{
					        		$years00 = "SELECT year_id, year_name FROM school_years";
					        		$years11 = $database_connect->query($years00);
					        		while($yearss = $years11->fetchObject())
					        		{
					        			?>
					        			<option value="<?= $yearss->year_id ?>" class="login_item"><?= $yearss->year_name ?></option>
					        			<?php
					        		}
					        	}
					        	?>
					        </select>	
						</td>
						<td style="border: none; width: 33.3%;">
							<button id="validate_insert_domain" class="validate_login" style="width: 100%;" >Enregistrer le domaine</button>	
						</td>
					</tr>
				</table>

				<br/><h1>Ajouter les domines et les sous-domaines des cours</h1>

				<table style="width: 90%; margin-left: 5%; background-color: inherit; border: 1px solid grey; padding: 25px;">
					<tr>
						<td style="border: none; width: 33.3%;">
							<label class="labell">Intitulé du domaine parent : </label><br/>
							<select class="selects_migrations select_pupil_cycle" id="domain_name">
					        	<option value="0" class="login_item"> -- Sélectionner l'année -- </option>
					        	<?php

					        	// if(count_school_years_exist() != 0)
					        	// {
					        		// $years001 = "SELECT * FROM courses_domains";
								// $years111 = $database_connect->query($years001);
					        		// while($yearss1 = $years111->fetchObject())
					        		// {
					        			?>
					        			<option value="<?= $yearss1->domain_id ?>" class="login_item"><?= $yearss1->domain_name ?></option>
					        			<?php
					        		// }
					        	// }
					        	?>
					        </select>
						</td>
						<td style="border: none; width: 33.3%;">
							<label class="labell">Intitulé du sous-domaine : </label><br/>
							<input type="text" class="selects" id="sub_domain_name" placeholder="Ex : Sous-domaine des Mathématiques" />
						</td>
					</tr>
					<tr>
						<td style="border: none;" colspan="2">
							<button id="validate_insert_course" class="validate_login" style="width: 100%;" >Enregistrer le sous-domaine</button>	
						</td>
					</tr>
				</table>
			</div><br/><br/> -->
		</div>
	</div>
