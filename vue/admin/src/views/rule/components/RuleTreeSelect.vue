<template>
  <el-cascader
      style="width: 100%"
      v-model="val"
      :props="props"
      :options="options"
      @change="handleChange"
      placeholder="请选择权限"
  />
</template>

<script>
export default {
  name: 'RuleTreeSelect',
  props: {
    value: {
      type: Array,
      default: () => []
    }
  },
  watch: {
    value: {
      immediate: true,
      handler(val) {
        this.val = val
      }
    }

  },
  created() {
    this.getRuleData()
  },
  data() {
    return {
      val: [],
      props: {
        multiple: true,
        emitPath: false
      },
      options: []
    }
  },
  methods: {
    getRuleData() {
      this.$http.post('common/permissions')
          .then(({data}) => {
            this.options = data
          })
    },
    handleChange(value) {
      this.$emit('input', value)
    }
  }
}
</script>

<style>
.el-scrollbar__wrap {
  margin-bottom: 0 !important;
}
</style>
