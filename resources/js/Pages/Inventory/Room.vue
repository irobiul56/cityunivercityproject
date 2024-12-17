<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ref, computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3';
import { ElMessage } from "element-plus";

const { props } = usePage()
const rooms = ref(props.room)

const itemsPerPage = ref(10)
const currentPage = ref(1)
const searchQuery = ref('')

const filteredroom = computed(() => {
  const filtered = rooms.value.filter(room => 
  room.name.toLowerCase().includes(searchQuery.value.toLowerCase()) 
  )
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filtered.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(
    rooms.value.filter(room => 
    room.name.toLowerCase().includes(searchQuery.value.toLowerCase()) 
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
const roomForm = useForm({
    name: '',
    room_number: '',
    floor: '',
    size: '',
    type: '',
});

const Addroom = () => {
    isModalVisible.value = true;
};


// Method to open modal with permission data for editing
const editroom = (room) => {
    roomForm.id = room.id; // Set the ID to track the permission being edited
    roomForm.name = room.name; // Populate form with existing data
    roomForm.room_number = room.room_number;
    roomForm.size = room.size;
    roomForm.type = room.type;
    roomForm.floor = room.floor;
  isModalVisible.value = true;
};

const submitroom = () => {
    // Determine if we are updating (PUT) or creating (POST)
    const isUpdate = Boolean(roomForm.id);
    const routeName = isUpdate ? 'room.update' : 'room.store';
    const routeParams = isUpdate ? [roomForm.id] : [];
    const method = isUpdate ? 'put' : 'post'; // Use PUT for update, POST for create

    // Submit the form with appropriate HTTP method
    roomForm[method](route(routeName, ...routeParams), {
        onSuccess: (page) => {
            isModalVisible.value = false; // Close modal on success
            rooms.value = page.props.room; // Refresh permissions list
            roomForm.reset(); // Reset form fields
            roomForm.id = null; // Reset ID for next add
            const message = isUpdate ? "room updated successfully!" : "room added successfully!";
            ElMessage.success(message);
        },
        onError: () => {
            ElMessage.error("Failed to submit the room. Please try again.");
        }
    });
};



// Method to close the modal
const closeModal = () => {
    isModalVisible.value = false;
};

const deleteroom = (roomId) => {
    if (confirm("Are you sure you want to delete this data?")) {
        roomForm.delete(route('room.destroy', roomId), {
            onSuccess: (page) => {
                rooms.value = page.props.room; // Update the rooms list after deletion
                ElMessage.success("room deleted successfully!");
            },
            onError: () => {
                ElMessage.error("Failed to delete the room. Please try again.");
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
      
        <Head title="room page"></Head>
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
                    <el-button class=" ml-3" @click="Addroom" type="success">Add</el-button>

            </div>

            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="text-left">
                        <th class="py-2 px-4 border-b-2">#</th>
                        <th class="py-2 px-4 border-b-2">Room Number</th>
                        <th class="py-2 px-4 border-b-2">Room Name</th>
                        <th class="py-2 px-4 border-b-2">Floor</th>
                        <th class="py-2 px-4 border-b-2">Type</th>
                        <th class="py-2 px-4 border-b-2">Size (sqm)</th>
                        <th class="py-2 px-4 border-b-2">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(room, index) in filteredroom" :key="index" class="hover:bg-gray-100">
                        <td class="py-2 px-4 border-b">{{ index + 1 }}</td>
                        <td class="py-2 px-4 border-b">{{ room.room_number }}</td>
                        <td class="py-2 px-4 border-b">{{ room.name }}</td>
                        <td class="py-2 px-4 border-b">{{ room.floor }}</td>
                        <td class="py-2 px-4 border-b">{{ room.type }}</td>
                        <td class="py-2 px-4 border-b">{{ room.size }}</td>
                        
                        <td class="py-2 px-4 border-b flex">

                           <!-- Edit button to open modal for editing -->
                            <button @click="editroom(room)">
                                <svg class="w-6 h-6 text-blue-400 dark:text-white ml-5" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                </svg>
                            </button>

                            <Link @click="deleteroom(room.id)">
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
    title="Room Details"
    :model-value="isModalVisible"
    @close="closeModal"
    width="40%"
    class="custom-modal"
>
    <div class="modal-content">
        <!-- Input field for room Name -->
        <el-input
            v-model="roomForm.name"
            placeholder="Enter room Name"
            style="max-width: 350px; margin-bottom: 10px"
        >
            <template #prepend> Room Name</template>
        </el-input>

        <el-input
            v-model="roomForm.room_number"
            placeholder="Enter room number"
            style="max-width: 350px; margin-bottom: 10px"
        >
            <template #prepend> Room No.</template>
        </el-input>

        <el-input
            v-model="roomForm.floor"
            placeholder="Enter Floor"
            style="max-width: 350px; margin-bottom: 10px"
        >
            <template #prepend> Floor No.</template>
        </el-input>

        <el-input
            v-model="roomForm.type"
            placeholder="Enter Room Type"
            style="max-width: 350px; margin-bottom: 10px"
        >
            <template #prepend> Room Type</template>
        </el-input>

        <el-input
            v-model="roomForm.size"
            placeholder="Enter Room Size"
            style="max-width: 350px; margin-bottom: 10px"
        >
            <template #prepend> Room Size</template>
        </el-input>

    </div>

    <!-- Modal Footer with Submit and Close Buttons -->
    <template #footer>
        <el-button
            @click="submitroom"
            :disabled="roomForm.processing"
            type="primary"
            native-type="submit"
        >
            <span v-if="roomForm.processing">Submitting...</span>
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
