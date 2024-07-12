<template>
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">{{ language.name }}</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/languages" class="nav-link">
          Back to Languages...
        </nav-link>
      </h6>
    </div>
  </div>
  
  <table class="table">
<tr>
  <td><th>Name</th></td>
  <td><span class="text-danger">{{language.name}}</span></td>
</tr>
<tr>
  <td><th>Added</th></td>
  <td><span class="text-danger">{{language.addedAgo}}</span></td>
</tr>
<tr>
  <td><th>Total Books</th></td>
  <td><span class="text-danger">{{language.totalBooks}}</span></td>
</tr>

  </table>
  <div class="row" v-if="language.totalBooks > 0">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-6">
<Search @search-trigged="onSearch" />
<div class="form-group mt-2">
<label for="">
  <input type="radio" v-model="search.picked" value="author" :checked="search.picked === 'author'">
  Author
</label>
<label for="" class="ml-2">
  <input type="radio" v-model="search.picked"  value="title" :checked="search.picked =='title'">
  Title
</label>
</div>

        </div>
      </div>
    </div>
    <div class="col-md-12">
      <Table>
        <thead>
          <th>Book Id</th>
          <th>Book Title</th>
          <th>Authors</th>
          <th>Book Category</th>
        </thead>
        <tbody>
          <tr v-for="book in books.data">
            <td>{{book.id}}</td>
            <td>
              <nav-link :href="`/books/${book.id}`">{{book.title}}</nav-link>
            </td>
            <td>
              <nav-link :href="`/authors/${author.id}`" v-for="author in book.authors"><span class="p-2">{{author.name}}</span></nav-link>
            </td>
            <td>
              <nav-link :href="`/categories/${book.categoryId}`" v-if="book.categoryId">{{book.categoryName}}</nav-link>
              <nav-link :href="`/books/${book.id}/edit`" v-else>Assign to a category</nav-link>
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
import { reactive } from 'vue'
import { router,usePage } from '@inertiajs/vue3';
defineProps({ language: Object, books: Object })
let search = reactive({
  picked: 'title',
  value: null,
  id:usePage().props.language.id
})
const onSearch = debounce((value) => {
  search.value = value
  if (search.picked == 'author') {
    return router.get(`/languages/${search.id}`,{author:search.value},{preserveScroll:true,preserveState:true,replace:true})
  }
   router.get(`/languages/${search.id}`,{title:search.value},{preserveScroll:true,preserveState:true,replace:true})
}, 1000)


function getResults(page = 1) {
  if (search.picked == 'title' && search.value) {
    router.get(`/languages/${search.id}`,{page:page,title:search.value},{preserveScroll:true,preserveState:true,replace:true})
  } else if (search.picked == 'author') {
    router.get(`/languages/${search.id}`,{page:page,author:search.value},{preserveScroll:true,preserveState:true,replace:true})
  } else {
   router.get(`/languages/${search.id}`,{page:page},{preserveScroll:true,preserveState:true,replace:true}) 
  }
}
</script>
