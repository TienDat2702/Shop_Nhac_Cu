<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chúc mừng lên cấp</title>
    <style>
        /* Các kiểu chung cho email */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .content span {
            display: block;
            margin-bottom: 10px;
        }
        .content strong {
            font-weight: bold;
        }
        
        /* Các kiểu riêng cho cấp độ */
        .level-bac {
            color: silver;
        }
        .level-vang {
            color: gold;
        }
        .level-kim-cuong {
            color: #b9f2ff;
        }
    </style>
</head>
<body>
    <div class="email-container">
        @php
            $str = '';
            if ($loyalty->level_name == 'Bạc') {
                $str = 'bac';
            }
            elseif ($loyalty->level_name == 'Vàng') {
                $str = 'vang';
            }
            elseif ($loyalty->level_name == 'Kim cương') {
                $str = 'kim-cuong';
            }
        @endphp
        <div class="header">
            <h2>Chúc mừng bạn đã được lên cấp <span class="{{ 'level-' . $str }}">{{ $loyalty->level_name }}</span></h2>
        </div>
        <div class="content">
            <span>Tổng đơn hàng bạn đã mua đã lớn hơn <strong>{{ number_format($loyalty->order_total_price, 0, '.', ',') }} VND</strong></span>
            <span>Chúng tôi chân thành cảm ơn quý khách hàng <strong>{{ $customer->name }}</strong> đã luôn tin tưởng và đồng hành cùng <strong>TuneNests</strong></span>
            <span>Chúng tôi xin gửi bạn phiếu giảm giá cho mọi đơn hàng giá trị <strong>{{ $loyalty->discount_rate * 100 }}%</strong> cho tổng đơn hàng</span>
            <span>Chúc quý khách có những trải nghiệm mua hàng vui vẻ ^^</span>
        </div>
    </div>
</body>
</html>
