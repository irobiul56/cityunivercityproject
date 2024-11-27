<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { ElMessage } from "element-plus";

const { props } = usePage();
const categories = ref(props.category);
const products = ref(props.product);

const form = useForm({
  name: props.product.name || '',
  description: props.product.description || '',
  price: props.product.price || '',
  stock: props.product.stock || '',
  image: props.product.image || null,
  category: props.product.category_id || '',
});

const imagePreview = ref(
    props.product.image ? `/images/${props.product.image}` : null
);

const beforeUpload = (file) => {
  const reader = new FileReader();
  reader.onload = (e) => {
    imagePreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
  form.image = file; // Set the image file to form data
  return false; // Prevent auto upload
};

const updateProduct = () => {
  form.put(route("product.update", props.product.id), {
    onSuccess: () => {
      ElMessage.success("Product updated successfully!");
    },
    onError: (errors) => {
      ElMessage.error("Failed to update the product. Please try again.");
    },
  });
};

</script>

<template>
  <Head title="Update Product" />
  <FrontendLayout>
    <el-card class="max-w-2xl mx-auto p-6">
      <el-form @submit.prevent="updateProduct" label-position="top" label-width="100px">
        <!-- Product Name -->
        <el-form-item label="Product Name" :required="true">
          <el-input v-model="form.name" placeholder="Enter product name" />
          <div v-if="form.errors.name" class="p-2 mb-2 text-sm text-red-800 bg-red-50 rounded-lg dark:bg-gray-800 dark:text-red-400">
            {{ form.errors.name }}
          </div>
        </el-form-item>

        <!-- Description -->
        <el-form-item label="Description" :required="true">
          <el-input type="textarea" v-model="form.description" placeholder="Enter product description" />
          <div v-if="form.errors.description" class="p-2 mb-2 text-sm text-red-800 bg-red-50 rounded-lg dark:bg-gray-800 dark:text-red-400">
            {{ form.errors.description }}
          </div>
        </el-form-item>

        <!-- Price -->
        <el-form-item label="Price" :required="true">
          <el-input v-model="form.price" type="number" placeholder="Enter product price" />
          <div v-if="form.errors.price" class="p-2 mb-2 text-sm text-red-800 bg-red-50 rounded-lg dark:bg-gray-800 dark:text-red-400">
            {{ form.errors.price }}
          </div>
        </el-form-item>

        <!-- Category -->
        <el-form-item label="Category" :required="true">
          <el-select v-model="form.category" placeholder="Select">
            <el-option v-for="(category, index) in categories" :key="index" :label="category.name" :value="category.id" />
          </el-select>
        </el-form-item>

        <!-- Stock -->
        <el-form-item label="Stock" :required="true">
          <el-input v-model="form.stock" type="number" placeholder="Enter stock quantity" />
          <div v-if="form.errors.stock" class="p-2 mb-2 text-sm text-red-800 bg-red-50 rounded-lg dark:bg-gray-800 dark:text-red-400">
            {{ form.errors.stock }}
          </div>
        </el-form-item>

        <!-- Image Upload -->
        <el-form-item label="Image" :required="true">
          <el-upload class="upload-demo" action="" :show-file-list="false" :before-upload="beforeUpload">
            <div v-if="imagePreview" class="mt-4">
              <img :src="imagePreview" alt="Image Preview" class="max-w-full h-auto rounded-md shadow-sm" />
            </div>
            <el-button v-else type="primary">Click to Upload</el-button>
          </el-upload>
          <div v-if="form.errors.image" class="p-2 mb-2 text-sm text-red-800 bg-red-50 rounded-lg dark:bg-gray-800 dark:text-red-400">
            {{ form.errors.image }}
          </div>
        </el-form-item>

        <!-- Update Button -->
        <el-form-item>
          <el-button :disabled="form.processing" type="success" native-type="submit">
            <span v-if="form.processing">Product Updating...</span>
            <span v-else>Update Product</span>
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
