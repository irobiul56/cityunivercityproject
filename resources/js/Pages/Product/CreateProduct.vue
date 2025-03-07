<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ref } from 'vue';
import { ElMessage } from "element-plus";

const { props } = usePage()
const categories = ref(props.category)
const stores = ref(props.store)

const form = useForm({
    name: '',
    description: '',
    price: '',
    stock: '',
    image: null,
    category: '',
    store_id: '',
});

const imagePreview = ref(null);

const beforeUpload = (file) => {
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
    form.image = file; // Set the image file to form data
    return false; // Prevent auto upload
};

const saveProduct = () => {
    form.post(route('product.store'), {
        onSuccess: () => {
            form.reset();
            imagePreview.value = null;
            ElMessage.success("Product updated successfully!");
        },
      onError: () => {
      ElMessage.error("Failed to update the product. Please try again.");
    },
    });
};

defineProps({ 
    errors: Object,
    category: Object
    
})


</script>

<template lang="">
    <Head title="Upload Product"/>
    <FrontendLayout>
        <el-card class="max-w-2xl mx-auto p-6">
          <div style="padding-bottom:20px; ">
            <h1 style="border-left: 2px solid rgb(79 70 229); padding-left:5px">Inventory Item Adding Form</h1>
          </div>
          
  <div class="p-2 mb-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" v-if="errors.name">{{ errors.name }}</div>   
    <el-form @submit.prevent="saveProduct()" label-position="top" label-width="100px">
      <el-form-item label="Item Name" :required="true">
        <el-input v-model="form.name" placeholder="Enter Item name"></el-input>
      </el-form-item>

      <div class="p-2 mb-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" v-if="errors.description">{{ errors.description }}</div>
      <el-form-item label="Description" :required="true">
        <el-input type="textarea" v-model="form.description" placeholder="Enter Item description"></el-input>
      </el-form-item>
      
      <div class="p-2 mb-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" v-if="errors.price">{{ errors.price }}</div>
      <el-form-item label="Price" :required="true">
        <el-input v-model="form.price" type="number" placeholder="Enter Item price"></el-input>
      </el-form-item>
      
      <el-form-item label="Category" :required="true">
        <el-select v-model="form.category" placeholder="Select">
        <el-option
          v-for="(category, index) in categories"
          :key="index"
          :label="category.name"
          :value="category.id"
        />
      </el-select>
      </el-form-item>

      <el-form-item label="Storage Location" :required="true">
        <el-select v-model="form.store_id" placeholder="Select">
        <el-option
          v-for="(store, index) in stores"
          :key="index"
          :label="store.name"
          :value="store.id"
        />
      </el-select>
      </el-form-item>

      <div class="p-2 mb-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" v-if="errors.stock">{{ errors.stock }}</div>
      <el-form-item label="Stock" :required="true">
        <el-input v-model="form.stock" type="number" placeholder="Enter stock quantity"></el-input>
      </el-form-item>

      <div class="p-2 mb-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" v-if="errors.image">{{ errors.image }}</div>
      <el-form-item label="Image" :required="true">
        <el-upload
          class="upload-demo"
          action=""
          :show-file-list="false"
          :before-upload="beforeUpload"
        > 
        <div v-if="imagePreview" class="mt-4">
          <img :src="imagePreview" alt="Image Preview" class="max-w-full h-auto rounded-md shadow-sm"/>
        </div>
        <el-button v-else type="primary">Click to Upload</el-button>
        </el-upload>
      </el-form-item>

      <el-form-item>
        <el-button :disabled="form.processing" type="success" native-type="submit">
          <span v-if="form.processing">Item Adding....</span>
          <span v-else>Add Item</span>
        </el-button>
        
        <span v-if="form.progress" :value="form.progress.percentage" max="100">
      Upload Image: {{ form.progress.percentage }}%
    </span>
      </el-form-item>
    </el-form>
  </el-card>
     
    </FrontendLayout>
</template>

<style scoped>
.upload-demo {
    display: block;
}
</style>
