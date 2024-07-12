<template>
  <Form :form-heading="`Add new Language!`">
    <form
      class="user"
      @submit.prevent="
        form.post('/languages', {
          preserveScroll: true,
          onSuccess: () => form.reset('name'),
        })
      "
    >
      <div class="form-group">
        <input
          type="text"
          class="form-control"
          id="exampleInputEmail"
          placeholder="Enter Language Name..."
          v-model="form.name"
        />
        <p
          v-if="form.errors.name"
          v-html="form.errors.name"
          class="text-danger"
        ></p>
      </div>

      <Button class="btn btn-outline-primary" :disabled="form.processing">
        Create
      </Button>
      <hr />
      <BtnLink class="btn-outline-danger" href="/languages">Cancel</BtnLink>
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
