<?php
namespace app\models\alumno;

use Yii;
use app\models\documento\Documento;

/**
 * This is the model class for table "alumno_documento".
 *
 * @property int $id ID
 * @property int $alumno_id Linea de credito ID
 * @property int $documento_id Documento ID
 *
 * @property Alumno $alumno
 * @property Documento $documento
 */
class AlumnoDocumento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alumno_documento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alumno_id', 'documento_id'], 'required'],
            [['alumno_id', 'documento_id'], 'integer'],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => Alumno::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['documento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Documento::className(), 'targetAttribute' => ['documento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alumno_id' => 'Alumno ID',
            'documento_id' => 'Documento ID',
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
    public function getDocumento()
    {
        return $this->hasOne(Documento::className(), ['id' => 'documento_id']);
    }
}
