<template>
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">{{ category.name }}</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/categories" class="nav-link">
          Back to categories
        </nav-link>
      </h6>
    </div>
  </div>
  <table class="table">
    <tr>
      <td><th>Name</th></td>
      <td class="text-danger">{{category.name}}</td>
    </tr>
    <tr>
      <td><th>Created Date </th></td>
      <td class="text-danger">{{category.createDate }}</td>
    </tr>
    <tr>
      <td><th>Total Books </th></td>
      <td class="text-danger">{{category.totalBooks }}</td>
    </tr>
  </table>
  <div class="col-md-12" v-if="category.totalBooks > 0">
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
<Table>
  <thead>
    <th>Id</th>
    <th>Name</th>
    <th>Authors</th>
    <th>Language</th>
    <th>Others Languages</th>
  </thead>
  <tbody>
    <tr v-for="book in books.data">
      <td>{{book.id}}</td>
      <td>
        <nav-link :href="`/books/${book.id}`">{{book.title}}</nav-link>
        
      </td>
      
      <td>
        <nav-link v-for="author in book.authors" :href="`/authors/${author.id}`"><span class="p-2">{{author.name}}</span></nav-link>
      </td>
      
      <td>
        {{  book.orignalLanguage }}
      </td>
      <td>
        <nav-link v-if="book.versions" :href="`/books/${version.id}`" v-for="version in book.versions"><span>{{version.language}}</span></nav-link>
        <nav-link v-else-if="book.orignal" :href="`/books/${book.orignal.id}`">{{book.orignal.language}}</nav-link>
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
import { reactive } from 'vue'
import { router,usePage } from '@inertiajs/vue3';
import debounce from 'lodash.debounce';
import BtnLink from '../../Shared/BtnLink.vue';
defineProps({ category: Object, books: Object })
let search = reactive({
  picked: 'title',
  value:null
});

const onSearch = debounce((value) => {
  let id = usePage().props.category.id;
  if (search.picked == 'title') {
    search.value = value
    router.get(`/categories/${id}`,{title:search.value},{preserveScroll:true,preserveState:true,replace:true})
  } else {
    search.value = value
    router.get(`/categories/${id}`,{author:search.value},{preserveScroll:true,preserveState:true,replace:true})
  }
})

function getResults(page = 1) {
  let id = usePage().props.category.id;
  if (search.picked == 'title' && search.value) {
    router.get(`/categories/${id}`,{page:page,title:search.value},{preserveScroll:true,preserveState:true,replace:true})
  } else if (search.picked == 'author') {
        router.get(`/categories/${id}`,{page:page,author:search.value},{preserveScroll:true,preserveState:true,replace:true})
  }
  else {
    router.get(`/categories/${id}`,{page:page},{preserveScroll:true,preserveState:true,replace:true})
  }
}
</script>
