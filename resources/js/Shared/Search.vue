<template>
  <div class="input-group">
    <input
      type="text"
      class="form-control bg-light border-0 small text-primary"
      placeholder="Search ..."
      aria-label="Search"
      v-model="search"
      aria-describedby="basic-addon2"
      @input="onSearch(search)"
    />
    <div class="input-group-append">
      <button class="btn btn-primary" type="button" disabled>
        <i class="fas fa-search fa-sm"></i>
      </button>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue'
const search = ref('')
const emit = defineEmits(['search-trigged'])
const onSearch = function (search) {
  emit('search-trigged', search)
}
</script>
