@extends('layouts.app')

@section('title', 'Thanh toán - NEON KINETIC')

@section('content')
<main class="max-w-screen-2xl mx-auto px-6 py-12 lg:px-12">
    <!-- Header Section -->
    <header class="mb-16">
        <h1 class="text-5xl md:text-7xl font-headline font-black tracking-tighter text-white mb-6">
            THANH <span class="text-primary italic">TOÁN</span>
        </h1>
        <p class="text-white/50 font-body text-lg max-w-2xl leading-relaxed">
            Hoàn tất thông tin để nhận được trải nghiệm công nghệ đỉnh cao ngay tại nhà bạn.
        </p>
    </header>

    <form action="{{ route('order.store') }}" method="POST" id="checkoutForm">
        @csrf
        <input type="hidden" name="promo_code" id="appliedPromoCode">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            <!-- Form Area -->
            <div class="lg:col-span-7">
                <div class="bg-[#131313] rounded-2xl p-10 border border-white/5">
                    <h2 class="text-2xl font-headline font-black tracking-tight text-white mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">person</span>
                        THÔNG TIN KHÁCH HÀNG
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] text-white/50 font-label uppercase tracking-[0.2em] block mb-3">Họ và tên *</label>
                            <input type="text" name="customer_name" value="{{ auth()->user()->name ?? old('customer_name') }}" required class="w-full bg-[#0e0e0e] border border-white/10 rounded-xl px-6 py-4 text-white focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all" placeholder="Nguyễn Văn A">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] text-white/50 font-label uppercase tracking-[0.2em] block mb-3">Email *</label>
                                <input type="email" name="customer_email" value="{{ auth()->user()->email ?? old('customer_email') }}" required class="w-full bg-[#0e0e0e] border border-white/10 rounded-xl px-6 py-4 text-white focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all" placeholder="email@example.com">
                            </div>

                            <div>
                                <label class="text-[10px] text-white/50 font-label uppercase tracking-[0.2em] block mb-3">Số điện thoại *</label>
                                <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required class="w-full bg-[#0e0e0e] border border-white/10 rounded-xl px-6 py-4 text-white focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all" placeholder="0912345678">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] text-white/50 font-label uppercase tracking-[0.2em] block mb-3">Địa chỉ giao hàng *</label>
                            <textarea name="customer_address" required rows="4" class="w-full bg-[#0e0e0e] border border-white/10 rounded-xl px-6 py-4 text-white focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('customer_address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-[#131313] rounded-2xl p-10 border border-white/5 mt-8">
                    <h2 class="text-2xl font-headline font-black tracking-tight text-white mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">payment</span>
                        PHƯƠNG THỨC THANH TOÁN
                    </h2>

                    <div class="space-y-4">
                        <label class="flex items-center gap-4 p-6 bg-[#0e0e0e] border-2 border-primary rounded-xl cursor-pointer">
                            <input type="radio" name="payment" value="cod" checked class="w-5 h-5 text-primary">
                            <div class="flex-1">
                                <div class="font-bold text-white">Thanh toán khi nhận hàng (COD)</div>
                                <div class="text-sm text-white/50">Thanh toán bằng tiền mặt khi nhận hàng</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-4 p-6 bg-[#0e0e0e] border border-white/10 rounded-xl cursor-pointer hover:border-primary/50 transition-colors">
                            <input type="radio" name="payment" value="bank" class="w-5 h-5 text-primary">
                            <div class="flex-1">
                                <div class="font-bold text-white">Chuyển khoản ngân hàng</div>
                                <div class="text-sm text-white/50">Chuyển khoản trước khi giao hàng</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-5">
                <div class="bg-[#131313] rounded-2xl p-10 border border-white/5 sticky top-32">
                    <h2 class="text-2xl font-headline font-black tracking-tight text-white mb-8">
                        ĐƠN HÀNG CỦA BẠN
                    </h2>

                    <div class="space-y-6 mb-8">
                        @php $subtotal = 0; @endphp
                        @foreach($cart as $id => $item)
                            @php $subtotal += $item['price'] * $item['quantity']; @endphp
                            <div class="flex gap-4 pb-6 border-b border-white/5">
                                <img src="{{ $item['image'] ?? 'https://via.placeholder.com/100x100?text=Laptop' }}" alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h3 class="font-bold text-white text-sm">{{ $item['name'] }}</h3>
                                    <p class="text-white/50 text-xs mt-1">Số lượng: {{ $item['quantity'] }}</p>
                                    <p class="text-secondary font-bold mt-2">{{ number_format($item['price'] * $item['quantity']) }}₫</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Promo Code -->
                    <div class="mb-6">
                        <label class="text-[10px] text-white/50 font-label uppercase tracking-[0.2em] block mb-3">Mã khuyến mãi</label>
                        <div class="flex gap-2">
                            <input type="text" id="promoCodeInput" class="flex-1 bg-[#0e0e0e] border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:ring-2 focus:ring-primary/30 uppercase" placeholder="Nhập mã...">
                            <button type="button" onclick="applyPromo()" class="px-6 py-3 bg-primary/20 text-primary rounded-lg font-bold hover:bg-primary/30 transition-all">
                                Áp dụng
                            </button>
                        </div>
                        <p id="promoMessage" class="text-xs mt-2 hidden"></p>
                    </div>

                    <div class="space-y-4 mb-8 pb-8 border-b border-white/5">
                        <div class="flex justify-between text-white/70">
                            <span>Tạm tính</span>
                            <span class="font-bold" id="subtotalDisplay">{{ number_format($subtotal) }}₫</span>
                        </div>
                        <div id="discountRow" class="flex justify-between text-green-400 hidden">
                            <span>Giảm giá (<span id="promoCodeDisplay"></span>)</span>
                            <span class="font-bold" id="discountDisplay">0₫</span>
                        </div>
                        <div class="flex justify-between text-white/70">
                            <span>Phí vận chuyển</span>
                            <span class="font-bold" id="shippingDisplay">{{ $subtotal >= 10000000 ? 'MIỄN PHÍ' : number_format(30000) . '₫' }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-8">
                        <span class="text-white font-headline font-bold text-lg">Tổng cộng</span>
                        <span class="text-4xl font-headline font-black text-secondary" id="totalDisplay">{{ number_format($subtotal >= 10000000 ? $subtotal : $subtotal + 30000) }}₫</span>
                    </div>

                    <button type="submit" class="w-full bg-secondary text-white font-headline font-black py-6 rounded-2xl flex items-center justify-center gap-4 transition-all duration-500 hover:scale-[1.03] hover:brightness-110 active:scale-95 shadow-[0_0_30px_rgba(255,117,36,0.4)]">
                        <span class="text-lg">XÁC NHẬN ĐẶT HÀNG</span>
                        <span class="material-symbols-outlined">check_circle</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</main>

<script>
let subtotal = {{ $subtotal }};
let discount = 0;
let shipping = {{ $subtotal >= 10000000 ? 0 : 30000 }};

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(price));
}

function updateTotal() {
    const total = subtotal - discount + shipping;
    document.getElementById('totalDisplay').textContent = formatPrice(total) + '₫';
}

async function applyPromo() {
    const code = document.getElementById('promoCodeInput').value.trim().toUpperCase();
    const messageEl = document.getElementById('promoMessage');
    
    if (!code) {
        messageEl.textContent = 'Vui lòng nhập mã khuyến mãi';
        messageEl.className = 'text-xs mt-2 text-red-400';
        messageEl.classList.remove('hidden');
        return;
    }

    try {
        const response = await fetch('{{ route("order.checkPromo") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code, subtotal })
        });

        const result = await response.json();

        if (result.success) {
            discount = result.discount;
            document.getElementById('appliedPromoCode').value = result.code;
            document.getElementById('promoCodeDisplay').textContent = result.code;
            document.getElementById('discountDisplay').textContent = '-' + formatPrice(discount) + '₫';
            document.getElementById('discountRow').classList.remove('hidden');
            
            messageEl.textContent = result.message;
            messageEl.className = 'text-xs mt-2 text-green-400';
            messageEl.classList.remove('hidden');
            
            updateTotal();
        } else {
            discount = 0;
            document.getElementById('appliedPromoCode').value = '';
            document.getElementById('discountRow').classList.add('hidden');
            
            messageEl.textContent = result.message;
            messageEl.className = 'text-xs mt-2 text-red-400';
            messageEl.classList.remove('hidden');
            
            updateTotal();
        }
    } catch (error) {
        messageEl.textContent = 'Có lỗi xảy ra khi áp dụng mã';
        messageEl.className = 'text-xs mt-2 text-red-400';
        messageEl.classList.remove('hidden');
    }
}
</script>
@endsection
