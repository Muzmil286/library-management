<template>
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">{{ publisher.name }}</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/publishers" class="nav-link">
          Back to Publishers...
        </nav-link>
      </h6>
    </div>
  </div>
  <table class="table">
    <tr>
        <td><th>Name</th></td>
        <td class="text-danger">{{publisher.name}}</td>
    </tr>
    <tr>
        <td><th>Add Date</th></td>
        <td class="text-danger">{{publisher.addDate}}</td>
    </tr>
    <tr>
        <td><th>Total Books</th></td>
        <td class="text-danger">{{publisher.totalBooks}}</td>
    </tr>
  </table>
  <div class="col-md-12" v-if="publisher.totalBooks > 0">
    <div class="row">
<div class="col-md-12 xy-2">
<div class="row">
<div class="col-md-6">
      <Search @search-trigged="onSearch"/>
      <p class="mt-2">Search by these feilds:</p>
      <label for="" >
        <input type="radio" v-model="search.picked" value="title">
        Title
      </label>
      <label for="" class="ml-2" >
        <input type="radio" v-model="search.picked" value="author" :checked="search.picked == 'author'">
        Author
      </label>
</div>
</div>
  </div>
    </div>
    <div class="row my-3">
<Table>
    <thead>
        <th>Id</th>
        <th>Title</th>
        <th>Authors</th>
        <th>Language</th>
        <th>Other Language</th>
    </thead>
    <tbody>
        <tr v-for="book in books.data">
            <td>{{book.id}}</td>
            <td>
              <nav-link :href="`/books/${book.id}`">{{book.title}}</nav-link>
            </td>
            <td>
              <nav-link :href="`/authors/${author.id}`" v-for="author in book.authors">{{author.name}}</nav-link>
            </td>
            <td>
              {{ book.orignalLanguage }}
            </td>
            <td>
              <nav-link :href="`/books/${version.id}`" v-if="book.versions" v-for="version in book.versions">{{version.language}}</nav-link>
              <nav-link v-else-if="book.orignal" :href="`/books/${book.orignal.id}`">{{book.orignal.language}}</nav-link>
              <span class="text-danger" v-else>Not Available</span>
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
import { reactive } from 'vue'
import { usePage } from '@inertiajs/vue3';
import Search from '../../Shared/Search.vue'
import { router } from '@inertiajs/vue3';
import debounce from 'lodash.debounce';
defineProps({ publisher: Object, books: Object })
let search = reactive({
  picked: 'title',
  value: null,
  id: usePage().props.publisher.id
})

const onSearch = debounce((value) => {
  search.value = value
  if (search.picked == 'author') {
    router.get(`/publishers/${search.id}`,{author:value},{preserveScroll:true,preserveState:true,replace:true})
  } else {
    router.get(`/publishers/${search.id}`,{title:value},{preserveScroll:true,preserveState:true,replace:true})
  }
}, 1000)

function getResults(page = 1) {
  if (search.picked == 'title' && search.value) {
    router.get(`/publishers/${search.id}`,{title:search.value,page:page},{preserveScroll:true,preserveState:true,replace:true})
  } else if (search.picked == 'author') {
    router.get(`/publishers/${search.id}`,{author:search.value,page:page},{preserveScroll:true,preserveState:true,replace:true})
  }
  else {
    router.get(`/publishers/${search.id}`,{page:page},{preserveScroll:true,preserveState:true,replace:true})
  }
}
</script>
