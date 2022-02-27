<?php

	if(session_in() == 0)
	{
		Header('Location: ./?_=login');
	}

	if(session_in() == 3 && $page != 'home.finance') {
		Header('Location: ./?_=home.finance');
	} else if(session_in() == 3 && $page == 'home.finance') {
		
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

<div class="show_third_party_caisse" style="text-align: left; color: black;">
	<div style="color: white; padding-left: 25px;" id="left_div">
	    <p class="title_libs" style=" color: black;">SAUVEGARDE DES LIBELLES</p>

	    <div class="here_now">
	        <label for="libelle" id="libb" style=" color: black;">Libéllé</label><br/>
	        <input type="text" id="input_du_libelle" class="innput" placeholder="Ex: Paiement deuxième trimestre" /><br/>
			<button style="display:none;" value="0" id="gender_libelle"></button>
	        <button class="validate_login" id="submit_libelle" style="width: 80%;">Enregister le libéllé</button>
	    </div><br/><br/>

	    <div class="liste_libelle">
	        <p class="title_libs" style=" color: black;">TABLE DES LIBELLES</p>
	        <div class="all_libelles_entries">

	        	<?php
	            $select_all0 = "SELECT * FROM libelles WHERE gender_libelle=0";
	            $select_all1 = $database_connect->query($select_all0);
				while($select_all = $select_all1->fetchObject())
				{
	            ?>
	                <div class="all_libs">
	                    <li style="margin-left: 20px; margin-bottom: 7px; color: black;"><strong><?= $select_all->description_libelle ?></strong></li>
	                </div>
	            <?php
	          	}
			      ?>
	        </div><br/>
	        <div id="refresh_table_l">
	            <a id="refresh_table_lib" style="text-decoration: underline;background-color: inherit; width: 90%; color: black;" href="./?_=home.finance">ACTUALISER LA TABLE DES LIBELLES</a>
	        </div><br/><br/>
	    </div>
	</div>
	<br/><br/><br/>
</div>

<div class="show_third_party_c">
	<?php
	if(count_school_years_exist() != 0)
	{
		$years000 = "SELECT year_id, year_name FROM school_years";
		$years111 = $database_connect->query($years000);
		while($yearsss = $years111->fetchObject())
		{
			?>
			<a href="./?_=classes&year=<?=$yearsss->year_id?>"><?= $yearsss->year_name ?></a><br/>
			<?php
		}
	}
	?>
</div>
