<?php 
require_once APP.'/vendor/phpmailer/PHPMailerAutoload.php'; 


class MailMe extends PHPMailer
{
    
    public function __construct() {
        
        
        //Tell PHPMailer to use SMTP
        $this->isSMTP();

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $this->SMTPDebug = 0;
        
        //Ask for HTML-friendly debug output
        $this->Debugoutput = 'html';

        //Set the hostname of the mail server
        $this->Host = 'smtp.gmail.com';
        // use
        // $this->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $this->Port = 587;

        //Set the encryption system to use - ssl (deprecated) or tls
        $this->SMTPSecure = 'tls';

        //Whether to use SMTP authentication
        $this->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $this->Username = "jetsynthesis@gmail.com";

        //Password to use for SMTP authentication
        $this->Password = "j3tsynthes1s";

        //Set who the message is to be sent from
        $this->setFrom('jetsynthesis@gmail.com', 'Banner Management');

        //Set an alternative reply-to address
        $this->addReplyTo('jetsynthesis@gmail.com', 'Banner Management');

        $this->AltBody = 'Hi, This is not span we are your friend JetSynthesys :)';

        
        
     }

     
    public function getRenderedHTML($path,$data)
    {
        extract($data); 
        ob_start();
        $var =   include  (APP."views/mail/".$path);
        $var=ob_get_contents(); 
        ob_end_clean();
        return $var;
    }

    public function  sendMail()
    {
        //Attach an image file
        //$this->addAttachment('images/phpmailer_mini.png');

        //send the message, check for errors
        if (!$this->send()) {
                return $this->ErrorInfo;
        } else {
                return 0;
        }
    }
}
?>


