<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { ElMessage } from "element-plus";

const { props } = usePage()
const products = ref(props.product)

const cart = ref([]);

const itemsPerPage = ref(8)
const currentPage = ref(1)
const searchQuery = ref('')
const searchBarCode = ref('')  // Holds the barcode search value

const filteredProduct = computed(() => {
    const filtered = products.value.filter(product =>
        product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
    const start = (currentPage.value - 1) * itemsPerPage.value
    const end = start + itemsPerPage.value
    return filtered.slice(start, end)
})

const totalPages = computed(() => {
    return Math.ceil(
        products.value.filter(product =>
            product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        ).length / itemsPerPage.value
    )
})

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++
    }
}

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--
    }
}

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

const subTotal = computed(() => {
    return cart.value.reduce((total, item) => total + (item.price * item.qnt), 0);
});

const tax = computed(() => {
    return subTotal.value * 0.05; // Assuming 5% tax rate
});

const discount = ref(0); // You can modify this based on user input or other logic

// Watch for changes in searchBarCode and add matching product to cart
watch(searchBarCode, (newValue) => {
    if (newValue) {
        const product = products.value.find(product => product.sku === newValue);
        if (product) {
            addToCart(product);  // Add product to cart if barcode matches
            searchBarCode.value = '';  // Clear the barcode input after adding to cart
        }
    }
});

const total = computed(() => {
    return subTotal.value + tax.value - discount.value;
});


// Modal visibility
const isModalVisible = ref(false);

// Form to add a new customer
const customerForm = useForm({
    name: '',
    email: '',
    phone: '',
    address: ''
});

const AddCustomer = () => {
    isModalVisible.value = true;
};

// Method to submit the customer details
const submitCustomer = () => {
    customerForm.post(route('customer.store'), {
        onSuccess: (page) => {
            isModalVisible.value = false; // Close the modal on success
            options.value = page.props.customers; // Load customer options dynamically
            // Reset the form
            customerForm.reset();
            ElMessage.success("Customer Added successfully!");
        },
        onError: () => {
            ElMessage.error("Failed to add the customer. Please try again.");
        },
    });
};

// Method to close the modal
const closeModal = () => {
    isModalVisible.value = false;
};

//Customer Section
const value = ref('');
const options = ref(props.customers || []); // Load customers dynamically
const radio = ref('cash');


//invoice setup
const invoiceForm = useForm({
    invoice_items: [], // Cart items with name, quantity, price
    total_qnt: 0,     // Total quantity
    sub_total: 0,     // Subtotal
    tax: 0,           // Tax
    discount: 0,      // Discount
    total: 0,         // Final total
    payment_type: '', // Payment method (cash, card, mobile banking)
    customer_id: null // Selected customer ID
});

const handleInvoicePrint = () => {
    invoiceForm.invoice_items = cart.value.map(item => ({
        name: item.name,
        qnt: item.qnt,
        price: item.price
    }));

    invoiceForm.total_qnt = totalQuantity.value;
    invoiceForm.sub_total = subTotal.value;
    invoiceForm.tax = tax.value;
    invoiceForm.discount = discount.value;
    invoiceForm.total = total.value;
    invoiceForm.payment_type = radio.value; // Payment type (cash, card, etc.)

    // Find customer by phone number (you could adjust this based on your form input)
    const customer = options.value.find(option => option.phone === value.value);
    invoiceForm.customer_id = customer ? customer.id : null;

    // Submit the form using Inertia
    invoiceForm.post(route('requisition.store'), {
        onSuccess: () => {
            ElMessage.success("Invoice generated and printed successfully!");
            // Handle success (e.g., redirect to a success page or reset the form)
        },
        onError: () => {
            ElMessage.error("Failed to generate the invoice.");
        }
    });
};


defineProps({
    errors: Object
})

</script>

<template>
    <FrontendLayout>

        <Head title="Point Of Sell"></Head>
        <input v-model="searchBarCode" type="text" class="w-full p-2 border border-gray-300 rounded-lg mb-4"
            placeholder="Search with barcode...">
        <div v-if="$page.props.flash.message"
            class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 alert">
            {{ $page.props.flash.message }}
        </div>

        <div class="bg-gray-100 p-4">
            <!-- Main section -->
            <div class="flex flex-wrap -mx-3">
                <!-- Product cart table (30% width) -->
                <div class="w-full md:w-2/5 px-3 mb-6 md:mb-0">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-semibold mb-4">Cart</h2>
                        <el-table class="curser-pointer" :data="cart" stripe style="width: 100%">
                            <el-table-column prop="name" label="Name" />
                            <el-table-column prop="qnt" label="Qty">
                                <template #default="{ row }">
                                    <el-input-number prop="qnt" v-model="row.qnt" min="1" style="width: 73px;"
                                        size="small" @change="updateCart" />
                                </template>
                            </el-table-column>
                            <el-table-column prop="price" label="Price" />
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
                            <tr>
                                <td colspan="2" class="border-b-2 p-2">Sub Total</td>
                                <td class="border-b-2 p-2">${{ subTotal.toFixed(2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="border-b-2 p-2">Tax</td>
                                <td class="border-b-2 p-2">${{ tax.toFixed(2) }}</td>
                            </tr>
                            <tr>
                                <td class="border-b-2 p-2">Discount</td>
                                <td class="border-b-2 p-2"><input v-model="discount"
                                        class="w-20 text-center border border-gray-300 rounded" value="0" type="number">
                                </td>
                                <td class="border-b-2 p-2">${{ discount }}</td>
                            </tr>

                            <tr>
                                <td colspan="2" class="border-b-2 p-2">Total</td>
                                <td class="border-b-2 p-2">${{ total.toFixed(2) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg mt-3">
                        <span> Coustomer: </span>
                        <el-select v-model="value" filterable placeholder="Search Phone Number" style="max-width: 60%">
                            <el-option v-for="(item, index) in options" :key="index" :label="item.label"
                                :value="item.phone">
                                <span style="float: left">{{ item.name }}</span>
                                <span style="float: right; color: var(--el-text-color-secondary); font-size: 10px;">
                                    {{ item.phone }}
                                </span>
                            </el-option>
                        </el-select>
                        <el-button class=" ml-3" @click="AddCustomer" type="success">Add</el-button>
                        <div class="mt-5">
                            <span>Payment Option: </span>
                            <el-radio-group v-model="radio">
                                <el-radio :value="'cash'">Cash</el-radio>
                                <el-radio :value="'card'">Card</el-radio>
                                <el-radio :value="'mobile banking'">Mobile Banking</el-radio>
                            </el-radio-group>

                        </div>
                        <el-button target="_blank" type="primary" @click="handleInvoicePrint"
                            class="success mt-5">Invoice Print</el-button>
                    </div>
                </div>

                <!-- Product search and category navbar + product cards (70% width) -->
                <div class="w-full md:w-3/5 px-3">
                    <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                        <input v-model="searchQuery" type="text"
                            class="w-full p-2 border border-gray-300 rounded-lg mb-4" placeholder="Search products...">
                        <nav class="flex space-x-4 mb-6">
                            <a href="#" class="text-blue-500 hover:underline">Category 1</a>
                            <a href="#" class="text-blue-500 hover:underline">Category 2</a>
                            <a href="#" class="text-blue-500 hover:underline">Category 3</a>
                            <!-- Add more categories as needed -->
                        </nav>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 cursor-pointer">
                            <!-- Example product card -->
                            <el-card @click="addToCart(item)" v-for="(item, index) in filteredProduct" :key="index"
                                style="max-width: 480px" shadow="hover">
                                <img :src="`/images/${item.image}`" alt="Product Image"
                                    class="w-full h-30 object-cover rounded">
                                <template #footer>
                                    <p><b>{{ item.name }}</b></p>
                                    <p>Price: ${{ item.price }}</p>

                                </template>
                            </el-card>

                            <!-- Add more product cards as needed, up to 12 total -->
                        </div>
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

        <!-- Modal -->
        <el-dialog title="Customer Details" :model-value="isModalVisible" @close="closeModal" width="40%"
            class="custom-modal">

            <div class="modal-content">
                <el-input class="mt-5" v-model="customerForm.name" style="max-width: 350px"
                    placeholder="Enter customer name">
                    <template #prepend>Name</template>
                </el-input>

                <el-input class="mt-3" v-model="customerForm.email" style="max-width: 350px"
                    placeholder="Enter customer email">
                    <template #prepend>Email</template>
                </el-input>

                <el-input class="mt-3" v-model="customerForm.phone" style="max-width: 350px"
                    placeholder="Enter customer phone">
                    <template #prepend>Phone</template>
                </el-input>

                <el-input class="mt-3" v-model="customerForm.address" style="max-width: 350px"
                    :autosize="{ minRows: 2, maxRows: 4 }" type="textarea" placeholder="Enter customer address" />
            </div>

            <!-- Modal footer -->
            <template #footer>
                <el-button @click="submitCustomer" :disabled="customerForm.processing" type="primary"
                    native-type="submit">
                    <span v-if="customerForm.processing"> Submiting....</span>
                    <span v-else> Submit</span>
                </el-button>
                <el-button @click="closeModal" type="danger">Close</el-button>
            </template>
        </el-dialog>
        
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