<?php
namespace app\models\cliente;

use Yii;
use app\models\documento\Documento;
/**
 * This is the model class for table "cliente_documento".
 *
 * @property int $id ID
 * @property int $cliente_id Cliente ID
 * @property int $documento_id Documento ID
 *
 * @property Cliente $cliente
 * @property Documento $documento
 */
class ClienteDocumento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente_documento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cliente_id', 'documento_id'], 'required'],
            [['cliente_id', 'documento_id'], 'integer'],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['cliente_id' => 'id']],
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
            'cliente_id' => 'Cliente ID',
            'documento_id' => 'Documento ID',
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
    public function getDocumento()
    {
        return $this->hasOne(Documento::className(), ['id' => 'documento_id']);
    }
}
