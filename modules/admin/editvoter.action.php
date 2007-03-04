<?php

/* Restricts access to specified user types or no user type at all */
$this->restrict(USER_ADMIN);

/* Required Files */
Hypworks::loadDao('Voter');
Hypworks::loadAddin('rfc822');
require_once APP_LIB . '/phpmailer/class.phpmailer.php';
require_once 'common/PinPasswordGenerator.class.php';

/*
 * Places the POST variables in local context.
 * E.g, $_POST['username'] can be accessed
 * using $username directly. If the variable
 * $username already exists, then it will not
 * be overwritten.
 */
extract($_POST, EXTR_REFS|EXTR_SKIP);
$voterid = $PARAMS[0];

if(empty($firstname))
	$this->addError('firstname', 'First name is required');
if(empty($lastname))
	$this->addError('lastname', 'Last name is required');
if(empty($email)) {
	$this->addError('email', 'Email is required');
}
else {
	$voter = Voter::select($voterid);
	if(!isValidEmail($email)) {
		$this->addError('email', 'Email is invalid');
	}
	else if($email != $voter['email'] && Voter::doEmailExists($email)) {
		$this->addError('email', 'Email already exists');
	}
}
if(strtolower(ELECTION_UNIT) == "enable") {
	if(empty($unitid))
		$this->addError('unit', 'Specific unit is required');
}

if($this->hasError()) {
	$this->addUserInput($_POST);
	$this->forward("editvoter/$voterid");
}
else {
	if(isset($password) && $password == 1) {
		// regenerate password
		$password = PinPasswordGenerator::generatePassword();
	}
	if(isset($pin) && $pin == 1) {
		// generate pin
		$pin = PinPasswordGenerator::generatePin();
	}

	if(!empty($password) || !empty($pin)) {
		if(strtolower(ELECTION_PIN_PASSWORD_GENERATION) == "email") {
			// Create PHPMailer object
			$mail = new PHPMailer();
			$mail->From     = MAIL_FROM;
			$mail->FromName = MAIL_FROM_NAME;
			$mail->Host     = MAIL_HOST;
			$mail->Port     = MAIL_PORT;
			$mail->Mailer   = MAIL_MAILER;
			$mail->SMTPAuth  = MAIL_SMTPAUTH;
			$mail->Username  = MAIL_USERNAME;
			$mail->Password  = MAIL_PASSWORD;
		}
		if(!empty($password)) {
			if(strtolower(ELECTION_PIN_PASSWORD_GENERATION) == "email") {
				$mail->Subject = "Halalan Auto-Generated Password";
			
				// Create Mail Body
				$body  = "Mabuhay!<br /><br />";
				$body .= "Ang bagong password mo ay " . $password;
				$body .= "<br /><br />";
				$body .= "Halalan";
			
				// Plain text body (for mail clients that cannot read HTML)
				$text_body  = "Mabuhay!\n\n";
				$text_body .= "Ang bagong password mo ay " . $password;
				$text_body .= "\n\n";
				$text_body .= "Halalan";
			
				$mail->Body    = $body;
				$mail->AltBody = $text_body;
				$mail->AddAddress($email);
			
				if(!$mail->Send()) {
					echo $mail->ErrorInfo;
					echo '<br />There has been a mail sending error.<br/>';
					echo "<a href=\"editvoter/$voterid\">[Back to Edit Voter]</a></p>";
					exit();
				}
				
				// Clear all addresses and attachments for next loop
				$mail->ClearAddresses();
				$mail->ClearAttachments();
			}
			else if(strtolower(ELECTION_PIN_PASSWORD_GENERATION) == "web") {
				$this->addMessage('password', "Election password: <strong>$password</strong>");
			}
			$password = sha1($password);
			Voter::update(compact('firstname', 'lastname', 'email', 'password'), $voterid);
		}
		if(!empty($pin)) {
			if(strtolower(ELECTION_PIN_PASSWORD_GENERATION) == "email") {
				$mail->Subject = "Halalan Auto-Generated Pin";
			
				// Create Mail Body
				$body  = "Mabuhay!<br /><br />";
				$body .= "Ang bagong pin mo ay " . $pin;
				$body .= "<br /><br />";
				$body .= "Halalan";
			
				// Plain text body (for mail clients that cannot read HTML)
				$text_body  = "Mabuhay!\n\n";
				$text_body .= "Ang bagong pin mo ay " . $pin;
				$text_body .= "\n\n";
				$text_body .= "Halalan";
			
				$mail->Body    = $body;
				$mail->AltBody = $text_body;
				$mail->AddAddress($email);
			
				if(!$mail->Send()) {
					echo $mail->ErrorInfo;
					echo '<br />There has been a mail sending error.<br/>';
					echo "<a href=\"editvoter/$voterid\">[Back to Edit Voter]</a></p>";
					exit();
				}
				
				// Clear all addresses and attachments for next loop
				$mail->ClearAddresses();
				$mail->ClearAttachments();
			}
			else if(strtolower(ELECTION_PIN_PASSWORD_GENERATION) == "web") {
				$this->addMessage('pin', "Election pin: <strong>$pin</strong>");
			}
			$pin = sha1($pin);
			Voter::update(compact('firstname', 'lastname', 'email', 'pin'), $voterid);
		}
	}
	else {
		Voter::update(compact('firstname', 'lastname', 'email'), $voterid);
	}
	if(strtolower(ELECTION_UNIT) == "enable") {
		Voter::update(compact('unitid'), $voterid);
	}
	$this->addMessage('editvoter', 'The voter has been successfully edited');
	$this->forward('voters');
}

?>