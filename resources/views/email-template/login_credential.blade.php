<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $info['title'] }}</title>
    <style type="text/css">
        body {
            background: #eee;
        }
    </style>
</head>

<body>
    <table width="500" cellspacing="0" cellpadding="0" style="width:100%;max-width:600px;background-color:#ffffff;margin:0 auto;margin-bottom: 20px;">
        <tbody>
            <tr>
                <td style="text-align: center;font-size: 30px;">{{env('APP_NAME')}}</td>
            </tr>
            <tr>
                <td style="padding-top:0.5em"></td>
            </tr>
            <tr>
                <td style="color:#3f4652;line-height:23px;padding-left:28px;padding-right:28px;font-size:15px;font-family:'Open Sans',sans-serif;font-weight:100;">
                    <font face="'Open Sans', sans-serif">
                        Dear {!! $info['name'] !!}, <br>
                        Your Account has been created. Please find below login credential to login. You may access to the admin panel through the below link.
                    </font>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <font face="'Open Sans', sans-serif">
                        <table>
                            <tr>
                                <td style="width:100%; padding: 0 20px;" valign="top">
                                    <font face="Arial, Helvetica, sans-serif" size="4" color="#57697e" style="font-size: 14px;">
                                        <table>

                                            <tr>
                                                <td style="color:#3f4652;padding-left:28px;padding-right:28px;font-size:28px;font-family:'Open Sans',Arial,sans-serif;font-weight:100;padding-top: 30px;">
                                                    <font face="'Open Sans', sans-serif">
                                                        <h3 style="font-size: 18px; margin: 0; font-weight: 500; padding-bottom: 10px;">Login Credential</h3>
                                                    </font>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Login ID</td>
                                                <td>{{ $info['name'] ?? $info['email'] }}</td>
                                            </tr>

                                            <tr>
                                                <td>Password </td>
                                                <td>{!! $info['password'] !!}</td>
                                            </tr>

                                            <tr>
                                                <td>Login In To <a href="{{ $info['url'] }}">Here</a></td>
                                            </tr>



                                        </table>
                                    </font>
                                </td>
                            </tr>
                        </table>
                    </font>
                </td>
            </tr>
            <tr>
                <td style="color:#3f4652;line-height:23px;padding-left:28px;padding-right:28px;font-size:15px;font-family:'Open Sans',sans-serif;font-weight:100;">
                    <font face="'Open Sans', sans-serif">
                        If you having trouble clicking the "Here" button , copy and paste the URL below into your web browser : {{$info['url']}}
                    </font>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
