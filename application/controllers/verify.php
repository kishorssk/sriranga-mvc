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

        if (empty($_POST['razorpay_payment_id']) === false)
        {
            $api = new Api(keyId, keySecret);

            try
            {
                // Please note that the razorpay order ID must
                // come from a trusted source (session here, but
                // could be database or something else)
                $attributes = array(
                    'razorpay_order_id' => $_SESSION['razorpay_order_id'],
                    'razorpay_payment_id' => $_POST['razorpay_payment_id'],
                    'razorpay_signature' => $_POST['razorpay_signature']
                );

                $api->utility->verifyPaymentSignature($attributes);
            }
            catch(SignatureVerificationError $e)
            {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
        }

        if ($success === true)
        {
            $this->orderModel = $this->loadModel('ordersModel');
            $this->orderModel->updateOrder($_SESSION['orderId'],$_POST['razorpay_payment_id']);
            $data['result'] = $success;
            $data['orderId'] = $_SESSION['orderId'];
            // $html = "<p>Your payment was successful</p>
            //          <p>Payment ID: {$_POST['razorpay_payment_id']}</p>";
        }
        else
        {
            $data['result'] = $success;
            // $html = "<p>Your payment failed</p>
            //          <p>{$error}</p>";
        }

        // echo $html;

        $path = 'flat/paymentmsg';
        $this->view($path,$data);

    }
}
?>

<?php

