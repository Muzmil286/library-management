<template>
  <div class="row my-3">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-6">
          <Search @search-trigged="onSearch" />
          <p class="text-danger">Search by these feilds</p>
          <div class="form-group mt-2">
            <label for="" class="ml-2">
              <input type="radio" v-model="search.picked" value="title" />
              Title
            </label>
            <label for="">
              <input
                type="radio"
                v-model="search.picked"
                class="ml-2"
                value="user"
                :checked="search.picked == 'user'"
              />
              User
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Borrowings</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/borrowings/create" class="nav-link">
          Borrow new book
        </nav-link>
      </h6>
    </div>
    <Table>
      <thead>
        <th>Id</th>
        <th>Book</th>
        <th>Member</th>
        <th>Actions</th>
      </thead>
      <tbody>
        <tr v-for="borrow in borrowings.data">
          <td>{{ borrow.id }}</td>
          <td>
            <nav-link :href="`/books/${borrow.book.id}`">
              {{ borrow.book.title }}
            </nav-link>
          </td>
          <td>
            <nav-link :href="`/members/${borrow.member.id}`">
              {{ borrow.member.name }}
            </nav-link>
          </td>
          <td>
            <!-- <BtnLink :href="`/borrowings/${borrow.id}`" class="btn-info mx-2">
              View
            </BtnLink> -->
            <BtnLink
              :href="`/borrowings/${borrow.id}/edit`"
              class="btn-primary mx-2"
            >
              Update
            </BtnLink>
            <button @click.prevent="onReturn(borrow.id)" class="btn btn-danger">
              Return
            </button>
          </td>
        </tr>
      </tbody>
    </Table>
    <Bootstrap4Pagination
      :data="borrowings"
      @pagination-change-page="getResults"
    />
  </div>
</template>
<script setup>
import BtnLink from '../../Shared/BtnLink.vue'

import { router } from '@inertiajs/vue3'
import Search from '../../Shared/Search.vue'
import { reactive } from 'vue'
import debounce from 'lodash.debounce'
defineProps({ borrowings: Object })
function onReturn(id) {
  if (!confirm('Are you sure')) {
    return
  }
  router.post(`/borrowings/${id}/return`)
}
let search = reactive({
  picked: 'title',
  value: null,
})
const onSearch = debounce((value) => {
  search.value = value
  if (search.picked == 'user') {
    router.get(
      `/borrowings`,
      { user: value },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  } else {
    router.get(
      `/borrowings`,
      { title: value },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  }
})

function getResults(page = 1) {
  if (search.picked == 'user') {
    router.get(
      `/borrowings`,
      { user: search.value, page: page },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  } else {
    router.get(
      `/borrowings`,
      { page: page },
      { preserveScroll: true, preserveState: true, replace: true },
    )
  }
}
</script>
