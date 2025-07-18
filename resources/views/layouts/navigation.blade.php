<nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 z-50 bg-primary-dark py-2.5 m-4 md:mx-6 max-w-screen-xl:rounded-full rounded-2xl shadow-lg px-4 sm:px-6 md:px-8">
    <div class="flex flex-wrap items-center justify-between max-w-screen-xl mx-auto gap-4 md:gap-2">

        <!-- Hamburger -->
                    <x-primary-button @click="open = !open" class="sm:hidden inline-flex h-8 w-8 rounded-md p-2 ">
            <i class="fa fa-bars fa-lg" :class="{ 'hidden': open, 'inline-flex': !open }"></i>
            <i class="fa-solid fa-xmark" :class="{ 'hidden': !open, 'inline-flex': open }"></i>
        </x-primary-button>

        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center space-x-2">
            <x-application-logo class="block h-9 w-auto fill-current text-light" />
            <span class="self-center text-xl md:text-lg font-semibold whitespace-nowrap text-light">ArthaPlan</span>
         </a>

        <!-- Navigation Links -->
        <div class="hidden space-x-4 sm:-my-px sm:flex items-center justify-between w-full md:flex sm:w-auto md:order-1">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Beranda') }}
            </x-nav-link>
            <x-nav-link :href="route('reports')" :active="request()->routeIs('reports')">
                {{ __('Laporan') }}
            </x-nav-link>
            <x-nav-link :href="route('transactions')" :active="request()->routeIs('transactions')">
                {{ __('Transaksi') }}
            </x-nav-link>
            <x-nav-link :href="route('budgets')" :active="request()->routeIs('budgets')">
                {{ __('Anggaran') }}
            </x-nav-link>
        </div>

        <!-- Right Section -->
        <div class="flex items-center md:order-2 space-x-2">

            <!-- Tombol Modal Tambah -->

            <x-primary-button data-modal-target="addModal"
                data-modal-toggle="addModal" class="relative flex items-center justify-center rounded-full w-8 h-8">
                <i class="fa fa-plus fa-lg" aria-hidden="true"></i>
            </x-primary-button>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="auto">
                    <x-slot name="trigger">
                        <x-secondary-button class="w-8 h-8 text-sm leading-4 font-medium rounded-full ">
                            <i class="fa fa-user text-light text-md" aria-hidden="true"></i>
                        </x-secondary-button>


                    </x-slot>

                    <x-slot name="content" cla>
                        <div class="mx-1 px-4 py-2 bg-primary rounded-lg">
                            <div class="font-medium text-md text-light">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="space-y-1 p-1 rounded-lg text-sm text-dark">
                            <x-dropdown-link :href="route('categories')">
                                {{ __('Kategori') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('reports.export.all')">
                                {{ __('Unduh Laporan') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')" >
                                {{ __('Pengaturan ') }}
                            </x-dropdown-link>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Keluar') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Beranda') }}
            </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('reports')" :active="request()->routeIs('reports')">
                {{ __('Laporan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('transactions')" :active="request()->routeIs('transactions')">
                {{ __('Transaksi') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('budgets')" :active="request()->routeIs('budgets')">
                {{ __('Anggaran') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="w-auto pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-md text-light">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-netral-light">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 p-1 rounded-lg text-sm text-dark bg-light">
                <x-responsive-nav-link :href="route('categories')">
                    {{ __('Kategori') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.export.all')">
                    {{ __('Unduh Laporan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Pengaturan') }}
                </x-responsive-nav-link>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Keluar') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- Tambahkan Transaksi --}}
       <x-moddal id="addModal" title="Tambah Transaksi" :name="'Tambah Transaksi'">
<form action="{{ route('transactions.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
            <div>
                <label for="category_id" class="block mb-2 text-sm font-medium text-dark">Kategori</label>
                <select id="category_id" name="category_id" required
                        class="block w-full p-2 border border-netral-light focus:border-accent focus:ring-accent rounded-lg shadow-lg">
                    <option value="" disabled selected>Kategori</option>

                    <optgroup label="Pengeluaran">
                        @foreach ($categories->where('type', 'outcome') as $category)
                            <option value="{{ $category->id }}">
                            {{ $category->name }}
                            </option>
                        @endforeach
                    </optgroup>

                    <optgroup label="Pemasukkan">
                        @foreach ($categories->where('type', 'income') as $category)
                            <option value="{{ $category->id }}">
                            {{ $category->name }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <div>
              <label for="date" class="block mb-2 text-sm font-medium text-dark">Tanggal</label>
              <x-text-input type="date" id="date" name="date" required/>
            </div>
          <div class="col-span-2">
            <label for="amount" class="block mb-2 text-sm font-medium text-dark">Nominal</label>
            <x-text-input type="number" id="amount" name="amount" placeholder="100000" required />
          </div>
          <div class="col-span-2">
            <label for="description" class="block mb-2 text-sm font-medium text-dark">Keterangan</label>
            <textarea id="description" name="description" rows="3"
                      class="block w-full p-2.5 text-sm text-dark bg-gray-50 border border-netral-light focus:border-accent focus:ring-accent rounded-lg resize-none shadow-lg"
                      placeholder="Keterangan"></textarea>
          </div>
        </div>
        <div class="flex items-center justify-between mt-6">
             <x-secondary-button type="button" data-modal-target="addCategoryModal" data-modal-toggle="addCategoryModal" class="rounded-lg space-x-2">
                <i class="fa fa-plus" aria-hidden="true"></i> <p>Kategori</p>
                </x-secondary-button>
          <x-primary-button type="submit"
                  class=" rounded-lg ">
            Simpan
          </x-primary-button>
        </div>
      </form>
    </x-moddal>

    <!-- Modal Tambah Kategori -->
<x-moddal id="addCategoryModal" title="Tambah Kategori" :name="'Tambah Kategori'">
      <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
            <div>
              <label for="name" class="block mb-2 text-sm font-medium text-dark">Kategori</label>
              <x-text-input type="text" id="name" name="name" placeholder="Makanan, Transportasi, dll"
                     required class="block w-full p-2.5 text-sm text-dark bg-gray-50 border border-netral-light focus:border-accent focus:ring-accent rounded-lg" />
            </div>
            <div>
              <label for="type" class="block mb-2 text-sm font-medium text-dark">Jenis Kategori</label>
              <select id="type" name="type"
                      class="block w-full p-2.5 text-sm text-dark bg-gray-50 border border-netral-light focus:border-accent focus:ring-accent rounded-lg shadow-lg" required>
                <option value="income" >Pemasukkan</option>
                <option value="outcome" >Pengeluaran</option>
              </select>
            </div>
          </div>
          <div class="flex items-center justify-end mt-6 gap-2">
            <x-primary-button type="submit" class="rounded-lg px-4 py-2">
                Simpan
            </x-primary-button>
          </div>
        </form>
</x-moddal>



