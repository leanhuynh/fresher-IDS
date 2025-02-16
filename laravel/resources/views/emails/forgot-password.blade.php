<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #333;">Yêu cầu đặt lại mật khẩu</h2>
        <p>Xin chào,</p>
        <p>Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
        <p style="text-align: center;">
            <a href="{{ $data['resetLink'] }}" style="background-color: #007bff; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block; font-size: 16px;">
                Đặt lại mật khẩu
            </a>
        </p>
        <p>Nếu bạn không yêu cầu đặt lại mật khẩu, không cần làm gì thêm.</p>
        <p>Liên kết này sẽ hết hạn sau {{ $data['expiration'] }} phút.</p>
        <hr style="margin: 20px 0;">
        <p style="font-size: 12px; color: #888;">Nếu bạn gặp vấn đề với nút "Đặt lại mật khẩu", sao chép và dán liên kết sau vào trình duyệt của bạn:</p>
        <p style="font-size: 12px; color: #888;">{{ $data['resetLink'] }}</p>
        <p style="text-align: center; font-size: 12px; color: #888;">Cảm ơn, <br> Đội ngũ {{ config('app.name') }}</p>
    </div>
</body>
</html>