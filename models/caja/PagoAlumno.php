<?php

namespace app\models\caja;

use Yii;
use  app\models\Alumno\Alumno;
use  app\models\ciclo\Ciclo;
use  app\models\user\User;
use  app\models\cliente\Cliente;
/**
 * This is the model class for table "pago_alumno".
 *
 * @property int $id id
 * @property int $alumno_id Alumno
 * @property int $tutor_id tutor de alumno
 * @property int $tipo_pago_id tipo de pago
 * @property int $ciclo_id Ciclo escolar
 * @property int $monto Cuanto se paga
 * @property int $descuento_especial status del descuento
 * @property int $created_at creado
 * @property int $created_by creado por
 * @property int $updated_at actualizado
 * @property int $updated_by actualizado por
 *
 * @property Alumno $alumno
 * @property Ciclo $ciclo
 * @property User $createdBy
 * @property TipoPago $tipoPago
 * @property Cliente $tutor
 * @property User $updatedBy
 */
class PagoAlumno extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pago_alumno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alumno_id', 'tutor_id', 'tipo_pago_id', 'ciclo_id', 'monto', 'descuento_especial', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => Alumno::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['ciclo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ciclo::className(), 'targetAttribute' => ['ciclo_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['tipo_pago_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoPago::className(), 'targetAttribute' => ['tipo_pago_id' => 'id']],
            [['mes_pago'], 'exist', 'skipOnError' => true, 'targetClass' => Mensualidad::className(), 'targetAttribute' => ['tipo_pago_id' => 'id']],
            [['tutor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['tutor_id' => 'id']],
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
            'alumno_id' => 'Alumno ID',
            'tutor_id' => 'Tutor ID',
            'tipo_pago_id' => 'Tipo Pago ID',
            'ciclo_id' => 'Ciclo ID',
            'monto' => 'Monto',
            'metodo_pago' => 'Metodo Pago',
            'total_neto' => 'Total Neto',
            'descuento_especial' => 'Descuento Especial',
            'codigo_operacion' => 'Codigo Operacion',
            'total_neto' => 'Total Neto',
            'mes_pago' => 'Mes Pago',
            'metodo_pago' => 'Metodo Pago',
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


    public static function getMesesEspeciales($alumno = null, $tutor = null, $ciclo = null){
        
    $pagos_consumidos = PagoAlumno::find()->andwhere(['alumno_id' => $alumno,'tutor_id'=>$tutor,'ciclo_id' => $ciclo,'descuento_especial' => 1, 'tipo_pago_id' => 2])->all();

    $count=0;
    if ($pagos_consumidos) {
        foreach ($pagos_consumidos as $key => $value) {
            $count++;
        }
    }
return $count;

    }

public static function getGuardarPago($alumno,$tutor,$tipo,$ciclo,$tarifa_regular,$colegiatura_regular = null,$tarifa_especial,$colegiatura_especial = null,$metodo , $neto){
      
        $pago_recibo=[];
        $count = 0;
        $codigo = rand(1200000000,5200000000);

        $confirm_code = PagoAlumno::find()->where(['codigo_operacion' => $codigo])->all();

        if (!$confirm_code) {
            
            $connection = \Yii::$app->db;
            if ($colegiatura_regular != null) {
                foreach ($colegiatura_regular as $key => $id) {
                    $pago = $connection->createCommand()->insert('pago_alumno',[
                        'alumno_id'=>$alumno,
                        'tutor_id'=>$tutor,
                        'tipo_pago_id'=>$tipo,
                        'ciclo_id'=>$ciclo,
                        'monto'=>$tarifa_regular,
                        'mes_pago'=> $id,
                        'metodo_pago'=> $metodo,
                        'total_neto'=> $neto,
                        'descuento_especial'=>0,
                        'codigo_operacion'=>$codigo,
                    ])->execute();

                    $count++;

                }
            }

            if ($colegiatura_especial != null) {
                foreach ($colegiatura_especial as $key => $id) {
                    $pago = $connection->createCommand()->insert('pago_alumno',[
                        'alumno_id'=>$alumno,
                        'tutor_id'=>$tutor,
                        'tipo_pago_id'=>$tipo,
                        'ciclo_id'=>$ciclo,
                        'monto'=>$tarifa_regular,
                        'mes_pago'=> $id,
                        'metodo_pago'=> $metodo,
                        'total_neto'=> $neto,
                        'descuento_especial'=>1,
                        'codigo_operacion'=>$codigo,
                    ])->execute();
                    
                    $count++;
                }
            }

            return $codigo;


        }else{
            return 'Error al guardar datos de pago.';
        }
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

    public function getCiclo()
    {
        return $this->hasOne(Ciclo::className(), ['id' => 'ciclo_id']);
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
    public function getTipoPago()
    {
        return $this->hasOne(TipoPago::className(), ['id' => 'tipo_pago_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTutor()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'tutor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
