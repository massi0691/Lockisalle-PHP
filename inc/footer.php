<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.0/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.min.js" integrity="sha512-OvBgP9A2JBgiRad/mM36mkzXSXaJE9BEIENnVEmeZdITvwT09xnxLtT4twkCa8m/loMbPHsvPl0T8lRGVBwjlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../assets/js/jquery.datetimepicker.full.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>


 <!-- datetime picker pour la date d'arrivée et de départ -->


<script>
   $.datetimepicker.setLocale('fr');

    $('#picker1').datetimepicker({
        timepicker: true,
        datepicker: true,
        format: 'Y-m-d H:i:s',
        weeks: true,
        step: 15,
        yearStart: 2022,
        yearEnd: 2026,
        theme: 'dark',
        lang: 'fr',
        mask: true,

    })
    $('#picker2').datetimepicker({
        timepicker: true,
        datepicker: true,
        format: 'Y-m-d H:i:s',
        weeks: true,
        step: 15,
        yearStart: 2022,
        yearEnd: 2026,
        theme: 'dark',
        lang: 'fr',
        mask: true,
      
    })

    $('#picker3').datetimepicker({
        timepicker: true,
        datepicker: true,
        format: 'Y-m-d H:i:s',
        weeks: true,
        step: 15,
        yearStart: 2022,
        yearEnd: 2026,
        theme: 'dark',
        lang: 'fr',
        mask: true,

    })

    $('#picker4').datetimepicker({
        timepicker: true,
        datepicker: true,
        format: 'Y-m-d H:i:s',
        weeks: true,
        step: 15,
        yearStart: 2022,
        yearEnd: 2026,
        theme: 'dark',
        lang: 'fr',
        mask: true,

    })
    $('#picker5').datetimepicker({
        timepicker: true,
        datepicker: true,
        format: 'Y-m-d H:i:s',
        weeks: true,
        step: 15,
        yearStart: 2022,
        yearEnd: 2026,
        theme: 'dark',
        lang: 'fr',
        mask: true,

    })


    $("#toggle1").on('click', function() {
        $("#picker1").datetimepicker('toggle');
        
    })
    $("#toggle2").on('click', function() {
        $("#picker2").datetimepicker('toggle');
        
    })
    $("#toggle3").on('click', function() {
        $("#picker3").datetimepicker('toggle');
        
    })
    $("#toggle4").on('click', function() {
        $("#picker4").datetimepicker('toggle');
        
    })
    $("#toggle5").on('click', function() {
        $("#picker5").datetimepicker('toggle');
        
    })

/// montrer le prix dans range

$( function() {
    $( "#slider-range-min" ).slider({
      range: "min",
      value: 37,
      min: 1,
      max: 700,
      slide: function( event, ui ) {
        $( "#amount" ).val( "$" + ui.value );
      }
    });
    $( "#amount" ).val( "$" + $( "#slider-range-min" ).slider( "value" ) );
  } );



</script>

</body>

</html>