<template>
  <el-select multiple v-model="role_ids" @change="$emit('input',role_ids)">
    <el-option v-for="item in list" :key="item.id" :label="item.name" :value="item.id"/>
  </el-select>
</template>

<script>
export default {
  name: 'RoleSelect',
  props: {
    value: Array
  },
  data() {
    return {
      list: [],
      role_ids: []
    }
  },
  mounted() {
    this.getData()
  },
  methods: {
    getData() {
      this.$http.post('role/list')
          .then(({ data }) => {
            this.list = data
          })
    }
  },
  watch: {
    value: {
      immediate: true,
      deep: true,
      handler(val) {
        this.role_ids = val
      }
    }
  }
}
</script>

<style scoped>

</style>
