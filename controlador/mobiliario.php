<?php
$id_centro = $_POST['id_centro']; 

$data  = array('id_centro' => $id_centro);

echo reporte($data);
 

function reporte($data){

    $conexion = mysqli_connect("162.241.252.245","proyedk4_WPZF0","MAYO_nesa94","proyedk4_WPZF0"); 
     
    $cabecera_vista = '
	 	<table class="table" style="font-size: 14px;">
		 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
		 		
				<td align="center" style="font-size: 15px; font-weight: bold; vertical-align: center; line-height: 1.5;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					SECCIÓN DE CENTROS DE RECREACIÓN <br>
					<span style="font-size: 12px;">INFORME DE ARTÍCULOS DAÑADOS</span>
				</td>
				
		 	</tr>
	 	</table><br>';
	 	$cuerpo = ""; 
		$cuerpo .= ' <div class="table-responsive">
			<table  style="font-size:12" class="table table-striped">
				<thead>
					<tr align="center">
						<th align="center">Tipo</th>
						<th align="center">Cantidad</th> 
						<th align="center">Cantidad Utilizada</th> 
						<th align="center">Cantidad Dañada</th> 
					</tr>
				</thead>
				<tbody>';
 

				$categorias = obtener_categorias($data,$conexion);
				if($categorias){ $contador = 0;
					foreach ($categorias as $fila_art) {
						$cuerpo .= '
							<tr>
							<td align="center">'.$fila_art['descripcion'].'</td> ';
						$data1 = array('id_centro' =>  $data['id_centro'],'id_categoria'=>$fila_art['id_categoria']);
						$cantidad = obtener_cantidades($data1,$conexion); 		
						foreach ($cantidad as $cantidad_datos) {	
							$cuerpo .= '
								<td align="center" >'.$cantidad_datos['cantidad'].'</td>
								<td align="center" >'.$cantidad_datos['cantidad_usada'].'</td> 
								<td align="center" >'.$cantidad_datos['cantidad_danada'].'</td> 
							';
						}
						$cuerpo .= '</tr>';
					}
				}

				 
				$cuerpo .= '	
				</tbody>
			</table></div>';


    mysqli_close($conexion);
    echo $cabecera_vista.=$cuerpo;
}
  
 
  function obtener_categorias($data,$conexion){
    
        $query_cat = mysqli_query($conexion,"SELECT c.* FROM cdr_categoria AS c WHERE c.id_tipo_categoria = '6'");
       
        while( $query_fila_cat=mysqli_fetch_array($query_cat,MYSQLI_ASSOC)){
	            $data_cat[] = $query_fila_cat;
	         }
        return $data_cat;
    }
    function obtener_cantidades($data,$conexion){
    
        $query_c = mysqli_query($conexion,"SELECT * from cdr_mobiliario where id_categoria='".$data['id_categoria']."' and id_centro='".$data['id_centro']."'");
        while( $query_fila_c=mysqli_fetch_array($query_c,MYSQLI_ASSOC)){
	            $data_c[] = $query_fila_c;
	         }
        return $data_c;
    }