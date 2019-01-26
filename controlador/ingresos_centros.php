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
	 	<table style="width: 100%;">
		 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
		 		
				<td align="center" style="font-size: 15px; font-weight: bold; vertical-align: center; line-height: 1.5;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					CENTROS DE RECREACIÓN <br>
					INGRESOS MONETARIOS
				</td>
				
		 	</tr>
	 	</table><br>';
	 	$cuerpo = "";
		$sufijo = "";
	 	if($data["tipo"] == "mensual"){
	 		$sufijo = "mes ".$mes[$data["value"]-1]." ".$data["anio"];
	 		$cuerpo .= "<p style='margin-bottom: 0;'>MES: ".$mes[$data["value"]-1]." DE ".$data["anio"]."</p>";
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
 			$sufijo = "trimestre de ".$mes[$tminicio-1]." - ".$mes[$tmfin-1]." ".$data["anio"];
	 		$cuerpo .= "<p style='margin-bottom: 0;'>TRIMESTRE: ".$mes[$tminicio-1]." - ".$mes[$tmfin-1]." DE ".$data["anio"]."</p>";
	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
 			$sufijo = "semestre: ".$mes[$sminicio-1]." - ".$mes[$smfin-1]." ".$data["anio"];
	 		$cuerpo .= "<p style='margin-bottom: 0;'>SEMESTRE: ".$mes[$sminicio-1]." - ".$mes[$smfin-1]." DE ".$data["anio"]."</p>";
	 	}else{
	 		$sufijo = "año ".$data["anio"];
	 		$cuerpo .= "<p style='margin-bottom: 0;'>AÑO: ".$data["anio"]."</p>";
	 	}

	 	$cuerpo .= ' <div class="table-responsive">
			<table class="table table-striped" style="font-size:12;">
				<thead>
					<tr>
						<th align="center">Centro de recreación</th>
						<th align="center">Ingreso monetario</th>
						<th align="center">Total personas visitantes</th>
						<th align="center">Hombres</th>
						<th align="center">Mujeres</th>
					</tr>
				</thead>
				<tbody>';

				$centro = obtener_centros($conexion);
				$total_monto = 0;
				$total_visitante = 0;
				$total_masculino = 0;
				$total_femenino = 0;
				$registros_genero = array();
				$labels = array();
				if($centro){
					foreach ($centro as $filas) {
						$data["id_centro"] = $filas['id_centro'];						
						$ingresos_centro = obtener_ingresos_periodo($data, "normal",$conexion);
						if($ingresos_centro){
							foreach ($ingresos_centro as $filaic) {}

							$total_monto += $filaic['monto'];
							$total_visitante += intval($filaic['cant_masculino']+$filaic['cant_femenino']);
							$total_masculino += intval($filaic['cant_masculino']);
							$total_femenino += intval($filaic['cant_femenino']);

							array_push($registros_genero, array(number_format($filaic['monto'],2,".",","), intval($filaic['cant_masculino']+$filaic['cant_femenino']), intval($filaic['cant_masculino']), intval($filaic['cant_femenino'])));
							array_push($labels, $filas['nickname']);

							$cuerpo .= '
							<tr>
								<td align="center" style="width:180px">'.$filas['nickname'].'</td>
								<td align="center" style="width:180px">$ '.number_format($filaic['monto'],2,".",",").'</td>
								<td align="center" style="width:180px">'.intval($filaic['cant_masculino']+$filaic['cant_femenino']).'</td>
								<td align="center" style="width:180px">'.intval($filaic['cant_masculino']).'</td>
								<td align="center" style="width:180px">'.intval($filaic['cant_femenino']).'</td>
							</tr>';
						}
					$ingresos_convenio = obtener_ingresos_periodo($data, "convenios",$conexion);
						if($ingresos_convenio){
							foreach ($ingresos_convenio as $filaie) {}

							$total_monto += $filaie['monto'];
							$total_visitante += intval($filaie['cant_masculino']+$filaie['cant_femenino']);
							$total_masculino += intval($filaie['cant_masculino']);
							$total_femenino += intval($filaie['cant_femenino']);

							if(!empty($filaie['monto']) && ($filaie['cant_masculino'] > 0 || $filaie['cant_femenino'] > 0)){
								array_push($registros_genero, array(number_format($filaie['monto'],2,".",","), intval($filaie['cant_masculino']+$filaie['cant_femenino']), intval($filaie['cant_masculino']), intval($filaie['cant_femenino'])));
								array_push($labels, $filas->nickname.' (por convenio)');
								$cuerpo .= '
								<tr>
									<td align="center" style="width:180px">'.$filas['nickname'].' (por convenio)</td>
									<td align="center" style="width:180px">$ '.number_format($filaie['monto'],2,".",",").'</td>
									<td align="center" style="width:180px">'.intval($filaie['cant_masculino']+$filaie['cant_femenino']).'</td>
									<td align="center" style="width:180px">'.intval($filaie['cant_masculino']).'</td>
									<td align="center" style="width:180px">'.intval($filaie['cant_femenino']).'</td>
								</tr>';
							}
						}
					}
				}
		$ingresos_despacho = obtener_ingresos_periodo($data, "despacho",$conexion);
				if($ingresos_despacho){
					foreach ($ingresos_despacho as $filaie) {}

					$total_monto += $filaie['monto'];
					$total_visitante += intval($filaie['cant_masculino']+$filaie['cant_femenino']);
					$total_masculino += intval($filaie['cant_masculino']);
					$total_femenino += intval($filaie['cant_femenino']);

					if(!empty($filaie['monto']) && ($filaie['cant_masculino'] > 0 || $filaie['cant_femenino'] > 0)){
						array_push($registros_genero, array(number_format($filaie['monto'],2,".",","), intval($filaie['cant_masculino']+$filaie['cant_femenino']), intval($filaie['cant_masculino']), intval($filaie['cant_femenino'])));
						array_push($labels, 'Exonerados por despacho');
						$cuerpo .= '
						<tr>
							<td align="center" style="width:180px">Exonerados por despacho</td>
							<td align="center" style="width:180px">$ '.number_format($filaie['monto'],2,".",",").'</td>
							<td align="center" style="width:180px">'.intval($filaie['cant_masculino']+$filaie['cant_femenino']).'</td>
							<td align="center" style="width:180px">'.intval($filaie['cant_masculino']).'</td>
							<td align="center" style="width:180px">'.intval($filaie['cant_femenino']).'</td>
						</tr>';
					}
				}
		$cuerpo .= '
						<tr>
							<th align="center" style="width:180px">Subtotal</th>
							<th align="center" style="width:180px">$ '.number_format($total_monto,2,".",",").'</th>
							<th align="center" style="width:180px">'.$total_visitante.'</th>
							<th align="center" style="width:180px">'.$total_masculino.'</th>
							<th align="center" style="width:180px">'.$total_femenino.'</th>
						</tr>';
		$ingresos_centro = obtener_ingresos_actuales($data,$conexion);
				if($ingresos_centro){
					foreach ($ingresos_centro as $filaia) {}

						$query_configs = mysqli_query($conexion,"SELECT * FROM cdr_configuracion");
						while( $query_config_fila=mysqli_fetch_array($query_configs,MYSQLI_ASSOC)){
				            $configs[] = $query_config_fila;
				         }
						$total_a_fecha = 0;
						if($configs){
						    foreach ($configs as $filaconf) {
						    	$total_a_fecha = $filaconf['cantidad'];
						    }
						}

						$cuerpo .= '
						<tr>
							<th align="center" style="width:180px">Total a la fecha</th>
							<th align="center" style="width:180px">$ '.number_format(($filaia['monto']+$total_a_fecha),2,".",",").'</th>
							<th align="center" style="width:180px"></th>
							<th align="center" style="width:180px"></th>
							<th align="center" style="width:180px"></th>
						</tr>';
				}

				$cuerpo .= '	
				</tbody>
			</table></div>';





    mysqli_close($conexion);
    echo $cabecera_vista.=$cuerpo;
}
 function obtener_ingresos_actuales($data,$conexion){
	 	$query = mysqli_query($conexion,"SELECT SUM(dr.monto) AS monto FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva AND YEAR(r.fecha_ingreso_reserva) >= '".$data["anio"]."'");
         while( $query_fila=mysqli_fetch_array($query,MYSQLI_ASSOC)){
	            $centros[] = $query_fila;
	         }
        return $centros;
    }
function obtener_ingresos_periodo($data, $tipo,$conexion){
		if($tipo == "normal"){
			$cnt_fem = "dr.cant_femenino";
			$cnt_mas = "dr.cant_masculino";
			$add = '';
			$select = "dr.monto";
			$data["id_centro"] = "AND r.id_centro = '".$data["id_centro"]."'";
		}else if($tipo == "convenios"){
			$select = "0.00";
			$cnt_fem = "dr.cant_femenino_exo_ministra";
			$cnt_mas = "dr.cant_masculino_exo_ministra";
			$add = "AND dr.	id_exoneracion_tipo = '2'";
			$data["id_centro"] = "";
		}else if($tipo == "despacho"){
			$select = "0.00";
			$cnt_fem = "dr.cant_femenino_exo_ministra";
			$cnt_mas = "dr.cant_masculino_exo_ministra";
			$add = "AND dr.	id_exoneracion_tipo = '1'";
			$data["id_centro"] = "";
		}

		if($data["tipo"] == "mensual"){
	 		$centros = mysqli_query($conexion,"SELECT SUM(".$select.") AS monto, SUM(".$cnt_mas.") AS cant_masculino, SUM(".$cnt_fem.") AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$add." ".$data["id_centro"]." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) = '".$data["value"]."'");
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
	 		$centros = mysqli_query($conexion,"SELECT SUM(".$select.") AS monto, SUM(".$cnt_mas.") AS cant_masculino, SUM(".$cnt_fem.") AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$add." ".$data["id_centro"]." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$tminicio."' AND '".$tmfin."'");

	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
	 		$centros = mysqli_query($conexion,"SELECT SUM(".$select.") AS monto, SUM(".$cnt_mas.") AS cant_masculino, SUM(".$cnt_fem.") AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$add." ".$data["id_centro"]." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$sminicio."' AND '".$smfin."'");
	 	}else{
	 		$centros = mysqli_query($conexion,"SELECT SUM(".$select.") AS monto, SUM(".$cnt_mas.") AS cant_masculino, SUM(".$cnt_fem.") AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$add." ".$data["id_centro"]." AND YEAR(r.fecha_inicio) = '".$data["anio"]."'");
	 	}
        while( $query_fila=mysqli_fetch_array($centros,MYSQLI_ASSOC)){
	            $centros_data[] = $query_fila;
	         }
        return $centros_data;
    }
function obtener_centros($conexion){
        $query=mysqli_query($conexion,"SELECT * FROM `cdr_centro` ORDER BY nombre ASC");
	     	 while( $query_fila=mysqli_fetch_array($query,MYSQLI_ASSOC)){
	            $centros[] = $query_fila;
	         }
        return $centros;
    }
 function obtener_categoria_espacios($tipo,$conexion){
    	if($tipo == "espacios_fisicos"){
    		$centros=mysqli_query($conexion,"SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '1'");
    	}elseif ($tipo == "cafeterias") {
    		$centros=mysqli_query($conexion,"SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '5'");
    	}elseif ($tipo == "estacionamientos") {
    		$centros=mysqli_query($conexion,"SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '3'");
    	}
    	while( $query_fila=mysqli_fetch_array($centros,MYSQLI_ASSOC)){
	            $centros_data[] = $query_fila;
	         }
        return $centros_data;
    }