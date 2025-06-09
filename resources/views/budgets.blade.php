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

      <section class="flex flex-col md:flex-row justify-between items-center w-full bg-light shadow-lg rounded-2xl p-6 mb-6 gap-6">

                <!-- Chart -->
            <div class="flex flex-col w-full md:w-1/3 items-center bg-base rounded-xl p-4 gap-4">
            <div id="donutChartPersen" data-sisa="{{ $persenSisa }}" data-pakai="{{ $persenPakai }}"></div>

            <x-primary-button
                type="button"
                data-modal-target="BudgetCate"
                data-modal-toggle="BudgetCate"
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
                        <div class="flex justify-between items-center text-light text-sm  space-x-2">
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
                                    class="fa fa-edit fa-lg hover:text-light cursor-pointer transition duration-300 ease-in-out"
                                    aria-hidden="true"
                                ></i>
                            </button>
                        </div>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-light flex items-center justify-center">
                        <i class="fa fa-credit-card text-xl text-accent"></i>
                    </div>
                </div>


            <!-- Pengeluaran -->
            <div class="flex items-center justify-between px-4 py-2 bg-primary text-light rounded-xl shadow">
                <div>
                    <p class="text-sm lg:text-lg">Pengeluaran</p>
                    <div class="flex items-start gap-1 font-bold">
                        <span class="text-sm lg:text-lg">Rp</span>
                        <p class="text-2xl">Rp {{ number_format($totalUsedAmount, 2, ',', '.') }}</p>
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
                        <span class="inline-flex text-accent font-semibold items-center gap-1">
                                (Harian)
                        </span>
                    </div>
                    <div class="flex items-start gap-1 font-bold">
                        <span class="text-sm lg:text-lg">Rp</span>
                        <p class="text-2xl">{{ number_format($rataRataHarianBudget, 2, ',', '.') }}</p>
                    </div>
                </div>
                <div class="h-10 w-10 rounded-full bg-light flex items-center justify-center">
                    <i class="fa fa-star-of-life text-xl text-accent"></i>
                </div>
            </div>
        </div>

    </section>

      <!-- Card Section -->
      <div
        class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
        <!-- Anggaran -->
        <section class="rounded-2xl bg-light shadow-lg p-6">
          <!-- Judul -->
          <div class="flex justify-between items-center mb-4 bg-base p-2 rounded-full border-2 border-primary">
              <x-secondary-button
                class="flex justify-center items-center h-8 w-8 rounded-full"
              >
                <i class="fa fa-hashtag fa-md"></i>
              </x-secondary-button>
              <h2 class="text-lg font-semibold">Transportasi</h2>
              <x-primary-button
                type="button"
                  data-modal-target="detail"
                  data-modal-toggle="detail" class="block w-8 h-8 rounded-full hover:bg-primary text-primary hover:text-light cursor-pointer transition duration-300 ease-in-out"
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
                  style="width: 80%"
                >
                  80%
                </div>
              </div>
              

              <div class="flex justify-between items-center">
                <p class="lightspace-nowrap">Anggaran</p>
                <div class="inline-flex justify-center items-center space-x-1">
                    <p class="text-right">60.000</p>
                    <button type="button" data-modal-target="BudgetCate"
                        data-modal-toggle="BudgetCate" class="text-sm text-netral-light hover:text-netral cursor-pointer transition duration-300 ease-in-out">
                        <i
                        class="fa fa-edit fa-lg"
                        aria-hidden="true"
                        ></i>
                    </button>
                </div>
              </div>
              <div class="flex justify-between items-center">
                <p class="lightspace-nowrap">Pengeluaran</p>
                <p class="text-right">40.000</p>
              </div>
              <div class="flex justify-between items-center">
                <p class="lightspace-nowrap">Sisa</p>
                <p class="text-right">20.000</p>
              </div>
            </div>
          </div>
        </section>
      </div>

      {{-- Modal Budget --}}
      @foreach($budgets as $budgets)

      <x-moddal id="deleteAlert-{{ $budgets->id }}" title="Hapus Transaksi" :name="'Hapus Transaksi'">
        <div class="mb-6 text-dark">
          Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
        </div>
        <form method="POST" action="{{ route('budgets.destroy', $budgets->id) }}" class="flex justify-end gap-2">
          @csrf
          @method('DELETE')
          <x-danger-button type="submit" class="rounded-lg px-4 py-2">
                Hapus
            </x-danger-button>
        </form>
      </x-moddal>

        <x-moddal id="budgetModal" title="Edit / Buat Anggaran" name="Edit / Buat Anggaran">
            <form action="{{ route('budgets.store') }}" method="POST" id="budgetForm">
                @csrf
                <input type="hidden" name="budget_id" id="budget_id" value="">

                <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
                    <div class="col-span-2">
                        <label for="amount" class="block mb-2 text-sm font-medium text-dark">Nominal</label>
                        <input type="number" id="amount" name="amount" placeholder="100000" required
                            class="block w-full p-2.5 text-sm text-dark bg-gray-50 border border-netral-light focus:border-accent focus:ring-accent rounded-lg shadow-lg" />
                    </div>

                    <div>
                        <label for="start_date" class="block mb-2 text-sm font-medium text-dark">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" required
                            class="block w-full p-2.5 text-sm text-dark bg-gray-50 border border-netral-light focus:border-accent focus:ring-accent rounded-lg shadow-lg" />
                    </div>

                    <div>
                        <label for="end_date" class="block mb-2 text-sm font-medium text-dark">Tanggal Berakhir</label>
                        <input type="date" id="end_date" name="end_date" required
                            class="block w-full p-2.5 text-sm text-dark bg-gray-50 border border-netral-light focus:border-accent focus:ring-accent rounded-lg shadow-lg" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6 gap-2">
                    <x-primary-button type="submit" class="rounded-lg px-4 py-2">Simpan</x-primary-button>
                </div>
            </form>
        </x-moddal>
@endforeach

      {{-- MODAL BUAT ANGGARAN --}}
    {{-- <x-moddal id="BudgetCate" title="Buat Anggaran" :name="'Buat Anggaran'">
      <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
          <div>
            <label for="category" class="block mb-2 text-sm font-medium text-dark">Kategori</label>
            <select id="category_id" name="category_id" required
                        class="block w-full p-2 border border-gray-300 rounded-md">
                    <option value="" disabled selected>Kategori</option>

                    <optgroup label="Pengeluaran (Outcome)">
                    @foreach ($categories->where('type', 'outcome') as $category)
                        <option value="{{ $category->id }}">
                        {{ $category->name }}
                        </option>
                    @endforeach
                    </optgroup>

                    <optgroup label="Pemasukkan (Income)">
                    @foreach ($categories->where('type', 'income') as $category)
                        <option value="{{ $category->id }}">
                        {{ $category->name }}
                        </option>
                    @endforeach
                    </optgroup>
                </select>    
        </div>
          <div>
            <label for="used_amount" class="block mb-2 text-sm font-medium text-dark">Nominal</label>
            <input type="number" id="used_amount" name="used_amount" placeholder="100000" required
                   class="block w-full p-2.5 text-sm text-dark bg-gray-50 border border-netral-light focus:border-accent focus:ring-accent rounded-lg shadow-lg" />
          </div>
         
        </div>
        <div class="flex items-center justify-end mt-6 gap-2">
          <x-primary-button type="submit"
                  class="px-4 py-2 text-light bg-accent rounded-lg hover:bg-primary transition duration-300 ease-in-out">
            Simpan
          </x-primary-button>
        </div>
      </form>
    </x-moddal> --}}

    {{-- MODAL DETAIL ANGGARAN CARD --}}
    <x-moddal id="detail" title="Detail Anggaran" :name="'Detail Anggaran'">
        <table class="min-w-full text-sm text-left text-dark ">
            <thead class="bg-primary text-light ">
              <tr>
                <th scope="col" class="px-4 py-2">Kategori</th>
                <th scope="col" class="px-4 py-2">Jam/Tanggal</th>
                <th scope="col" class="px-4 py-2">Nominal</th>
              </tr>
            </thead>
            <tbody class="bg-light">
              <tr class="border-b">
                <td class="px-4 py-2">Transportasi</td>
                <td class="px-4 py-2">10:30 / 02-05-2025</td>
                <td class="px-4 py-4">
                  <div
                    class="flex justify-center items-center gap-1 text-sm lg:text-lg"
                  >
                    <span class="text-sm lg:text-lg text-danger">
                      <i class="fa fa-minus" aria-hidden="true"></i>
                    </span>
                    <p>Rp20.000</p>
                  </div>
                </td>
              </tr>
              <tr class="border-b">
                <td class="px-4 py-2">Transportasi</td>
                <td class="px-4 py-2">08:00 / 01-05-2025</td>
                <td class="px-4 py-4">
                  <div
                    class="flex justify-center items-center gap-1 text-sm lg:text-lg"
                  >
                    <span class="text-sm lg:text-lg text-danger">
                      <i class="fa fa-minus" aria-hidden="true"></i>
                    </span>
                    <p>Rp20.000</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <form action="" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="flex items-center justify-end mt-6 ">
          <x-danger-button data-modal-target="deleteAlertRiwayat"
                            data-modal-toggle="deleteAlertRiwayat"
                            type="button"
                            class="flex justify-center items-center h-8 w-8  rounded-full ">
                      <i class="fa fa-trash"></i>
                    </x-danger-button>
    </x-moddal>

    <!-- Modal Hapus -->
      <x-moddal id="deleteAlertRiwayat" title="Hapus Transaksi" :name="'Hapus Transaksi'">
        <div class="mb-6 text-dark">
          Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
        </div>
        <form method="POST" action="" class="flex justify-end gap-2">
          @csrf
          @method('DELETE')
          <x-danger-button type="submit" class="rounded-lg px-4 py-2">
                Hapus
            </x-danger-button>
        </form>
      </x-moddal>

</x-app-layout>
