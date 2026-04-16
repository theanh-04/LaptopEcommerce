<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã khuyến mãi
            $table->string('name'); // Tên chương trình
            $table->text('description')->nullable(); // Mô tả
            $table->enum('type', ['percentage', 'fixed']); // Loại: % hoặc số tiền cố định
            $table->decimal('value', 15, 2); // Giá trị giảm
            $table->decimal('min_order_amount', 15, 2)->default(0); // Đơn hàng tối thiểu
            $table->decimal('max_discount', 15, 2)->nullable(); // Giảm tối đa (cho %)
            $table->integer('usage_limit')->nullable(); // Số lần sử dụng tối đa
            $table->integer('used_count')->default(0); // Đã sử dụng
            $table->dateTime('start_date'); // Ngày bắt đầu
            $table->dateTime('end_date'); // Ngày kết thúc
            $table->boolean('is_active')->default(true); // Đang hoạt động
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
