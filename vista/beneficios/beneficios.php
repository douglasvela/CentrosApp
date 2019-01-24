<!DOCTYPE html>
<html>
<head>
	 
	<script type="text/javascript">
		$( document ).ready(function() {
		    mostrarReporte('reporte');
		});
		function mostrarReporte(funcion){
	       var formData = new FormData();
	       formData.append("funcion", funcion);
	       formData.append("nr", localStorage.getItem('nr')); 
	        $.ajax({
	              //url: "http://192.168.0.16/viaticoapp/indicadores_inicio.php",
	              url: "http://persona.proyectotesisuesfmp.com/controlador/beneficios.php",
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
<body onload="">
	    <div class="container-fluid">
	        <div class="row page-titles">
	            <div class="align-self-center" align="center">
	                <h3 class="text-themecolor m-b-0 m-t-0">Beneficios</h3>
	            </div>
	        </div>
	         <div class="row " id="cnt_form">
	            <div class="col-lg-4"  style="display: block;">
	                <div class="card" style="display: none;">
	                    <div class="card-header bg-success2" id="">
	                        <h4 class="card-title m-b-0 text-white">Datos</h4>
	                    </div>
	                    <div class="card-body b-t" >
	                    	<div class="demo-radio-button">
                        	<h5>Periodo: <span class="text-danger"></span></h5>
                        	<div class="d-flex flex-row">
                                <div class="p-0" style="width: 50%;">
                                    <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_mensual" checked="">
		                            <label for="radio_mensual">Mensual</label>
		                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_trimestral">
		                            <label for="radio_trimestral">Trimestral</label>
		                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_semestral">
		                            <label for="radio_semestral">Semestral</label>
                                </div>
                                <div class="p-0" style="width: 50%;">
                                	<input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_anual">
		                            <label for="radio_anual">Anual</label>
		                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_periodo">
		                            <label for="radio_periodo">Perido</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="input_anio">
                            <h5>Año: <span class="text-danger">*</span></h5>
                            <input type="text" value="<?php echo date('Y'); ?>" class="date-own form-control" id="anio_actual" name="anio_actual" placeholder="yyyy">
                        </div>
                        <div class="form-group" id="input_mes">
                            <h5>Mes: <span class="text-danger"></span></h5>
                            <select id="mes" name="mes" class="select2" onchange="" style="width: 100%" >
                                <option value="0">[Seleccione]</option>
                                <option class="m-l-50" value="1">Enero</option>
                                <option class="m-l-50" value="2">Febrero</option>
                                <option class="m-l-50" value="3">Marzo</option>
                                <option class="m-l-50" value="4">Abril</option>
                                <option class="m-l-50" value="5">Mayo</option>
                                <option class="m-l-50" value="6">Junio</option>
                                <option class="m-l-50" value="7">Julio</option>
                                <option class="m-l-50" value="8">Agosto</option>
                                <option class="m-l-50" value="9">Septiembre</option>
                                <option class="m-l-50" value="10">Octubre</option>
                                <option class="m-l-50" value="11">Noviembre</option>
                                <option class="m-l-50" value="12">Diciembre</option>
                            </select>
                        </div>
                        <div class="form-group" id="input_trimestre" style="display:none">
                            <h5>Trimestre: <span class="text-danger"></span></h5>
                            <select id="trimestre" name="trimestre" class="select2" onchange="" style="width: 100%" >
                                <option value="0">[Seleccione]</option>
                                <option class="m-l-50" value="1">1er Trimestre</option>
                                <option class="m-l-50" value="2">2do Trimestre</option>
                                <option class="m-l-50" value="3">3er Trimestre</option>
                                <option class="m-l-50" value="4">4ta Trimestre</option>
                            </select>
                        </div>
                        <div class="form-group" id="input_semestre" style="display:none">
                            <h5>Semestre: <span class="text-danger"></span></h5>
                            <select id="semestre" name="semestre" class="select2" onchange="" style="width: 100%" >
                                <option value="0">[Seleccione]</option>
                                <option class="m-l-50" value="1">1er Semestre</option>
                                <option class="m-l-50" value="2">2do Semestre</option>
                            </select>
                        </div>
                        <div id="input_periodo" style="display:none">
                        	<div class="form-group">
	                            <h5>Inicio periodo: <span class="text-danger">*</span></h5>
	                            <input type="date" class="form-control" readonly id="fecha_inicio" name="fecha_inicio">
	                        </div>
	                        <div class="form-group">
	                            <h5>Fin periodo: <span class="text-danger">*</span></h5>
	                            <input type="date" class="form-control" readonly id="fecha_fin" name="fecha_fin">
	                        </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label for="id_empleado">Persona empleada:</label>
                                <select id="id_empleado" name="id_empleado" class="select2 form-control" style="width: 100%" required="">
			                        <option value="">[Seleccione]</option>
			                        <?php 

			                        $usuario_sesion = $_POST['nr'];
                                	$server   = "162.241.252.245";
							      	$database = "proyedk4_WPZF0";
							        $usuario  = "proyedk4_WPZF0";
							        $clave    = "MAYO_nesa94"; 

							      $conexion = mysqli_connect($server,$usuario,$clave,$database); 
									$query_consulta_nr=mysqli_query($conexion,"SELECT e.id_empleado, e.nr, UPPER(CONCAT_WS(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) AS nombre_completo FROM sir_empleado AS e WHERE e.id_estado = '00001' ORDER BY e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada");

									while( $fila_u=mysqli_fetch_array($query_consulta_nr)){
							            $usuarios_emp[] = $fila_u;
							         }
									foreach ($usuarios_emp as $fila) {              
			                                   echo '<option class="m-l-50" value="'.$fila[0].'"  >'.preg_replace ('/[ ]+/', ' ', $fila[2].' - '.$fila[1]).'</option>';
			                                }
			                            
			                        ?>
			                    </select>
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