<x-app-layout>
  <div class="overflow-x-auto rounded-lg">
    @if (session('success'))
      <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
      </div>
    @endif
    <div class="min-h-screen flex flex-col">



<div class="space-y-4 flex-1">
    @php
        $groupedTransactions = $transactions->sortByDesc('date')->groupBy(function($item) {
            return $item->date->format('Y-m-d');
        });

        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $todayTransactions = $groupedTransactions->pull($today) ?? collect();
    @endphp

    {{-- Today's Section --}}
    @if($todayTransactions->count() > 0)
    <div>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full mt-4 gap-2">
                <p>Hari ini - {{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
                <div class="flex justify-start sm:justify-center items-center space-x-4">
                    @php
                        $todayIncome = $todayTransactions->where('category.type', 'income')->sum('amount');
                        $todayOutcome = $todayTransactions->where('category.type', 'outcome')->sum('amount');
                    @endphp
                    <p class="text-sm text-dark">
                        <i class="fa fa-arrow-down text-accent" aria-hidden="true"></i>
                        <span class="font-semibold">Rp{{ number_format($todayIncome, 2, ',', '.') }}</span>
                    </p>
                    <p class="text-sm text-dark">
                        <i class="fa fa-arrow-up text-danger" aria-hidden="true"></i>
                        <span class="font-semibold">Rp{{ number_format($todayOutcome, 2, ',', '.') }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-2 bg-light rounded-lg px-2 py-2 shadow-lg border-2 border-primary overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="bg-light divide-y divide-gray-200">
                    @foreach($todayTransactions as $transaction)
                    <tr>
                        <td class="py-4 w-10 min-w-[40px]">
                            <div class="h-10 w-10 rounded-full bg-accent border-2 border-primary flex items-center justify-center">
                                <i class="fa-solid fa-dollar-sign text-xl text-primary"></i>
                            </div>
                        </td>
                        <td class="py-4 px-2 min-w-[120px]">
                            <p class="text-md font-medium text-dark">{{ $transaction->category->name }}</p>
                        </td>
                        <td class="py-4 min-w-[150px]">
                            <div class="flex items-center justify-end space-x-2">
                                <p>
                                    <span class="inline-flex items-center justify-between gap-x-2 font-semibold {{ $transaction->category->type == 'income' ? 'text-accent' : 'text-danger' }}">
                                        <i class="fa fa-{{ $transaction->category->type == 'income' ? 'plus' : 'minus' }} fa-lg"></i>
                                        Rp{{ number_format($transaction->amount, 2, ',', '.') }}
                                    </span>
                                </p>
                                <!-- Action buttons -->
                                <x-primary-button
                                    data-modal-target="edit-{{ $transaction->id }}"
                                    data-modal-toggle="edit-{{ $transaction->id }}"
                                    type="button"
                                    class="flex justify-center items-center h-8 w-8 rounded-full">
                                    <i class="fa fa-edit"></i>
                                </x-primary-button>

                                <x-secondary-button
                                    data-modal-target="deleteAlert-{{ $transaction->id }}"
                                    data-modal-toggle="deleteAlert-{{ $transaction->id }}"
                                    type="button"
                                    class="flex justify-center items-center h-8 w-8 rounded-full">
                                    <i class="fa fa-trash"></i>
                                </x-secondary-button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Other Days --}}
    @forelse($groupedTransactions as $date => $dailyTransactions)
    @php
        $currentDate = \Carbon\Carbon::parse($date);
        $isYesterday = $currentDate->isYesterday();

        // Calculate daily totals
        $dailyIncome = $dailyTransactions->where('category.type', 'income')->sum('amount');
        $dailyOutcome = $dailyTransactions->where('category.type', 'outcome')->sum('amount');
    @endphp

    <div class="">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full mt-4 gap-2">
            <p>
                @if($isYesterday)
                    Kemarin - {{ $currentDate->translatedFormat('l, d F Y') }}
                @else
                    {{ $currentDate->translatedFormat('l, d F Y') }}
                @endif
            </p>
            <div class="flex justify-start sm:justify-center items-center space-x-4">
                <p class="text-sm text-dark">
                    <i class="fa fa-arrow-down text-accent" aria-hidden="true"></i>
                    <span class="font-semibold">Rp{{ number_format($dailyIncome, 2, ',', '.') }}</span>
                </p>
                <p class="text-sm text-dark">
                    <i class="fa fa-arrow-up text-danger" aria-hidden="true"></i>
                    <span class="font-semibold">Rp{{ number_format($dailyOutcome, 2, ',', '.') }}</span>
                </p>
            </div>
        </div>

        <div class="mt-2 rounded-lg shadow-lg border-2 border-primary bg-light px-2 py-2 sm:px-4 sm:py-2 w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="divide-y divide-gray-200">
                    @foreach($dailyTransactions as $transaction)
                    <tr>
                        <td class="py-4 w-10">
                            <div class="h-10 w-10 rounded-full bg-accent border-2 border-primary flex items-center justify-center">
                                <i class="fa fa-dollar-sign text-xl text-primary"></i>
                            </div>
                        </td>
                        <td class="py-4 px-2 min-w-[120px]">
                            <p class="text-md font-medium text-dark">{{ $transaction->category->name }}</p>
                            @if($transaction->description)
                                <p class="text-xs text-gray-500 mt-1">{{ $transaction->description }}</p>
                            @endif
                        </td>
                        <td class="py-4">
                            <div class="flex items-center justify-end space-x-2">
                                <p>
                                    <span class="inline-flex items-center justify-between gap-x-2 font-semibold {{ $transaction->category->type == 'income' ? 'text-accent' : 'text-danger' }}">
                                        <i class="fa fa-{{ $transaction->category->type == 'income' ? 'plus' : 'minus' }} fa-lg"></i>
                                        Rp{{ number_format($transaction->amount, 2, ',', '.') }}
                                    </span>
                                </p>
                                <!-- Action buttons -->
                                <x-primary-button
                                    data-modal-target="edit-{{ $transaction->id }}"
                                    data-modal-toggle="edit-{{ $transaction->id }}"
                                    type="button"
                                    class="flex justify-center items-center h-8 w-8 rounded-full">
                                    <i class="fa fa-edit"></i>
                                </x-primary-button>

                                <x-secondary-button
                                    data-modal-target="deleteAlert-{{ $transaction->id }}"
                                    data-modal-toggle="deleteAlert-{{ $transaction->id }}"
                                    type="button"
                                    class="flex justify-center items-center h-8 w-8 rounded-full">
                                    <i class="fa fa-trash"></i>
                                </x-secondary-button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    @if($todayTransactions->count() == 0)
    <div class="w-full text-center py-4 text-md text-dark font-medium bg-light rounded-lg">
        Tidak ada data transaksi.
    </div>
    @endif
    @endforelse
</div>

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
                        <label for="category_id_{{ $transaction->id }}" class="block mb-2  text-gray-700">Kategori</label>
                        <select id="category_id_{{ $transaction->id }}" name="category_id" required
                                class="block w-full p-2 border border-netral-light focus:border-accent focus:ring-accent rounded-lg resize-none shadow-lg">
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
                        <x-text-input type="date" id="date_{{ $transaction->id }}" name="date"
                            value="{{ $transaction->date->format('Y-m-d') }}"
                            required />
                    </div>

                    <div class="col-span-2">
                        <label for="amount_{{ $transaction->id }}" class="block mb-2 text-sm font-medium text-dark">Nominal</label>
                        <x-text-input type="number" id="amount_{{ $transaction->id }}" name="amount" value="{{ $transaction->amount }}"
                            required />
                    </div>

                    <div class="col-span-2">
                        <label for="description_{{ $transaction->id }}" class="block mb-2 text-sm font-medium text-dark">Keterangan</label>
                        <textarea id="description_{{ $transaction->id }}" name="description" rows="3"
                                class="block w-full p-2.5 text-sm text-dark bg-gray-50 border border-netral-light focus:border-accent focus:ring-accent rounded-lg resize-none shadow-lg">{{ $transaction->description }}</textarea>
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
    </div>

</x-app-layout>
