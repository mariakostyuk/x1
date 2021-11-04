<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\Config;
use common\helpers\Arr;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\helpers\DateHelper;


class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }


    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'id_city' => 'Город',
            'username' => 'Email',
            'auth_key' => 'Ключ авторизации',
            'password_hash' => 'Пароль',
            'password_reset_token' => 'Токен',
            'email' => 'Email',
            'gender' => 'Пол',
            'status' => 'Статус',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'phone' => 'Телефон',
            'fio' => 'ФИО',
            'address' => 'Адрес',
            'datebirth' => 'Дата рождения',
            'type' => 'Тип',
            'id_pharmacy' => 'Аптека',
            'company' => 'Наименование компании',
            'inn' => 'ИНН',
            'ogrn' => 'ОГРН',
            'address_legal' => 'Юридический адрес',
            'address_post' => 'Почтовый адрес',
            'bankaccount' => 'Расчетный счет',
            'bank' => 'Банк',
            'bik' => 'БИК',
            'coraccount' => 'Корсчет',
            'contact_person' => 'Контактное лицо',
            'contract' => 'Дата и номер договора',
            'passport' => 'Паспорт',
            'address_propiska' => 'Адрес по прописке',
            'address_real' => 'Фактический адрес',
            'partner_type' => 'Тип',
            'rights' => 'Права доступа',
            'id_partner' => 'Партнер',
            'apikey' => 'Ключ API',
        ];
    }

    public function rules()
    {
        $g = [
            ['username', 'required', 'message' => 'Введите e-mail.'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];

        $r = [];

        // PARTNER
        if ($this->type == 1) {
            // PRIVATE PERSON
            if ($this->partner_type == 1) $r = [
                ['fio', 'required', 'message' => 'Введите ФИО.'],
                ['passport', 'required', 'message' => 'Введите пасспорт.'],
                ['address_propiska', 'required', 'message' => 'Введите адрес.'],
                ['address_real', 'required', 'message' => 'Введите адрес.'],
                ['phone', 'required', 'message' => 'Введите номер телефона.'],
                ['address_propiska', 'required', 'message' => 'Введите адрес.'],
                ['contract', 'required', 'message' => 'Введите дату и номер договора.'],
            ];

            // COMPANY
            if ($this->partner_type == 2) $r = [
                ['company', 'required', 'message' => 'Введите наименование компании.'],
                ['inn', 'required', 'message' => 'Введите ИНН.'],
                ['ogrn', 'required', 'message' => 'Введите ОГРН.'],
                ['address_legal', 'required', 'message' => 'Введите юридический адрес.'],
                ['address_post', 'required', 'message' => 'Введите почтовый адрес.'],
                ['bankaccount', 'required', 'message' => 'Введите расчетный счет.'],
                ['bank', 'required', 'message' => 'Введите название банка.'],
                ['bik', 'required', 'message' => 'Введите БИК.'],
                ['coraccount', 'required', 'message' => 'Введите корсчет.'],
                ['contact_person', 'required', 'message' => 'Введите контактное лицо.'],
                ['phone', 'required', 'message' => 'Введите номер телефона.'],
                ['contract', 'required', 'message' => 'Введите дату и номер договора.'],
            ];
        }

        // DEALER
        if ($this->type == 5) {
            // PRIVATE PERSON
            if ($this->partner_type == 1) $r = [
                ['fio', 'required', 'message' => 'Введите ФИО.'],
                ['id_city', 'required', 'message' => 'Введите город.'],
            ];

            // COMPANY
            if ($this->partner_type == 2) $r = [
                ['company', 'required', 'message' => 'Введите наименование компании.'],
                ['id_city', 'required', 'message' => 'Введите город.'],
                ['inn', 'required', 'message' => 'Введите ИНН.'],
                ['contact_person', 'required', 'message' => 'Введите контактное лицо.'],
                ['phone', 'required', 'message' => 'Введите номер телефона.'],
            ];
        }

        $g = array_merge($g, $r);

        return $g;
    }


    public function TypeList()
    {
        return [
            '0' => 'Администратор',
            '1' => 'Клиент',
            '2' => 'Перевозчик',
        ];
    }

    public function PartnerTypeList()
    {
        return [
            '1' => 'Физическое лицо',
            '2' => 'Юридическое лицо',
        ];
    }

    public function GenderList()
    {
        return [
            '0' => 'Мужской',
            '1' => 'Женский',
        ];
    }

    public function StatusList()
    {
        return [
            '0' => 'Не активный',
            '10' => 'Активный',
        ];
    }

    public function RightsList()
    {
        return [
            '10' => 'Финансы и статистика – просмотр данных и загрузка платежных документов',
            '01' => 'Администрирование номенклатуры – работа с базой лекарств и прайсами',
            '11' => 'Полный доступ',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['id' => 'id_city']);
    }

    public function getCities()
    {
        return $this->hasMany(Cities::className(), ['id_partner' => 'id']);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public function isPartnerCity($cityId)
    {
        for ($i = 0; $i < count($this->cities); $i++)
            if ($this->cities[$i]->id == $cityId)
                return true;
        return false;
    }

    public function ValidateUser()
    {
        if (isset($this->id))
            $user = User::find()->where("username='" . $this->username . "' AND id<>" . $this->id)->one();
        else
            $user = User::find()->where("username='" . $this->username . "'")->one();
        if ($user) {
            $this->addError("username", "Пользователь с таким Email уже существует.");
            return false;
        } else
            return true;
    }



    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if (
            !preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $this->password_hash, $matches)
            || $matches[1] < 4
            || $matches[1] > 30
        )
            return false;
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getAPIkey()
    {
        // GENERATE API KEY IF IT'S NOT SET
        if (!$this->apikey) {
            $this->apikey = Yii::$app->security->generateRandomString(20);
            $this->save(false);
        }

        // SET PHARMACY STATUS TO OK IF IT'S NEW
        if ($this->pharmacy && ($this->pharmacy->status == 0)) {
            $this->pharmacy->status = 1;
            $this->pharmacy->save();
        }

        return $this->apikey;
    }

    public function NewUserPassword()
    {
        $password = rand(10000, 99999);
        $this->setPassword($password);
        $this->save(false);
        return $password;
    }

    public function AddUserWithPhone($phone, $type = 1, $data = "")
    {
        $user = User::find()->where(['phone' => $phone])->one();
        if (!$user) {
            $user = new User;
            $user->phone = $phone;
            $user->username = $phone;
            $user->status = 10;
            $user->type = $type;
            $user->setPassword(Yii::$app->security->generateRandomString());
            $user->created_at = time();

            // PHARMACY
            if ($type == 3)
                $user->id_pharmacy = $data;

            $user->save(false);
        }
        return $user;
    }


    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }



    public function canAccessWebSite()
    {
        if (!$this->auth_key)
            return false;
        $authKey = Yii::$app->getRequest()->getCookies()->getValue('auth_key');
        if ($authKey != $this->auth_key)
            return false;
        else
            return true;
    }

    public function allowAccessWebSite()
    {
        $this->generateAuthKey();
        $this->save(false);

        \Yii::$app->getResponse()->getCookies()->add(new \yii\web\Cookie([
            'name' => 'auth_key',
            'value' => $this->auth_key,
        ]));
    }


    public function PhoneFormat($phone)
    {

        $phone = preg_replace("/[^0-9]+/", "", $phone);
        if (strlen($phone) == 11) {
            $phone = "+" . substr($phone, 0, 1) . " (" . substr($phone, 1, 3) . ") " . substr($phone, 4, 3) . "-" . substr($phone, 7, 2) . "-" . substr($phone, 9, 2);
            return $phone;
        }
        return "";
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $phone = User::PhoneFormat($this->phone);
            if ($phone)
                $this->phone = $phone;
            return true;
        }

        return false;
    }
}
