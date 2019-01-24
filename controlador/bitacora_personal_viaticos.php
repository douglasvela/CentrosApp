<?php
$funcion = $_POST['funcion'];
$id_empleado = $_POST['id_empleado']; 
switch($funcion) {
        case 'reporte_bitacora': 
            echo reporte_viatico_bitacora_empleado($id_empleado,$fecha_min,$fecha_max);
            break;
    }
	

function reporte_viatico_bitacora_empleado($id_empleado,$fecha_min,$fecha_max){

      $conexion = mysqli_connect("162.241.252.245","proyedk4_WPZF0","MAYO_nesa94","proyedk4_WPZF0"); 
      
     $cabecera_vista = '<table style="font-size: 14;" class="table"><tr> 
		<td width="950px"><h6><center>MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br> UNIDAD FINANCIERA INSTITUCIONAL <br> FONDO CIRCULANTE DE MONTO FIJO <br> BITÁCORA DE VIATICOS POR EMPLEADO</center><h6></td>
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
		$query_consulta_viatico=mysqli_query($conexion,"SELECT * FROM vyp_mision_oficial WHERE nr_empleado='".$id_empleado."' and (estado  between '0' and '8')");
		while( $fila=mysqli_fetch_array($query_consulta_viatico)){
            $viatico[] = $fila;
         }
          

		$cuerpo = '
		<h6 style="font-size: 12;">&nbsp;&nbsp;Empleado: '.($key_nombre[1]).'</h6>
		<div class="table-responsive">
			<table  class="table table-striped">
				
				<thead >

					<tr>
						<th scope="col" align="center">Fecha Solicitud</th>
						<th scope="col" align="center">Actividad</th>
						<th scope="col" align="center">Estado</th>
						<th scope="col" align="center">Viaticos</th>
						<th scope="col" align="center">Pasajes</th>
						<th scope="col" align="center">Alojamiento</th>
						<th scope="col" align="center" >Total</th>						 
					</tr>
					<tr>
						
					</tr>
				</thead>
				<tbody>
					
					';
				if($viatico){
					$suma_total=0;
					$suma_viaticos=0;
					$suma_pasajes=0;
					$suma_alojamientos=0;
				foreach ($viatico as $viaticos) {
					
					//$estado = $this->Reportes_viaticos_model->obtenerDetalleEstado($viaticos->estado);
					//foreach ($estado->result() as $estado_detalle) {}
					 
					$query_consulta_totales=mysqli_query($conexion,"SELECT sum(`viatico`) as viatico,sum(`pasaje`) as pasaje,sum(`alojamiento`) as alojamiento, (sum(`viatico`) + sum(`pasaje`) + sum(`alojamiento`)) as total FROM `vyp_empresa_viatico` WHERE `id_mision`='144'");
					while( $fila=mysqli_fetch_array($query_consulta_totales)){
			            $totales[] = $fila;
			         }
					//$totales = $this->Reportes_viaticos_model->obtenerTotalMontos($viaticos->id_mision_oficial);
					foreach ($totales as $totales_detalle) {}
					 	
						$suma_viaticos+=$totales_detalle[0];
						$suma_pasajes+=$totales_detalle[1];
						$suma_alojamientos+=$totales_detalle[2];
						$suma_total=$suma_viaticos+$suma_pasajes+$suma_alojamientos;

						$query_estado=mysqli_query($conexion,"select * from vyp_estado_solicitud where id_estado_solicitud='".$viaticos[13]."'");
						while( $filaestado=mysqli_fetch_array($query_estado)){
				            $nombre_estado[] = $filaestado;
				         }
				         foreach ($nombre_estado as $nombre_estado_fila) {}

					$cuerpo .= '
						<tr>
							<td>'.date('d-m-Y',strtotime($viaticos[5])).'</td>
							<td>'.$viaticos[7].'</td>
							<td>'.$nombre_estado_fila[1].'</td>
							<td>$'.number_format($totales_detalle[0],2,".",",").'</td>
							<td>$'.number_format($totales_detalle[1],2,".",",").'</td>
							<td>$'.number_format($totales_detalle[2],2,".",",").'</td>
							<td>$'.number_format($totales_detalle[3],2,".",",").'</td>
						</tr>
						';
					
					}
				}else{
				$cuerpo .= '
						<tr><td colspan="10"><center>No hay registros</center></td></tr>
					';
				}
				$cuerpo .= '
					<tr>
							<th colspan="3" align="right">Total</th>
							<th>$'.number_format($suma_viaticos,2,".",",").'</th>
							<th>$'.number_format($suma_pasajes,2,".",",").'</th>
							<th>$'.number_format($suma_alojamientos,2,".",",").'</th>
							<th>$'.number_format($suma_total,2,".",",").'</th>
						</tr>
				</tbody>
			</table>
			</div>
        ';  

         mysqli_close($conexion);
      echo $cabecera_vista.=$cuerpo;
     
}
?>