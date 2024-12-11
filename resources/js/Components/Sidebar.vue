<template>
  <div>
    <el-menu
      :default-active="activeMenu"
      class="el-menu-vertical-demo scrollable-sidebar"
      :collapse="isCollapse"
      @open="handleOpen"
      @close="handleClose"
    >
      <el-menu-item-group @click="toggleCollapse" class="border-indigo-600 border-b-4 cursor-pointer">
        <el-icon style="margin: 20px; font-size: 20px;"><Expand /></el-icon>
      </el-menu-item-group>

      <Link :href="route('dashboard')" @click="setActive('1')">
        <el-menu-item :index="'1'">
          <el-icon><icon-menu /></el-icon>
          <template #title>Dashboard</template>
        </el-menu-item>
      </Link>

      <el-sub-menu index="2">
        <template #title>
          <el-icon><Shop /></el-icon>
          <span>Inventory</span>
        </template>

        <el-menu-item-group>
          <Link :href="route('inventoryTracking.index')" @click="setActive('2-1')">
            <el-menu-item :index="'2-1'">
              <el-icon><Shop /></el-icon> Item List
            </el-menu-item>
          </Link>
          <Link :href="route('category.index')" @click="setActive('2-2')">
            <el-menu-item :index="'2-2'">
              <el-icon><PriceTag /></el-icon> Category List
            </el-menu-item>
          </Link>
          <Link :href="route('inventoryTracking.create')" @click="setActive('2-3')">
            <el-menu-item :index="'2-3'">
              <el-icon><HomeFilled /></el-icon> Storage Room
            </el-menu-item>
          </Link>
          <Link :href="route('inventoryTracking.create')" @click="setActive('2-4')">
            <el-menu-item :index="'2-4'">
              <el-icon><Plus /></el-icon> Vendor
            </el-menu-item>
          </Link>
        </el-menu-item-group>
      </el-sub-menu>

      <Link :href="route('inventoryTracking.index')" @click="setActive('3')">
        <el-menu-item :index="'3'">
          <el-icon><Coin /></el-icon>
          <template #title>P&P Management</template>
        </el-menu-item>
      </Link>

      <Link :href="route('inventoryTracking.index')" @click="setActive('4')">
        <el-menu-item :index="'4'">
          <el-icon><ToiletPaper /></el-icon>
          <template #title>Barcode</template>
        </el-menu-item>
      </Link>

      <Link :href="route('inventoryTracking.index')" @click="setActive('5')">
        <el-menu-item :index="'5'">
          <el-icon><RefreshLeft /></el-icon>
          <template #title>Stock</template>
        </el-menu-item>
      </Link>

      <Link :href="route('inventoryTracking.index')" @click="setActive('6')">
        <el-menu-item :index="'6'">
          <el-icon><Goods /></el-icon>
          <template #title>Asset Management</template>
        </el-menu-item>
      </Link>

      <Link :href="route('inventoryTracking.index')" @click="setActive('7')">
        <el-menu-item :index="'7'">
          <el-icon><Setting /></el-icon>
          <template #title>M&R Management</template>
        </el-menu-item>
      </Link>

      <Link :href="route('inventoryTracking.index')" @click="setActive('8')">
        <el-menu-item :index="'8'">
          <el-icon><Document /></el-icon>
          <template #title>Reporting</template>
        </el-menu-item>
      </Link>

      <Link :href="route('inventoryTracking.index')" @click="setActive('9')">
        <el-menu-item :index="'9'">
          <el-icon><icon-menu /></el-icon>
          <template #title>Departments</template>
        </el-menu-item>
      </Link>

      <el-sub-menu index="10">
        <template #title>
          <el-icon><Shop /></el-icon>
          <span>User</span>
        </template>
        <el-menu-item-group>

          <Link :href="route('user.index')" @click="setActive('10-1')">
            <el-menu-item :index="'10-1'">
              <el-icon><User /></el-icon> User List
            </el-menu-item>
          </Link>

          <Link :href="route('role.index')" @click="setActive('10-1')">
            <el-menu-item :index="'10-2'">
              <el-icon><Shop /></el-icon> Role
            </el-menu-item>
          </Link>

          <Link :href="route('permission.index')" @click="setActive('10-1')">
            <el-menu-item :index="'10-2'">
              <el-icon><Shop /></el-icon> Permission
            </el-menu-item>
          </Link>

        </el-menu-item-group>
      </el-sub-menu>
    </el-menu>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { 
  Document, Menu as IconMenu, User,
  Expand, Shop, PriceTag, Plus, HomeFilled, Coin, 
  ToiletPaper, RefreshLeft, Goods, Setting 
} from '@element-plus/icons-vue';

const isCollapse = ref(true);
const activeMenu = ref<string>("1"); // Track the active menu item

const handleOpen = (key: string, keyPath: string[]) => {
  console.log(key, keyPath);
};

const handleClose = (key: string, keyPath: string[]) => {
  console.log(key, keyPath);
};

const toggleCollapse = () => {
  isCollapse.value = !isCollapse.value;
};

const setActive = (index: string) => {
  activeMenu.value = index;
  isCollapse.value = false;
};

// Access the current page's props and handle possible undefined values
const { props } = usePage();
const user = props.auth?.user || { roles: [], permissions: [] };

// Helper functions with type safety
const userHasRole = (role: string) => Array.isArray(user.roles) && user.roles.includes(role);
const userHasPermission = (permission: string) => Array.isArray(user.permissions) && user.permissions.includes(permission);

const hasPermission = (permissionName: string): boolean => {
  return user.value?.role?.permissions?.some((permission: any) => permission.name === permissionName) ?? false;
};

</script>

<style>
.el-menu-vertical-demo:not(.el-menu--collapse) {
  width: 200px;
  min-height: 100%;
}

.scrollable-sidebar {
  max-height: 100%;
  overflow-y: auto;
}

.scrollable-sidebar::-webkit-scrollbar {
  width: 5px;
}

.scrollable-sidebar::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.scrollable-sidebar::-webkit-scrollbar-thumb {
  background-color: #888;
  border-radius: 10px;
}

.scrollable-sidebar::-webkit-scrollbar-thumb:hover {
  background-color: #555;
}

.scrollable-sidebar {
  scrollbar-width: thin;
  scrollbar-color: #888 #f1f1f1;
}
</style>
