<?php
$id_centro = $_POST['id_centro']; 
$data  = array('id_centro' => $id_centro);

echo reporte($data);
 

function reporte($data){

    $conexion = mysqli_connect("162.241.252.245","proyedk4_WPZF0","MAYO_nesa94","proyedk4_WPZF0"); 
    $mes = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');

    $cabecera_vista = '
	 	<table class="table" style="font-size: 14px;">
		 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
		 		
				<td align="center" style="font-size: 15px; font-weight: bold; vertical-align: center; line-height: 1.5;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					SECCIÓN DE CENTROS DE RECREACIÓN <br>
					<span style="font-size: 12px;">INFORME DE RESERVAS ANULADAS</span>
				</td>
				
		 	</tr>
	 	</table><br>';
	 	$cuerpo = "";

		
		$labels = array();
		$cuerpo .= ' <div class="table-responsive">
			<table  style="font-size:12" class="table table-striped">
				<thead>
					<tr>
					<th align="center">Fecha Solicitud</th>
						<th align="center">No. Solicitud</th>
						<th align="center">Fecha Inicio</th>
						<th align="center">Fecha Fin</th>
						<th align="center">Visitante</th>
						<th align="center">Monto</th>
						<th align="center">Motivo Anulada</th>
						<th align="center">Usuario</th>
					</tr>
				</thead>
				<tbody>';
 

				$centros = obtener_reservas_anuladas($data,$conexion);
				if($centros){
					foreach ($centros as $filahi) { 				
						if($filahi['codigo']==""){
							$micodigo="-";
						}else{
							$micodigo=$filahi['codigo'];
						}
						$cuerpo .= '
						<tr>
						<td align="center" style="width:180px">'.date("d/m/Y",strtotime($filahi['fecha_ingreso_reserva'])).'</td>
							<td align="center" style="width:180px">'.$micodigo.'</td>
							<td align="center" style="width:180px">'.date("d/m/Y",strtotime($filahi['fecha_inicio'])).'</td>
							<td align="center" style="width:180px">'.date("d/m/Y",strtotime($filahi['fecha_fin'])).'</td>
							<td align="center" style="width:180px">'.$filahi['visitante'].'</td>
							<td align="center" style="width:180px">$ '.number_format($filahi['monto'],2,".",",").'</td>
							<td align="center" style="width:180px">'.$filahi['motivo'].'</td>
							<td align="center" style="width:180px">'.$filahi['usuario'].'</td>
						</tr>';
					}
				}

				 
				$cuerpo .= '	
				</tbody>
			</table></div>';


    mysqli_close($conexion);
    echo $cabecera_vista.=$cuerpo;
}
  
 
  function obtener_reservas_anuladas($data,$conexion){
    
        $query = mysqli_query($conexion,"SELECT r.*,(select nombre from cdr_cliente where id_cliente=r.id_cliente) as visitante,(select nombre from cdr_reserva_detalle_estado where id_estado_dettalle_reserva=r.estado2) as motivo,(select sum(monto) from cdr_detalle_reserva where id_reserva=r.id_reserva) as monto,(select nombre_completo from org_usuario where nr=r.nr_usuario) as usuario FROM cdr_reserva as r where (r.estado2=2 or r.estado2=3) and r.id_centro='".$data['id_centro']."'");
       
        while( $query_fila=mysqli_fetch_array($query,MYSQLI_ASSOC)){
	            $centros[] = $query_fila;
	         }
        return $centros;
    }