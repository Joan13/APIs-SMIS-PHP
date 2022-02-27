<?php

    include '../../config/dbconnect.functions.php';

    $pupil_id = htmlspecialchars(trim(strip_tags($_POST['ppupil'])));
    $yearr = htmlspecialchars(trim(strip_tags($_POST['yyearr'])));

    $ssl0 = "SELECT pupil_id, first_name, second_name, last_name FROM pupils_info WHERE pupil_id=?";
  	$ssl1 = $database_connect->prepare($ssl0);
  	$ssl1->execute(array($pupil_id));
  	$ssl = $ssl1->fetchObject();

    $tot00 = "SELECT SUM(montant_paye) AS total_paye FROM paiements WHERE pupil_id=? AND school_year=? AND paiement_validated=1";
    $tot11 = $database_connect->prepare($tot00);
    $tot11->execute(array($pupil_id, $yearr));
    $tot = $tot11->fetchObject();

    $montant = $tot->total_paye;
    if($montant != 0)
    {
      if($montant <= 100)
      {
        if($montant == 100)
        {
          $message_soldes = "1er Trim: 0";
        }
        else
        {
          $tr1 = 100-$montant;
          $message_soldes = "1er Trim : $tr1";;
        }
      }

      if($montant > 100 && $montant <= 200)
      {
        if($montant == 200)
        {
          $message_soldes = "1er Trim : 0 / 2e Trim : 0";
        }
        else
        {
          $tr2 = 200-$montant;
          $message_soldes = "1er Trim : 0 / 2e Trim : $tr2";
        }
      }

      if($montant > 200)
      {
        if($montant == 300)
        {
          $message_soldes = "1er Trim : 0 / 2e Trim : 0 / 3e Trim : 0";
        }
        else
        {
          $tr3 = 300-$montant;
          $message_soldes = "1er Trim : 0 / 2e Trim : 0 / 3e Trim : $tr3";
        }
      }
    }
    else
    {
      $message_soldes = "100 dollars US par trimestre";
    }

?>
    <button style="display: none;" type="button" value="<?=$yearr?>" id="kkk"></button>
    <h3>Tous les paiements de l'internat de l'élève</h3>
    <?php
    $chek00 = "SELECT pupil_id, school_year, COUNT(*) AS count_paiements_internat_exist FROM boarding_school_fees WHERE pupil_id=? AND school_year=?";
    $chek11 = $database_connect->prepare($chek00);
    $chek11->execute(array($pupil_id, $yearr));
    $chek = $chek11->fetchObject();

    if($chek->count_paiements_internat_exist == 0)
    {
      echo '<h2>'."Aucun reçu enregistré pour le moment pour cet élève".'</h2>';
    }
    else
    {
      $paie00 = "SELECT * FROM boarding_school_fees WHERE pupil_id=? AND school_year=? AND paiement_validated=? ORDER BY fees_id DESC";
      $paie11 = $database_connect->prepare($paie00);
      if (session_in() == 3) {
        $paie11->execute(array($pupil_id, $yearr, 1));
      } else {
        $paie11->execute(array($pupil_id, $yearr, 1, 1));
      }
      while($paie = $paie11->fetchObject())
      {
        $solde = $paie->total_montant-$paie->montant_paye;

        $recup_libelle0 = "SELECT * FROM libelles WHERE libelle_id=?";
        $recup_libelle1 = $database_connect->prepare($recup_libelle0);
        $recup_libelle1->execute(array($paie->libelle));
        $recup_libelle = $recup_libelle1->fetchObject();

        if (session_in() == 6) {
          ?><table class="table_recus" style="border-collapse: collapse; width:550px; border-bottom: none;"><?php
        }
        else {
          ?><table class="table_recus" style="border-collapse: collapse; width:550px;"><?php
        }
    ?>
          <caption><strong><?= $school_name ?></strong></caption>
          <tr>
            <td style="padding: 5px; border:1px solid black;border-right:none;">
              <span>
                <strong><?= $school_name ?></strong><br/>
                <span><?= $email_school ?></span><br/>
                <span><?= $phone_1 ?></span><br/>
                <span><?= $phone_2 ?></span>
            </td>
            <td style="padding: 5px; text-align:left;width:200px;border:1px solid black;">
              <span>Date(JJ/MM/AA)</span><br/>
              <span><?=$paie->date_creation?></span><br/>
              <span>Reçu No <strong><?=$paie->fees_id."".date('Y')?></strong></span>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="padding: 5px; border-right: 1px solid black;">Noms de l'élève : <strong><?=strtoupper($ssl->first_name." ".$ssl->second_name)." ".$ssl->last_name?></strong></td>
          </tr>
          <tr>
            <td style="padding: 5px; ">
              Montant payé : <strong><?=$paie->montant_paye?> dollars US</strong><br/>
            </td>
            <td style="padding: 5px; ">En toutes lettres : <strong><?=$paie->montant_text?> dollars US</strong></td>
          </tr>
          <tr>
            <td style="padding: 5px; ">Montant restant total (solde) : <strong><?=$solde?> dollars US</strong></td>
            <td style="padding: 5px; ">Montant total à payer : <strong><?= $paie->total_montant." dollars US" ?></strong></td>
          </tr>
          <tr>
            <td style="padding: 5px; ">Motif du paiement : <strong><?=$recup_libelle->description_libelle?></strong></td>
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
        <?php
    		if(session_in() == 6 && $paie->paiement_validated == 1)
    		{
    			?>
          <table class="table_recus" style="border-collapse:collapse; width:550px;" style="border-top: none;">
            <tr>
              <td style="border-top: none;">
                <button class="fff validate_login" style="width: 100%;">Action</button>
                <button class="button_delete_recu" value="<?=$paie->paiement_id?>" class="validate_login_delete" style="display: none; width: 100%;">Effacer ce reçu</button>
              </td>
              <td style="text-align: right;">
                <button type="button" class="print_recu_pupil validate_login" style="width: 100%;">Imprimer ce reçu</button>
              </td>
            </tr>
          </table>
          <?php
    		}
        else{}
    		?>
        <br/><br/><br/><br/>
        <?php
      }
    }

?>
<script type="text/javascript" src="design/dynamic/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.button_delete_recu').on('click', function() {
    	var paiement_id = $(this).val();

    		$.post('pages/ajax/delete_div_ask.php', {paiement_id:paiement_id}, function(data) {
            $('#delete_div').html(data);
            $('#delete_div').fadeIn(200);
    		});
    });

    $('.fff').on('click', function() {
      $('.fff').fadeOut(-8000);
      $('.button_delete_recu').fadeIn(200);
    });
});
</script>
