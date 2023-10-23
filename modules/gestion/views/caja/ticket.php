<?php
use yii\helpers\Html;
use yii\helpers\Url;
use Da\QrCode\QrCode;
use app\models\cobro\CobroAlumno;

$color      = "#OOOOOO";
$colorGris  = "#D8D8D8";


$qrCode = (new QrCode($model->id))
            ->setSize(100)
            ->setMargin(2)
            ->setErrorCorrectionLevel('medium');
$code = [];

$code['qrBase64'] =  $qrCode->writeDataUri();


$array_cliente_id   =  [];
$suma_asegurada     = 0;
$total_pieza        = 0;
$pesoPAQUETE        = 0;
?>
<body>
    <table style="font-size: 12px">
        <tr>
            <td colspan="2" align="center">
                <?= Html::img('@web/img/logo-login.png', ["height"=>"150px"]) ?>
                <br>
            </td>
        </tr>
        <tr>
            <td style="background-color:<?php echo $color; ?>;  color: white; font-weight: bold; font-size: 16px; padding:  10px;">
                <p><strong style="font-weight: bold;">Tramite: </strong></p>
            </td>
            <td style="background-color:<?php echo $colorGris; ?>;  color: black; font-size: 14px;">
                <?= $model->tipo->singular ?></p>
            </td>
        </tr>
        <tr>
            <td style="background-color:<?php echo $color; ?>;  color: white; padding:  10px; font-size: 14px;">
                <p><strong style="font-weight: bold;">Padre / Tutor: </strong></p>
            </td>
            <td style="background-color:<?php echo $colorGris; ?>;  color: black;font-size: 14px;">
                <p><?= $model->padreTutor->nombreCompleto ?> </p>
            </td>
        </tr>

        <tr>
            <td style="background-color:<?php echo $color; ?>;  color: white; padding:  10px; font-size: 14px;">
                <p><strong style="font-weight: bold;">Alumno: </strong></p>
            </td>
            <td style="background-color:<?php echo $colorGris; ?>;  color: black;font-size: 14px;">
                <p><?= $model->alumno->nombreCompleto ?> </p>
            </td>
        </tr>

        <tr style="padding: 0">
            <td colspan="2" style="padding: 0">
                <hr  style="font-size: 15px; font-weight: bold; height: 5px; background-color: black; color: black">
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center" style=" font-size: 16px; padding: 5px"><strong style="font-weight: bold;">RESUMEN</strong></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <table width="100%">
                    <?php foreach ($model->cobros as $key => $item): ?>
                        <tr>
                            <td style="width: 50%">
                                <?= CobroAlumno::$servicioList[$item->metodo_pago] ?>
                            </td>
                            <td style="width: 50%">
                                <?= $item->cantidad ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </table>

            </td>
        </tr>



        <tr style="padding: 0">
            <td colspan="2" style="padding: 0">
                <hr  style="font-size: 15px; font-weight: bold; height: 5px; background-color: black; color: black">
            </td>
        </tr>

        <tr>
            <td colspan="2"></td>
        </tr>
        <tr style="font-size: 8px">
            <td colspan="2" align='justify' style="font-family: Georgia, serif; line-height: 15px">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Iure quas recusandae debitis adipisci facere, molestiae, illum culpa? Debitis dolore esse fuga voluptatibus dolores nihil enim laborum vitae voluptates, at architecto.

                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, soluta. Cumque debitis fugiat suscipit. Excepturi eius deleniti, dolorem sunt nihil magni nemo, dicta, placeat debitis dignissimos aspernatur ipsum veritatis, quas!

                <br/>
                <br/>
                <br/>
            </td>
        </tr>
        <tr>
          <td colspan="2" align="center" style="font-family: Georgia, serif; line-height: 15px">
                Consulta terminos y condiciones en <strong>https://colegiojv.edu.mx/</strong>
          </td>
        </tr>
        <tr>
            <td colspan="2"><br><br><br><br></td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">Firma</td>
        </tr>
        <tr>
            <td colspan="2" align="center">Acepto términos y condiciones establecidos</td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
    </table>

</body>
