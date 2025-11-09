// Variables Local Storage
if(localStorage.getItem("capturarRango2") != null){
  $("#daterange-btn2 span").html(localStorage.getItem("capturarRango2"));
}else{
  $("#daterange-btn2 span").html('<i class="fa fa-calendar"></i> Rango de Fecha');
}

// Rango de Fechas
$('#daterange-btn2').daterangepicker(
    {
    	ranges   : {
          'Hoy'       : [moment(), moment()],
          'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Ultimos 7 Dias' : [moment().subtract(6, 'days'), moment()],
          'Ultimos 30 Dias': [moment().subtract(29, 'days'), moment()],
          'Este Mes'  : [moment().startOf('month'), moment().endOf('month')],
          'Ultimos Mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment(),

        endDate  : moment()
    },
    function (start, end) {
    	$('#daterange-btn2 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
 
    	var fechaInicial = start.format('YYYY-MM-DD');
    	var fechaFinal = end.format('YYYY-MM-DD');
    	var capturarRango2 = $("#daterange-btn2 span").html();


    	localStorage.setItem("capturarRango2", capturarRango2);
    	
    	// Diferenciar los Botones
    	//$(".daterangepicker.opensleft .range_inputs .cancelBtn").attr("boton", "paginaVentas");
    	//$(".daterangepicker.opensleft .ranges li").attr("boton", "paginaVentas");
    	//Fin Diferenciar

    	window.location = "index.php?ruta=reportes&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;
    	
    }
)
// Cancelar Rango de Fechas
$(".daterangepicker.opensright .range_inputs .cancelBtn").on("click", function(){

  localStorage.removeItem("capturarRango2");
  localStorage.clear();

  //if($(this).attr("boton") == "paginaConsumos"){

    window.location = "reportesconsumos";

  //}
})

// Capturar Hoy
$(".daterangepicker.opensright .ranges li").on("click", function(event){
  var textoHoy = $(this).attr("data-range-key");
  if(textoHoy == "Hoy"){
    var d = new Date();
    var dia = d.getDate();
    var mes = d.getMonth() + 1;
    var año = d.getFullYear();
    if(mes < 10){
      mes = "0" + mes;      
    }
    if(dia < 10){
      dia = "0" + dia;
    }
    var fechaInicial = año+ "-" + mes + "-" + dia;
    var fechaFinal = año+ "-" + mes + "-" + dia;    
    localStorage.setItem("capturarRango2", "Hoy");

    //if($(this).attr("boton") == "paginaVentas"){
      window.location = "index.php?ruta=reportes&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;
    //}
  }

})