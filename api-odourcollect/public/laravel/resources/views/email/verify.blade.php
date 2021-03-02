<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <style type="text/css">
    #outlook a {
        padding: 0;
    }
    body {
        width: 100% !important;
        -webkit-text-size-adjust: none;
        margin: 0;
        padding: 0;
        font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
        font-size: 18px;
        line-height: 125%;
        color:#3b637b;
    }
    img {
        border: none;
        font-size: 14px;
        font-weight: bold;
        height: auto;
        line-height: 100%;
        outline: none;
        text-decoration: none;
    }
    p {margin-bottom:20px;}

    .h1 {
        font-size: 25px;
        line-height: 100%;
        font-weight: bold;
    }

    #boton {
        background-color: #2ca567;
        line-height: 100%;
        color: #FFFFFF;
        text-transform: uppercase;
        text-decoration: none;
        letter-spacing:0.5px;
        padding: 15px 50px;
        border-radius:30px;
    }
    .boton-container {
        width: 100%; 
        text-align: center; 
        margin-top:40px; 
        margin-bottom:40px;
    }

    @media only screen and (max-width: 480px) {
        .h1 {
            font-size: 40px !important;
            line-height: 100% !important;
        }
    }
    </style>
</head>
<?php if ($lang == 'es') { ?>
    <body style="margin:0; padding:0; background-color:#FFFFFF;" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    <center>
        <table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%" style="border-collapse:collapse;">
            <tbody>
                <tr>
                    <td valign="top" align="center">
                        <table cellpadding="0" cellspacing="0" width="600" style="border-collapse:collapse;padding:30px;">
                            <tbody>
                                <tr>
                                    <td valign="top" align="center">
                                        <p style="text-align:center;">
                                            <img src="https://odourcollect.eu/img/logo.png" alt="OdourCollect">
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:20px 50px;margin:10px 0; text-align:center;">
                                        <p>Hola {{ $name }}, </p>
                                        <p>Gracias por crear una cuenta con nosotros..<br />¡No olvide completar su registro!</p>
                                        <p>Haga clic en el enlace de abajo para confirmar su dirección de correo electrónico.</p>

                                        <div class="boton-container">
                                            <a href="{{ url('user/verify', $verification_code)}}" id="boton">CONFIRMAR EL CORREO</a>
                                        </div>

                                        <p>Saludos cordiales,<br />El equipo de OdourCollect</p>

                                    </td>
                                </tr>
                               
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </center>
</body>

<?php } else if($lang == 'en'){ ?>
    <body style="margin:0; padding:0; background-color:#FFFFFF;" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    <center>
        <table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%" style="border-collapse:collapse;">
            <tbody>
                <tr>
                    <td valign="top" align="center">
                        <table cellpadding="0" cellspacing="0" width="600" style="border-collapse:collapse;padding:30px;">
                            <tbody>
                                <tr>
                                    <td valign="top" align="center">
                                        <p style="text-align:center;">
                                            <img src="https://odourcollect.eu/img/logo.png" alt="OdourCollect">
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:20px 50px;margin:10px 0; text-align:center;">
                                        <p>Hi {{ $name }}, </p>
                                        <p>Thank you for creating an account with us.<br />Don't forget to complete your registration!</p>
                                        <p>Please click on the link below to confirm your email address.</p>

                                        <div class="boton-container">
                                            <a href="{{ url('user/verify', $verification_code)}}" id="boton">CONFIRM EMAIL ADDRESS</a>
                                        </div>

                                        <p>Kind regards,<br />OdourCollect Team</p>

                                    </td>
                                </tr>
                               
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </center>
</body>
<?php } else {?>
<body style="margin:0; padding:0; background-color:#FFFFFF;" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    <center>
        <table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%" style="border-collapse:collapse;">
            <tbody>
                <tr>
                    <td valign="top" align="center">
                        <table cellpadding="0" cellspacing="0" width="600" style="border-collapse:collapse;padding:30px;">
                            <tbody>
                                <tr>
                                    <td valign="top" align="center">
                                        <p style="text-align:center;">
                                            <img src="https://odourcollect.eu/img/logo.png" alt="OdourCollect">
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:20px 50px;margin:10px 0; text-align:center;">
                                        <p>Hi {{ $name }}, </p>
                                        <p>Thank you for creating an account with us.<br />Don't forget to complete your registration!</p>
                                        <p>Please click on the link below to confirm your email address.</p>

                                        <div class="boton-container">
                                            <a href="{{ url('user/verify', $verification_code)}}" id="boton">CONFIRM EMAIL ADDRESS</a>
                                        </div>

                                        <p>Kind regards,<br />OdourCollect Team</p>

                                    </td>
                                </tr>
                               
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </center>
</body>
<?php } ?>
</html>
