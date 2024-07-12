<template>
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">{{member.name}}</h6>
      <h6 class="m-0 font-weight-bold text-primary">
        <nav-link href="/members" class="nav-link">
          Back to Members...
        </nav-link>
      </h6>
    </div>
  </div>
  <table class="table">
    <tr>
        <td><th>Name</th></td>
        <td class="text-danger">{{member.name}}</td>
    </tr>
    <tr>
        <td><th>Email</th></td>
        <td class="text-danger">{{member.email}}</td>
    </tr>
    <tr>
        <td><th>Phone</th></td>
        <td class="text-danger">{{member.phone}}</td>
    </tr>
    <tr>
        <td><th>Age</th></td>
        <td class="text-danger">{{member.age}}</td>
    </tr>
    <tr>
        <td><th>Address</th></td>
        <td class="text-danger">{{member.address}}</td>
    </tr>
    <tr>
      <td><th>Total issued books</th></td>
      <td class="text-danger">{{member.totalIsuedBook}}</td>
    </tr>
    <tr v-if="member.currentBook">
        <td><th>Borrowed Book</th></td>
        <td class="text-danger">
          <nav-link :href="`/borrowings/${member.currentBook.id}`">{{member.currentBook.bookTitle}}</nav-link>
        </td>
    </tr>
    <tr v-if="member.currentBook">
      <td><th>Issue date</th></td>
      <td class="text-danger">{{member.currentBook.issueDate}}</td>
    </tr>
    {{ console.log(member.currentBook) }}
  </table>
</template>
<script setup>
defineProps({ member: Object })
</script>
