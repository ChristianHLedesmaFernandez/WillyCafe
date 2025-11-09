<!-- <div id="back"></div> -->
<?php 
// Para que el fondo sea aleatorio.
$fondosArray = array("vistas/img/plantilla/fondo0.jpg",
                     "vistas/img/plantilla/fondo1.jpg", 
                     "vistas/img/plantilla/fondo2.jpg", 
                     "vistas/img/plantilla/fondo3.jpg", 
                     "vistas/img/plantilla/fondo4.jpg",
                     "vistas/img/plantilla/fondo5.jpg",
                     "vistas/img/plantilla/fondo6.jpg",
                     "vistas/img/plantilla/fondo7.jpg",
                     "vistas/img/plantilla/fondo8.jpg",
                     "vistas/img/plantilla/fondo9.jpg",
                    ); 
$fondo = rand(0, (count($fondosArray)-1));
// Fin Fondo Aleatorio
 ?>
 <!-- Estilo para hacer el Fondo Aleatorio -->
<div style=" 
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 115%;
            background: url('<?php echo $fondosArray[$fondo]; ?>');
            background-size: cover;
            overflow: hidden;
            z-index: -1;"></div>

<div class="register-box" style="margin-top: 5px">
  <div class="register-logo">
    <img src="vistas/img/plantilla/willycafe.png" class="img-responsive" style="padding: 10px 0px">
  </div>
  <div class="register-box-body">
    <p class="login-box-msg">Completar Registro</p>
    <!--Solicito el cliente a la Base de Datos  -->
     <?php

      $item = "id";
      $valor = $_SESSION["id"];
      $cliente = controladorUsuarios::ctrMostrarUsuarios($item, $valor)[0];
      ?>
    <form method="POST" enctype="multipart/form-data" class="needs-validation" id="formCompletarRegistro" novalidate> <!-- class="needs-validation" id="formulario" -->
      <!-- Nombre -->
      <div class="form-group has-feedback" id="nombre">
        <div>
          <?php 
            echo'<input type="text" class="form-control" placeholder="'. $cliente["nombre"].'" name="ingNombre" id="ingNombre" readonly>';
            echo '<input type="hidden" value="'. $valor .'" id="idUsuario" name="idUsuario">';
            echo '<input type="hidden" value="'. $cliente["usuario"] .'" id="usuario" name="usuario">';
           ?>          
          <span class="fa fa-address-card form-control-feedback "></span>
        </div>
      </div>
      <!-- Ingresar Apellido -->
      <div class="form-group has-feedback" id="nombre">
        <div>
          <input type="text" class="form-control" placeholder="Apellido" id="ingApellido" name="ingApellido" required autofocus>
          <span class="fa fa-address-card form-control-feedback "></span>    
        </div>
      </div> 
      <!-- Usuario -->
      <div class="form-group has-feedback" id="usuario">
        <div>
          <?php 
            echo'<input type="text" class="form-control" placeholder="'. $cliente["usuario"].'" name="ingUsuario" id="ingUsuario" readonly>'
           ?>
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
      </div>
      <!-- Email -->
      <div class="form-group has-feedback" id="correo">
        <div>
          <?php 
            echo '<input type="email" class="form-control" placeholder="'. $cliente["correo"].'" name="ingEmail" id="ingEmail" readonly>';
           ?>          
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
      </div>
      <!-- Editar Telefono-->
      <!-- Utilizamos plugin  Input Mask (para formatear entradas) -->
      <div class="form-group has-feedback" id="telefono">
        <div>
          <input type="text" class="form-control" id="ingTelefono" name="ingTelefono" data-inputmask="'mask':'(999) 9999-9999'" data-mask>
          <span class="fa fa-phone form-control-feedback"></span>
        </div>
      </div>
      <!-- Ingresar Fecha de Nacimiento -->
      <div class="form-group has-feedback" id="nombre">
        <div>
          <input type="date" class="form-control" placeholder="Fecha de Nacimiento" id="ingFechaNacimiento" name="ingFechaNacimiento" required autofocus>
          <span class="glyphicon glyphicon-gift form-control-feedback"></span>
        </div>
      </div>
      <!-- Subir Foto -->
      <div class="form-group">
        <div class="panel">SUBIR FOTO</div>
        <input type="file" class="nuevaFoto" name="ingFoto">
        <p class="help-block">Peso maximo de la foto 2 Mb.</p>
        <img src="vistas/img/usuarios/default.jpg" class="img-thumbnail previsualizar" width="100px">              
      </div> 
      <div class="row">        
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Completar</button>
        </div>      
      </div>
      <?php 
        // Ejecuto el Metodo Completar Registro
        $registrar = new ControladorClientes();
        $registrar -> ctrCompletarRegistro();
      ?>   
    </form>
    <div class="social-auth-links text-center">
 </div>
  </div>
  <!-- /.form-box -->
</div>