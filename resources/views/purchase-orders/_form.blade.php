@csrf

@if ($errors->any())
    <div class="mb-6 rounded-md border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-200">
        <p class="font-semibold mb-2">Periksa input berikut:</p>
        <ul class="list-disc list-inside text-sm text-red-200">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $oldPriceByItemId = [];
    $oldItems = old('items');

    if (is_array($oldItems)) {
        foreach ($oldItems as $oldItem) {
            if (isset($oldItem['purchase_request_item_id'])) {
                $oldPriceByItemId[$oldItem['purchase_request_item_id']] = $oldItem['harga'] ?? 0;
            }
        }
    }
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div>
        <label class="block text-sm text-slate-300 mb-1" for="purchase_request_id">Purchase Request</label>
        <select id="purchase_request_id" name="purchase_request_id" required
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">
            <option value="">Pilih Purchase Request</option>
            @foreach ($purchaseRequests as $purchaseRequestOption)
                <option value="{{ $purchaseRequestOption->id }}"
                    {{ (string) old('purchase_request_id', $purchaseOrder->purchase_request_id ?? '') === (string) $purchaseRequestOption->id ? 'selected' : '' }}>
                    {{ $purchaseRequestOption->nomor_pr }} - {{ $purchaseRequestOption->requester }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1" for="nomor_po">Nomor PO</label>
        <input id="nomor_po" name="nomor_po" type="text" required
            value="{{ old('nomor_po', $purchaseOrder->nomor_po ?? '') }}"
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1" for="tanggal">Tanggal</label>
        <input id="tanggal" name="tanggal" type="date" required
            value="{{ old('tanggal', $purchaseOrder->tanggal ?? '') }}"
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1" for="status">Status</label>
        <select id="status" name="status" required
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" {{ old('status', $purchaseOrder->status ?? '') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1" for="supplier_nama">Nama Supplier</label>
        <input id="supplier_nama" name="supplier_nama" type="text" required
            value="{{ old('supplier_nama', $purchaseOrder->supplier_nama ?? '') }}"
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1" for="supplier_kontak">Kontak Supplier</label>
        <input id="supplier_kontak" name="supplier_kontak" type="text" required
            value="{{ old('supplier_kontak', $purchaseOrder->supplier_kontak ?? '') }}"
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm text-slate-300 mb-1" for="supplier_alamat">Alamat Supplier</label>
        <textarea id="supplier_alamat" name="supplier_alamat" rows="3" required
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">{{ old('supplier_alamat', $purchaseOrder->supplier_alamat ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm text-slate-300 mb-1" for="catatan">Catatan</label>
        <textarea id="catatan" name="catatan" rows="3"
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">{{ old('catatan', $purchaseOrder->catatan ?? '') }}</textarea>
    </div>
</div>

<div class="mb-4">
    <h2 class="text-lg font-semibold">Item Purchase Order</h2>
    <p class="text-xs text-slate-400">Item diambil otomatis dari Purchase Request dan tidak bisa diubah.</p>
</div>

<div class="overflow-x-auto rounded-lg border border-slate-800">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-900">
            <tr>
                <th class="text-left px-3 py-2">Nama Barang</th>
                <th class="text-left px-3 py-2">SKU</th>
                <th class="text-right px-3 py-2">Qty</th>
                <th class="text-left px-3 py-2">Satuan</th>
                <th class="text-right px-3 py-2">Harga</th>
                <th class="text-right px-3 py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody id="po-items-container"></tbody>
    </table>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <button type="submit" class="px-5 py-2 rounded-md bg-cyan-400 text-slate-900 font-semibold hover:bg-cyan-300 transition-colors">Simpan</button>
    <a href="{{ route('purchase-orders.index') }}" class="px-5 py-2 rounded-md border border-slate-700 text-slate-200 hover:bg-slate-900 transition-colors">Batal</a>
</div>

<script>
    (function () {
        const purchaseRequests = @json($purchaseRequestPayloads ?? []);
        const priceByItemId = Object.assign({}, @json($priceByItemId ?? []), @json($oldPriceByItemId ?? []));
        const priceBySignature = @json($priceBySignature ?? []);
        const container = document.getElementById('po-items-container');
        const prSelect = document.getElementById('purchase_request_id');

        if (!container || !prSelect) {
            return;
        }

        const emptyRow = `
            <tr class="border-t border-slate-800">
                <td colspan="6" class="px-3 py-6 text-center text-slate-400">Pilih Purchase Request untuk menampilkan item.</td>
            </tr>
        `;

        const escapeHtml = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');

        const signatureFor = (item) => [
            item.nama_barang,
            item.sku || '',
            item.qty,
            item.satuan,
        ].join('|');

        const renderItems = (purchaseRequestId) => {
            container.innerHTML = '';

            const selected = purchaseRequests.find((entry) => String(entry.id) === String(purchaseRequestId));

            if (!selected || !Array.isArray(selected.items) || selected.items.length === 0) {
                container.innerHTML = emptyRow;
                return;
            }

            selected.items.forEach((item, index) => {
                const signature = signatureFor(item);
                const price = priceByItemId[item.id]
                    ?? priceBySignature[signature]
                    ?? 0;
                const subtotal = (Number(item.qty) || 0) * (Number(price) || 0);

                container.insertAdjacentHTML(
                    'beforeend',
                    `
                    <tr class="border-t border-slate-800" data-item-row data-qty="${escapeHtml(item.qty)}">
                        <td class="px-3 py-2">
                            <input type="hidden" name="items[${index}][purchase_request_item_id]" value="${escapeHtml(item.id)}">
                            <span class="text-slate-100">${escapeHtml(item.nama_barang)}</span>
                        </td>
                        <td class="px-3 py-2 text-slate-300">${escapeHtml(item.sku || '-')}</td>
                        <td class="px-3 py-2 text-right">${escapeHtml(item.qty)}</td>
                        <td class="px-3 py-2">${escapeHtml(item.satuan)}</td>
                        <td class="px-3 py-2">
                            <input type="number" name="items[${index}][harga]" min="0" step="0.01" required data-price
                                value="${escapeHtml(price)}"
                                class="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-slate-100 text-right">
                        </td>
                        <td class="px-3 py-2">
                            <input type="text" data-subtotal readonly value="${subtotal.toFixed(2)}"
                                class="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-slate-100 text-right">
                        </td>
                    </tr>
                    `
                );
            });
        };

        const recalcRow = (row) => {
            const qty = Number(row.dataset.qty || 0);
            const priceInput = row.querySelector('[data-price]');
            const subtotalInput = row.querySelector('[data-subtotal]');

            if (!priceInput || !subtotalInput) {
                return;
            }

            const price = parseFloat(priceInput.value) || 0;
            subtotalInput.value = (qty * price).toFixed(2);
        };

        container.addEventListener('input', (event) => {
            if (event.target.matches('[data-price]')) {
                const row = event.target.closest('[data-item-row]');
                if (row) {
                    recalcRow(row);
                }
            }
        });

        prSelect.addEventListener('change', () => {
            renderItems(prSelect.value);
        });

        renderItems("{{ old('purchase_request_id', $purchaseOrder->purchase_request_id ?? '') }}");
    })();
</script>
