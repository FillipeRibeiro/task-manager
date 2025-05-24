<script setup>
import { ref, watch, onMounted } from 'vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

defineProps({
  show: {
    type: Boolean,
  },
  projectName: {
    type: String,
  }
})

const emit = defineEmits(['closeProject', 'confirmProject'])

const close = () => emit('closeProject')
const confirmDelete = () => emit('confirmProject')
</script>

<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
  >
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 space-y-6 animate-fade-in">
      <h2 class="text-xl font-bold text-gray-800">Confirm Deletion</h2>
      <p class="text-gray-600">
        Are you sure you want to delete the project
        <span class="font-semibold text-red-600">"{{ projectName }}"</span>?
        This action cannot be undone.
      </p>

      <div class="flex justify-end space-x-4">
        <button
          @click.stop="close"
          class="px-4 py-2 rounded-xl text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition"
        >
          Cancel
        </button>

        <PrimaryButton
          @click.stop="confirmDelete"
          class="bg-red-600 hover:bg-red-700"
        >
          Delete
        </PrimaryButton>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-fade-in {
  animation: fade-in 0.2s ease-out;
}
</style>
