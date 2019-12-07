<?php
	require 'application/libraries/Razorpay.php';
	use Razorpay\Api\Api;

class orders extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {
		
		print_r("expression");
	}

	public function flat() {
		if(!isset($_POST['quantity'])){
			header("Location: ".BASE_URL."news");
			exit();
		}
		
		$this->orderModel = $this->loadModel('ordersModel');
		$api = new Api(keyId,keySecret);
		$amount = $_POST['quantity'] * unitPrice * 100;
		$orderData = [
		    'amount'          => $amount, // rupees in paise
		    'currency'        => 'INR',
		    'payment_capture' => 1 // auto capture
		];

		$razorpayOrder = $api->order->create($orderData);
		$razorpayOrderId = $razorpayOrder['id'];
		$_SESSION['razorpay_order_id'] = $razorpayOrderId;
		$orderId = $this->orderModel->createOrder($orderData);
		$_SESSION['orderId'] = $orderId;

		$data = [
		    "key"               => keyId,
		    "amount"            => $amount,
		    "name"              => 'Sri Shankara Granthavali',
		    "description"       => "by T.K. Balasubrahmanyam",
		    "image"             => BASE_URL."public/images/mainc/001.jpg",
		    "prefill"           => [
				    "name"              => $_POST['name'],
				    "email"             => $_POST['email'],
				    "contact"           => $_POST['mobile'],
				    "orderId"			=> $orderId,
				    ],
		    "notes"             => [
				    "address1"           => $_POST['address1'],
				    "address2"           => $_POST['address2'],
				    "city"				 => $_POST['city'],
				    "pincode"			 => $_POST['pincode'],
				    "state"				 => $_POST['state'],
				    ],
		    "theme"             => [
				    "color"             => "#F37254"
				    ],
		    "order_id"          => $razorpayOrderId,
		];

		$data = json_encode($data);
		$path = 'flat/payment';
		$this->view($path,$data);
	}

	public function testmail(){
		
		$toEmail = "shiva@srirangadigital.com";
		$toName = "Shivashankar";
		$subject = 'Shankara Granthavali USB Stick';
		$emailBody = 'Thanks for ordering Shankara Granthavali USB Stick';
		
	
		$this->sendLetterToPostman($toEmail, $toName, $subject, $emailBody);
	}

	public function sendLetterToPostman ($toEmail, $toName, $subject, $emailBody) {

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
    	$mail->Subject = $subject;
    	$mail->msgHTML($emailBody);

        return ( $mail->send() ) ? true : $mail->ErrorInfo;
 	}

}

?>
