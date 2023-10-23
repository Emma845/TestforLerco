<?php
namespace app\models\cliente;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $api_username;
    public $email;
    public $password;
    public $rememberMe = true;
    public $status; // holds the information about user status

    /**
     * @var \app\models\user\User
     */
    private $_cliente = false;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'email'],
            ['password', 'validatePassword'],
            ['rememberMe', 'boolean'],

            //[['email', 'password'], 'required', 'on' => 'e'],
            //[['api_username', 'password'], 'required', 'on' => 'u'],
            //[['api_username', 'password'], 'required', 'on' => 'eu'],
            [['api_username', 'password'], 'required', 'on' => 'eu'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute The attribute currently being validated.
     * @param array  $params    The additional name-value pairs.
     */
    public function validatePassword($attribute, $params)
    {
        if ($this->hasErrors()) {
            return false;
        }

        $user = $this->getUser();

        if (!$user || !$user->validatePassword($this->password)) {
            // if scenario is 'lwe' we use email, otherwise we use api_username
            $field = ($this->scenario === 'e') ? 'Correo electrónico' : 'Nombre de usuario' ;

            $this->addError($attribute, $field . ' o contraseña incorrectos.');
        }
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'api_username'   => 'Nombre de usuario',
            'password'   => 'Contraseña',
            'email'      => 'Correo electrónico',
            'rememberMe' => 'Recuérdame',
        ];
    }

    /**
     * Logs in a user using the provided api_username|email and password.
     *
     * @return bool Whether the user is logged in successfully.
     */
    public function login()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->getUser();


        if (!$user) {
            return false;
        }

        // if there is user but his status is inactive, write that in status property so we know for later
        if ($user->status == Cliente::STATUS_INACTIVE) {
            $this->status = $user->status;
            return false;
        }
 
        return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
    }

    /**
     * Helper method responsible for finding user based on the model scenario.
     * In Login With Email 'lwe' scenario we find user by email, otherwise by api_username
     * 
     * @return object The found User object.
     */
    private function findUser()
    {
        return Cliente::findByUsername($this->api_username);

    }

    /**
     * Method that is returning User object.
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_cliente === false) {
            $this->_cliente = $this->findUser();
        }

        return $this->_cliente;
    }
}
