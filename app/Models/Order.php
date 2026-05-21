<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helpers; 

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'note',
        'status',
        'total',
        'payment_method',
        'transaction_code',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    protected $appends = ['order_code', 'total_formatted'];

    /**
     * Virtual Order Code (Mã đơn hàng ảo)
     */
    public function getOrderCodeAttribute(): string
    {
        if ($this->transaction_code) {
            return $this->transaction_code;
        }
        // Fallback cho đơn hàng cũ: Dùng DH + ID padding
        return 'DH' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Một đơn hàng có nhiều sản phẩm (order_items)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Một đơn hàng thuộc về 1 người dùng
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: Định dạng tổng tiền khi hiển thị ra giao diện
     */
    public function getTotalFormattedAttribute(): string
    {
        return Helpers::format((float)$this->total);
    }

    /**
     * Mutator: Khi lưu tổng tiền, tự động chuyển về số thực
     */
    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = Helpers::parse($value);
    }
}
