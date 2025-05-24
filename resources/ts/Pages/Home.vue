<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Pencil, Trash2 } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'
import ConfirmDeleteModal from '@/Pages/Project/Partials/ConfirmDeleteModal.vue'

// usar pinia
const showModal = ref(false)
const selectedProject = ref({ name: '' })

const props = defineProps({
  projects: Array,
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const goToProjects = (id) => {
  router.visit(`/projects/${id}`)
}

const goToTasks = (projectId) => {
  router.visit(`/tasks/list/${projectId}`)
}

const openModal = (project) => {
  selectedProject.value = project
  showModal.value = true
}

const deleteProject = () => {
  if (selectedProject.value) {
    router.delete(route('projects.delete', selectedProject.value.id), {
      onSuccess: () => {
        showModal.value = false
        selectedProject.value = null
      }
    })
  }
}
</script>

<template>
  <AppLayout title="Projects">
    <div class="py-6 px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-800">Your Projects</h1>
        <Link
          href="/projects/create"
          class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-xl hover:bg-blue-700 transition"
        >
          + New Project
        </Link>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="project in projects"
          :key="project.id"
          class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition cursor-pointer border"
          @click="goToTasks(project.id)"
        >
          <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            {{ project.name }}
            <Pencil
              class="w-4 h-4 text-gray-400 hover:text-gray-800 transition cursor-pointer"
              @click.stop="goToProjects(project.id)"
            />
            <Trash2
              class="relative left-24 w-4 h-4 text-gray-600 hover:text-gray-800 transition cursor-pointer"
              @click.stop="openModal(project)"
            />
            <ConfirmDeleteModal
                :show="showModal"
                :projectName="selectedProject?.name"
                @closeProject="showModal = false"
                @confirmProject="deleteProject"
            />
          </h2>
          <p class="text-sm text-gray-600 mt-2">
            {{ project.description ?? 'No description provided.' }}
          </p>
          <p class="text-xs text-gray-400 mt-4">Created on {{ formatDate(project.created_at) }}</p>
        </div>
      </div>

      <div v-if="projects.length === 0" class="text-center text-gray-500 mt-12">
        You don’t have any projects yet.
      </div>
    </div>
  </AppLayout>
</template>
