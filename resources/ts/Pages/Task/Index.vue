<script setup>
import { ref, computed } from 'vue'
import { Trash2 } from 'lucide-vue-next'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ConfirmDeleteModal from '@/Pages/Task/Partials/ConfirmDeleteModal.vue'

// usar pinia
const props = defineProps({
  tasks: {
    type: Array
  },
  project_id: {
    type: Number,
  }
})

const priorities = ['High', 'Medium', 'Low']
const statuses = ['pending', 'in_progress', 'completed']

const searchTitle = ref('')
const selectedStatus = ref('')
const selectedPriority = ref('')
const showModal = ref(false)
const selectedTask = ref({ title: '' })

const filteredTasks = computed(() => {
  return props.tasks.filter(task => {
    const matchesTitle = task.title.toLowerCase().includes(searchTitle.value.toLowerCase())
    const matchesPriority = selectedPriority.value ? task.priority === selectedPriority.value : true
    const matchesStatus = selectedStatus.value ? task.status === selectedStatus.value : true
    return matchesTitle || matchesPriority || matchesStatus
  })
})

const groupedTasks = computed(() => {
    const groups = {}
    statuses.forEach(status => {
        groups[status] = filteredTasks.value.filter(task => task.status === status)
    })
    return groups
})

const updateStatus = (taskId, newStatus) => {
  router.put(`/tasks/status/${taskId}`, { status: newStatus }, {
    preserveScroll: true,
  })
}

const goToTask = (id) => {
  router.visit(`/tasks/edit/${id}`);
}

const openModal = (task) => {
  selectedTask.value = task
  showModal.value = true
}

const deleteTask = () => {
  if (selectedTask.value) {
    router.delete(route('tasks.delete', selectedTask.value.id), {
      onSuccess: () => {
        showModal.value = false
        selectedTask.value = null
      }
    });
  }
}

const filter = () => {
  router.get(
    route('tasks.list', props.project_id),
    {
      searchTitle: searchTitle.value,
      searchPriority: selectedPriority.value,
      searchStatus: selectedStatus.value
    },
    {
      preserveScroll: true,
      preserveState: true,
      replace: true,
    }
  )
}

const getStatusName = (status) => {
  const index = statuses.map((e) => { 
    return e;
  }).indexOf(status);

  const statusesName = ['To Do', 'In Progress', 'Done'];

  return statusesName[index];
} 
</script>

<template>
  <Head title="Tasks" />

  <AppLayout title="Tasks">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-800">Task Board</h1>
        <Link :href="`/tasks/create/${props.project_id}`" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl font-semibold text-white hover:bg-blue-700 transition">
          + New Task
        </Link>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <input
          v-model="searchTitle"
          @input="filter"
          type="text"
          placeholder="Search by title"
          class="w-full px-4 py-2 border rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
        />
        <select
          v-model="selectedPriority"
          @change="filter"
          class="w-full px-4 py-2 border rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
        >
          <option value="">All Priorities</option>
          <option v-for="priority in priorities" :key="priority" :value="priority.toLowerCase()">
            {{ priority }}
          </option>
        </select>
        <select
          v-model="selectedStatus"
          @change="filter"
          class="w-full px-4 py-2 border rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
        >
          <option value="">All Statuses</option>
          <option v-for="status in statuses" :key="status" :value="status">
            {{ getStatusName(status) }}
          </option>
        </select>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="status in statuses" :key="status" class="bg-gray-50 rounded-2xl p-4 shadow-md">
          <h2 class="text-xl font-bold text-gray-700 mb-4">{{ getStatusName(status) }}</h2>
          <div class="space-y-6">
            <div
              v-for="task in groupedTasks[status]"
              :key="task.id"
              class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition cursor-pointer"
              @click.stop="goToTask(task.id)"
            >
              <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-800">{{ task.title }}</h3>
                <Trash2
                    class="relative left-24 w-4 h-4 text-gray-600 hover:text-gray-800 transition cursor-pointer"
                    @click.stop="openModal(task)"
                />
                <ConfirmDeleteModal
                    :show="showModal"
                    :taskTitle="selectedTask?.title"
                    @close="showModal = false"
                    @confirm="deleteTask"
                />
                <Link :href="`/tasks/edit/${task.id}`" class="text-gray-400 hover:text-blue-600"></Link>
              </div>
              <p class="text-sm text-gray-600 mb-3">{{ task.description }}</p>
              <p class="text-sm text-gray-400">Priority: {{ task.priority }}</p>
              <p class="text-sm text-gray-400 mb-2">Due: {{ new Date(task.due_date).toLocaleDateString() }}</p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="optionStatus in statuses.filter(s => s !== task.status)"
                  :key="optionStatus"
                  @click.stop="() => updateStatus(task.id, optionStatus)"
                  class="text-xs px-3 py-1 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200">
                  Move: {{ getStatusName(optionStatus) }}
                </button>
              </div>
            </div>

            <p v-if="!groupedTasks[status].length" class="text-gray-400 text-sm">No tasks</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
