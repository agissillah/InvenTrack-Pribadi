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
    $items = old('items', $items ?? []);
    $items = array_values($items);

    if (count($items) === 0) {
        $items = [
            [
                'nama_barang' => '',
                'sku' => '',
                'qty' => 1,
                'satuan' => '',
            ],
        ];
    }
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div>
        <label class="block text-sm text-slate-300 mb-1" for="nomor_pr">Nomor PR</label>
        <input id="nomor_pr" name="nomor_pr" type="text" required
            value="{{ old('nomor_pr', $purchaseRequest->nomor_pr ?? '') }}"
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1" for="tanggal">Tanggal</label>
        <input id="tanggal" name="tanggal" type="date" required
            value="{{ old('tanggal', $purchaseRequest->tanggal ?? '') }}"
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1" for="requester">Requester</label>
        <input id="requester" name="requester" type="text" required
            value="{{ old('requester', $purchaseRequest->requester ?? '') }}"
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">
    </div>
    <div>
        <label class="block text-sm text-slate-300 mb-1" for="status">Status</label>
        <select id="status" name="status" required
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" {{ old('status', $purchaseRequest->status ?? '') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm text-slate-300 mb-1" for="catatan">Catatan</label>
        <textarea id="catatan" name="catatan" rows="3"
            class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400">{{ old('catatan', $purchaseRequest->catatan ?? '') }}</textarea>
    </div>
</div>

<div class="mb-4 flex items-center justify-between gap-4">
    <h2 class="text-lg font-semibold">Item Purchase Request</h2>
    <button type="button" id="pr-add-item" class="px-3 py-2 rounded-md border border-cyan-700 text-cyan-300 hover:bg-cyan-500/10">Tambah Item</button>
</div>

<div class="overflow-x-auto rounded-lg border border-slate-800">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-900">
            <tr>
                <th class="text-left px-3 py-2">Nama Barang</th>
                <th class="text-left px-3 py-2">SKU</th>
                <th class="text-right px-3 py-2">Qty</th>
                <th class="text-left px-3 py-2">Satuan</th>
                <th class="text-center px-3 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody id="pr-items-container" data-next-index="{{ count($items) }}">
            @foreach ($items as $index => $item)
                <tr class="border-t border-slate-800" data-item-row>
                    <td class="px-3 py-2">
                        <input type="text" name="items[{{ $index }}][nama_barang]" required
                            value="{{ $item['nama_barang'] ?? '' }}"
                            class="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-slate-100">
                    </td>
                    <td class="px-3 py-2">
                        <input type="text" name="items[{{ $index }}][sku]"
                            value="{{ $item['sku'] ?? '' }}"
                            class="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-slate-100">
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" name="items[{{ $index }}][qty]" min="1" required
                            value="{{ $item['qty'] ?? 1 }}"
                            class="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-slate-100 text-right">
                    </td>
                    <td class="px-3 py-2">
                        <input type="text" name="items[{{ $index }}][satuan]" required
                            value="{{ $item['satuan'] ?? '' }}"
                            class="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-slate-100">
                    </td>
                    <td class="px-3 py-2 text-center">
                        <button type="button" data-remove-item class="px-3 py-1 rounded border border-red-700 text-red-300 hover:bg-red-500/10">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <button type="submit" class="px-5 py-2 rounded-md bg-cyan-400 text-slate-900 font-semibold hover:bg-cyan-300 transition-colors">Simpan</button>
    <a href="{{ route('purchase-requests.index') }}" class="px-5 py-2 rounded-md border border-slate-700 text-slate-200 hover:bg-slate-900 transition-colors">Batal</a>
</div>

<script>
    (function () {
        const container = document.getElementById('pr-items-container');
        const addButton = document.getElementById('pr-add-item');

        if (!container || !addButton) {
            return;
        }

        let nextIndex = parseInt(container.dataset.nextIndex, 10) || 0;

        const buildRow = (index) => {
            return `
                <tr class="border-t border-slate-800" data-item-row>
                    <td class="px-3 py-2">
                        <input type="text" name="items[${index}][nama_barang]" required
                            class="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-slate-100">
                    </td>
                    <td class="px-3 py-2">
                        <input type="text" name="items[${index}][sku]"
                            class="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-slate-100">
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" name="items[${index}][qty]" min="1" value="1" required
                            class="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-slate-100 text-right">
                    </td>
                    <td class="px-3 py-2">
                        <input type="text" name="items[${index}][satuan]" required
                            class="w-full rounded-md border border-slate-700 bg-slate-800 px-3 py-2 text-slate-100">
                    </td>
                    <td class="px-3 py-2 text-center">
                        <button type="button" data-remove-item class="px-3 py-1 rounded border border-red-700 text-red-300 hover:bg-red-500/10">Hapus</button>
                    </td>
                </tr>
            `;
        };

        const updateRemoveButtons = () => {
            const rows = container.querySelectorAll('[data-item-row]');
            const disable = rows.length <= 1;

            rows.forEach((row) => {
                const button = row.querySelector('[data-remove-item]');
                if (button) {
                    button.disabled = disable;
                    button.classList.toggle('opacity-50', disable);
                    button.classList.toggle('cursor-not-allowed', disable);
                }
            });
        };

        updateRemoveButtons();

        addButton.addEventListener('click', () => {
            container.insertAdjacentHTML('beforeend', buildRow(nextIndex));
            nextIndex += 1;
            const newRow = container.querySelector('[data-item-row]:last-child');
            updateRemoveButtons();
        });

        container.addEventListener('click', (event) => {
            if (event.target.matches('[data-remove-item]')) {
                const row = event.target.closest('[data-item-row]');
                if (!row) {
                    return;
                }

                const rows = container.querySelectorAll('[data-item-row]');
                if (rows.length > 1) {
                    row.remove();
                    updateRemoveButtons();
                }
            }
        });
    })();
</script>
