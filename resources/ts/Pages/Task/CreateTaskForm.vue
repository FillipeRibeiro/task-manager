<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const props = defineProps({
    project_id: {
        type: Number
    },
})

const form = useForm({
  title: '',
  description: '',
  priority: '',
  due_date: '',
  project_id: props.project_id
})

const submit = () => {
  form.post('/tasks', {
    onSuccess: () => form.reset()
  })
}

const priorities = ['High', 'Medium', 'Low']
</script>

<template>
  <Head title="Create Task" />

  <AppLayout title="Create Task">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
      <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-2xl font-extrabold text-gray-800 mb-6">New Task</h2>

        <form @submit.prevent="submit" class="space-y-6">
          <input v-model="form.project_id" id="project_id" type="hidden" />

          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input v-model="form.title" id="title" type="text" class="mt-1 block w-full px-4 py-2 border rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500" required />
            <p v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</p>
          </div>

          <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea v-model="form.description" id="description" rows="4" class="mt-1 block w-full px-4 py-2 border rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500"></textarea>
            <p v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</p>
          </div>

          <div>
            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
            <input v-model="form.due_date" id="due_date" type="date" class="mt-1 block w-full px-4 py-2 border rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500" required />
            <p v-if="form.errors.due_date" class="text-red-500 text-sm mt-1">{{ form.errors.due_date }}</p>
          </div>

          <div>
            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
            <select v-model="form.priority" id="priority" class="mt-1 block w-full px-4 py-2 border rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500" required>
              <option disabled value="">Select Priority</option>
              <option v-for="option in priorities" :key="option" :value="option.toLowerCase()">{{ option }}</option>
            </select>
            <p v-if="form.errors.priority" class="text-red-500 text-sm mt-1">{{ form.errors.priority }}</p>
          </div>

          <div class="flex justify-end items-center">
            <Link
              :href="`/tasks/list/${props.project_id}`"
              class="mr-4 text-gray-600 hover:underline"
            >
              Cancel
            </Link>

            <PrimaryButton
                type="submit"
                :disabled="form.processing"
            >
              Save
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
