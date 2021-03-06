<?php

	if(session_in() == 0)
	{
		Header('Location: index.php?_=login');
	}

?>

		<div class="main_middle_container">
			<div style="padding-left: 15px;">

				<div class="sub_divdiv div_reglages">
					<h1 class="subdivdivTtitle">Enregistrer les informations sur les classes section d'etude et annee scolaire</h1>
					<label class="labell">Enregistrer un cycle d'etude : </label><br/>
			        <input type="text" class="innput" id="study_cycle" placeholder="Nom du cycle d'etude" />
			        <button id="validate_insert_study_cycle" class="validate_login">Enregistrer le cycle d'etude</button><br/>

					<br/><label class="labell">Enregistrer une classe : </label><br/>
			        <input type="number" class="selects" id="class_number" placeholder="Nmero de la classe. Ex : 1" />
			        <button id="validate_insert_class" class="validate_login">Enregistrer la classe</button><br/>

					<br/><label class="labell">Ajouter un ordre de classe : </label><br/>
					<input type="text" class="innput" id="class_order" placeholder="Ordre de classe en majuscules. Ex : A" />
					<button id="validate_insert_class_order" class="validate_login">Enregistrer l'ordre de classe</button><br/>

					<br/><label class="labell">Enregistrer une section : </label><br/>
			        <input type="text" class="innput" id="section_name" placeholder="Nom de la section" />
			        <button id="validate_insert_section" class="validate_login">Enregistrer la section</button><br/>

					<br/><label class="labell">Ajouter une option : </label><br/>
			        <input type="text" class="innput" id="option_name" placeholder="Nom de l'option" />
			        <button id="validate_insert_option" class="validate_login">Enregistrer l'option</button><br/>

					<br/><label class="labell">Ajouter une annee scolaire : </label><br/>
			        <input type="text" class="innput" id="school_year" placeholder="Ex : 2018-2019" />
					<button id="validate_insert_school_year" class="validate_login">Enregistrer l'annee scolaire</button><br/><br/>

					<div style="text-align: center;margin-top: 20px;">
						<a href="index.php?_=home.secretor" class="validate_login_bis">Cliquez pour actualiser les informations entr??es</a>
					</div>
				</div>

				<div class="divdiv div_new_course">
					<br/><h1>Ajouter un cours</h1>
					<label class="labell">Intitul?? du cours : </label><br/>
			        <input type="text" class="selects" id="course_name" placeholder="Ex : Informatique" />
						<br/><label class="labell">Cycle d'??tude : </label><br/>
			            <select class="selects select_pupil_cycle" id="cycle_school_course">
			            	<option value="" class="login_item"> -- S??l??ctionner ici --</option>
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

						<br/><label class="labell">Classe : </label><br/>
			            <select class="selects select_pupil_cycle" id="class_school_course">
			            	<option value="" class="login_item"> -- Selectionner ici --</option>
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

						<br/><label class="labell">Ordre de classe : </label><br/>
			            <select class="selects select_pupil_cycle" id="class_order_course">
			            	<option value="" class="login_item"> -- Selectionner ici --</option>
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

						<br/><label class="labell">Section : </label><br/>
			            <select class="selects select_pupil_section" id="class_section_course">
			            	<option value="" class="login_item"> -- Selectionner ici --</option>
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

						<br/><label class="labell">Option : </label><br/>
			            <select class="selects select_pupil_option" id="class_option_course">
			            	<option value="" class="login_item"> -- Selectionner ici --</option>
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
			            <br/><label class="labell">Points maxima : </label><br/>
			            <input type="number" class="selects" id="maxima" placeholder="Ex : 100" /><br/>
					<button id="validate_insert_course" class="validate_login">Enregistrer le cours</button><br/>
				</div><br/>
			</div>

			<div class="divdiv div_new_worker" style="padding-left: 25px;">
				<h1 class="ttle title_new_worker">Enregistrer un nouveau travailleur</h1>
				<div class="sub_divdiv main_enter">
					<h3 class="subdivdivTtitle">Identit?? de base</h3>
					<label class="labell">Noms</label><br/>
					<input class="innput" id="first_name_personnel_enter" placeholder="Nom" />
					<input class="innput" id="second_name_personnel_enter" placeholder="Post-nom" />
					<input class="innput" id="last_name_personnel_enter" placeholder="Pr??nom" />

					<br/><label class="labell">Sexe</label><br/>
					<select id="gender" class="selects selects_entries">
						<option value=""> -- Choisir ici -- </option>
						<option value="0">F??minin</option>
						<option value="1">Masculin</option>
					</select>

					<br/><label class="labell">Lieu et date de naissance</label><br/>
					<span class="separator_before_inputs">N??(e) le </span>
					<input type="date" id="worker_birth_date" class="klkl class_date" />
					<span class="separator_inputs">??</span>
					<input class="innput klkl" id="worker_birth_place" placeholder="Lieu de naissance" />

					<br/><label class="labell">Poste au sein de l'??tablissement</label><br/>
		            <select class="selects select_poste" id="select_poste">
		            <option value="" class="login_item"> -- S??lectionner ici --</option>
		                <option value="Directeur de discipline" class="login_item">Directeur de discipline</option>
		                <option value="Directeur des ??tudes" class="login_item">Directeur des ??tudes</option>
		                <option value="Secr??taire de l'??cole" class="login_item">Secr??taire de l'??cole</option>
		                <option value="Directeur des finances" class="login_item">Directeur des finances</option>
		                <option value="Enseignant" class="login_item">Enseignant</option>
		                <option value="Caissier/Caissi??re" class="login_item">Caissier/Caissi??re</option>
		            </select>

					<br/><label class="labell">Etat civil</label><br/>
					<select id="civil_state" class="selects selects_entries">
						<option value=""> -- Choisir ici -- </option>
						<option value="0">C??libataire</option>
						<option value="1">Mari?? (e)</option>
					</select>
					<br/><label class="labell">Si mari?? (e), ??tat du mariage</label><br/>
					<select id="mariage_state" class="selects selects_entries">
						<option value=""> -- Choisir ici -- </option>
						<option value="Partenaire en vie">Partenaire en vie</option>
						<option value="Divorc?? (e)">Divorc?? (e)</option>
						<option value="Veuf (ve)">Veuf (ve)</option>
					</select>

					<br/><label class="labell">Nombre d'enfants</label><br/>
					<input id="children_number" type="number" class="selects" placeholder="Nombre d'enfants" />
				</div>
				<div class="sub_divdiv school_about">
					<h3 class="subdivdivTtitle">Etudes faites</h3>
					<br/><label class="labell">Diplom?? (e) en : </label><br/>
		            <select class=" selects select_poste" id="secondary_in">
		            <option value="" class="login_item"> -- S??lectionner ici --</option>
		                <option value="Litt??raire (Latin-philosophie)" class="login_item">Litt??raire (Latin-philosophie)</option>
		                <option value="Litt??raire (Latin-grec)" class="login_item">Litt??raire (Latin-grec)</option>
		                <option value="Math??matique-Physique" class="login_item">Math??matique-Physique</option>
		                <option value="Chimie-Biologie" class="login_item">Chimie-Biologie</option>
		                <option value="P??dagogie G??n??rale" class="login_item">P??dagogie G??n??rale</option>
		                <option value="Social" class="login_item">Social</option>
		                <option value="Commerciale et gestion" class="login_item">Commerciale et gestion</option>
		                <option value="Commerciale Informatique" class="login_item">Commerciale Informatique</option>
		                <option value="P??che et Agriculture" class="login_item">P??che et Agriculture</option>
		                <option value="Hotellerie" class="login_item">Hotellerie</option>
		                <option value="Informatique" class="login_item">Informatique</option>
		                <option value="Comptabilit??" class="login_item">Comptabilit??</option>
		                <option value="Electricit??" class="login_item">Electricit??</option>
		                <option value="M??canique g??n??rale" class="login_item">M??canique g??n??rale</option>
		                <option value="Nutrition" class="login_item">Nutrition</option>
		                <option value="Automobile" class="login_item">Automobile</option>
		            </select>

					<br/><label class="labell">Gradu?? (e) en : </label><br/>
		            <select class="selects select_poste" id="graduated_in">
		            <option value="" class="login_item"> -- S??lectionner ici --</option>
		                <option value="Lettres">Lettres</option>
		                <option value="Organisation des entreprises">Organisation des entreprises</option>
		                <option value="Informatique de gestion">Informatique de gestion</option>
		                <option value="Informatique">Informatique</option>
		                <option value="G??nie Logiciel">G??nie Logiciel</option>
		                <option value="Gestion des entreprises">Gestion des entreprises</option>
		                <option value="Chimie">Chimie</option>
		                <option value="Economie">Economie</option>
		                <option value="G??ologie">G??ologie</option>
		                <option value="Agronomie">Agronomie</option>
		                <option value="Biologie">Biologie</option>
		                <option value="Anglais">Anglais</option>
		                <option value="G??ographie">G??ographie</option>
		                <option value="Histoire">Histoire</option>
		                <option value="Sciences sociales">Sciences sociales</option>
		                <option value="Environnement">Environnement</option>
		                <option value="Journalisme">Journalisme</option>
		                <option value="Construction">Construction</option>
		                <option value="Topographie">Topographie</option>
		                <option value="Statistiques">Statistiques</option>
		                <option value="Finance">Finance</option>
		                <option value="Comptabilit??">Comptabilit??</option>
		                <option value="R??seaux et t??l??communications">R??seaux et t??l??communications</option>
		                <option value="Th??ologie">Th??ologie</option>
		                <option value="Banques">Banques</option>
		                <option value="Architecture">Architecture</option>
		                <option value="Electronique">Electronique</option>
		                <option value="Electricit??">Electricit??</option>
		                <option value="Physique">Physique</option>
		                <option value="Droit">Droit</option>
		                <option value="Automobile">Automobile</option>
		                <option value="Comptabilit??">Comptabilit??</option>
		                <option value="Math??matiques">Math??matiques</option>
		                <option value="M??canique g??n??rale">M??canique g??n??rale</option>
		                <option value="M??decine">M??decine</option>
		                <option value="Infirm??rie">Infirm??rie</option>
		                <option value="Psychologie clinique">Psychologie clinique</option>
		            </select>
		            
					<br/><label class="labell">Licenci?? (e) en : </label><br/>
		            <select class="selects select_poste" id="bachelor_in">
		            <option value="" class="login_item"> -- S??lectionner ici --</option>
		                <option value="Lettres">Lettres</option>
		                <option value="Organisation des entreprises">Organisation des entreprises</option>
		                <option value="Informatique de gestion">Informatique de gestion</option>
		                <option value="Informatique">Informatique</option>
		                <option value="G??nie Logiciel">G??nie Logiciel</option>
		                <option value="Gestion des entreprises">Gestion des entreprises</option>
		                <option value="Chimie">Chimie</option>
		                <option value="Economie">Economie</option>
		                <option value="G??ologie">G??ologie</option>
		                <option value="Agronomie">Agronomie</option>
		                <option value="Biologie">Biologie</option>
		                <option value="Anglais">Anglais</option>
		                <option value="G??ographie">G??ographie</option>
		                <option value="Histoire">Histoire</option>
		                <option value="Sciences sociales">Sciences sociales</option>
		                <option value="Environnement">Environnement</option>
		                <option value="Journalisme">Journalisme</option>
		                <option value="Construction">Construction</option>
		                <option value="Topographie">Topographie</option>
		                <option value="Statistiques">Statistiques</option>
		                <option value="Finance">Finance</option>
		                <option value="Comptabilit??">Comptabilit??</option>
		                <option value="R??seaux et t??l??communications">R??seaux et t??l??communications</option>
		                <option value="Th??ologie">Th??ologie</option>
		                <option value="Banques">Banques</option>
		                <option value="Architecture">Architecture</option>
		                <option value="Electronique">Electronique</option>
		                <option value="Electricit??">Electricit??</option>
		                <option value="Physique">Physique</option>
		                <option value="Droit">Droit</option>
		                <option value="Automobile">Automobile</option>
		                <option value="Comptabilit??">Comptabilit??</option>
		                <option value="Math??matiques">Math??matiques</option>
		                <option value="M??canique g??n??rale">M??canique g??n??rale</option>
		                <option value="M??decine">M??decine</option>
		                <option value="Infirm??rie">Infirm??rie</option>
		                <option value="Psychologie clinique">Psychologie clinique</option>
		            </select>

					<br/><label class="labell">Masters en : </label><br/>
		            <select class="selects select_poste" id="masters_in">
		            <option value="" class="login_item"> -- S??lectionner ici --</option>
		                <option value="Lettres">Lettres</option>
		                <option value="Organisation des entreprises">Organisation des entreprises</option>
		                <option value="Informatique de gestion">Informatique de gestion</option>
		                <option value="Informatique">Informatique</option>
		                <option value="G??nie Logiciel">G??nie Logiciel</option>
		                <option value="Gestion des entreprises">Gestion des entreprises</option>
		                <option value="Chimie">Chimie</option>
		                <option value="Economie">Economie</option>
		                <option value="G??ologie">G??ologie</option>
		                <option value="Agronomie">Agronomie</option>
		                <option value="Biologie">Biologie</option>
		                <option value="Anglais">Anglais</option>
		                <option value="G??ographie">G??ographie</option>
		                <option value="Histoire">Histoire</option>
		                <option value="Sciences sociales">Sciences sociales</option>
		                <option value="Environnement">Environnement</option>
		                <option value="Journalisme">Journalisme</option>
		                <option value="Construction">Construction</option>
		                <option value="Topographie">Topographie</option>
		                <option value="Statistiques">Statistiques</option>
		                <option value="Finance">Finance</option>
		                <option value="Comptabilit??">Comptabilit??</option>
		                <option value="R??seaux et t??l??communications">R??seaux et t??l??communications</option>
		                <option value="Th??ologie">Th??ologie</option>
		                <option value="Banques">Banques</option>
		                <option value="Architecture">Architecture</option>
		                <option value="Electronique">Electronique</option>
		                <option value="Electricit??">Electricit??</option>
		                <option value="Physique">Physique</option>
		                <option value="Droit">Droit</option>
		                <option value="Automobile">Automobile</option>
		                <option value="Comptabilit??">Comptabilit??</option>
		                <option value="Math??matiques">Math??matiques</option>
		                <option value="M??canique g??n??rale">M??canique g??n??rale</option>
		                <option value="M??decine">M??decine</option>
		                <option value="Infirm??rie">Infirm??rie</option>
		                <option value="Psychologie clinique">Psychologie clinique</option>
		            </select>

					<br/><label class="labell">Docteur en : </label><br/>
		            <select class="selects select_poste" id="phd_in">
		            <option value="" class="login_item"> -- S??lectionner ici --</option>
		                <option value="Lettres">Lettres</option>
		                <option value="Organisation des entreprises">Organisation des entreprises</option>
		                <option value="Informatique de gestion">Informatique de gestion</option>
		                <option value="Informatique">Informatique</option>
		                <option value="G??nie Logiciel">G??nie Logiciel</option>
		                <option value="Gestion des entreprises">Gestion des entreprises</option>
		                <option value="Chimie">Chimie</option>
		                <option value="Economie">Economie</option>
		                <option value="G??ologie">G??ologie</option>
		                <option value="Agronomie">Agronomie</option>
		                <option value="Biologie">Biologie</option>
		                <option value="Anglais">Anglais</option>
		                <option value="G??ographie">G??ographie</option>
		                <option value="Histoire">Histoire</option>
		                <option value="Sciences sociales">Sciences sociales</option>
		                <option value="Environnement">Environnement</option>
		                <option value="Journalisme">Journalisme</option>
		                <option value="Construction">Construction</option>
		                <option value="Topographie">Topographie</option>
		                <option value="Statistiques">Statistiques</option>
		                <option value="Finance">Finance</option>
		                <option value="Comptabilit??">Comptabilit??</option>
		                <option value="R??seaux et t??l??communications">R??seaux et t??l??communications</option>
		                <option value="Th??ologie">Th??ologie</option>
		                <option value="Banques">Banques</option>
		                <option value="Architecture">Architecture</option>
		                <option value="Electronique">Electronique</option>
		                <option value="Electricit??">Electricit??</option>
		                <option value="Physique">Physique</option>
		                <option value="Droit">Droit</option>
		                <option value="Automobile">Automobile</option>
		                <option value="Comptabilit??">Comptabilit??</option>
		                <option value="Math??matiques">Math??matiques</option>
		                <option value="M??canique g??n??rale">M??canique g??n??rale</option>
		                <option value="M??decine">M??decine</option>
		                <option value="Infirm??rie">Infirm??rie</option>
		                <option value="Psychologie clinique">Psychologie clinique</option>
		            </select>
				</div>

				<div class="sub_divdiv school_about">
					<h3 class="subdivdivTtitle">Contacts</h3>
					<br/><label class="labell">Adresse Electronique</label><br/>
					<input id="email_address" type="text" class="innput" placeholder="E-mail" /><br/>

					<br/><label class="labell">Adresse physique</label><br/>
					<input id="physical_address" type="text" class="innput" placeholder="Adresse physique" /><br/>

					<br/><label class="labell">Num??ro de t??l??phone 1</label><br/>
					<input id="contact_1" type="number" class="selects" placeholder="+ 000 000 000 000" />

					<br/><label class="labell">Num??ro de t??l??phone 2</label><br/>
					<input id="contact_2" type="number" class="selects" placeholder="+ 000 000 000 000" />

					<br/><label class="labell">Num??ro de t??l??phone 3</label><br/>
					<input id="contact_3" type="number" class="selects" placeholder="+ 000 000 000 000" />

					<br/><label class="labell">Num??ro de t??l??phone 4</label><br/>
					<input id="contact_4" type="number" class="selects" placeholder="+ 000 000 000 000" />
				</div>

				<button id="validate_insert_worker" class="validate_login">Enregistrer le travailleur</button><br/><br/><br/><br/>
			</div>

			<div class="divv_info">
				<span style="text-decoration: underline;">Cliquez sur un item du menu pour commencer</span><br/><br/>
				<li>Gerez les informations en rapport avec vos ??l??ves</li><br/>
				<li>Cliquez sur "PARCOURIR" pour consulter les informations<br/>en rapport avec les classes. Allez ensuite vers une des ann??es disponibles </li><br/>
				<li>Ensuite, cliquez sur "ENTRER LES POINTS" pour enregistrer les cotes</li><br/>
				<li>Ensuite, cliquez sur "FICHE DES POINTS" pour voir les fiches des cotes par periode</li><br/>
				<li>Allez dans les param??tres pour regler vos configurations de compte</li><br/>
			</div>
		</div>

		<div class="show_third_party">
			<?php
	    	if(count_school_years_exist() != 0)
	    	{
	    		$years000 = "SELECT year_id, year_name FROM school_years";
	    		$years111 = $database_connect->query($years000);
	    		while($yearsss = $years111->fetchObject())
	    		{
	    			?>
	    			<a href="index.php?_=classes&year=<?=$yearsss->year_id?>"><?= $yearsss->year_name ?></a><br/>
	    			<?php
	    		}
	    	}
			?>
		</div>
