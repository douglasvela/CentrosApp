<div class="row"><div class="col-lg-12 col-md-4">
                            <div class="form-group">
                                <select class="select2" style="width:100%" name="id_centro" id="id_centro" onchange="cargar_indicadores(this.value)">
                                <?php
                                $conexion = mysqli_connect("162.241.252.245","proyedk4_WPZF0","MAYO_nesa94","proyedk4_WPZF0"); 
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