<template>
  <Form :form-heading="`Update language!`">
    <form
      class="user"
      @submit.prevent="
        form.put(`/languages/${language.id}`, {
          preserveScroll: true,
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
        Update
      </Button>
      <hr />
      <BtnLink href="/languages" class="btn-outline-danger">Cancel</BtnLink>
    </form>
  </Form>
</template>
<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import BtnLink from '../../Shared/BtnLink.vue'
defineProps({ language: Object })
const form = useForm({
  name: usePage().props.language.name,
})
</script>
