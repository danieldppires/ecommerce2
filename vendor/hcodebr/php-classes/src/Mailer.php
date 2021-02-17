<?php
	namespace Hcode;

	use Rain\Tpl;

	class Mailer
	{
		const USERNAME = "ze.php.7@gmail.com";
		const PASSWORD = "zephp7!mail";
		const NAME_FROM = "Hcode Store";

		private $mail;

		public function __construct($toAddress, $toName, $subject, $tplName, $data = array())
		{
			$config = array(
				"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"] . "/views/email/",
				"cache_dir"     => $_SERVER["DOCUMENT_ROOT"] . "/views-cache/",
				"debug"         => false // set to false to improve the speed
			);
			Tpl::configure($config);
			$tpl = new Tpl;
			foreach ($data as $key => $value) 
			{
				$tpl->assign($key, $value);
			}
			$html = $tpl->draw($tplName, true);


			$this->mail = new \PHPMailer();
			//$this->mail = new PHPMailer\PHPMailer\PHPMailer();

			$this->mail->SMTPOptions = array(
			    'ssl' => array(
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
		    	)
		 	);

		 	$this->mail->CharSet = 'UTF-8';

			$this->mail->isSMTP();

			//Enable SMTP debugging
			// SMTP::DEBUG_OFF = off (for production use) //Deixar quando estiver em produção
			// SMTP::DEBUG_CLIENT = client messages //Deixar para quando estiver fazendo testes
			// SMTP::DEBUG_SERVER = client and server messages //Deixar para quando estiver desenvolvendo
			$this->mail->SMTPDebug = \SMTP::DEBUG_OFF;

			$this->mail->Debugoutput = 'html';

			$this->mail->Host = 'smtp.gmail.com';

			$this->mail->Port = 587;

			//$this->mail->SMTPSecure = \PHPMailer::ENCRYPTION_STARTTLS;
			$this->mail->SMTPSecure = 'tls';

			$this->mail->SMTPAuth = true;

			$this->mail->Username = Mailer::USERNAME;

			$this->mail->Password = Mailer::PASSWORD;

			$this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);

			$this->mail->addAddress($toAddress, $toName);

			$this->mail->Subject = $subject;

			$this->mail->msgHTML($html);

			$this->mail->AltBody = 'This is a plain-text message body';
		}

		public function send()
		{
			return $this->mail->send();
		}
	}	
?>