    <x-app-layout>
      <div
        class="flex flex-col gap-4 justify-between items-center w-full overflow-hidden"
      >
<div class="flex max-w-md px-2 justify-between items-center bg-accent/50 border-2  rounded-lg shadow-lg w-auto">
        {{-- Title --}}
          <h2
            class="text-sm p-4 text-light w-full"
          >
            Unduh Laporan
          </h2>
            {{-- Button --}}
            <a href="{{ route('reports.export.all') }}" title="Unduh Laporan CSV">
                <x-primary-button
                            class="flex justify-center items-center h-8 w-8  rounded-full ">
                      <i class="fa fa-download"></i>
                    </x-primary-button>
            </a>
        </div>

        {{-- Income --}}
      <div class="flex justify-center items-center w-full">
        {{-- Title --}}
          <h2
            class="text-xl p-4  text-center text-light bg-primary uppercase rounded-lg shadow-lg w-full font-bold"
          >
            laporan Pemasukkan
          </h2>
        </div>


        <div
          class="w-full grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-2"
        >

          <!-- line chart -->
          <section class="rounded-2xl bg-light shadow-lg p-4 w-full">
            <div class="w-full gap-2">
              <div
                class="flex justify-center items-center mb-4 text-center font-bold text-light bg-accent uppercase rounded-lg p-2 shadow-lg"
              >
                <p>total pemasukkan</p>
              </div>
              <div
                class="bg-gradient-to-t from-accent to-base rounded-xl h-auto flex items-center justify-center text-light p-4"
            >
                <canvas id="barChartIncome"
                    data-labels='@json($labels)'
                    data-data='@json($dataIn)'>
                </canvas>
            </div>
            </div>
          </section>
          <!-- line chart -->

          <!-- pie chart-->
          <section class="rounded-2xl bg-light shadow-lg p-4">
            <div class="w-full">
              <div class="w-full gap-2">
                <h2
                  class="mb-4 text-center font-bold text-light bg-accent uppercase rounded-lg p-2 shadow-lg"
                >
                  Rincian Kategori Pemasukan
                </h2>
                <!-- Pie chart -->
            <div class="flex items-center justify-center w-full">

                    <div id="pie-chart-Income"
                        data-categories='@json($categoriesIncome)'
                        data-values='@json($valuesIncome)'>
                    </div>

                </div>
             </div>

              <!-- Legenda -->
              <div
                class="flex justify-between items-center mt-4 text-sm md:text-lg w-full bg-base border-2 border-primary rounded-xl p-4 h-full">
                <ul id="legend-Report-Income" class="flex flex-col gap-y-4 w-full">
                    <li class="flex justify-between items-center w-full text-sm font-semibold text-dark mb-1">
                        <div class="inline-flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full"></span>
                        </div>
                  </li>
                </ul>
              </div>
            </div>
          </section>
        </div>

        {{-- Outcome --}}
        <div class="sm:flex justify-between items-center gap-4 w-full">
            {{-- title --}}
          <h2
            class="text-xl p-4 font-bold text-center text-light bg-danger-dark rounded-lg shadow-lg uppercase w-full"
          >
            laporan pengeluaran
          </h2>
        </div>

        <div
          class="w-full grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-2"
        >
          <!-- line chart Pengeluaran -->
          <section class="rounded-2xl bg-light shadow-lg p-6">
            <div class="w-full gap-2">
              <div
                class="flex justify-center items-center mb-4 text-center font-bold text-light bg-danger uppercase rounded-lg p-2 shadow-lg"
              >
                <p>total pengeluaran</p>

              </div>

             <div
                class="bg-gradient-to-t from-danger-light to-base rounded-xl h-auto flex items-center justify-center text-light p-4"
            >
                <canvas id="barChartOutcome"
                    data-labels='@json($labels)'
                    data-data='@json($dataOut)'>
            </canvas>
            </div> 
            </div>
          </section>

          <!-- piechart-->
          <section class="rounded-2xl bg-light shadow-lg p-4">
            <div class="w-full">
              <div class="w-full">
                <h2
                  class="mb-4 text-center font-bold text-light bg-danger uppercase rounded-lg p-2 shadow-lg"
                >
                  Rincian Kategori Pengeluaran
                </h2>
               <!-- Pie chart -->
                 <div class="flex items-center justify-center w-full">

                    <div id="pie-chart-Outcome"
                        data-categories-out='@json($categoriesOutcome)' data-values-out='@json($valuesOutcome)'>
                    </div>

                </div>
             </div>

              <!-- Legenda -->
              <div
                class="flex justify-between items-center mt-4 text-sm md:text-lg w-full bg-base border-2 border-primary rounded-xl p-4 h-full">
                <ul id="legend-Report-Outcome" class="flex flex-col gap-y-4 w-full">
                    <li class="flex justify-between items-center w-full text-sm font-semibold text-dark mb-1">
                        <div class="inline-flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full"></span>
                        </div>
                  </li>
                </ul>
              </div>
            </div>
          </section>
        </div>
      </div>
    </x-app-layout>
