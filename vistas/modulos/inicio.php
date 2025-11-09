<div class="content-wrapper">
  <!-- Encabezado de contenido (encabezado de página) -->
  <section class="content-header">
    <h1>
      Inicio
      <small>Panel de Control</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>Inicio</a></li>        
      <li class="active">Inicio</li>
    </ol>
  </section>
  <!-- Fin Encabezado de contenido -->
  <!-- Contenido principal  -->
  <section class="content">    
  <div class="row">
    
    
    <!-- Si es un Administrador -->
    <?php
      if($_SESSION["perfil"] == "Administrador"|| $_SESSION["perfil"] == "Vendedor"){
        include "inicio/cajas-superiores.php";
      }
    ?>    
  </div>
  <div class="row">
    <div class="col-lg-12">
      <?php
        if($_SESSION["perfil"] == "Administrador"){ 
          include "reportes/grafico-consumos.php";
        }
      ?>
    </div>
    <div class="col-lg-6">
      <?php 
        if($_SESSION["perfil"] == "Administrador"|| $_SESSION["perfil"] == "Vendedor"){
          include "reportes/productos-mas-vendidos.php";
        }
      ?>
    </div>
    <div class="col-lg-6">
      <?php 
        if($_SESSION["perfil"] == "Administrador"|| $_SESSION["perfil"] == "Vendedor"){
          
          include "inicio/productos-recientes.php";
        }
      ?>
    </div>
    <div class="col-lg-12">
      <?php 
      if($_SESSION["perfil"] == "Cliente"){
        echo '<div class= "box box-success">
                <div class= "box box-header">
                  <h1>Bienvenid@ '. $_SESSION["nombre"] .' </h1>
                </div>
              </div>';
      } ?>
    </div>
  </div>
  </section>
  <!-- Fin Contenido principal -->
</div>