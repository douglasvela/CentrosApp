<?php
$anio = $_POST['anio'];
$tipo = $_POST['type'];
$value = $_POST['value'];
$data  = array('tipo' => $tipo, 'value'=>$value,'anio'=>$anio);

echo reporte_viatico_pagado_empleado($data);
 

function reporte_viatico_pagado_empleado($data){

      $conexion = mysqli_connect("162.241.252.245","proyedk4_WPZF0","MAYO_nesa94","proyedk4_WPZF0"); 
      $mes = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
    $cabecera_vista = '
	 	<table style="width: 100%;" class="table">
		 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
		 		
				<td align="center" style="font-size: 15px; font-weight: bold; vertical-align: center; line-height: 1.5;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					OFICINA DE ESTADÍSTICA E INFORMÁTICA <br>
					INFORME '.strtoupper($data["tipo"]).' DIRECCIÓN ADMINISTRATIVA
				</td>
				
		 	</tr>
	 	</table><br>';
	 	$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );

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
	 	//$categoria_visita = $this->reportes_model->obtener_categoria_visitantes('pagado');

	 	$query_categoria_visita=mysqli_query($conexion,"SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '2' AND id_categoria IN (SELECT t.id_categoria_cdr_tarifas FROM cdr_tarifas AS t WHERE t.precio_cdr_tarifas > 0)");

      	while( $fila_categoria_visita=mysqli_fetch_array($query_categoria_visita,MYSQLI_ASSOC)){
            $categoria_visita[] = $fila_categoria_visita;
         }
		
	 	$cuerpo .= ' <div class="table-responsive">
			<table style="font-size:12;" class="table table-striped">
				<thead>
					<tr>
						<th align="center"></th>';		
		
		if(mysqli_num_rows($query_categoria_visita)>0){
			foreach ($categoria_visita as $filac) {
				$cuerpo .= '<th align="center" colspan="3" width="150px;">'.$filac['nombre_corto'].'</th>';
				array_push($ids_categorias, $filac['id_categoria']);
			}
		}
		$cuerpo .= '</tr><tr><th align="center">N° de visitas</th>';
		if(mysqli_num_rows($query_categoria_visita)>0){
			foreach ($categoria_visita as $filac) {
				array_push($sumas, 0);
				array_push($sumas, 0);
				array_push($sumas, 0);
				$cuerpo .= '<th align="center">Total</th>';
				$cuerpo .= '<th align="center">M</th>';
				$cuerpo .= '<th align="center">F</th>';
			}
		}
		$cuerpo .= '</tr></thead><tbody>';

		$datos = array();
	 	//$centro = $this->reportes_model->obtener_centros();
	 	$query_centro=mysqli_query($conexion,"SELECT * FROM cdr_centro ORDER BY nombre ASC");

      	while( $query_centro_fila=mysqli_fetch_array($query_centro,MYSQLI_ASSOC)){
            $centro[] = $query_centro_fila;
         }

        if(mysqli_num_rows($query_centro)>0){
			foreach ($centro as $filas) {
				$cuerpo .= '<tr>';
				$data["id_centro"] = $filas['id_centro'];
				$cuerpo .= '<td align="center">'.$filas['nickname'].'</td>';
				$j = 0;
				for ($i = 0; $i < count($ids_categorias); $i++) {
					$visitas_centros = obtener_cantidad_visitante($data, "normal", $ids_categorias[$i],$conexion);
					if(($visitas_centros)){
						foreach ($visitas_centros as $filav) {
							$sumas[$j] += intval($filav['cant_masculino']+$filav['cant_femenino']); $j++;
							$sumas[$j] += intval($filav['cant_masculino']); $j++;
							$sumas[$j] += intval($filav['cant_femenino']); $j++;
							$cuerpo .= '<td align="center">'.intval($filav['cant_masculino']+$filav['cant_femenino']).'</td>';
							$cuerpo .= '<td align="center">'.intval($filav['cant_masculino']).'</td>';
							$cuerpo .= '<td align="center">'.intval($filav['cant_femenino']).'</td>';
						}
					}
				}
				
				$cuerpo .= '</tr>';
			}
		}

		$cuerpo .= '<tr>';
		$data["id_centro"] = "";
		$cuerpo .= '<td align="center">Convenios</td>';
		$j = 0;
		for ($i = 0; $i < count($ids_categorias); $i++) {
			$visitas_centros = obtener_cantidad_visitante($data, "convenios", $ids_categorias[$i],$conexion);
			if($visitas_centros){
				foreach ($visitas_centros as $filav) {
					$sumas[$j] += intval($filav['cant_masculino']+$filav['cant_femenino']); $j++;
					$sumas[$j] += intval($filav['cant_masculino']); $j++;
					$sumas[$j] += intval($filav['cant_femenino']); $j++;
					$cuerpo .= '<td align="center">'.intval($filav['cant_masculino']+$filav['cant_femenino']).'</td>';
					$cuerpo .= '<td align="center">'.intval($filav['cant_masculino']).'</td>';
					$cuerpo .= '<td align="center">'.intval($filav['cant_femenino']).'</td>';
				}
			}
		}

		$cuerpo .= '<tr>';
		$data["id_centro"] = "";
		$cuerpo .= '<td align="center">Exonerados por despacho</td>';
		$j = 0;
		for ($i = 0; $i < count($ids_categorias); $i++) {
			$visitas_centros = obtener_cantidad_visitante($data, "despacho", $ids_categorias[$i],$conexion);
			if($visitas_centros){
				foreach ($visitas_centros as $filav) {
					$sumas[$j] += intval($filav['cant_masculino']+$filav['cant_femenino']); $j++;
					$sumas[$j] += intval($filav['cant_masculino']); $j++;
					$sumas[$j] += intval($filav['cant_femenino']); $j++;
					$cuerpo .= '<td align="center">'.intval($filav['cant_masculino']+$filav['cant_femenino']).'</td>';
					$cuerpo .= '<td align="center">'.intval($filav['cant_masculino']).'</td>';
					$cuerpo .= '<td align="center">'.intval($filav['cant_femenino']).'</td>';
				}
			}
		}

		$cuerpo .= '</tr>';

		$cuerpo .= '<tr><th align="center">Total de Visitantes</th>';
		for ($i = 0; $i < count($sumas); $i++) {
			$cuerpo .= '<th align="center">'.intval($sumas[$i]).'</th>';
		}
		$cuerpo .= '</tr>';

				$cuerpo .= '	
				</tbody>
			</table></div><br>';

		$ids_categorias = array();
	 	$sumas = array();



	 	$categoria_visita = obtener_categoria_visitantes('gratis',$conexion);

	 	$cuerpo .= ' <div class="table-responsive">
			<table  style="font-size:12;" class="table table-striped">
				<thead>
					<tr>
						<th align="center"></th>';					
					

		if($categoria_visita){
			foreach ($categoria_visita as $filac) {
				$cuerpo .= '<th align="center" colspan="3" width="150px;">'.$filac['nombre_corto'].'</th>';
				array_push($ids_categorias, $filac['id_categoria']);
			}
		}
		$cuerpo .= '</tr><tr><th align="center">N° de visitas</th>';
		if($categoria_visita){
			foreach ($categoria_visita as $filac) {
				array_push($sumas, 0);
				array_push($sumas, 0);
				array_push($sumas, 0);
				$cuerpo .= '<th align="center">Total</th>';
				$cuerpo .= '<th align="center">M</th>';
				$cuerpo .= '<th align="center">F</th>';
			}
		}

		$cuerpo .= '</tr></thead><tbody>';

		$datos = array();
	 	$centro = obtener_centros($conexion);
		if($centro){
			foreach ($centro as $filas) {
				$cuerpo .= '<tr>';
				$data["id_centro"] = $filas['id_centro'];
				$cuerpo .= '<td align="center">'.$filas['nickname'].'</td>';
				$j = 0;
				for ($i = 0; $i < count($ids_categorias); $i++) {
					$visitas_centros = obtener_cantidad_visitante($data, "normal", $ids_categorias[$i],$conexion);
					if($visitas_centros){
						foreach ($visitas_centros as $filav) {
							$sumas[$j] += intval($filav['cant_masculino']+$filav['cant_femenino']); $j++;
							$sumas[$j] += intval($filav['cant_masculino']); $j++;
							$sumas[$j] += intval($filav['cant_femenino']); $j++;
							$cuerpo .= '<td align="center">'.intval($filav['cant_masculino']+$filav['cant_femenino']).'</td>';
							$cuerpo .= '<td align="center">'.intval($filav['cant_masculino']).'</td>';
							$cuerpo .= '<td align="center">'.intval($filav['cant_femenino']).'</td>';
						}
					}
				}
				
				$cuerpo .= '</tr>';
			}
		}


		$cuerpo .= '<tr>';
		$data["id_centro"] = "";
		$cuerpo .= '<td align="center">Convenios</td>';
		$j = 0;
		for ($i = 0; $i < count($ids_categorias); $i++) {
			$visitas_centros = obtener_cantidad_visitante($data, "convenios", $ids_categorias[$i],$conexion);
			if($visitas_centros){
				foreach ($visitas_centros as $filav) {
					$sumas[$j] += intval($filav['cant_masculino']+$filav['cant_femenino']); $j++;
					$sumas[$j] += intval($filav['cant_masculino']); $j++;
					$sumas[$j] += intval($filav['cant_femenino']); $j++;
					$cuerpo .= '<td align="center">'.intval($filav['cant_masculino']+$filav['cant_femenino']).'</td>';
					$cuerpo .= '<td align="center">'.intval($filav['cant_masculino']).'</td>';
					$cuerpo .= '<td align="center">'.intval($filav['cant_femenino']).'</td>';
				}
			}
		}

		$cuerpo .= '<tr>';
		$data["id_centro"] = "";
		$cuerpo .= '<td align="center">Exonerados por despacho</td>';
		$j = 0;
		for ($i = 0; $i < count($ids_categorias); $i++) {
			$visitas_centros = obtener_cantidad_visitante($data, "despacho", $ids_categorias[$i],$conexion);
			if($visitas_centros){
				foreach ($visitas_centros as $filav) {
					$sumas[$j] += intval($filav['cant_masculino']+$filav['cant_femenino']); $j++;
					$sumas[$j] += intval($filav['cant_masculino']); $j++;
					$sumas[$j] += intval($filav['cant_femenino']); $j++;
					$cuerpo .= '<td align="center">'.intval($filav['cant_masculino']+$filav['cant_femenino']).'</td>';
					$cuerpo .= '<td align="center">'.intval($filav['cant_masculino']).'</td>';
					$cuerpo .= '<td align="center">'.intval($filav['cant_femenino']).'</td>';
				}
			}
		}

		$cuerpo .= '</tr>';

		$cuerpo .= '<tr><th align="center">Total de Visitantes</th>';
		for ($i = 0; $i < count($sumas); $i++) {
			$cuerpo .= '<th align="center">'.intval($sumas[$i]).'</th>';
		}
		$cuerpo .= '</tr>';

				$cuerpo .= '	
				</tbody>
			</table></div><br>';

		$cuerpo .= ' <div class="table-responsive">
			<table  style="font-size:12;" class="table table-striped">
				<thead>
					<tr>
						<th align="center"></th>
						<th align="center" colspan="3">Total por centro de recreación</th>
					</tr>
					<tr>
						<th align="center">N° de visistas</th>
						<th align="center">Total</th>
						<th align="center">Masculino</th>
						<th align="center">Femenino</th>
					</tr>
				</thead>
				<tbody>';

		$datos = array();
	 	$centro = obtener_centros($conexion);
		$total_visitante = 0;
		$total_masculino = 0;
		$total_femenino = 0;
		if($centro){
			foreach ($centro as $filas) {
				$data["id_centro"] = $filas['id_centro'];						
				$visitas_centros = obtener_cantidad_visitas_totales($data, "normal",$conexion);
				if($visitas_centros){
					foreach ($visitas_centros as $filaic) {
						$total_visitante += intval($filaic['cant_masculino']+$filaic['cant_femenino']);
						$total_masculino += intval($filaic['cant_masculino']);
						$total_femenino += intval($filaic['cant_femenino']);

						$cuerpo .= '
						<tr>
							<td align="center" style="width:250px;">'.$filas['nickname'].'</td>
							<td align="center" style="width:120px;">'.intval($filaic['cant_masculino']+$filaic['cant_femenino']).'</td>
							<td align="center" style="width:120px;">'.intval($filaic['cant_masculino']).'</td>
							<td align="center" style="width:120px;">'.intval($filaic['cant_femenino']).'</td>
						</tr>';
					}
				}
			}
		}

		$visitas_centros = obtener_cantidad_visitas_totales($data, "convenios",$conexion);
		if($visitas_centros){
			foreach ($visitas_centros as $filaic) {
				$total_visitante += intval($filaic['cant_masculino']+$filaic['cant_femenino']);
				$total_masculino += intval($filaic['cant_masculino']);
				$total_femenino += intval($filaic['cant_femenino']);

				$cuerpo .= '
				<tr>
					<td align="center" style="width:250px;">Convenios</td>
					<td align="center" style="width:120px;">'.intval($filaic['cant_masculino']+$filaic['cant_femenino']).'</td>
					<td align="center" style="width:120px;">'.intval($filaic['cant_masculino']).'</td>
					<td align="center" style="width:120px;">'.intval($filaic['cant_femenino']).'</td>
				</tr>';
			}
		}

		$visitas_centros = obtener_cantidad_visitas_totales($data, "despacho",$conexion);
		if($visitas_centros){
			foreach ($visitas_centros as $filaic) {
				$total_visitante += intval($filaic['cant_masculino']+$filaic['cant_femenino']);
				$total_masculino += intval($filaic['cant_masculino']);
				$total_femenino += intval($filaic['cant_femenino']);

				$cuerpo .= '
				<tr>
					<td align="center" style="width:250px;">Exoneraciones por despacho</td>
					<td align="center" style="width:120px;">'.intval($filaic['cant_masculino']+$filaic['cant_femenino']).'</td>
					<td align="center" style="width:120px;">'.intval($filaic['cant_masculino']).'</td>
					<td align="center" style="width:120px;">'.intval($filaic['cant_femenino']).'</td>
				</tr>';
			}
		}
		$cuerpo .= '<tr>
						<th align="center" style="width:250px;">Total personas usuarias</th>
						<th align="center" style="width:120px;">'.$total_visitante.'</th>
						<th align="center" style="width:120px;">'.$total_masculino.'</th>
						<th align="center" style="width:120px;">'.$total_femenino.'</th>
					</tr>';
		$cuerpo .= '<tr style="font-size: 14px;">
						<th align="center" style="width:250px; font-size: 16px;">TOTAL DE VISITAS</th>
						<th align="center" style="width:120px; font-size: 16px;" colspan="3">'.$total_visitante.'</th>
					</tr>';

				$cuerpo .= '	
				</tbody>
			</table></div><br>';



         mysqli_close($conexion);
      echo $cabecera_vista.=$cuerpo;
     
}
	function obtener_cantidad_visitas_totales($data, $tipo,$conexion){
		if($tipo == "normal"){ /* TODOS LOS INGRESOS QUE NO ESTEN EXONERADOS */
			$cnt_fem = "dr.cant_femenino";
			$cnt_mas = "dr.cant_masculino";
			$data['id_centro'] = "AND r.id_centro = '".$data["id_centro"]."'";
		}else if($tipo == "despacho"){ /* EXONERADOS EN CATEGORIAS EXTRAS */
			$cnt_fem = "dr.cant_femenino_exo_ministra";
			$cnt_mas = "dr.cant_masculino_exo_ministra";
			$data['id_centro'] = "AND dr.id_exoneracion_tipo = '1'";
		}else if($tipo == "convenios"){ /* SOLO CONVENIOS */
			$cnt_fem = "dr.cant_femenino_exo_ministra";
			$cnt_mas = "dr.cant_masculino_exo_ministra";
			$data['id_centro'] = "AND dr.id_exoneracion_tipo = '2'";
		}

		if($data["tipo"] == "mensual"){
	 		$centros = mysqli_query($conexion,"SELECT SUM(".$cnt_mas.") AS cant_masculino, SUM(".$cnt_fem.") AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) = '".$data["value"]."'");
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
	 		$centros = mysqli_query($conexion,"SELECT SUM(".$cnt_mas.") AS cant_masculino, SUM(".$cnt_fem.") AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$tminicio."' AND '".$tmfin."'");

	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
	 		$centros = mysqli_query($conexion,"SELECT SUM(".$cnt_mas.") AS cant_masculino, SUM(".$cnt_fem.") AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$sminicio."' AND '".$smfin."'");
	 	}else{
	 		$centros = mysqli_query($conexion,"SELECT SUM(".$cnt_mas.") AS cant_masculino, SUM(".$cnt_fem.") AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."'");
	 	}
        while( $fila=mysqli_fetch_array($centros,MYSQLI_ASSOC)){
	            $centros_data[] = $fila;
	         }
        return $centros_data;
    }
	function obtener_centros($conexion){
       // $centros = $this->db->query("SELECT * FROM `cdr_centro` ORDER BY nombre ASC");
        $query=mysqli_query($conexion,"SELECT * FROM `cdr_centro` ORDER BY nombre ASC");
	     	 while( $query_fila=mysqli_fetch_array($query,MYSQLI_ASSOC)){
	            $centros[] = $query_fila;
	         }
        return $centros;
    }
    function obtener_categoria_visitantes($tipo,$conexion){
    	if($tipo == "pagado"){
    		$query=mysqli_query($conexion,"SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '2' AND id_categoria IN (SELECT t.id_categoria_cdr_tarifas FROM cdr_tarifas AS t WHERE t.precio_cdr_tarifas > 0)");
	     	 while( $query_fila=mysqli_fetch_array($query,MYSQLI_ASSOC)){
	            $centros[] = $query_fila;
	         }
    		//$centros = $this->db->query("SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '2' AND id_categoria IN (SELECT t.id_categoria_cdr_tarifas FROM cdr_tarifas AS t WHERE t.precio_cdr_tarifas > 0)");
    	}elseif ($tipo == "gratis") {
    		$query=mysqli_query($conexion,"SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '2' AND id_categoria NOT IN (SELECT t.id_categoria_cdr_tarifas FROM cdr_tarifas AS t WHERE t.precio_cdr_tarifas > 0)");
	     	 while( $query_fila=mysqli_fetch_array($query,MYSQLI_ASSOC)){
	            $centros[] = $query_fila;
	         }
    		//$centros = $this->db->query("SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '2' AND id_categoria NOT IN (SELECT t.id_categoria_cdr_tarifas FROM cdr_tarifas AS t WHERE t.precio_cdr_tarifas > 0)");
    	}
        return $centros;
    }
function obtener_cantidad_visitante($data, $tipo, $id_categ_visi,$conexion){
		if($tipo == "normal"){ /* TODOS LOS INGRESOS QUE NO ESTEN EXONERADOS */
			$cnt_fem = "dr.cant_femenino";
			$cnt_mas = "dr.cant_masculino";
			$data['id_centro'] = "AND r.id_centro = '".$data["id_centro"]."'";
		}else if($tipo == "despacho"){ /* EXONERADOS EN CATEGORIAS EXTRAS */
			$cnt_fem = "dr.cant_femenino_exo_ministra";
			$cnt_mas = "dr.cant_masculino_exo_ministra";
			$data['id_centro'] = "AND dr.id_exoneracion_tipo = '1'";
		}else if($tipo == "convenios"){ /* SOLO CONVENIOS */
			$cnt_fem = "dr.cant_femenino_exo_ministra";
			$cnt_mas = "dr.cant_masculino_exo_ministra";
			$data['id_centro'] = "AND dr.id_exoneracion_tipo = '2'";
		}

		$id_categ_visi = "AND dr.id_categoria_espacio IN (".$id_categ_visi.")";

		if($data["tipo"] == "mensual"){
			$query_centro=mysqli_query($conexion,"SELECT SUM(".$cnt_mas.") AS cant_masculino, SUM(".$cnt_fem.") AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) = '".$data["value"]."' ".$id_categ_visi);
	     	 while( $query_centro_fila=mysqli_fetch_array($query_centro,MYSQLI_ASSOC)){
	            $centros[] = $query_centro_fila;
	         }
	 		//$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) = '".$data["value"]."' ".$id_categ_visi);
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
 			$query_centro=mysqli_query($conexion,"SELECT SUM(".$cnt_mas.") AS cant_masculino, SUM(".$cnt_fem.") AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$tminicio."' AND '".$tmfin."' ".$id_categ_visi);
	      	while( $query_centro_fila=mysqli_fetch_array($query_centro,MYSQLI_ASSOC)){
	            $centros[] = $query_centro_fila;
	         }
	 		//$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$tminicio."' AND '".$tmfin."' ".$id_categ_visi);

	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
 			$query_centro=mysqli_query($conexion,"SELECT SUM(".$cnt_mas.") AS cant_masculino, SUM(".$cnt_fem.") AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$sminicio."' AND '".$smfin."' ".$id_categ_visi);
	      	while( $query_centro_fila=mysqli_fetch_array($query_centro,MYSQLI_ASSOC)){
	            $centros[] = $query_centro_fila;
	         }
	 		//$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$sminicio."' AND '".$smfin."' ".$id_categ_visi);
	 	}else{
	 		$query_centro=mysqli_query($conexion,"SELECT SUM(".$cnt_mas.") AS cant_masculino, SUM(".$cnt_fem.") AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' ".$id_categ_visi);
	      	while( $query_centro_fila=mysqli_fetch_array($query_centro,MYSQLI_ASSOC)){
	            $centros[] = $query_centro_fila;
	         }
	 		//$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' ".$id_categ_visi);
	 	}
       
        return $centros;
    }
?>