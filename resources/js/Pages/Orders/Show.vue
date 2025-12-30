<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    order: {
        type: Object,
        required: true,
    },
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
    <Head title="Order Confirmation" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Order Confirmation
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Success Message -->
                <div
                    v-if="$page.props.flash?.success"
                    class="mb-6 rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-800 dark:text-green-200"
                >
                    {{ $page.props.flash.success }}
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6">
                        <!-- Order Header -->
                        <div class="mb-6 border-b border-gray-200 pb-4 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Order #{{ order.id }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Placed on {{ order.created_at }}
                            </p>
                        </div>

                        <!-- Order Items -->
                        <div v-if="order.items && order.items.length > 0">
                            <h4 class="mb-4 text-md font-semibold text-gray-900 dark:text-gray-100">
                                Order Items
                            </h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300"
                                            >
                                                Product Name
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300"
                                            >
                                                Quantity
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300"
                                            >
                                                Price
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300"
                                            >
                                                Subtotal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800"
                                    >
                                        <tr
                                            v-for="item in order.items"
                                            :key="item.id"
                                            class="hover:bg-gray-50 dark:hover:bg-gray-700"
                                        >
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100"
                                            >
                                                {{ item.product_name }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400"
                                            >
                                                {{ item.quantity }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-500 dark:text-gray-400"
                                            >
                                                {{ formatPrice(item.price) }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-100"
                                            >
                                                {{ formatPrice(item.subtotal) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <td
                                                colspan="3"
                                                class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-gray-100"
                                            >
                                                Total:
                                            </td>
                                            <td
                                                class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-gray-100"
                                            >
                                                {{ formatPrice(order.total_price) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div
                            v-else
                            class="py-8 text-center text-gray-500 dark:text-gray-400"
                        >
                            <p>No items in this order.</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-end space-x-4">
                            <Link :href="route('shop')">
                                <PrimaryButton>Continue Shopping</PrimaryButton>
                            </Link>
                            <Link :href="route('orders.index')">
                                <PrimaryButton class="bg-gray-600 hover:bg-gray-700">
                                    View All Orders
                                </PrimaryButton>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

