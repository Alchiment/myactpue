$(function(){
	function btnCancelar(){
			if($('.btnCancelar').length == 0){
			$('.btnCancelar').on('click',function(){
				$('.clientes').remove();
				$('btnCancelar').off('click');
			});
		}
		
	}
//btn cliente
	$('.nuevoCliente').on('click', function(){
		var formulario = '<div class="frmNuevo"> '+
					'<div class="message"></div> '+ 
					'<div class="form-group"> '+ 
						'<form action="index.php" method="post" name="frmCliente"> '+
							'<input type="text" placeholder="Nombre" class="form-control txtNombre" name="boxNombre">'+
							'<input type="text" placeholder="Numero" class="form-control txtNumero" name="boxNumero">'+
							'<input type="email" placeholder="Correo" class="form-control txtEmail" name="boxEmail">'+
							'<input type="submit" class="btn btn-info btnGuardarClie" value="Guardar" name="btnGuardarClie">'+
							'<button class="btnCancelar btn btn-warning">Cancelar</button>'+
						'</form>'+
					'</div>'+
				'</div>';
		//Remove form
		$('.frm').append(formulario);
		$('body').scrollTop(0);
		//validation
		if(!$('.frmNuevo').length == 0){
			$('.btnGuardarClie').on('click', function(){
				if($('.txtNombre').val() == "" && $('.txtNumero').val() == '' && $('.txtEmail').val() == ''){
					$('.message').html('<h3 class="text-center" style="color:red;">Debe llenar todos los campos</h3>');
					return false;
				}else if($('.txtNombre').val() == "" || $('.txtNumero').val() == '' || $('.txtEmail').val() == '')
				{
					$('.message').html('<h3 class="text-center" style="color:red;">Faltan algunos campos por llenar</h3>');
					return false;
				}else{
					return true;
				}
			});
		}
		btnCancelar();
	});
//btn cliente
	$('.nuevoProducto').on('click', function(){
		var formulario = '<div class="frmNuevo"> '+
					'<div class="message"></div> '+ 
					'<div class="form-group"> '+ 
						'<form action="index.php" method="post" name="frmCliente"> '+
							'<input type="text" placeholder="Producto" class="form-control txtNombre" name="boxNombre">'+
							'<input type="text" placeholder="Detalle" class="form-control txtDetalle" name="boxDetalle">'+
							'<input type="submit" class="btn btn-info btnGuardarPro" value="Guardar" name="btnGuardarPro">'+
							'<button class="btnCancelar btn btn-warning">Cancelar</button>'+
						'</form>'+
					'</div>'+
				'</div>';
		//Remove form
		$('.frm').append(formulario);
		$('body').scrollTop(0);
		//validation
		if(!$('.frmNuevo').length == 0){
			$('.btnGuardarPro').on('click', function(){
				if($('.txtNombre').val() == "" && $('.txtDetalle').val() == ''){
					$('.message').html('<h3 class="text-center" style="color:red;">Debe llenar todos los campos</h3>');
					return false;
				}else if($('.txtNombre').val() == "" || $('.txtDetalle').val() == '')
				{
					$('.message').html('<h3 class="text-center" style="color:red;">Faltan algunos campos por llenar</h3>');
					return false;
				}else{
					return true;
				}
			});
		}
		btnCancelar();
	});
//btnFactura
	/*$('.nuevoFactura').on('click',function(){
		var formulario = '<div class="frmNuevo"> '+
					'<div class="message"></div> '+ 
					'<div class="form-group"> '+ 
						'<form action="index.php" method="post" name="frmCliente"> '+
							'<select>'+
							'</select>'+
							'<input type="submit" class="btn btn-info btnGuardarPro" value="Guardar" name="btnGuardarPro">'+
							'<button class="btnCancelar btn btn-warning">Cancelar</button>'+
						'</form>'+
					'</div>'+
				'</div>';
		//Remove form
		$('.frm').append(formulario);
		$('body').scrollTop(0);
	});*/
});
