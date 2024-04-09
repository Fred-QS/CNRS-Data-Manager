<!DOCTYPE html>
<html lang="<?php echo get_locale(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $subject; ?></title>
    <style type="text/css">
        <?php include(ABSPATH . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-email.css'); ?>
    </style>
</head>
<!--[if mso]>
<xml>
    <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
</xml>
<![endif]-->
<body style="-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; width: 100%; font-family: 'Segoe', sans-serif; margin: 0; padding: 0;">
<!--[if mso | IE]>
<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:650px;" width="650" >
    <tr>
        <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
            <div style="background: #fff; background-color: #fff; max-width: 650px; margin: 0px auto;">
                <table  align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background: #fff; width: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" bgcolor="#fff">
                    <tr>
                        <td style="direction: ltr; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0px;" align="center">
                        <!--[if mso | IE]>
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="vertical-align:top;width:650px;" >
                        <![endif]-->
                                        <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-email-header.php'); ?>
                        <!--[if mso | IE]>
                                    </td>
                                </tr>
                            </table>
                        <![endif]-->
                        </td>
                    </tr>
                </table>
            </div>
<!--[if mso | IE]>
        </td>
    </tr>
</table>
<![endif]-->
<!--[if mso | IE]>
<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:650px;" width="650" >
    <tr>
        <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
            <div style="background: #fff; background-color: #fff; max-width: 650px; margin: 0px auto;">
                <table  align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background: #fff; width: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" bgcolor="#fff">
                    <tr>
                        <td style="direction: ltr; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0px;" align="center">
                        <!--[if mso | IE]>
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="vertical-align:top;width:650px;" >
                        <![endif]-->
                                        <?php include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/' . $template . '.php'); ?>
                        <!--[if mso | IE]>
                                    </td>
                                </tr>
                            </table>
                        <![endif]-->
                        </td>
                    </tr>
                </table>
            </div>
<!--[if mso | IE]>
        </td>
    </tr>
</table>
<![endif]-->
<!--[if mso | IE]>
<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:650px;" width="650" >
    <tr>
        <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
            <div style="background: #fff; background-color: #fff; max-width: 650px; margin: 0px auto;">
                <table  align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background: #fff; width: 100%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" bgcolor="#fff">
                    <tr>
                        <td style="direction: ltr; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0px;" align="center">
                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td style="vertical-align:top;width:650px;" ><![endif]-->
                            <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-email-footer.php'); ?>
                            <!--[if mso | IE]></td></tr></table><![endif]-->
                        </td>
                    </tr>
                </table>
            </div>
<!--[if mso | IE]>
        </td>
    </tr>
</table>
<![endif]-->
</body>
</html>