<template>
  <Form :form-heading="`Update category!`">
    <form
      class="user"
      @submit.prevent="
        form.put(`/categories/${category.id}`, {
          preserveScroll: true,
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
        Update
      </button>
      <hr />
      <BtnLink class="btn-outline-danger" href="/categories">Cancel</BtnLink>
    </form>
  </Form>
</template>
<script setup>
import { useForm, usePage } from '@inertiajs/vue3'

import BtnLink from '../../Shared/BtnLink.vue'
defineProps({ category: Object })
const form = useForm({
  name: usePage().props.category.name,
})
</script>
