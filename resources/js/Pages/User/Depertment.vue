<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ref, computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3';
import { ElMessage } from "element-plus";

const { props } = usePage()
const depertments = ref(props.depertment)

const itemsPerPage = ref(10)
const currentPage = ref(1)
const searchQuery = ref('')

const filtereddepertment = computed(() => {
  const filtered = depertments.value.filter(depertment => 
  depertment.name.toLowerCase().includes(searchQuery.value.toLowerCase()) 
  )
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filtered.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(
    depertments.value.filter(depertment => 
    depertment.name.toLowerCase().includes(searchQuery.value.toLowerCase()) 
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
const depertmentForm = useForm({
    name: '',
});

const Adddepertment = () => {
    isModalVisible.value = true;
};


// Method to open modal with permission data for editing
const editdepertment = (depertment) => {
    depertmentForm.id = depertment.id; // Set the ID to track the permission being edited
    depertmentForm.name = depertment.name; // Populate form with existing data
  isModalVisible.value = true;
};

const submitdepertment = () => {
    // Determine if we are updating (PUT) or creating (POST)
    const isUpdate = Boolean(depertmentForm.id);
    const routeName = isUpdate ? 'depertment.update' : 'depertment.store';
    const routeParams = isUpdate ? [depertmentForm.id] : [];
    const method = isUpdate ? 'put' : 'post'; // Use PUT for update, POST for create

    // Submit the form with appropriate HTTP method
    depertmentForm[method](route(routeName, ...routeParams), {
        onSuccess: (page) => {
            isModalVisible.value = false; // Close modal on success
            depertments.value = page.props.depertment; // Refresh permissions list
            depertmentForm.reset(); // Reset form fields
            depertmentForm.id = null; // Reset ID for next add
            const message = isUpdate ? "Depertment updated successfully!" : "Depertment added successfully!";
            ElMessage.success(message);
        },
        onError: () => {
            ElMessage.error("Failed to submit the depertment. Please try again.");
        }
    });
};



// Method to close the modal
const closeModal = () => {
    isModalVisible.value = false;
};

const deletedepertment = (depertmentId) => {
    if (confirm("Are you sure you want to delete this data?")) {
        depertmentForm.delete(route('depertment.destroy', depertmentId), {
            onSuccess: (page) => {
                depertments.value = page.props.depertment; // Update the depertments list after deletion
                ElMessage.success("Depertment deleted successfully!");
            },
            onError: () => {
                ElMessage.error("Failed to delete the depertment. Please try again.");
            },
        });
    }
};


</script>

<template>
    <FrontendLayout>
        <div v-if="$page.props.flash.message" class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 alert">
        {{ $page.props.flash.message }}
      </div>
      
        <Head title="Depertment page"></Head>
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
                    <el-button class=" ml-3" @click="Adddepertment" type="success">Add</el-button>

            </div>

            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="text-left">
                        <th class="py-2 px-4 border-b-2">#</th>
                        <th class="py-2 px-4 border-b-2">Depertment Name</th>
                        <th class="py-2 px-4 border-b-2">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(depertment, index) in filtereddepertment" :key="index" class="hover:bg-gray-100">
                        <td class="py-2 px-4 border-b">{{ index + 1 }}</td>
                        <td class="py-2 px-4 border-b">{{ depertment.name }}</td>
                        <td class="py-2 px-4 border-b flex">

                           <!-- Edit button to open modal for editing -->
                            <button @click="editdepertment(depertment)">
                                <svg class="w-6 h-6 text-blue-400 dark:text-white ml-5" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                </svg>
                            </button>

                            <Link @click="deletedepertment(depertment.id)">
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
    title="Depertment Details"
    :model-value="isModalVisible"
    @close="closeModal"
    width="40%"
    class="custom-modal"
>
    <div class="modal-content">
        <!-- Input field for depertment Name -->
        <el-input
            v-model="depertmentForm.name"
            placeholder="Enter Depertment Name"
            style="max-width: 350px"
        >
            <template #prepend> Name</template>
        </el-input>
    </div>

    <!-- Modal Footer with Submit and Close Buttons -->
    <template #footer>
        <el-button
            @click="submitdepertment"
            :disabled="depertmentForm.processing"
            type="primary"
            native-type="submit"
        >
            <span v-if="depertmentForm.processing">Submitting...</span>
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
