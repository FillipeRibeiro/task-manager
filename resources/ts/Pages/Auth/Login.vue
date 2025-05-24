<script setup>

import { reactive } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

function submit() {
  form.post('/login')
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 md:p-10">
      <h2 class="text-3xl font-extrabold text-gray-800 mb-6">Sign in to your account</h2>

      <form @submit.prevent="submit">
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            v-model="form.email"
            type="email"
            id="email"
            class="mt-1 block w-full px-4 py-2 border rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
            required
            autofocus
          />
        </div>

        <div class="mb-6">
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input
            v-model="form.password"
            type="password"
            id="password"
            class="mt-1 block w-full px-4 py-2 border rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
            required
          />
        </div>

        <button
          type="submit"
          class="w-full bg-blue-600 text-white font-semibold py-2 rounded-xl hover:bg-blue-700 transition"
        >
          Sign in
        </button>
      </form>

      <div class="mt-6 text-center text-sm text-gray-600">
        Don't have an account?
        <Link href="/register" class="text-blue-600 hover:underline">Sign up</Link>
      </div>
    </div>
  </div>
</template>