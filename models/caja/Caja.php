<?php
namespace app\models\caja;

use Yii;
use app\models\alumno\Alumno;
use app\models\cliente\Cliente;
use app\models\user\User;
use app\models\esys\EsysListaDesplegable;
use app\models\cobro\CobroAlumno;
/**
 * This is the model class for table "caja".
 *
 * @property int $id ID
 * @property int $padre_tutor_id Padre / Tutor
 * @property int $alumno_id Alumno
 * @property double $monto Monto
 * @property int $cantidad Cantidad
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at Modificado
 * @property int $updated_by Modificado por
 *
 * @property Alumno $alumno
 * @property Cliente $padreTutor
 * @property User $createdBy
 * @property User $updatedBy
 */
class Caja extends \yii\db\ActiveRecord
{

    const PERIODO_MES       = 10;
    const PERIODO_TRIMESTRAL= 20;
    const PERIODO_SEMESTRAL = 30;
    const PERIODO_ANUAL     = 40;
    const PERIODO_UNICO     = 50;

    public static $periodoList = [
        self::PERIODO_MES   => 'MENSUAL',
        self::PERIODO_TRIMESTRAL => 'TRIMESTRAL',
        self::PERIODO_SEMESTRAL => 'SEMESTRAL',
        self::PERIODO_ANUAL => 'ANUAL',
        self::PERIODO_UNICO => 'UNICO',
        //self::STATUS_DELETED  => 'Eliminado'
    ];


    public $cobroAlumno;
    /**
     * {@inheritdoc}
     */
    public static function tableName() 
    {
        return 'caja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['padre_tutor_id', 'alumno_id','tipo_id'], 'required'],
            [['padre_tutor_id', 'alumno_id', 'created_at', 'tipo_id', 'created_by', 'updated_at', 'updated_by','mes_agosto','mes_septiembre','mes_octubre','mes_noviembre','mes_diciembre','mes_enero','mes_febrero','mes_marzo','mes_abril','mes_mayo','mes_junio','mes_julio','periodicidad'], 'integer'],

            [['nota','ciclo_escolar_id'], 'string'],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => Alumno::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['padre_tutor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['padre_tutor_id' => 'id']],
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
            'tipo_id' => 'Tipo de pago',
            'padre_tutor_id' => 'Padre Tutor',
            'monto' => 'TOTAL A RECIBIR',
            'tipo.singular' => 'Tipo',
            'mes_agosto'=>'Agosto',
            'mes_septiembre'=>'Septiembre',
            'mes_octubre'=>'Octubre',
            'mes_noviembre'=>'Noviembre',
            'mes_diciembre'=>'Diciembre',
            'mes_enero'=>'Enero',
            'mes_febrero'=>'Febrero',
            'mes_marzo'=>'Marzo',
            'mes_abril'=>'Abril',
            'mes_mayo'=>'Mayo',
            'mes_junio'=>'Junio',
            'alumno.gradoText.singular' => 'Grado',
            'alumno.nivelText.singular' => 'Nivel',
            'mes_julio'=>'Julio',
            'ciclo_escolar_id' => 'Ciclo escolar',
            'cantidad' => 'Cantidad',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumno()
    {
        return $this->hasOne(Alumno::className(), ['id' => 'alumno_id']);
    }

    public function getTipo()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'tipo_id']);
    }

    public function getCicloEscolar()
    {
        return $this->hasOne(EsysListaDesplegable::className(), ['id' => 'ciclo_escolar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPadreTutor()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'padre_tutor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCobros()
    {
        return $this->hasMany(CobroAlumno::className(), ['caja_id' => 'id']);
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

//------------------------------------------------------------------------------------------------//
// ACTIVE RECORD
//------------------------------------------------------------------------------------------------//
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {

            if ($insert) {
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
