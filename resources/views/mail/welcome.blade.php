<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Welcome to {{ env('APP_NAME') }}!</title>
    </head>

    <body>
        <h1>Welcome to {{ env('APP_NAME') }}!</h1>
        <p>Thank you for signing up. We're excited to have you join our community.</p>
        <p>Please verify your email address to get started.</p>
        <p>If you did not create an account, no further action is required.</p>
        <br>
        <p>Best regards,<br>
            The {{ env('APP_NAME') }} Team</p>
    </body>

</html>
