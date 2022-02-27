<?php

    include '../../config/dbconnect.functions.php';
    $day = date('d');
    $month = date('m');
    $year = date('Y');
    $date = $day."/".$month."/".$year;
    $depense_validated = 1;
?>

<div class="divSubAll">
  <br/>

  <div id="all_depenses_table">
  <table id="all_depenses_tablee" style="border-collapse: collapse;margin-left: 1%;width: 98%;">
    <caption>DEPENSES DU DE LA JOURNEE DU <?=$date?></caption>
    <tr style="border: 1px solid black;">
      <th style="border: 1px solid black; width: 100px;">Coût<br/>unitaire</th>
      <th style="border: 1px solid black; width: 100px;">Nombre d'<br/>exécution</th>
      <th style="border: 1px solid black;">Coût total</th>
      <th style="border: 1px solid black;">Raison de la dépense</th>
      <th style="border: 1px solid black;">Date(JJ/MM/AA)</th>
      <th style="border: 1px solid black;">Heure(HH/mm)</th>
      <?php if (session_in() == 3 || session_in() == 6) { ?><th>^</th><?php } ?>
    </tr>
    <?php
    
    $sel00 = "SELECT * FROM depenses WHERE depense_validated=1 AND date_creation="."'$date'";
    $sel11 = $database_connect->query($sel00);
    while($sel = $sel11->fetchObject())
    {
      $cout_unitaire = $sel->cout_depense;
      $nombre = $sel->number_depense;
      $cout_total = $nombre*$cout_unitaire;

      $recup_libelle0 = "SELECT * FROM libelles WHERE libelle_id=?";
      $recup_libelle1 = $database_connect->prepare($recup_libelle0);
      $recup_libelle1->execute(array($sel->libelle));
      $recup_libelle = $recup_libelle1->fetchObject();

      ?>
      <tr style="border: 1px solid black;">
        <td style="border: 1px solid black; width: 100px;"><?=$cout_unitaire?> USD</td>
        <td style="border: 1px solid black; width: 100px;"><?=$nombre?></td>
        <td style="border: 1px solid black; width: 100px;"><?=$cout_total?> USD</td>
        <td style="border: 1px solid black; width: 500px;"><?=$recup_libelle->description_libelle?></td>
        <td style="border: 1px solid black; width: 100px;"><?=$sel->date_creation?></td>
        <td style="border: 1px solid black; width: 100px;"><?=$sel->heure_depense?></td>
        <?php 
        if (session_in() == 3 || session_in() == 6) {
          ?>
          <td style="border: 1px solid black; padding: 0px; width: 30px;"><button type="button" value="<?=$sel->depense_id?>" class="delete_depense" style=" width: 40px;">oo</button></td>
          <?php 
        } ?>
      </tr>
    <?php
  }
  ?>
  </table></div><br/><br/>
  <button style="margin-left:1%;" type="button" id="print_today_depenses" class="validate_login">Imprimer la fiche des dépenses de la journée</button>
  <button style="margin-left:1%;" type="button" id="see_other_depenses" class="validate_login">Autres dépenses</button>
</div>
<div style="display:none;" id="autres_depenses">
  <h3>Toutes les autres dépenses</h3>
  <?php
  $sel001 = "SELECT * FROM depenses WHERE depense_validated=1 AND date_creation!="."'$date'"."ORDER BY date_creation DESC";
  $sel111 = $database_connect->query($sel001);
  while($sel1 = $sel111->fetchObject())
  {
    $cout_unitaire1 = $sel1->cout_depense;
    $nombre1 = $sel1->number_depense;
    $cout_total1 = $nombre1*$cout_unitaire1;

    $recup_libelle01 = "SELECT * FROM libelles WHERE libelle_id=?";
    $recup_libelle11 = $database_connect->prepare($recup_libelle01);
    $recup_libelle11->execute(array($sel1->libelle));
    $recup_libelle1 = $recup_libelle11->fetchObject();

    ?>
    <table style="border-collapse: collapse; margin-left: 1%;width: 98%;">

      <caption>
        <strong>DEPENSE EFFECTUEE EN DATE DU <?=strtoupper($sel1->date_creation)?></strong>
      </caption>

      <tr>
        <th style="border: 1px solid black;">Coût unitaire</th>
        <th style="border: 1px solid black;">Nbre d'<br/>exécution</th>
        <th style="border: 1px solid black;">Coût total</th>
        <th style="border: 1px solid black;">Raison du la dépense</th>
        <th style="border: 1px solid black;">Date(JJ/MM/AA)</th>
        <th style="border: 1px solid black;">Heure(HH/mm)</th>
      </tr>
      <tr>
        <td style="border: 1px solid black;"><?=$cout_unitaire1?> USD</td>
        <td style="border: 1px solid black;"><?=$nombre1?></td>
        <td style="border: 1px solid black;"><?=$cout_total1?> USD</td>
        <td style="border: 1px solid black;"><?=$recup_libelle1->description_libelle?></td>
        <td style="border: 1px solid black;"><?=$sel1->date_creation?></td>
        <td style="border: 1px solid black;"><?=$sel1->heure_depense?></td>
      </tr>
    </table><br/><br/>
    <?php
  }

  ?>
</div><br/>
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
});
</script>
