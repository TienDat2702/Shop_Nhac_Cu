<div style="max-width: 600px; margin: 20px auto; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1); background-color: #ffffff;">
    
    <!-- Header -->
    <div style="background-color: #4CAF50; padding: 20px; text-align: center; color: #ffffff;">
        <h2 style="margin: 0;">Yêu Cầu Đặt Lại Mật Khẩu</h2>
    </div>

    <!-- Body Content -->
    <div style="padding: 20px; color: #333;">
        <h3 style="color: #333;">Xin chào, {{ $recipient->name }}!</h3>
        <p style="font-size: 16px; line-height: 1.5;">
            Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Nhấp vào nút bên dưới để tạo mật khẩu mới. Nếu bạn không thực hiện yêu cầu này, hãy bỏ qua email này.
        </p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url($resetLink) }}" style="background-color: #4CAF50; color: #ffffff; padding: 12px 20px; border-radius: 5px; text-decoration: none; font-size: 16px;">
                Đặt Lại Mật Khẩu
            </a>
        </div>
        <p style="font-size: 14px; color: #777;">
            Nếu nút trên không hoạt động, hãy sao chép và dán liên kết sau đây vào trình duyệt của bạn:
        </p>
        <p style="word-break: break-all; font-size: 14px; color: #4CAF50;">
            <a href="{{ url($resetLink) }}" style="color: #4CAF50; text-decoration: none;">
                {{ url($resetLink) }}
            </a>
        </p>
        <p style="font-size: 14px; color: #777; margin-top: 20px;">
            Cảm ơn bạn,<br>
            Đội ngũ hỗ trợ của TuneNest
        </p>
    </div>

    <!-- Footer -->
    <div style="background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #777;">
        &copy; 2024 TuneNest. Mọi quyền được bảo lưu.
    </div>
</div>
