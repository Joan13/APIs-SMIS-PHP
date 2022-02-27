<?php

	include '../../config/dbconnect.functions.php';
	include '../../config/home.livre_caisse.functions.php';

	$pupil_id = $_POST['pupil_id'];
	$yearr = $_POST['yearr'];
	$pupil_pupil = $_POST['pupil_pupil'];

	$ssl0 = "SELECT pupil_id, first_name, second_name, last_name FROM pupils_info WHERE pupil_id=?";
	$ssl1 = $database_connect->prepare($ssl0);
	$ssl1->execute(array($pupil_id));
	$ssl = $ssl1->fetchObject();

	?>
	<button value="<?=$pupil_id?>" style="display:none;" id="ididid"></button>
	<button value="<?=$yearr?>" style="display:none;" id="yyy"></button>
	<button value="2" style="display:none;" id="ggender"></button>
	<div id="okokok"></div>

	<style>
	
	.inCaisse {
		margin-top: 180px;
		font-size: 25px;
		margin-bottom: 50px;
		display: none;
	}

	</style>

	<div class="main_mm outCaisse">
		<img class="close_div_pupil_caisse" src="images/other/close_2.png" width="15" height="15" />
		<h2><?=strtoupper($ssl->first_name." ".$ssl->second_name)." ".$ssl->last_name?></h2>
		<?php
		if(session_in() == 2) {
			?>
			<!-- <button type="button" value="<?=$pupil_pupil?>" class="validate_login" id="delete_permanently_pupil">Supprimer cet l'élève</button> -->
			<button type="button" value="<?=$pupil_id?>" class="validate_login" id="button_new_input">Enregistrer une observation</button>
			<?php
		}

		if(session_in() == 1) {
			?>
			<!-- <button type="button" value="<?=$pupil_pupil?>" class="validate_login" id="delete_permanently_pupil">Supprimer cet l'élève</button>
			<button type="button" value="<?=$pupil_id?>" class="validate_login" id="button_new_input_internat">Paiement de l'internat</button> -->
      <!-- <button type="button" value="<?=$pupil_id?>" class="validate_login" id="voir_tous_les_recus">Reçu de l'élève</button> -->
      <button type="button" value="<?=$pupil_id?>" class="validate_login" id="frais_divers_button">Frais divers de l'élève</button>
			<?php
		}

		?>
		<button type="button" value="<?=$pupil_id?>" class="validate_login" id="voir_observations">Observations de l'élève</button>
		<?php

		if(session_in() == 3) {
		    $chek00 = "SELECT pupil_id, school_year, COUNT(*) AS count_paiements_internat_exist FROM boarding_school_fees WHERE pupil_id=? AND school_year=?";
		    $chek11 = $database_connect->prepare($chek00);
		    $chek11->execute(array($pupil_id, $yearr));
		    $chek = $chek11->fetchObject();

		    if($chek->count_paiements_internat_exist != 0) {
		    	?><button type="button" value="<?=$pupil_id?>" class="validate_login" id="voir_tous_les_recus_internat">Reçus de l'internat</button><br/><br/><?php
		    }
		}

		if(session_in() == 2) {
			?>
			<div id="div_new_recu" style="text-align:center;">
				<table style="width: 70%; margin-left: 15%; border: none">
					<tr>
						<td  style="border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 10px;">
							<div style="display: inline-block; text-align: left;">
								<br/><strong>Noms de l'élève : </strong><br/><h3 style="text-decoration:underline;"><?=strtoupper($ssl->first_name." ".$ssl->second_name)." ".$ssl->last_name?></h3>
								<strong>Observation disciplinaire : </strong><br/><textarea style="height: 150px; border: 1px solid rgba(0, 0, 0, 0.2); font-family:'Arial';" id="input_observation" class="innput" placeholder="Entrez votre observation ici"></textarea><br/>
								<!-- <strong>En toutes lettres : </strong><br/><input type="text" class="innput" id="input_montant_text" placeholder="Ex : Cent vingt dollars US"><br/>  -->
								<!-- <br/> -->
                <strong style="display: inline-block; max-width: 350px; color: gray;">
                  Entrez la note d'observation par rapport au comportement de cet élève. Cette note sera visible par vos supérieurs et par l'élève concerné (e) dans l'espace réservé aux élèves.
                </strong>
                <br/>
                <br/>
								<!-- <strong>Montant total et année scolaire : </strong><br/><input type="number" id="total_montant" class="ssss innput" placeholder="Ex : 540"> -->
								
								<!-- <br/><button type="button" value="<?=$pupil_id?>" id="validate_paiement" class="validate_login">Valider le paiement</button><br/><br/> -->
							</div>
						</td>
						<td  style="border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 10px;">
							<div style="display: inline-block; text-align: left;">
								<!-- <br/><strong>INFORMATIONS BASIQUES SUR L'OBSERVATION DE CET (TE) ELEVE </strong></h3><br/><br/> -->
                					<strong>Mention de l'observation : </strong><br/>
								<select class="innput_tl" id="mention"  style="width : 100%">
								<option value=""> -- Choisir mention -- </option>
								<option value="1"> Excellent </option>
								<option value="2"> Très bien </option>
								<option value="3"> Bien </option>
								<option value="4"> Assez bien </option>
								<option value="5"> Médiocre </option>
								<option value="6"> Mauvais </option>
							</select><br/><br/>
							<strong>Date de l'observation : </strong><br/>
							<input type="date" id="date_observation" class="klkl class_date" />

              <br/><br/>
								
								<br/><button type="button" value="<?=$pupil_id?>" id="validate_observation" class="validate_login">Enregistrer la note</button><br/><br/>
							</div>
						</td>
					</tr>
				</table>
			</div>
		<?php
		}

		if (session_in() == 3) {
			?>
			<div id="div_new_recu_internat" style="text-align: center;">
				<div style="display: inline-block; text-align: left;">
					<br/><strong>Noms de l'élève : </strong><br/><h3 style="text-decoration:underline;"><?=strtoupper($ssl->first_name." ".$ssl->second_name)." ".$ssl->last_name?></h3>
					<strong>Montant payé en dollars : </strong><br/><input type="number" id="input_montant_internat" class="innput" placeholder="Ex : 120"><br/>

					<strong>En toutes lettres : </strong><br/><input type="text" class="innput" id="input_montant_text_internat" placeholder="Ex : Cent vingt dollars US"><br/>

					<strong>Raison du paiement : </strong><br/>
					<select class="innput_ss" id="selectionner_le_libelle_internat">
						<option value="">Sélectionner un libéllé</option>
						<?php
						$select_all011 = "SELECT * FROM libelles WHERE gender_libelle=0";
						$select_all111 = $database_connect->query($select_all011);
						while($select_all11 = $select_all111->fetchObject()) {
						?>
							<option value="<?= $select_all11->libelle_id ?>" class="option_libelle">
							<?= $select_all11->description_libelle ?></option>
						<?php
						}
						?>
					</select><br/>

					<strong>Montant total et année scolaire : </strong><br/><input type="number" id="total_montant_internat" class="ssss innput" placeholder="Ex : 300">
					<select class="ssss selects select_pupil_cycle" id="school_year_input_internat">
						<option value="" class="login_item"> -- Année scolaire -- </option>

						<?php
							$years00 = "SELECT year_id, year_name FROM school_years";
							$years11 = $database_connect->query($years00);
							while($yearss = $years11->fetchObject())
							{
								?>
								<option value="<?= $yearss->year_id ?>" class="login_item"><?= $yearss->year_name ?></option>
								<?php
							}
						?>
					</select>
					<br/><button type="button" value="<?=$pupil_id?>" id="validate_paiement_internat" class="validate_login">Valider le paiement</button>
				</div>
			</div><br/><br/>
			<?php
		}
		?>

    		<div id="les_observations" style="text-align:center;"></div>
		<div id="tous_les_recus" style="text-align:center;"></div>
		<div id="frais_divers" style="text-align:center; display: none;">

			<h3>Entrer de nouveaux frais divers</h3>

			<strong>Sélectionner un motif de paiement et entrer le montant payé</strong><br/>
			<select class="innput_ss" id="libelle_frais_divers">
				<option value="">Sélectionner un motif de paiement</option>
				<?php
				$select_all01100 = "SELECT * FROM libelles WHERE gender_libelle=1";
				$select_all11100 = $database_connect->query($select_all01100);
				while($select_all1100 = $select_all11100->fetchObject())
				{
				?>
					<option value="<?= $select_all1100->libelle_id ?>" class="option_libelle">
					<?= $select_all1100->description_libelle ?></option>
				<?php
				}
				?>
			</select>
			<input type="number" id="montant_frais_divers" class="ssss innput" placeholder="Ex : 10">
			<br/><button type="button" value="<?=$pupil_id?>" id="validate_frais_divers" class="validate_login">Valider le paiement</button>

			<div>
				
				<?php
				
				$chek0000 = "SELECT pupil_id, school_year, deleted, COUNT(*) AS count_frais_divers_exist FROM frais_divers WHERE pupil_id=? AND school_year=? AND deleted=?";
				$chek1100 = $database_connect->prepare($chek0000);
				$chek1100->execute(array($pupil_id, $yearr, 0));
				$chek00 = $chek1100->fetchObject();

				if($chek00->count_frais_divers_exist == 0)
				{
					echo '<h2>'."Aucun frais divers enregistré pour le moment pour cet élève".'</h2>';
				}

				else
				{			
					?>

					<div id="tous_frais_divers" style="display: none;"></div>
					
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<div class="inCaisse">
		<a href="./?_=home.livre_caisse" class="inCaisse">REACTUALISER LE LIVRE DE DISCIPLINE</a>
	</div>
	

<script type="text/javascript" src="design/dynamic/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		function frais_divers() {

			let pupil_id = $('#ididid').val();
			let school_year = $('#yyy').val();

			$.post('pages/ajax/frais_divers.php', { pupil_id:pupil_id, school_year:school_year }, function(data) {
				$('#tous_frais_divers').html(data);
				$('#tous_frais_divers').fadeIn(200);
			});
		}

		$('#validate_reset').on('click', function() {
			let total_montant_reset = $('#total_montant_reset').val();
			let school_year_reset = $('#school_year_reset').val();

			if(total_montant_reset == '' || school_year_reset == '')
			{
				$('#information-div-global').fadeIn(400);
			}
			else
			{
				$.post('pages/ajax/reset_total_paiement.php', {total_montant_reset:total_montant_reset, school_year_reset:school_year_reset}, function() {
					$('#success-information-div-global').fadeIn(400);
				});
			}
		});

		$('#validate_paiement_internat').on('click', function() {
			let pupil_paiement = $('#ididid').val();
			let input_montant_internat = $('#input_montant_internat').val();
			let input_montant_text_internat = $('#input_montant_text_internat').val();
			let selectionner_le_libelle_internat = $('#selectionner_le_libelle_internat').val();
			let total_montant_internat = $('#total_montant_internat').val();
			let school_year_input_internat = $('#school_year_input_internat').val();
			let ggender = $('#ggender').val();

			if(input_montant_internat == '' || input_montant_text_internat == '' || selectionner_le_libelle_internat == '' || total_montant_internat == '' || school_year_input_internat == '' || ggender == '')
			{
				$('#information-div-global').fadeIn(400);
			}
			else
			{
				$.post('pages/ajax/new_recu_internat.php', {pupil_paiement:pupil_paiement, input_montant_internat:input_montant_internat, input_montant_text_internat:input_montant_text_internat, selectionner_le_libelle_internat:selectionner_le_libelle_internat, total_montant_internat:total_montant_internat, school_year_input_internat:school_year_input_internat, ggender:ggender}, function(data) {
					//alert("Le reçu a été enregistré avec succès");
					$('#success-information-div-global').fadeIn(400);
				});
			}
		});

		$('#validate_paiement').on('click', function() {
			let pupil_paiement = $('#ididid').val();
			let input_montant = $('#input_montant').val();
			let input_montant_text = $('#input_montant_text').val();
			let selectionner_le_libelle = $('#selectionner_le_libelle').val();
			let total_montant = $('#total_montant').val();
			let school_year_input = $('#school_year_input').val();
			let ggender = $('#ggender').val();

			if(input_montant == '' || input_montant_text == '' || selectionner_le_libelle == '' || total_montant == '' || school_year_input == '' || ggender == '')
			{
				$('#information-div-global').fadeIn(400);
			}
			else
			{
				$.post('pages/ajax/input_recu.php', {pupil_paiement:pupil_paiement, input_montant:input_montant, input_montant_text:input_montant_text, selectionner_le_libelle:selectionner_le_libelle, total_montant:total_montant, school_year_input:school_year_input, ggender:ggender}, function(data) {
					//alert("Le reçu a été enregistré avec succès");
					$('#success-information-div-global').fadeIn(400);
				});
			}
    });
    
    $('#validate_observation').on('click', function() {
			let pupil_id = $('#ididid').val();
      let input_observation = $('#input_observation').val();
      let date_observation = $('#date_observation').val();
			let mention = $('#mention').val();
			let school_year = $('#yyy').val();

			if(input_observation == '' || mention == '' || date_observation == '')
			{
				$('#information-div-global').fadeIn(400);
			}
			else
			{
				$.post('pages/ajax/input_observation.php', {pupil_id:pupil_id, input_observation:input_observation, date_observation:date_observation, mention:mention, school_year:school_year}, function(data) {

					$('#input_observation').val('');
					$('#date_observation').val('');
					$('#mention').val('');
          
					$('#success-information-div-global').fadeIn(400);
				});
			}
		});

		$('#validate_frais_divers').on('click', function() {
			let pupil_id = $('#ididid').val();
			let montant_frais_divers = $('#montant_frais_divers').val();
			let school_year = $('#yyy').val();
			let libelle_frais_divers = $('#libelle_frais_divers').val();

			if(pupil_id == '' || montant_frais_divers == '' || libelle_frais_divers == '' || school_year == '')
			{
				$('#information-div-global').fadeIn(400);
			}
			else
			{
				$.post('pages/ajax/new_frais_divers.php', { pupil_id:pupil_id, montant_frais_divers:montant_frais_divers, school_year:school_year, libelle_frais_divers:libelle_frais_divers }, function(data) {
					
					$('#success-information-div-global').fadeIn(400);
				});

				$.post('pages/ajax/frais_divers.php', { pupil_id:pupil_id, school_year:school_year }, function(data) {

					// $('#tous_frais_divers').html(data);
					// $('#tous_frais_divers').fadeIn(200);

					frais_divers();
				});
			}
		});


		$('.close_div_pupil_caisse').on('click', function() {
			$('#view_view_caisse').fadeOut(400);
		});

		$('#delete_permanently_pupil').on('click', function() {
			let pupil_pupil = $(this).val();

			$.post('pages/ajax/delete_div_ask.php', { pupil_pupil:pupil_pupil }, function(data) {
				$('#delete_div').html(data);
				$('.delete_div_global').fadeIn(400);
			});
		})

		$('#voir_tous_les_recus').on('click', function() {
			let ppupil = $('#ididid').val();
			let yyearr = $('#yyy').val();

			if(ppupil != '' && yyearr != '')
			{
				$.post('pages/ajax/pupil_paiements.php', {ppupil:ppupil, yyearr:yyearr}, function(data) {
					$('#div_new_recu').fadeOut(-2200);
					$('#frais_divers').fadeOut(200);
					$('#tous_les_recus').html(data);
					$('#tous_les_recus').fadeIn(200);
					$('#les_observations').fadeOut(200);
					$('#div_new_recu_internat').fadeOut(200);
				});
			}
    });
    
    $('#voir_observations').on('click', function() {
			let ppupil = $('#ididid').val();
			let yyearr = $('#yyy').val();

			if(ppupil != '' && yyearr != '')
			{
				$.post('pages/ajax/pupil_observations.php', {ppupil:ppupil, yyearr:yyearr}, function(data) {
					$('#div_new_recu').fadeOut(-2200);
					$('#frais_divers').fadeOut(200);
					$('#les_observations').html(data);
          $('#tous_les_recus').fadeOut(200);
          $('#les_observations').fadeIn(200);
					$('#div_new_recu_internat').fadeOut(200);
				});
			}
		});

		$('#voir_tous_les_recus_internat').on('click', function() {
			let ppupil = $('#ididid').val();
			let yyearr = $('#yyy').val();

			if(ppupil != '' && yyearr != '')
			{
				$.post('pages/ajax/pupil_paiements_internat.php', {ppupil:ppupil, yyearr:yyearr}, function(data) {
					$('#div_new_recu').fadeOut(-2200);
					$('#frais_divers').fadeOut(200);
					$('#tous_les_recus').html(data);
          $('#tous_les_recus').fadeIn(200);
          $('#les_observations').fadeOut(200);
					$('#div_new_recu_internat').fadeOut(200);
				});
			}
		});

		$('#button_new_input').on('click', function() {
			$('#tous_les_recus').fadeOut(-2200);
			$('#div_new_recu').fadeIn(200);
      $('#div_new_recu_internat').fadeOut(200);
      $('#les_observations').fadeOut(200);
			$('#frais_divers').fadeOut(200);
		});

		$('#button_new_input_internat').on('click', function() {
			$('#tous_les_recus').fadeOut(-2200);
			$('#div_new_recu').fadeOut(200);
			$('#div_new_recu_internat').fadeIn(200);
			$('#frais_divers').fadeOut(200);
		});

		$('#frais_divers_button').on('click', function() {
			$('#tous_les_recus').fadeOut(-2200);
			$('#div_new_recu').fadeOut(-2200);
			$('#div_new_recu_internat').fadeOut(-2200);
			$('#frais_divers').fadeIn(200);

			frais_divers();
		});
	});
</script>
