<!DOCTYPE html>
<html>
<head>
	<link href="../../assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
	<script src="../../assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	 <script>
	  $(document).ready(function(){
	          $('.date-own').datepicker({
	            minViewMode: 2,
	            format: 'yyyy',
	            autoclose: true,
	            todayHighlight: true
	          });
	      });

	</script>
	<script type="text/javascript">
		function mostrar_ocultar_selects(){
	     	if(document.getElementById('radio_mensual').checked==true){
	     		document.getElementById("input_mes").style.display="block";
	     		document.getElementById("input_semestre").style.display="none";
	     		document.getElementById("input_trimestre").style.display="none";
	     	}else if(document.getElementById('radio_trimestral').checked==true){
	     		document.getElementById("input_mes").style.display="none";
	     		document.getElementById("input_semestre").style.display="none";
	     		document.getElementById("input_trimestre").style.display="block";
	     	}else if(document.getElementById('radio_semestral').checked==true){
	     		document.getElementById("input_mes").style.display="none";
	     		document.getElementById("input_semestre").style.display="block";
	     		document.getElementById("input_trimestre").style.display="none";
	     	}else if(document.getElementById('radio_anual').checked==true){
	     		document.getElementById("input_mes").style.display="none";
	     		document.getElementById("input_semestre").style.display="none";
	     		document.getElementById("input_trimestre").style.display="none";
	     	}
	    }
		function mostrarReporte(funcion){
			var type = "anual";
			var value = "";
	       	$anio = $("#anio_actual").val();
		    if(document.getElementById('radio_mensual').checked==true){
		    	value = $("#mes").val();
		    	type = "mensual";
		    }
	       	if(document.getElementById('radio_trimestral').checked==true){
	       		value = $("#trimestre").val();
	       		type = "trimestral";
	       	}
	       	if(document.getElementById('radio_semestral').checked==true){
	       		value = $("#semestre").val();
	       		type = "semestral";
	       	}
	        
	        if($anio!="" &&  (($("#mes").val()!="0" && document.getElementById('radio_mensual').checked==true)	
	        	|| ($("#trimestre").val()!="0" && document.getElementById('radio_trimestral').checked==true)	
	        	|| ($("#semestre").val()!="0" && document.getElementById('radio_semestral').checked==true)	
	        	|| document.getElementById('radio_anual').checked==true
	        )){
	        	var formData = new FormData();
		       formData.append("funcion", funcion);
		       formData.append("id_empleado", $("#id_empleado").val());
		       formData.append("fecha_min", $("#fecha_min").val());
		       formData.append("fecha_max", $("#fecha_max").val());
		        $.ajax({
		              //url: "http://192.168.0.16/viaticoapp/indicadores_inicio.php",
		              url: "http://centros.proyectotesisuesfmp.com/controlador/viaticopagado.php",
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
	        }else{
	        	//swal({ title: "¡Ups! Error", text: "Completa los campos.", type: "error", showConfirmButton: true });
	        	$.toast({ heading: 'Fechas Incorrectas', text: 'Debe ingresar intervalo de fechas correctas', position: 'top-right', loaderBg:'#fc4b6c', icon: 'error', hideAfter: time_notificaciones, stack: 6,loaderBg:'#F67171'
                    });
	        }

	     }
	     var options = {
		    date: new Date(),
		    mode: 'date'
		};
		 
		function onSuccess(date) {
		    alert('Selected date: ' + date);
		}
		 
		function onError(error) { // Android only
		    alert('Error: ' + error);
		}
	     function calendar(){ 

		    alert("test") // is working 
		    datePicker.show(options, onSuccess, onError);
		} 
	</script>
</head>
<body>
	    <div class="container-fluid">
	        <div class="row page-titles">
	            <div class="align-self-center" align="center">
	                <h3 class="text-themecolor m-b-0 m-t-0">Estadística de Visitas</h3>
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
	                            <h5>Año: <span class="text-danger">*</span></h5>
	                            <input type="text" value="<?php echo date('Y'); ?>" class="form-control" id="anio_actual" name="anio_actual" placeholder="yyyy" onclick="calendar()">
	                        </div>
	                        <div class="demo-radio-button">
	                        	<h5>Periodo: <span class="text-danger"></span></h5>
	                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_mensual" checked="">
	                            <label for="radio_mensual">Mensual</label>
	                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_trimestral">
	                            <label for="radio_trimestral">Trimestral</label>
	                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_semestral">
	                            <label for="radio_semestral">Semestral</label>
	                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_anual">
	                            <label for="radio_anual">Anual</label>
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
                            
                            <div align="right">
                            <button type="button" onclick="mostrarReporte('reporte_viatico_pendiente_empleado')" class="btn waves-effect waves-light btn-success2"><i class="ti-clipboard"></i> Consultar</button>
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