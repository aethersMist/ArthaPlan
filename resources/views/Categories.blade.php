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
          <th class="px-4 py-3">Tipe</th>
          <th class="px-4 py-3">#</th>
        </tr>
      </thead>
      <tbody class="bg-light divide-y divide-gray-200">
        @forelse ($categories as $index => $category)
                <tr>
                  <td class="px-4 py-4">{{ $index + 1 }}</td>
                  <td class="px-4 py-4">
                    <div class="flex justify-start items-center">
                        <span class="text-center px-2 py-1 bg-primary text-light font-medium rounded-lg">
                         {{ $category->name }}
                      </span>
                    </div>
                  </td>
                  <td class="px-4 py-4">
                    <div class="flex justify-start items-center">
                       <span class="text-center px-2 py-1 rounded-lg font-semibold
                            {{ $category->type == 'income' ? 'bg-accent/50 text-primary-dark' : 'bg-danger/50 text-danger-dark' }}">
                            {{ $category->type == 'income' ? 'Pemasukkan' : 'Pengeluaran' }}
                        </span>
                    </div>
                  </td>

                  </td>
                  <td class="flex gap-2 px-4 py-4">
                    <!-- Tombol Edit -->
                    <x-primary-button data-modal-target="editRiwayat-{{ $category->id }}"
                            data-modal-toggle="editRiwayat-{{ $category->id }}"
                            type="button"
                            class="flex justify-center items-center h-8 w-8  rounded-full ">
                      <i class="fa fa-edit"></i>
                    </x-primary-button>

                    <!-- Tombol Hapus -->
                    <x-secondary-button data-modal-target="deleteAlert-{{ $category->id }}"
                            data-modal-toggle="deleteAlert-{{ $category->id }}"
                            type="button"
                            class="flex justify-center items-center h-8 w-8  rounded-full ">
                      <i class="fa fa-trash"></i>
                    </x-secondary-button>
                  </td>
                </tr>
@empty
          <tr>
            <td colspan="4" class="w-full text-center py-4 text-md text-dark font-medium bg-light">
              Tidak ada data Kategori.
            </td>
          </tr>
        @endforelse

      </tbody>
    </table>

    <!-- Semua Modal -->
    @foreach($categories as $category)

      <!-- Modal Hapus -->
      <x-moddal id="deleteAlert-{{ $category->id }}" title="Hapus Kategori" :name="'Hapus Kategori'">
        <div class="mb-6 text-dark">
          Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
        </div>
        <form method="POST" action="{{ route('categories.destroy', $category->id) }}" class="flex justify-end gap-2">
          @csrf
          @method('DELETE')
          <x-danger-button type="submit" class="rounded-lg px-4 py-2">
                Hapus
            </x-danger-button>
        </form>
      </x-moddal>

      <!-- Modal Edit -->
      <x-moddal id="editRiwayat-{{ $category->id }}" title="Update Kategori" :name="'Update Kategori'">
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
            <div>
              <label for="category_{{ $category->id }}" class="block mb-2 text-sm font-medium text-dark">Kategori</label>
                <x-text-input type="text" id="category_{{ $category->id }}" name="name" value="{{ $category->name }}"
                     required/>
            </div>
            <div>
              <label for="type_{{ $category->id }}" class="block mb-2 text-sm font-medium text-dark ">Jenis Kategori</label>
              <select id="type_{{ $category->id }}" name="type"
                      class="block w-full p-2.5 text-sm text-dark bg-gray-50 border border-netral-light focus:border-accent focus:ring-accent rounded-lg shadow-lg" required>
                 <option value="income" {{ $category->type == 'income' ? 'selected' : '' }}>Pemasukkan</option>
                <option value="outcome" {{ $category->type == 'outcome' ? 'selected' : '' }}>Pengeluaran</option>
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
      @endforeach
    </div>
  </div>

</x-app-layout>
