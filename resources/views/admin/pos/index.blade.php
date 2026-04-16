<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    @include('components.admin.head')
    <style>
        body {
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex">
    @include('components.admin.sidebar')

    <!-- Main Content Canvas -->
    <main class="ml-64 flex w-full h-[calc(100vh)] bg-background">
        <!-- Left: Product Grid Section -->
        <section class="flex-1 p-8 overflow-y-auto">
            <div class="flex items-center justify-between mb-8">
                <div class="flex gap-3 overflow-x-auto pb-2">
                    <button onclick="filterByCategory('all')" class="category-btn px-6 py-2 rounded-lg bg-primary/10 text-primary border border-primary/20 headline text-sm font-bold whitespace-nowrap" data-category="all">
                        Tất cả
                    </button>
                    @foreach($categories ?? [] as $category)
                    <button onclick="filterByCategory({{ $category->id }})" class="category-btn px-6 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-neutral-300 hover:text-white transition-colors headline text-sm font-medium whitespace-nowrap" data-category="{{ $category->id }}">
                        {{ $category->name }} <span class="text-xs opacity-70">({{ $category->laptops_count }})</span>
                    </button>
                    @endforeach
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <input id="searchProduct" type="text" placeholder="Tìm sản phẩm..." class="bg-surface-container-low border border-outline-variant/10 rounded-lg text-sm text-white placeholder-neutral-400 focus:ring-1 focus:ring-primary py-2 pl-10 pr-4 w-64" onkeyup="searchProducts()">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-neutral-400 text-sm">search</span>
                    </div>
                    <div class="text-neutral-300 text-sm font-medium">
                        <span class="text-primary font-bold" id="productCount">{{ count($products ?? []) }}</span> Sản phẩm
                    </div>
                </div>
            </div>

            <!-- Bento-style Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5" id="productsGrid">
                @foreach($products ?? [] as $product)
                <div class="group relative bg-white rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 shadow-md border border-gray-100 product-card flex flex-col" data-name="{{ strtolower($product->name) }}" data-category="{{ $product->category_id }}">
                    <div class="p-4 flex flex-col flex-1">
                        <div class="h-40 mb-3 relative overflow-hidden rounded-lg bg-gray-50 flex items-center justify-center">
                            @if($product->image)
                                @if(str_starts_with($product->image, 'http'))
                                <img class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500" src="{{ $product->image }}" alt="{{ $product->name }}"/>
                                @else
                                <img class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"/>
                                @endif
                            @else
                            <span class="material-symbols-outlined text-5xl text-gray-300">laptop</span>
                            @endif
                            @if($product->is_new ?? false)
                            <div class="absolute top-2 right-2 px-2 py-0.5 bg-cyan-400 text-white rounded text-[9px] font-bold uppercase tracking-wider shadow-md">Mới</div>
                            @endif
                        </div>
                        <div class="flex-1 flex flex-col">
                            <h3 class="font-bold text-sm text-gray-900 line-clamp-2 mb-2 leading-tight">{{ $product->name }}</h3>
                            <p class="text-gray-500 text-xs line-clamp-2 mb-3 flex-1">{{ $product->description ?? 'Sản phẩm chất lượng cao' }}</p>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="text-lg font-black text-gray-900">{{ number_format($product->price) }}₫</span>
                                <button class="w-8 h-8 rounded-lg bg-cyan-400 flex items-center justify-center text-white hover:bg-cyan-500 transition-all shadow-md active:scale-95" onclick='addToCart(@json($product))'>
                                    <span class="material-symbols-outlined text-[18px]">add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Right: Transaction Checkout Sidebar -->
        <aside class="w-80 bg-surface-container flex flex-col shadow-[-40px_0_60px_rgba(0,0,0,0.4)] relative z-10">
            <div class="p-6 border-b border-outline-variant/15 flex justify-between items-center bg-surface-container-high">
                <div class="flex flex-col">
                    <h2 class="headline font-bold text-lg text-white">Đơn hàng hiện tại</h2>
                    <span class="text-[10px] text-neutral-400 uppercase tracking-widest font-black">TXID: #NK-{{ date('ymd') }}-POS</span>
                </div>
                <button onclick="clearCart()" class="w-10 h-10 rounded-full bg-error-container/20 text-error hover:bg-error-container/40 transition-colors flex items-center justify-center">
                    <span class="material-symbols-outlined">delete_sweep</span>
                </button>
            </div>

            <!-- Cart Items List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="cart-items">
                <div class="flex flex-col items-center justify-center h-full text-center" id="emptyCart">
                    <span class="material-symbols-outlined text-6xl text-neutral-600 mb-4">shopping_cart</span>
                    <p class="text-neutral-400 text-sm">Chưa có sản phẩm trong giỏ hàng</p>
                </div>
            </div>

            <!-- Totals and Checkout -->
            <div class="p-6 bg-surface-container-high space-y-6">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-neutral-400">Tạm tính</span>
                        <span class="text-white font-medium" id="subtotal">0₫</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-neutral-400">Thuế (8.5%)</span>
                        <span class="text-white font-medium" id="tax">0₫</span>
                    </div>
                    <div class="pt-4 border-t border-outline-variant/15 flex justify-between items-end">
                        <span class="headline text-neutral-300 font-bold">Tổng cộng</span>
                        <span class="headline text-3xl font-black text-white" id="total">0₫</span>
                    </div>
                </div>
                <button onclick="openCheckoutModal()" class="w-full py-5 rounded-full bg-secondary text-on-secondary headline font-black text-lg shadow-[0_10px_30px_rgba(255,117,36,0.3)] hover:shadow-[0_15px_40px_rgba(255,117,36,0.5)] active:scale-95 transition-all uppercase tracking-tighter" id="checkoutBtn" disabled>
                    Xử lý thanh toán
                </button>
            </div>
        </aside>
    </main>

    <!-- Checkout Modal -->
    <div id="checkoutModal" class="fixed inset-0 bg-black/70 backdrop-blur-md z-50 hidden flex items-center justify-center">
        <div class="bg-neutral-900 rounded-xl w-full max-w-md overflow-hidden shadow-2xl border border-neutral-800">
            <div class="p-6 bg-neutral-950 border-b border-neutral-800 flex justify-between items-center">
                <h2 class="text-2xl font-headline font-bold text-white">Thông tin khách hàng</h2>
                <button onclick="closeCheckoutModal()" class="p-2 hover:bg-neutral-800 rounded-lg transition-colors text-neutral-400 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="checkoutForm" class="p-6 space-y-4 bg-neutral-900">
                <div>
                    <label class="block text-xs text-cyan-400 uppercase font-bold mb-2">Tên khách hàng *</label>
                    <input type="text" name="customer_name" required class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-2.5 text-white focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">
                </div>
                <div>
                    <label class="block text-xs text-cyan-400 uppercase font-bold mb-2">Email *</label>
                    <input type="email" name="customer_email" required class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-2.5 text-white focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">
                </div>
                <div>
                    <label class="block text-xs text-cyan-400 uppercase font-bold mb-2">Số điện thoại *</label>
                    <input type="tel" name="customer_phone" required class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-2.5 text-white focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">
                </div>
                <div>
                    <label class="block text-xs text-cyan-400 uppercase font-bold mb-2">Địa chỉ</label>
                    <textarea name="customer_address" rows="2" class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-2.5 text-white focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-cyan-400 uppercase font-bold mb-2">Phương thức thanh toán *</label>
                    <select name="payment_method" required class="w-full bg-neutral-800 border border-neutral-700 rounded-lg px-4 py-2.5 text-white focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">
                        <option value="cash">Tiền mặt</option>
                        <option value="card">Thẻ</option>
                        <option value="transfer">Chuyển khoản</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-4 rounded-full bg-secondary text-on-secondary headline font-black text-lg shadow-[0_10px_30px_rgba(255,117,36,0.3)] hover:shadow-[0_15px_40px_rgba(255,117,36,0.5)] active:scale-95 transition-all uppercase">
                    Xác nhận thanh toán
                </button>
            </form>
        </div>
    </div>

    <script>
        let cart = [];

        function addToCart(product) {
            const existingItem = cart.find(item => item.id === product.id);
            
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: 1
                });
            }
            
            updateCart();
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        }

        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    removeFromCart(productId);
                } else {
                    updateCart();
                }
            }
        }

        function clearCart() {
            if (cart.length === 0) return;
            if (confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')) {
                cart = [];
                updateCart();
            }
        }

        function updateCart() {
            const cartContainer = document.getElementById('cart-items');
            const emptyCart = document.getElementById('emptyCart');
            
            if (cart.length === 0) {
                cartContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-center" id="emptyCart">
                        <span class="material-symbols-outlined text-6xl text-neutral-600 mb-4">shopping_cart</span>
                        <p class="text-neutral-400 text-sm">Chưa có sản phẩm trong giỏ hàng</p>
                    </div>
                `;
                document.getElementById('checkoutBtn').disabled = true;
                document.getElementById('checkoutBtn').classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                let html = '';
                cart.forEach(item => {
                    html += `
                        <div class="bg-surface-container-highest/40 rounded-xl p-4 flex gap-4 items-center">
                            <div class="w-16 h-16 rounded-lg bg-black/40 flex-shrink-0">
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white">laptop</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold headline text-white">${item.name}</h4>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs text-primary font-bold">${formatPrice(item.price)}₫</span>
                                    <div class="flex items-center gap-3 bg-surface-container-low rounded-full px-2 py-1">
                                        <button onclick="updateQuantity(${item.id}, -1)" class="text-neutral-400 hover:text-white transition-colors">
                                            <span class="material-symbols-outlined text-xs">remove</span>
                                        </button>
                                        <span class="text-xs font-bold w-4 text-center text-white">${item.quantity}</span>
                                        <button onclick="updateQuantity(${item.id}, 1)" class="text-neutral-400 hover:text-white transition-colors">
                                            <span class="material-symbols-outlined text-xs">add</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                cartContainer.innerHTML = html;
                document.getElementById('checkoutBtn').disabled = false;
                document.getElementById('checkoutBtn').classList.remove('opacity-50', 'cursor-not-allowed');
            }
            
            updateTotals();
        }

        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.085;
            const total = subtotal + tax;
            
            document.getElementById('subtotal').textContent = formatPrice(subtotal) + '₫';
            document.getElementById('tax').textContent = formatPrice(tax) + '₫';
            document.getElementById('total').textContent = formatPrice(total) + '₫';
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN').format(Math.round(price));
        }

        function searchProducts() {
            const search = document.getElementById('searchProduct').value.toLowerCase();
            const products = document.querySelectorAll('.product-card');
            let visibleCount = 0;
            
            products.forEach(product => {
                const name = product.dataset.name;
                const isVisible = product.style.display !== 'none';
                
                if (name.includes(search)) {
                    product.classList.remove('hidden');
                    if (isVisible) visibleCount++;
                } else {
                    product.classList.add('hidden');
                }
            });
            
            updateProductCount();
        }

        function filterByCategory(categoryId) {
            const products = document.querySelectorAll('.product-card');
            const buttons = document.querySelectorAll('.category-btn');
            
            // Update button styles
            buttons.forEach(btn => {
                if (btn.dataset.category == categoryId) {
                    btn.className = 'category-btn px-6 py-2 rounded-lg bg-primary/10 text-primary border border-primary/20 headline text-sm font-bold whitespace-nowrap';
                } else {
                    btn.className = 'category-btn px-6 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-neutral-300 hover:text-white transition-colors headline text-sm font-medium whitespace-nowrap';
                }
            });
            
            // Filter products
            products.forEach(product => {
                const productCategory = product.dataset.category;
                if (categoryId === 'all' || productCategory == categoryId) {
                    product.style.display = '';
                } else {
                    product.style.display = 'none';
                }
            });
            
            updateProductCount();
        }

        function updateProductCount() {
            const products = document.querySelectorAll('.product-card');
            let count = 0;
            products.forEach(product => {
                if (product.style.display !== 'none' && !product.classList.contains('hidden')) {
                    count++;
                }
            });
            document.getElementById('productCount').textContent = count;
        }

        function openCheckoutModal() {
            if (cart.length === 0) return;
            document.getElementById('checkoutModal').classList.remove('hidden');
        }

        function closeCheckoutModal() {
            document.getElementById('checkoutModal').classList.add('hidden');
        }

        document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const total = subtotal * 1.085;
            
            const data = {
                items: cart,
                customer_name: formData.get('customer_name'),
                customer_email: formData.get('customer_email'),
                customer_phone: formData.get('customer_phone'),
                customer_address: formData.get('customer_address') || 'POS Sale',
                payment_method: formData.get('payment_method'),
                total: total
            };
            
            try {
                const response = await fetch('{{ route("admin.pos.payment") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Thanh toán thành công! Mã đơn hàng: ' + result.order_number);
                    cart = [];
                    updateCart();
                    closeCheckoutModal();
                    e.target.reset();
                } else {
                    alert('Có lỗi xảy ra: ' + result.message);
                }
            } catch (error) {
                alert('Có lỗi xảy ra khi xử lý thanh toán');
                console.error(error);
            }
        });
    </script>
</body>
</html>
