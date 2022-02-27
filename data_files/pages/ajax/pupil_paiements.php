<?php

  include '../../config/dbconnect.functions.php';
  include '../../config/class.marksheet.insert.functions.php';

  $pupil_id = htmlspecialchars(trim(strip_tags($_POST['ppupil'])));
  $yearr = htmlspecialchars(trim(strip_tags($_POST['yyearr'])));

  $ssl0 = "SELECT pupil_id, first_name, second_name, last_name FROM pupils_info WHERE pupil_id=?";
  $ssl1 = $database_connect->prepare($ssl0);
  $ssl1->execute(array($pupil_id));
  $ssl = $ssl1->fetchObject();
  
  $ssl0 = "SELECT * FROM pupils_info WHERE pupil_id=?";
  $ssl1 = $database_connect->prepare($ssl0);
  $ssl1->execute(array($pupil_id));
  $ssl = $ssl1->fetchObject();

  $cycle_name = find_cycle_name($ssl->cycle_school);
	$class_number = find_class_number($ssl->class_school);
	$true_class_number = find_class_number($ssl->class_school);
	$order_name = find_order_name($ssl->class_order);
	$section_name = find_section_name($ssl->class_section);
	$option_name = find_option_name($ssl->class_option);
	$school_year = find_school_year($ssl->school_year);

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
			if($option_name == "-")
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

    $tot00 = "SELECT SUM(montant_paye) AS total_paye FROM paiements WHERE pupil_id=? AND school_year=? AND paiement_validated=1";
    $tot11 = $database_connect->prepare($tot00);
    $tot11->execute(array($pupil_id, $yearr));
    $tot = $tot11->fetchObject();

    function solde_eleve($total_montant, $montant) {

      $message_soldes = 0;
      $montant_t1 = $total_montant/3;
      $montant_t2 = $montant_t1 + $montant_t1;
      $montant_t3 = $montant_t2 + $montant_t1;

      if($montant != 0)
      {
        if($montant <= $montant_t1)
        {
          if($montant == $montant_t1)
          {
            $message_soldes = "1er Trim: 0";
          }
          else
          {
            $tr1 = $montant_t1 - $montant;
            $message_soldes = "1er Trim : $tr1";;
          }
        }
  
        if($montant > $montant_t1 && $montant <= $montant_t2)
        {
          if($montant == $montant_t2)
          {
            $message_soldes = "1er Trim : 0 / 2e Trim : 0";
          }
          else
          {
            $tr2 = $montant_t2 - $montant;
            $message_soldes = "1er Trim : 0 / 2e Trim : $tr2";
          }
        }
  
        if($montant > $montant_t2)
        {
          if($montant == $montant_t3)
          {
            $message_soldes = "1er Trim : 0 / 2e Trim : 0 / 3e Trim : 0";
          }
          else
          {
            $tr3 = $montant_t3 - $montant;
            $message_soldes = "1er Trim : 0 / 2e Trim : 0 / 3e Trim : $tr3";
          }
        }
      }
      else
      {
        $message_soldes = "100 dollars US par trimestre";
      }

      return $message_soldes;
    }

?>

  <div style="text-align: center;">
    <div style="display: inline-block; text-align: left;">

      <button style="display: none;" type="button" value="<?=$yearr?>" id="kkk"></button>

      <h3>Tous les paiements de l'élève</h3>

      <?php

        $chek00 = "SELECT pupil_id, school_year, COUNT(*) AS count_paiements_exist FROM paiements WHERE pupil_id=? AND school_year=?";
        $chek11 = $database_connect->prepare($chek00);
        $chek11->execute(array($pupil_id, $yearr));
        $chek = $chek11->fetchObject();

        if($chek->count_paiements_exist == 0)
        {
          echo '<h2>'."Aucun reçu enregistré pour le moment pour cet élève".'</h2>';
        }

        else
        {
          $paie00 = "SELECT * FROM paiements WHERE pupil_id=? AND school_year=? AND (paiement_validated=? OR paiement_validated=?) ORDER BY paiement_id DESC";
          $paie11 = $database_connect->prepare($paie00);
          
          if (session_in() == 3) {
            $paie11->execute(array($pupil_id, $yearr, 0, 1));
          } else {
            $paie11->execute(array($pupil_id, $yearr, 1, 1));
          }

          while($paie = $paie11->fetchObject()) {

            $solde = $paie->total_montant-$tot->total_paye;

            $recup_libelle0 = "SELECT * FROM libelles WHERE libelle_id=?";
            $recup_libelle1 = $database_connect->prepare($recup_libelle0);
            $recup_libelle1->execute(array($paie->libelle));
            $recup_libelle = $recup_libelle1->fetchObject();

            ?>
            
            <div id="print-pupil-marks<?=$paie->paiement_id?>">
            <div style="border: 1px solid rgba(0, 0, 0, 0.4); padding: 3px; border-radius: 5px;">
            
            <?php

            if (session_in() == 6) {

              ?>
              
                <table class="table_recus" style="border-collapse: collapse; width:740px; border: 1px solid rgba(0, 0, 0, 0.2); border-radius: 5px;">
              
              <?php
              }

              else {
                ?>
                
                <table class="table_recus" style="border-collapse: collapse; width:; <?= ($paie->paiement_validated == 0) ? "color: red;" : "" ?>">
                
                <?php

              }

              if ($paie->paiement_validated == 0) {

                ?>

                <caption>
                  <strong style="color: red;">CE RECU A ETE EFFACE. LE MONTANT Y FIGURANT N'EST PAS CONSIDERE</strong>
                </caption>

                <?php

                }
                
                ?>

                <tr>
                  <td style="padding: 10px; text-align:left; border:1px solid rgba(0, 0, 0, 0.2);">

                      <strong style="font-size: 15px;"><?= strtoupper($school_name) ?></strong><br/>
                      <table style="border: none; border-top: 1px solid rgba(0, 0, 0, 0.2); margin-top: 10px; padding-top: 10px;">
                          <tr  style="border: 1px solid rgba(0, 0, 0, 0.2);">
                              <td style="border: none; padding-right: 25px;" rowspan="3">
                                  <span>Email</span><br/>
                                  <span>Téléphones</span><br/>
                                  <span style="color: transparent;">EliteEliteEliteEl</span>
                              </td>
                              <td style="border: 1px solid rgba(0, 0, 0, 0);">
                                  <strong><?= $email_school ?></strong><br/>
                                  <strong><?= $phone_1 ?></strong><br/>
                                  <strong><?= $phone_2 ?></strong>
                              </td>
                          </tr>
                      </table>
                  </td>
                  <td style="padding: 10px; border:1px solid rgba(0, 0, 0, 0.2); width: 300px; padding-left: 10px; border-right:none; text-align: left;">
                      ELEVE : <strong><?=strtoupper($ssl->first_name." ".$ssl->second_name)." ".$ssl->last_name?></strong><br/>
                      CLASSE : <strong><?=$class_identity?></strong><br/>
                      ANNEE-SCOLAIRE : <strong><?=$school_year?></strong><br/><br/>
                      <strong>RECU FRAIS SCOLAIRES</strong><br/>
                      <span>DATE(JJ/MM/AAAA) : 
                      <span><?=$paie->date_creation?></span><br/>
                      <span>Reçu No <strong><?=$paie->paiement_id?></strong></span>
                  </td>
                </tr>

                <tr>
                  <td colspan="2" style="padding: 10px; border-right: 1px solid rgba(0, 0, 0, 0.2);">ELEVE : <strong><?=strtoupper($ssl->first_name." ".$ssl->second_name)." ".$ssl->last_name?></strong></td>
                </tr>

                <tr>
                  <td style="padding: 10px; ">
                    Montant payé : <strong><?=$paie->montant_paye?> dollars US</strong><br/>
                  </td>
                  <td style="padding: 10px; ">En toutes lettres : <strong><?=$paie->montant_text?> dollars US</strong></td>
                </tr>

                <tr>
                  <td style="padding: 10px; ">Montant restant total (solde) : <strong><?=$solde?> dollars US</strong></td>
                  <td rowspan="2" style="padding: 10px; ">
                    Solde par trimestre : <br/><br/>
                    <strong style="font-size: 11px;"><?= solde_eleve($paie->total_montant, $tot->total_paye) ?></strong>
                  </td>

                </tr>

                <tr>
                  <td style="padding: 10px; ">Motif du paiement : <strong><?=$recup_libelle->description_libelle?></strong></td>
                  
                </tr>

                <?php

                if (session_in() == 6) {
                  ?>

                  <tr>
                    <td colspan="2" style="text-align:center;">Sceau de l'école et signature</td>
                  </tr>

                  <tr><td colspan="2"></td></tr>

                  <tr><td colspan="2"></td></tr>

                  <?php
                }
                ?>
            </table>
            </div>
          </div>

        <?php

    		if(session_in() == 6 && $paie->paiement_validated == 1) {

    		?>

          <div class="table_recus" style="width:550px; text-align: center;">
            <!-- <tr>
              <td style="border: none; border-top: none"> -->
                <button class="fff validate_login" style="width: 270px; line-height: 10px;">Action</button>
                <button class="button_delete_recu" value="<?=$paie->paiement_id?>" class="validate_login_delete" style="display: none; width: 270px; line-height: 10px;">Effacer ce reçu</button>
                <button style="display: none;" value="<?= $pupil_id ?>" id="ppupil"></button>
                <button style="display: none;" value="<?= $yearr ?>" id="yearr"></button>
              <!-- </td>
              <td style="text-align: right; border: none; border-top: none"> -->
                <button type="button" class="print_recu_pupil validate_login" value="<?=$paie->paiement_id?>" style="width:270px; line-height: 10px;">Imprimer ce reçu</button>
              <!-- </td>
            </tr> -->
          </div>

          <?php
    		}
        else{}

    		?>

        <br/><br/><br/><br/>
        <?php
      }
    }

  ?>
  </div>
</div>

<script type="text/javascript" src="design/dynamic/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
      $('.button_delete_recu').on('click', function() {
        let paiement_id = $(this).val();

          $.post('pages/ajax/delete_div_ask.php', {paiement_id:paiement_id}, function(data) {
              $('#delete_div').html(data);
              $('.delete_div_global').fadeIn(400);
          });
      });

      $('.fff').on('click', function() {
        $('.fff').fadeOut(-8000);
        $('.button_delete_recu').fadeIn(400);
      });

      function printContent(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        
        window.print();
        
        document.body.innerHTML = originalContents;
      }

      $('.print_recu_pupil').on('click', function() {
          let paiement_id = $(this).val();
          printContent("print-pupil-marks" + paiement_id);

          $('.outCaisse').fadeOut(400);
          $('.inCaisse').fadeIn(400);
      });
  });
</script>
