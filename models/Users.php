<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $UserId
 * @property string $FirstName
 * @property string $LastName
 * @property int $Telephone
 * @property bool $Status
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FirstName', 'LastName', 'Telephone'], 'required'],
            [['Telephone'], 'integer'],
            [['Status'], 'boolean'],
            [['FirstName', 'LastName'], 'string', 'max' => 20],
        ];
    }

    public function getrelation_tableUserPaymentDetails()
    {
        return $this->hasOne(UserPaymentDetails::className(), ['customerId' => 'UserId']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'UserId' => 'User ID',
            'FirstName' => 'First Name',
            'LastName' => 'Last Name',
            'Telephone' => 'Telephone',
            'Status' => 'Status',
        ];
    }
}
