<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\UserDetails;
use app\models\UserPaymentDetails;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public static function allowedDomains() {
        return [
            // '*',                        // star allows all domains
            'https://37f32cl571.execute-api.eu-central-1.amazonaws.com/default/wunderfleet-recruiting-backend-dev-save-payment-data',
        ];
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            
            'corsFilter'  => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    // restrict access to domains:
                    'Origin'                           => static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['POST'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 3600,                 // Cache (seconds)
                ],
            ],

        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        $detailsmodel = new UserDetails();
        $paymentmodel = new UserPaymentDetails();
        $user = array();
        $session = Yii::$app->session;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            $user['fname']=$model->FirstName;    
            $user['lname']=$model->LastName;    
            $user['telephone']=$model->Telephone;
            $session['user'] = $user;
            $session['userid'] = $model->UserId;

            $model->Status = 0;
            $model->save();
            return $this->redirect(['renderstep2', 'detailsmodel' => $detailsmodel, 'userid' => $model->UserId ]);
        }

        if (isset($session['user']))
        {
            //print "exit" ; exit;
            $model->FirstName = $session['user']['fname'];
            $model->LastName = $session['user']['lname'];
            $model->Telephone = $session['user']['telephone'];
        }

        return $this->render('create', [
            'model' => $model,
            'detailsmodel' => $detailsmodel,
            'paymentmodel' => $paymentmodel,
        ]);
    }

    public function actionRenderstep2()
    {
        $paymentmodel = new UserPaymentDetails();
        $detailsmodel = new UserDetails();
        $user_details = array();
        $session = Yii::$app->session;
        $userid = isset($session['userid']) ? $session['userid'] : $detailsmodel->UserId;

        if ($detailsmodel->load(Yii::$app->request->post()) && $detailsmodel->validate()) 
        {

            $user_details['House_Number']=$detailsmodel->House_Number;    
            $user_details['Street']=$detailsmodel->Street;    
            $user_details['City']=$detailsmodel->City;
            $user_details['Zipcode']=$detailsmodel->Zipcode;
            $session['user_details'] = $user_details;    
        
            $detailsmodel->Status = 0;
            $detailsmodel->save();
            return $this->redirect(['renderstep3', 'paymentmodel' => $paymentmodel,'userid' => $detailsmodel->UserId]);
        }

        if (isset($session['user_details']))
        {
           // print_r($session['user_details']); exit;
            $detailsmodel->House_Number = $session['user_details']['House_Number'];
            $detailsmodel->Street = $session['user_details']['Street'];
            $detailsmodel->City = $session['user_details']['City'];
            $detailsmodel->Zipcode = $session['user_details']['Zipcode'];
        }

        return $this->render('renderstep2', [
            'detailsmodel' => $detailsmodel,
        ]);
    }

    public function actionRenderstep3()
    {
        $model = new Users();
        $detailsmodel = new UserDetails();
        $paymentmodel = new UserPaymentDetails();
        $payment_details = array();
        $session = Yii::$app->session;
        $userid = isset($session['userid']) ? $session['userid'] : isset($_GET['userid']) ? $_GET['userid'] : $paymentmodel->customerId ;

        if ($paymentmodel->load(Yii::$app->request->post()) && $paymentmodel->validate()) 
        {
            $ch = curl_init( "https://37f32cl571.execute-api.eu-central-1.amazonaws.com/default/wunderfleet-recruiting-backend-dev-save-payment-data" );
            # Setup request to send json via POST.
            $payload = json_encode( $_REQUEST['UserPaymentDetails'] );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            # Return response instead of printing.
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            # Send request.
            $result = curl_exec($ch);
            curl_close($ch);
            $Paymentdataid = json_decode($result, true);
            $userid = isset($session['userid']) ? $session['userid'] : isset($_GET['userid']) ? $_GET['userid'] : $paymentmodel->customerId ;
            if ( isset($Paymentdataid['paymentDataId']) )
            {
               // print_r( $Paymentdataid) ; exit;
                $paymentmodel->Response = $Paymentdataid['paymentDataId'];
                $paymentmodel->Status = 1;
                Yii::$app->db->createCommand()->update('users', ['Status' => 1], "UserId = $userid")->execute();
                Yii::$app->db->createCommand()->update('user_details', ['Status' => 1], "UserId = $userid")->execute();
                Yii::$app->db->createCommand()->delete('users', ['Status' => 0])->execute();
                Yii::$app->db->createCommand()->delete('user_details', ['Status' => 0])->execute();
                Yii::$app->db->createCommand()->delete('user_payment_details', ['Status' => 0])->execute();
            }
            else
            {
                $paymentmodel->Status = 0;
                Yii::$app->session->setFlash('Failed',"Error Occurred while Registration. Please try again later!!");
                return $this->redirect(['index']);
            }

            $payment_details['owner']=$paymentmodel->owner;
            $payment_details['iban']=$paymentmodel->iban;
            $session['payment_details'] = $payment_details;
            $paymentmodel->save(false);
            Yii::$app->session->setFlash('Success',"User Registered successfully!! Payment Data ID is : $paymentmodel->Response");
            return $this->redirect(['index']);
        }

        if (isset($session['payment_details']))
        {
            $paymentmodel->owner = $session['payment_details']['owner'];
            $paymentmodel->iban = $session['payment_details']['iban'];
        }
        return $this->render('renderstep3', [
            'model' => $model,
            'detailsmodel' => $detailsmodel,
            'paymentmodel' => $paymentmodel,
            'userid' => $userid,
        ]);
    }



    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->UserId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
