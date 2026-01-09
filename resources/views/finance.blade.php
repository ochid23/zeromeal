@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6" x-data="financeApp()">
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Finance & Budgeting
                </h2>
                <p class="mt-1 text-sm text-gray-500">Manage your weekly meal budget and shopping list.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Budget & Summary Card -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Weekly Budget</h3>
                        <div class="mt-4">
                            <label for="budget" class="block text-sm font-medium text-gray-700">Set your budget Amount
                                (Rp)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="budget" id="budget" x-model.number="budget"
                                    class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md py-2"
                                    placeholder="0">
                            </div>
                        </div>

                        <div class="mt-6 border-t border-gray-200 pt-6 space-y-4">
                            <div class="flex justify-between text-sm font-medium">
                                <span class="text-gray-500">Total Estimated Cost:</span>
                                <span class="text-gray-900" x-text="formatRupiah(totalCost)"></span>
                            </div>
                            <div class="flex justify-between text-sm font-medium">
                                <span class="text-gray-500">Remaining Budget:</span>
                                <span :class="remainingBudget < 0 ? 'text-red-600' : 'text-green-600'"
                                    x-text="formatRupiah(remainingBudget)"></span>
                            </div>
                        </div>

                        <!-- Warning Alert -->
                        <div x-show="isOverBudget" x-transition class="mt-4 rounded-md bg-red-50 p-4 border border-red-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Over Budget Warning</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>Your planned items exceed your weekly budget!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shopping/Ingredients List -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Planned Ingredients / Items</h3>

                        <!-- Add Item Form -->
                        <div class="flex gap-4 mb-6 items-end bg-gray-50 p-4 rounded-lg">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Item
                                    Name</label>
                                <input type="text" x-model="newItemName" @keydown.enter="addItem()"
                                    placeholder="e.g. Chicken Breast"
                                    class="block w-full border-gray-300 rounded-md sm:text-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div class="w-1/3">
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Price
                                    Estimation (Rp)</label>
                                <input type="number" x-model.number="newItemPrice" @keydown.enter="addItem()"
                                    placeholder="0"
                                    class="block w-full border-gray-300 rounded-md sm:text-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                            <button type="button" @click="addItem()"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Add
                            </button>
                        </div>

                        <!-- Items Table -->
                        <div class="flex flex-col">
                            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                    <div class="overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Item</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Estimated Cost</th>
                                                    <th scope="col" class="relative px-6 py-3">
                                                        <span class="sr-only">Delete</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <template x-for="(item, index) in items" :key="index">
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                            x-text="item.name"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right"
                                                            x-text="formatRupiah(item.price)"></td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <button @click="removeItem(index)"
                                                                class="text-red-600 hover:text-red-900">
                                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <tr x-show="items.length === 0">
                                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                                        No items added yet.
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="bg-gray-50" x-show="items.length > 0">
                                                <tr>
                                                    <td class="px-6 py-3 text-left text-sm font-bold text-gray-900">Total
                                                    </td>
                                                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900"
                                                        x-text="formatRupiah(totalCost)"></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function financeApp() {
            return {
                budget: 0,
                items: [],
                newItemName: '',
                newItemPrice: '',

                get totalCost() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.price) || 0), 0);
                },

                get remainingBudget() {
                    return (this.budget || 0) - this.totalCost;
                },

                get isOverBudget() {
                    return this.remainingBudget < 0;
                },

                addItem() {
                    if (this.newItemName.trim() === '' || !this.newItemPrice) return;

                    this.items.push({
                        name: this.newItemName,
                        price: this.newItemPrice
                    });

                    this.newItemName = '';
                    this.newItemPrice = '';
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                }
            }
        }
    </script>
@endsection