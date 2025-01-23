<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
        h2 {
            color: #333;
        }
        p {
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> {{ $mailData['name'] }}</p>
        <p><strong>Email:</strong> {{ $mailData['email'] }}</p>
        <p><strong>Subject:</strong> {{ $smailData['ubject'] }}</p>
        <p><strong>Message:</strong></p>
        <p>{{ $mailData['message'] }}</p>
    </div>
</body>
</html>
