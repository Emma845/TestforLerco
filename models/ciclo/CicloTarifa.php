<?php

namespace app\models\ciclo;


use Yii;
use app\models\user\User;
use app\models\caja\PagoAlumno;
use app\models\caja\TipoPago;
/**
 * This is the model class for table "ciclo_tarifa".    
 *
 * @property int    $id ID
 * @property int    $nivel nivel
 * @property int    $ciclo_id ciclo_id
 * @property int    $inscripcion inscripcion
 * @property float  $colegiatura colegiatura
 * @property float  $mora mora
 * @property string $notas notas
 * @property int    $created_at Creado
 * @property int    $created_by Creado por
 * @property int    $updated_at Modificado
 * @property int    $updated_by Modificado por
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class CicloTarifa extends \yii\db\ActiveRecord
{

    const GRADO_PREESCOLAR     = 10;
    const GRADO_PRIMARIA       = 20;
    const GRADO_SECUNDARIA     = 30;

    public static $gradoList = [
        self::GRADO_PREESCOLAR => 'PREESCOLAR',
        self::GRADO_PRIMARIA      => 'PRIMARIA',
        self::GRADO_SECUNDARIA    => 'SECUNDARIA',
    ];
    const INSCRIPCION     = 10;
    const COLEGIATURA     = 20;

    public static $pagoList = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ciclo_tarifa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inscripcion','colegiatura','mora'], 'required'],
            [['created_at', 'created_by', 'updated_at', 'updated_by','nivel'], 'integer'],
            [['notas'], 'string', 'max' => 350],
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
            'nivel' => 'Nivel',
            'ciclo_id' => 'ciclo_id',
            'inscripcion' => 'inscripcion',
            'colegiatura' => 'colegiatura',
            'mora' => 'mora',
            'notas' => 'Notas',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public static function getPagosList($alumno, $tutor, $ciclo){
    //Aqui verificara si ya ha realizado un pago en la tabla de pagos 
    $inscripcion = PagoAlumno::find()->andwhere(['alumno_id' => $alumno,'tutor_id'=>$tutor,'ciclo_id' => $ciclo,'tipo_pago_id' => 1])->one();
       /*Verifica que no haya usuarios con el id tutor, ciclo y tipo para poder mostrar la informacion en el campo con esto
       podremos ver que se inscriba una sola vez
       */
    if ($inscripcion) {
        $pagoList = TipoPago::find()->andwhere(['id' => 2])->all();;
    }
    else{
        $pagoList = TipoPago::find()->all();
    }
    return $pagoList;

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public static function getPrimaria($id)
    {
        $model = self::find()->select(['nivel','inscripcion','colegiatura','mora'])->andwhere(['ciclo_id' => $id, 'nivel' => 10])->one();
        return $model;
    }
    public static function getSecundaria($id)
    {
        $model = self::find()->select(['nivel','inscripcion','colegiatura','mora'])->andwhere(['ciclo_id' => $id, 'nivel' => 20])->one();
        return $model;
    }
    public static function getPreparatoria($id)
    {
        $model = self::find()->select(['nivel','inscripcion','colegiatura','mora'])->andwhere(['ciclo_id' => $id, 'nivel' => 30])->one();
        return $model;
    }
    public static function getConfigCiclo($id)
    {
        $model = self::find()->select(['nivel','inscripcion','colegiatura','mora'])->andwhere(['ciclo_id' => $id])->all();
        return $model;
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

        } 
        else
            
        return false;
    }
}
