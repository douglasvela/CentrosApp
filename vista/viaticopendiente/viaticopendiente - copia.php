<html>
<head>
	<title></title>
	<link rel="stylesheet" href="../../assets/plugins/bootstrap/css/bootstrap.min.css">
	<script type="text/javascript" src="../../assets/plugins/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
 <?php
        $server   = "162.241.252.245";
      $database = "proyedk4_WPZF0";
        $usuario  = "proyedk4_WPZF0";
        $clave    = "MAYO_nesa94"; 

      $conexion = mysqli_connect($server,$usuario,$clave,$database); 
      /*$query_consulta=mysqli_query($conexion,"select * from org_usuario");
      while( $fila=mysqli_fetch_array($query_consulta)){
            $data[] = $fila;
         }		*/
      
      $cabecera_vista = '<table><tr>
 		<td>
		    
		</td>
		<td width="950px"><h6><center>MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br> UNIDAD FINANCIERA INSTITUCIONAL <br> FONDO CIRCULANTE DE MONTO FIJO <br> REPORTE VIATICOS PENDIENTE POR EMPLEADO</center><h6></td>
		<td>
		    
		   
		</td>
	 	</tr></table>';
	 	$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
	 	//$pie = 'Usuario: '.$this->session->userdata('usuario_viatico').'    Fecha y Hora Creacion: '.$fecha.'||{PAGENO} de {nbpg} páginas';

	 	//$data = array('nr'=>'2588');
		//$empleado_NR_viatico = $this->Reportes_viaticos_model->obtenerNREmpleadoViatico($data);
		$query_consulta_nr=mysqli_query($conexion,"select * from org_usuario where nr='2588'");
      	while( $fila=mysqli_fetch_array($query_consulta_nr)){
            $empleado_NR_viatico[] = $fila;
         }
		foreach ($empleado_NR_viatico as $key) {	}
		
		//$ids = array('nr' => '2588');
		//$viatico = $this->Reportes_viaticos_model->obtenerListaviatico_pendiente($ids);
		$query_consulta_viatico=mysqli_query($conexion,"SELECT * FROM `vyp_mision_oficial` WHERE `nr_empleado`='2588' and ( `estado` between '0' and '7')");
		while( $fila=mysqli_fetch_array($query_consulta_viatico)){
            $viatico[] = $fila;
         }
          

		$cuerpo = '
		<h6>NR: Empleado: '.($key[1]).'</h6>
		<div class="table-responsive">
			<table  class="table table-striped">
				
				<thead >

					<tr>
						<th scope="col" align="center" rowspan="2">Fecha Solicitud</th>
						<th scope="col" align="center" rowspan="2">Fecha Inicio Mision</th>
						<th scope="col" align="center" rowspan="2">Fecha Fin Mision</th>
						<th scope="col" align="center" rowspan="2">Actividad</th>
						<th scope="col" align="center" colspan="3">Tipo</th>
						<th scope="col" align="center" rowspan="2">Total</th>						 
					</tr>
					<tr>
						<th align="center">Viaticos</th>
						<th align="center">Pasajes</th>
						<th align="center">Alojamiento</th>
					</tr>
				</thead>
				<tbody>
					
					';
				if($viatico){
					$suma_total=0;
					$suma_viaticos=0;
					$suma_pasajes=0;
					$suma_alojamientos=0;
				foreach ($viatico as $viaticos) {
					
					//$estado = $this->Reportes_viaticos_model->obtenerDetalleEstado($viaticos->estado);
					//foreach ($estado->result() as $estado_detalle) {}
					 
					$query_consulta_totales=mysqli_query($conexion,"SELECT sum(`viatico`) as viatico,sum(`pasaje`) as pasaje,sum(`alojamiento`) as alojamiento, (sum(`viatico`) + sum(`pasaje`) + sum(`alojamiento`)) as total FROM `vyp_empresa_viatico` WHERE `id_mision`='144'");
					while( $fila=mysqli_fetch_array($query_consulta_totales)){
			            $totales[] = $fila;
			         }
					//$totales = $this->Reportes_viaticos_model->obtenerTotalMontos($viaticos->id_mision_oficial);
					foreach ($totales as $totales_detalle) {}
					 	
						$suma_viaticos+=$totales_detalle[0];
						$suma_pasajes+=$totales_detalle[1];
						$suma_alojamientos+=$totales_detalle[2];
						$suma_total=$suma_viaticos+$suma_pasajes+$suma_alojamientos;
					$cuerpo .= '
						<tr>
							<td>'.date('d-m-Y',strtotime($viaticos[5])).'</td>
							<td>'.date('d-m-Y',strtotime($viaticos[3])).'</td>
							<td>'.date('d-m-Y',strtotime($viaticos[4])).'</td>
							<td></td>
							<td>$'.number_format($totales_detalle[0],2,".",",").'</td>
							<td>$'.number_format($totales_detalle[1],2,".",",").'</td>
							<td>$'.number_format($totales_detalle[2],2,".",",").'</td>
							<td>$'.number_format($totales_detalle[3],2,".",",").'</td>
						</tr>
						';
					
					}
				}else{
				$cuerpo .= '
						<tr><td colspan="10"><center>No hay registros</center></td></tr>
					';
				}
				$cuerpo .= '
					<tr>
							<th colspan="4" align="right">Total</th>
							<th>$'.number_format($suma_viaticos,2,".",",").'</th>
							<th>$'.number_format($suma_pasajes,2,".",",").'</th>
							<th>$'.number_format($suma_alojamientos,2,".",",").'</th>
							<th>$'.number_format($suma_total,2,".",",").'</th>
						</tr>
				</tbody>
			</table>
			</div>
        ';  


      echo $cabecera_vista.=$cuerpo;
      mysqli_close($conexion);
 ?>	 
</body>
</html>



otro


<html>
<head>
    <title></title>
    <!--<link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
      <script type="text/javascript" src="assets/plugins/bootstrap/js/popper.min.js"></script>
  <script type="text/javascript" src="assets/plugins/jquery/jquery.min.js"></script>
  <script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="assets/plugins/jquery/jquery.plugin.js"></script>
    <script  src="http://code.jquery.com/jquery-latest.js"></script> -->
    <link href="assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet">
    <link href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.skinModern.css" rel="stylesheet">
    <script  src="http://code.jquery.com/jquery-latest.js"></script> 
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <link href="assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
    <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/colors/blue.css" id="theme" rel="stylesheet">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <script>
      function buscar() { 
        var formData = new FormData();
        $.ajax({
              //url: "http://192.168.0.16/viaticoapp/indicadores_inicio.php",
              url: "http://viaticos.proyectotesisuesfmp.com/indicadores_inicio.php",
              type: "post",
              dataType: "html",
              data: formData,
              crossDomain: true,
              cache: false,
              contentType: false,
              processData: false
          })
          .done(function(res1){
            $("#ind_inicio").html(res1);
          }); 
      } 
      function prueba_buscar() { 
        var formData = new FormData();
        $.ajax({
              //url: "http://192.168.0.16/viaticoapp/indicadores_inicio.php",
              url: "http://viaticos.proyectotesisuesfmp.com/vista/viaticopendiente/viaticopendiente.php",
              type: "post",
              dataType: "html",
              data: formData,
              crossDomain: true,
              cache: false,
              contentType: false,
              processData: false
          })
          .done(function(res1){
            $("#ind_inicio").html(res1);
          }); 
      } 
      function myFunc(){
       
      }
    </script>
</head>
<body onload="buscar()">
   <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Menú</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#" data-toggle="collapse" data-target=".navbar-collapse.show"><span onclick="prueba_buscar()">Viáticos Pendientes</span> <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item" >
        <a class="nav-link" href="#" ><span onclick="buscar()">Link</span></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav> -->

  <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User profile -->
                
                <!-- End User profile text-->
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">PERSONAL</li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="index.html">Dashboard 1</a></li>
                                <li><a href="index2.html">Dashboard 2</a></li>
                                <li><a href="index3.html">Dashboard 3</a></li>
                                <li><a href="index4.html">Dashboard 4</a></li>
                                <li><a href="index5.html">Dashboard 5</a></li>
                                <li><a href="index6.html">Dashboard 6</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-laptop-windows"></i><span class="hide-menu">Template Demos</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="../minisidebar/index.html">Minisidebar</a></li>
                                <li><a href="../horizontal/index2.html">Horizontal</a></li>
                                <li><a href="../dark/index3.html">Dark Version</a></li>
                                <li><a href="../material-rtl/index4.html">RTL Version</a></li>
                                <li><a href="javascript:angular">Anuglar-CLI Starter kit</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-bullseye"></i><span class="hide-menu">Apps</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="app-calendar.html">Calendar</a></li>
                                <li><a href="app-chat.html">Chat app</a></li>
                                <li><a href="app-ticket.html">Support Ticket</a></li>
                                <li><a href="app-contact.html">Contact / Employee</a></li>
                                <li><a href="app-contact2.html">Contact Grid</a></li>
                                <li><a href="app-contact-detail.html">Contact Detail</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-email"></i><span class="hide-menu">Inbox</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="app-email.html">Mailbox</a></li>
                                <li><a href="app-email-detail.html">Mailbox Detail</a></li>
                                <li><a href="app-compose.html">Compose Mail</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-chart-bubble"></i><span class="hide-menu">Ui Elements</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="ui-cards.html">Cards</a></li>
                                <li><a href="ui-user-card.html">User Cards</a></li>
                                <li><a href="ui-buttons.html">Buttons</a></li>
                                <li><a href="ui-modals.html">Modals</a></li>
                                <li><a href="ui-tab.html">Tab</a></li>
                                <li><a href="ui-tooltip-popover.html">Tooltip &amp; Popover</a></li>
                                <li><a href="ui-tooltip-stylish.html">Tooltip stylish</a></li>
                                <li><a href="ui-sweetalert.html">Sweet Alert</a></li>
                                <li><a href="ui-notification.html">Notification</a></li>
                                <li><a href="ui-progressbar.html">Progressbar</a></li>
                                <li><a href="ui-nestable.html">Nestable</a></li>
                                <li><a href="ui-range-slider.html">Range slider</a></li>
                                <li><a href="ui-timeline.html">Timeline</a></li>
                                <li><a href="ui-typography.html">Typography</a></li>
                                <li><a href="ui-horizontal-timeline.html">Horizontal Timeline</a></li>
                                <li><a href="ui-session-timeout.html">Session Timeout</a></li>
                                <li><a href="ui-session-ideal-timeout.html">Session Ideal Timeout</a></li>
                                <li><a href="ui-bootstrap.html">Bootstrap Ui</a></li>
                                <li><a href="ui-breadcrumb.html">Breadcrumb</a></li>
                                <li><a href="ui-bootstrap-switch.html">Bootstrap Switch</a></li>
                                <li><a href="ui-list-media.html">List Media</a></li>
                                <li><a href="ui-ribbons.html">Ribbons</a></li>
                                <li><a href="ui-grid.html">Grid</a></li>
                                <li><a href="ui-carousel.html">Carousel</a></li>
                                <li><a href="ui-date-paginator.html">Date-paginator</a></li>
                                <li><a href="ui-dragable-portlet.html">Dragable Portlet</a></li>
                            </ul>
                        </li>
                        <li class="nav-devider"></li>
                        <li class="nav-small-cap">FORMS, TABLE &amp; WIDGETS</li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-file"></i><span class="hide-menu">Forms</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="form-basic.html">Basic Forms</a></li>
                                <li><a href="form-layout.html">Form Layouts</a></li>
                                <li><a href="form-addons.html">Form Addons</a></li>
                                <li><a href="form-material.html">Form Material</a></li>
                                <li><a href="form-float-input.html">Floating Lable</a></li>
                                <li><a href="form-pickers.html">Form Pickers</a></li>
                                <li><a href="form-upload.html">File Upload</a></li>
                                <li><a href="form-mask.html">Form Mask</a></li>
                                <li><a href="form-validation.html">Form Validation</a></li>
                                <li><a href="form-dropzone.html">File Dropzone</a></li>
                                <li><a href="form-icheck.html">Icheck control</a></li>
                                <li><a href="form-img-cropper.html">Image Cropper</a></li>
                                <li><a href="form-bootstrapwysihtml5.html">HTML5 Editor</a></li>
                                <li><a href="form-typehead.html">Form Typehead</a></li>
                                <li><a href="form-wizard.html">Form Wizard</a></li>
                                <li><a href="form-xeditable.html">Xeditable Editor</a></li>
                                <li><a href="form-summernote.html">Summernote Editor</a></li>
                                <li><a href="form-tinymce.html">Tinymce Editor</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-table"></i><span class="hide-menu">Tables</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="table-basic.html">Basic Tables</a></li>
                                <li><a href="table-layout.html">Table Layouts</a></li>
                                <li><a href="table-data-table.html">Data Tables</a></li>
                                <li><a href="table-footable.html">Footable</a></li>
                                <li><a href="table-jsgrid.html">Js Grid Table</a></li>
                                <li><a href="table-responsive.html">Responsive Table</a></li>
                                <li><a href="table-bootstrap.html">Bootstrap Tables</a></li>
                                <li><a href="table-editable-table.html">Editable Table</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-widgets"></i><span class="hide-menu">Widgets</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="widget-apps.html">Widget Apps</a></li>
                                <li><a href="widget-data.html">Widget Data</a></li>
                                <li><a href="widget-charts.html">Widget Charts</a></li>
                            </ul>
                        </li>
                        <li class="nav-devider"></li>
                        <li class="nav-small-cap">EXTRA COMPONENTS</li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-book-multiple"></i><span class="hide-menu">Page Layout</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="layout-single-column.html">1 Column</a></li>
                                <li><a href="layout-fix-header.html">Fix header</a></li>
                                <li><a href="layout-fix-sidebar.html">Fix sidebar</a></li>
                                <li><a href="layout-fix-header-sidebar.html">Fixe header &amp; Sidebar</a></li>
                                <li><a href="layout-boxed.html">Boxed Layout</a></li>
                                <li><a href="layout-logo-center.html">Logo in Center</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-book-open-variant"></i><span class="hide-menu">Sample Pages</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="starter-kit.html">Starter Kit</a></li>
                                <li><a href="pages-blank.html">Blank page</a></li>
                                <li><a href="#" class="has-arrow">Authentication <span class="label label-rounded label-success">6</span></a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li><a href="pages-login.html">Login 1</a></li>
                                        <li><a href="pages-login-2.html">Login 2</a></li>
                                        <li><a href="pages-register.html">Register</a></li>
                                        <li><a href="pages-register2.html">Register 2</a></li>
                                        <li><a href="pages-lockscreen.html">Lockscreen</a></li>
                                        <li><a href="pages-recover-password.html">Recover password</a></li>
                                    </ul>
                                </li>
                                <li><a href="pages-profile.html">Profile page</a></li>
                                <li><a href="pages-animation.html">Animation</a></li>
                                <li><a href="pages-fix-innersidebar.html">Sticky Left sidebar</a></li>
                                <li><a href="pages-fix-inner-right-sidebar.html">Sticky Right sidebar</a></li>
                                <li><a href="pages-invoice.html">Invoice</a></li>
                                <li><a href="pages-treeview.html">Treeview</a></li>
                                <li><a href="pages-utility-classes.html">Helper Classes</a></li>
                                <li><a href="pages-search-result.html">Search result</a></li>
                                <li><a href="pages-scroll.html">Scrollbar</a></li>
                                <li><a href="pages-pricing.html">Pricing</a></li>
                                <li><a href="pages-lightbox-popup.html">Lighbox popup</a></li>
                                <li><a href="pages-gallery.html">Gallery</a></li>
                                <li><a href="pages-faq.html">Faqs</a></li>
                                <li><a href="#" class="has-arrow">Error Pages</a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li><a href="pages-error-400.html">400</a></li>
                                        <li><a href="pages-error-403.html">403</a></li>
                                        <li><a href="pages-error-404.html">404</a></li>
                                        <li><a href="pages-error-500.html">500</a></li>
                                        <li><a href="pages-error-503.html">503</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-file-chart"></i><span class="hide-menu">Charts</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="chart-morris.html">Morris Chart</a></li>
                                <li><a href="chart-chartist.html">Chartis Chart</a></li>
                                <li><a href="chart-echart.html">Echarts</a></li>
                                <li><a href="chart-flot.html">Flot Chart</a></li>
                                <li><a href="chart-knob.html">Knob Chart</a></li>
                                <li><a href="chart-chart-js.html">Chartjs</a></li>
                                <li><a href="chart-sparkline.html">Sparkline Chart</a></li>
                                <li><a href="chart-extra-chart.html">Extra chart</a></li>
                                <li><a href="chart-peity.html">Peity Charts</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-brush"></i><span class="hide-menu">Icons</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="icon-material.html">Material Icons</a></li>
                                <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
                                <li><a href="icon-themify.html">Themify Icons</a></li>
                                <li><a href="icon-linea.html">Linea Icons</a></li>
                                <li><a href="icon-weather.html">Weather Icons</a></li>
                                <li><a href="icon-simple-lineicon.html">Simple Lineicons</a></li>
                                <li><a href="icon-flag.html">Flag Icons</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-map-marker"></i><span class="hide-menu">Maps</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="map-google.html">Google Maps</a></li>
                                <li><a href="map-vector.html">Vector Maps</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-arrange-send-backward"></i><span class="hide-menu">Multi level dd</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="#">item 1.1</a></li>
                                <li><a href="#">item 1.2</a></li>
                                <li> <a class="has-arrow" href="#" aria-expanded="false">Menu 1.3</a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li><a href="#">item 1.3.1</a></li>
                                        <li><a href="#">item 1.3.2</a></li>
                                        <li><a href="#">item 1.3.3</a></li>
                                        <li><a href="#">item 1.3.4</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">item 1.4</a></li>
                            </ul>
                        </li>
                        
                         
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item--><a href="" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a>
                <!-- item--><a href="" class="link" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>
                <!-- item--><a href="" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a> </div>
            <!-- End Bottom points-->
        </aside>

  <div  id="ind_inicio" ></div>
    
</body>
<script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/buttons.flash.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.print.min.js"></script>
    <script src="assets/plugins/ion-rangeslider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
    <script src="assets/plugins/ion-rangeslider/js/ion-rangeSlider/ion.rangeSlider-init.js"></script>
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/js/viaticos_validation.js"></script>
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/sidebarmenu.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/js/custom.min.js"></script>
    <script src="assets/js/validation.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="assets/plugins/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <script> jQuery(document).ready(function() { $(".select2").select2(); $('.selectpicker').selectpicker(); }); </script>
    <script src="assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/moment-with-locales.min.js"></script>
    
    <script src="assets/plugins/toast-master/js/jquery.toast.js"></script>
</html>