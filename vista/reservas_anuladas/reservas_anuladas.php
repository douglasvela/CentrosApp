<!DOCTYPE html>
<html>
<head>  
	<script type="text/javascript">
		
		function mostrarReporte(){
	        	var formData = new FormData();
		       formData.append("id_centro", $("#id_centro_anulada").val());

		        $.ajax({
		              //url: "http://192.168.0.16/viaticoapp/indicadores_inicio.php",
		              url: "http://centros.proyectotesisuesfmp.com/controlador/reservas_anuladas.php",
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
	                <h3 class="text-themecolor m-b-0 m-t-0">Reservas Anuladas</h3>
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
	                            <h5>Centro de Recreación: <span class="text-danger">*</span></h5> 
	                            <select id="id_centro_anulada" name="id_centro_anulada" class="select2" style="width: 100%">
	                            	<?php 
	                            	$conexion = mysqli_connect("162.241.252.245","proyedk4_WPZF0","MAYO_nesa94","proyedk4_WPZF0"); 
                                    $query_consulta_centros=mysqli_query($conexion,"select * from cdr_centro"); 
                                    while( $fila_centros=mysqli_fetch_array($query_consulta_centros)){
                                            $indicador_centros[] = $fila_centros;
                                            }
                                    foreach ($indicador_centros as $indicador_centros_fila) {
                                    	echo "<option class='m-l-50' value='$indicador_centros_fila[0]'>".$indicador_centros_fila[1]."</option>";
	                            	?>
	                            	<?php }?>
	                            </select>
	                        </div>
                            <div align="right">
                            <button type="button" onclick="mostrarReporte()" class="btn waves-effect waves-light btn-success2"><i class="ti-clipboard"></i> Consultar</button>
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
 <script> jQuery(document).ready(function() { 
        $(".container-fluid").css("padding",'3');
     }); </script>
</html>