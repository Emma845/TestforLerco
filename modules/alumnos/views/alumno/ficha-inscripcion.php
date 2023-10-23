<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use app\models\esys\EsysListaDesplegable;
use app\models\cliente\Cliente;
use app\models\alumno\Alumno;
use app\models\file\FileCheck;

?>

<div>
    <table class="table">
        <tbody>
            <tr class="tabla">
                <td align="right"><strong><label>Ficha de inscripción</label></strong></td>
                <td width="276"  align="right"><strong><label>Fecha:</label></strong><label><?='  '.date('Y-m-d')?></label></td>
            </tr>
        </tbody>
    </table>
    <table class="table">
        <tbody>
            <tr class="tabla">
                <td colspan="2" align="center"><strong><label>I. Datos del alumno</label></strong></td>
            </tr>
            <tr class="tabla">
                <td align="left"><strong><label>a) Nivel: </label></strong><label><?=$model->nivelText->singular?></label></td>
                <td width="135"  align="left"><strong><label>b) Grado: </label></strong><label><?=$model->gradoText->singular?></td>
            </tr>
            <tr class="tabla">
                <td align="left"><strong><label>c) Nombre completo del alumno: </label></strong><label><?=$model->nombre.' '.$model->apellidos.'     '?></label></td>
            </tr>
            <tr class="tabla">
                <td align="left"><strong><label>d) Lugar y fecha de nacimiento: </label></strong>
                <label><?=$model->lugar_nacimiento.' '.($model->fecha_nacimiento ? date('Y-m-d',$model->fecha_nacimiento).'     ': ' ')?></label></td>
                <td width="135" align="left"><strong><label>e) Edad: </label></strong><label><?=$model->fecha_nacimiento ? $model->edad.' Año(s)' : ' '?></label></td>
            </tr>
            <tr class="tabla">
                <td colspan="2" align="left"><strong><label>f) Domicilio: </label></strong>
                    <label><?=$tutor->direccion->direccion.', Ext.'.$tutor->direccion->num_ext.', Int.'.$tutor->direccion->num_int.
                    ', Colonia '.$tutor->direccion->esysDireccionCodigoPostal->colonia.
                    ', '.$tutor->direccion->esysDireccionCodigoPostal->municipio->singular.
                    ', '.$tutor->direccion->esysDireccionCodigoPostal->estado->singular.
                    ', '.$tutor->direccion->esysDireccionCodigoPostal->codigo_postal.'. '?></label>
                    <br>
                    <strong><label>Vive con: </label></strong><label><?=$model->viveConText ? $model->viveConText->singular.'. ': ' '?></label>
                    <?php if($model->nombre_vive_con) :?>
                        <strong><label>Nombre: </label></strong><label><?=$model->nombre_vive_con?></label>
                    <?php endif?>
                </td>
            </tr>
            <tr class="tabla">
                <td colspan="2" align="left"><strong><label>g) ¿Cuenta con computadora/equipo para trabajar e internet? </label></strong>
                    <label><?= $model->cuenta_equipo_internet ? Alumno::$equipoList[$model->cuenta_equipo_internet].' ': ' '?></label><strong><label>Correo electronico: </label></strong><label><?=$tutor->email?></label>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table">
        <tbody>
            <tr class="tabla">
                <td colspan="2" align="center"><strong><label>II. Datos del padre o tutor</label></strong></td>
            </tr>
            <tr class="tabla">
                <td align="left"><strong><label>a) Nombre Completo: </label></strong><label><?=$tutor->nombre.' '.$tutor->apellidos.'     '?></label></td>
                <td width="185" align="left"><strong><label>b) Parentesco: </label></strong><label><?=$tutor->parentescoText ? $tutor->parentescoText->singular : ' '?></td>
            </tr>
            <tr class="tabla">
                <td colspan="2" align="left"><strong><label>c) Domicilio: </label></strong>
                    <label><?=$tutor->direccion->direccion.', Ext.'.$tutor->direccion->num_ext.', Int.'.$tutor->direccion->num_int.
                    ', Colonia '.$tutor->direccion->esysDireccionCodigoPostal->colonia.
                    ', '.$tutor->direccion->esysDireccionCodigoPostal->municipio->singular.
                    ', '.$tutor->direccion->esysDireccionCodigoPostal->estado->singular.
                    ', '.$tutor->direccion->esysDireccionCodigoPostal->codigo_postal.'. '?></label>
                </td>
            </tr>
            <tr class="tabla">
                <td colspan="2" align="left"><strong><label>d) Telefonos: </label></strong>
                <label><?='Personal: '.$tutor->telefono_movil.', Trabajo: '.$tutor->telefono.', WhatsApp: '.$tutor->whatsapp?></label></td>
            </tr>
        </tbody>
    </table>
    <table class="table">
        <tbody>
            <tr class="tabla">
                <td colspan="3" align="center"><strong><label>III. Datos clinicos alumno</label></strong></td>
            </tr>
            <tr class="tabla">
                <td align="left"><strong><label>a) Tipo Sanguíneo: </label></strong><label><?= $model->tipoSangreText ? $model->tipoSangreText->singular : ''?></label></td>
                <td align="left"><strong><label>b) Talla: </label></strong><label><?=$model->talla?></td>
                <td align="left"><strong><label>c) Peso: </label></strong><label><?=$model->peso.' Kg'?></td>
            </tr>
            <tr class="tabla">
                <td colspan="3" align="left"><strong><label>d) Enfermedades/Lesiones Graves: </label></strong><label><?=$model->enfermedades_lesiones?></label></td>
            </tr>
            <tr class="tabla">
                <td colspan="3" align="left"><strong><label>e) Antecedentes Familiares: </label></strong><label><?=$model->antecedentes_enfermedades?></label></td>
            </tr>
            <tr class="tabla">
                <td colspan="3" align="left"><strong><label>f) ¿Tiene algún tipo de discapacidad?: </label></strong><label><?=$model->discapacidad?></label></td>
            </tr>
        </tbody>
    </table>
    <table class="table">
        <tbody>
            <tr class="tabla">
                <td colspan ="2" align="center"><strong><label>IV. Documentos que entrega</label></strong></td>
            </tr>
            <tr class="tabla">
                <td colspan ="2" align="center"><strong><label>Padre o Tutor</label></strong></td>
            </tr>
            <?php foreach (EsysListaDesplegable::getItems("document_tutor") as $key => $item_documento): ?>
            <tr class="tabla">
                <td align="left"><label><?=$item_documento?></label></td>
                <td width="200" align="center">
                    <?php if(FileCheck::getFilesTutor($tutor->id, $key)) :?>
                            <label>Si</label>
                    <?php else :?>
                        <label>No</label>
                    <?php endif ?>
                </td>
            </tr>
            <?php endforeach ?>
            <tr class="tabla">
                <td colspan ="2" align="center"><strong><label>Alumno</label></strong></td>
            </tr>
            <?php foreach (EsysListaDesplegable::getItems("document_alumno") as $key => $item_documento): ?>
            <tr class="tabla">
                <td align="left"><label><?=$item_documento?></label></td>
                <td width="200" align="center">
                    <?php if(FileCheck::getFilesAlumno($model->id, $key)) :?>
                            <label>Si</label>
                    <?php else :?>
                        <label>No</label>
                    <?php endif ?>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <table class="table">
        <tbody>
            <tr class="tabla">
                <td colspan="3" align="center"><strong><label>V. Información Recibida</label></strong></td>
            </tr>
            <tr class="tabla">
                <td align="left"><label>Reglamento escolar: </label><label>_______</label></td>
                <td align="left"><label>Lista de utiles escolares: </label><label>_______</td>
                <td align="left"><label>Uniformes</label><label>_______</td>
            </tr>
            <tr class="tabla">
                <td align="left"><label>No. Tarjeta de pago de colegiaturas: </label><label>_______</label></td>
                <td colspan="2" align="left"><label>No. cuenta para deposito de pago de colegiaturas: </label><label>_______</td>
            </tr>
            <tr class="tabla">
                <td colspan="2" align="left"><label>Carta responsiva de salud del alumno: </label><label>_______</label></td>
                <td align="left"><label>Observaciones: </label><label>__________</td>
            </tr>
        </tbody>
    </table>
    <br>
    <div class="text-left">
        <p>Manifiesto bajo protesta de decir verdad que la información y documentos presentados para efecto de cumplir con los 
            requerimientos son legítimos, siendo única responsabilidad del suscrito</p>
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