<?php
namespace app\models\viaje;

use Yii;
use app\models\cliente\Cliente;

/**
 * This is the model class for table "viaje_cliente".
 *
 * @property int $id ID
 * @property int $viaje_id Viaje ID
 * @property int $cliente_id Cliente ID
 *
 * @property Cliente $cliente
 * @property Viaje $viaje
 */
class ViajeCliente extends \yii\db\ActiveRecord
{

    public $viajeClienteArray;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'viaje_cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['viaje_id', 'cliente_id'], 'required'],
            [['viaje_id', 'cliente_id','porcentaje'], 'integer'],
            [['viajeClienteArray'], 'safe'],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['cliente_id' => 'id']],
            [['viaje_id'], 'exist', 'skipOnError' => true, 'targetClass' => Viaje::className(), 'targetAttribute' => ['viaje_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'viaje_id' => 'Viaje ID',
            'cliente_id' => 'Cliente ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViaje()
    {
        return $this->hasOne(Viaje::className(), ['id' => 'viaje_id']);
    }

    public function saveCliente($viaje_id)
    {
        $viajeClienteArray = json_decode($this->viajeClienteArray);
        ViajeCliente::deleteAll([ "viaje_id" => $viaje_id]);

        if ($viajeClienteArray) {
            foreach ($viajeClienteArray as $key => $viajeCliente) {
                $ViajeCliente = new ViajeCliente();
                $ViajeCliente->viaje_id     = $viaje_id;
                $ViajeCliente->cliente_id   = $viajeCliente->id;
                $ViajeCliente->porcentaje   = $viajeCliente->porcentaje ? $viajeCliente->porcentaje : 0;
                $ViajeCliente->save();
            }
        }
        return true;
    }
}
