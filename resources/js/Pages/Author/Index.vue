<template>
  <div class="row my-3">
    <div class="col-md-12">
      <div class="col-md-6">
        <Search @search-trigged="onSearch" />
      </div>
    </div>
  </div>
  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Authors</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/authors/create" class="nav-link">
          Add new Author
        </nav-link>
      </h6>
    </div>
    <Table>
      <thead>
        <tr>
          <th>Id</th>
          <th>Name</th>
          <th>Total Books</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="author in authors.data">
          <td>{{ author.id }}</td>
          <td>{{ author.name }}</td>
          <td>
            {{ author.totalBooks }}
          </td>
          <td>
            <BtnLink class="btn-info mx-2" :href="`/authors/${author.id}`">
              View
            </BtnLink>
            <BtnLink
              class="btn-primary mx-2"
              :href="`/authors/${author.id}/edit`"
            >
              Update
            </BtnLink>
          </td>
        </tr>
      </tbody>
    </Table>
    <Bootstrap4Pagination
      :data="authors"
      @pagination-change-page="getResults"
    />
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import debounce from 'lodash.debounce'
import { ref } from 'vue'
import BtnLink from '../../Shared/BtnLink.vue'
import Search from '../../Shared/Search.vue'
defineProps({ authors: Object })
function getResults(page = 1) {
  if (name.value != null) {
    router.visit(`authors?page=${page}`)
  } else {
    router.get(
      `/authors`,
      { page: page, name: name },
      {
        preserveScroll: true,
        preserveState: true,
        replace: true,
      },
    )
  }
}
let name = ref('')
const onSearch = debounce((value) => {
  name = value
  router.get(
    `/authors`,
    { name: name },
    { preserveScroll: true, preserveState: true, replace: true },
  )
}, 1000)
</script>
