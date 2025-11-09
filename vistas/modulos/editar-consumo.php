<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Editar Consumo
      <small>Consumos</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i>Inicio</a></li>
      <li>Consumos</li>
      <li class="active">Editar</li>
    </ol>
  </section>
  <section class="content">
    <!-- Formulario  -->
    <div class="row">
      <!-- Tabla de Ventas-->
      <div class="col-lg-5 col-xs-12">        
        <!-- Pinta una linea verde borde  -->
          <div class="box box-success">
            <!--  Dibbuja una franja en blanco  -->
            <div class="box-header with-border">
              <form role="form" method="POST" class="formularioConsumo">  
                <!-- Cuerpo del Modulo -->
                <div class="box-body">                
                  <div class="box">                    
                    <?php
                      $item = "id_con";
                      $idConsumo = $_GET["idConsumo"];
                      $consumo = ControladorConsumos::ctrMostrarConsumos($item, $idConsumo);
                      $itemVendedor = "id";
                      $valorVendedor = $consumo["id_ven"];
                      $vendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor)[0];
                      $itemCliente = "id";
                      $valorCliente = $consumo["id_cli"];
                      $cliente = ControladorClientes::ctrMostrarUsuarios($itemCliente, $valorCliente);
                      $porcentajeDescuento = $consumo["total_descuento"] * 100 / $consumo["neto"];         
                    ?>
                    <!-- Entrada Vendedor -->
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $vendedor["nombre"]?>" readonly>
                        <input type="hidden" name="idVendedor" value="<?php echo($vendedor["id"])?>">
                      </div>
                    </div>
                    <!-- Entrada Codigo de Venta -->
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <input type="text" class="form-control" id="nuevoConsumo" name="editarConsumo" value="<?php echo $consumo["codigo"]?>" readonly>
                        <input type="hidden" id="idConsumo" name="idConsumo" value="<?php echo($consumo["id_con"])?>">
                      </div>
                    </div>
                    <!-- Entrada de Cliente -->
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-users"></i></span>

                        <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" value="<?php echo $cliente["nombre"]?>"  readonly>
                        <input type="hidden" id="seleccionarCliente" name="seleccionarCliente" value="<?php echo($cliente["id"])?>">
                      </div>
                    </div>
                    <!-- Entrada para Agregar Producto --> <!--  hidden-md hidden-sm hidden-xs -->
                    <div class="form-group row nuevoProducto" style="width: 100%; height: 130px; overflow-y: auto; overflow-x: hidden;">
                    <?php
                      $listaProductos = json_decode($consumo["productos"], true);
                      foreach ($listaProductos as $key => $value) {
                        $item = "id";
                        $valor = $value["id"];
                        $orden = "id";
                        $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
                        $stockAnterior = $respuesta["stock"] + $value["cantidad"];
                        echo '<div class="row" style="padding:5px 15px">
                                <!-- Entrada Descripcion del Producto -->
                                <div class="col-xs-6" style="padding-right:0px">
                                  <div class="input-group">
                                    <span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" idproducto="'. $value["id"] .'"><i class="fa fa-times"></i></button></span>
                                    <input type="text" class="form-control nuevaDescripcionProducto" id="agregarProducto" name="agregarProducto" idProducto="'. $value["id"] .'" value ="'. $value["descripcion"] .'" readonly required>
                                  </div>
                                </div>
                                <!-- Entrada Cantidad de Producto -->
                                <div class="col-xs-3">                        
                                   <input type="number" class="form-control nuevaCantidadProducto" id="nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="'.$value["cantidad"].'" stock="'. $stockAnterior .'" nuevoStock="'. $value["stock"] .'" required>
                                </div>
                                <!-- Entrada Precio del Producto -->
                                <div class="col-xs-3 ingresoPrecio" style="padding-left:0px">
                                    <div class="input-group">
                                      <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                                      <input type="text" class="form-control nuevoPrecioProducto" precioUnitario="'. $respuesta["precio_venta"] .'" name="nuevoPrecioProducto" value ="'. $value["total"] .'" readonly required>  
                                    </div>
                                </div>
                              </div>';
                      }
                    ?>                   
                    </div>                    
                    <input type="hidden" id="listaProductos" name="listaProductos">
                    <!-- Boton para agregar Producto pantallas pequeñas -->
                    <button type="button" class="btn btn-default btnAgregarProducto hidden-lg">Agregar Producto</button>
                    <hr>
                    <!-- Total a Pagar -->
                    <div class="row">
                      <div class="col-xs-8 pull-right">
                        <table class="table">
                          <thead>
                            <tr>
                              <th>Descuento</th>
                              <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <!-- Descuento -->
                              <td style="width:50%">
                                <div class="input-group">
                                  <input type="number" class="form-control input-lg" min="0" id="nuevoDescuentoVenta" name="nuevoDescuentoVenta" value="<?php echo $porcentajeDescuento ?>" readonly>
                                  <input type="hidden" name="nuevoPrecioDescuento" id="nuevoPrecioDescuento" value = "<?php echo $consumo["total_descuento"]?>" required>
                                  <input type="hidden" name="nuevoPrecioNeto" id="nuevoPrecioNeto" value="<?php echo $consumo["neto"]?>" required>
                                  <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                </div>
                              </td>
                              <!-- Total -->
                              <td style="width:50%">
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                                  <input type="text" class="form-control input-lg" min="1" id="nuevoTotalVenta" name="nuevoTotalVenta" value="<?php echo $consumo["total"]?>" readonly required>
                                  <input type="hidden" id="totalVenta" name="totalVenta" value="<?php echo $consumo["total"]?>">
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <hr>
                    <!-- Metodo de Pago -->                    
                    <div class="form-group row">
                      <div class="col-xs-6">
                       <div class="input-group">
                          <select class="form-control" name="nuevoMetodoPago" id="nuevoMetodoPago" required>
                            <option value="<?php echo $consumo["metodo_pago"]?>"><?php echo $consumo["metodo_pago"]?></option>
                            <?php
                              if($consumo["metodo_pago"] == "Efectivo"){
                            ?>
                                <option value="CuentaCorriente">Cuenta Corriente</option>
                            <?php
                              }else{
                            ?>
                                <option value="Efectivo">Efectivo</option>
                            <?php
                              }
                            ?>
                          <!--
                            <option value="tarjetaCredito">Tarjeta Credito</option>
                            <option value="tarjetaDebito">Tarjeta Debito</option>
                          -->
                          </select>                     
                        </div> 
                      </div>
                      <div class="cajasMetodoPago">  

                      </div>
                    </div>
                    <br>
                  </div>
                </div>
                <!-- Pie del formulario -->
                <div class="box-footer">
                  <button type="submit" class="btn btn-primary pull-right">Guardar Cambios</button>                    
                </div>
              </form>
              <?php 
              $editarConsumo = new ControladorConsumos();
              $editarConsumo -> ctrEditarConsumo();
              ?>
            </div>
          </div>
        <!-- Fin Tabla Ventas -->
      </div>
      <div class="col-lg-7 hidden-md hidden-sm hidden-xs">
        <!-- Tabla de Productos para pantallas grandes-->
        <!-- Pinta una linea amarilla -->
          <div class="box box-warning">
            <!-- Dibuja una franja en blanco  -->
            <div class="box-header with-border">
              <div class="box-body">
                <table class="table table-bordered table-striped dt-responsive tablaConsumos" > <!-- data-page-length='6'-->
                <thead>
                 <tr>
                   <th style="width: 10px">#</th>                   
                   <th>Codigo</th>
                   <th>Imagen</th>
                   <th>Descripcion</th>
                   <th>Stock</th>
                   <th>Acciones</th>
                 </tr>
                </thead>
              </table>
              </div>
            </div>
          </div>
        <!-- Fin Tabla -->
      </div>
    <!-- Fin Formulario -->
    </div>
  </section>  
</div>

