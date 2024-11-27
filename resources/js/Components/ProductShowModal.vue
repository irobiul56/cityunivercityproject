<script setup>
import { ref } from 'vue';

const showModal = ref(false);

const closeModal = () => {
  showModal.value = false;
};

const handleConfirm = () => {
  // Perform the action you want on confirm
  showModal.value = false;
};
</script>

<template>
    <div>
      <el-button type="primary" @click="showModal = true">Show</el-button>
  
      <el-dialog
        title="Product Details"
        :visible.sync="showModal"
        width="30%"
        @close="closeModal"
      >
        <p>This is the content inside the modal</p>
        <span slot="footer" class="dialog-footer">
          <el-button @click="closeModal">Cancel</el-button>
          <el-button type="primary" @click="handleConfirm">Confirm</el-button>
        </span>
      </el-dialog>
    </div>
  </template>
  

  