<template>
  <div class="row my-3">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-6">
          <Search @search-trigged="onSearch" />
          <p class="text-danger">Search by these feilds</p>
          <div class="form-group mt-2">
            <label for="" class="ml-2">
              <input
                type="radio"
                v-model="search.picked"
                value="title"
                checked
              />
              Title
            </label>
            <label for="">
              <input
                type="radio"
                v-model="search.picked"
                class="ml-2"
                value="author"
                :checked="search.picked == 'author'"
              />
              author
            </label>
            <label>
              <input
                type="radio"
                class="ml-2"
                v-model="search.picked"
                value="language"
                :checked="search.picked == 'language'"
              />
              Language
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Books</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/books/create" class="nav-link">
          Add new Book
        </nav-link>
      </h6>
    </div>
    <Table>
      <thead>
        <th>Id</th>
        <th>Title</th>
        <th>Authors</th>
        <th>Actions</th>
      </thead>
      <tbody>
        <tr v-for="book in books.data">
          <td>{{ book.id }}</td>
          <td>{{ book.title }}</td>

          <td>
            <nav-link
              :href="`/authors/${author.id}`"
              v-for="author in book.authors"
            >
              <span class="p-2">{{ author.name }}</span>
            </nav-link>
          </td>
          <td>
            <BtnLink class="btn-info mx-2" :href="`/books/${book.id}`">
              View
            </BtnLink>
            <BtnLink class="btn-primary mx-2" :href="`/books/${book.id}/edit`">
              Update
            </BtnLink>
            <DeleteButton @delete-trigged="onDelete(book.id)">
              Delete
            </DeleteButton>
          </td>
        </tr>
      </tbody>
    </Table>
    <Bootstrap4Pagination :data="books" @pagination-change-page="getResults" />
  </div>
</template>
<script setup>
import Search from '../../Shared/Search.vue'
import { reactive } from 'vue'
import debounce from 'lodash.debounce'
import { router } from '@inertiajs/vue3'
import BtnLink from '../../Shared/BtnLink.vue'
import DeleteButton from '../../Shared/DeleteButton.vue'
defineProps({ books: Object, categories: Object })
let search = reactive({
  value: null,
  picked: null,
})
const onSearch = debounce((value) => {
  search.value = value
  if (search.picked == 'language') {
    router.get(
      '/books',
      { language: search.value },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  } else if (search.picked == 'author') {
    router.get(
      '/books',
      { author: search.value },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  } else {
    router.get(
      '/books',
      { title: search.value },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  }
})

function getResults(page = 1) {
  if (search.picked == 'title') {
    router.get(
      '/books',
      { page: page, title: search.value },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  } else if (search.picked == 'author') {
    router.get(
      '/books',
      { page: page, author: search.value },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  } else if (search.picked == 'language') {
    router.get(
      '/books',
      { page: page, language: search.value },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  } else {
    router.get(
      '/books',
      { page: page },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  }
}
function onDelete(id) {
  if (!confirm('Are you sure?')) {
    return
  }
  router.delete(`/books/${id}`)
}
</script>
