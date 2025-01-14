<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ref, computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import { ElMessage, ElDialog, ElButton } from 'element-plus';

const page = usePage();
const user = computed(() => page.props.auth.user);

import {
  Document, Menu as IconMenu, User, CaretRight
} from '@element-plus/icons-vue';


const { props } = usePage()
const requisitions = ref(props.requisition)

const itemsPerPage = ref(10)
const currentPage = ref(1)
const searchQuery = ref('')

const filteredrequisition = computed(() => {
  const filtered = requisitions.value.filter(requisition =>
    requisition.description.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filtered.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(
    requisitions.value.filter(requisition =>
      requisition.description.toLowerCase().includes(searchQuery.value.toLowerCase())
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

// const deleterequisition = (requisitionId) => {
//   if (confirm("Are you sure you want to delete this data?")) {
//     form.delete(route('requisition.destroy', requisitionId));
//   }
// }

const deleterequisition = (requisitionId) => {
  if (confirm("Are you sure you want to delete this data?")) {
    form.delete(route('requisition.destroy', requisitionId), {
      onSuccess: (page) => {
        requisitions.value = page.props.requisition; // Update the rooms list after deletion
        ElMessage.success("Data Deleted Successful");
      },
      onError: () => {
        ElMessage.error("Data Deleted Unsuccessful");
      },
    });
  }
};

const approveRequisition = (id) => {
  form.post(route('requisitions.approve', id), {
    onSuccess: (page) => {
      const updatedRequisition = page.props.requisition.find(r => r.id === id);
      const index = requisitions.value.findIndex(r => r.id === id);
      if (index !== -1) {
        requisitions.value[index] = updatedRequisition;
      }
      ElMessage.success(page.props.flash.success || "Requisition Approved successfully!");
    },
    onError: () => {
      ElMessage.error(page.props.flash.error || "Failed to approved the requisition. Please try again.");
    },
  });
};

const rejectRequisition = (id) => {
  form.post(route('requisitions.reject', id), {
    onSuccess: (page) => {
      const updatedRequisition = page.props.requisition.find(r => r.id === id);
      const index = requisitions.value.findIndex(r => r.id === id);
      if (index !== -1) {
        requisitions.value[index] = updatedRequisition;
      }
      ElMessage.success(page.props.flash.success || "Requisition Reject successfully!");
    },
    onError: () => {
      ElMessage.error(page.props.flash.error || "Failed to reject the requisition. Please try again.");
    },
  });
};




</script>

<template>
  <FrontendLayout>
    <Head title="Requisition page"></Head>
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
        <Link :href="route('requisition.index')">
        <span class="ml-5 bg-blue-500 text-white px-4 py-2 rounded inline-block"> Requisition Application </span>
        </Link>

      </div>

      <table class="min-w-full bg-white shadow-md rounded-lg">
        <thead>
          <tr class="text-left">
            <th class="py-2 px-4 border-b-2">#</th>
            <th class="py-2 px-4 border-b-2">Requested By</th>
            <th class="py-2 px-4 border-b-2">Products</th>
            <th class="py-2 px-4 border-b-2">Description</th>
            <th class="py-2 px-4 border-b-2">Application Date</th>
            <th class="py-2 px-4 border-b-2">Status</th>
            <th class="py-2 px-4 border-b-2">Action</th>

          </tr>
        </thead>
        <tbody>
          <tr v-for="(requisition, index) in filteredrequisition" :key="index" class="hover:bg-gray-100">
            <td class="py-2 px-4 border-b">{{ index + 1 }}</td>
            <td class="py-2 px-4 border-b">{{ requisition.users.name }}</td>
            <td class="py-2 px-4 border-b">
              <ul v-for="(product, index) in requisition.products" :key="index">
                <li><el-icon>
                    <CaretRight />
                  </el-icon> {{ product.name }}, qnt({{ product.pivot.quantity }}) </li>
              </ul>
            </td>
            <td class="py-2 px-4 border-b">{{ requisition.description }}</td>

            <td class="py-2 px-4 border-b">{{ new Date(requisition.created_at).toLocaleDateString('en-GB') }}</td>

            <td class="py-2 px-4 border-b">
              <div class="flex gap-2">
                <el-tag v-if="requisition.status == 'pending'" type="danger"><b>Pending</b></el-tag>
                <el-tag v-if="requisition.status == 'approved'" type="success"><b>Approved</b></el-tag>
                <el-tag v-if="requisition.status == 'rejected'" type="warning"><b>Rejected</b></el-tag>
              </div>
            </td>
            <td class="py-2 px-4 border-b">
              <div class="flex gap-2">
              <el-button v-if="user.role.name == 'Administrator' && requisition.status == 'pending'" size="small" type="success" @click="approveRequisition(requisition.id)">Approve</el-button>
              <el-button  v-if="user.role.name == 'Administrator' && requisition.status == 'pending'" type="danger" size="small" @click="rejectRequisition(requisition.id)"> Reject </el-button>
              <el-button v-if="user.role.name !== 'Administrator' && requisition.status == 'pending'" size="small" type="danger" @click="deleterequisition(requisition.id)">Delete</el-button>

              </div>
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
  </FrontendLayout>
</template>

<style scoped>
.requisition-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.show-requisition-button {
  background-color: #409eff;
  color: white;
  border-radius: 5px;
  padding: 0.5rem 1rem;
}

.show-requisition-button:hover {
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

.requisition-image {
  width: 50%;
  margin-bottom: 1rem;
}

.image-preview {
  max-width: 100%;
  height: auto;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.requisition-details {
  width: 100%;
  text-align: left;
}

.requisition-details h2 {
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
