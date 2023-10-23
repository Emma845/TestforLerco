<?php
namespace app\models\lista;

use Yii;
use app\models\alumno\Alumno;

/**
 * This is the model class for table "lista_alumno".
 *
 * @property int $id ID
 * @property int $lista_id Lista ID
 * @property int $alumno_id Alumno ID
 *
 * @property Alumno $alumno
 * @property Lista $lista
 */
class ListaAlumno extends \yii\db\ActiveRecord
{
    const TIPO_ASISTENCIA       = 10;
    const TIPO_AUSENTE          = 20;
    const TIPO_SINASISTENCIA    = 30;
    const TIPO_JUSTIFICADO    = 40;

    public static $sexoList = [
        self::TIPO_ASISTENCIA       => 'ASISTENCIA',
        self::TIPO_AUSENTE          => 'FUERA DE LINEA',
        self::TIPO_SINASISTENCIA    => 'INASISTENCIA',
        self::TIPO_JUSTIFICADO    => 'JUSTIFICADO',

    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lista_alumno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lista_id', 'alumno_id','tipo'], 'required'],
            [['lista_id', 'alumno_id'], 'integer'],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => Alumno::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['lista_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lista::className(), 'targetAttribute' => ['lista_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lista_id' => 'Lista ID',
            'alumno_id' => 'Alumno ID',
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
    public function getLista()
    {
        return $this->hasOne(Lista::className(), ['id' => 'lista_id']);
    }
}
