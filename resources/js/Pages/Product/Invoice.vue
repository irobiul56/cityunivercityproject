<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ref, onMounted } from 'vue';  // Import onMounted
import { ElMessage } from "element-plus";
import { defineProps } from 'vue';

const isPrinting = ref(false);

function printInvoice() {
    isPrinting.value = true;
    setTimeout(() => {
        window.print();
        isPrinting.value = false;
    }, 100);
}

defineProps({
    invoice: Object
});

// Automatically call printInvoice when the page loads
onMounted(() => {
    printInvoice();
});
</script>

<template lang="">
    <Head title="Invoice Print"/>
    <FrontendLayout>
    

        <div class="container mx-auto p-6">
            <!-- Customer Details -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h1 class="text-2xl font-semibold mb-6">Invoice #{{ invoice.id }}</h1>
                <h2 class="text-xl font-semibold mb-4">Customer Details</h2>
                <p><strong>Name:</strong> {{ invoice.customer.name }}</p>
                <p><strong>Email:</strong> {{ invoice.customer.email }}</p>
                <p><strong>Phone:</strong> {{ invoice.customer.phone }}</p>
                <p><strong>Address:</strong> {{ invoice.customer.address }}</p>

            <!-- Invoice Details -->
                <h2 class="text-xl font-semibold mb-4">Invoice Details</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="p-4">Product</th>
                            <th class="p-4">Quantity</th>
                            <th class="p-4">Price</th>
                            <th class="p-4">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in invoice.invoice_items" :key="index" class="border-b">
                            <td class="p-4">{{ item.name }}</td>
                            <td class="p-4">{{ item.qnt }}</td>
                            <td class="p-4">{{ item.price }}</td>
                            <td class="p-4">${{ (item.qnt * item.price).toFixed(2) }}</td>
                        </tr>
                    </tbody>
                </table>
           

            <!-- Payment Summary -->
                <h2 class="text-xl font-semibold mb-4">Payment Summary</h2>
                <table class="w-full">
                    <tr>
                        <td class="p-4 border-b"><strong>Total Quantity</strong></td>
                        <td class="p-4 border-b">{{invoice.total_qnt}}</td>
                    </tr>
                    <tr>
                        <td class="p-4 border-b"><strong>Sub Total</strong></td>
                        <td class="p-4 border-b">{{invoice.sub_total}}</td>
                    </tr>
                    <tr>
                        <td class="p-4 border-b"><strong>Tax (5%)</strong></td>
                        <td class="p-4 border-b">{{invoice.tax}}</td>
                    </tr>
                    <tr>
                        <td class="p-4 border-b"><strong>Discount</strong></td>
                        <td class="p-4 border-b">{{invoice.discount}}</td>
                    </tr>
                    <tr>
                        <td class="p-4 border-b"><strong>Total</strong></td>
                        <td class="p-4 border-b">{{invoice.total}}</td>
                    </tr>
                    <tr>
                        <td class="p-4"><strong>Payment Type</strong></td>
                        <td class="p-4">{{invoice.payment_type}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </FrontendLayout>
</template>

<style scoped>

.container {
    max-width: 800px;
    margin: 0 auto;
}

/* Hide print button when printing */
@media print {
    .no-print {
        display: none;
    }
}
</style>
