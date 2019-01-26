<?php
$anio = $_POST['anio'];
$tipo = $_POST['type'];
$value = $_POST['value'];
$data  = array('tipo' => $tipo, 'value'=>$value,'anio'=>$anio);

echo reporte($data);
 

function reporte($data){

    $conexion = mysqli_connect("162.241.252.245","proyedk4_WPZF0","MAYO_nesa94","proyedk4_WPZF0"); 
    $mes = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');

    $cabecera_vista = '
	 	<table style="width: 100%;" class="table">
		 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
		 		
				<td align="center" style="font-size: 15px; font-weight: bold; vertical-align: center; line-height: 1.5;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					OFICINA DE ESTADÍSTICA E INFORMÁTICA <br>
					<span style="font-size: 12px;">INFORME DE INGRESO CONSOLIDADO POR CENTROS DE RECREACIÓN</span>
				</td>
				
		 	</tr>
	 	</table><br>';
	 	$cuerpo = "";

		$centro = obtener_centros($conexion);
		$labels = array();
		$cuerpo .= ' <div class="table-responsive">
			<table  style="font-size:12" class="table table-striped">
				<thead>
					<tr>
						<th align="center">Fecha</th>';
						if($centro){
							foreach ($centro as $filas) {
								$cuerpo .= '<th align="center">'.$filas['nickname'].'</th>';
								array_push($labels, $filas['nickname']);
							}
						}

		$cuerpo .= '<th align="center">Total</th>	
					</tr>
				</thead>
				<tbody>';

				$total1 = 0;
				$total2 = 0;
				$total3 = 0;
				$total4 = 0;

				$totalcentros = 0;

				$ingresos_centro = obtener_ingresos_diarios($data,$conexion);
				if($ingresos_centro){
					foreach ($ingresos_centro as $filahi) {
						$totalcentros = 0;
						$total1 += $filahi['column1'];
						$total2 += $filahi['column2'];
						$total3 += $filahi['column3'];
						$total4 += $filahi['column4'];

						$totalcentros += floatval($filahi['column1'])+floatval($filahi['column2'])+floatval($filahi['column3'])+floatval($filahi['column4']);					

						$cuerpo .= '
						<tr>
							<td align="center" style="width:180px">'.date("d/m/Y",strtotime($filahi['fecha'])).'</td>
							<td align="center" style="width:180px">$ '.number_format($filahi['column1'],2,".",",").'</td>
							<td align="center" style="width:180px">$ '.number_format($filahi['column2'],2,".",",").'</td>
							<td align="center" style="width:180px">$ '.number_format($filahi['column3'],2,".",",").'</td>
							<td align="center" style="width:180px">$ '.number_format($filahi['column4'],2,".",",").'</td>
							<td align="center" style="width:180px">$ '.number_format($totalcentros,2,".",",").'</td>
						</tr>';
					}
				}

				$cuerpo .= '
					<tr>
						<th align="center" style="width:180px">Total por centro</th>
						<th align="center" style="width:180px">$ '.number_format($total1,2,".",",").'</th>
						<th align="center" style="width:180px">$ '.number_format($total2,2,".",",").'</th>
						<th align="center" style="width:180px">$ '.number_format($total3,2,".",",").'</th>
						<th align="center" style="width:180px">$ '.number_format($total4,2,".",",").'</th>
						<th align="center" style="width:180px">$ '.number_format(($total1+$total2+$total3+$total4),2,".",",").'</th>
					</tr>';

				$cuerpo .= '	
				</tbody>
			</table></div>';


    mysqli_close($conexion);
    echo $cabecera_vista.=$cuerpo;
}
  
function obtener_centros($conexion){
        $query=mysqli_query($conexion,"SELECT * FROM `cdr_centro` ORDER BY nombre ASC");
	     	 while( $query_fila=mysqli_fetch_array($query,MYSQLI_ASSOC)){
	            $centros[] = $query_fila;
	         }
        return $centros;
    }
  function obtener_ingresos_diarios($data,$conexion){
    	$centro = obtener_centros($conexion);
    	$add=""; $contador = 0;
		if($centro){
			foreach ($centro as $filas) {
				$contador++;
				$add .= ", SUM(CASE WHEN r.id_centro = '".$filas['id_centro']."' THEN dr.monto ELSE 0 END) AS column".$contador;
			}
		}
 
        if($data["tipo"] == "mensual"){
        	$query = mysqli_query($conexion,"SELECT r.fecha_inicio AS fecha ".$add." FROM cdr_detalle_reserva AS dr JOIN cdr_reserva AS r ON r.id_reserva = dr.id_reserva JOIN cdr_centro AS ce ON ce.id_centro = r.id_centro where year(r.fecha_inicio)=".$data['anio']." AND MONTH(r.fecha_inicio) = '".$data["value"]."'  GROUP BY r.fecha_inicio ");
        }else if($data["tipo"] == "trimestral"){
        	$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
        	$query = mysqli_query($conexion,"SELECT r.fecha_inicio AS fecha ".$add." FROM cdr_detalle_reserva AS dr JOIN cdr_reserva AS r ON r.id_reserva = dr.id_reserva JOIN cdr_centro AS ce ON ce.id_centro = r.id_centro where year(r.fecha_inicio)=".$data['anio']." AND MONTH(r.fecha_inicio) BETWEEN '".$tminicio."' AND '".$tmfin."'  GROUP BY r.fecha_inicio ");
        }else if($data["tipo"] == "semestral"){
        	$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
        	$query = mysqli_query($conexion,"SELECT r.fecha_inicio AS fecha ".$add." FROM cdr_detalle_reserva AS dr JOIN cdr_reserva AS r ON r.id_reserva = dr.id_reserva JOIN cdr_centro AS ce ON ce.id_centro = r.id_centro where year(r.fecha_inicio)=".$data['anio']." AND MONTH(r.fecha_inicio) BETWEEN '".$sminicio."' AND '".$smfin."'  GROUP BY r.fecha_inicio ");
        }else{
        	$query = mysqli_query($conexion,"SELECT r.fecha_inicio AS fecha ".$add." FROM cdr_detalle_reserva AS dr JOIN cdr_reserva AS r ON r.id_reserva = dr.id_reserva JOIN cdr_centro AS ce ON ce.id_centro = r.id_centro where year(r.fecha_inicio)=".$data['anio']."  GROUP BY r.fecha_inicio ");
        }

        while( $query_fila=mysqli_fetch_array($query,MYSQLI_ASSOC)){
	            $centros[] = $query_fila;
	         }
        return $centros;
    }