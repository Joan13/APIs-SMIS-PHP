<?php

	if(session_in() == 0)
	{
		Header('Location: ./?_=login');
	}

	if(session_in() == 1 && $page != 'home.promotor') {
		Header('Location: ./?_=home.promotor');
	} else if(session_in() == 1 && $page == 'home.promotor') {
		
	} else {
		Header('Location: ./?_=login');
	}

?>

<div style="text-align:center;" class="main_middle_container_caisse"><br/><br/><br/><br/><br/>
	<h2 style="text-align: center;">RECHERCHEZ UN ELEVE</h2>
	<div class="logo_right_sub_container">
		<img class="logo_login" style="border: 1px solid rgba(0, 0, 0, 0.5);" src=" <?php if ($school_name == "Collège Alfajiri") {echo "images/other/Alfajiri.jpeg";} else if($school_name == "Collège Saint-Paul") {echo "images/other/Saint_Paul.jpeg";} else if($school_name == "Complexe Scolaire \"Elite\"") {echo "images/other/logo.png";} else {echo "images/other/user-3.png";} ?>" width="140" height="180" />
	</div><br/>
	<input type="text" class="innput" id="input_search_first_name" placeholder="Nom" />
	<input type="text" class="innput" id="input_search_second_name" placeholder="Post-nom" />
	<div>
		<select class="selects select_pupil_cycle" id="school_year_search">
			<option value="" class="login_item"> -- Sélectionner l'année scolaire --</option>
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
	</div>
	<button id="validate_search_pupil">LANCER LA RECHERCHE</button>
</div>

<div class="show_third_party_caisse" style="text-align: left;">
	<div style="color: white; padding-left: 25px;" id="left_div">

	    <div class="liste_libelle" style="color:black;">

			<strong id="show_update_school_info" style="cursor: pointer;">EDITER LES INFORMATIONS DE BASE DE L'ECOLE</strong><br/><br/>
			<div id="base_school_info" style="display: none;">
				<span>Entrez nom du Chef d'établissement, la date de fin d'année et l'année scolaire concernée</span><br/><br/>
				<input type="text" id="name_promoter" class="innput" placeholder="Nom chef d'établissement" /><br/>
				<input type="text" id="code_school" class="innput" placeholder="Code de l'établissement" /><br/>
				<input type="date" id="date_end" class="innput" /><br/>

				<select class="selects" id="school_year_info">
					<option value="" class="login_item"> -- Sélectionner l'année scolaire --</option>
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
	        		<button class="validate_login" id="submit_base_school_info" style="width: 80%;">Enregister la mise à jour</button>
			</div><br/>

	        	<p class="title_libs" style="font-weight: bold; color: black;">APERCUS</p>
	        	<div class="all_libelles_entries">

			<table style="border: 1px solid rgba(255, 255, 255, 0.2);">
				<caption>APERCU GLOBAL DES NOTES DISCIPLINAIRES DE L'ANNEE 2020-2021</caption>
					<tr>
						<th style="color: black; text-align: left; padding-left: 10px; width: 100px;">Mention</th>
						<th style="color: black; text-align: center; width: 100px;">Nbr élèves</th>
						<th style="color: black; text-align: center; width: 100px;">Tot élèves</th>
						<th style="color: black; text-align: center; width: 50px;">%</th>
					</tr>

					<?php
					$mmention = 0;
					$mentions = array();
					$mentionrr = 0;
					$mentionsrr = array();
					$mentionsrrr = array();

					$select_all0rr = "SELECT * FROM conduite WHERE school_year=1 ORDER BY main_conduite ASC";
					$select_all1rr = $database_connect->query($select_all0rr);
					while($select_allrr = $select_all1rr->fetchObject()) {
					$mentionrr = $select_allrr->main_conduite;

						if(!in_array($select_allrr->main_conduite, $mentionsrrr)) {

						$scqueryrr = "SELECT conduite_id, COUNT(*) AS count_con FROM conduite WHERE main_conduite=?";
						$screquestrr = $database_connect->prepare($scqueryrr);
						$screquestrr->execute(array($select_allrr->main_conduite));
						$scresponserrr = $screquestrr->fetchObject();

						$scqueryrr = "SELECT school_year, COUNT(*) AS sum_pupils FROM pupils_info WHERE school_year=1";
						$screquestrr = $database_connect->query($scqueryrr);
						$scresponserr = $screquestrr->fetchObject();

						if ($mentionrr == 1) {
								$mentionrr = "Excellent";
							} else if ($mentionrr == 2) {
								$mentionrr = "Tres bien";
							} else if ($mentionrr == 3) {
								$mentionrr = "Bien";
							} else if ($mentionrr == 4) {
								$mentionrr = "Assez bien";
							} else if ($mentionrr == 5) {
								$mentionrr = "Mediocre";
							} else {
								$mentionrr = "Mauvais";
							}
							?>
							<tr>
								<td style="text-align: left; padding-left: 10px;">
									<strong><?= strtoupper($mentionrr) ?></strong>
								</td>
								<td style="text-align: center;">
									<strong><?= $scresponserrr->count_con ?></strong>
								</td>
								<td style="text-align: center;">
									<strong><?= $scresponserr->sum_pupils ?></strong>
								</td>
								<td style="text-align: center;">
									<strong><?= ($scresponserrr->count_con * 100) / $scresponserr->sum_pupils ?></strong>
								</td>
							</tr>

							<?php
			
							if(!in_array($select_allrr->main_conduite, $mentionsrrr)) {
								array_push($mentionsrrr, $select_allrr->main_conduite);
							}
						}
					}
					?>		
					</table>

					<br/><br/>

				<?php
				for ($i = 1; $i <= 4; $i++) {

					if ($i == 1) {
						$periode = "P1";
					} else if($i == 2) {
						$periode = "P2";
					} else if($i == 3) {
						$periode = "P3";
					} else {
						$periode = "P4";
					}

					?>
					<table style="border: 1px solid rgba(255, 255, 255, 0.2);">
					<caption>APERCU DES NOTES DISCIPLINAIRES <?= $periode ?></caption>
					<tr>
						<th style="color: black; text-align: left; padding-left: 10px; width: 100px;">Mention</th>
						<th style="color: black; text-align: center; width: 100px;">Nbr élèves</th>
						<th style="color: black; text-align: center; width: 100px;">Tot élèves</th>
						<th style="color: black; text-align: center; width: 50px;">%</th>
					</tr>

					<?php
					$mmention = 0;
					$mentions = array();
					$mentionrr = 0;
					$mentionsrr = array();

					$select_all0rr = "SELECT * FROM conduite WHERE school_year=1 AND periode=$i ORDER BY main_conduite ASC";
					$select_all1rr = $database_connect->query($select_all0rr);
					while($select_allrr = $select_all1rr->fetchObject()) {
					$mentionrr = $select_allrr->main_conduite;

						if(!in_array($select_allrr->main_conduite, $mentionsrr)) {

						$scqueryrr = "SELECT conduite_id, COUNT(*) AS count_con FROM conduite WHERE main_conduite=? AND periode=$i";
						$screquestrr = $database_connect->prepare($scqueryrr);
						$screquestrr->execute(array($select_allrr->main_conduite));
						$scresponserrr = $screquestrr->fetchObject();

						$scqueryrr = "SELECT school_year, COUNT(*) AS sum_pupils FROM pupils_info WHERE school_year=1";
						$screquestrr = $database_connect->query($scqueryrr);
						$scresponserr = $screquestrr->fetchObject();

						if ($mentionrr == 1) {
							$mentionrr = "Excellent";
							} else if ($mentionrr == 2) {
							$mentionrr = "Tres bien";
							} else if ($mentionrr == 3) {
							$mentionrr = "Bien";
							} else if ($mentionrr == 4) {
							$mentionrr = "Assez bien";
							} else if ($mentionrr == 5) {
							$mentionrr = "Mediocre";
							} else {
							$mentionrr = "Mauvais";
							}
							?>
							<tr>
								<td style="text-align: left; padding-left: 10px;">
									<strong><?= strtoupper($mentionrr) ?></strong>
								</td>
								<td style="text-align: center;">
									<strong><?= $scresponserrr->count_con ?></strong>
								</td>
								<td style="text-align: center;">
									<strong><?= $scresponserr->sum_pupils ?></strong>
								</td>
								<td style="text-align: center;">
									<strong><?= ($scresponserrr->count_con * 100) / $scresponserr->sum_pupils ?></strong>
								</td>
							</tr>

							<?php
			
							if(!in_array($select_allrr->main_conduite, $mentionsrr)) {
								array_push($mentionsrr, $select_allrr->main_conduite);
							}
						}

					}
					?>		
					</table><br/><br/>
					<?
				}
				?>

				<table style="border: 1px solid rgba(255, 255, 255, 0.2);">
				<caption>APERCU DES FEUILLETS DE DISCIPLINE (OBSERVATIONS)</caption>
					<tr>
						<th style="color: black; text-align: left; padding-left: 10px; width: 100px;">Mention</th>
						<th style="color: black; text-align: center; width: 100px;">Nbr élèves</th>
						<th style="color: black; text-align: center; width: 100px;">Tot élèves</th>
						<th style="color: black; text-align: center; width: 50px;">%</th>
					</tr>

					<?php
					$mmention = 0;
					$mentions = array();

					$select_all0 = "SELECT * FROM observations WHERE deleted = 0 ORDER BY mention ASC";
					$select_all1 = $database_connect->query($select_all0);
					while($select_all = $select_all1->fetchObject()) {
					$mention = $select_all->mention;

						if(!in_array($select_all->mention, $mentions)) {

						$scquery = "SELECT observation_id, COUNT(*) AS count_obs FROM observations WHERE mention=?";
						$screquest = $database_connect->prepare($scquery);
						$screquest->execute(array($select_all->mention));
						$scresponse = $screquest->fetchObject();

						$scqueryrr = "SELECT school_year, COUNT(*) AS sum_pupils FROM pupils_info WHERE school_year=1";
						$screquestrr = $database_connect->query($scqueryrr);
						$scresponserr = $screquestrr->fetchObject();

						if ($mention == 1) {
							$mention = "Excellent";
							} else if ($mention == 2) {
							$mention = "Tres bien";
							} else if ($mention == 3) {
							$mention = "Bien";
							} else if ($mention == 4) {
							$mention = "Assez bien";
							} else if ($mention == 5) {
							$mention = "Mediocre";
							} else {
							$mention = "Mauvais";
							}
							?>
							<tr>
								<td style="text-align: left; padding-left: 10px;">
									<strong><?= strtoupper($mention) ?></strong>
								</td>
								<td style="text-align: center;">
									<strong><?= $scresponse->count_obs ?></strong>
								</td>
								<td style="text-align: center;">
									<strong><?= $scresponserr->sum_pupils ?></strong>
								</td>
								<td style="text-align: center;">
									<strong><?= ($scresponse->count_obs * 100) / $scresponserr->sum_pupils ?></strong>
								</td>
							</tr>

							<?php
			
							if(!in_array($select_all->mention, $mentions)) {
								array_push($mentions, $select_all->mention);
							}
						}
					}
					?>		
					</table>
	        </div>
			<br/>
	        <div id="refresh_table_l">
	            <a id="refresh_table_lib" style="text-decoration: underline;background-color: inherit; width: 90%; font-weight: bold;" href="./?_=home.promotor">ACTUALISER LA TABLE DES APERCUS</a>
	        </div>
			
			<br/><br/>

			<div style="border-top: 1px solid rgba(255, 255, 255, 0.5);">
				<h3>LISTE DES ELEVES OBSERVES (ES)</h3>
				<span style="color: gray;">Choisissez une date ensuite lancer la recherche</span>
				<p class="separator_before_inputs">Date du jour </p>
				<input type="date" id="date_day" class="klkl class_date" />
				<button class="validate_login" id="submit_search_observations" style="width: 80%;">Lancer la recherche</button>
				<div id="observationsDay"></div>
			</div>
	    </div>
	</div>
	<br/><br/><br/>
</div>

<div class="show_third_party_c_pp">
    <?php
    if(count_school_years_exist() != 0)
    {
        $years000 = "SELECT year_id, year_name FROM school_years";
        $years111 = $database_connect->query($years000);
        while($yearsss = $years111->fetchObject())
        {
            ?>
            <a href="./?_=classes_bis&year=<?=$yearsss->year_id?>"><?= $yearsss->year_name ?></a><br/>
            <?php
        }
    }
    ?>
</div>
