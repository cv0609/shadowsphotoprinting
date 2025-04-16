<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Blog Submitted
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body style="  font-family: Open Sans, sans-serif;background-color: #f9f9f9; margin: 0; padding: 0;">
    <div
        style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">

        <table width="100%" cellspacing="0">
            <tr style="padding: 20px; text-align: center; color: #ffffff;background:black;">
                <td style="padding: 10px;">
                    <a href="{{env('SITE_DOMAIN')}}" target="_blank"> <img src="{{ env('SITE_DOMAIN') }}assets/images/logo.png" alt="logo"
                            style="max-width: 150px; width:100%;">
                    </a>
                </td>
            </tr>
            <tr style="background-image: linear-gradient(135deg, #16a085 0%, #3b8879 100%); color: #ffffff;">
                <td
                    style="padding:36px 48px;display:block;text-align:center;padding-top:15px;padding-bottom:15px;padding-left:48px;padding-right:48px">
                    <h1 style="margin: 0; font-size: 30px;">📝 New Blog Submitted for Review</h1>
                </td>
            </tr>

            <table style=" margin-top: 20px; padding: 0 30px;"  width="100%">
                <tr>
                    <td>
                        <p>
                            Hi Admin
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin: 5px 0;">
                            A new blog has been submitted and is awaiting your review.
                        </p>
                        <h3>📄 Blog Details:</h3>
                        <ul>
                            <li><strong>Title:</strong> {{ $blog->title }}</li>
                            <li><strong>Author:</strong> {{ $blog->user->first_name ?? $blog->user->username }}</li>
                            <li><strong>Submitted on:</strong> {{ $blog->created_at->format('F j, Y h:i A') }}</li>
                        </ul>
                    
                        <p><strong>Excerpt:</strong></p>
                        <blockquote style="border-left: 4px solid #ccc; margin: 10px 0; padding-left: 10px;">
                            {{ Str::limit(strip_tags($blog->description), 200) }}
                        </blockquote>
                    
                        <p>
                            👉 <a href="{{ url('/admin/blogs/'.$blog->slug) }}" style="color: #1a73e8;">Click here to review and approve the blog</a>
                        </p>
                    </td>
                  
                </tr>
                <table style="padding: 0 20px" cellspacing="12" width="100%">
                    <tbody>
                  
                       <tr>
                          <td colspan="2">
                             <p
                                style="
                                font-size: 14px;
                                color: #636363;
                                margin-bottom: 16px;
                                "
                                >
                                Thanks for using
                                <a
                                   style="color: #15c"
                                   href="https://shadowsphotoprinting.com.au/"
                                   target="_blank"
                                   data-saferedirecturl="https://www.google.com/url?q=https://shadowsphotoprinting.com.au/&amp;source=gmail&amp;ust=1725689988542000&amp;usg=AOvVaw1tixJHsI_GjcQGBsAq6HgD"
                                   >shadowsphotoprinting.com.au!</a
                                   >
                             </p>
                          </td>
                       </tr>
                       <tr>
                          <td
                             colspan="2"
                             style="
                             color: #555;
                             font-size: 12px;
                             text-align: center;
                             padding-bottom: 20px;
                             padding-left: 48px;
                             padding-top: 20px;
                             padding-right: 48px;
                             "
                             >
                             Shadows Photo Printing
                          </td>
                       </tr>
                    </tbody>
                 </table>


            </table>


    

       

        </table>


    </div>

</body>

</html>