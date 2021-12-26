<template>
  <tree-select placeholder="请选择上级规则" :props="props" v-model="val" :options="options" @input="$emit('input',$event)"/>
</template>

<script>
import TreeSelect from '@/components/TreeSelect/index'

export default {
  name: 'MenusSelect',
  components: { TreeSelect },
  props:['value'],
  data() {
    return {
      options: [],
      val: undefined,
      props: {
        label: 'title'
      }
    }
  },
  created() {
    this.getRuleData()
  },
  methods: {
    getRuleData() {
      this.$http.post('rule/tree')
          .then(({ data }) => {
            this.options = [{
              title: '顶级菜单',
              id: 0,
              children: data
            }]
          })
    }
  },
  watch: {
    value: {
      immediate: true,
      handler(val) {
        this.val = val
      }
    }
  }
}
</script>

<style scoped>

</style>
