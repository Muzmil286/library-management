<template>
  <div class="row my-3">
    <div class="col-md-12">
      <div class="col-md-6">
        <Search @search-trigged="onSearch" />
      </div>
    </div>
  </div>
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Authors</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/languages/create" class="nav-link">
          Add new Language
        </nav-link>
      </h6>
    </div>
    <Table>
      <thead>
        <th>id</th>
        <th>Name</th>
        <th>Total Books</th>
        <th>Actions</th>
      </thead>
      <tbody>
        <tr v-for="language in languages.data">
          <td>{{ language.id }}</td>
          <td>{{ language.name }}</td>
          <td>
            {{ language.totalBooks }}
          </td>
          <td>
            <BtnLink class="btn-info mx-2" :href="`/languages/${language.id}`">
              View
            </BtnLink>
            <BtnLink
              class="btn-primary mx-2"
              :href="`/languages/${language.id}/edit`"
            >
              Update
            </BtnLink>
            <DeleteButton @delete-trigged="onDelete(language.id)">
              Delete
            </DeleteButton>
          </td>
        </tr>
      </tbody>
    </Table>
    <Bootstrap4Pagination
      :data="languages"
      @pagination-change-page="getResults"
    />
  </div>
</template>
<script setup>
import Search from '../../Shared/Search.vue'
import debounce from 'lodash.debounce'
import { router } from '@inertiajs/vue3'
import BtnLink from '../../Shared/BtnLink.vue'
import DeleteButton from '../../Shared/DeleteButton.vue'
defineProps({ languages: Object })

const onSearch = debounce(
  (value) =>
    router.get(
      '/languages',
      { name: value },
      { preserveScroll: true, preserveState: true, replace: true },
    ),
  1000,
)

function getResults(page = 1) {
  router.get(
    '/languages',
    { page: page },
    { preserveScroll: true, preserveState: true, replace: true },
  )
}
function onDelete(id) {
  if (!confirm('Are you sure')) {
    return
  }
  router.delete(`/languages/${id}`)
}
</script>
