<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { ElMessage } from "element-plus";
import {List, ShoppingTrolley, RefreshLeft, Coin , OfficeBuilding, PieChart } from "@element-plus/icons-vue";
import { ref, computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

</script>

<template>
    <FrontendLayout>
        <Head title="Dashboard"></Head>
        <div class="card-container">

            <el-card v-if="user.role.permissions.includes('Inventory')" class="card" shadow="hover">
                <div class="icon"> <el-icon style="font-size: 35px; color: green;"><List /></el-icon></div>
                <div class="title"><p>Inventory</p></div>
            </el-card>

            <el-card v-if="user.role.permissions.includes('Store Requisition')" class="card" shadow="hover">
                <div class="icon"> <el-icon style="font-size: 35px; color: green;"><ShoppingTrolley /></el-icon></div>
                <div class="title"><p>Store Requisition</p></div>
            </el-card>

            <el-card v-if="user.role.permissions.includes('Stock')" class="card" shadow="hover">
                <div class="icon"> <el-icon style="font-size: 35px; color: green;"><RefreshLeft /></el-icon></div>
                <div class="title"><p>Stock</p></div>
            </el-card>

            <el-card v-if="user.role.permissions.includes('Asset Management')" class="card" shadow="hover">
                <div class="icon"> <el-icon style="font-size: 35px; color: green;"><Coin   /></el-icon></div>
                <div class="title"><p>Asset Management</p></div>
            </el-card>

            <el-card v-if="user.role.permissions.includes('Departments')" class="card" shadow="hover">
                <div class="icon"> <el-icon style="font-size: 35px; color: green;"><OfficeBuilding /></el-icon></div>
                <div class="title"><p>Department</p></div>
            </el-card>

            <el-card v-if="user.role.permissions.includes('Reporting')"  class="card" shadow="hover">
                <div class="icon"> <el-icon style="font-size: 35px; color: green;"><PieChart /></el-icon></div>
                <div class="title"><p>Reporting</p></div>
            </el-card>

        </div>
    </FrontendLayout>
</template>

<style scoped>
.card-container {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 20px;
  padding: 20px;

}

.card {
  width: 150px;
  text-align: center;
  border-radius: 8px;
  overflow: hidden;
  transition: transform 0.2s ease-in-out;
}

.card:hover {
  transform: scale(1.05);
}

.icon {
  background-color: #f5faff;
  padding: 15px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 10px auto;
  width: 60px;
  height: 60px;
}

.icon svg {
  width: 40px;
  height: 40px;
}

.title {
  font-size: 14px;
  font-weight: bold;
  color: #333;
  margin-top: 10px;
}
</style>
