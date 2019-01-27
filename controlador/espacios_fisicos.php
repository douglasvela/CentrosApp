<?php
$id_centro = $_POST['id_centro']; 
$id_tipo_categoria = $_POST['id_tipo_categoria']; 
$data  = array('id_centro' => $id_centro,'id_tipo_categoria'=>$id_tipo_categoria);

echo reporte($data);
 

function reporte($data){

    $conexion = mysqli_connect("162.241.252.245","proyedk4_WPZF0","MAYO_nesa94","proyedk4_WPZF0"); 
     
    $cabecera_vista = '
	 	<table class="table" style="font-size: 14px;">
		 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
		 		
				<td align="center" style="font-size: 15px; font-weight: bold; vertical-align: center; line-height: 1.5;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					SECCIÓN DE CENTROS DE RECREACIÓN <br>
					<span style="font-size: 12px;">INFORME DE ESPACIOS FÍSICOS POR CENTRO</span>
				</td>
				
		 	</tr>
	 	</table><br>';
	 	$cuerpo = ""; 
		$cuerpo .= ' <div class="table-responsive">
			<table  style="font-size:12" class="table table-striped">
				<thead>
					<tr align="center">
					<th align="center">#</th>
						<th align="center">Descripción</th>
						<th align="center">Cantidad</th> 
					</tr>
				</thead>
				<tbody>';
 

				$categorias = obtener_categorias($data,$conexion);
				if($categorias){ $contador = 0;
					foreach ($categorias as $fila_cat) { 			
						$data1  = array('id_centro' =>  $data['id_centro'],'id_categoria'=>$fila_cat['id_categoria']);	
						$espacios = obtener_espacios_fisicos($data1,$conexion);$contador++;
						$cuerpo .= '
						<tr> 
							<td align="center">'.$contador.'</td>
							<td align="center" >'.$fila_cat['descripcion'].'</td>
							<td align="center" >'.$espacios.'</td> 
						</tr>';
					}
				}

				 
				$cuerpo .= '	
				</tbody>
			</table></div>';


    mysqli_close($conexion);
    echo $cabecera_vista.=$cuerpo;
}
  
 
  function obtener_categorias($data,$conexion){
    
        $query_cat = mysqli_query($conexion,"SELECT c.* FROM cdr_categoria AS c WHERE c.id_tipo_categoria = '".$data["id_tipo_categoria"]."'");
       
        while( $query_fila_cat=mysqli_fetch_array($query_cat,MYSQLI_ASSOC)){
	            $data_cat[] = $query_fila_cat;
	         }
        return $data_cat;
    }
    function obtener_espacios_fisicos($data,$conexion){
    
        $query = mysqli_query($conexion,"SELECT * FROM cdr_espacio_fisico WHERE id_categoria = '".$data['id_categoria']."' AND id_centro = '".$data['id_centro']."'");
       $cantidad = mysqli_num_rows($query);
        while( $query_fila=mysqli_fetch_array($query,MYSQLI_ASSOC)){
	            $data[] = $query_fila;
	         }
        return $cantidad;
    }