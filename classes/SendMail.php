<?php 
require('phpmailer/class.phpmailer.php');
require('phpmailer/class.smtp.php');

require_once 'config.php';
require_once PATH_TO_DB;
require_once PATH_TO_SANITIZE;


class SendMail {
	private $_mail,
			$_db;
	
	public function __construct($exception = true) {
		$this->_db = DB::getInstance();
		$this->_mail = new PHPMailer($exception);
	}//end consturctor
	
	
	public function createMessage($addres_to, $name_to, $subject, $body) {
		//configuration to send a mail
		$this->setSingleTo();
		$this->setSMTPSecure();
		$this->setSMTPAuth();
		$this->setMailerDebug();
		$this->setSMTPDebug();
		$this->setFrom();
		$this->setFromName('Quiz');
		$this->setAddReplyTo();
		$this->setLanguage();
		$this->setCharSet();
		$this->setIsHTML();
		$this->setServer();
		$this->setMessage($subject, $body);
		$this->setAddress($addres_to, $name_to);
		$this->setAddressBCC('mail.test.app@wp.pl', 'Biuro Quiz ĄÓŹ');
		
		//sen a mail
		try{
			$this->_mail->Send();
			$receiver = $name_to.' ('.$addres_to.')';
			$sender = $this->_mail->FromName.' ('.$this->_mail->From.')	';
			//fields table on database
			$fields = array(
				'receiver' 		=> $receiver, 
				'receiverCC' 	=> NULL, 
				'receiverBCC' 	=> NULL, 
				'sender' 		=> $sender,
				'header' 		=> $this->_mail->GetSentMIMEMessage(), 
				'subject' 		=> $this->_mail->Subject, 
				'body' 			=> $this->_mail->Body,
				'data_send' 	=> date('Y-m-d H:i:s')
			);
			//add information about send mail
			if(!$this->_db->insert('messageSsended', $fields)) {
				throw new Exception('#40453 Error add message');
			}
		}catch(Exception $e) {
			echo 'Error send message'.$e->getMessage();
		}
	}
	
	
	public function setSingleTo($SingleTo = false) {
		return $this->_mail->SingleTo = $SingleTo;
	}
	public function setSMTPSecure($SMTPSecure = 'ssl') {
		// options: 'ssl', 'tls' , ''
		return $this->_mail->SMTPSecure = $SMTPSecure;
	}
	public function setSMTPAuth($SMTPAuth = true) {
		return $this->_mail->SMTPAuth = $SMTPAuth;
	}
	public function setMailerDebug($MailerDebug = false) {
		return $this->_mail->MailerDebug = $MailerDebug;
	}
	public function setSMTPDebug($SMTPDebug = 0) {
		return $this->_mail->SMTPDebug = $SMTPDebug;
	}
	public function setFrom($From = 'mail.test.app@wp.pl') {
		return $this->_mail->From = $From;
	}
	public function setFromName($FromName = 'Szostkiewicz :)') {
		return $this->_mail->FromName = $FromName;
	}
	public function setAddReplyTo($address = 'mail.test.app@wp.pl', $name = '') {
		return $this->_mail->AddReplyTo($address, $name);
	}
	public function setLanguage($lang = 'pl', $path = 'phpmailer/language/') {
		return $this->_mail->SetLanguage($lang, $path);
	}
	public function setCharSet($charset = 'utf-8') {
		return $this->_mail->CharSet = $charset;
	}
	public function setIsHTML($value = true) {
		return $this->_mail->isHTML($value);
	}
	public function setServer($host = 'smtp.wp.pl', $mailer = 'smtp', $user = 'mail.test.app@wp.pl', $password = 'QWERTY12345', $port = 465) {
		$this->_mail->Host 		= $host;
		$this->_mail->Mailer	= $mailer;
		$this->_mail->Username 	= $user;
		$this->_mail->Password 	= $password;
		$this->_mail->Port 		= $port;
	}
	
	public function setMessage($subject = 'Default subject', $body, $path_attachment = '') {
		$this->_mail->Subject		= $subject;
		$this->_mail->Body			= $body;
		$this->_mail->AddAttachment = $path_attachment;
	}
	
	public function setAddress($address, $address_name = '') {
		return $this->_mail->AddAddress($address, $address_name);
	}
	
	public function setAddressCC($CC, $CC_name = '') {
		return $this->_mail->AddCC($CC, $CC_name);
	}
	
	public function setAddressBCC($BCC, $BCC_name = '') {
		return $this->_mail->AddBCC($BCC, $BCC_name);
	}  
	
	public function toString() {
		echo $this->_mail->Host .'<br/>';
		echo htmlentities($this->_mail->GetSentMIMEMessage()).'<br/>';
	}
}//end class