<template>
  <Form :form-heading="`Borrow a new book`">
    <form
      @submit.prevent="
        form.post('/borrowings', {
          onSuccess: () => form.reset(),
        })
      "
    >
      <div class="form-group">
        <label for="">Books</label>
        <Multiselect :options="books" v-model="form.book" :searchable="true" />
        <p class="text-danger" v-if="form.errors.book">
          {{ form.errors.book }}
        </p>
      </div>
      <div class="form-group">
        <label for="">Members</label>
        <Multiselect
          :options="members"
          v-model="form.member"
          :searchable="true"
        />
        <p class="text-danger" v-if="form.errors.member">
          {{ form.errors.member }}
        </p>
      </div>
      <div class="form-group">
        <label for="">Issue Date</label>
        <input type="date" class="form-control" v-model="form.issueDate" />
        <p class="text-danger" v-if="form.errors.issueDate">
          {{ form.errors.issueDate }}
        </p>
      </div>
      <button class="btn btn-outline-primary" :disabled="form.processing">
        Create
      </button>
      <hr />
      <BtnLink class="btn-outline-danger" href="/borrowings">Cancel</BtnLink>
    </form>
  </Form>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
import BtnLink from '../../Shared/BtnLink.vue'
defineProps({ books: Object, members: Object })
let form = useForm({
  issueDate: null,
  member: null,
  book: null,
})
</script>
