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
      <h6 class="m-0 font-weight-bold text-primary">Categories</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/categories/create" class="nav-link">
          Add new category
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
        <tr v-for="category in categories.data" :key="category.id">
          <td>{{ category.id }}</td>
          <td>{{ category.name }}</td>
          <td>
            {{ category.totalBooks }}
          </td>
          <td>
            <BtnLink :href="`/categories/${category.id}`" class="btn-info mx-2">
              View
            </BtnLink>
            <BtnLink
              class="btn-primary mx-2"
              :href="`/categories/${category.id}/edit`"
            >
              Update
            </BtnLink>
            <DeleteButton @delete-trigged="onDelete(category.id)" />
          </td>
        </tr>
      </tbody>
    </Table>
    <Bootstrap4Pagination
      :data="categories"
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
defineProps({ categories: Object })
function getResults(page = 1) {
  router.get(
    `/categories`,
    { page: page },
    { preserveScroll: true, preserveState: true, replace: true },
  )
}

const onSearch = debounce((value) => {
  router.get(
    '/categories',
    { name: value },
    { preserveScroll: true, preserveState: true, replace: true },
  )
}, 1000)
const onDelete = (id) => {
  if (!confirm('Are you sure')) {
    return
  }
  router.delete(`/categories/${id}`)
}
</script>
