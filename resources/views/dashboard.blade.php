<x-layouts::app :title="__('Dashboard')">
    @push('head')
        <meta name="livewire-cache" content="no">
    @endpush

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex justify-between">
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl font-bold text-neutral-800 dark:text-neutral-100">{{ __('Dashboard Admin') }}</h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    {{ __('Welcome back, :name!', ['name' => auth()->user()->name]) }}
                </p>
            </div>
            <x-button primary href="{{ route('home') }}" icon="home">
                <span class="hidden md:inline">{{ __('Back to homepage') }}</span>
            </x-button>
        </div>

        {{-- Statistics Grid --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-4">
            <div class="flex flex-col gap-1 rounded-xl border border-neutral-200 p-6 dark:border-neutral-700 bg-blue-700/15">
                <span class="text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Total Products') }}</span>
                <span class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">{{ number_format($totalProducts) }}</span>
            </div>
            <div class="flex flex-col gap-1 rounded-xl border border-neutral-200 p-6 dark:border-neutral-700 bg-blue-700/15">
                <span class="text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Total Orders') }}</span>
                <span class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">{{ number_format($totalOrders) }}</span>
            </div>
            <div class="flex flex-col gap-1 rounded-xl border border-neutral-200 p-6 dark:border-neutral-700 bg-blue-700/15">
                <span class="text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Total Revenue') }}</span>
                <span class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col gap-1 rounded-xl border border-neutral-200 p-6 dark:border-neutral-700 bg-blue-700/15">
                <span class="text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('New Customers') }}</span>
                <span class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">{{ number_format($newCustomers) }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            {{-- Line Chart --}}
            <div class="lg:col-span-2 flex flex-col gap-4 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">{{ __('Order Trends (Last 7 Days)') }}</h3>
                </div>
                <div class="h-64">
                    <canvas id="orderChart"></canvas>
                </div>
            </div>

            {{-- Circle Chart --}}
            <div class="flex flex-col gap-4 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">{{ __('Products by Category') }}</h3>
                </div>
                <div class="h-64 flex justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Use a function to initialize charts so we can call it on navigation
        function initCharts() {
            const orderCanvas = document.getElementById('orderChart');
            const categoryCanvas = document.getElementById('categoryChart');

            if (!orderCanvas || !categoryCanvas) return;

            // Line Chart
            const ctxOrder = orderCanvas.getContext('2d');
            
            // Check if chart instance already exists (to avoid "Canvas is already in use" error)
            if (window.orderChartInstance) {
                window.orderChartInstance.destroy();
            }

            window.orderChartInstance = new Chart(ctxOrder, {
                type: 'line',
                data: {
                    labels: {!! json_encode($lineChartLabels) !!},
                    datasets: [{
                        label: '{{ __("Orders") }}',
                        data: {!! json_encode($lineChartData) !!},
                        borderColor: 'rgb(79, 70, 229)',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(79, 70, 229)',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Circle Chart
            const ctxCategory = categoryCanvas.getContext('2d');

            if (window.categoryChartInstance) {
                window.categoryChartInstance.destroy();
            }

            window.categoryChartInstance = new Chart(ctxCategory, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($circleChartLabels) !!},
                    datasets: [{
                        data: {!! json_encode($circleChartData) !!},
                        backgroundColor: [
                            'rgba(79, 70, 229, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(107, 114, 128, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    }
                }
            });
        }

        // Initialize on load
        initCharts();

        // Also initialize on Livewire navigation
        document.addEventListener('livewire:navigated', initCharts);
    </script>
    @endpush
</x-layouts::app>
