<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_details".
 *
 * @property int|null $UserId
 * @property string $House_Number
 * @property string|null $Street
 * @property string|null $City
 * @property int $Zipcode
 * @property bool $Status
 */
class UserDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['UserId', 'Zipcode'], 'integer'],
            [['House_Number', 'Zipcode'], 'required'],
            [['House_Number', 'Street', 'City'], 'string'],
            [['Status'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'UserId' => 'User ID',
            'House_Number' => 'House Number',
            'Street' => 'Street',
            'City' => 'City',
            'Zipcode' => 'Zipcode',
            'Status' => 'Status',
        ];
    }
}
