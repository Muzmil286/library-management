<template>
  <button class="btn btn-danger" @click.prevent="onDelete">
    Delete
  </button>
</template>
<script setup>
const emit = defineEmits(['delete-trigged'])

function onDelete() {
  emit('delete-trigged')
}
</script>
