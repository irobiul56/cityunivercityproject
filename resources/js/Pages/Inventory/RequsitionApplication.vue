<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { ElMessage } from "element-plus";

const { props } = usePage();
const products = ref(props.product);

const cart = ref([]);

const itemsPerPage = ref(8);
const currentPage = ref(1);
const searchQuery = ref('');
const searchBarCode = ref('');  // Holds the barcode search value

const filteredProduct = computed(() => {
    const filtered = products.value.filter(product =>
        product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filtered.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(
        products.value.filter(product =>
            product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        ).length / itemsPerPage.value
    );
});

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const addToCart = (product) => {
    const cartItem = cart.value.find(item => item.id === product.id);
    if (cartItem) {
        cartItem.qnt++;
    } else {
        cart.value.push({ ...product, qnt: 1 });
    }
};

const removeFromCart = (product) => {
    cart.value = cart.value.filter(item => item.id !== product.id);
};

const totalQuantity = computed(() => {
    return cart.value.reduce((total, item) => total + item.qnt, 0);
});

// Update the quantity in the cart
const updateCart = (row) => {
    const cartItem = cart.value.find(item => item.id === row.id);
    if (cartItem) {
        cartItem.qnt = row.qnt;  // Update the quantity of the item
    }
};

// Invoice setup
const invoiceForm = useForm({
    invoice_items: [], // Cart items with name, quantity
    total_qnt: 0,     // Total quantity
    description: '',     // description

});

const handleInvoicePrint = () => {
    invoiceForm.invoice_items = cart.value.map(item => ({
        name: item.name,
        id: item.id,
        qnt: item.qnt,
    }));

    invoiceForm.total_qnt = totalQuantity.value;

    // Submit the form using Inertia
    invoiceForm.post(route('requisition.store'), {
        onSuccess: () => {
            ElMessage.success("Submited successfully!");
            // Handle success (e.g., redirect to a success page or reset the form)
        },
        onError: () => {
            ElMessage.error("Failed to generate the invoice.");
        }
    });
};

defineProps({
    errors: Object
});
</script>

<template>
    <FrontendLayout>

        <Head title="Point Of Sell"></Head>
        <div v-if="$page.props.flash?.message"
            class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 alert">
            {{ $page.props.flash?.message }}
        </div>

        <div class="bg-gray-100 p-4">
            <!-- Main section -->
            <div class="flex flex-wrap -mx-3">
                <!-- Product cart table (30% width) -->
                <div class="w-full md:w-3/6 px-3 mb-6 md:mb-0">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-semibold mb-4">Requisition Form</h2>
                        <el-table class="curser-pointer" :data="cart" stripe style="width: 100%">
                            <el-table-column prop="name" label="Name" />
                            <el-table-column prop="qnt" label="Qty">
                                <template #default="{ row }">
                                    <el-input-number prop="qnt" v-model="row.qnt" min="1" style="width: 100px;"
                                        size="small" @change="updateCart(row)" />
                                </template>
                            </el-table-column>
                            <el-table-column prop="action" label="Action">
                                <template #default="{ row }">
                                    <button @click="removeFromCart(row)"
                                        class="w-5 h-5 flex items-center justify-center bg-red-600 hover:bg-gray-400 text-white rounded ">
                                        - </button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-lg mt-3">
                        <h2 class="text-2xl font-semibold mb-3"></h2>
                        <table class="w-full text-left">
                            <tr>
                                <td colspan="2" class="border-b-2 p-2">Total Quantity</td>
                                <td class="border-b-2 p-2"> {{ totalQuantity }} </td>
                            </tr>
                        </table>

                        <el-input v-model="invoiceForm.description" style="width: 100%; margin-top: 5px;" :rows="3" type="textarea"
                            placeholder="Write the requsition details" />

                        <el-button target="_blank" type="primary" @click="handleInvoicePrint"
                            class="success mt-5">Submit</el-button>
                    </div>
                </div>

                <!-- Product search and category navbar + product cards (70% width) -->
                <div class="w-full md:w-3/6 px-3">
                    <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                        <input v-model="searchQuery" type="text"
                            class="w-full p-2 border border-gray-300 rounded-lg mb-4" placeholder="Search products...">
                        <el-table :data="filteredProduct" style="width: 100%" @row-click="addToCart">
                            <el-table-column prop="image" label="Image" width="70">
                                <template #default="scope">
                                    <img :src="`/images/${scope.row.image}`" alt="Product Image"
                                        class="w-full h-10 object-cover rounded">
                                </template>
                            </el-table-column>

                            <el-table-column prop="name" label="Item Name">
                                <template #default="scope">
                                    <b>{{ scope.row.name }}</b>
                                </template>
                            </el-table-column>

                            <el-table-column prop="description" label="Item Description">
                                <template #default="scope">
                                    <b>{{ scope.row.description }}</b>
                                </template>
                            </el-table-column>

                            <el-table-column prop="stock" label="Status">
                                <template #default="scope">
                                    <el-tag type="success" v-if="scope.row.stock > 0"> <b>{{ scope.row.stock }} Available</b></el-tag>
                                    <el-tag type="danger" v-if="scope.row.stock <= 0"> Unvailable</el-tag>
                                </template>
                            </el-table-column>

                        </el-table>
                    </div>

                    <tfoot class="flex items-center mt-4">
                        <button class="py-2 px-4 bg-gray-300 hover:bg-gray-400 rounded" :disabled="currentPage === 1"
                            @click="prevPage">
                            Previous
                        </button>
                        <span class="ml-2">Page {{ currentPage }} of {{ totalPages }}</span>
                        <button class="py-2 px-4 bg-gray-300 hover:bg-gray-400 rounded ml-2"
                            :disabled="currentPage === totalPages" @click="nextPage">
                            Next
                        </button>
                    </tfoot>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<style scoped>
/* Modal Styles */
.custom-modal .el-dialog__title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
    text-align: center;
    margin-bottom: 1rem;
}

.modal-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>
