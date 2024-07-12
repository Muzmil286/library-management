<template>
  <Form :form-heading="`Add new book!`">
    <form @submit.prevent="form.put(`/books/${book.id}`)">
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
                :checked="book.picked == 'translation'"
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
                :checked="book.picked == 'orignal'"
                value="orignal"
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
          noOptionsText="The list is empty"
          :object="true"
          :mode="book.versions ? 'multiple' : 'single'"
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
              v-model="form.category"
              noOptionsText="The list is empty"
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
              v-model="form.language"
              noOptionsText="The list is empty"
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
              :object="true"
              :label="`label`"
              v-model="form.authors"
              mode="multiple"
              noOptionsText="The list is empty"
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
              :object="true"
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
import { useForm, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import BtnLink from '../../Shared/BtnLink.vue'

defineProps({
  categories: Object,
  languages: Object,
  authors: Object,
  publishers: Object,
  book: Object,
})
let form = useForm({
  picked: usePage().props.book.picked,
  title: usePage().props.book.title,
  book: usePage().props.book.versions
    ? usePage().props.book.versions
    : usePage().props.book.orignal,
  category: usePage().props.book.category,
  language: usePage().props.book.language,
  copy: usePage().props.book.copy,
  authors: usePage().props.book.authors,
  publishers: usePage().props.book.publishers,
  publishDate: usePage().props.book.publishDate,
})
const books = ref({})
function getBooks() {
  if (form.picked) {
    axios
      .get(`/api/books?title=${usePage().props.book.title}`)
      .then((response) => {
        books.value = response.data
        console.log(books.value)
      })
  }
}
getBooks()
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
