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
	 	<table  class="table">
		 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
		 		
				<td align="center" style="font-size: 15px; font-weight: bold; vertical-align: center; line-height: 1.5;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					OFICINA DE ESTADÍSTICA E INFORMÁTICA <br>
					INFORME '.strtoupper($data["tipo"]).' DIRECCIÓN ADMINISTRATIVA
				</td>
				
		 	</tr>
	 	</table><br>
	 	';
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


	 $ids_categorias = array();
	 $sumas = array();
	 $categoria_visita = obtener_categoria_espacios('espacios_fisicos',$conexion);

	 $cuerpo .= ' 
		<table  class="table">
		 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
		 		
				<td align="center" style="font-size: 15px; font-weight: bold; vertical-align: center; line-height: 1.5;">
				 USO DE INSTALACIONES
				</td>
				
		 	</tr>
	 	</table>
	 <div class="table-responsive">
			<table style="font-size: 12;" class="table table-striped">
				<thead>
					<tr>
						<th align="center" width="400px;"></th>';	

	if($categoria_visita){
			foreach ($categoria_visita as $filac) {
				$cuerpo .= '<th align="center" width="120px;" colspan="2">'.$filac['descripcion'].'</th>';
				array_push($ids_categorias, $filac['id_categoria']);
			}
		}
	$cuerpo .= '</tr><tr><th align="center">Uso de instalaciones</th>';
		if($categoria_visita){
			foreach ($categoria_visita as $filac) {
				array_push($sumas, 0);
				array_push($sumas, 0);
				$cuerpo .= '<th align="center">Monto</th>';
				$cuerpo .= '<th align="center">Cant.</th>';
			}
		}
		$cuerpo .= '</tr></thead><tbody>';
	/***************** INICIO REGISTROS DE VISITANTES POR CENTROS ******************************************/
	 	$datos = array();
	 	$centro = obtener_centros($conexion);
		if($centro){
			foreach ($centro as $filas) {
				$cuerpo .= '<tr>';
				$data["id_centro"] = $filas['id_centro'];
				$cuerpo .= '<td align="center">'.$filas['nickname'].'</td>';
				$j = 0;
				for ($i = 0; $i < count($ids_categorias); $i++) {
					$visitas_centros = obtener_instalacion_uso($data, "normal", $ids_categorias[$i],$conexion);
					if($visitas_centros){
						foreach ($visitas_centros as $filav) {
							$sumas[$j] += floatval($filav['monto']); $j++;
							$sumas[$j] += intval($filav['cantidad']); $j++;
							$cuerpo .= '<td align="center">$'.number_format($filav['monto'],2,".",",").'</td>';
							$cuerpo .= '<td align="center">'.intval($filav['cantidad']).'</td>';
						}
					}
				}
				
				$cuerpo .= '</tr>';
			}
		}
		/***************** FIN REGISTROS DE VISITANTES POR CENTROS ******************************************/
		/***************** INICIO REGISTROS VISITANTES POR CONVENIOS ***************************************/
		$cuerpo .= '<tr>';
		$data["id_centro"] = "";
		$cuerpo .= '<td align="center">Convenios</td>';
		$j = 0;
		for ($i = 0; $i < count($ids_categorias); $i++) {
			$visitas_centros = obtener_instalacion_uso($data, "convenios", $ids_categorias[$i],$conexion);
			if($visitas_centros){
				foreach ($visitas_centros as $filav) {
					$sumas[$j] += floatval($filav['monto']); $j++;
					$sumas[$j] += intval($filav['cantidad']); $j++;
					$cuerpo .= '<td align="center">$'.number_format($filav['monto'],2,".",",").'</td>';
					$cuerpo .= '<td align="center">'.intval($filav['cantidad']).'</td>';
				}
			}
		}
		/***************** FIN REGISTROS VISITANTES POR CONVENIOS ******************************************/
		/***************** INICIO REGISTROS VISITANTES POR DESPACHO ***************************************/
		$cuerpo .= '<tr>';
		$data["id_centro"] = "";
		$cuerpo .= '<td align="center">Exonerados por despacho</td>';
		$j = 0;
		for ($i = 0; $i < count($ids_categorias); $i++) {
			$visitas_centros = obtener_instalacion_uso($data, "despacho", $ids_categorias[$i],$conexion);
			if($visitas_centros){
				foreach ($visitas_centros as $filav) {
					$sumas[$j] += floatval($fila['monto']); $j++;
					$sumas[$j] += intval($fila['cantidad']); $j++;
					$cuerpo .= '<td align="center">$'.number_format($fila['monto'],2,".",",").'</td>';
					$cuerpo .= '<td align="center">'.intval($fila['cantidad']).'</td>';
				}
			}
		}
		/***************** FIN REGISTROS VISITANTES POR DESPACHO ******************************************/
		$cuerpo .= '</tr>';

		$cuerpo .= '<tr><th align="center">Total</th>';
		for ($i = 0; $i < count($sumas); $i++) {
			if($i % 2 == 0)
				$cuerpo .= '<th align="center">$'.number_format($sumas[$i],2,".",",").'</th>';
			else
				$cuerpo .= '<th align="center">'.intval($sumas[$i]).'</th>';
		}
		$cuerpo .= '</tr>';

				$cuerpo .= '	
				</tbody>
			</table></div></div><br>
		<table  class="table">
		 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
		 		
				<td align="center" style="font-size: 15px; font-weight: bold; vertical-align: center; line-height: 1.5;">
				 USO DE ESTACIONAMIENTO
				</td>
				
		 	</tr>
	 	</table>
			';


		$ids_categorias = array();
	 	$sumas = array();
	 	$categoria_visita = obtener_categoria_espacios('estacionamientos',$conexion);

	 	$cuerpo .= ' <div class="table-responsive">
			<table  style="font-size: 12;" class="table table-striped">
				<thead>
					<tr>
						<th align="center" width="400px;"></th>';					
					

		if($categoria_visita){
			foreach ($categoria_visita as $filac) {
				$cuerpo .= '<th align="center" width="120px;" colspan="2">'.$filac['descripcion'].'</th>';
				array_push($ids_categorias, $filac['id_categoria']);
			}
		}

		$cuerpo .= '</tr><tr><th align="center">Uso de estacionamientos</th>';
		if($categoria_visita){
			foreach ($categoria_visita as $filac) {
				array_push($sumas, 0);
				array_push($sumas, 0);
				$cuerpo .= '<th align="center">Monto</th>';
				$cuerpo .= '<th align="center">Cant.</th>';
			}
		}
		$cuerpo .= '</tr></thead><tbody>';

	/***************** INICIO REGISTROS DE VISITANTES POR CENTROS ******************************************/
	 	$datos = array();
	 	$centro = obtener_centros($conexion);
		if($centro){
			foreach ($centro as $filas) {
				$cuerpo .= '<tr>';
				$data["id_centro"] = $filas['id_centro'];
				$cuerpo .= '<td align="center">'.$filas['nickname'].'</td>';
				$j = 0;
				for ($i = 0; $i < count($ids_categorias); $i++) {
					$visitas_centros = obtener_instalacion_uso($data, "normal", $ids_categorias[$i],$conexion);
					if($visitas_centros){
						foreach ($visitas_centros as $filav) {
							$sumas[$j] += floatval($filav['monto']); $j++;
							$sumas[$j] += intval($filav['cantidad']); $j++;
							$cuerpo .= '<td align="center">$'.number_format($filav['monto'],2,".",",").'</td>';
							$cuerpo .= '<td align="center">'.intval($filav['cantidad']).'</td>';
						}
					}
				}
				
				$cuerpo .= '</tr>';
			}
		}
		/***************** FIN REGISTROS DE VISITANTES POR CENTROS ******************************************/
		/***************** INICIO REGISTROS VISITANTES POR CONVENIOS ***************************************/
		$cuerpo .= '<tr>';
		$data["id_centro"] = "";
		$cuerpo .= '<td align="center">Convenios</td>';
		$j = 0;
		for ($i = 0; $i < count($ids_categorias); $i++) {
			$visitas_centros = obtener_instalacion_uso($data, "convenios", $ids_categorias[$i],$conexion);
			if($visitas_centros){
				foreach ($visitas_centros as $filav) {
					$sumas[$j] += floatval($filav['monto']); $j++;
					$sumas[$j] += intval($filav['cantidad']); $j++;
					$cuerpo .= '<td align="center">$'.number_format($filav['monto'],2,".",",").'</td>';
					$cuerpo .= '<td align="center">'.intval($filav['cantidad']).'</td>';
				}
			}
		}
		/***************** FIN REGISTROS VISITANTES POR CONVENIOS ******************************************/
		/***************** INICIO REGISTROS VISITANTES POR DESPACHO ***************************************/
		$cuerpo .= '<tr>';
		$data["id_centro"] = "";
		$cuerpo .= '<td align="center">Exonerados por despacho</td>';
		$j = 0;
		for ($i = 0; $i < count($ids_categorias); $i++) {
			$visitas_centros = obtener_instalacion_uso($data, "despacho", $ids_categorias[$i],$conexion);
			if($visitas_centros){
				foreach ($visitas_centros as $filav) {
					$sumas[$j] += floatval($filav['monto']); $j++;
					$sumas[$j] += intval($filav['cantidad']); $j++;
					$cuerpo .= '<td align="center">$'.number_format($filav['monto'],2,".",",").'</td>';
					$cuerpo .= '<td align="center">'.intval($filav['cantidad']).'</td>';
				}
			}
		}
		/***************** FIN REGISTROS VISITANTES POR DESPACHO ******************************************/

		$cuerpo .= '</tr>';

		$cuerpo .= '<tr><th align="center">Total</th>';
		for ($i = 0; $i < count($sumas); $i++) {
			if($i % 2 == 0)
				$cuerpo .= '<th align="center">$'.number_format($sumas[$i],2,".",",").'</th>';
			else
				$cuerpo .= '<th align="center">'.intval($sumas[$i]).'</th>';
		}
		$cuerpo .= '</tr>';

				$cuerpo .= '	
				</tbody>
			</table></div><br>';


			$ids_categorias = array();
	 	$sumas = array();
	 	$categoria_visita = obtener_categoria_espacios('cafeterias',$conexion);

	 	$cuerpo .= ' 
		<table  class="table">
		 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
		 		
				<td align="center" style="font-size: 15px; font-weight: bold; vertical-align: center; line-height: 1.5;">
				 USO DE CAFETERIAS, ESPACIOS PUBLICITARIOS Y ACTOS CULTURALES
				</td>
				
		 	</tr>
	 	</table>
	 	<div class="table-responsive">
			<table style="font-size: 12;" class="table table-striped">
				<thead>
					<tr>
						<th align="center" width="400px;"></th>';					
					

		if($categoria_visita){
			foreach ($categoria_visita as $filac) {
				$cuerpo .= '<th align="center" width="120px;" colspan="2">'.$filac['descripcion'].'</th>';
				array_push($ids_categorias, $filac['id_categoria']);
			}
		}

		$cuerpo .= '</tr><tr><th align="center">Espacios por metro</th>';
		if($categoria_visita){
			foreach ($categoria_visita as $filac) {
				array_push($sumas, 0);
				array_push($sumas, 0);
				$cuerpo .= '<th align="center">Monto</th>';
				$cuerpo .= '<th align="center">Cant.</th>';
			}
		}
		$cuerpo .= '</tr></thead><tbody>';

		/***************** INICIO REGISTROS DE VISITANTES POR CENTROS ******************************************/
	 	$datos = array();
	 	$centro = obtener_centros($conexion);
		if($centro){
			foreach ($centro as $filas) {
				$cuerpo .= '<tr>';
				$data["id_centro"] = $filas['id_centro'];
				$cuerpo .= '<td align="center">'.$filas['nickname'].'</td>';
				$j = 0;
				for ($i = 0; $i < count($ids_categorias); $i++) {
					$visitas_centros = obtener_instalacion_uso($data, "normal", $ids_categorias[$i],$conexion);
					if($visitas_centros){
						foreach ($visitas_centros as $filav) {
							$sumas[$j] += floatval($filav['monto']); $j++;
							$sumas[$j] += intval($filav['cantidad']); $j++;
							$cuerpo .= '<td align="center">$'.number_format($filav['monto'],2,".",",").'</td>';
							$cuerpo .= '<td align="center">'.intval($filav['cantidad']).'</td>';
						}
					}
				}
				
				$cuerpo .= '</tr>';
			}
		}
		/***************** FIN REGISTROS DE VISITANTES POR CENTROS ******************************************/

		$cuerpo .= '<tr><th align="center">Total</th>';
		for ($i = 0; $i < count($sumas); $i++) {
			if($i % 2 == 0)
				$cuerpo .= '<th align="center">$'.number_format($sumas[$i],2,".",",").'</th>';
			else
				$cuerpo .= '<th align="center">'.intval($sumas[$i]).'</th>';
		}
		$cuerpo .= '</tr>';

				$cuerpo .= '	
				</tbody>
			</table><div><br>';




    mysqli_close($conexion);
    echo $cabecera_vista.=$cuerpo;
}


function obtener_instalacion_uso($data, $tipo, $id_categoria,$conexion){
		if($tipo == "normal"){ /* TODOS LOS INGRESOS QUE NO ESTEN EXONERADOS */
			$data['id_centro'] = "AND r.id_centro = '".$data["id_centro"]."' AND dr.monto > 0";
			$monto = 'SUM(dr.monto) AS monto';
		}else if($tipo == "despacho"){ /* EXONERADOS EN CATEGORIAS EXTRAS */
			$monto = 'SUM(dr.monto_exonerado) AS monto';
			$data['id_centro'] = "AND dr.id_exoneracion_tipo = '1'";
		}else if($tipo == "convenios"){ /* SOLO CONVENIOS */
			$monto = 'SUM(dr.monto_exonerado) AS monto';
			$data['id_centro'] = "AND dr.id_exoneracion_tipo = '2'";
		}

		if($data["tipo"] == "mensual"){
	 		$centros = mysqli_query($conexion,"SELECT count(*) AS cantidad, $monto FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) = '".$data["value"]."' AND id_categoria_espacio = '".$id_categoria."'");
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
	 		$centros = mysqli_query($conexion,"SELECT count(*) AS cantidad, $monto FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$tminicio."' AND '".$tmfin."' AND id_categoria_espacio = '".$id_categoria."'");

	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
	 		$centros = mysqli_query($conexion,"SELECT count(*) AS cantidad, $monto FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$sminicio."' AND '".$smfin."' AND id_categoria_espacio = '".$id_categoria."'");
	 	}else{
	 		$centros = mysqli_query($conexion,"SELECT count(*) AS cantidad, $monto FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND id_categoria_espacio = '".$id_categoria."'");
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