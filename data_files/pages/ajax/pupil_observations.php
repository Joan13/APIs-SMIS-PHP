
<?php

include '../../config/dbconnect.functions.php';
include '../../config/class.marksheet.insert.functions.php';

$pupil_id = htmlspecialchars(trim(strip_tags($_POST['ppupil'])));
$s_year = htmlspecialchars(trim(strip_tags($_POST['yyearr'])));

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
$school_year = find_school_year($s_year);

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

?>
<h2>Feuillet de discipline de l'élève. Année scolaire <?= $school_year ?></h2>

<div style="text-align: center; display: inline-block;" id="pupil_frais_divers">
<table style="border: 1px solid rgba(0, 0, 0, 0.2); border-collapse: collapse;" class="frais_divers_table">
<tr style="border: 1px solid rgba(0, 0, 0, 0.2);">
    <td colspan="2" style="padding: 5px; text-align:left; border:1px solid rgba(0, 0, 0, 0.2);">

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
    <td colspan="2" style="padding: 5px; border:1px solid rgba(0, 0, 0, 0.2); width: 200px; border-right:none; text-align: left;">
        ELEVE : <strong><?=strtoupper($ssl->first_name." ".$ssl->second_name)." ".$ssl->last_name?></strong><br/>
        CLASSE : <strong><?=$class_identity?></strong><br/>
        ANNEE-SCOLAIRE : <strong><?=$school_year?></strong><br/><br/>
        <strong>FICHE OBSERVATION (DISCIPLINE)</strong><br/>
        <span>Reçu No <strong><?=rand(1000, 9999)?></strong></span>
    </td>
</tr>

<tr style="border: 1px solid rgba(0, 0, 0, 0.2);">
    <th style="width: 150px; text-align: center;">Date</th>
    <th style=" max-width: 500px;">Détaillé de l'observation</th>
    <th style="width: 150px; text-align: center;">Mention</th>
    <th style="width: 150px; text-align: center;">Année scolaire</th>
</tr>

<?php

$query = "SELECT * FROM observations WHERE deleted=? AND pupil_id=? AND school_year=?";
$request = $database_connect->prepare($query);
$request->execute(array(0, $pupil_id, $s_year));
while($response = $request->fetchObject()) {

    $recup_annee0 = "SELECT * FROM school_years WHERE year_id=?";
    $recup_annee1 = $database_connect->prepare($recup_annee0);
    $recup_annee1->execute(array($response->school_year));
    $recup_annee = $recup_annee1->fetchObject();

    $mention = $response->mention;
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
        <td style="border: 1px solid rgba(0, 0, 0, 0.2); text-align: center;"><?= $response->date_observation ?></td>
        <td style="border: 1px solid rgba(0, 0, 0, 0.2); text-align: left; padding-left: 15px; max-width: 500px;"><?= $response->main_observation ?></td>
        <td style="border: 1px solid rgba(0, 0, 0, 0.2); text-align: center;"><?= $mention ?></td>
        <td style="border: 1px solid rgba(0, 0, 0, 0.2); text-align: center;"><?= $recup_annee->year_name ?></td>
        <!-- <td style="display: none;" class="edit_td"><button class="button_delete_recu" value="<?= $response->observation_id ?>">Effacer cette entrée</button></td> -->
    </tr>
    
    <?php
}
?>
</table>
</div>

<div class="action_frais_divers">
<!-- <button class="validate_login" id="edit_frais_divers">Editer les entrées</button> -->
<button class="validate_login" id="print_recu_frais_divers">Imprimer cette fiche</button>
</div>

<script type="text/javascript" src="design/dynamic/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

  function printContent(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    
    window.print();
    
    document.body.innerHTML = originalContents;
  }

  $('#print_recu_frais_divers').on('click', function() {
    printContent("pupil_frais_divers");

    $('.outCaisse').fadeOut(400);
    $('.inCaisse').fadeIn(400);
  });

  $('#edit_frais_divers').on('click', function() {
    $('.edit_td').fadeToggle(800);
  });

  $('.button_delete_recu').on('click', function() {
    let frais_divers_id = $(this).val();

    $.post('pages/ajax/delete_frais_divers.php', {frais_divers_id:frais_divers_id}, function(data) {
        $('.outCaisse').fadeOut(400);
        $('.inCaisse').fadeIn(400);
    });
  })
});
</script>