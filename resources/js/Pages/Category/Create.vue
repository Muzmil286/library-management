<template>
  <Form :form-heading="`Add new category!`">
    <form
      class="user"
      @submit.prevent="
        form.post('/categories', {
          onSuccess: () => form.reset('name'),
        })
      "
    >
      <div class="form-group">
        <input
          type="text"
          class="form-control"
          id="exampleInputEmail"
          placeholder="Enter Category Name..."
          v-model="form.name"
        />
        <p
          v-if="form.errors.name"
          v-html="form.errors.name"
          class="text-danger"
        ></p>
      </div>

      <button class="btn btn-outline-primary" :disabled="form.processing">
        Create
      </button>
      <hr />
      <BtnLink class="btn-outline-danger" href="/categories">Cancel</BtnLink>
    </form>
  </Form>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
import BtnLink from '../../Shared/BtnLink.vue'
const form = useForm({
  name: null,
})
</script>
