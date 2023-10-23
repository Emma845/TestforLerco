<?php
namespace app\modules\v1\controllers;

use Yii;
use yii\db\Query;
use LSS\XML2Array;
use yii\db\Expression;
use app\models\esysfact\Advans;
use app\models\user\User;
use app\models\descarga\DescargaCfdiDetalle;
use app\models\reembolso\Reembolso;
use app\models\reembolso\ReembolsoDetalle;
use app\models\esys\EsysListaDesplegable;
use app\models\viaje\Viaje;
use app\models\Esys;
class ReembolsoController extends DefaultController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                // restrict access to
                'Origin' => ['*'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                // Allow only headers 'X-Wsse'
                'Access-Control-Request-Headers' => ['*'],
                // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Allow-Origin' => ['*'],
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 3600,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
            ],
        ];

        return $behaviors;
    }

  	/*****************************************
     *  CONSULTA REEMBOLSO APROBADOS
    *****************************************/
    public function actionAprobados()
    {
        $post = Yii::$app->request->post();

        if (!isset($post["token"]))
            throw new \yii\web\HttpException(202, 'El Token es requerido.', 10);

        // Validamos Token
        $user       = $this->authToken($post["token"]);

        $reembolsos = Reembolso::getReembolsoAprobados($user->id);

        $data = [];

        foreach ($reembolsos as $key => $reembolso) {
            $item = [
                "sucursal"  => $reembolso->sucursal->nombre,
                "subtotal"  => $reembolso->subtotal,
                "iva"       => $reembolso->iva,
                "total"     => $reembolso->total,
                "reembolso_detalle"    => [],
            ];

            foreach ($reembolso->reembolsoDetalles as $key => $r_detalle) {
                $r_detalle_array = [
                    "concepto"      =>  $r_detalle->concepto->singular,
                    "comprobante"   =>  ReembolsoDetalle::$rembolsoList[$r_detalle->comprobante],
                    "descripcion"   =>  $r_detalle->descripcion,
                    "is_compartida" =>  $r_detalle->is_compartido == ReembolsoDetalle::COMPARTIDO_ON ? 'SI' : 'NO',
                    "subtotal"      =>  $r_detalle->subtotal,
                    "iva"           =>  $r_detalle->iva,
                    "total"         =>  $r_detalle->total,
                ];

                array_push($item["reembolso_detalle"], $r_detalle_array);
            }

            array_push($data, $item);
        }

        return [
            "code"    => 202,
            "name"    => "Reembolso",
            "data"    => $data,
            "type"    => "Success",
        ];
    }

    public function actionListSucursales()
    {
        $post = Yii::$app->request->post();

        if (!isset($post["token"]))
            throw new \yii\web\HttpException(202, 'El Token es requerido.', 10);

        // Validamos Token
        $user       = $this->authToken($post["token"]);
        //$user       =  new User;

        $sucursales = [];

        foreach ($user->getSucursales($user->id) as $key => $item) {
            $sucursal = [
                "id"        => $key,
                "nombre"    => $item,
            ];
            array_push($sucursales,$sucursal);
        }

        return [
            "code"    => 202,
            "name"    => "Reembolso",
            "data"    => $sucursales,
            "type"    => "Success",
        ];
    }

    public function actionListConceptos()
    {
        $post = Yii::$app->request->post();

        if (!isset($post["token"]))
            throw new \yii\web\HttpException(202, 'El Token es requerido.', 10);

        // Validamos Token
        $user       = $this->authToken($post["token"]);

        return [
            "code"    => 202,
            "name"    => "Reembolso",
            "data"    => EsysListaDesplegable::getItems('concepto_reembolso',true),
            "type"    => "Success",
        ];

    }

    public function actionListViajes()
    {
        $post = Yii::$app->request->post();

        if (!isset($post["token"]))
            throw new \yii\web\HttpException(202, 'El Token es requerido.', 10);

        // Validamos Token
        $user       = $this->authToken($post["token"]);

        return [
            "code"    => 202,
            "name"    => "Reembolso",
            "data"    => Viaje::getItems($user->id,true),
            "type"    => "Success",
        ];
    }

    /*public function actionValidaFactura(){
        $post = Yii::$app->request->post();
        if (!isset($post["token"]))
            throw new \yii\web\HttpException(202, 'El Token es requerido.', 10);

        // Validamos Token
        $user       = $this->authToken($post["token"]);
        $uuid       = isset($post["uuid"]) ? $post["uuid"] : null;
        $rfc_emisor     = isset($post["rfc_emisor"]) ? $post["rfc_emisor"] : null;
        $rfc_receptor   = isset($post["rfc_receptor"]) ? $post["rfc_receptor"] : null;
        $total          = isset($post["total"]) ? $post["total"] : null;
        $viaje_id       = isset($post["viaje_id"]) ? $post["viaje_id"] : null;
        $Viaje          = Viaje::findOne($viaje_id);


        if ($uuid &&  $rfc_emisor &&  $rfc_receptor && $total && $viaje_id && $Viaje) {

            $Advans = new Advans(Yii::$app->params['advans']);

            $consultarEstadoSAT = $Advans->consultarEstadoSAT($post["rfc_emisor"],$post["rfc_receptor"],$post["total"],$post["uuid"]);

            if ($Advans->response()["Estado"] == 'Vigente') {

                $DescargaCfdiDetalle = DescargaCfdiDetalle::find()->andWhere([ "UUID" => $post["uuid"] ])->one();
                $count      = ReembolsoDetalle::find()->andWhere([ "UUID" => $post["uuid"] ])->count();
                $compartido = ReembolsoDetalle::find()->andWhere([ "UUID" => $post["uuid"] ])->andWhere(["is_compartido" => ReembolsoDetalle::COMPARTIDO_ON ])->all();

                if ($DescargaCfdiDetalle->id) {
                    if ( ( $count > 0 && $compartido ) || $count == 0 ) {

                        $fecha_factura      = $DescargaCfdiDetalle->fecha;
                        $totalCompartido    = 0;
                        $totalFactura       = 0;
                        $total_disponible   = 0;

                        foreach ($compartido as $key => $item) {
                            $totalCompartido = $totalCompartido + floatval($item->total_compartido);
                            $totalFactura = floatval($item->total);
                        }
                        $total_disponible = $totalFactura - $totalCompartido;

                        if ($fecha_factura >= $Viaje->fecha_ini && $fecha_factura <= $Viaje->fecha_expired ) {
                            return [
                                    "code"    => 202,
                                    "name"    => "Reembolso",
                                    "message" => "La factura es valida.",
                                    "totalDisponible"   => $total_disponible,
                                    "is_compartido"     => count($compartido) > 0 ? 10 : 1,
                                    "uuid"      =>  $DescargaCfdiDetalle->UUID,
                                    "subtotal"  =>  $DescargaCfdiDetalle->subtotal,
                                    "iva"       => round(floatval($DescargaCfdiDetalle->iva),2),
                                    "total"     => $DescargaCfdiDetalle->total,
                                    "type"    => "Success",
                            ];
                        }else{
                            return [
                                    "code"    => 10,
                                    "name"    => "Reembolso",
                                    "message" => "La factura no coincide con el rango de fecha del viaje, intente nuevamente.",
                                    "type"    => "Success",
                            ];
                        }
                    }
                    return [
                        "code"    => 10,
                        "name"    => "Reembolso",
                        "message" => "La factura ya existe en el sistema, intente nuevamente.",
                        "type"    => "Success",
                    ];
                }
                return [
                    "code"    => 10,
                    "name"    => "Reembolso",
                    "message" => "La factura no se encontro en el sistema, solicita al administrador a realizar la descarga masiva.",
                    "type"    => "Success",
                ];

            }elseif($Advans->response()["Estado"] == 'Cancelado')
                return [
                    "code"    => 10,
                    "name"    => "Reembolso",
                    "message" => "La factura que ingresaste ha sido cancelada, verifica tu información",
                    "type"    => "Success",
                ];
            else
                return [
                    "code"    => 10,
                    "name"    => "Reembolso",
                    "message" => "Error al consultar la factura al SAT, intenta nuevamente",
                    "type"    => "Success",
                ];
        }

        return [
            "code"    => 10,
            "name"    => "Reembolso",
            "message" => 'Todos los datos son requeridos, intente nuevamente',
            "type"    => "Error",
        ];

    }*/

    public function actionAddReembolso()
    {
        $post = Yii::$app->request->post();
        if (!isset($post["token"]))
            throw new \yii\web\HttpException(202, 'El Token es requerido.', 10);

        // Validamos Token
        $user       = $this->authToken($post["token"]);
        $sucursal_id    = isset($post["sucursal_id"])   ? $post["sucursal_id"] : null;
        $viaje_id       = isset($post["viaje_id"])      ? $post["viaje_id"] : null;
        $subtotal       = isset($post["subtotal"])      ? $post["subtotal"] : 0;
        $iva            = isset($post["iva"])           ? $post["iva"] : 0;
        $total          = isset($post["total"])         ? $post["total"] : 0;
        $gastos_array   = isset($post["gastos_array"]) && count($post["gastos_array"]) > 0  ? $post["gastos_array"] : null;
        $errors_array   = [];

        if ($sucursal_id && $viaje_id && $gastos_array) {
            $model = new Reembolso();
            $model->sucursal_id  =  $sucursal_id;
            $model->viaje_id     =  $viaje_id;
            $model->subtotal     =  $subtotal;
            $model->iva          =  $iva;
            $model->total        =  $total;
            $model->created_by   =  $user->id;
            if ($model->save()) {
                foreach ($gastos_array as $key => $gasto) {
                    switch ($gasto['comprobante']) {
                        case ReembolsoDetalle::COMPROBANTE_NOTA:

                            $ReembolsoDetalle = new ReembolsoDetalle();
                            $ReembolsoDetalle->reembolso_id = $model->id;
                            $ReembolsoDetalle->concepto_id  = $gasto['concepto_id'];
                            $ReembolsoDetalle->comprobante  = ReembolsoDetalle::COMPROBANTE_NOTA;
                            $ReembolsoDetalle->descripcion  = $gasto['descripcion'];

                            $nota_name = "app_nota_". $user->id  ."_". time() . "_" . $key;

                            $file = fopen(Yii::getAlias('@app') ."/web/rembolso/nota/". $nota_name, "wb");
                            fwrite($file, base64_decode($gasto['nota_base64']) );
                            fclose($file);

                            $ReembolsoDetalle->nota         = $nota_name;
                            $ReembolsoDetalle->status       = ReembolsoDetalle::STATUS_HABILITADO;
                            if($gasto["comprobante"] == ReembolsoDetalle::COMPROBANTE_NOTA)
                                $ReembolsoDetalle->total    = $gasto['total'];

                            if ($ReembolsoDetalle->save()) {
                                $error = [
                                    "message" => "Se ingreso correctamente el gasto",
                                ];
                                array_push($errors_array, $error);
                            }else{
                                $error = [
                                    "message" => "Ocurrio un error al ingresar el gasto, intente nuevamente",
                                ];
                                array_push($errors_array, $error);
                            }

                            break;
                        case ReembolsoDetalle::COMPROBANTE_FACTURA:
                            if (isset($gasto['total'])) {
                                if (isset($gasto['uuid']) && $gasto['uuid']  ) {

                                    /*$xml_decode =  base64_decode($gasto['xml_base64']);
                                    $xml_decode       = trim(substr($xml_decode, strpos($xml_decode, '<')));
                                    $xml_decode        = str_replace('xmlns:schemaLocation','xsi:schemaLocation', $xml_decode);
                                    $xml_array = XML2Array::createArray($xml_decode)['cfdi:Comprobante'];*/


                                    $ReembolsoDetalle = new ReembolsoDetalle();
                                    $ReembolsoDetalle->reembolso_id = $model->id;
                                    $ReembolsoDetalle->concepto_id  = $gasto['concepto_id'];
                                    $ReembolsoDetalle->comprobante  = ReembolsoDetalle::COMPROBANTE_FACTURA;
                                    $ReembolsoDetalle->descripcion  = $gasto['descripcion'];

                                    //$nota_name_xml = "app_xml_". $user->id  ."_". time() . "_" . $key;
                                    //$nota_name_pdf = "app_pdf_". $user->id  ."_". time() . "_" . $key;

                                    //$src = Yii::getAlias('@app') . '/web/cfdi_dowload_xml/'. $DescargaCfdiDetalle->file_name;
                                    //$dst = Yii::getAlias('@app') . '/web/rembolso/xml/' . $nota_name_xml . '.xml';
                                    //copy($src, $dst);


                                    //$file_xml = fopen(Yii::getAlias('@app') ."/web/rembolso/xml/". $nota_name_xml . ".xml", "wb");
                                    //fwrite($file_xml, base64_decode($gasto['xml_base64']) );
                                    //fclose($file_xml);

                                    /*
                                    $file_pdf = fopen(Yii::getAlias('@app') ."/web/rembolso/pdf/". $nota_name_pdf . ".pdf", "wb");
                                    fwrite($file_pdf, base64_decode($gasto['pdf_base64']) );
                                    fclose($file_pdf);*/

                                    //$ReembolsoDetalle->xml              = $nota_name_xml;
                                    //$ReembolsoDetalle->pdf              = $nota_name_pdf;

                                    $ReembolsoDetalle->is_compartido    = isset($gasto["is_compartido"]) && $gasto["is_compartido"] ? 10 : null;
                                    $ReembolsoDetalle->is_ajuste        = isset($gasto["is_ajuste"]) && $gasto["is_ajuste"] ? 10 : null;


                                    $ReembolsoDetalle->UUID                   = $gasto['uuid'];
                                    //$ReembolsoDetalle->fecha                = $DescargaCfdiDetalle->fecha;
                                    //$ReembolsoDetalle->tipodecomprobante    = $DescargaCfdiDetalle->tipodecomprobante;
                                    //$ReembolsoDetalle->rfc_emisor       =  $DescargaCfdiDetalle->rfc_emisor;
                                    //$ReembolsoDetalle->nombre_emisor    =  $DescargaCfdiDetalle->nombre_emisor;
                                    //$ReembolsoDetalle->rfc_receptor     =  $DescargaCfdiDetalle->rfc_receptor;
                                    //$ReembolsoDetalle->nombre_receptor  =  $DescargaCfdiDetalle->nombre_receptor;


                                    $ReembolsoDetalle->status       = ReembolsoDetalle::STATUS_HABILITADO;
                                    if (($ReembolsoDetalle->is_compartido  ||  $ReembolsoDetalle->is_ajuste )  && $gasto["comprobante"] == ReembolsoDetalle::COMPROBANTE_FACTURA) {
                                        $ReembolsoDetalle->subtotal_compartido  = isset($gasto['subtotal']) ? $gasto['subtotal'] : 0;
                                        $ReembolsoDetalle->iva_compartido       = isset($gasto['iva']) ? $gasto['iva']      : 0;
                                        $ReembolsoDetalle->total_compartido     = isset($gasto['total']) ? $gasto['total']  : 0;
                                    }

                                    $ReembolsoDetalle->subtotal     = isset($gasto['subtotal']) ? $gasto['subtotal']            : 0;
                                    $ReembolsoDetalle->iva          = isset($gasto['iva']) ?  round(floatval($gasto['iva']),2)  : 0;
                                    $ReembolsoDetalle->total        = isset($gasto['total']) ? $gasto['total']                  : 0;

                                    if ($ReembolsoDetalle->save()) {
                                        array_push($errors_array, [ "message" => "Se ingreso correctamente el gasto" ]);
                                    }else{

                                        array_push($errors_array, [ "message" => "Ocurrio un error al ingresar el gasto, intente nuevamente" ]);
                                    }

                                }else
                                    array_push($errors_array, [ "message" => "El UUID es requerido, intente nuevamente" ]);
                            }else
                                array_push($errors_array,  [ "message" => "El total es requerido, intente nuevamente"]);
                            break;
                        default:

                            array_push($errors_array, [
                                "message" => "Aviso, no se pudo guardar el gasto porque el tipo de comprobante es incorrecto, intente nuevamente"]);
                        break;
                    }
                }
            }

            return [
                "code"    => 202,
                "name"    => "Reembolso",
                "message" => $errors_array,
                "type"    => "Success",
            ];
        }
        return [
            "code"    => 10,
            "name"    => "Reembolso",
            "message" => 'Todos los datos son requeridos, intente nuevamente',
            "type"    => "Error",
        ];
    }
}
