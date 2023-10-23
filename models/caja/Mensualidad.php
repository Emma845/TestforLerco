<?php

namespace app\models\caja;

use Yii;
use app\models\caja\PagoAlumno;
use app\models\user\User;

/**
 * This is the model class for table "mensualidad".
 *
 * @property int $id id
 * @property string $name name
 * @property string $code name
 * @property int $created_at creado
 * @property int $created_by creado por
 * @property int $updated_at actualizado
 * @property int $updated_by actualizado por
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Mensualidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mensualidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'code'], 'string', 'max' => 120],
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
            'name' => 'Name',
            'code' => 'Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public static function getmeses(){
        $meses = Mensualidad::find()->orderBy(['id' => SORT_ASC])->all();
        return $meses;
    }

    public static function confirmmeses($alumno = null, $ciclo = null, $tipo = null,$mes= null){
        $confirmacion = PagoAlumno::find()->where(['alumno_id'=>$alumno,'ciclo_id'=>$ciclo,'tipo_pago_id'=>$tipo,'mes_pago'=>$mes])->one();

        if ($confirmacion) {
            return $confirmacion;
        }else {
            return 0;
        }
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
}
