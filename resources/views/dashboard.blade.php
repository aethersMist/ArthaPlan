<x-app-layout>

    <section class="w-full bg-light shadow-lg rounded-2xl p-6 mb-6 items-center justify-center">
    <!-- Calendar -->
    <div class="flex items-center space-x-2 mb-6">
        <x-secondary-button class="flex justify-center items-center h-8 w-8  rounded-full ">
            <i class="fa fa-calendar fa-md" aria-hidden="true"></i>
        </x-secondary-button>
        <div class="flex items-center gap-2">
            <p id="tanggal-terpilih" class="text-md font-bold text-dark">
            {{ $currentDate }}
            </p>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">


        <!-- Pemasukan -->
        <div class="flex items-center justify-between p-4 bg-primary text-light rounded-xl shadow">
        <div>
            <p class="text-sm lg:text-lg font-medium">Pemasukan</p>
            <div class="flex items-start gap-1 font-bold">
            <span class="text-sm lg:text-lg">Rp</span>
            <p class="text-2xl">{{ number_format($totalIncome, 2, ',', '.') }}</p>
            <span class="text-sm lg:text-lg text-accent">
                <i class="fa fa-arrow-down" aria-hidden="true"></i>
            </span>
            </div>
        </div>
<div class="h-10 w-10 rounded-full bg-light flex items-center justify-center
                hidden
                xs:flex
                md:hidden
                lg:flex">            <i class="fa-solid fa-heart text-xl text-accent"></i>
        </div>
        </div>

        <!-- Pengeluaran -->
        <div class="flex items-center justify-between p-4 bg-primary text-light rounded-xl shadow">
        <div>
            <p class="text-sm lg:text-lg font-medium">Pengeluaran</p>
            <div class="flex items-start gap-1 font-bold">
            <span class="text-sm lg:text-lg">Rp</span>
            <p class="text-2xl">{{ number_format($totalOutcome, 2, ',', '.') }}</p>
            <span class="text-sm lg:text-lg text-danger">
                <i class="fa fa-arrow-up" aria-hidden="true"></i>
            </span>
            </div>
        </div>
<div class="h-10 w-10 rounded-full bg-light flex items-center justify-center
                hidden
                xs:flex
                md:hidden
                lg:flex">            <i class="fa-solid fa-arrow-right-to-bracket text-xl text-accent"></i>
        </div>
        </div>

        <!-- Saldo -->
        <div class="flex items-center justify-between p-4 rounded-xl shadow-lg bg-primary text-light">
        <div>
            <p class="text-sm lg:text-lg font-medium">Saldo</p>
            <div class="flex items-start gap-1 font-bold">
            <span class="text-sm lg:text-lg">Rp</span>
            <p class="text-2xl">{{ number_format($totalBalance, 2, ',', '.') }}</p>
            </div>
        </div>
<div class="h-10 w-10 rounded-full bg-light flex items-center justify-center
                hidden
                xs:flex
                md:hidden
                lg:flex">
                            <i class="fa fa-money-bill text-2xl text-accent"></i>
        </div>
        </div>
    </div>
    </section>

    <!-- Card Section -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-2">
        <!-- Laporan (Pie Chart) -->
        <section class="rounded-2xl bg-light shadow-lg p-6">
            <!-- Header Laporan -->
            <a href="{{ route('reports') }}" class="flex items-center w-fit mb-4 gap-2 rounded-full bg-base  border-2 border-primary p-2 hover:bg-accent transition">
                <x-secondary-button class="flex justify-center items-center h-8 w-8 rounded-full">
                    <i class="fa fa-chart-pie fa-md"></i>
                </x-secondary-button>
                <h2 class="text-md lg:text-lg font-semibold">Laporan Transaksi</h2>
            </a>

            <!-- Chart dan Legenda -->
            <div class="space-y-4 lg:space-y-0 lg:flex gap-4 items-center justify-center">
            <!-- Donut Chart -->
            <div class="flex items-center justify-center w-full lg:w-1/2">
                <div id="pie-chart-Combined" data-categories-combined='{!! $combinedCategories !!}' data-values-combined='{!! $combinedValues !!}'>
                </div>
            </div>

            <!-- Legenda -->
            <div class="flex justify-center items-center mt-4 text-sm md:text-lg w-full bg-base  border-2 border-primary rounded-xl p-4 h-full">
                <ul id="legend-Report-Combined" class="flex flex-wrap gap-x-6 gap-y-2 space-x-4">
                    <li class="flex items-center transition hover:scale-105 mb-1 space-x-2">
                        <span class="inline-block w-3 h-3 rounded-full"></span>
                    </li>
                </ul>
            </div>
            </div>
        </section>

        <!-- Riwayat Transaksi -->
        <section class="rounded-2xl bg-light shadow-lg p-6">
            <a href="{{ route('transactions') }}" class="flex items-center w-fit mb-4 gap-2 rounded-full bg-base  border-2 border-primary p-2 hover:bg-accent transition">

            <x-secondary-button class="flex justify-center items-center h-8 w-8 rounded-full">
                <i class="fa fa-file-lines fa-md" aria-hidden="true"></i>
            </x-secondary-button>
            <h2 class="text-md lg:text-lg font-semibold">Riwayat Transaksi</h2>
            </a>
            <div class="overflow-x-auto rounded-lg">
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
                            <span class="flex justify-start items-center gap-1 {{ $transaction->category->type == 'income' ? 'text-accent' : 'text-danger' }}">
                                <i class="fa fa-{{ $transaction->category->type == 'income' ? 'plus' : 'minus' }}" aria-hidden="true"></i>
                                <p class="text-sm md:text-md lg:text-lg">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</p>
                            </span>
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
            </div>
        </section>

        <!-- Anggaran -->
        <section class="rounded-2xl bg-light shadow-lg p-6">
            <!-- Judul -->
            <a href="{{ route('budgets') }}" class="flex items-center w-fit mb-4 gap-2 rounded-full bg-base  border-2 border-primary p-2 hover:bg-accent transition">
            <x-secondary-button class="flex justify-center items-center h-8 w-8 rounded-full">
                <i class="fa-solid fa-table fa-md"></i>
            </x-secondary-button>
            <h2 class="text-md lg:text-lg font-semibold">Kategori Anggaran</h2>
            </a>

            <!-- Konten Utama -->
            <div class="flex flex-col lg:flex-row w-full gap-4 lg:gap-6 items-center lg:items-center justify-between">
                <!-- Chart -->
                <div class="w-full lg:w-2/5 flex justify-center bg-base border-2 border-primary rounded-xl shadow-lg lg:bg-transparent lg:border-0 lg:rounded-none lg:shadow-none xl:bg-base xl:border-2 xl:border-primary xl:rounded-xl xl:shadow-lg py-4">
                    <div id="donutChartPersen" data-sisa="{{ $persenSisa }}" data-pakai="{{ $persenPakai }}"></div>
                </div>

                <!-- Informasi Anggaran -->
                <div class="w-full lg:w-3/5 space-y-4 text-md sm:text-sm lg:text-lg">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <p class="whitespace-nowrap font-semibold">Anggaran</p>
                        <p class="text-left sm:text-right mt-1 sm:mt-0">Rp {{ number_format($totalBudgetTrans, 2, ',', '.') }}</p>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <p class="whitespace-nowrap font-semibold">Status</p>
                        <div class="flex flex-col sm:flex-row gap-1 items-start sm:items-center text-left sm:text-right mt-1 sm:mt-0">
                            <span class="text-sm {{ $Sisa == 0 && $totalOutcome > $totalBudgetTrans ? 'text-danger' : 'text-accent' }}">
                                ({{ $persenSisa }}%) {{ $statusAnggaran }}
                            </span>
                            <p class="text-left sm:text-right">Rp {{ number_format($Sisa, 2, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <p class="whitespace-nowrap font-semibold">Rata-rata Harian</p>
                        <p class="text-left sm:text-right mt-1 sm:mt-0">Rp {{ number_format($rataRataHarianOutcome, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Diagram (Bar Chart) -->
        <section class="flex flex-col rounded-2xl bg-light shadow-lg p-6">
            <!-- atas -->
            <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-center mb-4">

                <a href="{{ route('budgets') }}" class="flex items-center gap-2 rounded-full bg-base border-2 border-primary p-2 hover:bg-accent transition w-fit">
                    <x-secondary-button
                        class="flex justify-center items-center h-8 w-8 rounded-full"
                    >
                        <i class="fa-solid fa-chart-simple fa-md"></i>
                    </x-secondary-button>
                    <h2 class="text-md lg:text-lg font-semibold whitespace-nowrap">Diagram Transaksi</h2>
                </a>

                <!-- Dropdown -->
                <div class="flex justify-end">
                    <x-dropdown align="right" width="auto">
                        <x-slot name="trigger">
                            <x-secondary-button class="text-sm rounded-lg px-1 py-[1px] gap-1">
                                {{ ucfirst($filter ?? 'bulan') }}
                                <i class="fa-solid fa-chevron-down"></i>
                            </x-secondary-button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="space-y-1 p-1 rounded-lg text-sm text-dark">
                                @foreach(['tahun', 'bulan', 'minggu', 'hari'] as $option)
                                    <button
                                        type="button"
                                        data-filter="{{ $option }}"
                                        class="w-full text-left px-3 py-1.5 hover:bg-gray-100 {{ ($filter ?? 'bulan') === $option ? 'font-bold bg-gray-200 rounded' : '' }}">
                                        {{ ucfirst($option) }}
                                    </button>
                                @endforeach
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- body Diagram -->
            <div class="space-y-4">
            <div class="flex justify-between items-center text-sm lg:text-lg">
                <p>
                Rata-rata: <span>Rp{{ number_format($rataRata, 2, ',', '.') }}</span></p>
            </div>
            <div
                class="bg-gradient-to-t from-accent to-base rounded-xl h-auto flex items-center justify-center text-light p-4"
            >
                <canvas id="barChartCanvas"
                    data-labels='@json($labels)'
                    data-data-out='@json($dataOut)'
                    data-data-in='@json($dataIn)'>
            </canvas>
            </div>
            </div>
        </section>

    </div>
    </div>

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
                                class="block w-full p-2 border border-netral-light focus:border-accent focus:ring-accent rounded-lg shadow-lg">
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
</x-app-layout>
