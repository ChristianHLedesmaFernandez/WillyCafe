<!-- Reporte de los mejores vendedores. -->
<?php 
$item = null;
$valor = null;
$consumos = ControladorConsumos::ctrMostrarConsumos($item, $valor);
$sumaTotalVendedores = array();
foreach ($consumos as $venta) {
    $nombre = $venta['vendedor'];
    $neto = floatval($venta['neto']); // o 'total' si querés incluir descuentos
    if (!isset($sumaTotalVendedores[$nombre])) {
        $sumaTotalVendedores[$nombre] = 0;
    }
    $sumaTotalVendedores[$nombre] += $neto;
}
$items = array();
foreach($sumaTotalVendedores as $nombre => $total){
    $items[] = "{y: '".addslashes($nombre)."', a: ".$total."}";
}
?>

<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Vendedores</h3>
	</div>
	<div class="box-body">
		<div class="chart-responsive">
			<div class="chart" id="bar-chartVendedor" style="height: 300px;">
				
			</div>
		</div>
	</div>
</div>

<script>
// BAR CHART
var bar = new Morris.Bar({
	element: 'bar-chartVendedor',
	resize: true,
	data: [
	<?php 
		echo implode(",", $items);	 
	?>  
    ],
    barColors: ['#33FF35'],
    xkey: 'y',
    ykeys: ['a'],
    labels: ['Ventas'],
    preUnits: '$',
    hideHover: 'auto'
});
</script>