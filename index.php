<?php

define('MYACTIVERECORD_CONNECTION_STR', 'mysql://root:@localhost/myactpue');
include 'MyActiveRecord.0.4.php';
//QUERY TABLES
$cliente_sql = "
	CREATE TABLE cliente(
		id INT(11) PRIMARY KEY AUTO_INCREMENT,
		nombre_clie VARCHAR(100) NOT NULL,
		numero_clie VARCHAR(15) NOT NULL,
		email_clie VARCHAR(250) NOT NULL
		)
";
$producto_sql = "
	CREATE TABLE producto(
		id INT(11) PRIMARY KEY AUTO_INCREMENT,
		nombre_prod VARCHAR(100) NOT NULL,
		detalle_prod VARCHAR(150)
		)
";
$cliente_producto_sql = "
	CREATE TABLE cliente_producto(
		cliente_id INT(11) NOT NULL,
		producto_id INT(11) NOT NULL,
		KEY cliente_id(cliente_id),
		KEY producto_id(producto_id)
		)
";
$factura_sql = "
		CREATE TABLE factura(
			id INT AUTO_INCREMENT PRIMARY KEY,
			id_clie INT(11) NOT NULL,
			id_prod INT(11) NOT NULL,
						
			FOREIGN KEY(id_clie) REFERENCES cliente(id),
			FOREIGN KEY(id_prod) REFERENCES producto(id)
			
			)
";

//CREATE TABLES
if(!MyActiveRecord::TableExists('cliente')){
	MyActiveRecord::Query($cliente_sql);
	print "Tabla cliente creada con exito <br>";
}
if(!MyActiveRecord::TableExists('producto')){
	MyActiveRecord::Query($producto_sql);
	print "Tabla producto creada con exito <br>";
}
if(!MyActiveRecord::TableExists('factura')){
	MyActiveRecord::Query($factura_sql);
	print "Tabla factura creada con exito <br>";
}
if(!MyActiveRecord::TableExists('cliente_producto')){
	MyActiveRecord::Query($cliente_producto_sql);
	print "Tabla cliente_producto creada con exito <br>";
}

//CLASS TABLES - CLIENTE
class Cliente extends MyActiveRecord{
	function mostrarDatos(){
		$consulta_sql = "SELECT * FROM cliente";
		$query = MyActiveRecord::Query($consulta_sql);

		while ($result = mysql_fetch_assoc($query)) {
		   echo "<tr>
		   			<td>".$result['id']."</td>
		   			<td>".$result['nombre_clie']."</td>
		   			<td>".$result['numero_clie']."</td>
		   			<td>".$result['email_clie']."</td>
		   			<td><a id='eli' href='?id=".$result['id']."'>Eliminar</a></td>
		 		</tr>";
		}
	}
	function nuevoRegistro(){
		$nombre = $_POST['boxNombre'];
					$numero = $_POST['boxNumero'];
					$email = $_POST['boxEmail'];
		$cl = MyActiveRecord::Create('Cliente', array('nombre_clie'=>$nombre, 'numero_clie'=>$numero, 'email_clie'=>$email));
		$cl->save();
	}
	function eliminarRegistro($id){
		$del = MyActiveRecord::FindById('cliente', $id);
		if(!$del){
			echo 'No existe ';
		}else{
			$del->destroy($id);
			return $del;
		}
		
	}
}
//CLASS TABLES - PRODUCTO
class Producto extends MyActiveRecord
{
	function mostrarDatos(){
		$consulta_sql = "SELECT * FROM producto";
		$query = MyActiveRecord::Query($consulta_sql);

		while($result = mysql_fetch_array($query)){
			echo "<tr>
		   			<td>".$result['id']."</td>
		   			<td>".$result['nombre_prod']."</td>
		   			<td>".$result['detalle_prod']."</td>
		 		</tr>";
		}
	}
}
//CLASS TABLES - FACTURA
class Factura extends MyActiveRecord{
	function mostrarDatos(){
		$sql = "
			SELECT factura.id id_factura, factura.id_clie id_cliente, factura.id_prod id_producto, cliente.nombre_clie nombre_cliente,
			cliente.numero_clie numero_cliente, cliente.email_clie email_cliente, producto.nombre_prod nombre_producto, producto.detalle_prod detalle_producto
			FROM factura
			INNER JOIN cliente
			ON factura.id_clie = cliente.id 
			INNER JOIN producto
			on factura.id_prod = producto.id
		";
		$query = MyActiveRecord::Query($sql);
		while($result = mysql_fetch_array($query)){
			echo "<tr>
		   			<td>".$result['id_factura']."</td>
		   			<td>".$result['id_cliente']."</td>
		   			<td>".$result['nombre_cliente']."</td>
		   			<td>".$result['numero_cliente']."</td>
		   			<td>".$result['email_cliente']."</td>
		   			<td>".$result['nombre_producto']."</td>
		   			<td>".$result['detalle_producto']."</td>
		 		</tr>";
		}
	}
}
//INSTANCE OBJECTS
$data_cliente = new Cliente();
$data_producto = new Producto();
$data_factura = new Factura();
//DELETE REGISTERS
if(isset($_GET['id'])) {
	$id = $_GET['id'];
	if(!$id){
		echo 'No existe ';
	}else{
		$data_cliente->eliminarRegistro($id);	
		header('Location: index.php');
	}
}
?>
<!DOCTYPE html>
	<html lang="es">
	<head>
			<meta charset="utf-8">
			<meta name="description" content="">
			<meta name="keywords" content="">
			<meta name="" content="">
			<title>POO PHP</title>
			<link rel="stylesheet" href="css/bootstrap.css">
			<link rel="stylesheet" href="css/style.css">
			<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
			<script src="js/bootstrap.js"></script>
			<script>
			$(function(){
				$('.nuevoCliente').on('click', function(){
					var formulario = '<div class="frmNuevo"> '+
								'<div class="message"></div> '+ 
								'<div class="form-group"> '+ 
									'<form action="index.php" method="post" name="frmCliente"> '+
										'<input type="text" placeholder="Nombre" class="form-control txtNombre" name="boxNombre">'+
										'<input type="text" placeholder="Numero" class="form-control txtNumero" name="boxNumero">'+
										'<input type="email" placeholder="Correo" class="form-control txtEmail" name="boxEmail">'+
										'<input type="submit" class="btn btn-info btnGuardar" value="Guardar" name="btnGuardar">'+
										'<button class="btnCancelar btn btn-warning">Cancelar</button>'+
									'</form>'+
								'</div>'+
							'</div>';
					//Remove form
					$('.clientes').append(formulario);
					//validation
					if(!$('.frmNuevo').length == 0){
						$('.btnGuardar').on('click', function(){
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
					if($('.btnCancelar').length == 0){
						$('.btnCancelar').on('click',function(){
							$('.clientes').remove();
							$('btnCancelar').off('click');
						});
					}
					
				});
			});

			</script>
	</head>
	<body>
		<?php 
		if(isset($_POST['btnGuardar'])){
			//CREATE REGISTERS
			$cl = MyActiveRecord::Create('Cliente', 
				array('nombre_clie'=>''.$_POST["boxNombre"].'', 
					  'numero_clie'=>''.$_POST["boxNumero"].'',
					  'email_clie'=>''.$_POST["boxEmail"].''));
			$cl->save();
			header('Location: index.php');
			if(!$cl->save() == true){
				echo 'PROBLEMAS AL GUARDAR LOS DATOS';
			}
		}?>
		<div class="clientes">
			<h1 class="titulo">Listado de clientes</h1>
			<div class="nuevoCliente btn btn-primary"><i class="glyphicon glyphicon-plus"></i></div>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="success">ID.</th>
					<th class="success">NOMBRE.</th>
					<th class="success">NUMERO.</th>
					<th class="success">CORREO.</th>
					<th class="success">--.</th>
				</tr>
				<tr>
					<?php 
					$data_cliente->mostrarDatos();
					?>
				</tr>
			</table>
		</div>
		<hr>
		<div class="productos">
			<?php 
			if(isset($_POST['btnGuardar'])){
				//CREATE REGISTERS
				$cl = MyActiveRecord::Create('Cliente', 
					array('nombre_clie'=>''.$_POST["boxNombre"].'', 
						  'numero_clie'=>''.$_POST["boxNumero"].'',
						  'email_clie'=>''.$_POST["boxEmail"].''));
				$cl->save();
				header('Location: index.php');
				if(!$cl->save() == true){
					echo 'PROBLEMAS AL GUARDAR LOS DATOS';
				}
			}?>
			<h1 class="titulo">Listado de productos</h1>
			<div class="nuevoProducto btn btn-primary"><i class="glyphicon glyphicon-plus"></i></div>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="success">ID.</th>
					<th class="success">NOMBRE.</th>
					<th class="success">DETALLE.</th>
				</tr>
				<?php $data_producto->mostrarDatos(); ?>
			</table>
		</div>
		<hr>
		<div class="factura">
			<h1 class="titulo">Listado de factura</h1>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="success">NO. FAC</th>
					<th class="success">NO. CLIENTE</th>
					<th class="success">CLIENTE</th>
					<th class="success">NUMERO</th>
					<th class="success">CORREO</th>
					<th class="success">PRODUCTO</th>
					<th class="success">DETALLE PROD.</th>
				</tr>
				<tr>
					<?php $data_factura->mostrarDatos(); ?>
				</tr>
			</table>
		</div>
	</body>
</html>
