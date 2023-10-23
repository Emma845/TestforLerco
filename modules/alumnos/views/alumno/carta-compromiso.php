<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use app\models\esys\EsysListaDesplegable;
use app\models\cliente\Cliente;
use app\models\alumno\Alumno;
use app\models\file\FileCheck;

setlocale(LC_ALL,"es_MX");
$fecha = strftime("%A %d de %B del %Y")
?>

<div>
    <table class="table">
        <tbody>
        <tr class="tabla">
                <td align="center"><strong><label>CARTA COMPROMISO DE ENTREGA DE DOCUMENTOS FALTANTES</label></strong></td>
            </tr>
            <tr class="tabla">
                <td align="right"><label>Puebla, Pue.</label><label><?=' a '.$fecha?></label></td>
            </tr>

        </tbody>
    </table>
    <table class="table">
        <tbody>
            <tr class="tabla">
                <td align="left">
                    <p>El que suscribe <strong><?=$tutor->nombre.' '.$tutor->apellidos.'     '?></strong> tutor del alumno 
                    <strong><?=$model->nombre.' '.$model->apellidos.'     '?></strong> me comprometo a entregar los documentos 
                    faltantes en el tramite de inscripcion, descritos a continuación:</p>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table">
        <tbody>
            <?php foreach ($files as $key => $item_documento): ?>
            <tr class="tabla">
                <td width="200"></td>
                <td align="left">
                    <ul>
                        <li>
                            <label><?=$item_documento->singular?></label></td>
                        </li>
                    </ul>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <br>
    <div class="text-left">
        <p>En caso de no entregar dichos documentos la Institución Educativa se deslinda de toda responsabilidad, 
            en caso de que surgiera alguna supervisión y no estuviese mi documento bajo resguardo, y esto fuera 
            causa de Baja en forma inmediata y definitiva sin perjuicio para la Institución
        </p>
    </div>
    <div class="text-center div-firma">
        <table class="firma">
            <tbody>
                <tr>
                    <td align="center"><strong><label>Nombre y firma del padre o tutor</label></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>