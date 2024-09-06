<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get a Quote
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&display=swap"
        rel="stylesheet">
</head>

<body style="font-family: Roboto Serif, serif; background-color: #f9f9f9; margin: 0; padding: 0;">
    <div
        style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">

        <table width="100%" cellspacing="0">
            <tr style="background-color: #000; padding: 20px; text-align: center; color: #ffffff;">
                <td style="padding: 10px;">
                    <a href="{{env('SITE_DOMAIN')}}" target="_blank">
                        <img src="{{ env('SITE_DOMAIN') }}{{asset('assets/images/logo.png')}}" alt="Shadows Photo Printing" style="max-width: 300px; width:100%;">
                    </a>
                </td>
            </tr>
            <tr style="background-color: #16a085; color: #ffffff;">
                <td
                    style="padding:36px 48px;display:block;text-align:center;padding-top:15px;padding-bottom:15px;padding-left:48px;padding-right:48px">
                    <h1 style="margin: 0;">Get a Quote</h1>
                </td>

            <tr>
                <td style="padding: 20px;">
                    <h2 style="color: #16a085; margin-top: 0;">User Details</h2>
                    <p><strong>First Name:</strong> {{$quote['name'] ?? ''}}</p>
                    <p><strong>Last Name:</strong> {{$quote['last_name'] ?? ''}}</p>
                    <p><strong>Email:</strong> {{$quote['email'] ?? ''}}</p>
                    <p><strong>Phone Number:</strong> {{$quote['phone_number'] ?? ''}}</p>

                    <h2 style="color: #16a085;">Requested Size</h2>
                    <p><strong>Requested Size to be printed:</strong> {{$quote['requested'] ?? ''}}</p>

                    <h2 style="color: #16a085;">Additional Query</h2>
                    <p><strong>Any Other Query:</strong> {{$quote['message'] ?? ''}}</p>
                </td>
            </tr>
            <tr>
                <td style="text-align: center; padding: 10px; background-color: #f4f4f4; border-radius: 0 0 8px 8px;">
                    <p style="margin: 0;">Thank you for your submission!</p>
                </td>
            </tr>
        </table>


    </div>
</body>

</html>