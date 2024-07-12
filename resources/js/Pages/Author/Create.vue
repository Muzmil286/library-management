<template>
  <Form :form-heading="`Add new author!`">
    <form
      class="user"
      @submit.prevent="
        form.post('/authors', {
          preserveScroll: true,
          // onSuccess: () => form.reset('firstName', 'lastName'),
        })
      "
    >
      <div class="form-group">
        <input
          type="text"
          class="form-control"
          id="exampleInputEmail"
          placeholder="Enter First Name..."
          v-model="form.firstName"
        />
        <p
          v-if="form.errors.firstName"
          v-html="form.errors.firstName"
          class="text-danger"
        ></p>
      </div>
      <div class="form-group">
        <input
          type="text"
          class="form-control"
          id="exampleInputEmail"
          placeholder="Enter Last Name..."
          v-model="form.lastName"
        />
        <p
          v-if="form.errors.lastName"
          v-html="form.errors.lastName"
          class="text-danger"
        ></p>
      </div>

      <Button class="btn btn-outline-primary" :disabled="form.processing">
        Create
      </Button>
      <hr />
      <BtnLink class="btn-outline-danger" href="/authors">Cancel</BtnLink>
    </form>
  </Form>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
import BtnLink from '../../Shared/BtnLink.vue'
let form = useForm({
  firstName: null,
  lastName: null,
})
</script>
