<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Profile" />

    <AppLayout>
        <template #header>
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-6 sm:p-8 bg-white border shadow-md rounded-2xl">
                    <UpdateProfileInformationForm class="max-w-xl" />
                </div>

                <div class="p-6 sm:p-8 bg-white border shadow-md rounded-2xl">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
