<template>
  <Form :form-heading="`Add new publisher!`">
    <form
      @submit.prevent="
        form.post('/publishers', {
          onSuccess: () => form.reset(),
        })
      "
    >
      <div class="form-input">
        <label for="">Name</label>
        <input
          type="text"
          class="form-control"
          placeholder="Enter Publisher Name"
          v-model="form.name"
        />
        <p class="text-danger" v-if="form.errors.name">
          {{ form.errors.name }}
        </p>
      </div>
      <hr />
      <button class="btn btn-outline-primary" :disabled="form.processing">
        Create
      </button>
      <hr />
      <BtnLink class="btn-outline-danger" href="/publishers">Cancel</BtnLink>
    </form>
  </Form>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
import BtnLink from '../../Shared/BtnLink.vue'
let form = useForm({
  name: null,
})
</script>
