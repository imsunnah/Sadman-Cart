<template>
    <StoreLayout>
        <div class="bg-slate-50 min-h-screen py-20 font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12">
                    <h1
                        class="text-4xl font-black text-slate-900 tracking-tight italic uppercase"
                    >
                        Shopping <span class="text-[#003366]">Cart</span>
                    </h1>
                    <p
                        class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-[10px]"
                    >
                        Review your selection before checkout
                    </p>
                </div>

                <div
                    v-if="isLoading && cart.length === 0"
                    class="flex justify-center py-20"
                >
                    <div
                        class="animate-spin rounded-full h-12 w-12 border-4 border-[#003366] border-t-transparent"
                    ></div>
                </div>

                <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    <div class="lg:col-span-8">
                        <div
                            v-if="cart.length > 0"
                            class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-100 overflow-hidden"
                        >
                            <ul role="list" class="divide-y divide-slate-50">
                                <li
                                    v-for="item in cart"
                                    :key="item.id"
                                    class="flex p-4 sm:p-8 group transition-colors hover:bg-slate-50/50"
                                >
                                    <div
                                        class="flex-shrink-0 w-20 h-20 sm:w-40 sm:h-40 bg-slate-100 rounded-2xl overflow-hidden relative border border-slate-100"
                                    >
                                        <img
                                            v-if="item.product?.image"
                                            :src="item.product.image"
                                            :alt="item.product.name"
                                            class="w-full h-full object-center object-cover group-hover:scale-105 transition-transform duration-500"
                                        />
                                        <div
                                            v-else
                                            class="w-full h-full flex items-center justify-center text-slate-300"
                                        >
                                            <Package class="w-8 h-8 sm:w-12 sm:h-12" />
                                        </div>
                                    </div>

                                    <div class="ml-4 sm:ml-8 flex-1 flex flex-col">
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 sm:gap-0"
                                        >
                                            <div>
                                                <h3
                                                    class="text-xl font-black text-slate-900 italic uppercase tracking-tight"
                                                >
                                                    <Link
                                                        :href="`/products/${item.product?.slug}`"
                                                        class="hover:text-[#003366] transition-colors"
                                                        >{{
                                                            item.product?.name
                                                        }}</Link
                                                    >
                                                </h3>
                                                <p
                                                    class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-[0.2em]"
                                                >
                                                    {{ item.product?.sku }}
                                                </p>
                                            </div>
                                            <p
                                                class="text-2xl font-black text-[#003366]"
                                            >
                                                ৳{{
                                                    parseFloat(
                                                        item.product?.price ||
                                                            0,
                                                    ).toLocaleString()
                                                }}
                                            </p>
                                        </div>
                                        <div
                                            class="mt-4 sm:mt-auto flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 sm:gap-0"
                                        >
                                            <div
                                                class="flex items-center space-x-2 sm:space-x-4 bg-slate-100 p-1.5 rounded-xl"
                                            >
                                                <button
                                                    @click="
                                                        updateQuantity(
                                                            item.id,
                                                            item.quantity - 1,
                                                        )
                                                    "
                                                    class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm hover:text-[#003366] transition-colors"
                                                    :disabled="
                                                        item.quantity <= 1
                                                    "
                                                >
                                                    <Minus class="w-4 h-4" />
                                                </button>
                                                <span
                                                    class="text-sm font-black w-8 text-center"
                                                    >{{ item.quantity }}</span
                                                >
                                                <button
                                                    @click="
                                                        updateQuantity(
                                                            item.id,
                                                            item.quantity + 1,
                                                        )
                                                    "
                                                    class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm hover:text-[#003366] transition-colors"
                                                >
                                                    <Plus class="w-4 h-4" />
                                                </button>
                                            </div>
                                            <button
                                                type="button"
                                                @click="removeFromCart(item.id)"
                                                class="text-[10px] font-black text-red-400 hover:text-red-600 flex items-center uppercase tracking-widest transition-colors"
                                            >
                                                <Trash2 class="w-4 h-4 mr-2" />
                                                Remove Item
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div
                            v-else
                            class="bg-white shadow-2xl shadow-slate-200/50 rounded-3xl border border-slate-100 p-8 sm:p-20 text-center flex flex-col items-center"
                        >
                            <div
                                class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6"
                            >
                                <ShoppingCart
                                    class="w-12 h-12 text-slate-200"
                                />
                            </div>
                            <h2
                                class="text-2xl font-black text-slate-800 italic uppercase"
                            >
                                Your cart is empty
                            </h2>
                            <p
                                class="mt-2 text-slate-400 font-bold uppercase tracking-widest text-[10px]"
                            >
                                Explore our catalog to add items
                            </p>
                            <Link
                                href="/shop"
                                class="mt-10 inline-flex items-center px-10 py-4 bg-[#003366] text-white font-black rounded-full hover:bg-slate-800 transition-all shadow-xl shadow-[#003366]/20 text-[10px] uppercase tracking-widest"
                            >
                                Continue Shopping
                            </Link>
                        </div>
                    </div>

                    <div class="lg:col-span-4" v-if="cart.length > 0">
                        <div
                            class="bg-[#003366] text-white rounded-3xl p-6 sm:p-10 sticky top-24 shadow-2xl shadow-[#003366]/30 overflow-hidden relative"
                        >
                            <div
                                class="absolute -top-20 -right-20 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"
                            ></div>
                            <h2
                                class="text-xs font-black mb-10 border-b border-white/10 pb-6 uppercase tracking-[0.3em]"
                            >
                                Order Architecture
                            </h2>

                            <dl class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <dt
                                        class="text-[10px] font-black text-slate-300 uppercase tracking-widest"
                                    >
                                        Subtotal
                                    </dt>
                                    <dd class="text-lg font-black">
                                        ৳{{ cartTotal.toLocaleString() }}
                                    </dd>
                                </div>
                                <div
                                    class="flex items-center justify-between border-t border-white/10 pt-6"
                                >
                                    <dt class="flex flex-col">
                                        <span
                                            class="text-[10px] font-black text-slate-300 uppercase tracking-widest"
                                            >Shipping</span
                                        >
                                        <span
                                            class="text-[8px] font-black text-[#FF6600] mt-1 italic uppercase tracking-widest"
                                            >Cash on Delivery</span
                                        >
                                    </dt>
                                    <dd
                                        class="text-[10px] font-black uppercase tracking-widest italic"
                                    >
                                        Calculated next
                                    </dd>
                                </div>
                                <div
                                    class="flex items-center justify-between border-t border-white/20 pt-8 mt-4"
                                >
                                    <dt
                                        class="text-lg font-black italic uppercase tracking-tighter"
                                    >
                                        Total Payable
                                    </dt>
                                    <dd
                                        class="text-3xl font-black text-[#FF6600]"
                                    >
                                        ৳{{ cartTotal.toLocaleString() }}
                                    </dd>
                                </div>
                            </dl>

                            <div class="mt-12">
                                <Link
                                    href="/checkout"
                                    class="w-full flex items-center justify-center rounded-2xl bg-white px-8 py-5 text-[10px] font-black text-[#003366] shadow-xl hover:bg-[#FF6600] hover:text-white transition-all uppercase tracking-[0.3em]"
                                >
                                    Proceed To Checkout
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import StoreLayout from "@/Layouts/StoreLayout.vue";
import { Package, Trash2, ShoppingCart, Plus, Minus } from "lucide-vue-next";
import { useCart } from "@/Composables/useCart";

const { cart, removeFromCart, updateQuantity, cartTotal, isLoading } =
    useCart();
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 51, 102, 0.05);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 51, 102, 0.1);
}
</style>
