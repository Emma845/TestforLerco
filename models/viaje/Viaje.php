<?php

namespace app\models\viaje;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\user\User;
use app\models\cliente\Cliente;
use app\models\reembolso\Reembolso;
use app\models\Esys;
/**
 * This is the model class for table "viaje".
 *
 * @property int $id ID
 * @property string $nombre Nombre
 * @property int $fecha_ini Fecha inicio
 * @property int $fecha_expired Fecha fin
 * @property string $nota Nota
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at
 * @property int $updated_by Modificado por
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Viaje extends \yii\db\ActiveRecord
{
    /*
    const STATUS_ACTIVE     = 10;
    const STATUS_CERRADO    = 20;
    const STATUS_CANCEL     = 1;*/

    const STATUS_PROCESO         = 10;
    const STATUS_TERMINADO       = 30;
    const STATUS_PENAUTORIZACION = 11;
    const STATUS_AUTORIZADO    = 20;
    const STATUS_NOAUTORIZADO  = 21;
    const STATUS_INHABILITADO  = 2;
    const STATUS_CANCELADO     = 1;


    const TIPO_VIAJE        = 20;
    const TIPO_SUCURSAL     = 10;


    /*public static $statusList = [
        self::STATUS_ACTIVE     => 'Habilitado',
        self::STATUS_CERRADO    => 'Cerrado / Terminado',
        self::STATUS_CANCEL     => 'Cancelado',
    ];*/

    public $asignado_id;

    public $viajeCliente;

    public static $tipoList = [
        self::TIPO_VIAJE      => 'Viaje',
        self::TIPO_SUCURSAL   => 'Sucursal',
    ];

    public static $statusList = [
        self::STATUS_TERMINADO      => 'Terminado / Cerrado',
        self::STATUS_AUTORIZADO     => 'Autorizado',
        self::STATUS_NOAUTORIZADO   => 'No Autorizado',
        self::STATUS_PROCESO        => 'En Proceso',
        self::STATUS_PENAUTORIZACION=> 'Pendiente Autorizacion',
        self::STATUS_INHABILITADO   => 'Inhabilitado',
        self::STATUS_CANCELADO      => 'Cancelado',

    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'viaje';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'fecha_ini', 'fecha_expired'], 'required'],
            [[ 'created_at', 'created_by', 'user_no_autorizo','updated_at', 'updated_by','status','sucursal_id','tipo'], 'integer'],
            [['nota'], 'string'],
            [['nombre'], 'string', 'max' => 150],
            [['user_no_autorizo'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_no_autorizo' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'asignado_id' => 'Asignar',
            'fecha_ini' => 'Fecha inicio',
            'fecha_expired' => 'Fecha fin',
            'sucursal_id' => 'Sucursal',
            'nota' => 'Nota',
            'status' => 'Estatus',
            'nota_noautorizacion' => 'Nota de no Autorizado',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolizas()
    {
        return $this->hasMany(Poliza::className(), ['viaje_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReembolsos()
    {
        return $this->hasMany(Reembolso::className(), ['viaje_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViajeClientes()
    {
        return $this->hasMany(ViajeCliente::className(), ['viaje_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserNoAutorizo()
    {
        return $this->hasOne(User::className(), ['id' => 'user_no_autorizo']);
    }

    /**
     * @return JSON string
     */

    public static function getItems($user_id_app = false, $is_app = false,$is_terminado = false )
    {

        $user_id = $is_app ? $user_id_app  : Yii::$app->user->identity->id;

        $query = Self::find()
            ->select([
                "viaje.id",
                "viaje.nombre",
            ])
            ->leftJoin("poliza", "viaje.id = poliza.viaje_id")
            ->asArray()
            ->orderBy('viaje.nombre');
        if ($is_terminado){
            $query->andWhere([ "viaje.status" => self::STATUS_AUTORIZADO ]);
            $query->andWhere(['IS', 'poliza.id', new \yii\db\Expression('null')]);
        }

        else
            $query->andWhere([ "viaje.status" => self::STATUS_PROCESO ]);



        if($user_id && Yii::$app->user->identity->tipo == User::TIPO_SUPERVISOR || Yii::$app->user->identity->tipo == User::TIPO_AGENTE)
            $query->andWhere(['viaje.created_by' => $user_id]);

        //echo ($query->createCommand()->rawSql) . '<br/><br/>';
        if ($is_app)
            return $query->all();
        else
            return ArrayHelper::map($query->all(), 'id', 'nombre');

    }
    //------------------------------------------------------------------------------------------------//
    // ACTIVE RECORD
    //------------------------------------------------------------------------------------------------//
    public function beforeSave($insert)
    {


        if(parent::beforeSave($insert)) {
            $this->fecha_ini        = Esys::stringToTimeUnix($this->fecha_ini);
            $this->fecha_expired    = Esys::stringToTimeUnix($this->fecha_expired);

            if ($insert) {

                $this->status           = self::STATUS_PROCESO;
                $this->created_at = time();
                $this->created_by = Yii::$app->user->identity? Yii::$app->user->identity->id: null;

            }else{

                // Quién y cuando
                $this->updated_at = time();
                $this->updated_by = Yii::$app->user->identity? Yii::$app->user->identity->id: null;
            }

            return true;

        } else
            return false;
    }
}
