<?php
header('Content-type: application/json');
require_once('php-mailer/PHPMailerAutoload.php'); // Include PHPMailer

$mail = new PHPMailer();
$emailTO = $emailBCC =  $emailCC = array(); $formEmail = '';

### Enter Your Sitename 
$sitename = 'CoinEx Shop';

### Enter your email addresses: @required
$emailTO[] = array( 'email' => 'Allcoinsneeded@gmail.com', 'name' => 'Your Name' ); 

### Enable bellow parameters & update your BCC email if require.
//$emailBCC[] = array( 'email' => 'email@yoursite.com', 'name' => 'Your Name' );

### Enable bellow parameters & update your CC email if require.
//$emailCC[] = array( 'email' => 'email@yoursite.com', 'name' => 'Your Name' );

### Enter Email Subject
$subject = "Contact Us" . ' - ' . $sitename; 

### If your did not recive email after submit form please enable below line and must change to your correct domain name. eg. noreply@example.com
//$formEmail = 'noreply@yoursite.com';

### Success Messages
$msg_success = "We have <strong>successfully</strong> received your message. We'll get back to you soon.";

if( $_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST["signup-fname"]) && $_POST["signup-fname"] != '' && isset($_POST["signup-email"]) && $_POST["signup-email"] != '') {
		### Form Fields
		$cf_fname = $_POST["signup-fname"];
		$cf_email = $_POST["signup-email"];
		$cf_mname = isset($_POST["signup-mname"]) ? $_POST["signup-mname"] : '';
		$cf_lname = isset($_POST["signup-lname"]) ? $_POST["signup-lname"] : '';
		$cf_tel = isset($_POST["signup-tel"]) ? $_POST["signup-tel"] : '';
		$cf_country = isset($_POST["signup-country"]) ? $_POST["signup-country"] : '';
		$cf_bname = isset($_POST["signup-acctn"]) ? $_POST["signup-acctn"] : '';
		
        $cf_acctname = isset($_POST["signup-acctna"]) ? $_POST["signup-acctna"] : '';
        $cf_acctnum = isset($_POST["signup-acct"]) ? $_POST["signup-acct"] : '';

		$honeypot 	= isset($_POST["form-anti-honeypot"]) ? $_POST["form-anti-honeypot"] : false;
		$bodymsg = '';
		
		if ($honeypot == '' && !(empty($emailTO))) {
			$mail->IsHTML(true);
			$mail->CharSet = 'UTF-8';

			$mail->From = ($formEmail !='') ? $formEmail : $cf_email;
			$mail->FromName = $cf_fname . ' - ' . $sitename;
			$mail->AddReplyTo($cf_email, $cf_fname);
			$mail->Subject = $subject;
			
			foreach( $emailTO as $to ) {
				$mail->AddAddress( $to['email'] , $to['fname'] );
			}
			
			### if CC found
			if (!empty($emailCC)) {
				foreach( $emailCC as $cc ) {
					$mail->AddCC( $cc['email'] , $cc['fname'] );
				}
			}
			
			### if BCC found
			if (!empty($emailBCC)) {
				foreach( $emailBCC as $bcc ) {
					$mail->AddBCC( $bcc['email'] , $bcc['fname'] );
				}				
			}

			### Include Form Fields into Body Message
			
            $bodymsg .= isset($cf_fname) ? "First Name: $cf_fname<br><br>" : '';
            $bodymsg .= isset($cf_mname) ? "Middle Name: $cf_mname<br><br>" : '';
            $bodymsg .= isset($cf_lname) ? "Last Name: $cf_lname<br><br>" : '';
            $bodymsg .= isset($cf_email) ? "Email Address: $cf_email<br><br>" : '';
            $bodymsg .= isset($cf_tel) ? "Phone Number: $cf_tel<br><br>" : '';
            $bodymsg .= isset($cf_country) ? "Country: $cf_country<br><br>" : '';
            $bodymsg .= isset($cf_bname) ? "Bank Name: $cf_bname<br><br>" : '';
            $bodymsg .= isset($cf_acctname) ? "Account Name: $cf_acctname<br><br>" : '';
            $bodymsg .= isset($cf_acctnum) ? "Account Number: $cf_acctnum<br><br>" : '';
	
			$bodymsg .= $_SERVER['HTTP_REFERER'] ? '<br>---<br><br>This email was sent from [CoinEx Shop]: ' . $_SERVER['HTTP_REFERER'] : '';
			
			// Protect Submission from outside
			$mail->MsgHTML( $bodymsg );
			$is_emailed = $mail->Send();

			if( $is_emailed === true ) {
				$response = array ('result' => "success", 'message' => $msg_success);
			} else {
				$response = array ('result' => "error", 'message' => $mail->ErrorInfo);
			}
			echo json_encode($response);
			
		} else {
			echo json_encode(array ('result' => "error", 'message' => "Bot <strong>Detected</strong>.! Clean yourself Botster.!"));
		}
		
		
{
//  To redirect form on a particular page
header("Location:https://www.coin-ex.shop/commitment.html");

}

	} else {
		echo json_encode(array ('result' => "error", 'message' => "Please <strong>Fill up</strong> all required fields and try again."));
	}
}
