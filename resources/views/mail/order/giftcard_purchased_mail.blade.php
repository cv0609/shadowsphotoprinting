<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gift Card</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Serif:wght@400;700&display=swap" rel="stylesheet">
</head>

<body style="font-family: 'Roboto Serif', serif; background-color: #f9f9f9; margin: 0; padding: 0;">
    <div style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">

        {{-- Header with logo --}}
        <table width="100%" cellspacing="0">
            <tr style="background-color: #000; text-align: center;">
                <td style="padding: 20px;">
                    <a href="{{ env('SITE_DOMAIN') }}" target="_blank">
                        <img src="{{ env('SITE_DOMAIN') }}assets/images/logo.png" alt="Shadows Photo Printing" style="max-width: 300px; width: 100%;">
                    </a>
                </td>
            </tr>
            <tr style="background-color: #16a085; color: #ffffff;">
                <td style="padding: 20px; text-align: center;">
                    <h1 style="margin: 0;">You've Received a Gift Card!</h1>
                </td>
            </tr>
        </table>

        {{-- Gift Card Image --}}
        <table width="100%" cellspacing="0">
            <tr>
                <td style="padding: 30px; text-align: center;">
                    @if(!empty($order['image']))
                        <img src="{{ asset(str_replace(' ', '%20', $order['image'])) }}" alt="Gift Card" style="max-width: 100%; height: auto; border-radius: 8px;">
                    @else
                        <p>No gift card image available.</p>
                    @endif
                </td>
            </tr>
        </table>

        {{-- Gift Card Message --}}
        @if(!empty($order['message']))
        <table width="100%" cellspacing="0">
            <tr>
                <td style="padding: 0 30px 30px 30px; text-align: center;">
                    <h3 style="color: #333;">Personal Message</h3>
                    <blockquote style="font-style: italic; font-size: 16px; color: #555; margin: 10px auto; border-left: 4px solid #16a085; padding-left: 15px;">
                        {{ $order['message'] }}
                    </blockquote>
                </td>
            </tr>
        </table>
        @endif

        {{-- Footer --}}
        <table width="100%" cellspacing="0">
            <tr>
                <td style="text-align: center; padding: 30px; font-size: 14px; color: #636363;">
                    Thank you for choosing <a href="{{ env('SITE_DOMAIN') }}" style="color: #15c;">shadowsphotoprinting.com.au</a>!
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 12px; color: #aaa; padding-bottom: 40px;">
                    Shadows Photo Printing
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
