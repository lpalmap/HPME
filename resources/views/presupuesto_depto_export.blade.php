<!DOCTYPE html>
<html lang="en">
    <body>
            <table>
                <thead>
                                        <tr style="text-align: center;">
                                            <th></th>
                                            <th></th>
                                            <th>%</th>
                                            <th>Enero</th>
                                            <th>Febrero</th>
                                            <th>Marzo</th>
                                            <th>Abril</th>
                                            <th>Mayo</th>
                                            <th>Junio</th>
                                            <th>Julio</th>
                                            <th>Agosto</th>
                                            <th>Septiembre</th>
                                            <th>Octubre</th>
                                            <th>Noviembre</th>
                                            <th>Diciembre</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                        @for ($i=0;$i<count($cuentas);$i++)
                                            @if(isset($cuentas[$i]['nivel']))
                                                @if($cuentas[$i]['nivel']==0)
                                                <tr style="text-align: center;background-color: #99ff99" >
                                                @else
                                                    @if($cuentas[$i]['nivel']==1)
                                            <tr  style="text-align: center; background-color: #ffcccc" >
                                                    @else
                                            <tr style="text-align: center; background-color: #ffffff" >
                                                    @endif
                                                @endif
                                                
                                            @else
                                            <tr style="text-align: center;background-color: #ffffff" >
                                            @endif                                       
                                                @if(is_null($cuentas[$i]['cuenta']) || strlen($cuentas[$i]['cuenta'])==0)
                                                <td style="text-align: center">{{$cuentas[$i]['nombre']}}</td>
                                                <td></td>
                                                @else
                                                <td style="text-align: center">{{$cuentas[$i]['cuenta']}}</td>
                                                <td style="text-align: center">{{$cuentas[$i]['nombre']}}</td>
                                                @endif
                                                <td style="font-weight: bolder">{{isset($cuentas[$i]['porc'])?number_format($cuentas[$i]['porc'],2):'0'}}</td>
                                                <td style="text-align: right;">{{isset($cuentas[$i]['item1'])?number_format($cuentas[$i]['item1'],(fmod($cuentas[$i]['item1'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="text-align: right;">{{isset($cuentas[$i]['item2'])?number_format($cuentas[$i]['item2'],(fmod($cuentas[$i]['item2'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="text-align: right;">{{isset($cuentas[$i]['item3'])?number_format($cuentas[$i]['item3'],(fmod($cuentas[$i]['item3'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="text-align: right;">{{isset($cuentas[$i]['item4'])?number_format($cuentas[$i]['item4'],(fmod($cuentas[$i]['item4'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="text-align: right;" >{{isset($cuentas[$i]['item5'])?number_format($cuentas[$i]['item5'],(fmod($cuentas[$i]['item5'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="text-align: right;">{{isset($cuentas[$i]['item6'])?number_format($cuentas[$i]['item6'],(fmod($cuentas[$i]['item6'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="text-align: right;">{{isset($cuentas[$i]['item7'])?number_format($cuentas[$i]['item7'],(fmod($cuentas[$i]['item7'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="text-align: right;">{{isset($cuentas[$i]['item8'])?number_format($cuentas[$i]['item8'],(fmod($cuentas[$i]['item8'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="text-align: right;">{{isset($cuentas[$i]['item9'])?number_format($cuentas[$i]['item9'],(fmod($cuentas[$i]['item9'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="text-align: right;">{{isset($cuentas[$i]['item10'])?number_format($cuentas[$i]['item10'],(fmod($cuentas[$i]['item10'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="text-align: right;">{{isset($cuentas[$i]['item11'])?number_format($cuentas[$i]['item11'],(fmod($cuentas[$i]['item11'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="text-align: right;">{{isset($cuentas[$i]['item12'])?number_format($cuentas[$i]['item12'],(fmod($cuentas[$i]['item12'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="font-weight: bolder;text-align: right;">{{isset($cuentas[$i]['total'])?number_format($cuentas[$i]['total'],(fmod($cuentas[$i]['total'], 1) !== 0.00)?2:0):'0'}}</td>
                                            </tr>
                                        @endfor    
                                    </tbody>
                                </table>
    </body>   
</html>
     



