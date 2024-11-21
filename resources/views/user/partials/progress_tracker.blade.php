@if ($order->status == 'Chưa xác nhận')
    <p style="color: red; font-size: 16px" class="text-progress-tracker">Hãy kiểm tra lại mail để xác nhận đơn hàng, sau đó đơn hàng mới được xử lý</p>
@endif
    <div class="progress-tracker">
        @foreach ($steps as $step)
            <div class="progress-step {{ $step['class'] }}" aria-label="{{ $step['text'] }}" tabindex="0">
                <div class="progress-step__icon {{ $step['class'] }}">
                    <div class="circle">{{ $loop->index + 1 }}</div>
                </div>
                <div class="progress-step__text">{{ $step['text'] }}</div>
            </div>
        @endforeach

        <div class="progress-line">
            <div class="progress-line__background"></div>
            <div class="progress-line__foreground" style="width: {{ $progressWidth }}%"></div>
        </div>
    </div>
