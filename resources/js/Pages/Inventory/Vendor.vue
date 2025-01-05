<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ref, computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3';
import { ElMessage } from "element-plus";

const { props } = usePage()
const vendors = ref(props.vendor)

const itemsPerPage = ref(10)
const currentPage = ref(1)
const searchQuery = ref('')

const filteredvendor = computed(() => {
  const filtered = vendors.value.filter(vendor => 
  vendor.name.toLowerCase().includes(searchQuery.value.toLowerCase()) 
  )
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filtered.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(
    vendors.value.filter(vendor => 
    vendor.name.toLowerCase().includes(searchQuery.value.toLowerCase()) 
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



// Modal visibility
const isModalVisible = ref(false);

// Form to add a new customer
const vendorForm = useForm({
    name: '',
    company_name: '',
    phone: '',
    address: '',
});

const Addvendor = () => {
    isModalVisible.value = true;
};


// Method to open modal with permission data for editing
const editvendor = (vendor) => {
    vendorForm.id = vendor.id; // Set the ID to track the permission being edited
    vendorForm.name = vendor.name; // Populate form with existing data
    vendorForm.company_name = vendor.company_name;
    vendorForm.address = vendor.address;
    vendorForm.phone = vendor.phone;
  isModalVisible.value = true;
};

const submitvendor = () => {
    // Determine if we are updating (PUT) or creating (POST)
    const isUpdate = Boolean(vendorForm.id);
    const routeName = isUpdate ? 'vendor.update' : 'vendor.store';
    const routeParams = isUpdate ? [vendorForm.id] : [];
    const method = isUpdate ? 'put' : 'post'; // Use PUT for update, POST for create

    // Submit the form with appropriate HTTP method
    vendorForm[method](route(routeName, ...routeParams), {
        onSuccess: (page) => {
            isModalVisible.value = false; // Close modal on success
            vendors.value = page.props.vendor; // Refresh permissions list
            vendorForm.reset(); // Reset form fields
            vendorForm.id = null; // Reset ID for next add
            const message = isUpdate ? "vendor updated successfully!" : "vendor added successfully!";
            ElMessage.success(message);
        },
        onError: () => {
            ElMessage.error("Failed to submit the vendor. Please try again.");
        }
    });
};



// Method to close the modal
const closeModal = () => {
    isModalVisible.value = false;
};

const deletevendor = (vendorId) => {
    if (confirm("Are you sure you want to delete this data?")) {
        vendorForm.delete(route('vendor.destroy', vendorId), {
            onSuccess: (page) => {
                vendors.value = page.props.vendor; // Update the vendor list after deletion
                ElMessage.success("vendor deleted successfully!");
            },
            onError: () => {
                ElMessage.error("Failed to delete the vendor. Please try again.");
            },
        });
    }
};


</script>

<template>
    <FrontendLayout>
        <div v-if="$page.props.flash?.message" class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 alert">
        {{ $page.props.flash?.message }}
      </div>
      
        <Head title="vendor page"></Head>
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
                    <el-button class=" ml-3" @click="Addvendor" type="success">Add</el-button>

            </div>

            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="text-left">
                        <th class="py-2 px-4 border-b-2">#</th>
                        <th class="py-2 px-4 border-b-2">Vendor Name</th>
                        <th class="py-2 px-4 border-b-2">Company Name</th>
                        <th class="py-2 px-4 border-b-2">Address</th>
                        <th class="py-2 px-4 border-b-2">Phone</th>
                        <th class="py-2 px-4 border-b-2">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(vendor, index) in filteredvendor" :key="index" class="hover:bg-gray-100">
                        <td class="py-2 px-4 border-b">{{ index + 1 }}</td>
                        <td class="py-2 px-4 border-b">{{ vendor.name }}</td>
                        <td class="py-2 px-4 border-b">{{ vendor.company_name }}</td>
                        <td class="py-2 px-4 border-b">{{ vendor.address }}</td>
                        <td class="py-2 px-4 border-b">{{ vendor.phone }}</td>
                        
                        <td class="py-2 px-4 border-b flex">

                           <!-- Edit button to open modal for editing -->
                            <button @click="editvendor(vendor)">
                                <svg class="w-6 h-6 text-blue-400 dark:text-white ml-5" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                </svg>
                            </button>

                            <Link @click="deletevendor(vendor.id)">
                            <svg class="w-6 h-6 text-red-400 dark:text-white ml-5" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            </Link>
                        </td>
                    </tr>
                </tbody>

            </table>
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

        <el-dialog
    title="Vendor Details"
    :model-value="isModalVisible"
    @close="closeModal"
    width="40%"
    class="custom-modal"
>
    <div class="modal-content">
        <!-- Input field for vendor Name -->
        <el-input
            v-model="vendorForm.name"
            placeholder="Enter vendor Name"
            style="max-width: 350px; margin-bottom: 10px"
        >
            <template #prepend> Vendor Name</template>
        </el-input>

        <el-input
            v-model="vendorForm.company_name"
            placeholder="Enter Company Nmae"
            style="max-width: 350px; margin-bottom: 10px"
        >
            <template #prepend> Company Name</template>
        </el-input>

        <el-input
            v-model="vendorForm.phone"
            placeholder="Enter Phone Number"
            style="max-width: 350px; margin-bottom: 10px"
        >
            <template #prepend> Phone No.</template>
        </el-input>

        <el-input type="textarea"
            v-model="vendorForm.address"
            placeholder="Enter vendor address"
            style="max-width: 350px; margin-bottom: 10px"
        >

        </el-input>

    </div>

    <!-- Modal Footer with Submit and Close Buttons -->
    <template #footer>
        <el-button
            @click="submitvendor"
            :disabled="vendorForm.processing"
            type="primary"
            native-type="submit"
        >
            <span v-if="vendorForm.processing">Submitting...</span>
            <span v-else>Submit</span>
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
