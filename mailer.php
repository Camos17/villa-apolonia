<?php 
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'php-mailer/src/Exception.php';
    require 'php-mailer/src/PHPMailer.php';
    require 'php-mailer/src/SMTP.php';
    // use PHPMailer\PHPMailer\PHPMailer;
    //Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    //Tell PHPMailer to use SMTP
    $mail->SMTPDebug = 2;
    $mail->isSMTP();

    //Set the hostname of the mail server
    $mail->Host = 'smtp.gmail.com';
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6

    //Set the SMTP port number - 587 for authenticated TLS
    $mail->Port = 587;

    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';

    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;

    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "camos8710@gmail.com";

    //Password to use for SMTP authentication
    $mail->Password = "kmosa-200511725";

    //Set who the message is to be sent from
    $mail->setFrom('camos8710@gmail.com', 'Villa Apolonia');

    //Set an alternative reply-to address
    $mail->addReplyTo('no-reply@villaapolonia.com', 'No-Reply');

    //Set who the message is to be sent to
    $mail->addAddress('camos8710@gmail.com', 'Villa Apolonia');

    $mail->AltBody = 'Es posible que la información proporcionada por el cliente no se haya enviado correctamente';
    $mail->addAttachment('img/logoLightOrangeStack.png', 'Logo-Email-VP');

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["first-last"]));
        $name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $telephone = strip_tags(trim($_POST["telephone"]));
        $comment = strip_tags(trim($_POST["comment"]));
        $startDate = strip_tags(trim($_POST["fechaIngreso"]));
        $endDate = strip_tags(trim($_POST["fechaSalida"]));
        $numPersons = strip_tags(trim($_POST["numPersonas"]));

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($telephone) OR !filter_var($email, FILTER_VALIDATE_EMAIL) OR empty($startDate) OR empty($endDate) OR empty($numPersons) ) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Oops! Algo ha salido mal con el envío de tu solicitud. Por favor, completa el formulario e inténtalo nuevamente.";
            exit;
        }

        // Set the email subject.
        $mail->Subject = "Nueva Solicitud Reserva Villa Apolonia";

        $mail->Body    = '<div style="width:100%; display: block; position:relative; margin-top: 40px; margin-bottom: 40px; padding: 0px; background-color: #FFF; color: #FFCD97;">
                            <div style="width:90%; display: block; position:relative; margin: 0px auto; background-color: #00082D;">
                                <div style="display: block; position:relative; margin: 0px auto; padding: 20px 15px; background-color: #FFCD97;">
                                    <h1 style="margin: 20px auto; color: #00082D; font-size: 2rem; font-weight: bold; text-transform: uppercase; text-align: center;">Información Reservas <br> Villa Apolonia</h1>
                                </div>
                                <div style="width:100%; display: block; margin: 0px auto; padding: 40px 0px; position:relative; background-color: #00082D; color: #FFCD97;">
                                    <p style="width: 80%; margin-left: auto; margin-right: auto; text-align: left;">
                                        <span style="margin-bottom: 7px;"><span style="display: inline-block; font-weight: bold;">Nombre:</span> '. $name .'<br> </span>
                                        <span style="margin-bottom: 7px;"><span style="display: inline-block; font-weight: bold;">Teléfono:</span> '. $telephone .'<br></span>
                                        <span style="margin-bottom: 7px;"><span style="display: inline-block; font-weight: bold;">Comentario:</span> ' . $comment . '<br></span>
                                        <span style="margin-bottom: 7px;"><span style="display: inline-block; font-weight: bold;">Fecha Incio Reserva:</span> ' . $startDate . '<br></span>
                                        <span style="margin-bottom: 7px;"><span style="display: inline-block; font-weight: bold;">Fecha Fin Reservas:</span> ' . $endDate . '<br></span>
                                        <span style="margin-bottom: 7px;"><span style="display: inline-block; font-weight: bold;">Número Perosnas:</span> ' . $numPersons . '<br></span>
                                    </p>
                                </div>
                                <div style="width:140px; display: block; margin: 0px auto; padding: 40px 0px; position:relative; background-color: #00082D;">
                                    <img style="width: 100px; height: auto; margin: 0px auto; display:block;" src="cid:Logo-Email-VP" alt="Logo Villa Apolonia">
                                </div>
                            </div>
                        </div>';
        // Build the email headers.
        $email_headers = "From: $name <$email>";

        // Send the email.
        if ($mail->send()) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            // echo "Thank You! Your message has been sent.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "No ha sido posible enviar tu solicitud.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>