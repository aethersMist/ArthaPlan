<x-app-layout>
  <div class="overflow-x-auto rounded-lg">
    @if (session('success'))
      <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
      </div>
    @endif

    <table class="min-w-full text-sm lg:text-md text-left text-gray-700">
      <thead class="text-sm lg:text-md text-primary-dark uppercase bg-accent">
        <tr>
          <th class="px-4 py-3">#</th>
          <th class="px-4 py-3">Kategori</th>
          <th class="px-4 py-3">Nominal</th>
          <th class="px-4 py-3">Tanggal</th>
          <th class="px-4 py-3">Keterangan</th>
          <th class="px-4 py-3">Action</th>
        </tr>
      </thead>
      <tbody class="bg-light divide-y divide-gray-200">
        @forelse($transactions as $index => $transaction)
            <tr>
                <td class="px-4 py-4">{{ $index + 1 }}</td>
                <td class="px-4 py-4">
                <div class="flex justify-start items-center">
                    <span class="text-center px-2 py-1 w-full bg-primary text-light font-medium rounded-lg">
                        {{ $transaction->category->name }}
                    </span>
                </div>
                </td>
                <td class="px-4 py-4">
                <div class="flex justify-start items-center gap-1 text-sm lg:text-lg">
                    <span class="{{ $transaction->category->type == 'income' ? 'text-accent' : 'text-danger' }}">
                    <i class="fa fa-{{ $transaction->category->type == 'income' ? 'plus' : 'minus' }}" aria-hidden="true"></i>
                    </span>
                    <p>Rp{{ number_format($transaction->amount, 2, ',', '.') }}</p>
                </div>
                </td>
                <td class="px-4 py-4">{{ $transaction->date->translatedFormat('l, d F Y') }}</td>

                <td class="px-4 py-4">{{ $transaction->description }}</td>
 
                <td class="flex gap-2 px-4 py-4">
                <!-- Tombol Update -->
                <x-primary-button data-modal-target="edit-{{ $transaction->id }}"
                        data-modal-toggle="edit-{{ $transaction->id }}"
                        type="button"
                                class="flex justify-center items-center h-8 w-8  rounded-full">
                    <i class="fa fa-edit"></i>
                </x-primary-button>

                <!-- Tombol Hapus -->
                <x-secondary-button data-modal-target="deleteAlert-{{ $transaction->id }}"
                        data-modal-toggle="deleteAlert-{{ $transaction->id }}"
                        type="button"
                        class="flex justify-center items-center h-8 w-8  rounded-full ">
                    <i class="fa fa-trash"></i>
                </x-secondary-button>
                </td>
            </tr>

            @empty
          <tr>
            <td colspan="6" class="w-full text-center py-4 text-md text-dark font-medium bg-light">
              Tidak ada data transaksi.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <!-- Semua Modal -->
      <!-- Modal Hapus -->
    @foreach($transactions as $transaction)

      <x-moddal id="deleteAlert-{{ $transaction->id }}" title="Hapus Transaksi" :name="'Hapus Transaksi'">
        <div class="mb-6 text-dark">
          Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
        </div>
        <form method="POST" action="{{ route('transactions.destroy', $transaction->id) }}" class="flex justify-end gap-2">
          @csrf
          @method('DELETE')
          <x-danger-button type="submit" class="rounded-lg px-4 py-2">
                Hapus
            </x-danger-button>
        </form>
      </x-moddal>

      <!-- Modal Update -->
        <x-moddal id="edit-{{ $transaction->id }}" title="Update Transaksi" :name="'Update Transaksi'">
            <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
                    <!-- Kategori -->
                    <div>
                        <label for="category_id_{{ $transaction->id }}" class="block mb-2 font-semibold text-gray-700">Kategori</label>
                        <select id="category_id_{{ $transaction->id }}" name="category_id" required
                                class="block w-full p-2 border border-gray-300 rounded-md">
                            <option value="" disabled>Kategori</option>

                            <optgroup label="Pengeluaran (Outcome)">
                                @foreach ($categories->where('type', 'outcome') as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $transaction->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Pemasukkan (Income)">
                                @foreach ($categories->where('type', 'income') as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $transaction->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label for="date_{{ $transaction->id }}" class="block mb-2 text-sm font-medium text-dark">Tanggal</label>
                        <input type="date" id="date_{{ $transaction->id }}" name="date"
                            value="{{ $transaction->date->format('Y-m-d') }}"
                            required class="block w-full p-2.5 text-sm text-dark bg-gray-50 border border-gray-300 rounded-lg" />
                    </div>

                    <div class="col-span-2">
                        <label for="amount_{{ $transaction->id }}" class="block mb-2 text-sm font-medium text-dark">Nominal</label>
                        <input type="number" id="amount_{{ $transaction->id }}" name="amount" value="{{ $transaction->amount }}"
                            required class="block w-full p-2.5 text-sm text-dark bg-gray-50 border border-gray-300 rounded-lg" />
                    </div>

                    <div class="col-span-2">
                        <label for="description_{{ $transaction->id }}" class="block mb-2 text-sm font-medium text-dark">Keterangan</label>
                        <textarea id="description_{{ $transaction->id }}" name="description" rows="3"
                                class="block w-full p-2.5 text-sm text-dark bg-gray-50 border border-gray-300 rounded-lg resize-none">{{ $transaction->description }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6 gap-2">
                    <x-primary-button type="submit" class="rounded-lg px-4 py-2">
                        Simpan
                    </x-primary-button>
                </div>
            </form>
        </x-moddal>
      @endforeach

      
  </div>
</x-app-layout>
