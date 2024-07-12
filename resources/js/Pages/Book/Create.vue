<template>
  <Form :form-heading="`Add new book!`">
    <form
      @submit.prevent="
        form.post('/books', {
          //onSuccess: () => form.reset(),
        })
      "
    >
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <p class="text-danger">
              If you saved origan book and want to save it's translation version
              then click is translation and select origan book
            </p>
            <label for="">
              <input
                type="radio"
                v-model="form.picked"
                value="isTranslation"
                @click="getBooks"
                @dblclick="form.picked = null"
              />
              is Translation
            </label>
          </div>
          <div class="col-md-6">
            <p class="text-danger">
              if you saved translation version and want to save origan book then
              select it's translation version
            </p>
            <label for="" class="ml-2">
              <input
                type="radio"
                v-model="form.picked"
                value="isOrigen"
                @click="getBooks"
                @dblclick="form.picked = null"
              />
              is Origan
            </label>
          </div>
        </div>
      </div>
      <div class="form-group" v-if="form.picked">
        <label for="">Books</label>

        <Multiselect
          :options="books"
          :searchable="true"
          v-model="form.book"
          @select="onSelect"
          noOptionsText="The list is empty"
        />
        <p class="text-danger" v-if="form.errors.book">
          {{ form.errors.book }}
        </p>
      </div>
      <div class="form-group">
        <label for="">Title</label>
        <input type="text" class="form-control" v-model="form.title" />
        <p class="text-danger" v-if="form.errors.title">
          {{ form.errors.title }}
        </p>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label for="">Category</label>
            <Multiselect
              :options="categories"
              :searchable="true"
              :object="true"
              noOptionsText="The list is empty"
              v-model="form.category"
            />
            <p class="text-danger" v-if="form.errors.category">
              {{ form.errors.category }}
            </p>
          </div>
          <div class="col-md-6">
            <label for="">Language</label>
            <Multiselect
              :options="languages"
              :searchable="true"
              noOptionsText="The list is empty"
              v-model="form.language"
            />
            <p class="text-danger" v-if="form.errors.language">
              {{ form.errors.language }}
            </p>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="">Copy</label>
        <input type="number" class="form-control" v-model="form.copy" />
        <p class="text-danger" v-if="form.errors.copy">
          {{ form.errors.copy }}
        </p>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label for="">Authors</label>
            <Multiselect
              :options="authors"
              :searchable="true"
              noOptionsText="The list is empty"
              v-model="form.authors"
              mode="multiple"
            />
            <p class="text-danger" v-if="form.errors.authors">
              {{ form.errors.authors }}
            </p>
          </div>
          <div class="col-md-6">
            <label for="">Publishers</label>
            <Multiselect
              :options="publishers"
              :searchable="true"
              v-model="form.publishers"
              mode="multiple"
              noOptionsText="The list is empty"
            />
            <p class="text-danger" v-if="form.errors.publishers">
              {{ form.errors.publishers }}
            </p>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="">Publishe Date</label>
        <input type="date" class="form-control" v-model="form.publishDate" />
        <p class="text-danger" v-if="form.errors.publishDate">
          {{ form.errors.publishDate }}
        </p>
      </div>
      <button class="btn btn-outline-primary" :disabled="form.processing">
        Create
      </button>
      <hr />
      <BtnLink class="btn-outline-danger" href="/books">Cancel</BtnLink>
    </form>
  </Form>
</template>
<script setup>
import Form from '../../Shared/Form.vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'
import { ref } from 'vue'
import BtnLink from '../../Shared/BtnLink.vue'
defineProps({
  categories: Object,
  languages: Object,
  authors: Object,
  publishers: Object,
})
let form = useForm({
  picked: null,
  book: null,
  title: null,
  category: null,
  language: null,
  copy: null,
  authors: [],
  publishers: [],
  publishDate: null,
})
const books = ref({})
function getBooks() {
  axios.get('/api/books').then((response) => {
    books.value = response.data
  })
}

function onSelect(value) {
  axios.get(`/api/books?version=${value}`).then((response) => {
    form.category = response.data
  })
}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
