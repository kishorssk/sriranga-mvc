<?php
    require 'application/libraries/Razorpay.php';
    use Razorpay\Api\Api;
    use Razorpay\Api\Errors\SignatureVerificationError;
    class verify extends Controller {

    public function __construct() {
        
        parent::__construct();
    }

    public function flat() {
        $success = true;

        $error = "Payment Failed";

        if (empty($_POST['razorpay_payment_id']) === false){
            $api = new Api(keyId, keySecret);

            try{
                // Please note that the razorpay order ID must
                // come from a trusted source (session here, but
                // could be database or something else)
                $attributes = array(
                    'razorpay_order_id' => $_SESSION['razorpay_order_id'],
                    'razorpay_payment_id' => $_POST['razorpay_payment_id'],
                    'razorpay_signature' => $_POST['razorpay_signature']
                );

                $api->utility->verifyPaymentSignature($attributes);
            }catch(SignatureVerificationError $e){
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
        }

        $this->orderModel = $this->loadModel('ordersModel');
       

        if ($success === true){
            
            $this->orderModel->updateOrder($_SESSION['orderId'],$_POST['razorpay_payment_id']);
            $data['result'] = $success;
            $data['orderId'] = $_SESSION['orderId'];

            $subject = 'Shankara Granthavali USB Stick : Order Conformation';
            $emailBody = 'Thanks for ordering Shankara Granthavali USB Stick. Your Order ID is <b style="color: red;">'. $_SESSION['orderId'] .'</b>';
            $emailBody .= '<br> Order Deatils are as follows :';
            $emailBody .= '<br> Mobile No:'. $orderDetails['user_mobile'];
            $emailBody .= '<br> Quantity:'. $orderDetails['order_quantity'];
            $emailBody .= '<br> Address :'. $orderDetails['user_address_1'] . $orderDetails['user_address_2'] ;
            $emailBody .= ', ' . $orderDetails['user_city'] . ' - ' . $orderDetails['user_pincode'];
            $emailBody .= ','. $orderDetails['user_state'];
            $emailBody .= '<br><br> If you have any quries Please send a mail to '. CONTACT_EMAIL;
            $emailBody .= '<br><br> Thanks and Regards';
            $emailBody .= '<br>'.SERVICE_NAME;

            // $html = "<p>Your payment was successful</p>
            //          <p>Payment ID: {$_POST['razorpay_payment_id']}</p>";
        }else{
            $data['result'] = $success;
            $subject = 'Shankara Granthavali USB Stick : Payment Failed';
            $emailBody = 'Sorry Unfortunately we could not collect payment on your purchase. If amount is debited from your account then please reply to this email to let us know. Otherwise Please try again. Your Order ID is <b style="color: red;">'. $_SESSION['orderId'] .'</b>.';

            $emailBody .= '<br><br> Thanks and Regards';
            $emailBody .= '<br>'.SERVICE_NAME;
        }

        $orderDetails = $this->orderModel->getOrderDetails($_SESSION['orderId']);
        if($orderDetails != null){
            $toEmail = $orderDetails['user_email'];
            $toName = $orderDetails['username'];
            $this->sendLetterToPostman($toEmail, $toName, $subject, $emailBody);
        }

        // echo $html;
        
        $path = 'flat/paymentmsg';
        $this->view($path,$data);

    }

    public function sendLetterToPostman ($toEmail, $toName, $subject, $emailBody) {
        if($toEmail == null){
            return;
        }

        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = SERVICE_EMAIL;
        $mail->Password = SERVICE_EMAIL_PASSWORD;
        $mail->setFrom(SERVICE_EMAIL, SERVICE_NAME);
        $mail->addAddress($toEmail, $toName);
        $mail->addBCC(CONTACT_EMAIL);
        $mail->addReplyTo(CONTACT_EMAIL, 'Contact');
        $mail->Subject = $subject;
        $mail->msgHTML($emailBody);

        return ( $mail->send() ) ? true : $mail->ErrorInfo;
    }
}
?>


