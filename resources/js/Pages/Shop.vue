<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    products: {
        type: Array,
        required: true,
    },
});

const addingToCart = ref({});
const addedToCart = ref({});

const addToCart = (productId) => {
    addingToCart.value[productId] = true;
    addedToCart.value[productId] = false;

    router.post(
        route('cart.add'),
        { product_id: productId },
        {
            preserveScroll: true,
            onSuccess: () => {
                addingToCart.value[productId] = false;
                addedToCart.value[productId] = true;
                
                // Reset "Added!" message after 2 seconds
                setTimeout(() => {
                    addedToCart.value[productId] = false;
                }, 2000);
            },
            onError: (errors) => {
                addingToCart.value[productId] = false;
                alert(errors.stock || 'Failed to add product to cart');
            },
        }
    );
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
    <Head title="Shop" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Shop
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    v-if="products.length === 0"
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        No products available.
                    </div>
                </div>

                <div
                    v-else
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="overflow-hidden rounded-lg bg-white shadow-md transition-shadow duration-300 hover:shadow-lg dark:bg-gray-800"
                    >
                        <div class="p-6">
                            <h3
                                class="mb-2 text-lg font-semibold text-gray-900 dark:text-gray-100"
                            >
                                {{ product.name }}
                            </h3>

                            <div class="mb-4">
                                <p
                                    class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                                >
                                    {{ formatPrice(product.price) }}
                                </p>
                                <p
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                    :class="{
                                        'text-red-600 dark:text-red-400':
                                            product.stock_quantity === 0,
                                        'text-green-600 dark:text-green-400':
                                            product.stock_quantity > 0,
                                    }"
                                >
                                    Stock: {{ product.stock_quantity }}
                                </p>
                            </div>

                            <PrimaryButton
                                @click="addToCart(product.id)"
                                :disabled="
                                    product.stock_quantity === 0 ||
                                    addingToCart[product.id]
                                "
                                class="w-full"
                                :class="{
                                    'opacity-50 cursor-not-allowed':
                                        product.stock_quantity === 0,
                                }"
                            >
                                <span v-if="addingToCart[product.id]">
                                    Adding...
                                </span>
                                <span v-else-if="addedToCart[product.id]">
                                    ✓ Added!
                                </span>
                                <span v-else>Add to Cart</span>
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

