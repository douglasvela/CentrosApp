<html>
<head>
	<title></title>
</head>
<body>
<?php
$nr = $_POST['nr'];
$id_centro = $_POST['id_centro'];
	$conexion = mysqli_connect("162.241.252.245","proyedk4_WPZF0","MAYO_nesa94","proyedk4_WPZF0"); 
	$query_consulta_incompleta=mysqli_query($conexion,"SELECT count(r.id_reserva) as numero from cdr_reserva as r where r.id_centro='".$id_centro."' and r.estado2='1' and r.estado=1"); 
	while( $fila_incompleta=mysqli_fetch_array($query_consulta_incompleta)){
			$indicador_incompleta[] = $fila_incompleta;
			}
	foreach ($indicador_incompleta as $indicador_fila_incompleta) {}

    $query_consulta_no_confir=mysqli_query($conexion,"SELECT count(r.id_reserva) as numero from cdr_reserva as r where r.id_centro='".$id_centro."' and r.estado2='1' and r.estado=2"); 
    while( $fila_no_confir=mysqli_fetch_array($query_consulta_no_confir)){
            $indicador_no_confir[] = $fila_no_confir;
            }
    foreach ($indicador_no_confir as $indicador_no_confir_fila) {}

    $query_consulta_confir=mysqli_query($conexion,"SELECT count(r.id_reserva) as numero from cdr_reserva as r where r.id_centro='".$id_centro."' and r.estado2='1' and r.estado=3"); 
    while( $fila_confir=mysqli_fetch_array($query_consulta_confir)){
            $indicador_confir[] = $fila_confir;
            }
    foreach ($indicador_confir as $indicador_confir_fila) {}

	
?>
<div class="container-fluid"> <br>
    <div class="row"><div class="col-lg-12 col-md-4">
                            <div class="form-group">
                                <select class="select2" style="width:100%" name="id_centro" id="id_centro" onchange="">
                                <?php
                                    $query_consulta_centros=mysqli_query($conexion,"select * from cdr_centro"); 
                                    while( $fila_centros=mysqli_fetch_array($query_consulta_centros)){
                                            $indicador_centros[] = $fila_centros;
                                            }
                                    foreach ($indicador_centros as $indicador_centros_fila) {
                                        
                                        
                                    ?>
                                        <option value="<?php echo $indicador_centros_fila[0];?>"><?php echo $indicador_centros_fila[1];?></option>
                                    <?php
                                        }
                                    ?>
                            </select>
                            </div>
                        </div>
                        </div>
	<div class="row">       	
		<div class="col-lg-4 col-md-4">
                <div class="card card-inverse card-warning" onclick="">
                    <div class="card-body">
                        
                        <div class="d-flex">
                            <div class="m-r-20 align-self-center">
                                <h1 class="text-white"><i class="ti-notepad"></i></h1></div>
                                 <div>
                                   <h3 class="card-title">Solicitudes Incompletas</h3>
                                 </div>
                            </div>
                            <div class="row">
                                <div style="text-align: center;" class="col-8 p-t-10 p-b-20 align-self-center">
                                    <h1 class="font-light text-white" id=""><?php echo $indicador_fila_incompleta[0];?></h1> </div>
                            </div>
                     </div>
                </div>
            </div>
        <div class="col-lg-4 col-md-4">
                <div class="card card-inverse card-danger" onclick="">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="m-r-20 align-self-center">
                                <h1 class="text-white"><i class="ti-money"></i></h1></div>
                                 <div>
                                   <h4 class="card-title">Solicitudes No Confirmadas</h4>
                                 </div>
                            </div>
                            <div class="row">
                                 <div style="text-align: center;" class="col-8 p-t-10 p-b-20 align-self-center">
                                  <h1 class="font-light text-white" id=""><?php echo $indicador_no_confir_fila[0];?></h1> 
                                </div>
                            </div>
                     </div>
                </div>
            </div>
        <div class="col-lg-4 col-md-4">
                <div class="card card-inverse card-success" onclick="">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="m-r-20 align-self-center">
                                <h1 class="text-white"><i class="ti-check-box"></i></h1></div>
                                 <div>
                                   <h3 class="card-title">Solicitudes Confirmadas</h3>
                                 </div>
                            </div>
                            <div class="row">
                                <div style="text-align: center;" class="col-8 p-t-10 p-b-20 align-self-center">
                                    <h1 class="font-light text-white" id=""><?php echo $indicador_confir_fila[0];?></h1> </div>
                            </div>
                     </div>
                </div>
            </div>
	</div>
</div>
</body>
</html>