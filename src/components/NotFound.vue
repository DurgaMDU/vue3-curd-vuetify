<template>
  <div>
    <h1>Page Not Founded</h1>    
  </div>
</template>

<script>
export default {
  name: 'NotFound',
  props: {
    msg: String,
    default() {
        return 'Page Not Founded'
      } 
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
</style>
