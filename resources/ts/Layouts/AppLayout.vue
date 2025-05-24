<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="min-h-screen bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-600">
          <Link href="/projects">Task Manager</Link>
        </h1>
        <div class="flex gap-4 items-center">
          <Link href="/projects" class="hover:text-blue-600">Projects</Link>
          <Link href="/profile" class="hover:text-blue-600">Profile</Link>
          <Link href="/logout" method="post" as="button" class="text-sm text-blue-600 font-medium hover:underline">Logout</Link>
        </div>
      </div>
    </header>

    <!-- Content -->
    <main>
      <slot />
    </main>
  </div>
</template>
