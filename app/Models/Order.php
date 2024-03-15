<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'number',
        'order_key',
        'status',
        'total',
        'date_created',
        'customer_id',
        'customer_note',
        'billing',
        'shipping'
    ];
    public function lineitem()
    {
        return $this->hasMany(LineItem::class,'order_id','id');
    }
    public function scopeStatus(Builder $builder, string $status)
    {
        return $builder->where('status', $status);
    }
    public function scopeOrderId(Builder $builder, string $id)
    {
        return $builder->where('number', $id);
    }
    public function scopeCustomer(Builder $builder, string $id)
    {
        return $builder->where('customer_id', $id);
    }
    public function scopeSearch(Builder $builder, string $search)
    {
        return $builder->where('customer_note','like','%'.$search.'%');
    }

    public function scopeFromDate(Builder $builder, string $date)
    {
        return $builder->whereDate('created_at', '>=', $date);
    }

    public function scopeToDate(Builder $builder, string $date)
    {
        return $builder->whereDate('created_at', '<=', $date);
    }
}
