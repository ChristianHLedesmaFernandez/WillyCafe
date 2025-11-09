<!-- Reporte de los mejores vendedores. -->

<?php 

$item = null;
$valor = null;

$consumos = ControladorConsumos::ctrMostrarConsumos($item, $valor);
$sumaTotalCompradores = array();
foreach ($consumos as $consumo) {
    $nombre = $consumo['cliente'];
    $neto = floatval($consumo['neto']); // o 'total' si querés incluir descuentos

    if (!isset($sumaTotalCompradores[$nombre])) {
        $sumaTotalCompradores[$nombre] = 0;
    }
    $sumaTotalCompradores[$nombre] += $neto;
}
$items = array();
foreach($sumaTotalCompradores as $nombre => $total){
    $items[] = "{y: '".addslashes($nombre)."', a: ".$total."}";
}

?>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Compradores</h3>
	</div>
	<div class="box-body">
		<div class="chart-responsive">
			<div class="chart" id="bar-chartComprador" style="height: 300px;">
				
			</div>
		</div>
	</div>
</div>

<script>

// BAR CHART
var bar = new Morris.Bar({
	element: 'bar-chartComprador',
	resize: true,
	data: [
	        <?php 
	        	echo implode(",", $items);		 
			?>  
    	  ],
    barColors: ['#3E85E3'],
    xkey: 'y',
    ykeys: ['a'],
    labels: ['Ventas'],
    preUnits: '$',
    hideHover: 'auto'
});

</script>