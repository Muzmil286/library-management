<template>
  <Form :form-heading="`Add a new Member!`">
    <form
      @submit.prevent="
        form.post('/members', {
          onSuccess: () => form.reset(),
        })
      "
    >
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label>First Name</label>
            <input
              type="text"
              placeholder="Enter Member First Name"
              class="form-control"
              v-model="form.firstName"
            />
            <p class="text-danger" v-if="form.errors.firstName">
              {{ form.errors.firstName }}
            </p>
          </div>
          <div class="col-md-6">
            <label>Last Name</label>
            <input
              type="text"
              placeholder="Enter Member Last Name"
              class="form-control"
              v-model="form.lastName"
            />
            <p class="text-danger" v-if="form.errors.lastName">
              {{ form.errors.lastName }}
            </p>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label>Age</label>
            <input
              type="number"
              placeholder="Enter Member Age"
              class="form-control"
              v-model="form.age"
            />
            <p class="text-danger" v-if="form.errors.age">
              {{ form.errors.age }}
            </p>
          </div>
          <div class="col-md-6">
            <label>Address</label>
            <input
              type="text"
              placeholder="Enter Member Address"
              class="form-control"
              v-model="form.address"
            />
            <p class="text-danger" v-if="form.errors.address">
              {{ form.errors.address }}
            </p>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label>Contact Number</label>
            <input type="number" class="form-control" v-model="form.phone" />
            <p class="text-danger" v-if="form.errors.phone">
              {{ form.errors.phone }}
            </p>
          </div>
          <div class="col-md-6">
            <label>C.N.I.C</label>
            <input type="number" class="form-control" v-model="form.cnic" />
            <p class="text-danger" v-if="form.errors.cnic">
              {{ form.errors.cnic }}
            </p>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input
          type="email"
          class="form-control"
          v-model="form.email"
          placeholder="Enter Member Email"
        />
        <p class="text-danger" v-if="form.errors.email">
          {{ form.errors.email }}
        </p>
      </div>
      <button class="btn btn-outline-primary" :disabled="form.processing">
        Create
      </button>
      <hr />
      <BtnLink class="btn-outline-danger" href="/members">Cancel</BtnLink>
    </form>
  </Form>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
import BtnLink from '../../Shared/BtnLink.vue'
let form = useForm({
  firstName: null,
  lastName: null,
  age: null,
  address: null,
  phone: null,
  cnic: null,
  email: null,
})
</script>
