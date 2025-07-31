<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your 10% Discount Coupon - August 2025 Promotion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&display=swap"
        rel="stylesheet">
</head>

<body style="font-family: Roboto Serif, serif; background-color: #f9f9f9; margin: 0; padding: 0;">
    <div
        style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">

        <!-- Header Section -->
        <div style="background-color: #000; color: #ffd700; text-align: center; padding: 30px 20px;">
            <!-- Logo -->
            <div style="margin-bottom: 20px;">
                <a href="{{env('SITE_DOMAIN')}}" target="_blank">
                    <img src="{{ env('SITE_DOMAIN') }}assets/images/logo.png" alt="Shadows Photo Printing Logo" 
                         style="max-width: 150px; width: 100%; height: auto;">
                </a>
            </div>
            <h1 style="margin: 0; font-size: 28px; font-weight: bold;">🎉 August 2025 Special Promotion! 🎉</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px; color: #ffffff;">Welcome to Shadows Photo Printing</p>
        </div>

        <!-- Main Content -->
        <div style="padding: 40px 30px;">
            <h2 style="color: #333; margin-bottom: 20px; font-size: 24px;">Hello!</h2>
            
            <p style="color: #666; font-size: 16px; line-height: 1.6; margin-bottom: 25px;">
                Thank you for participating in our August 2025 promotion! As a first-time customer, we're excited to offer you a special discount.
            </p>

            <!-- Coupon Code Box -->
            <div style="background-color: #20c997; color: white; padding: 25px; text-align: center; border-radius: 10px; margin: 30px 0; box-shadow: 0 4px 15px rgba(32, 201, 151, 0.3);">
                <h3 style="margin: 0 0 15px 0; font-size: 18px; font-weight: normal;">Your Exclusive Coupon Code</h3>
                <div style="font-size: 32px; font-weight: bold; letter-spacing: 3px; background: rgba(255,255,255,0.2); padding: 15px; border-radius: 8px; margin: 10px 0;">
                    {{ $data['coupon_code'] }}
                </div>
                <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">10% OFF Your First Order</p>
            </div>

            <!-- How to Use Section -->
            <div style="background-color: #f8f9fa; padding: 25px; border-radius: 10px; margin: 25px 0;">
                <h3 style="color: #333; margin-bottom: 15px; font-size: 20px;">📋 How to Use Your Coupon:</h3>
                <ol style="color: #555; font-size: 16px; line-height: 1.8; margin: 0; padding-left: 20px;">
                    <li style="margin-bottom: 8px;">Browse our products at <a href="{{ url('/shop') }}" style="color: #20c997; text-decoration: none; font-weight: bold;">shadowsphotoprinting.com</a></li>
                    <li style="margin-bottom: 8px;">Add items to your cart</li>
                    <li style="margin-bottom: 8px;">At checkout, enter your coupon code: <strong style="color: #20c997;">{{ $data['coupon_code'] }}</strong></li>
                    <li style="margin-bottom: 8px;">Enjoy your 10% discount!</li>
                </ol>
            </div>

            <!-- Call to Action Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/shop') }}" 
                   style="display: inline-block; background-color: #20c997; color: white; padding: 15px 35px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 15px rgba(32, 201, 151, 0.3);">
                    🛒 Start Shopping Now
                </a>
            </div>

            <!-- Terms & Conditions -->
            <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 8px; margin: 25px 0;">
                <h4 style="color: #856404; margin-bottom: 15px; font-size: 18px;">📜 Terms & Conditions:</h4>
                <ul style="color: #856404; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 20px;">
                    <li style="margin-bottom: 6px;">Valid only for first-time customers</li>
                    <li style="margin-bottom: 6px;">10% discount on your entire order</li>
                    <li style="margin-bottom: 6px;">Valid from August 1st to August 31st, 2025</li>
                    <li style="margin-bottom: 6px;">One use per customer</li>
                    <li style="margin-bottom: 6px;">Cannot be combined with other offers</li>
                    <li style="margin-bottom: 6px;">Valid on all products except gift cards</li>
                </ul>
            </div>

            <p style="color: #666; font-size: 16px; line-height: 1.6; margin-bottom: 15px;">
                If you have any questions, please don't hesitate to contact our customer support team.
            </p>
            
            <p style="color: #333; font-size: 16px; margin-bottom: 5px;">Happy shopping!</p>
            <p style="color: #20c997; font-weight: bold; font-size: 16px; margin: 0;">The Shadows Photo Printing Team</p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8f9fa; padding: 25px 30px; text-align: center; border-top: 1px solid #e9ecef;">
            <p style="color: #6c757d; font-size: 12px; margin: 0 0 5px 0;">© 2025 Shadows Photo Printing. All rights reserved.</p>
        </div>

    </div>
</body>

</html>

