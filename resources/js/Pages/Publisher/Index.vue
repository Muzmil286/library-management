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
      <h6 class="m-0 font-weight-bold text-primary">Publishers</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/publishers/create" class="nav-link">
          Add new Publishers
        </nav-link>
      </h6>
    </div>
    <Table>
      <thead>
        <th>Id</th>
        <th>Name</th>
        <th>Total Books</th>
        <th>Actions</th>
      </thead>
      <tbody>
        <tr v-for="publisher in publishers.data">
          <td>{{ publisher.id }}</td>
          <td>{{ publisher.name }}</td>
          <td>{{ publisher.totalBooks }}</td>
          <td>
            <BtnLink
              :href="`/publishers/${publisher.id}`"
              class="btn-info mx-2"
            >
              View
            </BtnLink>
            <BtnLink
              :href="`/publishers/${publisher.id}/edit`"
              class="btn-primary"
            >
              Update
            </BtnLink>
          </td>
        </tr>
      </tbody>
    </Table>
    <Bootstrap4Pagination
      :data="publishers"
      @pagination-change-page="getResults"
    />
  </div>
</template>

<script setup>
import debounce from 'lodash.debounce'
import BtnLink from '../../Shared/BtnLink.vue'
import { router } from '@inertiajs/vue3'
import Search from '../../Shared/Search.vue'
defineProps({ publishers: Object })

const onSearch = debounce((value) =>
  router.get(
    '/publishers',
    { name: value },
    { preserveScroll: true, preserveState: true, replace: true },
  ),
)
function getResults(page = 1) {
  router.get(
    '/publishers',
    { page: page },
    { preserveScroll: true, preserveState: true },
  )
}
</script>
