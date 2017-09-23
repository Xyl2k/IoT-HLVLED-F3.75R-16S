<?php
/*
 * Reset new password email sender function
 */
if(! function_exists('forgotPassEmail')){
	function forgotPassEmail($userData){
        $resetPassLink = 'http://example.com/admin/resetPassword.php?fp_code='.$userData['forgot_pass_identity'];
		$serderName = 'HLVLED-F3.75R-16S';
		$serderEmail = 'sender@example.com';
		
        $to = $userData['email'];
		$subject = "Password Update Request | HLVLED-F3.75R-16S";
        $mailContent = '<p>Dear <strong>'.htmlentities($userData['first_name']).'</strong>,</p>
        <p>Recently a request was submitted to reset a password for your account. If this was a mistake, just ignore this email and nothing will happen.</p>
        <p>To reset your password, visit the following link: <a href="'.$resetPassLink.'">'.$resetPassLink.'</a></p>
        <p>Let us know at contact@example.com in case of any query or feedback.</p>
        <p>Regards,<br/><strong>HLVLED-F3.75R-16S Staff</strong></p>';
        
        //set content-type header for sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        //additional headers
        $headers .= 'From: '.$serderName.'<'.$serderEmail.'>' . "\r\n";
        //send email
        mail($to,$subject,$mailContent,$headers);
        return true;
    }
}