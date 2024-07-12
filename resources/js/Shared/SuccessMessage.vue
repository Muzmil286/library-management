<template>
  <div class="row mb-2">
    <div class="col-md-12 mt-2">
      <p class="text-success" v-if="success">{{ success }}</p>
    </div>
  </div>
</template>
<script setup>
defineProps({
  success: String,
})
</script>
