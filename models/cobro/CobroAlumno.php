<?php
namespace app\models\cobro;

use Yii;
use app\models\user\User;
use app\models\caja\Caja;
/**
 * This is the model class for table "cobro_rembolso_envio".
 *
 * @property int $id ID
 * @property int $caja_id Envio ID
 * @property int $tipo Tipo
 * @property int $metodo_pago Metodo de pago
 * @property double $cantidad Cantidad
 * @property int $created_at Creado
 * @property int $created_by Creado por
 *
 * @property User $createdBy
 * @property Envio $envio
 */
class CobroAlumno extends \yii\db\ActiveRecord
{

    const COBRO_EFECTIVO        = 10;
    const COBRO_CHEQUE          = 20;
    const COBRO_TRANFERENCIA    = 30;
    const COBRO_TARJETA_CREDITO = 40;
    const COBRO_TARJETA_DEBITO  = 50;
    const COBRO_DEPOSITO        = 60;


    public static $servicioList = [
        self::COBRO_EFECTIVO        => 'Efectivo',
        self::COBRO_CHEQUE          => 'Cheque',
        self::COBRO_TRANFERENCIA    => 'Tranferencia',
        self::COBRO_TARJETA_CREDITO => 'Tarjeta de credito',
        self::COBRO_TARJETA_DEBITO  => 'Tarjeta de debito',
        self::COBRO_DEPOSITO        => 'Deposito',
    ];

    public $cobroAlumnoArray;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cobro_alumno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['caja_id'], 'required'],
            [['caja_id', 'metodo_pago', 'created_at', 'created_by'], 'integer'],
            [['cantidad'], 'number'],
            [['nota'], 'string'],
            [['cobroAlumnoArray'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['caja_id'], 'exist', 'skipOnError' => true, 'targetClass' => Caja::className(), 'targetAttribute' => ['caja_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'caja_id' => 'Envio ID',
            'metodo_pago' => 'Metodo Pago',
            'cantidad' => 'Cantidad',
            'nota' => 'Nota',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
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
    public function getCaja()
    {
        return $this->hasOne(Caja::className(), ['id' => 'caja_id']);
    }

    public function saveCobroAlumno($caja_id)
    {
        //$CobroRembolsoEnvio  =  CobroRembolsoEnvio::deleteAll([ "caja_id" => $caja_id]);

        $cobroAlumno = json_decode($this->cobroAlumnoArray);

        if ($cobroAlumno) {
            foreach ($cobroAlumno as $key => $cobro) {
                if ($cobro->origen  ==  1 ) {
                    $CobroAlumno  =  new CobroAlumno();
                    $CobroAlumno->caja_id       = $caja_id;
                    $CobroAlumno->metodo_pago    = $cobro->metodo_pago_id;
                    $CobroAlumno->cantidad       = $cobro->cantidad;
                    $CobroAlumno->save();
                }
            }
        }
        return true;
    }

    //------------------------------------------------------------------------------------------------//
    // ACTIVE RECORD
    //------------------------------------------------------------------------------------------------//
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {

            if ($insert) {
                $this->created_at = time();
                $this->created_by = Yii::$app->user->identity? Yii::$app->user->identity->id: null;

            }

            return true;

        } else
            return false;
    }
}
