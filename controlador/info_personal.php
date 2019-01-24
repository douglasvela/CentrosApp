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
		<td width="950px"><h6><center>MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br> DEPARTAMENTO DE RECURSOS HUMANOS <br>  INFORMACIÓN PERSONAL</center><h6></td>
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
		
		//$ids = array('nr' => '2588');
		//$viatico = $this->Reportes_viaticos_model->obtenerListaviatico_pendiente($ids);
		$query_consulta=mysqli_query($conexion,"SELECT * FROM sir_empleado WHERE nr='".$id_empleado."'");
		while( $fila=mysqli_fetch_array($query_consulta)){
            $info[] = $fila;
         }
          
         if($info){ 
				foreach ($info as $informacion) {
		$cuerpo = '
		<h6 style="font-size: 12;">&nbsp;&nbsp;Empleado: '.$key_nombre[2].' - '.($key_nombre[1]).'</h6>
		<div class="table-responsive">
			<table  class="table" style="font-size: 14;">
				<thead >
					<tr>
						<th >Primer Nombre</th>
						<th >Segundo Nombre</th>					 
					</tr>
					
				</thead>
				<tbody>
					
					';

					$cuerpo .= '
						<tr>
							<td>'.$informacion[1].'</td>
							<td>'.$informacion[2].'</td>
						</tr></tbody>
					</table>
						';
				$cuerpo .='
					<div class="table-responsive">
					<table  class="table" style="font-size: 14;">
						<thead >
							<tr>
								<th >Primer Apellido</th>
								<th >Segundo Apellido</th>
							</tr>
						</thead>
						<tbody>
				';
				$cuerpo .= '
						<tr>
							<td>'.$informacion[4].'</td>
							<td>'.$informacion[5].'</td>
						</tr></tbody>
						</table>
						';
				$cuerpo .='
					<div class="table-responsive">
					<table  class="table" style="font-size: 14;">
						<thead >
							<tr>
								<th >Estado Civil</th>
								<th >Genero</th>
							</tr>
						</thead>
						<tbody>
				';
				$query_consulta_ec=mysqli_query($conexion,"SELECT * FROM sir_estado_civil WHERE id_estado_civil='".$informacion[8]."'");
				while( $fila=mysqli_fetch_array($query_consulta_ec)){
		            $infoec[] = $fila;
		         }
		         foreach ($infoec as $infoecfila) {}

		        $query_consulta_ge=mysqli_query($conexion,"SELECT * FROM org_genero WHERE id_genero='".$informacion[9]."'");
				while( $fila=mysqli_fetch_array($query_consulta_ge)){
		            $infoge[] = $fila;
		         }
		         foreach ($infoge as $infogefila) {}
		         	if($informacion[8]==0){
		         		$n_e_civil="-";
		         	}else{
		         		$n_e_civil=$infoecfila[1];
		         	}
				$cuerpo .= '
						<tr>
							<td>'.$n_e_civil.'</td>
							<td>'.$infogefila[1].'</td>
						</tr></tbody>
						</table>
						';
				$cuerpo .='
					<div class="table-responsive">
					<table  class="table" style="font-size: 14;">
						<thead >
							<tr>
								<th >Nacionalidad</th>
								<th >Lugar de Nacimiento</th>
							</tr>
						</thead>
						<tbody>
				';
				$query_consulta_nac=mysqli_query($conexion,"SELECT * FROM org_nacionalidad WHERE id_nacionalidad='".$informacion[10]."'");
				while( $fila=mysqli_fetch_array($query_consulta_nac)){
		            $infonac[] = $fila;
		         }
		         foreach ($infonac as $infonacfila) {}

				$cuerpo .= '
						<tr>
							<td>'.$infonacfila[1].'</td>
							<td>'.$informacion[12].'</td>
						</tr></tbody>
						</table>
						';
				$cuerpo .='
					<div class="table-responsive">
					<table  class="table" style="font-size: 14;">
						<thead >
							<tr>
								<th >Fecha de Nacimiento</th>
								<th >Estado Empleado</th>
							</tr>
						</thead>
						<tbody>
				';
				$query_consulta_est=mysqli_query($conexion,"SELECT * FROM sir_estado WHERE id_estado='".$informacion[25]."'");
				while( $fila=mysqli_fetch_array($query_consulta_est)){
		            $infoest[] = $fila;
		         }
		         foreach ($infoest as $infoestfila) {}

				$cuerpo .= '
						<tr>
							<td>'.date("d-m-Y",strtotime($informacion[13])).'</td>
							<td>'.$infoestfila[1].'</td>
						</tr></tbody>
						</table>
						';
				$cuerpo .='
					<div class="table-responsive">
					<table  class="table" style="font-size: 14;">
						<thead >
							<tr>
								<th >Fecha de Ingreso</th>
								<th >Fecha de Retiro</th>
							</tr>
						</thead>
						<tbody>
				';
				if($informacion[16]=='0000-00-00'){
					$fecha_r='-';
				}else{
					$fecha_r = date("d-m-Y",strtotime($informacion[16]));					
				}
				$cuerpo .= '
						<tr>
							<td>'.date("d-m-Y",strtotime($informacion[15])).'</td>
							<td>'.$fecha_r.'</td>
						</tr></tbody>
						</table>
						';
				$cuerpo .='
					<div class="table-responsive">
					<table  class="table" style="font-size: 14;">
						<thead >
							<tr>
								<th >Correo Electrónico</th>
								<th >Teléfono</th>
							</tr>
						</thead>
						<tbody>
				';
				$cuerpo .= '
						<tr>
							<td>'.$informacion[17].'</td>
							<td>'.$informacion[24].'</td>
						</tr></tbody>
						</table>
						';

					}
				}else{
				$cuerpo .= '
						<tr><td colspan="10"><center>No hay registros "'.$id_empleado.'"</center></td></tr>
					';
				}
				$cuerpo .= '
				
			</div>
        ';  

         mysqli_close($conexion);
      echo $cabecera_vista.=$cuerpo;
     
}
?>