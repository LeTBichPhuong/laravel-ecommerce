<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->decimal('total', 10, 2)->default(0);
            if (!Schema::hasColumn('orders', 'created_at')) {
                $table->timestamp('created_at')->useCurrent();
            }
            if (!Schema::hasColumn('orders', 'updated_at')) {
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            }
            $table->string('payment_method')->default('COD');
$table->string('transaction_code')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
            $table->dropColumn('payment_method');
            $table->dropColumn('transaction_code');
        });
    }
};