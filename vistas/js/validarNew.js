// Identificar grupos segun su ID
const grupo = {
  // Agregar
  ingNombre: "nombre",
  nuevoNombre: "nombre",
  ingUsuario: "usuario",
  nuevoUsuario: "usuario",
  ingEmail: "correo",
  nuevoEmail:  "correo",
  reIngEmail: "correoR",  
  nuevoLocal: "local",
  nuevoTelefono: "telefono",
  nuevaDireccion: "direccion",
  nuevaCategoria: "categoria",
  camPassword: "password",
  ingPassword: "password",
  nuevoPassword: "password",
  reIngPassword: "passwordR",
  reCamPassword: "passwordR",
  nuevaProducto: "producto",
  // Editar
  //editarLocal: 
  editarNombre: "nombreE",
  editarEmail:  "correoE",  
  editarTelefono: "telefonoE",  
  editarDireccion: "direccionE",
  editarCategoria: "categoriaE",
  editarPassword: "passwordE",
  editarProducto: "productoE"
}

// Campos a verificar (error = false, ok = true)
const campos = {
  nombre: false,
  usuario: false,
  correo: false,
  correoR: false,
  password: false,
  passwordR: false,
  telefono: false,
  direccion: false
}

// Expresiones Regulares
const expresiones = {
  usuario: /^[a-zA-Z0-9]+$/, // Letras y numeros.
  //       /^[a-zA-Z0-9\_\-]{4,16}$/, // Letras, numeros, guion y guion_bajo. Tamaño entre 4 y 16 caracteres.
  nombre: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/, // Letras espacios, puden llevar acento ñ. 
  //      /^[a-zA-ZÀ-ÿ\s]{1,40}$/, // Letras y espacios, pueden llevar acentos.          
  password: /^[a-zA-Z0-9]+$/, // Letras y numeros.
  //        /^.{4,12}$/, // 4 a 12 digitos.
  correo: /^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/,
  //      /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
  telefono: /^[()\-0-9 ]+$/, // Numeros, parentesis, guion.
  //        /^\d{7,14}$/ // 7 a 14 numeros.
  //        /^\(\d{3}\)\s\d{4}-\d{4}$/;
  direccion: /^[#\.\-a-zA-Z0-9 ]+$/
}

// Mensajes de Error
const mensaje = {
  error: {
    usuario: "Ingrese solo letras y numeros",
    nombre: "Ingrese solo letras espacios, tilde y ñ",
    local: "Ingrese solo letras espacios, tilde y ñ",
    categoria: "Ingrese solo letras espacios, tilde y ñ",
    password: "Ingrese solo letras y numeros",
    passwordDiferente: "Las contraseñas no coinciden" ,
    correo: "Ingrese el correo en un formato valido",
    correoR: "Reingrese el correo en un formato valido",
    correoDiferente: "Los correos no coinciden",
    telefono: "Ingrese el telefono en el formato valido",
    direccion: "Ingrese solo letras, numeros y el simbolo numeral"
  },
  vacio: {
    usuario: "Ingrese su Usuario",
    nombre: "Ingrese un Nombre",
    local: "Ingrese el nombre del Local",
    categoria: "Ingrese el nombre de la Categoria",
    password: "Ingrese el Password",
    passwordR: "Reingrese el Password",
    correo: "Ingrese un correo Electronico",
    correoR: "Reingrese el correo Electronico",
    telefono: "Ingrese el Telefono",
    direccion: "Ingrese la Direccion"
  }
}

//--------------------------------------------------------------------------------------------------
// Funcion validar formulario
const validarFormulario = (e) => { 
  //var input = e.target;
  const input = e && e.target ? e.target : e;
  switch(input.name) {
    case "ingUsuario":
    case "nuevoUsuario":
      if(!isVacio(input)){
        validarCampos(expresiones.usuario, input);
      }
      break;
    case "ingNombre":
    case "nuevoNombre":
    case "editarNombre":
      if(!isVacio(input)){
        validarCampos(expresiones.nombre, input);
      }
      break;
    case "ingPassword":
    case "nuevoPassword":
    case "editarPassword":
    case "password":                   // Agregado
      if(!isVacio(input)){        
        validarCampos(expresiones.password, input);
      } 
      break;
    case "reIngPassword":
    case "rePassword":                 // Agregado
      if(campos.password){
        if(!isVacio(input)){
          validarCampos(expresiones.password, input);
          validarPassword(input);
        }
      }
      break; 
    case "ingEmail":
    case "nuevoEmail":
    case "editarEmail":      
      if(!isVacio(input)){
          validarCampos(expresiones.correo, input);
      }
      break;
    case "reIngEmail":
      if(campos.correo){
        if(!isVacio(input)){                
          validarCampos(expresiones.correo, input);
          if(campos.correoR){
            validarCorreo(input);
          }          
        }
      }
      break;
    case "nuevoLocal":
    //case "editarLocal":
      if(!isVacio(input)){
        validarCampos(expresiones.nombre, input);
      }
      break;
    case "nuevaCategoria":
    case "editarCategoria":
      if(!isVacio(input)){
        validarCampos(expresiones.nombre, input);
      }
      break;
    case "nuevaProducto":
    case "editarProducto":
      if(!isVacio(input)){
        validarCampos(expresiones.nombre, input);
      }
      break;
    //case "ingTelefono":
    case "nuevoTelefono":
    case "editarTelefono":
      if(!isVacio(input)){          
        validarCampos(expresiones.telefono, input);
      }
      break;
    //case "ingDireccion":
    case "nuevaDireccion":
    case "editarDireccion":
      if(!isVacio(input)){
        validarCampos(expresiones.direccion, input);
      }
      break;
  }
}

// Funcion que valida Cada input
const validarCampos = (expresion, input) => {
  campo = grupo[input.id];
  if(expresion.test(input.value)){ 
    document.getElementById(campo).classList.remove('has-error');
    document.getElementById(campo).classList.add('has-success');
    limpiarMsjInput(input);
    campos[campo] = true;
  }else{
    document.getElementById(campo).classList.remove('has-success');
    document.getElementById(campo).classList.add('has-error');
    $('#'+input.id).parent().after('<span class="help-block" id="msjError_'+input.id+'"><i class="fa fa-warning "></i>  ' + mensaje.error[campo] + '</span>');
    campos[campo] = false;
  }
}

// Funcion que valida la coincidencia del Password
const validarPassword = (input) => {  
  const password = document.getElementById('ingPassword').value;
  const passwordR = document.getElementById('reIngPassword').value;
  if(password != passwordR){     
    document.getElementById('passwordR').classList.remove('has-success');
    document.getElementById('passwordR').classList.add('has-error');
    $('#reIngPassword').parent().after('<span class="help-block" id="msjError_passwordDiferente"><i class="fa fa-warning "></i>  ' + mensaje.error.passwordDiferente + '</span>');
    campos['passwordR'] = false;
  }else{      
    document.getElementById('passwordR').classList.remove('has-error');
    document.getElementById('passwordR').classList.add('has-success');
    limpiarMsjInput(input);
    campos['passwordR'] = true;
  }
}

// Funcion que valida la coincidencia de los Correos
const validarCorreo = (input) => {
  const correo = document.getElementById('ingEmail').value;
  const correoR = document.getElementById('reIngEmail').value;
  if(correo != correoR){     
    document.getElementById('correoR').classList.remove('has-success');
    document.getElementById('correoR').classList.add('has-error');
    $('#reIngEmail').parent().after('<span class="help-block" id="msjError"><i class="fa fa-warning "></i>  '+ mensaje.error.correoDiferente +'</span>'); 
    campos['correoR'] = false;
  }else{       
    document.getElementById('correoR').classList.remove('has-error');
    document.getElementById('correoR').classList.add('has-success');
    limpiarMsjInput(input);   
    campos['correoR'] = true;   
  }
}

// Funcion que valida si el campo esta vacio
const isVacio = (input) =>{
  const campo = grupo[input.id];
 if((input.value.trim()).length > 0){  
    // Si No esta Vacio el Campo   
    document.getElementById(campo).classList.remove('has-error');
    document.getElementById(campo).classList.add('has-success');
    limpiarMsjInput(input);
    campos[campo] = true;
    return false;
  }else{
    // Si esta Vacio el Campo
    document.getElementById(campo).classList.remove('has-success');
    document.getElementById(campo).classList.add('has-error');;
    $('#'+input.id).parent().after('<span class="help-block" id="msjVacio_'+ input.id +'"><i class="fa fa-warning "></i>  ' + mensaje.vacio[campo] + '</span>'); 
    campos[campo] = false;
    return true;
    }
}

// Funcion Limpiar Mensajes de los input.
const limpiarMsjInput = (input) =>{
    //
    console.log("limpiarMsjInput")
    //
    $('#msjVacio_'+input.id).remove();
    $('#msj_'+input.id).remove(); 
    $('#msjError_passwordDiferente').remove(); 
    $('#msjError_'+input.id).remove();
    $('#msjError').remove();    
    $('.help-block').remove();
    $('.alert-danger').remove();
    //$('.help-block').remove();     
}
//--------------------------------------------------------------------------------------------------



// Localizo el formulario a Validar
var form = document.getElementsByClassName('needs-validation');
// Si no existe formulario no hace nada
//
//console.log("form = ", form)
//

if (form[0] !== undefined){
  
  var formulario = form[0]; 
  //
  console.log("formulario.id = ", formulario['id'])
  //

// Arreglo con todos los inputs del formulario
  var inputs = document.querySelectorAll(`#${formulario['id']} input`);

/*

  inputs.forEach((input) => {
    
    input.addEventListener('blur', validarFormulario);

  });
*/
  switch(formulario['id']){
    case "formLogin":          
      console.log('aca verificamos los campos del fomrulario de Login');         
      break;
    case "formRecupera":          
      console.log('aca verificamos los campos del fomrulario recuperar Contraseña');         
      break;
    case "formCambiarPass":          
      console.log('aca verificamos los campos del fomrulario Cambiar Contraseña');         
      break;
    case "formRegistro":          
      console.log('aca verificamos los campos del fomrulario Solicitar Registro');         
      break;
    };
} else{

  $(document).on("click", ".btnModal",function(e){ 
    modal = "form" + $(this).attr("data-target").substr(6); 
    
    formulario = document.getElementById(modal);
    inputs = document.querySelectorAll(`#${modal} input`);

    
    //console.log("formulario = ", formulario);
    //console.log("inputs = ", inputs);

    switch(formulario['id']){
    case "formEditarCliente":          
      console.log('aca verificamos los campos del fomrulario de Editar Cliente');         
      break;
    case "formAgregarCliente":          
      console.log('aca verificamos los campos del fomrulario Agregar Cliente');         
      break;
    case "formEditarLocal":          
      console.log('aca verificamos los campos del fomrulario Editar Local');         
      break;
    case "formAgregarUsuario":          
      console.log('aca verificamos los campos del fomrulario Agregar Usuario Administrador');         
      break;
    case "formEditarUsuario":          
      console.log('aca verificamos los campos del fomrulario Editar Usuario Administrador');         
      break;
    
    case "formAgregarCategoria":          
      console.log('aca verificamos los campos del fomrulario Agregar Categoria de Producto');         
      break;
    case "formEditarCategoria":          
      console.log('aca verificamos los campos del fomrulario Editar Categoria de Productos');         
      break;
    case "formEditarProducto":          
      console.log('aca verificamos los campos del fomrulario Editar Producto');         
      break;
    case "formAgregarProducto":          
      console.log('aca verificamos los campos del fomrulario Agregar Nuevo Producto');         
      break;
    };

  });

}
