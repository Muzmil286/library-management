<template>
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">{{ author.name }}</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/authors" class="nav-link">
          Back to authors
        </nav-link>
      </h6>
    </div>
  </div>
  <table class="table">
<tr>
  <td><th>Name</th></td>
  <td><span class="text-danger">{{author.name}}</span></td>
</tr>
<tr>
  <td><th>Added Date</th></td>
  <td><span class="text-danger">{{author.addedAgo}}</span></td>
</tr>
<tr>
  <td><th>Total Books</th></td>
  <td><span class="text-danger">{{author.totalBooks}}</span></td>
</tr>
  </table>
  <div class="row" v-if="author.totalBooks > 0">
    <div class="col-md-12">
      <div class="col-md-6">
<Search @search-trigged="onSearch"/>
      </div>
    </div>
    <div class="col-md-12">
      <Table>
        <thead>
          <th>Book Id</th>
          <th>Books</th>
          <th>Category</th>
          <th>Other Languages</th>
        </thead>
        <tbody>
          <tr v-for="book in books.data">
            <td>{{book.id}}</td>
            <td>
              <nav-link :href="`/books/${book.id}`">{{book.title}}</nav-link>
            </td>
            <td>
              <nav-link :href="`/categories/${book.categoryId}`" v-if="book.categoryId ">{{book.categoryName}}</nav-link>
              <nav-link :href="`/books/${book.id}`" v-else>Assign to category</nav-link>
            </td>
            <td>
              <nav-link v-if="book.versions" :href="`/books/${version.id}`" v-for="version in book.versions"><span class="p-2">{{version.language}}</span></nav-link>
              <span v-else class="text-danger">Not Available</span>
            </td>
          </tr>
        </tbody>
      </Table>
    </div>
    <Bootstrap4Pagination
      :data="books"
      @pagination-change-page="getResults"
    />
  </div>
</template>
<script setup>
import Search from '../../Shared/Search.vue';
import debounce from 'lodash.debounce';
import { router , usePage } from '@inertiajs/vue3';
import {reactive} from 'vue'
defineProps({ books: Object, author: Object })
let search = reactive({
  id: usePage().props.author.id,
  value:null
})
const onSearch = debounce((value) => {
  search.value = value;
  router.get(`/authors/${search.id}`,{title : value},{preserveScroll:true,preserveState:true,replace:true})
}, 1000)
function getResults(page = 1) {
  if (search.value) {
    router.get(`/authors/${search.id}`,{page:page,title:search.value},{preserveScroll:true,preserveState:true,replace:true})
  } else {
    router.get(`/authors/${search.id}`,{page:page},{preserveScroll:true,preserveState:true,replace:true})
  }
 }
</script>
