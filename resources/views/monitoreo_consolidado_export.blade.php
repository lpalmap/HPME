<!DOCTYPE html>
<html lang="en">
    <body>
        <table>
            <thead>
                <tr>
                    <th style="text-align: left">Meta/Area/Objetivo</th>
                    <th style="text-align: left">Indicador</th>
                    <th style="text-align: left">Producto</th>
                    <?php
                        foreach ($encabezados as $encabezado){
                    ?>
                    <th style="text-align: right" colspan="2">{{$encabezado}}</th>
                    <?php
                        }
                    ?>
                    <th style="text-align: right" colspan="2">Total</th>
                </tr>
                <tr>
                    <th style="text-align: left"></th>
                    <th style="text-align: left"></th>
                    <th style="text-align: left"></th>
                    <?php
                        foreach ($encabezados as $encabezado){
                    ?>
                    <th style="text-align: right">Plan</th>
                    <th style="text-align: right">Ejec</th>
                    <?php
                        }
                    ?>
                    <th style="text-align: right">Plan</th>
                    <th style="text-align: right">Ejec</th>
                </tr>
            </thead>
            <tbody id="lista-items" name="lista-items">
                <?php
                    $metas=$plantilla['metas'];
                    foreach ($metas as $meta){
                        $objetivos=$meta['objetivos'];
                ?>
                    <tr class="warning" style="text-align: center" >
                        <td style="background: #000099;font-weight: bolder;color: #ffffff">{{$meta['meta']->nombre}}</td>
                        <td></td>
                        <td></td>
                        <?php 
                            for($f=0;$f<$num_items;$f++){
                                echo '<td></td>';
                                echo '<td></td>';
                            } 
                        ?>
                        <td></td>
                         <td></td>
                    </tr>
                    <?php 
                        foreach($objetivos as $objetivo){
                            $areas=$objetivo['areas'];
                            foreach($areas as $area){
                                $indicadores=$area['indicadores'];
                    ?>
                        <tr class="success" style="text-align: center" >
                            <td style="background:  #008dc5;font-weight: bolder;color: #ffffff">{{$area['area']->nombre}}</td>
                            <td></td>
                            <td></td>
                            <?php 
                                for($f=0;$f<$num_items;$f++){
                                    echo '<td></td>';
                                    echo '<td></td>';
                                } 
                            ?>
                            <td></td>
                            <td></td>
                        </tr>
                            <?php
                                foreach($indicadores as $indicador){
                                    $productos=$indicador['productos'];
                                    foreach($productos as $producto){
                                        $detalles=$producto['detalles'];
                                        $total=0;
                                        $totalEje=0;
                                        foreach ($detalles as $detalle){
                                            $total=$total+$detalle->valor;
                                            $totalEje=$totalEje+$detalle->ejecutado;
                                        }
                                        if($total>0 || $totalEje>0){
                                            ?>
                                                <tr class="info" style="text-align: center" >
                                                    <td>{{$objetivo['objetivo']->nombre}}</td>
                                                    <td>{{$indicador['indicador']->nombre}}</td>
                                                    <td>{{$producto['producto']->nombre}}</td>
                                                    <?php
                                                        foreach ($detalles as $detalle){
                                                    ?>
                                                    <td style="text-align: right;background: #BDD7EE;">{{intval($detalle->valor)}}</td>
                                                    <td style="text-align: right;background: #BDD7EE;">{{intval($detalle->ejecutado)}}</td>
                                                    <?php
                                                        }
                                                    ?>
                                                    <td style="text-align: right;background: #99ff33;">{{$total}}</td>
                                                    <td style="text-align: right;background: #99ff33;">{{$totalEje}}</td>
                                                </tr>
                                            <?php
                                        }}}}
                            ?>
                        <?php                                              
                        }}
                        ?>    
            </tbody>
        </table>
    </body>   
</html>