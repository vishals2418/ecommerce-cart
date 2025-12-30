<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    cart: {
        type: Object,
        required: true,
    },
    items: {
        type: Array,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },
});

const updating = ref({});
const quantities = ref({});

// Initialize quantities from props
props.items.forEach((item) => {
    quantities.value[item.id] = item.quantity;
});

const updateQuantity = (itemId, productId) => {
    const quantity = parseInt(quantities.value[itemId]);
    
    if (isNaN(quantity) || quantity < 1) {
        quantities.value[itemId] = 1;
        return;
    }

    updating.value[itemId] = true;

    router.patch(
        route('cart.update', itemId),
        { quantity: quantity },
        {
            preserveScroll: true,
            onSuccess: () => {
                updating.value[itemId] = false;
            },
            onError: (errors) => {
                updating.value[itemId] = false;
                alert(errors.stock || 'Failed to update cart');
            },
        }
    );
};

const removeItem = (itemId) => {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        router.delete(route('cart.remove', itemId), {
            preserveScroll: true,
        });
    }
};

const checkout = () => {
    if (props.items.length === 0) {
        alert('Your cart is empty!');
        return;
    }

    if (confirm('Proceed to checkout?')) {
        router.post(route('orders.checkout'), {}, {
            onError: (errors) => {
                alert(errors.cart || errors.stock || 'Checkout failed. Please try again.');
            },
        });
    }
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
    <Head title="Shopping Cart" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Shopping Cart
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    v-if="items.length === 0"
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-center text-gray-900 dark:text-gray-100">
                        <p class="mb-4 text-lg">Your cart is empty.</p>
                        <Link :href="route('shop')">
                            <PrimaryButton>Continue Shopping</PrimaryButton>
                        </Link>
                    </div>
                </div>

                <div v-else class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
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
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300"
                                    >
                                        Quantity
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300"
                                    >
                                        Price
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300"
                                    >
                                        Subtotal
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300"
                                    >
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800"
                            >
                                <tr
                                    v-for="item in items"
                                    :key="item.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700"
                                >
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        {{ item.product_name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center space-x-2">
                                            <TextInput
                                                v-model="quantities[item.id]"
                                                type="number"
                                                min="1"
                                                class="w-20"
                                                @blur="updateQuantity(item.id, item.product_id)"
                                                @keyup.enter="updateQuantity(item.id, item.product_id)"
                                            />
                                            <span
                                                v-if="updating[item.id]"
                                                class="text-xs text-gray-400"
                                            >
                                                Updating...
                                            </span>
                                        </div>
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        {{ formatPrice(item.product_price) }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        {{ formatPrice(item.subtotal) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <DangerButton
                                            @click="removeItem(item.id)"
                                            class="text-xs"
                                        >
                                            Remove
                                        </DangerButton>
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
                                        class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100"
                                    >
                                        {{ formatPrice(total) }}
                                    </td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="border-t border-gray-200 p-6 dark:border-gray-700">
                        <div class="flex justify-end space-x-4">
                            <Link :href="route('shop')">
                                <PrimaryButton class="bg-gray-600 hover:bg-gray-700">
                                    Continue Shopping
                                </PrimaryButton>
                            </Link>
                            <PrimaryButton @click="checkout">
                                Proceed to Checkout
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

