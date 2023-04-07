<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{{--
    Original:  https://github.com/mailchimp/email-blueprints/blob/master/templates/transactional_basic.html
    Sections:  main, footer (unsubscribe)
    Variables: $nztmailerSubject - added by Message class so title tag can be added automatically
    Link & button colour: #337AB7 (search/replace)
--}}
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!-- Facebook sharing information tags -->
    <meta property="og:title" content="{{ $nztmailerSubject }}" />

    <title>{{ $nztmailerSubject }}</title>
    <style type="text/css">
        /* Client-specific Styles */
        #outlook a{padding:0;}
        body{width:100% !important;} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
        body{-webkit-text-size-adjust:none;}

        /* Reset Styles */
        body{margin:0; padding:0;}
        img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
        table td{border-collapse:collapse;}
        #backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}

        /* Template Styles */

        /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: COMMON PAGE ELEMENTS /\/\/\/\/\/\/\/\/\/\ */

        /* Background color */
        body, #backgroundTable{
            background-color:#FCFCFC;
            font-family: -apple-system, ".SFNSText-Regular", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", "Lucida Grande", sans-serif;
        }

        /* Content border */
        #templateContainer{
            border: 1px solid #F1F1F1;
        }

        h1, .h1 {
            color:#202020;
            display:block;
            font-family: -apple-system, ".SFNSText-Regular", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", "Lucida Grande", sans-serif;
            font-size:34px;
            font-weight:bold;
            line-height:100%;
            margin-top:0;
            margin-right:0;
            margin-bottom:10px;
            margin-left:0;
            text-align:left;
        }

        h2, .h2{
            color:#202020;
            display:block;
            font-family: -apple-system, ".SFNSText-Regular", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", "Lucida Grande", sans-serif;
            font-size:30px;
            font-weight:bold;
            line-height:100%;
            margin-top:0;
            margin-right:0;
            margin-bottom:10px;
            margin-left:0;
            text-align:left;
        }

        h3, .h3{
            color:#202020;
            display:block;
            font-family: -apple-system, ".SFNSText-Regular", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", "Lucida Grande", sans-serif;
            font-size:26px;
            font-weight:bold;
            line-height:100%;
            margin-top:0;
            margin-right:0;
            margin-bottom:10px;
            margin-left:0;
            text-align:left;
        }

        h4, .h4{
            color:#202020;
            display:block;
            font-family: -apple-system, ".SFNSText-Regular", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", "Lucida Grande", sans-serif;
            font-size:22px;
            font-weight:bold;
            line-height:100%;
            margin-top:0;
            margin-right:0;
            margin-bottom:10px;
            margin-left:0;
            text-align:left;
        }

        /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: HEADER /\/\/\/\/\/\/\/\/\/\ */

        #templateHeader{
            background-color:#FFFFFF;
            border-bottom:0;
        }

        .headerContent{
            color:#202020;
            font-family: -apple-system, ".SFNSText-Regular", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", "Lucida Grande", sans-serif;
            font-size:34px;
            font-weight:bold;
            line-height:100%;
            padding: 15px 0 0 0;
            text-align:center;
            vertical-align:middle;
        }

        .headerContent a:link, .headerContent a:visited, /* Yahoo! Mail Override */ .headerContent a .yshortcuts /* Yahoo! Mail Override */{
            color:#337AB7;
            font-weight:normal;
            text-decoration:underline;
        }

        #headerImage{
            height:auto;
            max-width: 100% !important;
        }

        /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: MAIN BODY /\/\/\/\/\/\/\/\/\/\ */

        #templateContainer, .bodyContent{
            background-color:#FFFFFF;
        }

        .bodyContent {
            color:#505050;
            font-family: -apple-system, ".SFNSText-Regular", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", "Lucida Grande", sans-serif;
            font-size:14px;
            line-height:150%;
            text-align:left;
        }

        .bodyContent a, .bodyContent a:link, .bodyContent a:visited, /* Yahoo! Mail Override */ .bodyContent a .yshortcuts /* Yahoo! Mail Override */{
            color:#337AB7;
            font-weight:normal;
            text-decoration:underline;
        }

        /* Used for button and image partials */
        .sep {
            margin: 20px 0;
        }

        .templateButton {
            -moz-border-radius:3px;
            -webkit-border-radius:3px;
            border-radius:3px;
            background-color:#337AB7;
            border:0;
            border-collapse:separate !important;
        }

        .templateButton, .templateButton a, .templateButton a:link, .templateButton a:visited, /* Yahoo! Mail Override */ .templateButton a .yshortcuts /* Yahoo! Mail Override */{
            color:#FFFFFF;
            font-family: -apple-system, ".SFNSText-Regular", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", "Lucida Grande", sans-serif;
            font-size:15px;
            font-weight:bold;
            letter-spacing:-.5px;
            line-height:100%;
            text-align:center;
            text-decoration:none;
        }

        .bodyContent img {
            display:inline;
            height:auto;
        }

        /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: FOOTER /\/\/\/\/\/\/\/\/\/\ */

        #templateFooter {
            background-color:#FFFFFF;
            border-top:0;
        }

        .footerContent {
            color:#707070;
            font-family: -apple-system, ".SFNSText-Regular", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", "Lucida Grande", sans-serif;
            font-size: 14px;
            line-height:125%;
            text-align:left;
        }

        .footerContent a, .footerContent a:link, .footerContent a:visited, /* Yahoo! Mail Override */ .footerContent a .yshortcuts /* Yahoo! Mail Override */{
            color:#337AB7;
            font-family: -apple-system, ".SFNSText-Regular", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", "Lucida Grande", sans-serif;
            font-size: 14px;
            font-weight:normal;
            text-decoration:underline;
        }

        .footerContent img {
            display:inline;
        }

        #utility {
            background-color:#FFFFFF;
            border:0;
            text-align:center;
            font-size: 12px;
            font-family: -apple-system, ".SFNSText-Regular", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", "Lucida Grande", sans-serif;
        }
        #utility a, #utility a:link, #utility a:visited, /* Yahoo! Mail Override */ #utility a .yshortcuts /* Yahoo! Mail Override */ {
            color: #707070;
            font-size: 12px;
            font-family: -apple-system, ".SFNSText-Regular", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", "Lucida Grande", sans-serif;
            font-weight:normal;
            text-decoration:underline;
        }
    </style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
<center>
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable">
        <tr>
            <td align="center" valign="top" style="padding-top:20px;">
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer">
                    <tr>
                        <td align="center" valign="top">
                            <!-- // Begin Template Header \\ -->
                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader">
                                <tr>
                                    <td class="headerContent">
                                    </td>
                                </tr>
                            </table>
                            <!-- // End Template Header \\ -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- // Begin Template Body \\ -->
                            <table border="0" cellpadding="0" cellspacing="0" width="640" id="templateBody">
                                <tr>
                                    <td valign="top">
                                        <!-- // Begin Module: Standard Content \\ -->
                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                            <tr>
                                                <td valign="top" class="bodyContent">
                                                    @yield('main')
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- // End Module: Standard Content \\ -->
                                    </td>
                                </tr>
                            </table>
                            <!-- // End Template Body \\ -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- // Begin Template Footer \\ -->
                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateFooter">
                                <tr>
                                    <td valign="top" class="footerContent">
                                        <!-- // Begin Module: Transactional Footer \\ -->
                                        <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                            <tr>
                                                <td valign="top" style="padding-left:0;padding-right:0;">
                                                    <a href="{{ url('/') }}">Mail App</a><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="middle" id="utility">
                                                    @yield('footer')
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- // End Module: Transactional Footer \\ -->
                                    </td>
                                </tr>
                            </table>
                            <!-- // End Template Footer \\ -->
                        </td>
                    </tr>
                </table>
                <br />
            </td>
        </tr>
    </table>
</center>
</body>
</html>
