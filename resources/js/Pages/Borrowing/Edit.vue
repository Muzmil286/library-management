<template>
  <Form :form-heading="`Borrow update`">
    <form @submit.prevent="form.put(`/borrowings/${borrowing.id}`)">
      <div class="form-group">
        <label for="">Books</label>
        <Multiselect :options="books" :searchable="true" v-model="form.book" />
        <p class="text-danger" v-if="form.errors.book">
          {{ form.errors.book }}
        </p>
      </div>
      <div class="form-group">
        <label for="">Members</label>
        <Multiselect
          :options="members"
          :searchable="true"
          v-model="form.member"
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
        Update
      </button>
      <hr />
      <BtnLink class="btn-outline-danger" href="/borrowings">Cancel</BtnLink>
    </form>
  </Form>
</template>
<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import BtnLink from '../../Shared/BtnLink.vue'
defineProps({ books: Object, members: Object, borrowing: Object })
let form = useForm({
  issueDate: usePage().props.borrowing.issue_date,
  member: usePage().props.borrowing.member_id,
  book: usePage().props.borrowing.book_id,
})
</script>
