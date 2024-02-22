<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en_GB">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="x-apple-disable-message-reformatting">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=$subject?></title>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">

        <style type="text/css">
            /* CLIENT-SPECIFIC STYLES */
            body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
            table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
            img { -ms-interpolation-mode: bicubic; }

            body {
                margin: 0;
                padding: 0;
                font-size: 15px;
                line-height: 1.5em;
                background-color: #F9FAFC;
            }

            img {
                border: 0;
                outline: none;
                text-decoration: none;
            }

            table {
                border-collapse: collapse !important;
                font-size: 15px;
            }

            * {
                /* fontfamily na vse zaradi outlooka! */
                font-family: 'Open Sans', Arial, sans-serif;
                box-sizing: border-box;
            }

            /* iOS BLUE LINKS */
            /*a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
            }*/


            /******************/
            /* Default Styles */
            /******************/

            /* Paddings */
            .__pt0{ Padding-top: 0px; }
            .__pt5{ Padding-top: 5px; }
            .__pt10{ Padding-top: 10px; }
            .__pt15{ Padding-top: 15px; }
            .__pt20{ Padding-top: 20px; }
            .__pt30{ Padding-top: 30px; }
            .__pt40{ Padding-top: 40px; }
            .__pt50{ Padding-top: 50px; }

            .__pb0{ Padding-bottom: 0px; }
            .__pb5{ Padding-bottom: 5px; }
            .__pb10{ Padding-bottom: 10px; }
            .__pb15{ Padding-bottom: 15px; }
            .__pb20{ Padding-bottom: 20px; }
            .__pb30{ Padding-bottom: 30px; }
            .__pb40{ Padding-bottom: 40px; }
            .__pb50{ Padding-bottom: 50px; }

            .__pl0{ Padding-left: 0px; }
            .__pl5{ Padding-left: 5px; }
            .__pl10{ Padding-left: 10px; }
            .__pl15{ Padding-left: 15px; }
            .__pl20{ Padding-left: 20px; }
            .__pl30{ Padding-left: 30px; }
            .__pl40{ Padding-left: 40px; }
            .__pl50{ Padding-left: 50px; }

            .__pr0{ Padding-right: 0px; }
            .__pr5{ Padding-right: 5px; }
            .__pr10{ Padding-right: 10px; }
            .__pr15{ Padding-right: 15px; }
            .__pr20{ Padding-right: 20px; }
            .__pr30{ Padding-right: 30px; }
            .__pr40{ Padding-right: 40px; }
            .__pr50{ Padding-right: 50px; }

            p {
                Margin: 0;
            }

            .strong {
                font-weight: 600;
            }

            .uppercase {
                text-transform: uppercase;
            }

            h1, h2, h3, h4 {
                Margin: 0;
            }

            h1 {
                font-size: 28px;
                text-align: center;
                line-height: 1.5em;
            }

            .align-right {
                text-align: right;
            }

            .margin-auto {
                margin: 0 auto;
            }

            .email-wrapper {
                padding: 50px 0;
            }

            .email-container {
                border-collapse: collapse;
            }

            .email-padding {
                padding-left: 30px;
                padding-right: 30px;
            }

            .divider-table-wrapper {
                Padding-top: 15px;
                Padding-bottom: 15px;
            }

            .divider {
                width: 100% !important;
            }

            .divider tr td {
                background-color: #f0f0f0;
                border-collapse:collapse;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                mso-line-height-rule: exactly;
                line-height: 1px;
            }

            .button-link {
                width: 100%;
                Padding: 8px 12px;
                font-size: 16px;
                color: #ffffff;
                text-decoration: none;
                font-weight: bold;
                display: inline-block;
                text-align: center;
            }

            .__lite-grey {
                color: #bbb;
            }

            .footer-cell {
                font-size: 12px;
                color: #bbb;
            }

            /* Other styles */

            .header-logo-image {
                Padding-top: 40px;
                Padding-bottom: 30px;
            }

            .heading {
                font-size: 18px;
                font-weight: bold;
            }

            .heading-two {
                font-size: 16px;
                font-weight: bold;
            }

            @media all and (max-width: 599px) {
                h1 {
                    font-size: 16px !important;
                }

                .email-wrapper {
                    Padding: 0 !important;
                    Padding-bottom: 30px !important;
                }

                .email-container {
                    width: 100% !important;
                }

                .email-padding {
                    Padding-left: 20px !important;
                    Padding-right: 20px !important;
                }

                .header-email,
                .header-phone {
                    display: block !important;
                    padding-bottom: 3px !important;
                }

                .button-table {
                    width: 100% !important;
                }

                .responsive-table {
                    width: 100% !important;
                    display: block !important;
                }

                .responsive-table td.align-right {
                    text-align: left !important;
                }

                .footer-cell {
                    Padding-left: 20px !important;
                    Padding-right: 20px !important;
                }

                /* Other styles */

                .header-logo-image {
                    Padding-top: 30px !important;
                    Padding-bottom: 30px !important;
                }

                .item-image-cell {
                    width: 100% !important;
                    display: block !important;
                    padding-right: 0 !important;
                }

                .item-info-cell {
                    width: 100% !important;
                    display: block !important;
                    padding-top: 10px !important;
                }

                .second-cta-button {
                    padding-top: 15px !important;
                }
            }
    </style>
   </head>
   <body>

        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td class="email-wrapper">
                    <!-- BODY -->
                    <table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="600" class="email-container" style="background-color: #fff; border-collapse: collapse;">
                        <tr>
                            <td align="left" class="header-logo-image email-padding">
                                <a href="<?=get_home_url()?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/email/logo.png" width="160"></a>
                            </td>
                        </tr>
