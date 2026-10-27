@if(!empty($order['is_christmas']))
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="x-apple-disable-message-reformatting">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ !empty($order['is_christmas']) ? 'You’ve received a Christmas gift!' : 'You’ve received an eGift Card!' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Playfair+Display:ital,wght@0,600;0,700&display=swap" rel="stylesheet">

  <style type="text/css">
    body, table, td, p, a, li, blockquote {
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      -webkit-font-smoothing: antialiased !important;
      -moz-osx-font-smoothing: grayscale !important;
      text-rendering: optimizeLegibility !important;
    }
    table, td {
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
      border-collapse: collapse;
    }
    img {
      -ms-interpolation-mode: bicubic;
      border: 0;
      height: auto;
      line-height: 100%;
      outline: none;
      text-decoration: none;
    }
    body {
      margin: 0 !important;
      padding: 0 !important;
      width: 100% !important;
      background-color: #F5EFE6;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    @media only screen and (max-width: 620px) {
      .email-container {
        width: 100% !important;
        margin: auto !important;
        border-radius: 0px !important;
      }
      .card-padding {
        padding: 28px 18px !important;
      }
      .heading-title {
        font-size: 26px !important;
        line-height: 32px !important;
      }
      .gift-value-title {
        font-size: 28px !important;
        line-height: 34px !important;
      }
      .coupon-code {
        font-size: 19px !important;
        letter-spacing: 2px !important;
      }
      .message-box-td {
        padding: 20px 18px !important;
      }
    }
  </style>
</head>
<body style="margin:0; padding:0; background-color:#F5EFE6; font-family:'Lora', Georgia, 'Times New Roman', serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;">

  <!-- Outer background container -->
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F5EFE6; width:100%; margin:0; padding:36px 10px;">
    <tr>
      <td align="center" style="vertical-align:top;">

        <!-- Main Email Card (Clean White matching mockup) -->
        <table role="presentation" class="email-container" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; width:100%; background-color:#FFFFFF; border-radius:18px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.04);">
          
          <!-- Header matching other order emails -->
          @php
            $siteDomain = env('SITE_DOMAIN') ?: url('/');
            $logoUrl = !empty(env('SITE_DOMAIN')) ? rtrim(env('SITE_DOMAIN'), '/') . '/assets/images/logo.png' : asset('assets/images/logo.png');
          @endphp
          <tr style="background-color: #000000; text-align: center; background: #000000;">
            <td style="padding: 20px 10px; text-align: center; background-color: #000000; border-top-left-radius: 18px; border-top-right-radius: 18px;">
              <a href="{{ $siteDomain }}" target="_blank" style="text-decoration: none; display: inline-block;">
                <img src="{{ $logoUrl }}" alt="Shadows Photo Printing" style="max-width: 300px; width: 100%; height: auto; display: block; margin: 0 auto; border: 0;" />
              </a>
            </td>
          </tr>

          <!-- Inner content padding -->
          <tr>
            <td class="card-padding" style="padding:36px 38px 40px 38px; text-align:center;">

              <!-- Brand Subtitle -->
              <p style="margin:0 0 10px 0; font-family:'Lora', Georgia, serif; font-size:15.5px; font-weight:400; color:#243927; letter-spacing:0.4px; text-align:center;">
                Shadows Affordable Memories
              </p>

              <!-- Star Sparkle Divider -->
              <table role="presentation" align="center" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 18px auto;">
                <tr>
                  <td align="center" style="padding:0; line-height:0; font-size:0; text-align:center;">
                    <img src="{{ asset('assets/images/star-divider.png') }}" width="210" height="22" alt="✦" style="display:block; width:210px; max-width:210px; height:auto; margin:0 auto;" />
                  </td>
                </tr>
              </table>

              <!-- Main Heading -->
              <h1 class="heading-title" style="margin:0 0 26px 0; font-family:'Lora', Georgia, 'Times New Roman', serif; font-size:33px; line-height:40px; font-weight:600; color:#183620; text-align:center;">
                @if(!empty($order['is_christmas']))
                  You’ve received a Christmas gift!
                @else
                  You’ve received an eGift Card!
                @endif
              </h1>

              <!-- Gift Card Image -->
              @php
                $rawCardImage = !empty($order['image']) ? $order['image'] : 'assets/images/christmas-egift-card-email.png';
                $rawCardImage = preg_replace('#(assets/admin/uploads/categories/)+#', 'assets/admin/uploads/categories/', (string)$rawCardImage);
                if (!preg_match('#^(https?://|assets/)#i', $rawCardImage)) {
                    $rawCardImage = 'assets/admin/uploads/categories/' . ltrim($rawCardImage, '/');
                }
                $cardImageUrl = preg_match('/^https?:\/\//i', $rawCardImage) ? $rawCardImage : asset(str_replace(' ', '%20', ltrim($rawCardImage, '/')));
              @endphp
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 28px 0;">
                <tr>
                  <td align="center">
                    <img src="{{ $cardImageUrl }}" alt="eGift Card" style="display:block; width:100%; max-width:524px; height:auto; border-radius:14px; box-shadow:0 3px 12px rgba(0,0,0,0.05);" />
                  </td>
                </tr>
              </table>

              <!-- Sender Details -->
              @if(!empty($order['from']))
              <p style="margin:0 0 6px 0; font-family:'Lora', Georgia, serif; font-size:18px; line-height:24px; font-weight:400; color:#283D2B; text-align:center;">
                A gift from {{ $order['from'] }}
              </p>
              @endif

              <!-- Gift Card Value -->
              @php
                $cardAmount = isset($order['amount']) && is_numeric($order['amount']) ? number_format((float)$order['amount'], 2) : '50.00';
              @endphp
              <h2 class="gift-value-title" style="margin:0 0 26px 0; font-family:'Cormorant Garamond', Georgia, 'Times New Roman', serif; font-size:42px; line-height:48px; font-weight:700; font-variant-numeric:lining-nums; -webkit-font-feature-settings:'lnum' 1; font-feature-settings:'lnum' 1; color:#15381E; text-align:center;">
                Your eGift Card: ${{ $cardAmount }}
              </h2>

              <!-- Personal Message Box -->
              @if(!empty($order['message']))
              @php
                $rawMsg = $order['message'] ?? '';
                $rawLines = array_values(array_filter(array_map('trim', explode("\n", str_replace(["\r\n", "\r"], "\n", $rawMsg))), function($l) { return $l !== ''; }));
                $hasMultipleLines = count($rawLines) > 1;
              @endphp
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 28px auto; max-width:524px; background-color:#FAF4EA; border-radius:14px;">
                <tr>
                  <td class="message-box-td" style="padding:26px 34px 26px 34px; text-align:center;">

                    <!-- Decorative Holly Header (Centered icon + horizontal accent lines) -->
                    <table role="presentation" align="center" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 16px auto;">
                      <tr>
                        <td align="center" style="padding:0; line-height:0; font-size:0; text-align:center;">
                          <img src="{{ asset('assets/images/holly-divider.png') }}" width="200" height="42" alt="🌿" style="display:block; width:200px; max-width:200px; height:auto; margin:0 auto;" />
                        </td>
                      </tr>
                    </table>

                    <!-- Message Text Layout formatted like mockup -->
                    @if(!empty($rawLines))
                      @if($hasMultipleLines)
                        <!-- Greeting / Title Line -->
                        <p style="margin:0 0 8px 0; font-family:'Lora', Georgia, serif; font-size:18px; line-height:25px; font-weight:500; color:#243927; text-align:center;">
                          {{ $rawLines[0] }}
                        </p>

                        @if(count($rawLines) == 2)
                          <!-- Second Line -->
                          <p style="margin:0; font-family:'Lora', Georgia, serif; font-size:16px; line-height:25px; font-weight:400; color:#2A3F2C; text-align:center;">
                            {{ $rawLines[1] }}
                          </p>
                        @else
                          <!-- Body Lines -->
                          <p style="margin:0 0 10px 0; font-family:'Lora', Georgia, serif; font-size:16px; line-height:25px; font-weight:400; color:#2A3F2C; text-align:center;">
                            {!! nl2br(e(implode("\n", array_slice($rawLines, 1, -1)))) !!}
                          </p>
                          <!-- Sign-off Line -->
                          <p style="margin:0; font-family:'Lora', Georgia, serif; font-size:16px; line-height:24px; font-weight:400; color:#2A3F2C; text-align:center;">
                            {{ end($rawLines) }}
                          </p>
                        @endif
                      @else
                        <!-- Single-line Message -->
                        <p style="margin:0; font-family:'Lora', Georgia, serif; font-size:16.5px; line-height:26px; font-weight:400; color:#2A3F2C; text-align:center;">
                          {{ $rawLines[0] }}
                        </p>
                      @endif
                    @endif

                  </td>
                </tr>
              </table>
              @endif

              <!-- Gift Card Code Section -->
              <p style="margin:0 0 12px 0; font-family:'Lora', Georgia, serif; font-size:17px; font-weight:400; color:#183620; text-align:center;">
                Your gift card code
              </p>

              <!-- Copyable Code Box -->
              <table role="presentation" align="center" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 22px auto;">
                <tr>
                  <td style="background-color:#FAF4EA; border-radius:8px; padding:12px 42px; text-align:center;">
                    <span class="coupon-code" style="font-family:'Lora', Georgia, 'Times New Roman', serif; font-size:24px; font-weight:600; letter-spacing:4px; color:#183620; user-select:all; display:inline-block;">
                      {{ $order['code'] ?? 'SAMPLE-ONLY' }}
                    </span>
                  </td>
                </tr>
              </table>

              <!-- Call To Action Button -->
              @php
                $shopUrl = url('/shop');
              @endphp
              <table role="presentation" align="center" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 14px auto;">
                <tr>
                  <td align="center">
                    <a href="{{ $shopUrl }}" target="_blank" style="background-color:#24452C; color:#FFFFFF; font-family:'Lora', Georgia, serif; font-size:18px; font-weight:500; line-height:24px; padding:14px 44px; border-radius:6px; text-decoration:none; display:inline-block; letter-spacing:0.3px; box-shadow:0 3px 10px rgba(36,69,44,0.2);">
                      Use Your eGift Card
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Redemption Instructions -->
              <p style="margin:0 0 28px 0; font-family:'Lora', Georgia, serif; font-size:15.5px; line-height:22px; color:#2A3F2C; font-weight:400; text-align:center;">
                Enter your gift card code at checkout.
              </p>

              <!-- Star Sparkle Divider -->
              <table role="presentation" align="center" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 18px auto;">
                <tr>
                  <td align="center" style="padding:0; line-height:0; font-size:0; text-align:center;">
                    <img src="{{ asset('assets/images/star-divider.png') }}" width="210" height="22" alt="✦" style="display:block; width:210px; max-width:210px; height:auto; margin:0 auto;" />
                  </td>
                </tr>
              </table>

              <!-- Family Sign-off -->
              <p style="margin:0 0 18px 0; font-family:'Lora', Georgia, serif; font-size:16px; line-height:24px; color:#183620; font-weight:400; text-align:center;">
                From our family to yours.<br>
                The Shadows Family
              </p>

              <!-- Contact Details -->
              <p style="margin:0; font-family:'Lora', Georgia, serif; font-size:12px; line-height:19px; color:#7E8A7F; text-align:center;">
                Shadows Photo Printing &bull; 21 Boundary Street, Glenreagh NSW 2450<br>
                Phone: <a href="tel:0266029424" style="color:#183620; text-decoration:none; font-weight:600;">02 6602 9424</a> &bull; <a href="{{ url('/') }}" style="color:#183620; text-decoration:none; font-weight:600;">shadowsphotoprinting.com.au</a>
              </p>

            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>

</body>
</html>
@else
@include('mail.order.legacy_giftcardmail')
@endif