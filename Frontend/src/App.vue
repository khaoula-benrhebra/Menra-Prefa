<template>
  <div id="app">
    
    <AppHeader v-if="$route.name !== 'Dashboard'" />
    
    <router-view />
    
    <AppFooter v-if="$route.name !== 'Dashboard'" />
  </div>
</template>

<script>
import AppHeader from './components/layout/AppHeader.vue'
import AppFooter from './components/layout/AppFooter.vue'

export default {
  name: 'App',
  components: {
    AppHeader,
    AppFooter
  }
}
</script>

<style>
/* Styles globaux pour l'application */
body {
  margin: 0;
  font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

#app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}
</style>