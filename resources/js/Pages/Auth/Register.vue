<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { ElForm, ElFormItem, ElInput, ElButton, ElMessage, ElSelect, ElOption } from "element-plus";
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'; // Add `usePage` here
import { ref } from 'vue';

const { props } = usePage();
const roles = ref(props.role);

const form = useForm({
    name: '',
    email: '',
    address: '',
    role_id: '',
    department_id: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
            ElMessage.success("Registration successful!");
        },
        onError: () => {
            ElMessage.error("Registration failed. Please check your inputs.");
        }
    });
};
</script>

<template>
    <FrontendLayout>

        <Head title="Register" />
        <el-form :model="form" @submit.prevent="submit" label-width="auto" style="max-width: 600px" class="max-w-lg mx-auto p-6 bg-white shadow-md rounded-md">
            <el-form-item :error="form.errors.fname" label="Name:">
                <el-input v-model="form.name" />
            </el-form-item>
            <el-form-item :error="form.errors.email" label="Email:">
                <el-input v-model="form.email" />
            </el-form-item>
            <el-form-item :error="form.errors.password" label="Password:">
                <el-input type="password" v-model="form.password" />
            </el-form-item>
            <el-form-item :error="form.errors.password_confirmation" label="Confirm Password:">
                <el-input type="password" v-model="form.password_confirmation" />
            </el-form-item>
                <el-form-item label="Role:">
                    <el-select v-model="form.role_id" placeholder="please select your role">
                        <el-option v-for="(role, index) in roles" :key="index" :label="role.name" :value="role.id" />
                    </el-select>
                </el-form-item>
            <el-form-item label="Depertment:">
                <el-select v-model="form.department_id" placeholder="please select your depertment">
                    <el-option label="CSE" value="1" />
                </el-select>
            </el-form-item>
            <el-form-item label="Address:">
                <el-input v-model="form.address" type="textarea" />
            </el-form-item>
            <div class="flex justify-end mt-6">
                <el-form-item>
                    <el-button type="primary" @click="submit">Register</el-button>
                    <el-button type="danger">Reset</el-button>
                </el-form-item>

            </div>
        </el-form>
    </FrontendLayout>
</template>

<style scoped>
.max-w-lg {
    max-width: 50rem;
}

.mx-auto {
    margin-left: auto;
    margin-right: auto;
}

.p-6 {
    padding: 1.5rem;
}

.bg-white {
    background-color: #ffffff;
}

.shadow-md {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.rounded-md {
    border-radius: 0.375rem;
}

.text-2xl {
    font-size: 1.5rem;
}

.font-semibold {
    font-weight: 600;
}

.mb-6 {
    margin-bottom: 1.5rem;
}
</style>
