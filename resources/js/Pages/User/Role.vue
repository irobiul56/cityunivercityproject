<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import { ElMessage } from "element-plus";

const { props } = usePage();
const roles = ref(props.role);
const permissions = ref(props.permission);

const itemsPerPage = ref(10);
const currentPage = ref(1);
const searchQuery = ref("");

const filteredRole = computed(() => {
    const filtered = roles.value.filter((role) =>
        role.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filtered.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(
        roles.value.filter((role) =>
            role.name.toLowerCase().includes(searchQuery.value.toLowerCase())
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

defineProps({
    errors: Object,
});

// Modal visibility
const isModalVisible = ref(false);

// Form to add or edit a role
const roleForm = useForm({
    name: "",
    permission_id: [],
});

// Function to open the modal for adding a new role
const AddRole = () => {
    roleForm.reset(); // Reset form fields
    isModalVisible.value = true;
};

// Function to open the modal with data for editing a role
const editRole = (role) => {
    roleForm.id = role.id; // Set the ID to track the role being edited
    roleForm.name = role.name; // Populate form with existing role name
    // Map the role's permissions to their IDs and set them in permission_id
    roleForm.permission_id = role.permissions.map(permission => permission.id);
    isModalVisible.value = true;
};

// Function to submit the role form
const submitRole = () => {
    const isUpdate = Boolean(roleForm.id);
    const routeName = isUpdate ? "role.update" : "role.store";
    const routeParams = isUpdate ? [roleForm.id] : [];
    const method = isUpdate ? "put" : "post";

    roleForm[method](route(routeName, ...routeParams), {
        onSuccess: (page) => {
            isModalVisible.value = false;
            roles.value = page.props.role;
            roleForm.reset();
            roleForm.id = null;
            const message = isUpdate
                ? "Role updated successfully!"
                : "Role added successfully!";
            ElMessage.success(message);
        },
        onError: () => {
            ElMessage.error("Failed to submit the Role. Please try again.");
        },
    });
};

// Function to close the modal
const closeModal = () => {
    isModalVisible.value = false;
};

// Function to delete a role
const deleteRole = (roleId) => {
    if (confirm("Are you sure you want to delete this data?")) {
        roleForm.delete(route("role.destroy", roleId), {
            onSuccess: (page) => {
                roles.value = page.props.role;
                ElMessage.success("Role deleted successfully!");
            },
            onError: () => {
                ElMessage.error("Failed to delete the Role. Please try again.");
            },
        });
    }
};
</script>

<template>
    <FrontendLayout>
        <div v-if="$page.props.flash?.message"
            class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 alert">
            {{ $page.props.flash?.message }}
        </div>

        <Head title="Role page"></Head>
        <div class="container mx-auto">
            <div class="relative mx-4 lg:mx-0 mb-2">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </span>
                <input v-model="searchQuery"
                    class="w-32 pl-10 pr-4 text-indigo-600 border-gray-200 rounded-md sm:w-64 focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                    type="text" placeholder="Search" />
                <el-button class="ml-3" @click="AddRole" type="success">Add</el-button>
            </div>

            <el-table :data="filteredRole" style="width: 100%" class="shadow-md rounded-lg">
                <el-table-column label="#" width="50">
                    <template #default="scope">
                        <span>{{ scope.$index + 1 }}</span>
                    </template>
                </el-table-column>

                <el-table-column label="Role Name" prop="name">
                    <template #default="scope">
                        <span>{{ scope.row.name }}</span>
                    </template>
                </el-table-column>

                <el-table-column label="Permissions">
                    <template #default="scope">
                        <el-tag v-for="permission in scope.row.permissions" :key="permission.id" type="info" class="mr-2">
                            {{ permission.name }}
                        </el-tag>
                    </template>
                </el-table-column>

                <el-table-column label="Action">
                    <template #default="scope">
                        <el-button @click="editRole(scope.row)" type="primary" class="mr-2">Edit</el-button>
                        <el-button @click="deleteRole(scope.row.id)" type="danger">Delete</el-button>
                    </template>
                </el-table-column>
            </el-table>

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

        <!-- Modal-->
        <el-dialog title="Role Details" :model-value="isModalVisible" @close="closeModal" width="40%"
            class="custom-modal">
            <div class="modal-content">
                <!-- Input field for Role Name -->
                <el-input v-model="roleForm.name" placeholder="Enter Role Name" style="max-width: 100%">
                    <template #prepend>Name</template>
                </el-input>

                <div class="mt-4">
                    <span style="font-size: 20px;">Select Permission: </span>

                    <el-checkbox-group v-model="roleForm.permission_id">
                        <el-checkbox v-for="(permission, index) in permissions" :key="index" :label="permission.id"
                            class="mt-3" border>
                            {{ permission.name }}
                        </el-checkbox>
                    </el-checkbox-group>
                </div>
            </div>

            <!-- Modal Footer with Submit and Close Buttons -->
            <template #footer>
                <el-button @click="submitRole" :disabled="roleForm.processing" type="primary" native-type="submit">
                    <span v-if="roleForm.processing">Submitting...</span>
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
