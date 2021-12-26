<template>
  <el-card shadow="never">
  </el-card>
</template>

<script>
import {hasPermission} from "@/store/modules/permission";


export default {
  name: "dashboard",
  methods:{
    click(){
      console.log(hasPermission('asdf'))
    }
  }
}
</script>

<style scoped>

</style>
