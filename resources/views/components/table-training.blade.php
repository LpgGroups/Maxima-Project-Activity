@props([
    'id' => 'table-training',
    'pageSize' => 20,
])

<div class="flex justify-between items-center mb-2">
    <input type="text" placeholder="Cari No Surat / Pelatihan..." class="border px-2 py-1 text-sm rounded-md"
        data-ref="search">
</div>

<div id="{{ $id }}" class="space-y-3" data-page-size="{{ $pageSize }}">
    <div class="overflow-x-auto rounded-xl border bg-white">
        <table class="table-auto w-full text-center align-middle">
            <thead>
                <tr class="bg-slate-600 lg:text-sm text-white text-[10px]">
                    <th class="rounded-l-lg p-2">No</th>
                    <th>No Surat</th>
                    <th>PIC</th>
                    <th>Perusahaan</th>
                    <th>Pelatihan</th>
                    <th>Lokasi</th>
                    <th>Tanggal</th>
                    <th>Status Upload</th>
                </tr>
            </thead>
            <tbody class="lg:text-[14px] text-[10px]" data-ref="tbody">
                <tr>
                    <td colspan="9" class="py-6 text-gray-500">Memuat...</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between text-sm" data-ref="pager"></div>
</div>
