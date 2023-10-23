<?php
namespace app\models\agenda;

use Yii;
use app\models\user\User;
use app\models\alumno\Alumno;
use app\models\cliente\Cliente;
/**
 * This is the model class for table "agenda".
 *
 * @property int $id ID
 * @property string $titulo Titulo
 * @property int $tipo Tipo
 * @property int $padre_familia_id Padre familia
 * @property int $alumno_id Alumno
 * @property string $nota Nota
 * @property int $fecha Fecha
 * @property int $created_at Creado
 * @property int $created_by Creado por
 * @property int $updated_at
 * @property int $updated_by Modificado por
 *
 * @property Alumno $alumno
 * @property User $createdBy
 * @property Cliente $padreFamilia
 * @property User $updatedBy
 */
class Agenda extends \yii\db\ActiveRecord
{


    const TIPO_NOTA 		= 10;
    const TIPO_RECORDATORIO = 20;
    const TIPO_JUNTA 		= 30;
    const TIPO_TAREA 		= 40;
    const TIPO_LLAMADA      = 50;

    public static $statusList = [
        self::TIPO_NOTA   	=> 'Nota',
        self::TIPO_RECORDATORIO => 'Recordatorio',
        self::TIPO_JUNTA 	=> 'Junta',
        self::TIPO_TAREA 	=> 'Tarea',
        self::TIPO_LLAMADA  => 'Llamada grupal',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agenda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo', 'fecha'], 'required'],
            [['tipo', 'padre_familia_id', 'alumno_id', 'created_at', 'created_by', 'updated_at', 'updated_by','fecha_fin', 'usuario_asignado_id'], 'integer'],
            [['nota'], 'string'],
            [['titulo'], 'string', 'max' => 200],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => Alumno::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['padre_familia_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['padre_familia_id' => 'id']],
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
            'titulo' => 'Titulo',
            'tipo' => 'Tipo',
            'padre_familia_id' => 'Padre Familia ID',
            'alumno_id' => 'Alumno ID',
            'usuario_asignado_id' => 'Usuario Asignado ID',
            'nota' => 'Nota',
            'fecha' => 'Fecha',
            'fecha_fin' => 'Fecha fin',
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
    public function getPadreFamilia()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'padre_familia_id']);
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
