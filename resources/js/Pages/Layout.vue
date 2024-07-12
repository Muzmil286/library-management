<template>
  <div id="wrapper">
    <SideBar />
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <NavBar />
        <div class="container-fluid">
          <SuccessMessage v-if="flesh.success" :success="flesh.success" />
          <slot />
        </div>
      </div>
      <Footer />
    </div>
  </div>
</template>
<script setup>
import NavBar from '../Shared/NavBar.vue'
import SideBar from '../Shared/SideBar.vue'
import Footer from '../Shared/Footer.vue'
import SuccessMessage from '../Shared/SuccessMessage.vue'
defineProps({
  flesh: Object,
})
</script>
