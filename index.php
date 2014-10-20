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
		   			<td><a id='eli' href='?clid=".$result['id']."'>Eliminar</a></td>
		 		</tr>";
		}
	}
	function nuevoCliente(){
		$nombre = $_POST['boxNombre'];
					$numero = $_POST['boxNumero'];
					$email = $_POST['boxEmail'];
		$cl = MyActiveRecord::Create('Cliente', array('nombre_clie'=>$nombre, 'numero_clie'=>$numero, 'email_clie'=>$email));
		$cl->save();
	}
	function eliminarCliente($id){
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
		   			<td><a id='eli' href='?prid=".$result['id']."'>Eliminar</a></td>
		 		</tr>";
		}
	}
	function nuevoProducto(){
		$pr = MyActiveRecord::Create('Producto', 
			array('nombre_prod'=>''.$_POST["boxNombre"].'', 
				  'detalle_prod'=>''.$_POST["boxDetalle"].''));
		$pr->save();
	}
	function eliminarProducto($id){
		$sql = "
			DELETE * FROM producto
			INNER JOIN cliente
			ON factura.id_clie = cliente.id 
			INNER JOIN producto
			on factura.id_prod = producto.id
			WHERE factura.id = '".$id."'";
		MyActiveRecord::Query($sql);

		//$del = MyActiveRecord::FindById('producto', $id);
		/*if(!$del){
			echo 'No existe ';
		}else{
			$del->destroy($id);
			return $del;
		}*/
		
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
	function nuevaFactura(){
		$pr = MyActiveRecord::Create('Factura', 
			/*array('id_clie'=>''.$_POST["boxNombre"].'', 
				  'id_prod'=>''.$_POST["boxDetalle"].''));*/
		array('id_clie'=>'2', 
				  'id_prod'=>'1'));
		$pr->save();
	}
	function eliminarFactura($id){
		$del = MyActiveRecord::FindById('factura', $id);
		if(!$del){
			echo 'No existe ';
		}else{
			$del->destroy($id);
			return $del;
		}
		
	}
}
//INSTANCE OBJECTS
$data_cliente = new Cliente();
$data_producto = new Producto();
$data_factura = new Factura();
//DELETE REGISTERS
if(isset($_GET['clid'])) {
	$id = $_GET['clid'];
	if(!$id){
		echo 'No existe ';
	}else{
		$data_cliente->eliminarCliente($id);	
		header('Location: index.php');
	}
}
if(isset($_GET['prid'])) {
	$id = $_GET['prid'];
	if(!$id){
		echo 'No existe ';
	}else{
		$data_producto->eliminarProducto($id);	
		//header('Location: index.php');
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
			<script src="js/functions.js"></script>
	</head>
	<body>
		<?php 
		if(isset($_POST['btnGuardarClie'])){
			//CREATE REGISTERS
			$data_cliente->nuevoCliente();
			//header('Location: index.php');
		}?>
		<div class="frm"></div>
		<div class="clientes">
			<h1 class="titulo">Listado de clientes</h1>
			<div class="nuevoCliente btn btn-primary"><i class="glyphicon glyphicon-plus"></i></div>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="success">ID.</th>
					<th class="success">NOMBRE.</th>
					<th class="success">NUMERO.</th>
					<th class="success">CORREO.</th>
					<th class="success">ACCION</th>
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
			if(isset($_POST['btnGuardarPro'])){
				//CREATE REGISTERS
				$data_producto->nuevoProducto();
				//header('Location: index.php');
			}?>
			<h1 class="titulo">Listado de productos</h1>
			<div class="nuevoProducto btn btn-primary"><i class="glyphicon glyphicon-plus"></i></div>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="success">ID.</th>
					<th class="success">NOMBRE.</th>
					<th class="success">DETALLE.</th>
					<th class="success">ACCION.</th>
				</tr>
				<?php $data_producto->mostrarDatos(); ?>
			</table>
		</div>
		<hr>
		<div class="factura">
			<h1 class="titulo">Listado de factura</h1>
			<!--<div class="nuevoFactura btn btn-primary"><i class="glyphicon glyphicon-plus"></i></div>-->
			<?php
				//$data_factura->nuevaFactura();
				//$data_factura->eliminarFactura(10);
			?>
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
