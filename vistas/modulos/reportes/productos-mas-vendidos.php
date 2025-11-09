<?php 
$item = NULL;
$valor = NULL;
$orden = "ventas";
$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
$colores = array("red", "green", "yellow", "aqua", "purple", "blue", "maroon", "light-blue", "orange", "navy");
$totalVentas = ControladorProductos::ctrMostrarSumaVentas();
?>
<div class="box box-warning">
  <div class="box-header with-border">
    <h3 class="box-title">Productos mas Vendidos</h3>
  </div> 
  <div class="box-body">
    <div class="row">
      <div class="col-md-7">
        <div class="chart-responsive">
          <canvas id="pieChart" height="150"></canvas>
        </div>
      </div>
      <div class="col-md-5">
        <ul class="chart-legend clearfix">          
          <?php 
            for ($i = 0; $i < 10 ; $i++) { 
              echo '<li><i class="fa fa-circle-o text-'. $colores[$i] .'"></i>   '. $productos[$i]["descripcion"] .'</li>';
            }
                    ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="box-footer no-padding">
    <ul class="nav nav-pills nav-stacked">
      <?php
        for ($i = 0; $i < 5 ; $i++) {
          echo '<li>
                  <a href="#">
                    '. $productos[$i]["descripcion"] .'
                    <span class="pull-right text-'. $colores[$i] .'"><i class="fa fa-angle-down"></i> '. ceil($productos[$i]["ventas"]*100/$totalVentas["total"]) .'% </span>
                  </a>
                </li>';
        }
      ?>
    </ul>
  </div>
</div>
<!-- Script que da funcionalidad -->
<script>
 
  // PIE CHART 
  // Obtener contexto con jQuery - usando jQuery's .get() method.
  var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
  var pieChart       = new Chart(pieChartCanvas);
  var PieData        = [
    <?php
    for ($i=0; $i < 10; $i++) {       
      echo "{
              value    : ". $productos[$i]["ventas"] .",
              color    : '". $colores[$i] ."',
              highlight: '". $colores[$i] ."',
              label    : '". $productos[$i]["descripcion"] ."'
            },";
    }
    ?>   
    
  ];
  var pieOptions     = {
    // Boolean - Si debemos mostrar un trazo en cada segmento.
    segmentShowStroke    : true,
    // String - El color de cada segmento de trazo.
    segmentStrokeColor   : '#fff',
    // Number - El ancho de cada segmento de trazo.
    segmentStrokeWidth   : 1,
    // Number - El porcentaje de la tabla que recortamos del medio.
    percentageInnerCutout: 50, // This is 0 for Pie charts
    // Number - Cantidad de pasos de animación
    animationSteps       : 100,
    // String - Animación que suaviza el efecto.
    animationEasing      : 'easeOutBounce',
    // Boolean - Si animamos la rotación de la Donut.
    animateRotate        : true,
    // Boolean - Ya sea que animemos escalando el Donut desde el centro.
    animateScale         : false,
    // Boolean - Ya sea para hacer que el gráfico responda al cambio de tamaño de la ventana.
    responsive           : true,
    // Boolean - Si se mantiene o no la relación de aspecto inicial cuando la capacidad de respuesta, si se establece en falso, ocupará todo el contenedor.
    maintainAspectRatio  : false,
    // String - Una plantilla de leyenda.
    legendTemplate       : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
    // String - Una plantilla de información sobre herramientas.
    tooltipTemplate      : '<%=value %> <%=label%>'
  };
  // Crear gráfico Circular o Donut
  // Puede cambiar entre Circular y Donut usando el método a continuación.
  pieChart.Doughnut(PieData, pieOptions);

</script>