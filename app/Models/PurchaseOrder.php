<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model PurchaseOrder merepresentasikan tabel `purchase_orders`.
 */
class PurchaseOrder extends Model
{
    use HasFactory;

    public const STATUS_OPTIONS = [
        'draft' => 'Draft',
        'approved' => 'Approved',
        'ordered' => 'Ordered',
        'received' => 'Received',
        'canceled' => 'Canceled',
    ];

    protected $fillable = [
        'purchase_request_id',
        'nomor_po',
        'tanggal',
        'supplier_nama',
        'supplier_kontak',
        'supplier_alamat',
        'status',
        'catatan',
    ];

    /**
     * Relasi ke Purchase Request asal.
     */
    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    /**
     * Relasi ke item-item Purchase Order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Opsi status yang dipakai di form.
     */
    public static function statusOptions(): array
    {
        return self::STATUS_OPTIONS;
    }
}
