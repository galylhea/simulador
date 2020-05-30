<?php
/* Archivo para realizar cálculos */

require_once('PHPExcel/Calculation/Financial.php');
$oFinancial = new PHPExcel_Calculation_Financial();

//Recibiendo datos
$valor = $_POST['valor'];
$plazo = $_POST['plazo'];
$gradiente = $_POST['gradiente'];
$cuota_inicial_porc = $_POST['cuota_inicial'];
$seguro = $_POST['seguro'];
$tipo = $_POST['tipo'];
$tipo = substr($tipo, 0, -1);

$tipo = explode(',', $tipo);

//Constantes
$tasa_nominal_anual = 0.1580;

//Cálculos previos
$cuota_inicial = $valor * $cuota_inicial_porc;
$valor_refinanciar = $valor - $cuota_inicial;
$tasa_mensual = $tasa_nominal_anual / 12;

$tasa_efectiva_anual = $oFinancial->EFFECT($tasa_nominal_anual,12);
//$cuota_mensual = ($valor_refinanciar*($tasa_mensual*(pow((1+$tasa_mensual),$plazo))))/((pow((1+$tasa_mensual),$plazo))-1);

$tasa_mensual = $tasa_nominal_anual / 12;

$html1 = '';
$html2 = '';
$html3 = '';

foreach ($tipo as $t){//Recorrer tipo de cálculo seleccionado por el usuario, por defecto es VENCIDA
    if ($t == 1){
        $cuota_mensual = $oFinancial->PMT($tasa_mensual,$plazo,-$valor_refinanciar);
        $cuota_mensual_seguro = $cuota_mensual + $seguro;
        $html1 .='<table>
                    <thead>
                        <tr>
                            <th style="width: 50%">Tipo de amortización</th>
                            <td style="width: 50%; border-top: none" class="bg-blue">VENCIDA</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Valor del préstamo</td>
                            <td class="bg-blue">$'.number_format($valor).'</td>
                        </tr>
                        <tr>
                            <td>Cuota inicial</td>
                            <td class="bg-blue">$'.number_format($cuota_inicial).'</td>
                        </tr>
                        <tr>
                            <td>Valor a refinanciar</td>
                            <td class="bg-blue">$'.number_format($valor_refinanciar).'</td>
                        </tr>
                        <tr>
                            <td>Tasa efectiva anual</td>
                            <td class="bg-blue">'.round($tasa_efectiva_anual * 100, 2) .'%</td>
                        </tr>
                        <tr>
                            <td>Tasa nominal anual</td>
                            <td class="bg-blue">'.$tasa_nominal_anual * 100 .'%</td>
                        </tr>
                        <tr>
                            <td>Tasa mensual</td>
                            <td class="bg-blue">'.round($tasa_mensual * 100, 2) .'%</td>
                        </tr>
                        <tr>
                            <td>Plazo</td>
                            <td class="bg-blue">$'.$plazo.'</td>
                        </tr>
                        <tr>
                            <td>Cuota mensual sin seguro</td>
                            <td class="bg-blue">$'.number_format($cuota_mensual,2).'</td>
                        </tr>
                        <tr>
                            <td>Seguro</td>
                            <td class="bg-blue">$'.number_format($seguro).'</td>
                        </tr>
                        <tr>
                            <td>Cuota con seguro</td>
                            <td class="bg-blue">$'.number_format($cuota_mensual_seguro,2).'</td>
                        </tr>
                    </tbody>
        </table>
        <br>
        <table>
            <thead>
                <tr>
                    <th style="width: 5.5%">#</th>
                    <th style="width: 13.5%">CRÉDITO</th>
                    <th style="width: 13.5%">INTERÉS</th>
                    <th style="width: 13.5%">CUOTA SIN SEGURO</th>
                    <th style="width: 13.5%">SEGURO</th>
                    <th style="width: 13.5%">CUOTA + SEGURO</th>
                    <th style="width: 13.5%"> AMORTIZACIÓN</th>
                    <th style="width: 13.5%">SALDO</th>
                </tr>
            </thead>
            <tbody>';
        $cont = 1;
        $saldo_inicial = $valor_refinanciar;    
        for($i = 0; $i < $plazo; $i++){
            $interes = $saldo_inicial * $tasa_mensual;
            $amortizacion = $cuota_mensual - $interes;
            $saldo = abs($saldo_inicial - $amortizacion);
            $html1 .='<tr>
                        <td>'.$cont.'</td>
                        <td>$'.number_format($saldo_inicial,2).'</td>
                        <td>$'.number_format($interes,2).'</td>
                        <td>$'.number_format($cuota_mensual,2).'</td>
                        <td>$'.number_format($seguro,2).'</td>
                        <td>$'.number_format($cuota_mensual_seguro,2).'</td>
                        <td>$'.number_format($amortizacion,2).'</td>
                        <td>$'.number_format($saldo,2).'</td>
            </tr>';
            $saldo_inicial = $saldo;
            $cont++;
        }
        $html1 .= '</tbody>
        </table><br><br>';
    }

    if($t == 2){
        $cuota_mensual = $oFinancial->PMT($tasa_mensual,$plazo,-$valor_refinanciar,0,1);
        $cuota_mensual_seguro = $cuota_mensual + $seguro;
        $html2 .='<table>
                    <thead>
                        <tr>
                            <th style="width: 50%">Tipo de amortización</th>
                            <td style="width: 50%; border-top: none" class="bg-blue">ANTICIPADA</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Valor del préstamo</td>
                            <td class="bg-blue">$'.number_format($valor).'</td>
                        </tr>
                        <tr>
                            <td>Cuota inicial</td>
                            <td class="bg-blue">$'.number_format($cuota_inicial).'</td>
                        </tr>
                        <tr>
                            <td>Valor a refinanciar</td>
                            <td class="bg-blue">$'.number_format($valor_refinanciar).'</td>
                        </tr>
                        <tr>
                            <td>Tasa efectiva anual</td>
                            <td class="bg-blue">'.round($tasa_efectiva_anual * 100, 2) .'%</td>
                        </tr>
                        <tr>
                            <td>Tasa nominal anual</td>
                            <td class="bg-blue">'.$tasa_nominal_anual * 100 .'%</td>
                        </tr>
                        <tr>
                            <td>Tasa mensual</td>
                            <td class="bg-blue">'.round($tasa_mensual * 100, 2) .'%</td>
                        </tr>
                        <tr>
                            <td>Plazo</td>
                            <td class="bg-blue">$'.$plazo.'</td>
                        </tr>
                        <tr>
                            <td>Cuota mensual sin seguro</td>
                            <td class="bg-blue">$'.number_format($cuota_mensual,2).'</td>
                        </tr>
                        <tr>
                            <td>Seguro</td>
                            <td class="bg-blue">$'.number_format($seguro).'</td>
                        </tr>
                        <tr>
                            <td>Cuota con seguro</td>
                            <td class="bg-blue">$'.number_format($cuota_mensual_seguro,2).'</td>
                        </tr>
                    </tbody>
        </table>
        <br>
        <table>
            <thead>
                <tr>
                    <th style="width: 5.5%">#</th>
                    <th style="width: 13.5%">CRÉDITO</th>
                    <th style="width: 13.5%">INTERÉS</th>
                    <th style="width: 13.5%">CUOTA SIN SEGURO</th>
                    <th style="width: 13.5%">SEGURO</th>
                    <th style="width: 13.5%">CUOTA + SEGURO</th>
                    <th style="width: 13.5%"> AMORTIZACIÓN</th>
                    <th style="width: 13.5%">SALDO</th>
                </tr>
            </thead>
            <tbody>';
            $cont = 1;
            $saldo_inicial = $valor_refinanciar;
            $interes = 0;    
            for($i = 0; $i < $plazo; $i++){
                $amortizacion = $cuota_mensual - $interes;
                $saldo = abs($saldo_inicial - $amortizacion);
                $html2 .='<tr>
                            <td>'.$cont.'</td>
                            <td>$'.number_format($saldo_inicial,2).'</td>
                            <td>$'.number_format($interes,2).'</td>
                            <td>$'.number_format($cuota_mensual,2).'</td>
                            <td>$'.number_format($seguro,2).'</td>
                            <td>$'.number_format($cuota_mensual_seguro,2).'</td>
                            <td>$'.number_format($amortizacion,2).'</td>
                            <td>$'.number_format($saldo,2).'</td>
                </tr>';
                $saldo_inicial = $saldo;
                $interes = $saldo_inicial * $tasa_mensual;
                $cont++;
            }
            $html2 .= '</tbody>
            </table><br><br>';
    }

    if($t == 3){
        //$cuota_mensual = ($valor_refinanciar*($tasa_mensual*(pow((1+$tasa_mensual),$plazo))))/((pow((1+$tasa_mensual),$plazo))-1);
        //$cuota_mensual = $oFinancial->PMT($tasa_mensual,$plazo,-$valor_refinanciar,0,1);
        //$cuota_mensual = $valor_refinanciar*($tasa_mensual*pow(1+$tasa_mensual),$plazo))/((pow(1+$tasa_mensual),$plazo)-1)-($gradiente*((1/$tasa_mensual)-($plazo/((pow(1+$tasa_mensual),$plazo)-1)));
        /*D3*(D6*(1+D6)^D7)/(((1+D6)^D7)-1)-
        (D8*((1/D6)-(D7/(((1+D6)^D7)-1)))*/
        $cuota_mensual = $valor_refinanciar*($tasa_mensual*(pow((1+$tasa_mensual),$plazo)))/((pow((1+$tasa_mensual),$plazo))-1)-($gradiente*((1/$tasa_mensual)-($plazo/((pow((1+$tasa_mensual),$plazo)-1)))));
        $html3 .='<table>
                    <thead>
                        <tr>
                            <th style="width: 50%">Tipo de amortización</th>
                            <td style="width: 50%; border-top: none" class="bg-blue">ARITMÉTICA</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Valor del préstamo</td>
                            <td class="bg-blue">$'.number_format($valor).'</td>
                        </tr>
                        <tr>
                            <td>Cuota inicial</td>
                            <td class="bg-blue">$'.number_format($cuota_inicial).'</td>
                        </tr>
                        <tr>
                            <td>Valor a refinanciar</td>
                            <td class="bg-blue">$'.number_format($valor_refinanciar).'</td>
                        </tr>
                        <tr>
                            <td>Tasa efectiva anual</td>
                            <td class="bg-blue">'.round($tasa_efectiva_anual * 100, 2) .'%</td>
                        </tr>
                        <tr>
                            <td>Tasa nominal anual</td>
                            <td class="bg-blue">'.$tasa_nominal_anual * 100 .'%</td>
                        </tr>
                        <tr>
                            <td>Tasa mensual</td>
                            <td class="bg-blue">'.round($tasa_mensual * 100, 2) .'%</td>
                        </tr>
                        <tr>
                            <td>Plazo</td>
                            <td class="bg-blue">$'.$plazo.'</td>
                        </tr>
                        <tr>
                            <td>Gradiente</td>
                            <td class="bg-blue">$'.$gradiente.'</td>
                        </tr>
                        <tr>
                            <td>Seguro</td>
                            <td class="bg-blue">$'.number_format($seguro).'</td>
                        </tr>
                    </tbody>
        </table>
        <br>
        <table>
            <thead>
                <tr>
                    <th style="width: 5.5%">#</th>
                    <th style="width: 13.5%">CRÉDITO</th>
                    <th style="width: 13.5%">INTERÉS</th>
                    <th style="width: 13.5%">CUOTA SIN SEGURO</th>
                    <th style="width: 13.5%">SEGURO</th>
                    <th style="width: 13.5%">CUOTA + SEGURO</th>
                    <th style="width: 13.5%"> AMORTIZACIÓN</th>
                    <th style="width: 13.5%">SALDO</th>
                </tr>
            </thead>
            <tbody>';
            $cont = 1;
            $saldo_inicial = $valor_refinanciar;
            for($i = 0; $i < $plazo; $i++){
                $interes = $saldo_inicial * $tasa_mensual;
                $cuota_mensual_seguro = $cuota_mensual + $seguro;
                $amortizacion = $cuota_mensual - $interes;
                $saldo = abs($saldo_inicial - $amortizacion);
                $html3 .='<tr>
                            <td>'.$cont.'</td>
                            <td>$'.number_format($saldo_inicial,2).'</td>
                            <td>$'.number_format($interes,2).'</td>
                            <td>$'.number_format($cuota_mensual,2).'</td>
                            <td>$'.number_format($seguro,2).'</td>
                            <td>$'.number_format($cuota_mensual_seguro,2).'</td>
                            <td>$'.number_format($amortizacion,2).'</td>
                            <td>$'.number_format($saldo,2).'</td>
                </tr>';
                $saldo_inicial = $saldo;
                $cuota_mensual += $gradiente;
                $cont++;
            }
            $html3 .= '</tbody>
            </table><br><br>';  
    }
}

echo $html1.$html2.$html3;
