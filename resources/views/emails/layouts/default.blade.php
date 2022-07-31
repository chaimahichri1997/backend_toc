<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
        <title>
        </title>
        <!--[if !mso]>
        <!-- -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<![endif]-->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <style type="text/css">#outlook a { padding:0; }
        .ReadMsgBody { width:100%; }
        .ExternalClass { width:100%; }
        .ExternalClass * { line-height:100%; }
        body { margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%; }
        table, td { border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt; }
        img { border:0;height:auto;line-height:100%; outline:none;text-decoration:none;-ms-interpolation-mode:bicubic; }
        p { display:block;margin:13px 0; }
    a,a:visited{
            color: black;
        }</style>
        <!--[if !mso]>
        <!-->
        <style type="text/css">
        @media only screen and (max-width:480px) {
            @-ms-viewport { width:320px; }
            @viewport { width:320px; }
            .mj-column-per-33 *{
                padding: 5px 0 5px 0 !important;
            }
            .hide-mobile{
                display: none !important;
            }
            .copyright .w-50-mobile{
                width: 50% !important;
            }
        }</style>
        <!--<![endif]-->
        <!--[if mso]>
        <xml>
        <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml>
        <![endif]-->
        <!--[if lte mso 11]>
        <style type="text/css">
        .outlook-group-fix { width:100% !important; }
        </style>
        <![endif]-->
        <!--[if !mso]>
        <!-->
        {{-- <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
        <style type="text/css">@import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);</style> --}}
        <!--<![endif]-->
        <style type="text/css">@media only screen and (min-width:480px) {
        .mj-column-per-100 { width:100% !important; max-width: 100%; }
        .mj-column-per-33 { width:33.333333333333336% !important; max-width: 33.333333333333336%; }
        .mj-column-per-50 { width:50% !important; max-width: 50%; }
        }</style>
        <style type="text/css">@media only screen and (max-width:480px) {
        table.full-width-mobile { width: 100% !important; }
        td.full-width-mobile { width: auto !important; }
        }</style>
    </head>
    <body style="background-color:#F4F4F4;">
        <div style="background-color:#F4F4F4; padding-top: 30px">

            <table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width: 100%; max-width: 1120px; margin: auto; box-shadow: 0px 0px 15px #00000012;">
                <tr>
                    <td>
                        <div style="background: #ffffff; padding: 80px; color: #002F49">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background: #ffffff; width:100%;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <!--[if mso | IE]>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td class="" style="vertical-align:top;width:550px;" >
                                                        <![endif]-->
                                                        <div class="mj-column-per-100 outlook-group-fix">
                                                            <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                                <tr>
                                                                    <td align="left">
                                                                        <div style="">

                                                                            <div style="text-align:center;font-size:0px;padding:40px 0px 40px 0px;">
                                                                                <img src="{{ env('APP_URL').'/'.'images/logo-toc.svg' }}" style="max-width:150px; margin: auto;">
                                                                            </div>
                                                                            @yield('content')

                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <!--[if mso | IE]>
                                                    </td>
                                                </tr>
                                            </table>
                                            <![endif]-->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </body>
</html>
