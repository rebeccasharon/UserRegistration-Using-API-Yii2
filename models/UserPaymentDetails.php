<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_payment_details".
 *
 * @property int|null $UserId
 * @property string $Account_Owner
 * @property string $IBAN
 * @property string|null $Response
 * @property bool $Status
 */
class UserPaymentDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_payment_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customerId'], 'integer'],
            [['owner', 'iban'], 'required'],
            [['iban'], 'string'],
            [['Status'], 'boolean'],
            [['owner'], 'string', 'max' => 30],
         //   [['Response'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'customerId' => 'User ID',
            'owner' => 'Account Owner',
            'iban' => 'IBAN',
            'Response' => 'Response',
            'Status' => 'Status',
        ];
    }
}
