<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model PurchaseRequestItem merepresentasikan tabel `purchase_request_items`.
 */
class PurchaseRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'nama_barang',
        'sku',
        'qty',
        'satuan',
        'harga_estimasi',
        'subtotal',
    ];

    /**
     * Item ini milik Purchase Request tertentu.
     */
    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class);
    }
}
