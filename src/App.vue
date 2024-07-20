<template>
    <v-app>      
      <v-app-bar color="grey-lighten-2" elevation="0" >
        
        <v-toolbar >
          <template v-slot:prepend>
            <div class="text-h5">My Project</div>
          </template>

          <v-divider class="ms-3"  inset vertical   ></v-divider>
          <v-toolbar-title>CURD</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn variant="text" to="/">Dashboard</v-btn>
            <v-divider vertical></v-divider>
            <v-btn variant="text" to="/add">Add</v-btn>
            <v-divider vertical></v-divider>
          </v-toolbar-items>

        </v-toolbar>

      </v-app-bar>

      <v-main style="margin-top: -60px;">
        <router-view></router-view>
      </v-main>
    </v-app>
</template>

<script>
export default {
  name: 'App',
  data: () => ({
    naviconcolor: 'grey lighten-1',
    sidebar: true
  }),
  components: {
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
