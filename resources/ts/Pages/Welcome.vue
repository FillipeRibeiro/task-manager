<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4 py-12">
    <div class="max-w-4xl w-full bg-white shadow-2xl rounded-2xl p-8 md:p-16 grid md:grid-cols-2 gap-10">
      <!-- Left side -->
      <div class="flex flex-col justify-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-4">
          Welcome to Your Task Manager
        </h1>
        <p class="text-gray-600 text-lg mb-6">
          Organize your projects. Track your tasks. Collaborate with your team.
        </p>
        <div class="flex gap-4">
          <Link
            href="/login"
            class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-blue-700 transition"
          >
            Login
          </Link>
          <Link
            href="/register"
            class="bg-gray-200 text-gray-800 font-semibold px-6 py-3 rounded-xl hover:bg-gray-300 transition"
          >
            Register
          </Link>
        </div>
      </div>

      <!-- Right side illustration -->
      <div class="hidden md:flex items-center justify-center">
        <img
          src="https://cdn.create.vista.com/api/media/medium/371560210/stock-vector-3d-isometric-flat-vector-concept-of-task-management-process-scheduling-tool?token="
          alt="Task Illustration"
          class="w-full max-w-sm"
        />
      </div>
    </div>
  </div>
</template>
