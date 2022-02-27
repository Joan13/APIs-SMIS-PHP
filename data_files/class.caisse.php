<?php

	if(session_in() == 0)
	{
		Header('Location: ./?_=login');
	}

	$totalclass = 0;
	$numero = 0;

	$cycle_name = find_cycle_name($_GET['cycle']);
	$class_number = find_class_number($_GET['class_id']);
	$true_class_number = find_class_number($_GET['class_id']);
	$order_name = find_order_name($_GET['order_id']);
	$section_name = find_section_name($_GET['section_id']);
	$option_name = find_option_name($_GET['option_id']);
	$school_year = find_school_year($_GET['school_year']);

	$toUpper_class_name = strtoupper($cycle_name);

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

?>
<div class="main_middle_container">
	<h2 style="text-align: center;">Fiche des paiements de la <?=$class_identity?> / <u class="printFichePaiements" style="cursor: pointer;">Imprimer la fiche</u></h2>

	<?php
	if(session_in() == 5) {
		?><?php
	}
	?>

<div id="fichePaiements" style="text-align: center;">
	<table style="border-collapse: collapse; min-width:70%; text-align: center;" class="class_table">
		<caption><h2>Fiche des paiements : <?= strtoupper($class_identity) ?></h2></caption>
		<tr>
			<th style="text-align:left;padding-left: 5px; width: 20px;">N0</th>
			<th style="text-align:left;padding-left: 5px; width: 300px;">Nom</th>
			<th style="text-align:left;padding-left: 5px; width: 300px;">Post-nom</th>
			<th style="text-align:left;padding-left: 5px; width: 300px;">Prénom</th>
			<th style="width: 80px;">Solde trim. 1</th>
			<th style="width: 80px;">Solde trim. 2</th>
			<th style="width: 80px;">Solde trim. 3</th>
		</tr>
		<?php

		$yearr = $_GET['school_year'];

		$sell = "SELECT total_montant, school_year, paiement_validated FROM paiements WHERE school_year=? AND paiement_validated=?";
		$sellr = $database_connect->prepare($sell);
		$sellr->execute(array($yearr, 1));
		$sellrr = $sellr->fetchObject();

		$jiji = "SELECT school_year, paiement_validated, COUNT(*) AS count_paie FROM paiements WHERE school_year=? AND paiement_validated=?";
		$jijirr = $database_connect->prepare($jiji);
		$jijirr->execute(array($yearr, 1));
		$jijir = $jijirr->fetchObject();

		if($jijir->count_paie != 0) {
			$total_montantt = $sellrr->total_montant;
		}  
		else {
			$total_montantt = 0;
		}

		if(count_pupils_exist($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']) != 0)
		{
			$query_fetch00 = "SELECT * FROM pupils_info WHERE cycle_school=? AND class_school=? AND class_order=? AND class_section=? AND class_option=? AND school_year=? ORDER BY first_name ASC";
			$query_fetch11 = $database_connect->prepare($query_fetch00);

			$query_fetch11->execute(array($_GET['cycle'], $_GET['class_id'], $_GET['order_id'], $_GET['section_id'], $_GET['option_id'], $_GET['school_year']));
			while($query_fetch = $query_fetch11->fetchObject())
			{
				if($query_fetch->gender == 1)
				{
					$gender_sub = "M";
				}
				else
				{
					$gender_sub = "F";
				}

				$tot00 = "SELECT SUM(montant_paye) AS total_paye FROM paiements WHERE pupil_id=? AND school_year=? AND paiement_validated=1";
			    $tot11 = $database_connect->prepare($tot00);
			    $tot11->execute(array($query_fetch->pupil_id, $_GET['school_year']));
				$tot = $tot11->fetchObject();

				$s1 = $total_montantt/3;
				$s2 = $s1 + $s1;
				$s3 = $s2 + $s1;

				$montant = $tot->total_paye;
				$totalclass += $montant; 
				$numero = $numero + 1;
			    if($montant != 0)
			    {
			      if($montant <= $s1)
			      {
			        if($montant == $s1)
			        {
			          $message_soldes_t1 = "0K";
								$message_soldes_t2 = $s1;
								$message_soldes_t3 = $s1;
			        }
			        else
			        {
			          $tr1 = $s1-$montant;
								$message_soldes_t1 = "$tr1";
								$message_soldes_t2 = $s1;
								$message_soldes_t3 = $s1;
			        }
			      }

			      if($montant > $s1 && $montant <= $s2)
			      {
			        if($montant == $s2)
			        {
								$message_soldes_t1 = "OK";
								$message_soldes_t2 = "OK";
								$message_soldes_t3 = $s1;
			        }
			        else
			        {
			          $tr2 = $s2-$montant;
								$message_soldes_t1 = "OK";
								$message_soldes_t2 = "$tr2";
								$message_soldes_t3 = $s1;
			        }
			      }

			      if($montant > $s2)
			      {
			        if($montant == $s3)
			        {
								$message_soldes_t1 = "OK";
								$message_soldes_t2 = "OK";
								$message_soldes_t3 = "OK";
			        }
			        else
			        {
			          $tr3 = $s3-$montant;
								$message_soldes_t1 = "OK";
								$message_soldes_t2 = "OK";
								$message_soldes_t3 = "$tr3";
			        }
			      }
			    }
			    else
			    {
						$message_soldes_t1 = $s1;
						$message_soldes_t2 = $s1;
						$message_soldes_t3 = $s1;
				}

				?>
				<tr>
					<td style=" text-align: left;padding-left: 5px; width: 70px;">
						<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?= $query_fetch->pupil_id?>"><?= $numero ?></a>
					</td>
					<td style=" text-align: left;padding-left: 5px; width: 300px;">
						<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?= $query_fetch->pupil_id?>"><?= strtoupper($query_fetch->first_name)?></a>
					</td>
					<td style=" text-align: left;padding-left: 5px; width: 300px;">
						<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?= $query_fetch->pupil_id?>"><?= strtoupper($query_fetch->second_name)?></a>
					</td>
					<td style=" text-align: left;padding-left: 5px; width: 300px;">
						<a class="a_pupils" href="./?_=infos.info&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2&typeuser=1&pupil_id=<?= $query_fetch->pupil_id?>"><?= $query_fetch->last_name?></a>
					</td>
					<td style=" text-align: center;padding-left: 5px;">
						<?= $message_soldes_t1 ?>
					</td>
					<td style=" text-align: center;padding-left: 5px;">
						<?= $message_soldes_t2 ?>
					</td>
					<td style=" text-align: center;padding-left: 5px;">
						<?= $message_soldes_t3 ?>
					</td>
				</tr>
				<?php
			}
		}
		?>
	</table>
	</div>
	<table>
		<tr>
			<td><span style="display: block; text-align: center; font-size: 25px; font-weight: bold;">Total déjà payé pour cette classe : <?= $totalclass." / ".$numero*300 ?></span></td>
		</tr>
	</table>
	<br/><br/><br/><br/>
</div>

<div class="show_third_party">
	<?php

	if($toUpper_class_name == "MATERNELLE" || $toUpper_class_name == "MATERNEL")
	{
		?>
		<div class="periods">
			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=1">1ere periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2">2eme periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=3">3eme periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=4">4eme periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=5">5eme periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=6">6eme periode</a>
		</div>
		<?php
	}
	if($toUpper_class_name == "PRIMAIRE")
	{
		?>
		<div class="periods">
			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=1">1ere periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2">2eme periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=7">Examen du premier trimestre</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=3">3eme periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=4">4eme periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=8">Examen du second trimestre</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=5">5eme periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=6">6eme periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=9">Examen du 3eme trimestre</a>
		</div>
		<?php
	}
	if($toUpper_class_name == "SECONDAIRE")
	{
		?>
		<div class="periods">
			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=1">1ere periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=2">2eme periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=10">Examen du premier semestre</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=3">3eme periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=4">4eme periode</a>

			<a href="./?_=class.marksheet&cycle=<?=$_GET['cycle']?>&class_id=<?=$_GET['class_id']?>&order_id=<?=$_GET['order_id']?>&section_id=<?=$_GET['section_id']?>&option_id=<?=$_GET['option_id']?>&school_year=<?=$_GET['school_year']?>&periode=11">Examen du second semestre</a>
		</div>
		<?php
	}
	?>
</div>
