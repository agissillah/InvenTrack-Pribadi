<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model PurchaseRequest merepresentasikan tabel `purchase_requests`.
 */
class PurchaseRequest extends Model
{
    use HasFactory;

    public const STATUS_OPTIONS = [
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    protected $fillable = [
        'nomor_pr',
        'tanggal',
        'requester',
        'status',
        'catatan',
    ];

    /**
     * Relasi ke item-item Purchase Request.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    /**
     * Relasi ke Purchase Order yang diturunkan dari PR.
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Opsi status yang dipakai di form.
     */
    public static function statusOptions(): array
    {
        return self::STATUS_OPTIONS;
    }
}
