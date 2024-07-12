<template>
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">{{ book.title }}</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/books" class="nav-link">
          Back to Books..
        </nav-link>
      </h6>
    </div>
  </div>
  <table class="table">
<tr>
    <td><th>Title</th></td>
    <td class="text-danger">{{book.title}}</td>
</tr>
<tr>
    <td><th>Language</th></td>
    <td class="text-danger">{{book.language}}</td>
</tr>
<tr>
  <td><th>Category</th></td>
  <td class="text-danger">
    <span v-if="book.category">{{book.category}}</span>
    <nav-link :href="`/books/${book.id}/edit`" v-else>Assign to a category</nav-link>
  </td>
</tr>
<tr>
    <td><th>Other Language</th></td>
    <td>
        <nav-link v-if="book.versions" v-for="version in book.versions" :href="`/books/${version.id}`"><span>{{version.language}}</span></nav-link>
        <nav-link v-else-if="book.orignal" :href="`/books/${book.orignal.id}`">{{book.orignal.language}}</nav-link>
        <span class="text-danger" v-else>Not Availbale</span>
    </td>
</tr>
<tr>
  <td><th>Copy</th></td>
  <td>{{book.copy}}</td>
</tr>
<tr>
  <td><th>Available Copy</th></td>
  <td class="text-danger">{{book.availableCopy}}</td>
</tr>
<tr v-if="book.members">
  <td><th>Members</th></td>
  <td>
    <nav-link  v-for="member in book.members" :href="`/members/${member.id}`" >{{member.name}}</nav-link>
  </td>
</tr>
  </table>
</template>
<script setup>
defineProps({ book: Object })
</script>
