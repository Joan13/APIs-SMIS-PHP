<?php

    include '../../config/dbconnect.functions.php';

    $day_c = date('d');
    $month_c = date('m');
    $year_c = date('Y');
    $date_creation = $day_c."/".$month_c."/".$year_c;

    $mm00 = "SELECT SUM(montant_paye) AS total_des_montants_payes FROM paiements WHERE paiement_validated=1 AND date_creation="."'$date_creation'";
    $mm11 = $database_connect->query($mm00);
    $mm = $mm11->fetchObject();
    $mmontant = $mm->total_des_montants_payes;

    if($mmontant == 0)
    {
        $mmontant = "0 dollar US";
    }
    else
    {
        $mmontant = $mm->total_des_montants_payes." dollars US";
    }

    $montant_t1 = 0;
    $montant_t2 = 0;
    $montant_t3 = 0;

    ?>
    
    <br/><br/><br/><br/>

    <strong style="font-size:15px;">Le montant total de la journée est de </strong><br/><br/><br/>
    <span style="font-weight:bold;font-size:50px;color:#0e2142;"><?=$mmontant?></span>

    <?php

    if (isset($_POST['stat_year'])) {

      $school_year = $_POST['stat_year'];

      $pupilsNumberQuery = "SELECT pupil_id, school_year, COUNT(*) AS count_pupils FROM pupils_info WHERE school_year=?";
      $pupilsNumberRequest = $database_connect->prepare($pupilsNumberQuery);
      $pupilsNumberRequest->execute(array($school_year));
      $pupilsNumberResponse = $pupilsNumberRequest->fetchObject();

      $pupilsPaiementQuery = "SELECT pupil_id, school_year, paiement_validated, COUNT(*) AS count_pupils_paiements FROM paiements WHERE school_year=? AND paiement_validated=?";
      $pupilsPaiementRequest = $database_connect->prepare($pupilsPaiementQuery);
      $pupilsPaiementRequest->execute(array($school_year, 1));
      $pupilsPaiementResponse = $pupilsPaiementRequest->fetchObject();

      if ($pupilsPaiementResponse->count_pupils_paiements != 0) {
        $selectPaiementsQuery = "SELECT SUM(montant_paye) AS totall FROM paiements WHERE school_year=? AND paiement_validated=?";
        $selectPaiementsRequest = $database_connect->prepare($selectPaiementsQuery);
        $selectPaiementsRequest->execute(array($school_year, 1));
        $selectPaiementsResponse = $selectPaiementsRequest->fetchObject();

        $queryDetectTotal = "SELECT school_year, total_montant FROM paiements WHERE school_year=? ORDER BY paiement_id DESC LIMIT 1";
        $requestDetectTotal = $database_connect->prepare($queryDetectTotal);
        $requestDetectTotal->execute(array($school_year));
        $responseDetectTotal = $requestDetectTotal->fetchObject();

        $montant_t1 = $responseDetectTotal->total_montant/3;
        $montant_t2 = $montant_t1 + $montant_t1;
        $montant_t3 = $montant_t2 + $montant_t1;

        $paiements = $selectPaiementsResponse->totall;

        if ($paiements <= $pupilsNumberResponse->count_pupils*$montant_t1) {

            $s1 = $paiements; // ($pupilsNumberResponse->count_pupils*300)-($pupilsNumberResponse->count_pupils*200);
            $s2 = 0;
            $s3 = 0;
          
        } 

       if ($paiements > $pupilsNumberResponse->count_pupils*$montant_t1 && $paiements <= $pupilsNumberResponse->count_pupils*$montant_t2) {
          $s1 = $pupilsNumberResponse->count_pupils*$montant_t1;
          $s2 = $paiements-$s1;
          $s3 = 0;
        }

       if ($paiements > $pupilsNumberResponse->count_pupils*$montant_t2 && $paiements <= $pupilsNumberResponse->count_pupils*$montant_t3) {
          
          $s1 = $pupilsNumberResponse->count_pupils*$montant_t1;
          $sminus = $pupilsNumberResponse->count_pupils*$montant_t2;
          $s2 = $s1;
          $s3 = $paiements-$sminus;

        } 


      } else {

        $paiements = "0";
        $s1 = 0;
        $s2 = 0;
        $s3 = 0;

      }

      ?>
      <div class="divSubAll">
      <br/><br/><br/>

      <table style="display: inline-block;">
        <caption><h4>TABLEAU DES STATISTIQUES DE L'ANNEE</h4></caption>
        <tr>
          <th>Montant entré (montant payé)</th>
          <th>Montant restant (solde total)</th>
          <th>Montant total</th>
        </tr>

        <tr>
          <td style="padding-left: 60px; padding-right: 60px;">
            <span style="font-weight:bold;font-size:50px;color:#0e2142;"><?= $paiements ?></span>
          </td>
          <td style="padding-left: 60px; padding-right: 60px;">
            <span style="font-weight:bold;font-size:50px;color:#0e2142;"><?= ($pupilsNumberResponse->count_pupils*$montant_t3) - $paiements ?></span>
          </td>
          <td style="padding-left: 60px; padding-right: 60px;">
            <span style="font-weight:bold;font-size:50px;color:#0e2142;"><?= $pupilsNumberResponse->count_pupils*$montant_t3 ?></span>
          </td>
        </tr>
        <tr>
          <th>Stats 1T</th>
          <th>Stats 2T</th>
          <th>Stats 3T</th>
        </tr>
        <tr>
          <td style="padding-left: 60px; padding-right: 60px;">
            <span style="font-weight:bold;font-size:50px;color:#0e2142;"><?= $s1."/".($pupilsNumberResponse->count_pupils*$montant_t1) ?></span>
          </td>
          <td style="padding-left: 60px; padding-right: 60px;">
            <span style="font-weight:bold;font-size:50px;color:#0e2142;"><?= $s2."/".($pupilsNumberResponse->count_pupils*$montant_t1) ?></span>
          </td>
          <td style="padding-left: 60px; padding-right: 60px;">
            <span style="font-weight:bold;font-size:50px;color:#0e2142;"><?= $s3."/".($pupilsNumberResponse->count_pupils*$montant_t1) ?></span>
          </td>
        </tr>

      </table><br/>
      
      <?php

      $countFraisDivers = "SELECT school_year, COUNT(*) AS count_frais_divers FROM frais_divers WHERE school_year=? AND deleted=?";
      $requestCountFraisDivers = $database_connect->prepare($countFraisDivers);
      $requestCountFraisDivers->execute(array($school_year, 0));
      $responseCountFraisDivers = $requestCountFraisDivers->fetchObject();

      if($responseCountFraisDivers->count_frais_divers != 0) {

        $recup_annee0 = "SELECT * FROM school_years WHERE year_id=?";
        $recup_annee1 = $database_connect->prepare($recup_annee0);
        $recup_annee1->execute(array($school_year));
        $recup_annee = $recup_annee1->fetchObject();
        $annee_stat = $recup_annee->year_name;

        ?>
        <div style="margin-top: 30px; margin-bottom: 30px; border: rgba(0, 0, 0, 0.1);">
        <div id="frais_divers_div_stats">
        <table style="display: inline-block; border-collapse: collapse;">
          <caption><h4>TABLEAU GENERAL DES STATISTIQUES ANNUELLES DES FRAIS DIVERS POUR L'ANNEE SCOLAIRE <?= $annee_stat ?></h4></caption>
          <tr style="border: 1px solid rgba(0, 0, 0, 0.2);">
            <th style="width: 170px; text-align: center;">Date</th>
            <th style="width: 600px;">Raison du paiement</th>
            <th style="width: 170px; text-align: center;">Montant</th>
            <!-- <th style="width: 150px; text-align: center;">Année scolaire</th> -->
          </tr>
        <?php

        $libelle_id = 0;
        $libelles = array();

        $querySelectFraisDiverssTotal = "SELECT SUM(montant) AS totall FROM frais_divers WHERE school_year=? AND deleted=?";
        $requestSelectFraisDiverssTotal = $database_connect->prepare($querySelectFraisDiverssTotal);
        $requestSelectFraisDiverssTotal->execute(array($school_year, 0));
        $responseSelectFraisDiverssTotal = $requestSelectFraisDiverssTotal->fetchObject();

        $querySelectFraisDiverss = "SELECT * FROM frais_divers WHERE school_year=? AND deleted=?";
        $requestSelectFraisDiverss = $database_connect->prepare($querySelectFraisDiverss);
        $requestSelectFraisDiverss->execute(array($school_year, 0));
        while($responseSelectFraisDiverss = $requestSelectFraisDiverss->fetchObject()) {

          if(!in_array($responseSelectFraisDiverss->libelle, $libelles)) {
            $libelle_id = $responseSelectFraisDiverss->libelle;

            $libQ = "SELECT * FROM libelles WHERE libelle_id=?";
            $libReq = $database_connect->prepare($libQ);
            $libReq->execute(array($responseSelectFraisDiverss->libelle));
            $libRes = $libReq->fetchObject();

            $querySelectFraisDiverssTotall = "SELECT SUM(montant) AS totall FROM frais_divers WHERE libelle=? AND school_year=? AND deleted=?";
            $requestSelectFraisDiverssTotall = $database_connect->prepare($querySelectFraisDiverssTotall);
            $requestSelectFraisDiverssTotall->execute(array($responseSelectFraisDiverss->libelle, $school_year, 0));
            $responseSelectFraisDiverssTotall = $requestSelectFraisDiverssTotall->fetchObject();

            ?>
            <tr>
              <td style="border: 1px solid rgba(0, 0, 0, 0.2); text-align: center;"><?= $responseSelectFraisDiverss->date_entry ?></td>
              <td style="border: 1px solid rgba(0, 0, 0, 0.2); text-align: left; padding-left: 15px;"><?= $libRes->description_libelle ?></td>
              <td style="border: 1px solid rgba(0, 0, 0, 0.2); text-align: center;"><?= $responseSelectFraisDiverssTotall->totall." USD" ?></td>
              <td style="display: none;" class="edit_td"><button class="button_delete_recu" value="<?= $responseSelectFraisDiverss->frais_divers_id ?>">Effacer cette entrée</button></td>
            </tr>
          <?php

            if(!in_array($responseSelectFraisDiverss->libelle, $libelles)) {
              array_push($libelles, $responseSelectFraisDiverss->libelle);
            }
          }
        }
        ?></table></div>
        <button class="validate_login" style="" id="print_frais_divers_stats">
          Imprimer la fiche des statistiques des frais divers
        </button>
        </div><?php
      }
      ?>
      </div>
      <?php
    }

    ?>

    <div style="display: none; margin-top: 150px;" class="linkRefresh">
      <a style="margin-top: 200px; font-size: 25px;" href="index.php?_=home.livre_caisse">REACTUALISER LE LIVRE DE CAISSE</a>
    </div>


<script type="text/javascript" src="design/dynamic/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#see_other_depenses').on('click', function() {
    	$('#autres_depenses').fadeToggle(200);
    });

    $('.delete_depense').on('click', function() {
      let depense_id = $(this).val();

      $.post('pages/ajax/delete_div_ask.php', {depense_id:depense_id}, function(data) {
        $('#delete_div').html(data);
        $('.delete_div_global').fadeIn(100);
      });
    });

  function printContent(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		
		window.print();
		
		document.body.innerHTML = originalContents;
	}

	$('#print_today_depenses').on('click', () => {
    printContent("all_depenses_table");
    $('.divSubAll').fadeOut(400);
    $('.linkRefresh').fadeIn(400);
  });
  
  $('#print_frais_divers_stats').on('click', () => {
    printContent("frais_divers_div_stats");
    // alert("ok");
    $('.divSubAll').fadeOut(400);
    $('.linkRefresh').fadeIn(400);
	});
});
</script>
