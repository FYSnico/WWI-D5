<?php include 'components/header.php';
include 'components/config.php';
use PHPMailer\PHPMailer\PHPMailer;
//print_r($_POST);
if(!isset($_POST["naam"])) {
    ?>
    <div class="container">
        <form id="contact-form" class="bg-white p-3 rounded shadow" method="post" action="contact.php" role="form">
            <div class="">
                <h1>Neem contact met ons op</h1>
                <p>U kunt met een contactformulier contact met ons opnemen.</p>
            </div>
            <br>
            <div class="controls">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_name">Naam *</label>
                            <input id="form_name" type="text" name="naam" class="form-control"
                                   value="<?php if(isset($_SESSION["email"])){print($_SESSION["naam"]);}?>"
                                   placeholder="<?php if(!isset($_SESSION["email"])){print("Voer a.u.b. uw naam in");}?>" required="required"
                                   data-error="Voornaam is verplicht">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group">-->
<!--                            <label for="form_lastname">Achternaam *</label>-->
<!--                            <input id="form_lastname" type="text" name="achternaam" class="form-control"-->
<!--                                   placeholder="Voer a.u.b. uw achternaam in" required="required"-->
<!--                                   data-error="Achternaam is verplicht">-->
<!--                            <div class="help-block with-errors"></div>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_email">Email *</label>
                            <input id="form_email" type="email" name="email" class="form-control"
                                   value="<?php if(isset($_SESSION["email"])){print($_SESSION["email"]);}?>"
                                   placeholder="<?php if(!isset($_SESSION["email"])){print("Voer a.u.b. uw email in");}?>" required="required"
                                   data-error="Geldige email is verplicht">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_need">Specificeer uw behoefte *</label>
                            <select id="form_need" name="behoefte" class="form-control" required="required"
                                    data-error="Specific a.u.b. uw behoefte">
                                <option value=""></option>
                                <option value="offerte">Offerte aanvragen</option>
                                <option value="bestelstatus">Verzoek om bestelstatus</option>
                                <option value="factuurkopie">Vraag een kopie van een factuur aan</option>
                                <option value="anders">Anders</option>
                            </select>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="form_message">Bericht *</label>
                            <textarea id="form_message" name="bericht" class="form-control" placeholder="" rows="4"
                                      required="required" data-error="Voer a.u.b. uw bericht in"></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-1">
                        <input type="submit" class="btn btn-outline-danger btn-send" value="Verzenden">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-muted">De met een (<strong class="text-danger">*</strong>) gemarkeerde velden
                            moeten worden ingevuld.</p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php
}
else{
    $naam = $_POST["naam"];
    $email = $_POST["email"];
    $behoefte = $_POST["behoefte"];
    $bericht = $_POST["bericht"];

    $sql = "INSERT INTO contact VALUES(NULL, '$naam', '$email', '$bericht', '$behoefte')";
    $stmt = $pdo->query($sql);
    unset($stmt);

    $bevestigingsmail = "Bedankt voor uw bericht<br>We zullen zo snel mogelijk bij u terugkomen<br><br>Vriendelijke groeten WWI";
    $sentTo = $email;
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';


    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = "ssl://smtp.gmail.com";
    $mail->Port = 465;
    $mail->IsHTML(true);
    $mail->Username = "project.wwi.d5@gmail.com";
    $mail->Password = "moeilijkwachtwoord";
    $mail->SetFrom("project.wwi.d5@gmail.com");
    $mail->Subject = "Berichtbevestiging";
    $mail->Body = $bevestigingsmail;
    $mail->AddAddress($sentTo);

    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    ?>
    <h1>
        Er is iets mis gegaan, probeer alstublieft opniew een bericht te versturen.
    </h1>
    <?php
    }
    else{
    ?>
    <h1>
        Uw bericht is verstuurd
    </h1>
    <p>
        U ontvangt een bevestigingsmail!
     </p>
    <?php
    }
    ?>
    <?php
    unset($_POST["voornaam"]);
    unset($_POST["achternaam"]);
    unset($_POST["email"]);
    unset($_POST["behoefte"]);
    unset($_POST["bericht"]);
}
?>
<?php include 'components/footer.php';?>