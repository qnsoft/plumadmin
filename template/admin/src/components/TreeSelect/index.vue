<template>
  <el-popover
      placement="bottom-start"
      trigger="click"
      v-model="visible"
  >
    <template v-slot:reference>
      <el-input readonly v-bind="$attrs" :value="title"></el-input>
    </template>
    <div>
      <el-input v-model="keyword" style="width:500px;" clearable prefix-icon="el-icon-search"/>
      <el-tree
          style="margin-top: 10px"
          ref="tree"
          :data="$attrs.options"
          :props="$attrs.props"
          @node-click="handleNodeClick"
          :highlight-current="true"
          :expand-on-click-node="false"
          :filter-node-method="filterNode"
     />
    </div>
  </el-popover>
</template>

<script>
import { treeToArray } from '@/utils'

export default {
  name: 'ThreeSelect',
  props:['value'],
  data(){
    return {
      keyword:'',
      id:undefined,
      visible:false
    }
  },
  computed:{
    arrayOptions(){
      return treeToArray(this.$tools.cloneDeep(this.$attrs.options))
    },
    title(){
      const index = this.arrayOptions.findIndex(e=>{
        return e.id ===this.id
      })
      if(index!==-1){
        return this.arrayOptions[index].title
      }
      return ''
    }
  },
  methods:{
    handleNodeClick(e){
      this.visible = false
      this.id = e.id
      this.$emit('input',e.id)
    },
    filterNode(value, data) {
      if (!value) return true;
      return data.title.indexOf(value) !== -1;
    }
  },
  watch:{
    keyword(val){
      this.$refs.tree.filter(val)
    },
    value:{
      immediate:true,
      handler(val){
        this.id = val
      }
    }
  },
}
</script>

<style scoped>

</style>
