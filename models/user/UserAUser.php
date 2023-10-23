<?php
namespace app\models\user;

use Yii;

/**
 * This is the model class for table "user_a_user".
 *
 * @property int $id ID
 * @property int $user_agente_id Agente id
 * @property int $user_supervisor_id Supervisor ID
 *
 * @property User $userAgente
 * @property User $userSupervisor
 */
class UserAUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_a_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_agente_id', 'user_supervisor_id'], 'required'],
            [['user_agente_id', 'user_supervisor_id'], 'integer'],
            [['user_agente_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_agente_id' => 'id']],
            [['user_supervisor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_supervisor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_agente_id' => 'User Agente ID',
            'user_supervisor_id' => 'User Supervisor ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAgente()
    {
        return $this->hasOne(User::className(), ['id' => 'user_agente_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSupervisor()
    {
        return $this->hasOne(User::className(), ['id' => 'user_supervisor_id']);
    }
}
