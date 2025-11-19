<div class="content-wrapper">
  <!-- Encabezado de contenido (encabezado de página) -->
  <section class="content-header">
    <h1>
      Reporte de Consumos
      <small>Crear reportes de Ventas</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>Inicio</a></li>
      <li>Consumos</li>        
      <li class="active">Reportes de Consumos</li>
    </ol>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <div class="input-group">
          <!-- Comienzo Boton de Rango de Fecha -->
          <button type="button" class="btn btn-default" id="daterange-btn2">          
            <span>
              <i class="fa fa-calendar"></i>  Rango de Fecha 
            </span> 
              <i class="fa fa-caret-down"></i>
          </button>
          <!-- Fin Boton de Rango -->
        </div>  
        <div class="box-tools pull-right">
          <!-- Comienzo Boton de Reporte en Excel -->
          <?php 
            if(isset($_GET['fechaInicial'])){
              echo  '<a href="vistas/modulos/descargar-reporte.php?reporte=reporte&fechaInicial='.$_GET["fechaInicial"].'&fechaFinal='.$_GET["fechaFinal"].'">';
            }else{
              echo  '<a href="vistas/modulos/descargar-reporte.php?reporte=reporte">';
            }

            if($_SESSION["perfil"] == "Administrador"){
              echo '
                    <button class="btn btn-success" style="margin-top:5px">
                      Descargar reporte en Excel
                    </button>';              
            }
          ?>
          </a>          
          <!-- Fin Boton Reporte -->
        </div>

      </div>
      <div class="box-body">

        <div class="row">
          
          <div class="col-xs-12">
            <?php 
              include "reportes/grafico-consumos.php"
            ?>
          </div>
          <div class="col-md-6 col-xs-12">
            <?php 
              include "reportes/productos-mas-vendidos.php"
            ?>
          </div>
          
          <div class="col-md-6 col-xs-12">            
            <?php 
              include "reportes/vendedores.php"
            ?>
          </div>
          <div class="col-md-6 col-xs-12">            
            <?php 
              include "reportes/compradores.php"
            ?>
          </div>
        </div>

      </div>
      
    </div>

  </section>
</div>