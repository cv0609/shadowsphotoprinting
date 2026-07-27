<!DOCTYPE html>
<html>
<head>
    <title>404 Not Found</title>
    <style>
       *{
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        }
    .page-not-found {
        background: url(https://avologypro.com/Frontend/shadowsphotoprinting/images/cart-page.jpg) rgba(0 0 0/30%);
        height: 100vh;
        color: #fff;
        display: grid;
        place-content: center;
        place-items: center;
        background-blend-mode: multiply;
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
    }

    .page-not-found h1 {
        color: #16a085;
        font-size: min(6vw, 100px);
        margin: 0 0 20px;
        text-shadow: 1px 1px #ffc205;
    }

    .page-not-found p {
        font-size: 20px;
        font-weight: 600;
        color: #ffc205;
    }
    .home-button {
        background-color: #16a085;
        color: #fff;
        padding: 15px 30px;
        font-size: 18px;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease-in-out;
        cursor: pointer;
        margin-top: 16px;
    }

    .home-button:hover {
        background-color: #149174;
    }

    </style>
</head>
<body>
    <div class="page-not-found">
        <h1>404 Not Found</h1>
        <p>The page you requested could not be found.</p>
        <a href="/" class="home-button">Go to Home Page</a>
    </div>
</body>
</html>
