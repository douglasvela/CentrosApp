<?php
$funcion = $_POST['funcion'];
$id_empleado = $_POST['nr']; 
switch($funcion) {
        case 'reporte': 
            echo reporte_info_empleado($id_empleado);
            break;
    }
	

function reporte_info_empleado($id_empleado){

      $conexion = mysqli_connect("162.241.252.245","proyedk4_WPZF0","MAYO_nesa94","proyedk4_WPZF0"); 
      
     $cabecera_vista = '<table style="font-size: 14;" class="table"><tr> 
		<td width="950px"><h6><center>MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br> DEPARTAMENTO DE RECURSOS HUMANOS <br>  BENEFICIOS POR EMPLEADO</center><h6></td>
	 	</tr></table>';
	 	$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
	 	//$pie = 'Usuario: '.$this->session->userdata('usuario_viatico').'    Fecha y Hora Creacion: '.$fecha.'||{PAGENO} de {nbpg} páginas';

	 	//$data = array('nr'=>'2588');
		//$empleado_NR_viatico = $this->Reportes_viaticos_model->obtenerNREmpleadoViatico($data);
		$query_consulta_nr=mysqli_query($conexion,"select * from org_usuario where nr='".$id_empleado."'");
      	while( $fila_nombre=mysqli_fetch_array($query_consulta_nr)){
            $empleado_nombre[] = $fila_nombre;
         }
		foreach ($empleado_nombre as $key_nombre) {	}

		$query_consulta_id_emp=mysqli_query($conexion,"select id_empleado from sir_empleado where nr='".$id_empleado."'");
      	while( $fila_id=mysqli_fetch_array($query_consulta_id_emp)){
            $empleado_id[] = $fila_id;
         }
		foreach ($empleado_id as $empleado_id_fila) {	}
		
		//$ids = array('nr' => '2588');
		//$viatico = $this->Reportes_viaticos_model->obtenerListaviatico_pendiente($ids);
		$query_consulta=mysqli_query($conexion,"select s.nombre_seccion, DATE_FORMAT(br.fecha_registro, '%d-%m-%Y') fecha_registro, b.nombre_bien,
    	 a.nombre_articulo , FORMAT(br.cantidad,0) cantidad, br.precio, FORMAT((br.cantidad * br.precio),2) subtotal from sir_bien_registro br join org_seccion s on s.id_seccion = br.id_seccion join sir_articulo a on a.id_articulo = br.id_articulo join  sir_bien b on b.id_bien = a.id_bien where br.id_empleado ='".$empleado_id_fila[0]."' and br.estado=1  order by br.fecha_registro");
		while( $fila=mysqli_fetch_array($query_consulta)){
            $info[] = $fila;
         }
         $total=0;
          
         if($info){ 
				
		$cuerpo = '
		<h6 style="font-size: 12;">&nbsp;&nbsp;Empleado: '.$key_nombre[2].' - '.($key_nombre[1]).'</h6>
		<div class="table-responsive">
			<table  class="table" style="font-size: 14;">
				<thead >
					<tr>
						<th >Nombre seccion</th>
						<th >Fecha Registro</th>
						<th >Bien</th>
						<th >Articulo</th>
						<th >Cantidad</th>
						<th >Costo</th>
						<th >Subtotal</th>					 
					</tr>
					
				</thead>
				<tbody>
					
					';
					foreach ($info as $informacion) {
					$cuerpo .= '
						<tr>
							<td>'.$informacion[0].'</td>
							<td>'.$informacion[1].'</td>
							<td>'.$informacion[2].'</td>
							<td>'.$informacion[3].'</td>
							<td>'.$informacion[4].'</td>
							<td>'.$informacion[5].'</td>
							<td>'.$informacion[6].'</td>
						</tr>
						';
						$total+=$informacion[6];
					}
				}else{
				$cuerpo .= '
						<tr><td colspan="10"><center>No hay registros "'.$id_empleado.'"</center></td></tr>
					';
				}
				$cuerpo .= '
					<tr>
						<td colspan="6">Total</td>
						<td>'.$total.'</td>
					</tr>
				</tbody>
					</table>
				
			</div>
        ';  

         mysqli_close($conexion);
      echo $cabecera_vista.=$cuerpo;
     
}
?>