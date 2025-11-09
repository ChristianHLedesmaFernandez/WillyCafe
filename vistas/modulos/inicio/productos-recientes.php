<?php 

  $item = NULL;
  $valor = NULL;
  $orden = "id";
  $productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
  

 $type = array("success", "warning", "info", "danger", "primary", "success", "warning", "info", "danger", "primary");



?>
<!-- Listas de ultimos 10 Productos agregados  -->
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Productos Agregados Recientemente</h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
  </div>
    <!-- /.box-header -->
  <div class="box-body">
    <ul class="products-list product-list-in-box">
      <!-- Mostrar Producto -->
      <?php 
      for ($i=0; $i < 5 ; $i++) {

        echo '<li class="item">
                <div class="product-img">
                  <img src="'. $productos[$i]["imagen"] .'" alt="Product Image">
                </div>
                <div class="product-info" style="font-size:16px;">
                  <a href="" class="product-title">
                    '. $productos[$i]["descripcion"] .'
                    <span class="label label-'. $type[$i] .' pull-right" style="font-size:14px;">$ '. $productos[$i]["precio_venta"].'</span>
                  </a>

                </div>
               </li>';
      }
      ?>
      <!-- Fin Mostrar -->

                
    </ul>
  </div>

  <div class="box-footer text-center">
    <a href="productos" class="uppercase">Ver Todos los Productos</a>
  </div>

</div>