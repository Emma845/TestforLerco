<?php
namespace app\models\lista;

use Yii;
use app\models\user\User;
use app\models\lista\Lista;

/**
 * This is the model class for table "lista".
 *
 * @property int $id ID
 * @property int $profesor_id Profesor ID
 * @property int $created_at Creado
 * @property int $created_by Creado por
 *
 * @property User $createdBy
 * @property Lista $profesor
 * @property Lista[] $listas
 * @property ListaAlumno[] $listaAlumnos
 */
class Lista extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lista';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profesor_id'], 'required'],
            [['profesor_id', 'created_at', 'created_by'], 'integer'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['profesor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['profesor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'profesor_id' => 'Profesor ID',
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesor()
    {
        return $this->hasOne(User::className(), ['id' => 'profesor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListas()
    {
        return $this->hasMany(User::className(), ['profesor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListaAlumnos()
    {
        return $this->hasMany(ListaAlumno::className(), ['lista_id' => 'id']);
    }

    public function validateAsistencia($alumno_id)
    {

        return ListaAlumno::find()->andWhere(['and',[ "lista_id" => $this->id ],["alumno_id" => $alumno_id ],["tipo" => ListaAlumno::TIPO_ASISTENCIA ]])->one() ? true : false;
    }

    public function validateAusente($alumno_id)
    {
        return ListaAlumno::find()->andWhere(['and',[ "lista_id" => $this->id ],["alumno_id" => $alumno_id ],["tipo" => ListaAlumno::TIPO_AUSENTE ]])->one() ? true : false;

    }

    public function validateSinAsistencia($alumno_id)
    {
        return ListaAlumno::find()->andWhere(['and',[ "lista_id" => $this->id ],["alumno_id" => $alumno_id ],["tipo" => ListaAlumno::TIPO_SINASISTENCIA ]])->one() ? true : false;

    }
    public function validateJustificado($alumno_id)
    {
        return ListaAlumno::find()->andWhere(['and',[ "lista_id" => $this->id ],["alumno_id" => $alumno_id ],["tipo" => ListaAlumno::TIPO_JUSTIFICADO ]])->one() ? true : false;

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
