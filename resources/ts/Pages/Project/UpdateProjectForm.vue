<script setup>
import { Link } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

// usar pinia
const props = defineProps({
    id: {
        type: Number,
    },
    name: {
        type: String,
    },
    description: {
        type: String,
    },
})

const form = useForm({
  id: props.id,
  name: props.name,
  description: props.description
})

const submit = () => {
  form.put(`/projects/${form.id}`, {
    onSuccess: () => {
      form.reset()
    }
  })
}
</script>

<template>
  <AppLayout title="Create Project">
    <div class="py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-2xl font-extrabold text-gray-800 mb-4">Update Project</h2>

        <form @submit.prevent="submit">
          <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="form.name"
              type="text"
              id="name"
              class="mt-1 block w-full px-4 py-2 border rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
              required
            />
            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
          </div>

          <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
              v-model="form.description"
              id="description"
              rows="4"
              class="mt-1 block w-full px-4 py-2 border rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
            ></textarea>
            <p v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</p>
          </div>

          <div class="flex justify-end items-center">
            <Link
              href="/projects"
              class="mr-4 text-gray-600 hover:underline"
            >
              Cancel
            </Link>

            <PrimaryButton
              type="submit"
              :disabled="form.processing">
                Save
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
