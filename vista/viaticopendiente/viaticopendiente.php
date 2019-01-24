<!DOCTYPE html>
<html>
<head>
	 
	<script type="text/javascript">

		function mostrarReporteVPendiente(funcion){
	       var formData = new FormData();
	       formData.append("funcion", funcion);
	       formData.append("id_empleado", $("#id_empleado").val());
	        $.ajax({
	              //url: "http://192.168.0.16/viaticoapp/indicadores_inicio.php",
	              url: "http://viaticos.proyectotesisuesfmp.com/controlador/viaticopendiente.php",
	              type: "post",
	              dataType: "html",
	              data: formData,
	              crossDomain: true,
	              cache: false,
	              contentType: false,
	              processData: false
	          })
	          .done(function(res1){
	            $("#informe_vista").html(res1);
	          }); 
	     }
	</script>
</head>
<body>
	    <div class="container-fluid">
	        <div class="row page-titles">
	            <div class="align-self-center" align="center">
	                <h3 class="text-themecolor m-b-0 m-t-0">Viaticos Pendientes de Pago</h3>
	            </div>
	        </div>
	         <div class="row " id="cnt_form">
	            <div class="col-lg-4"  style="display: block;">
	                <div class="card">
	                    <div class="card-header bg-success2" id="">
	                        <h4 class="card-title m-b-0 text-white">Datos</h4>
	                    </div>
	                    <div class="card-body b-t">
	                    	<div class="form-group">
							 	<h5>Empleado: <span class="text-danger">*</span></h5>
                                <select id="id_empleado" name="id_empleado" class="select2" onchange="" style="width: 100%" required>
                                <option value=''>[Elija el empleado]</option>
                                <?php
                                	$usuario_sesion = $_POST['usuario'];
                                	$server   = "162.241.252.245";
							      	$database = "proyedk4_WPZF0";
							        $usuario  = "proyedk4_WPZF0";
							        $clave    = "MAYO_nesa94"; 

							      $conexion = mysqli_connect($server,$usuario,$clave,$database); 
									$query_consulta_nr=mysqli_query($conexion,"SELECT * FROM sir_empleado");

									$query_nr_usuario_session=mysqli_query($conexion,"SELECT nr FROM org_usuario where usuario = '".$usuario_sesion."'");
									while( $fila=mysqli_fetch_array($query_nr_usuario_session)){
							            $usuario_nr_sess[] = $fila;
							         }
									foreach ($usuario_nr_sess as $fila_session_usuario) {}	


							      	while( $fila=mysqli_fetch_array($query_consulta_nr)){
							            $empleado_NR_viatico[] = $fila;
							         }
									foreach ($empleado_NR_viatico as $fila2) {	
								?>
						<option class="m-l-50" value="<?php echo $fila2[7]; ?>" <?php if(isset($fila_session_usuario)){ if($fila2[7]==$fila_session_usuario[0]){ echo "selected"; }} ?>><?php echo preg_replace ('/[ ]+/', ' ',$fila2[1]." ".$fila2[2]." ".$fila2[4]." ".$fila2[5]) ?></option>
								<?php
									} mysqli_close($conexion);
                                ?>
                                </select>
                            </div>
                            <div align="right">
                            <button type="button" onclick="mostrarReporteVPendiente('reporte_viatico_pendiente_empleado')" class="btn waves-effect waves-light btn-success2"><i class="ti-clipboard"></i> Consultar</button>
                            </div>
	                    </div>
	                </div>
	            </div>
	            <div class="col-lg-8" id="cnt_form" style="display: block;">
	                <div class="card"> 
	                    <div class=""  >
							 <!-- <embed src="" width="770" height="400"> -->
								<div id="informe_vista"></div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
</body>
 
</html>