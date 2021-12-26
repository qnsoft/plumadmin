<template>
  <el-dialog :title="dialogTitle" :visible.sync="visible" :close-on-click-modal="false"
             @closed="closed" @open="beforeOpened"
  >
    <el-scrollbar style="height: calc(70vh - 100px)" wrap-style="overflow-x:hidden;">
      <el-form ref="form" :model="form" size="small" :label-width="labelWidth" style="padding-right: 50px">
        <slot></slot>
      </el-form>
    </el-scrollbar>
    <template v-slot:footer>
      <el-button size="mini" @click="visible = false">关闭</el-button>
      <el-button type="primary" size="mini" @click="submit">保存</el-button>
    </template>
  </el-dialog>
</template>

<script>
import _ from 'lodash'

export default {
  name: 'DialogSubmit',
  props: {
    value: Object,
    title: String,
    labelWidth: {
      type: String,
      default: '150px'
    }
  },
  data() {
    return {
      initForm: {},
      visible: false,
      form: {},
      url: ''
    }
  },
  computed: {
    dialogTitle() {
      if (this.title) {
        return this.title
      } else {
        return this.form.id ? '编辑' : '新增'
      }
    }
  },
  created() {
    this.initForm = this.$tools.cloneDeep(this.value)
    this.form = this.value
  },
  methods: {
    beforeOpened() {
      this.$nextTick(() => {
        this.$refs.form.clearValidate()
      })
    },
    open(cover) {
      if (!_.isEmpty(cover) && _.isPlainObject(cover)) {
        this.form = Object.assign(this.form, cover)
      }
      this.visible = true
    },
    submit() {
      this.$refs.form.validate(valid => {
        if (valid) {
          this.$emit('submit', this.form, () => {
            this.visible = false
          })
        }
      })
    },
    closed() {
      //还原数据
      const cover = this.$tools.cloneDeep(this.initForm)
      for (let index in this.form) {
        if (cover[index]===undefined) {
          this.form[index] = undefined
        }else{
          this.form[index] = cover[index]
        }
      }
      //通知关闭弹窗
      this.$emit('close')
    }
  }
}
</script>

<style scoped>

</style>
