<x-app-layout>
    <section class="w-full bg-light shadow-lg rounded-2xl p-6 mb-6 items-center justify-center">
        <!-- Calendar -->
        <div class="flex items-center space-x-2 mb-6">
        <div class="flex justify-center items-center h-8 w-8 bg-primary text-light rounded-full cursor-pointer hover:text-light hover:bg-accent shadow-lg">
            <i class="fa fa-calendar fa-md" aria-hidden="true"></i>
        </div>
        <div class="flex items-center gap-2">
            <p id="tanggal-terpilih" class="text-md font-bold text-dark">
            {{ $currentDate }}
            </p>
        </div>
        </div>

        <!-- Ringkasan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Saldo -->
            <div class="flex items-center justify-between p-4 rounded-xl shadow-lg bg-primary text-light">
            <div>
                <p class="text-sm lg:text-lg">Saldo</p>
                <div class="flex items-start gap-1 font-bold">
                <span class="text-sm lg:text-lg">Rp</span>
                <p class="text-2xl">{{ number_format($totalBalance, 2, ',', '.') }}</p>
                </div>
            </div>
            <div class="h-10 w-10 rounded-full bg-light flex md:hidden lg:flex items-center justify-center">
                <i class="fa fa-money-bill text-2xl text-accent"></i>
            </div>
            </div>

            <!-- Pemasukan -->
            <div class="flex items-center justify-between p-4 bg-primary text-light rounded-xl shadow">
            <div>
                <p class="text-sm lg:text-lg">Pemasukan</p>
                <div class="flex items-start gap-1 font-bold">
                <span class="text-sm lg:text-lg">Rp</span>
                <p class="text-2xl">{{ number_format($totalIncome, 2, ',', '.') }}</p>
                <span class="text-sm lg:text-lg text-accent">
                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                </span>
                </div>
            </div>
            <div class="h-10 w-10 rounded-full bg-light flex md:hidden lg:flex items-center justify-center">
                <i class="fa-solid fa-heart text-xl text-accent"></i>
            </div>
            </div>

            <!-- Pengeluaran -->
            <div class="flex items-center justify-between p-4 bg-primary text-light rounded-xl shadow">
            <div>
                <p class="text-sm lg:text-lg">Pengeluaran</p>
                <div class="flex items-start gap-1 font-bold">
                <span class="text-sm lg:text-lg">Rp</span>
                <p class="text-2xl">{{ number_format($totalOutcome, 2, ',', '.') }}</p>
                <span class="text-sm lg:text-lg text-danger">
                    <i class="fa fa-arrow-up" aria-hidden="true"></i>
                </span>
                </div>
            </div>
            <div class="h-10 w-10 rounded-full bg-light flex md:hidden lg:flex items-center justify-center">
                <i class="fa-solid fa-arrow-right-to-bracket text-xl text-accent"></i>
            </div>
            </div>
        </div>
    </section>
    <div class="overflow-x-auto rounded-lg">
        @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
        @endif
      <section class="flex flex-col md:flex-row justify-between items-center w-full bg-light shadow-lg rounded-2xl p-6 mb-6 gap-6">

                <!-- Chart -->
            <div class="flex flex-col w-full md:w-1/3 items-center bg-base border-2 border-primary rounded-xl p-4 gap-4">
                <div id="donutChartPersen" data-sisa="{{ $persenSisa }}" data-pakai="{{ $persenPakai }}"></div>

                <x-primary-button
                    type="button"
                    data-modal-target="addBudgetTransactionModal"
                    data-modal-toggle="addBudgetTransactionModal"
                    class="rounded-full space-x-2"
                >
                <i class="fa fa-plus " aria-hidden="true"></i>
                <p>Anggaran Kategori</p>
                </x-primary-button>
            </div>

        <!-- Ringkasan -->
        <div class="w-full md:w-2/3 grid grid-row-1 md:grid-row-3 gap-4">
            <!-- Anggaran -->
                <div class="flex items-center justify-between px-4 py-2 rounded-xl shadow-lg  bg-primary text-light">
                    <div>
                        <!-- Tanggal -->
                        <div class="flex justify-start items-center text-light text-sm  space-x-2">
                            <p class="lg:text-lg">Anggaran</p>
                            <span class="inline-flex text-accent font-semibold items-center gap-1">
                                ({{ $budgets->count() ? $budgets->first()->start_date->translatedFormat('d F Y') : '-' }}
                                -
                                {{ $budgets->count() ? $budgets->first()->end_date->translatedFormat('d F Y') : '-' }})
                            </span>
                        </div>
                        <div class="flex items-start gap-1 font-bold">
                            <span class="text-sm lg:text-lg">Rp</span>
                            <p class="text-2xl">{{ number_format($totalBudgetAmount, 2, ',', '.') }}</p>
                            <button
                                type="button" data-modal-target="budgetModal"
                                    data-modal-toggle="budgetModal" class="text-sm text-netral-light">
                                <i
                                    class="fa fa-pen fa-lg hover:text-light cursor-pointer transition duration-300 ease-in-out"
                                    aria-hidden="true"
                                ></i>
                            </button>
                            @foreach($budgets as $budget)
                                <button
                                    type="button" data-modal-target="editBudgetModal-{{ $budget->id }}" data-modal-toggle="editBudgetModal-{{ $budget->id }}" class="text-sm text-netral-light hover:text-netral cursor-pointer transition duration-300 ease-in-out">
                                    <i class="fa fa-edit fa-lg "></i>
                                </button>
                            @endforeach

                        </div>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-light flex items-center justify-center">
                        <i class="fa fa-credit-card text-xl text-accent"></i>
                    </div>
                </div>


            <!-- Pengeluaran -->
            <div class="flex items-center justify-between px-4 py-2 bg-primary text-light rounded-xl shadow">
                <div>
                    <div class="flex justify-start items-center text-light text-sm  space-x-2">
                        <p class="text-sm lg:text-lg">Status</p>
                        <span class="font-semibold {{ $Sisa == 0 && $totalOutcome > $totalBudgetAmount ? 'text-danger' : 'text-accent' }}">
                            {{ $statusAnggaran }} ({{ $persenSisa }}%)
                        </span>
                    </div>
                    <div class="flex items-start gap-1 font-bold">
                        <span class="text-sm lg:text-lg">Rp</span>
                        <p class="text-2xl">{{ number_format($Sisa, 2, ',', '.') }}</p>
                    </div>
                </div>
                <div class="h-10 w-10 rounded-full bg-light flex items-center justify-center">
                    <i class="fa fa-right-from-bracket text-xl text-accent"></i>
                </div>
            </div>

            <!-- Rata-rata -->
            <div class="flex items-center justify-between px-4 py-2 bg-primary text-light rounded-xl shadow">
                <div>
                    <div class="flex justify-between items-center text-light text-sm  space-x-2">
                        <p class="text-sm lg:text-lg">Rata-rata</p>
                        <span class=" text-accent font-semibold ">
                                (Harian)
                        </span>
                    </div>
                    <div class="flex items-start gap-1 font-bold">
                        <span class="text-sm lg:text-lg">Rp</span>
                        <p class="text-2xl">{{ number_format($rataRataHarianOutcome, 2, ',', '.') }}</p>
                    </div>
                </div>
                <div class="h-10 w-10 rounded-full bg-light flex items-center justify-center">
                    <i class="fa fa-star-of-life text-xl text-accent"></i>
                </div>
            </div>
        </div>

    </section>

      <!-- Card Section -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
        <!-- Anggaran -->
            @foreach ($budget->budgetTransaction as $transaction)
        <section class="rounded-2xl bg-light shadow-lg p-6">
            <input type="hidden" name="budget_id" value="{{ $transaction->budget_id }}">
            <input type="hidden" name="category_id" value="{{ $transaction->category_id }}">
            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

          <!-- Judul -->
          <div class="flex justify-between items-center mb-4 bg-base p-2 rounded-full border-2 border-primary">
              <x-secondary-button
                class="flex justify-center items-center h-8 w-8 rounded-full"
              >
                <i class="fa fa-hashtag fa-md"></i>
              </x-secondary-button>
              <h2 class="text-lg font-semibold">{{ $transaction->category->name }}</h2>
              <x-primary-button
                type="button"
                  data-modal-target="detail-{{ $transaction->id }}"
                  data-modal-toggle="detail-{{ $transaction->id }}" class="block w-8 h-8 rounded-full hover:bg-primary text-primary hover:text-light cursor-pointer transition duration-300 ease-in-out"
              >
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
              </x-primary-button>
          </div>

          <!-- Konten Utama -->
          <div
            class="flex flex-col lg:flex-row w-full gap-4 lg:gap-6 items-center lg:items-center justify-between"
          >
            <!-- Informasi Anggaran -->
            <div class="w-full space-y-2 text-md sm:text-sm lg:text-lg">
                <div class="w-full space-y-2 text-md sm:text-sm lg:text-lg">

              <!-- Line -->
              <div class="w-full bg-gray-200 rounded-xl p-1">
                <div
                  class="h-4 p-1 flex justify-center items-center text-xs font-medium rounded-full bg-accent"
                   style="width: {{ $transaction->progress }}%">
                    {{ round($transaction->progress) }}%
                </div>
              </div>

              {{-- Detail --}}
              <div class="flex justify-between items-center">
                  <p class="lightspace-nowrap">Anggaran</p>
                  <div class="inline-flex justify-center items-center space-x-1">
                      <p class="text-right">Rp{{ number_format($transaction->limit, 2, ',', '.') }}</p>
                <button type="button" data-modal-target="editBudgetTrans-{{ $transaction->id }}"
                    data-modal-toggle="editBudgetTrans-{{ $transaction->id }}" class="text-sm text-netral-light hover:text-netral cursor-pointer transition duration-300 ease-in-out">
                    <i
                    class="fa fa-edit fa-lg"
                    aria-hidden="true"
                    ></i>
                </button>
            </div>
            </div>
              <div class="flex justify-between items-center">
                <p class="lightspace-nowrap">Pengeluaran</p>
                <p class="text-right">Rp {{ number_format($transaction->totalOutcome, 2, ',', '.') }}</p>
              </div>
              <div class="flex justify-between items-center">
                <p class="lightspace-nowrap">Sisa</p>
                <p class="text-right {{ $transaction->remaining < 0 ? 'text-danger font-semibold' : '' }}">
                        Rp{{ number_format($transaction->remaining, 2, ',', '.') }}
              </div>
            </div>
          </div>
        </section>
        @endforeach
      </div>

      {{-- Modal Budget --}}
        <x-moddal id="budgetModal" title="Buat Anggaran" name="Buat Anggaran">
            <form action="{{ route('budgets.store') }}" method="POST" id="budgetForm">
                @csrf
                <input type="hidden" name="budget_id" id="budget_id" value="">

                <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
                    <div class="col-span-2">
                        <label for="amount" class="block mb-2 text-sm font-medium text-dark">Nominal</label>
                        <x-text-input type="number" id="amount" name="amount" placeholder="100000" required />
                    </div>

                    <div>
                        <label for="start_date" class="block mb-2 text-sm font-medium text-dark">Tanggal Mulai</label>
                        <x-text-input type="date" id="start_date" name="start_date" required />
                    </div>

                    <div>
                        <label for="end_date" class="block mb-2 text-sm font-medium text-dark">Tanggal Berakhir</label>
                        <x-text-input type="date" id="end_date" name="end_date" required />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6 gap-2">

                    <x-primary-button type="submit" class="rounded-lg px-4 py-2">Simpan</x-primary-button>
                </div>
            </form>
        </x-moddal>

        <!-- Modal Edit Anggaran -->
      @foreach($budgets as $budget)
    <x-moddal id="editBudgetModal-{{ $budget->id }}" title="Edit Anggaran" :name="'Edit Anggaran'">
        <form action="{{ route('budgets.update', $budget->id) }}" method="POST" id="editBudgetForm-{{ $budget->id }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="budget_id" id="edit_budget_id_{{ $budget->id }}" value="{{ $budget->id }}">

            <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
                <div class="col-span-2">
                    <label for="edit_amount_{{ $budget->id }}" class="block mb-2 text-sm font-medium text-dark">Nominal</label>
                    <x-text-input type="number" id="edit_amount_{{ $budget->id }}" name="amount" placeholder="100000" required
                        value="{{ $budget->amount }}" />
                </div>

                <div>
                    <label for="edit_start_date_{{ $budget->id }}" class="block mb-2 text-sm font-medium text-dark">Tanggal Mulai</label>
                    <x-text-input type="date" id="edit_start_date_{{ $budget->id }}" name="start_date" required
                        value="{{ $budget->start_date->format('Y-m-d') }}" />
                </div>

                <div>
                    <label for="edit_end_date_{{ $budget->id }}" class="block mb-2 text-sm font-medium text-dark">Tanggal Berakhir</label>
                    <x-text-input type="date" id="edit_end_date_{{ $budget->id }}" name="end_date" required
                        value="{{ $budget->end_date->format('Y-m-d') }}" />
                </div>
            </div>

            <div class="flex items-center justify-between mt-6 gap-2">
                <x-danger-button data-modal-target="deleteAlert-{{ $budget->id }}"
                            data-modal-toggle="deleteAlert-{{ $budget->id }}"
                            type="button"
                            class="flex justify-center items-center h-8 w-8  rounded-full ">
                      <i class="fa fa-trash"></i>
                    </x-danger-button>

                <x-primary-button type="submit" class="rounded-lg px-4 py-2">Update</x-primary-button>
            </div>
        </form>
    </x-moddal>

        {{-- MODAL HAPUS ANGGARAN --}}
          <x-moddal id="deleteAlert-{{ $budget->id }}" title="Hapus Anggaran" :name="'Hapus Anggaran'">
        <div class="mb-6 text-dark">
          Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
        </div>
        <form method="POST" action="{{ route('budgets.destroy', $budget->id) }}" class="flex justify-end gap-2">
          @csrf
          @method('DELETE')
          <x-danger-button type="submit" class="rounded-lg px-4 py-2">
                Hapus
            </x-danger-button>
        </form>
          </x-moddal>
@endforeach

{{-- BudgetTransaction --}}
      {{-- MODAL BUAT ANGGARAN --}}
    <x-moddal id="addBudgetTransactionModal" title="Buat Anggaran" :name="'Buat Anggaran'">
      <form action="{{ route('budgetTransactions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="budget_id" id="budget_id" value="{{ $budget->id }}">
        <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

        <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
          <div>
            <label for="category" class="block mb-2 text-sm font-medium text-dark">Kategori</label>
            <select id="category_id" name="category_id" required
                        class="block w-full p-2 border border-netral-light focus:border-accent focus:ring-accent rounded-lg shadow-lg" >
                    <option value="" disabled selected>Kategori</option>

                    <optgroup label="Pengeluaran (Outcome)">
                    @foreach ($categories->where('type', 'outcome') as $category)
                        <option value="{{ $category->id }}">
                        {{ $category->name }}
                        </option>
                    @endforeach
                    </optgroup>
                </select>
        </div>
          <div>
            <label for="used_amount" class="block mb-2 text-sm font-medium text-dark">Nominal</label>
            <x-text-input type="number" id="used_amount" name="used_amount" placeholder="100000" required/>
          </div>

        </div>
        <div class="flex items-center justify-end mt-6 gap-2">
          <x-primary-button type="submit"
                  class="px-4 py-2 text-light bg-accent rounded-lg hover:bg-primary transition duration-300 ease-in-out">
            Simpan
          </x-primary-button>
        </div>
      </form>
    </x-moddal>

    </div>

    {{-- MODAL UPDATE ANGGARAN CARD --}}
            @foreach ($budget->budgetTransaction as $index => $transaction)

            {{-- Delete --}}
        <x-moddal id="deleteAlertBudgetTrans-{{ $transaction->id }}" title="Hapus Transaksi" :name="'Hapus Transaksi'">
            <div class="mb-6 text-dark">
            Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <form method="POST" action="{{ route('budgetTransactions.destroy', $transaction->id) }}" class="flex justify-end gap-2">
            @csrf
            @method('DELETE')
            <x-danger-button type="submit" class="rounded-lg px-4 py-2">
                    Hapus
                </x-danger-button>
            </form>
        </x-moddal>

    <x-moddal id="editBudgetTrans-{{ $transaction->id }}" title="Update Anggaran" :name="'Update Anggaran'">
      <form action="{{ route('budgetTransactions.update', $transaction->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
          <div>
            <input type="hidden" name="budget_id" value="{{ $transaction->budget_id }}">
            <input type="hidden" name="transaction_id" value="{{ $transaction->transaction_id }}">

            <label for="category_id_{{ $transaction->id }}" class="block mb-2 font-semibold text-gray-700">Kategori</label>
            <select id="category_id_{{ $transaction->id }}" name="category_id" required class="block w-full p-2 border border-netral-light focus:border-accent focus:ring-accent rounded-lg shadow-lg" >
                    <option value="" disabled selected>Kategori</option>

                    <optgroup label="Pengeluaran (Outcome)">
                        @foreach ($categories->where('type', 'outcome') as $category)
                            <option value="{{ $category->id }}"
                                {{ $transaction->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
        </div>
          <div>
            <label for="used_amount" class="block mb-2 text-sm font-medium text-dark">Nominal</label>
            <x-text-input type="number" id="used_amount" name="used_amount"   value="{{ $transaction->used_amount }}" required  />
          </div>

        </div>
        <div class="flex items-center justify-end mt-6 gap-2">
          <x-primary-button type="submit"
                  class="px-4 py-2 text-light bg-accent rounded-lg hover:bg-primary transition duration-300 ease-in-out">
            Simpan
          </x-primary-button>
        </div>
      </form>
    </x-moddal>

    
    {{-- MODAL DETAIL ANGGARAN CARD --}}
    <x-moddal id="detail-{{ $transaction->id }}" title="Detail {{ $transaction->category->name }}" :name="'Detail {{ $transaction->category->name }}'">
        
        <table class="min-w-full text-sm text-left text-dark">
            <thead class="bg-primary text-light">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Nominal</th>
                </tr>
            </thead>
            <tbody class="bg-light">
                @php
                    $filteredTransactions = $transactions->where('category_id', $transaction->category_id);
                    $no = 1;
                @endphp

                @foreach ($filteredTransactions as $t)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $no++ }}</td>
                                <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($t->date)->translatedFormat('l, d M Y') }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex justify-center items-center gap-1 text-sm lg:text-lg">
                                <span class="text-sm lg:text-lg text-danger">
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </span>
                                <p>Rp{{ number_format($t->amount, 2, ',', '.') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @csrf
        <div class="flex items-center justify-end mt-6 ">
            <x-danger-button data-modal-target="deleteAlertBudgetTrans-{{ $transaction->id }}"
                            data-modal-toggle="deleteAlertBudgetTrans-{{ $transaction->id }}"
                            type="button"
                            class="flex justify-center items-center h-8 w-8  rounded-full ">
                <i class="fa fa-trash"></i>
            </x-danger-button>
        </div>
    </x-moddal>
@endforeach
    </div>
</x-app-layout>


