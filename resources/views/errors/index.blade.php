<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    @notifyCss
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #333333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            padding: 20px;
        }
        h1 {
            font-size: 24px;
            color: #6e6e6e9d;
        }
        h2 {
            font-size: 24px;
            color: #e74c3c;
        }
        p {
            font-size: 16px;
            margin: 20px 0;
        }
        .button {
            display: flex;
            align-items: center;
            font-size: 16px;
            color: #000000;
            text-decoration: none;
            border-radius: 5px;
            padding: 10px 20px;
            background-color: #ffffff;
        }
        .button:hover {
            background-color: #f0f0f0;
        }
        .arrow {
            font-size: 18px;
            margin-right: 8px;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>
<body>
    <div class="back-button">
        <a href="{{ route('dashboard') }}" class="button">
            <span class="arrow">&#8592;</span> Back
        </a>
    </div>
    <div class="container">
        <h1>ERROR : 403</h1>
        <h2>Access Denied</h2>
        <p>You do not have permission to access this page.</p>
    </div>
    <x-notify::notify />
    @notifyJs
</body>
</html>
