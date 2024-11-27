<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const sidebarOpen = ref(true);

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

</script>


<template>

<nav class="flex justify-between p-4 bg-gray-200">
        <div class="text-lg">
            Navbar
        </div>
        <div>
            <ul class="flex gap-x-4 me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <Link class="text-dark" :href="route('home')"> Home</Link>
                </li>
                <li class="nav-item">
                    <Link class="text-dark" :href="route('about')"> About</Link>
                </li>
                <li class="nav-item">
                    <Link class="text-dark" :href="route('login')"> Login</Link>
                </li>
                <li class="nav-item">
                    <Link class="text-dark" :href="route('register')"> Register</Link>
                </li>

            </ul>
        </div>
    </nav>

</template>
