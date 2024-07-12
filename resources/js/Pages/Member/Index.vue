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
      <h6 class="m-0 font-weight-bold text-primary">Members</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/members/create" class="nav-link">
          Add new Member
        </nav-link>
      </h6>
    </div>
    <Table>
      <thead>
        <th>Id</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Actions</th>
      </thead>
      <tbody>
        <tr v-for="member in members.data">
          <td>{{ member.id }}</td>
          <td>{{ member.name }}</td>
          <td>{{ member.email }}</td>
          <td>{{ member.phone }}</td>
          <td>
            <BtnLink :href="`/members/${member.id}`" class="btn-info mx-2">
              View
            </BtnLink>
            <BtnLink
              :href="`/members/${member.id}/edit`"
              class="btn-primary mx-2"
            >
              Update
            </BtnLink>
            <DeleteButton @delete-trigged="onDelete(member.id)">
              Delete
            </DeleteButton>
          </td>
        </tr>
      </tbody>
    </Table>
    <Bootstrap4Pagination
      :data="members"
      @pagination-change-page="getResults"
    />
  </div>
</template>
<script setup>
import Search from '../../Shared/Search.vue'
import BtnLink from '../../Shared/BtnLink.vue'
import DeleteButton from '../../Shared/DeleteButton.vue'
import { router } from '@inertiajs/vue3'
import debounce from 'lodash.debounce'
import { ref } from 'vue'
defineProps({
  members: Object,
})

let name = ref(null)
const onSearch = debounce((value) => {
  name.value = value
  router.get(
    '/members',
    { name: value },
    { preserveScroll: true, preserveState: true, replace: true },
  )
}, 1000)

function getResults(page = 1) {
  if (name.value != null) {
    router.get(
      '/members',
      { page: page, name: name.value },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  } else {
    router.get(
      '/members',
      { page: page },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  }
}

function onDelete(id) {
  if (!confirm('Are you sure')) {
    return
  }
  router.delete(`/members/${id}`)
}
</script>
