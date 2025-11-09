<?php 

require_once __DIR__ . '/usuarios.controlador.php';
require_once __DIR__ . '/../modelos/usuarios.modelo.php'; // si el modelo también se usa

class ControladorClientes extends ControladorUsuarios{

	// Mostrar los Usuarios
	static public function ctrMostrarUsuarios($item, $valor){
		$respuesta = ModeloClientes::mdlMostrarClientes($item, $valor);
		return $respuesta;
	}
	// Crear Cliente
	static public function ctrCrearCliente(){
		if(isset($_POST["nuevoUsuario"])){		
			$nombre = ucwords(strtolower($_POST['nuevoNombre']));
			//$apellido = $_POST["nuevoApellido"];
			$usuario = ucwords(strtolower($_POST['nuevoUsuario']));							
			$encriptar = "";
			$correo = strtolower($_POST['nuevoEmail']);
			$telefono =  $_POST["nuevoTelefono"];
			$local = $_POST["nuevoLocal"];
			// Para saber si envio el Correo electronico o no
			$envio = false;
			$correcto = true;
			if(isNombre($nombre) && 
				isUsuario($usuario)){
				// Evitar usuario repetido
				if(!usuarioExiste($usuario)){					
					// Verifico si el telefono viene vacio	
					if(!isNull($telefono)){
						if(!isTelefono($telefono)){
							$correcto = false;
							echo("	<script>
										swal({
											type: 'error',
											title: '¡El telefono no puede llevar caracteres especiales!',
											showConfirmButton: true,						
									  		confirmButtonText: 'Cerrar'
											})
											.then((result) => {
											  if (result.value) {
											    window.location = 'clientes';
											  }
										});						
									</script>");
						}
					}					
					// Fin Telefono
					// Si viene el correo pregunto si es correcto caso contrario continuo
					if(!isNull($correo)){
						if(!isEmail($correo)){
							// Si no es Correcto el Correo
							$correcto = false;
							echo("	<script>
										swal({
											type: 'error',
											title: 'El mail es incorrecto!',
											showConfirmButton: true,						
									  		confirmButtonText: 'Cerrar'
											})
											.then((result) => {
											  if (result.value) {
											    window.location = 'clientes';
											  }
										});						
									</script>");
							if(emailExiste($correo)){
								$correcto = false;
								echo("	<script>
										swal({
											type: 'error',
											title: '¡El correo ".explode("@", $correo, 2)[0]." ya existe en el Sistema!',
											showConfirmButton: true,						
									  		confirmButtonText: 'Cerrar'
											})
											.then((result) => {
											  if (result.value) {
											    window.location = 'clientes';
											  }
										});						
									</script>");
							}else{
								$envio = true;
								$password = generaPassword();					
								$encriptar = cifrarPassword($password);
							}
							// Fin Correo repetido
						}
						// Fin Correo Incorrecto																	
					}				
					// Fin Correo
					if($correcto){
						// Sugerir Local Nuevo					
						if($local == "agregarlocal"){
							$idLocal = 0; // Local no definido.					
							$nombreLocal = $_POST["nuevoNombreLocal"];
							$telefonoLocal = -1; // bandera para saber que es una sugerencia de local.
							//Verifico que el nombre sea correcto
							if(isNombre($nombreLocal)){							
								// Si es correcto verifico que no exista en la tabla locales
								if(!localSugeridoExiste($nombreLocal)){
									/// Si no existe guardo en la tabla locales
									$datosLocal = array("nombre"    => $nombreLocal,
														"telefono"  => $telefonoLocal);
									$idLocal = ModeloLocales::mdlSugerirLocal($datosLocal);
								}else{
									// Busco el Id del local
									$item = "nombre";
						            $valor =$nombreLocal;
						            $local = ControladorLocales:: ctrMostrarLocalesSugerido($item, $valor);
						            $idLocal = $local["id_local"];
								}
							}

						}else{
							$idLocal = $local;
						}
						// Fin Sugerir Local
						// Regristrar				
						$tipo_usuario = "Cliente";					
						$pendiente = 2;
						$token =  generaToken();
						$datos = array("nombre"   => $nombre, 
									   "usuario"  => $usuario,
									   "correo"	  => $correo,
									   "telefono" => $telefono,
									   "local"	  => $idLocal,
									   "password" => $encriptar, 
									   "estado"   => $pendiente,
									   "token"	  => $token,
									   "perfil"   => $tipo_usuario);

						$respuesta = ModeloClientes::mdlCrearCliente($datos);								
						if($respuesta){
							// Si se ingreso Correo enviar notificacion
							if($envio){
								// Envio el mail.
								// Obtener id Usuario
								$item = "usuario";
						        $valor =$usuario;
					            $cliente = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);	
						        $idUsuario = $cliente[0]["id"];	
								// Fin obtener
								$url = 'http://'.$_SERVER["SERVER_NAME"].'/willycafe/index.php?ruta=activar&idUsuario='.$idUsuario.'&token='.$token;
								$asunto = 'Alta de Cliente - Willy Cafe';               
				                $cuerpo = "Estimado $nombre: <br/><br/>Para completar el proceso de registro, haga click en la siguiente enlace <a href='$url'>Activar Cuenta</a><br/><br/>Usuario: ".$usuario."<br/>Password: ".$password;
				                if(!enviarEmail($correo, $nombre, $asunto, $cuerpo)){
				                	echo '<script>
										swal({
											type: "success",
											title: "El Cliente '.$nombre.' se guardo Correctamente!<br />Error al enviar Correo",
											showConfirmButton : true,
											confirmButtonText: "Cerrar",					
											}).then((result)=>{
													if(result.value){
														window.location = "clientes";
													}
												})
								      	</script>';                    
				                }
							}
							echo '<script>
										swal({
											type: "success",
											title: "El Cliente '.$nombre.' se guardo correctamente!!",
											showConfirmButton : true,
											confirmButtonText: "Cerrar",					
											}).then((result)=>{
													if(result.value){
														window.location = "clientes";
													}
												})
								   </script>';	
						}						
						// Fin Registrar
					}
				}else{
					echo '<script>
					swal({
						type: "error",
						title: "¡El nombre de Usuario '.$usuario.' ya se encuentra en uso!",
						showConfirmButton: true,						
						confirmButtonText: "Cerrar"
						}).then(function(result){
							if(result.value){						
								window.location = "clientes";
							}
						});	
					</script>';	
				}
				// Fin usuario repetido
			}else{				
				echo '<script>
				swal({
					type: "error",
					title: "¡Los Nombre y Usuario no pueden ir vacío o llevar caracteres especiales!",
					showConfirmButton: true,						
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){						
							window.location = "clientes";
						}
					});	
				</script>';	
			}
		}
	}
	// Editar Cliente
	static public function ctrEditarCliente(){
		if(isset($_POST["editarUsuario"])){		
			$nombre = ucwords(strtolower($_POST['editarNombre']));
			//$apellido = $_POST["editarApellido"];
			$usuario = $_POST['editarUsuario'];
			$encriptar = $_POST['passwordActual'];
			$telefono =  $_POST["editarTelefono"];
			$local = $_POST["editarLocal"];
			$localAnterior = $_POST["localActual"];
			$token = $_POST["tokenActual"];
			// Para saber si envio el Correo electronico o no
			$envio = false;
			$correcto = true;				
			if(isNombre($nombre)){					
				// Si viene el telefono pregunto si es correcto caso contrario continuo	
				if(!isNull($telefono)){
					if(!isTelefono($telefono)){
						$correcto = false;
						echo '	<script>
									swal({
										type: "error",
										title: "¡El Telefono no pueden llevar caracteres especiales!",
										showConfirmButton: true,						
										confirmButtonText: "Cerrar"
										}).then(function(result){
											if(result.value){						
												window.location = "clientes";
											}
										});	
								</script>';	
					}				
				}
				// Fin Telefono
				// Comprobar si viene un Mail nuevo
				if(isset($_POST["nuevoEmail"])){
					$correo = strtolower($_POST['nuevoEmail']);
					// Si viene el correo pregunto si es correcto caso contrario continuo
					if(!isNull($correo)){
						if(!isEmail($correo)){
						$correcto = false;
						echo '	<script>
									swal({
										type: "error",
										title: "¡El Correo no puede llevar caracteres especiales!",
										showConfirmButton: true,						
										confirmButtonText: "Cerrar"
										}).then(function(result){
											if(result.value){						
												window.location = "clientes";
											}
										});											
								</script>';								
						}
						// Si es correcto Verifico que no se repita
						if(emailExiste($correo)){
							$correcto = false;
							echo '<script>
									swal({
										type: "error",
										title: "¡El correo '.explode('@', $correo, 2)[0].' ya existe en el Sistema!",
										showConfirmButton: true,						
										confirmButtonText: "Cerrar"
										}).then(function(result){
											if(result.value){						
												window.location = "clientes";
											}
										});	
									</script>';
							// Fin Correo repetido			
						}else{
							$envio = true;
							$password = generaPassword();
							$encriptar = cifrarPassword($password);
							$token =  generaToken();
						}					
					}				
					// Fin Correo
				}else{
					$correo = $_POST['editarEmail'];
				}
				// Fin nuevoEmail					
				if($correcto){
					// Sugerir Local Nuevo					
					if($local == "agregarlocal"){
						$idLocal = 0; // Local no definido.					
						$nombreLocal = $_POST["editarNombreLocal"];
						$telefonoLocal = -1; // bandera para saber que es una sugerencia de local.
						//Verifico que el nombre sea correcto
						if(isNombre($nombreLocal)){							
							// Si es correcto verifico que no exista en la tabla locales
							if(!localSugeridoExiste($nombreLocal)){
								/// Si no existe guardo en la tabla locales
								$datosLocal = array("nombre"    => $nombreLocal,
													"telefono"  => $telefonoLocal);
								$idLocal = ModeloLocales::mdlSugerirLocal($datosLocal);
							}else{
								// Busco el Id del local
								$item = "nombre";
					            $valor =$nombreLocal;
					            $local = ControladorLocales:: ctrMostrarLocalesSugerido($item, $valor);
					            $idLocal = $local["id_local"];
							}
						}
					}else{
						$idLocal = $local;
					}
					// Fin Sugerir Local
					// Si cambio de local y el anterior es un sugerido lo elimino. Siempre y cuando no halla mas usuarios asignado a ese local
					if($idLocal != $localAnterior){
						// Busco el local y verifico que siga siendo sugerido (telefono = -1)
			            $borrarLocal = ModeloLocales:: mdlBorrarLocalSugerido($localAnterior);
			        }
					// Fin eliminar sugerido
					// Editar
					$datos = array("nombre"   => $nombre, 
								   "usuario"  => $usuario,
								   "correo"	  => $correo,
								   "telefono" => $telefono,
								   "local"	  => $idLocal,
								   "password" => $encriptar,
								   "token"	  => $token);
					$respuesta = ModeloClientes::mdlEditarCliente($datos);					
					if($respuesta){
						// Si se ingreso Correo enviar notificacion
						if($envio){
							// Envio el mail.
							// Obtener id Usuario
							$item = "usuario";
					        $valor =$usuario;
				            $cliente = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);	
					        $idUsuario = $cliente[0]["id"];	
							// Fin obtener
							$url = 'http://'.$_SERVER["SERVER_NAME"].'/willycafe/index.php?ruta=activar&idUsuario='.$idUsuario.'&token='.$token;
							$asunto = 'Alta de Cliente - Willy Cafe';               
			                $cuerpo = "Estimado $nombre: <br/><br/>Para completar el proceso de registro, haga click en la siguiente enlace <a href='$url'>Activar Cuenta</a><br/><br/>Usuario: ".$usuario."<br/>Password: ".$password;
			                if(!enviarEmail($correo, $nombre, $asunto, $cuerpo)){
			                	echo '<script>
									swal({
										type: "success",
										title: "El Cliente '.$nombre.' se guardo Correctamente!<br />Error al enviar Correo",
										showConfirmButton : true,
										confirmButtonText: "Cerrar",					
										}).then((result)=>{
												if(result.value){
													window.location = "clientes";
												}
											})
							      	</script>';                    
			                }
						}
						echo '<script>
									swal({
										type: "success",
										title: "El Cliente '.$nombre.' se guardo correctamente!!",
										showConfirmButton : true,
										confirmButtonText: "Cerrar",					
										}).then((result)=>{
												if(result.value){
													window.location = "clientes";
												}
											})
							   </script>';	
					}					
					// Fin Editar
				}										
			}else{				
				echo '<script>
				swal({
					type: "error",
					title: "¡Los Nombre y Usuario no pueden ir vacío o llevar caracteres especiales!",
					showConfirmButton: true,						
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){						
							window.location = "clientes";
						}
					});	
				</script>';	
			}
		}	
	}
	// Completar Registro
	static public function ctrCompletarRegistro(){
		$errores = 0; 	// Cuenta los errores del formulario
		if(!empty($_POST)){
			$idUsuario = $_POST["idUsuario"];
			$usuario = $_POST["usuario"];
			$apellido = ucwords(strtolower($_POST["ingApellido"]));
			$telefono =  $_POST["ingTelefono"];
			$fechaNacimiento = $_POST["ingFechaNacimiento"];
			$foto = $_FILES["ingFoto"]["tmp_name"];
			$ruta = "";
			// Para Crear la cuenta
			$compras = $descuento = $saldoActual = $saldoAnterior = 0;
			$ultimaCompra = "0000-00-00 00:00:00";
			if(!isApellido($apellido)){
				$errores++;
				echo '<br><div class="alert alert-danger">El Apellido no puede ir vacío o llevar caracteres especiales</div>';
			}else{
				if(!isTelefono($telefono)){
					$errores++;
					echo '<br><div class="alert alert-danger">Ingrese un telefono valido!</div>';
				}else{
					if (!isFechaValida($fechaNacimiento)) {
						$errores++;
						echo '<br><div class="alert alert-danger">La fecha no es Validad</div>';
					}else{
						if (!isMayor($fechaNacimiento)) {
							$errores++;
							echo '<br><div class="alert alert-danger">Debe ser mayor de 18 años!</div>';
						}
					}
				}
			}
			// Validar Foto
			if(isset($foto)){
				// Para redimensionar la foto
				list($ancho, $alto) = getimagesize($foto);
				$nuevoAncho = 500;
				$nuevoAlto = 500;
				// Creamos el directorio donde guardaremos la foto
				$directorio = "vistas/img/usuarios/". $usuario;
				mkdir($directorio, 0755);
				$rand = mt_rand(100, 999);	// nombre del archivo
				// Guardo segun la imagen sea JPG o PNG 
				if($_FILES["ingFoto"]["type"] == "image/jpeg"){						
					$ruta = "vistas/img/usuarios/" . $usuario . "/".$rand . ".jpg";
					$origen = imagecreatefromjpeg($foto);
					$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
					imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
					imagejpeg($destino, $ruta);
					}
				if($_FILES["ingFoto"]["type"] == "image/png"){
					$ruta = "vistas/img/usuarios/" . $usuario . "/".$rand . ".png";
					$origen = imagecreatefrompng($foto);
					$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
					imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
					imagepng($destino, $ruta);						
				}
			}
			if ($errores == 0) {
				$estado = 4; // Estado registro Finalizado.
				// Enviar datos al modelo
				$datos = array("id" 				=> $idUsuario,
							   "usuario" 			=> $usuario, 	
							   "apellido" 			=> $apellido,
							   "telefono"			=> $telefono,								   
							   "estado"				=> $estado,
							   "fechaNacimiento"	=> $fechaNacimiento,								   
						   	   "foto" 				=> $ruta);
				$respuesta = ModeloUsuarios::mdlCompletarRegistro($datos);
				if($respuesta){
					$datosCuenta = array("id" 				=> $idUsuario,
									     "descuento" 		=> $descuento, 	
									     "compras" 			=> $compras,
									     "ultimaCompra"		=> $ultimaCompra,								   
									     "saldoActual"		=> $saldoActual,
									     "saldoAnterior"	=> $saldoAnterior);
					$respuesta = ModeloCuentas::mdlCrearCuenta($datosCuenta);
					// Variables de Sesion
					$_SESSION["iniciarSesion"] = true;
					//$_SESSION["id"] = $respuesta["id"];
					$_SESSION["apellido"] = $apellido;
					$_SESSION["usuario"] = $usuario;
					$_SESSION["foto"] = $ruta;
					// Fecha ultimo inicio de session
					$_SESSION["sesion"] = "Primera Vez";	
					// Fin Variables de Sesion
					echo '<script>window.location = "inicio";</script>';
				}else{
					echo '<br><div class="alert alert-danger">Error al Completar el Registro</div>';
				}				
			}
		}
	}
}