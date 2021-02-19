<?php namespace app\controllers;
use Yii;
use app\models\Users;
use yii\swiftmailer\Mailer;
use mdm\admin\models\form\Login;
use mdm\admin\controllers\UserController as BaseUserController;
class SecurityController extends BaseUserController {
    public function actionLogin() {
        if (!Yii::$app->getUser()->isGuest) {
            return $this->goHome();
        }
        $model = new Login();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goBack();
        } else {
            /*$user = Users::findOne(2);
            $secure_key = $this->secure_key();
            if (empty($user->secure_key)) {
                $user->secure_key = $secure_key;
                $user->act_date = date('Y-m-d');
                $user->save(false);
                return $this->redirect(['/site/activation']);
            } elseif ($user->secure_key != $secure_key) {
                echo "OOPS! Something went wrong in installation.";
                exit;
            } elseif ($user->activation_status == 0 && $user->secure_key != "") {
                return $this->redirect(['/site/activation']);
            } elseif (($user->activation_status == 2) && !empty($user->act_date)) {
                $d1 = new \DateTime(date('Y-m-d', strtotime($user->act_date)));
                $d2 = new \DateTime(date('Y-m-d'));
                $interval = $d1->diff($d2);
                $diff = $interval->format('%a');
                if ($diff > 14) {
                    return $this->redirect(['/site/activation2']);
                }
            }*/
            return $this->render('login', ['model' => $model, ]);
        }
    }
    public function secure_key() {
        $secure_key = "";
        $uuid = shell_exec("echo | {$_ENV['SYSTEMROOT']}\System32\wbem\wmic.exe path win32_computersystemproduct get uuid");
        $lines = explode("\n", $uuid);
        $bios = shell_exec("echo | {$_ENV['SYSTEMROOT']}\System32\wbem\wmic.exe path win32_bios get serialnumber");
        $secure_key = md5(trim($lines[1]) . "" . $bios);
        return $secure_key;
    }
}
?>