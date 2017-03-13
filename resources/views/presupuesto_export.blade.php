<!DOCTYPE html>
<html lang="en">
    <body>
            <table>
                <thead>
                                        <tr style="text-align: center;">
                                            <th>C&oacute;digo Cuenta</th>
                                            <th>Periodo</th>
                                            <th>Importe Base</th>
                                            <th>Importe de la Transacci&oacute;n</th>
                                            <th>Moneda</th>
                                            <th>Tipo de Diario</th>
                                            <th>L1-Location</th>
                                            <th>L3-FunctionDept</th>
                                            <th>L4-Employee</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                            @for ($i=0;$i<count($cuentas);$i++)
                                                <tr>
                                                    <?php
                                                        $cuenta=$cuentas[$i];
                                                    ?>
                                                    <td>{{$cuenta->cuenta}}</td>
                                                    <td>{{$year}}/0{{$cuenta->num_detalle<10?'0':''}}{{$cuenta->num_detalle}}</td>
                                                    <td>{{$cuenta->valor}}</td>
                                                    <td>{{$cuenta->valor}}</td>
                                                    <td>GTQ</td>
                                                    <td>BUDGET</td>
                                                    <td>{{$cuenta->l1}}</td>
                                                    <td>{{$cuenta->l2}}</td>
                                                    <td>{{$cuenta->l4}}</td>
                                                </tr>
                                            @endfor   
                                        </tbody>
                                </table>
    </body>   
</html>