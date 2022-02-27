$(document).ready(function() {

  $("#submit_base_school_info").on('click', function(){

      let name_promoter = $("#name_promoter").val();
      let code_school = $("#code_school").val();
      let date_end = $("#date_end").val();
      let school_year_info = $("#school_year_info").val();

      if(name_promoter == '' || date_end == '' || school_year_info == '' || code_school == '')
      {
        $('#information-div-global').fadeIn(400);
      }
      else
      {
          $.post("pages/ajax/base_school_info.php", {name_promoter:name_promoter, code_school:code_school, date_end:date_end, school_year_info:school_year_info}, function() {
              $("#name_promoter").val('');
              $("#date_end").val('');
              $("#school_year_info").val('');
              $('#success-information-div-global').fadeIn(400);
          });
      }
  });

  $("#show_update_school_info").on('click', function() {
    $("#base_school_info").slideToggle(400);
  })

  $(".delete_libelle").on('click', function(){
      var delete_lib = $(this).val();
      $.post("pages/ajax/delete_libelle.php", {delete_lib:delete_lib}, function(){
          $("#entry_deleted").fadeIn(700);
      });
  });

  $('#validate_search_pupil').on('click', function() {
    let first_name = $('#input_search_first_name').val();
    let second_name = $('#input_search_second_name').val();
    let school_year_search = $('#school_year_search').val();

    if(first_name == '' || second_name == '' || school_year_search == '')
    {
      //alert("Renseignez le nom de l'élève, son prénom et l'année de recherche");
      $('#information-div-global').fadeIn(400);
    }
    else
    {
      $.post('pages/ajax/search.php', {first_name:first_name, second_name:second_name, school_year_search:school_year_search}, function(data) {
        $('.main_div_search').html(data);
    			$('.main_div_search_main').fadeIn(400);
      });
    }
  });

  $('.close_div_search').on('click', function() {
    $('.main_div_search_main').fadeOut(400);
  });

  $('#depense_caisse').on('click', function() {
      $('#dep_caisse').fadeIn(400);
  });

  $('.close_div_pupil_caisse_depense').on('click', function() {
    $('#dep_caisse').fadeOut(400);
  });

  $('#button_main_montant').on('click', function() {
    $('#new_depense').fadeOut(-2000);
    $('#fiches_depenses').fadeOut(-2000);
    $('#status').fadeIn(200);
  });

  $('#nouvelle_depense_caisse').on('click', function() {
    $('#status').fadeOut(-2000);
    $('#fiches_depenses').fadeOut(-2000);
    $('#new_depense').fadeIn(200);
  });

  $('#all_depenses_caisse').on('click', function() {
    $.post('pages/ajax/all_depenses_caisse.php', function(data) {
      $('#new_depense').fadeOut(-2000);
      $('#status').fadeOut(-2000);
      $('#fiches_depenses').html(data);
      $('#fiches_depenses').fadeIn(200);
    });
  });

  $('#validate_depense').on('click', function() {
    let cout_depense = $('#cout_depense').val();
    let number_depense = $('#number_depense').val();
    let libelle_depense = $('#libelle_depense').val();

    if(cout_depense == '' || number_depense == '' || libelle_depense == '')
    {
      $('#information-div-global').fadeIn(400);
    }
    else
    {
      $.post('pages/ajax/new_depense.php', {cout_depense:cout_depense, number_depense:number_depense, libelle_depense:libelle_depense}, function() {
        $('#success-information-div-global').fadeIn(400);
      });
    }
});

$('#les_classes').on('click', function() {
  $('.show_third_party_c_pp').slideToggle(400);
});

  $('#les_classes_caisse').on('click', function(){
    $('.show_third_party_c').slideToggle(400);
  });

  $('.button_parametres').on('click', function(){
    $('#rd_party').fadeIn(400);
  });

  $('#annuler_change_password').on('click', function(){
    $('#rd_party').fadeOut(400);
  });

  $('#old_password').keyup(function(){
    if($('#old_password').val().length > 5)
    {
      $('.info_old_password').fadeIn();
    }
  });

  $('#new_password2').keyup(function(){
    if($('#new_password2').val() != $('#new_password1').val())
    {
      $('.info_new_password').fadeIn();
    }
    else
    {
      $('.info_new_password').fadeOut();
    }
  });

$('#submit_change_password').on('click', function(){
  let poste_number = $(this).val();
  let old_password = $('#old_password').val();
  let new_password1 = $('#new_password1').val();
  let new_password2 = $('#new_password2').val();

  if(poste_number == '' || old_password == '' || new_password1 == '' || new_password2 == '')
  {
    $('#information-div-global').fadeIn(400);
  }
  else
  {
    if(new_password1.length < 6 || new_password2.length < 6)
    {
    alert("Au moins 6 caracteres pour le nouveau mot de passe");
    }
    else
    {
      $.post('pages/ajax/password_edit.php', {poste_number:poste_number, old_password:old_password, new_password2:new_password2}, function(){

        // $('#old_password').val("");
        // $('#new_password1').val("");
        // $('#new_password2').val("");

        $('#rd_party').fadeOut(400);
        
        $('#success-information-div-global').fadeIn(400);
      });
    }
  }
});



$('#statistics_caisse').on('click', function() {

  $.post('pages/ajax/main_stat_caisse.php', function(data) {
    $('.main_stat_div').html(data);
  });

  $('#stat_caisse').fadeIn(400);
});

$('#submit_search_observations').on('click', function() {

let date_day = $("#date_day").val();

  if (date_day != "") {
  $.post('pages/ajax/observations_generales.php', {date_day:date_day}, function(data) {
    $('#observationsDay').html(data);
  });
} else {
  // alert("Toka");
}

  // $('#stat_caisse').fadeIn(400);
});

$('#all_stats').on('click', function() {

  let stat_year = $('#select_years_stat').val();

  console.log({stat_year:stat_year})

  $.post('pages/ajax/main_stat_caisse.php', { stat_year:stat_year }, function(data) {

    $('.main_stat_div').html(data);

  });

});

$('.close_div_pupil_caisse_stat').on('click', function() {
  $('#stat_caisse').fadeOut(400);
});
});

