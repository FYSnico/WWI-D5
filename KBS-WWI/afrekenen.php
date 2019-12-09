<!DOCTYPE html>
<html>

    <?php   include 'components/header.php';?>

<body>
    <?php
    print('Bedankt voor uw besteslling!<BR> Uw order ID is ' . $_GET["order_id"]);
    print("<BR>");
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';
$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "ssl://smtp.gmail.com";
$mail->Port = 465;
$mail->IsHTML(true);
$mail->Username = "project.wwi.d5@gmail.com";
$mail->Password = "moeilijkwachtwoord";
$mail->SetFrom("project.wwi.d5@gmail.com");
$mail->Subject = "Test";
$mail->Body = "hello";
$mail->AddAddress("julien.kolkman@gmail.com");

 if(!$mail->Send()) {
     echo "Mailer Error: " . $mail->ErrorInfo;
 } else {
     echo "Message has been sent";
 }
?>
</body>

    <?php   include 'components/footer.php';    ?>

</html>
