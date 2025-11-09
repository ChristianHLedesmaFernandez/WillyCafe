<?php 

error_reporting(0); // Para evitar los errores
if(isset($_GET["fechaInicial"])){	
	$fechaInicial = $_GET["fechaInicial"];
    $fechaFinal =$_GET["fechaFinal"];
}else{
    $fechaInicial = null;
    $fechaFinal = null;
}

$respuesta = ControladorConsumos:: ctrMostrarRangoFechasConsumos($fechaInicial, $fechaFinal);
$arrayFechas = array();
$sumaPagoMes = array();
foreach ($respuesta as $item) {
    // Capturamos solo año y mes
    $fecha = substr($item["fecha"], 0, 7);
    // Guardar la fecha
    $arrayFechas[] = $fecha;
    // Inicializar si no existe
    if (!isset($sumaPagoMes[$fecha])) {
        $sumaPagoMes[$fecha] = 0;
    }
    // Sumar el total del mes
    $sumaPagoMes[$fecha] += floatval($item["total"]);//$item["total"];
}
$noRepetirFecha = array_unique($arrayFechas);

//var_dump($sumaPagoMes);

?>
<!-- Grafico de Consumos (bg-teal-gradient) degradado -->
<div class="box box-solid bg-teal-gradient">
	<div class="box-header">		
		<i class="fa fa-th"></i>
		<h3 class="box-title">Gráfico de Consumos</h3>
	</div>
	<div class="box-body border-radius-none nuevoGraficoConsumos">
		<div class="chart" id="line-chart-consumos" style="height:250px;"></div>		
	</div>	
</div>

<!-- Comienzo  Morris --> 
<script>	
	var line = new Morris.Line({
	    element          : 'line-chart-consumos',
	    resize           : true,
	    data             : [	    	
	      <?php 
		      if($noRepetirFecha != null){
			      foreach ($noRepetirFecha as $key) {		      		
			      	echo "{ y: '".$key."', consumos: ". $sumaPagoMes[$key]." },";
			      }
			      echo "{ y: '".$key."', consumos: ". $sumaPagoMes[$key]." }";
			  }else{
			  	echo "{y: '0', consumos: '0'}";
			  }		  
	      ?>
	    ],	    
	    xkey             : 'y',
	    ykeys            : ['consumos'],
	    labels           : ['consumos'],
	    lineColors       : ['#efefef'],
	    lineWidth        : 2,
	    hideHover        : 'auto',
	    gridTextColor    : '#fff',
	    gridStrokeWidth  : 0.4,
	    pointSize        : 4,
	    pointStrokeColors: ['#efefef'],
	    gridLineColor    : '#efefef',
	    gridTextFamily   : 'Open Sans',
	    preUnits		 : '$',	
	    gridTextSize     : 10
	    /**/
  	});
</script>


<!-- Fin Morris -->

<!-- Fin Grafico -->