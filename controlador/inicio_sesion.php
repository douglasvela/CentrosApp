<?php
$usuario = $_POST['usuario'];
$contrasenia = $_POST['contrasenia'];

$conexion = mysqli_connect("162.241.252.245","proyedk4_WPZF0","MAYO_nesa94","proyedk4_WPZF0"); 
$query_consulta=mysqli_query($conexion,"SELECT * FROM org_usuario WHERE usuario = '".$usuario."' AND password = '".md5($contrasenia)."' AND estado = 1");
      	 $row_cnt = mysqli_num_rows($query_consulta);

      	 while( $fila=mysqli_fetch_array($query_consulta)){
			$usuario_nr_sess[] = $fila;
			}
		foreach ($usuario_nr_sess as $fila_session_usuario) {}


      	 if($row_cnt>0){
      	 	echo "correcto,".$fila_session_usuario[3].','.$fila_session_usuario[2];
      	 }else{
      	 	echo "incorrecto";
      	 }
?>