<?php

    include '../../config/dbconnect.functions.php';

    if(isset($_POST['paiement_id']))
    {
      $paiement_id = htmlspecialchars(trim(strip_tags($_POST['paiement_id'])));
      ?>
      <div class="ask_div">
        <h2>Etes-vous sur de vouloir effacer ce reçu ?<br/>
        Une fois fait, vous ne pourrez plus revenir en arrière</h2>
        <button type="button" class="accept_button_ask" value="<?=$paiement_id?>" id="accept_button_ask">Oui, effacer</button>
        <button type="button" class="annuler_button_ask" id="annuler_button_ask">Annuler</button>
      </div>
      <script type="text/javascript" src="design/dynamic/jquery-3.2.1.min.js"></script>
      <script type="text/javascript">
      $(document).ready(function() {
          $('#accept_button_ask').on('click', function() {
          	let paiement_id = $(this).val();
          	if(paiement_id != '')
          	{
          		$.post('pages/ajax/delete_recu.php', {paiement_id:paiement_id}, function() {
                  $('.delete_div_global').fadeOut(200);
          		});
          	}
          });

          $('#annuler_button_ask').on('click', function() {
            $('.delete_div_global').fadeOut(200);
          });
      });
      </script>
      <?php

    }

    if(isset($_POST['depense_id']))
    {
      $depense_id = htmlspecialchars(trim(strip_tags($_POST['depense_id'])));
      ?>
      <div class="ask_div">
        <h2>Etes-vous sur de vouloir effacer cette dépense ?<br/>
        Une fois fait, vous ne pourrez plus revenir en arrière</h2>
        <button type="button" class="accept_button_ask" value="<?=$depense_id?>" id="accept_button_ask">Oui, effacer</button>
        <button type="button" class="annuler_button_ask" id="annuler_button_ask">Annuler</button>
      </div>
      <script type="text/javascript" src="design/dynamic/jquery-3.2.1.min.js"></script>
      <script type="text/javascript">
      $(document).ready(function() {
          $('#accept_button_ask').on('click', function() {
          	let depense_id = $(this).val();
          	if(depense_id != '')
          	{
          		$.post('pages/ajax/delete_depense.php', {depense_id:depense_id}, function() {
                  $.post('pages/ajax/all_depenses_caisse.php', function(data) {
                    $('#fiches_depenses').html(data);
                    $('.delete_div_global').fadeOut(200);
                  });
          		});
          	}
          });

          $('#annuler_button_ask').on('click', function() {
            $('.delete_div_global').fadeOut(200);
          });
      });
      </script>
      <?php
    }

    if(isset($_POST['pupil_pupil']))
    {
      $pupil_pupil = htmlspecialchars(trim(strip_tags($_POST['pupil_pupil'])));
      ?>
      <div class="ask_div">
        <h2>Vous êtes sur le point de vouloir effacer toutes les informations par rapport à cet (te) élève ?<br/>
        NB : Cette opération est irréversible. Voulez-vous proccéder ?</h2>
        <button type="button" class="accept_button_ask" value="<?=$pupil_pupil?>" id="accept_button_ask">Oui, effacer</button>
        <button type="button" class="annuler_button_ask" id="annuler_button_ask">Annuler</button>
      </div>
      <script type="text/javascript" src="design/dynamic/jquery-3.2.1.min.js"></script>
      <script type="text/javascript">
      $(document).ready(function() {
          $('#accept_button_ask').on('click', function() {
          	let pupil_pupil = $(this).val();
          	if(pupil_pupil != '')
          	{
          		$.post('pages/ajax/delete_pupil.php', {pupil_pupil:pupil_pupil}, function() {
                  // $.post('pages/ajax/all_depenses_caisse.php', function(data) {
                  //   $('#fiches_depenses').html(data);
                  //   $('#delete_div').fadeOut(200);
                  // });

                  $('.delete_div_global').fadeOut(200);
          		});
          	}
          });

          $('#annuler_button_ask').on('click', function() {
            $('.delete_div_global').fadeOut(200);
          });
      });
      </script>
      <?php
    }

    if(isset($_POST['migrations_confirmation']))
    {
      include '../../config/pupil.marks.functions.php';

      $from_school_year = htmlspecialchars(trim(strip_tags($_POST['from_school_year'])));
      $to_school_year = htmlspecialchars(trim(strip_tags($_POST['to_school_year'])));
      $cycle_school_pupil = htmlspecialchars(strip_tags(trim($_POST['cycle_school_pupil'])));
      $class_school_pupil = htmlspecialchars(strip_tags(trim($_POST['class_school_pupil'])));
      $class_order_pupil = htmlspecialchars(strip_tags(trim($_POST['class_order_pupil'])));
      $class_section_pupil = htmlspecialchars(strip_tags(trim($_POST['class_section_pupil'])));
      $class_option_pupil = htmlspecialchars(strip_tags(trim($_POST['class_option_pupil'])));

      ?>
      <div class="ask_div">
        <h2>Etes-vous sûr de vouloir proccéder aux migrations<br/> de l'année scolaire <?= find_school_year($from_school_year); ?> à l'année scolaire <?= find_school_year($to_school_year); ?> ? </h2>
        <button type="button" class="accept_button_ask" id="accept_button_ask">Oui, Procéder à la migration</button>
        <button type="button" class="annuler_button_ask" id="annuler_button_ask">Annuler</button>
      </div>
      <script type="text/javascript" src="design/dynamic/jquery-3.2.1.min.js"></script>
      <script type="text/javascript">
      $(document).ready(function() {

          $('#annuler_button_ask').on('click', function() {
            $('.ask_div').fadeOut(400);
          });

          $('#accept_button_ask').on('click', function() {
            $('#delete_mig').fadeOut(400);
            $('#progress_bar_div').fadeIn();

            let from_school_year = <?= $from_school_year ?>;
            let to_school_year = <?= $to_school_year ?>;
            let cycle_school_pupil = <?= $cycle_school_pupil ?>;
            let class_school_pupil = <?= $class_school_pupil ?>;
            let class_order_pupil = <?= $class_order_pupil ?>;
            let class_section_pupil = <?= $class_section_pupil ?>;
            let class_option_pupil = <?= $class_option_pupil ?>;

              $.post('pages/ajax/migrate_years.php', 
              { 
                from_school_year:from_school_year,
                to_school_year:to_school_year,
                cycle_school_pupil:cycle_school_pupil,
                class_school_pupil:class_school_pupil,
                class_order_pupil:class_order_pupil,
                class_section_pupil:class_section_pupil,
                class_option_pupil:class_option_pupil,
              }, 
              () => {
                  $('#progress_bar_div').fadeOut();
              });
          });
      });
      </script>
      <?php
    }

  ?>
