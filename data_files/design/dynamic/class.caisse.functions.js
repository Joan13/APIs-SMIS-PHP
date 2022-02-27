$(document).ready(function () {

  function printContent(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;

  }

  $('.printFichePaiements').on('click', function () {
    printContent("fichePaiements");
  });

});