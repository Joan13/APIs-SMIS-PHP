<?php

	if(session_in() == 0)
	{
		Header('Location: index.php?_=login');
	}

?>

	<div class="main_middle_container">
		<div style="padding-left: 15px;">
			<div class="divv_info">
				<span style="text-decoration: underline;">Cliquez sur un item du menu pour commencer</span><br/><br/>
				<li>Gérez les informations en rapport avec vos élèves</li><br/>
				<li>Cliquez sur "LES CLASSES" pour consulter les informations<br/> sur la classe dont vous etes titulaire</li><br/>
				<li>Ensuite, cliquez sur "ENTRER LES POINTS" pour enregistrer les cotes</li><br/>
				<li>Allez dans les paramètres pour regler vos configurqtions de compte</li><br/>
			</div>
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
