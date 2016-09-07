<?php
    require_once('../phpmailer/class.phpmailer.php');
    require_once('../phpmailer/class.smtp.php');

    $mail = new PHPMailer();

    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->Host = "smtp.gmail.com"; // Endereço do servidor SMTP Gmail
    $mail->SMTPAuth = true; // Autenticação
    $mail->Port       = 587; //Porta, IMPORTANTE!!!
    $mail->SMTPSecure = "tls";
    $mail->Username = 'reginocontato@gmail.com'; // Usuário do servidor SMTP
    $mail->Password = '6b5a5a160a92cb2848a432bf7b48caba';

    //PEGANDO AS INFORMAÇÕES DIGITADAS NO FORM:
    $name = $_POST['name'];
    $email = $_POST['email'];
    // $topic = $_POST['topic'];
    $message = $_POST['message'];
    $data_envio = date('d/m/Y');
    $hora_envio = date('H:i:s');

    //PASSANDO ESSAS INFORMAÇÕES PARA O OBJETO QUE FARÁ O ENVIO POR EMAIL:

    $mail->From = $email;
    $mail->FromName = $name;

    $file = "
        <style type='text/css'>
            body {
                margin: 0px;
                font-family: Verdane;
                font-size: 12px;
                color: #666666;
            }

            a {
                color: #666666;
                text-decoration: none;
            }

            a:hover {
                color: #FF0000;
                text-decoration: none;
            }
        </style>

        <html>
            <table width='510' border='1' cellpadding='1' cellspacing='1' bgcolor='#CCCCCC'>
                <tr>
                    <td>
                        <tr>
                            <td width='500'>Nome: $name</td>
                        </tr>
                        <tr>
                            <td width='320'>E-mail: <b>$email</b></td>
                        </tr>
                        <tr>
                            <td width='320'>Mensagem: $message</td>
                        </tr>
                    </td>
                </tr>
                <tr>
                    <td>Este e-mail foi enviado em <b>$data_envio</b> &agrave;s <b>$hora_envio</b></td>
                </tr>
            </table>
        </html>
        ";

    $destination = "liperegino@gmail.com";
    $mail->AddAddress($destination, 'Felipe Regino');

    $mail->IsHTML(true);
    $mail->CharSet = 'utf-8'; //formatação da linguagem do html que passo no $file

    $mail->Subject  = "Contato via site pessoal";
    $mail->Body = $file;

    $enviado = $mail->Send();

    $mail->ClearAllRecipients();
    $mail->ClearAttachments();


    if ($enviado) {
        echo "Mensagem enviada!";
    } else {
        echo "Não foi possível enviar o e-mail.";
        echo "Informações do erro: " . $mail->ErrorInfo;
    }

?>
