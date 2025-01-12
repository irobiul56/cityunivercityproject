<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ref, computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import { ElMessage, ElDialog, ElButton } from 'element-plus';

const { props } = usePage()
const products = ref(props.product)

const itemsPerPage = ref(10)
const currentPage = ref(1)
const searchQuery = ref('')

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


defineProps({
  errors: Object
})

const form = useForm({});

// const deleteProduct = (productId) => {
//   if (confirm("Are you sure you want to delete this data?")) {
//     form.delete(route('product.destroy', productId));
//   }
// }

const deleteProduct = (productId) => {
    if (confirm("Are you sure you want to delete this data?")) {
        form.delete(route('product.destroy', productId), {
            onSuccess: (page) => {
                products.value = page.props.product; // Update the rooms list after deletion
                ElMessage.success("Product deleted successfully!");
            },
            onError: () => {
                ElMessage.error("Failed to delete the Product. Please try again.");
            },
        });
    }
};


// Initialize the selected product data for the modal
const selectedProduct = ref({
  name: '',
  description: '',
  price: '',
  stock: '',
  image: '',
  storage: ''
});

// Modal visibility
const isModalVisible = ref(false);

// Method to show the modal and set the product data
const showProduct = (product) => {
  selectedProduct.value = product;
  isModalVisible.value = true;
};

// Method to close the modal
const closeModal = () => {
  isModalVisible.value = false;
};

</script>

<template>
  <FrontendLayout>
    <div v-if="$page.props.flash?.message"
      class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 alert">
      {{ $page.props.flash?.message }}
    </div>

    <Head title="Product page"></Head>
    <div class="container mx-auto">
      <div class="relative mx-4 lg:mx-0 mb-2">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-gray-500"
            viewBox="0 0 24 24" fill="none">
            <path
              d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            </path>
          </svg></span>
        <input v-model="searchQuery"
          class="w-32 pl-10 pr-4 text-indigo-600 border-gray-200 rounded-md sm:w-64 focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
          type="text" placeholder="Search">
        <Link :href="route('product.create')">
        <span class="ml-5 bg-blue-500 text-white px-4 py-2 rounded inline-block"> Item Add </span>
        </Link>

      </div>

      <table class="min-w-full bg-white shadow-md rounded-lg">
        <thead>
          <tr class="text-left">
            <th class="py-2 px-4 border-b-2">#</th>
            <th class="py-2 px-4 border-b-2">Title</th>
            <th class="py-2 px-4 border-b-2">Description</th>
            <th class="py-2 px-4 border-b-2">Storage Location</th>
            <th class="py-2 px-4 border-b-2">Purchase Date</th>
            <th class="py-2 px-4 border-b-2">Qnt</th>
            <th class="py-2 px-4 border-b-2">Status</th>
            <th class="py-2 px-4 border-b-2">Action</th>

          </tr>
        </thead>
        <tbody>
          <tr v-for="(product, index) in filteredProduct" :key="index" class="hover:bg-gray-100">
            <td class="py-2 px-4 border-b">{{ index + 1 }}</td>
            <!-- <td class="py-2 px-4 border-b"><img
                style="width: 50px; height: 50px; border: 1px solid gray; border-radius: 50%;"
                :src="`/images/${product.image}`" alt="Product image" /></td> -->
            <td class="py-2 px-4 border-b">{{ product.name }}</td>
            <td class="py-2 px-4 border-b">{{ product.description }}</td>
            <td class="py-2 px-4 border-b"> {{ product.storage ? product.storage.name : 'N/A' }}</td>
            <!-- <td class="py-2 px-4 border-b" v-html="product.barcode_html"></td> -->
            <td class="py-2 px-4 border-b">{{ new Date(product.created_at).toLocaleDateString('en-GB') }}</td>
            <td class="py-2 px-4 border-b">{{ product.stock }}</td>
            
            <td class="py-2 px-4 border-b"> 
              <div class="flex gap-2">
                <el-tag v-if="product.stock <= 0" type="danger">Unavilable</el-tag>
                <el-tag v-if="product.stock > 0" type="success">Available</el-tag>
              </div>
            </td>
            <td class="py-6 px-4 border-b flex">
              <el-button @click="showProduct(product)">
                <svg class="w-6 h-6 text-green-600 dark:text-white" xmlns="http://www.w3.org/2000/svg" width="24"
                  height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-width="2"
                    d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                  <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
              </el-button>


              <Link :href="route('product.edit', product.id)">
              <svg class="w-6 h-6 text-blue-400 dark:text-white ml-5" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
              </svg>
              </Link>

              <Link @click="deleteProduct(product.id)">
              <svg class="w-6 h-6 text-red-400 dark:text-white ml-5" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              </Link>
            </td>
          </tr>
        </tbody>

      </table>
      <tfoot class="flex items-center mt-4">

        <button class="py-2 px-4 bg-gray-300 hover:bg-gray-400 rounded" :disabled="currentPage === 1" @click="prevPage">
          Previous
        </button>
        <span class="ml-2">Page {{ currentPage }} of {{ totalPages }}</span>
        <button class="py-2 px-4 bg-gray-300 hover:bg-gray-400 rounded ml-2" :disabled="currentPage === totalPages"
          @click="nextPage">
          Next
        </button>
      </tfoot>
    </div>

    <!-- Modal -->
    <el-dialog title="Product Details" :model-value="isModalVisible" @close="closeModal" width="40%"
      class="custom-modal">
      <div class="modal-content">
        <div class="product-image" v-if="selectedProduct.image">
          <img :src="`/images/${selectedProduct.image}`" alt="Product Image" class="image-preview" />
        </div>
        <div class="product-details">
          <h2>{{ selectedProduct.name }}</h2>
          <p class="description">{{ selectedProduct.description }}</p>
          <div class="price-stock">
            <span class="price"><strong>Price:</strong> <span style="font-size: 16px; font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;">৳</span>{{selectedProduct.price }}</span>
            <span class="stock"><strong>Stock:</strong> {{ selectedProduct.stock }}</span>
          </div>
          <p class="description"><strong>Storage Location:</strong> {{ selectedProduct.storage.name }}</p>
          <p class="description"> {{ selectedProduct.storage.address }}</p>
        </div>
      </div>

      <!-- Modal footer -->
      <template #footer>
        <el-button @click="closeModal" class="close-button">Close</el-button>
      </template>
    </el-dialog>
  </FrontendLayout>
</template>

<style scoped>
.product-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.show-product-button {
  background-color: #409eff;
  color: white;
  border-radius: 5px;
  padding: 0.5rem 1rem;
}

.show-product-button:hover {
  background-color: #66b1ff;
}

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

.product-image {
  width: 50%;
  margin-bottom: 1rem;
}

.image-preview {
  max-width: 100%;
  height: auto;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.product-details {
  width: 100%;
  text-align: left;
}

.product-details h2 {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.description {
  font-size: 1rem;
  margin-bottom: 1rem;
  color: #666;
}

.price-stock {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
}

.price,
.stock {
  font-size: 1rem;
  font-weight: 500;
}

.category {
  font-size: 1rem;
  font-weight: 500;
  color: #999;
}

.close-button {
  background-color: #f56c6c;
  color: white;
  border-radius: 5px;
  padding: 0.5rem 1rem;
}

.close-button:hover {
  background-color: #f78989;
}
</style>
