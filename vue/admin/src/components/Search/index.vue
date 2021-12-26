<template>
  <el-form ref="form" inline class="search-container" :class="{'search-expand':expand}" size="small">
    <slot></slot>
    <el-form-item class="search-button">
      <el-button size="mini" type="primary" @click="search">搜索</el-button>
      <el-button size="mini" @click="reset">重置</el-button>
      <el-link v-if="formItemCount>5" style="margin-left: 10px" type="primary" :underline="false"
               @click="expand=!expand"
      >{{ expand ? '收起' : '展开' }} <i :class="expand?'el-icon-arrow-up':'el-icon-arrow-down'"></i>
      </el-link>
    </el-form-item>
  </el-form>
</template>

<script>
import * as _ from 'lodash'

export default {
  name: 'Search',
  props: {
    value: Object
  },
  data() {
    return {
      expand: false,
      initForm: {},
      form: {}
    }
  },
  computed: {
    formItemCount() {
      if (this.$refs.form) {
        return this.$refs.form.$el.querySelectorAll('.el-form-item').length
      } else {
        return 0
      }
    }
  },
  methods: {
    search: _.debounce(function() {
      this.$emit('search', _.cloneDeep(this.form))
    }, 500),
    reset() {
      this.form = _.cloneDeep(this.initForm)
      this.$emit('input', this.form)
      this.search()
    }
  },
  watch: {
    value: {
      immediate: true,
      deep: true,
      handler(val) {
        if (_.isEmpty(this.initForm)) {
          this.initForm = _.cloneDeep(val)
        }
        this.form = val
      }
    }
  }
}
</script>

<style scoped lang="scss">
.search-container {
  width: 100%;
  display: inline-grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 0;

  .el-form-item {
    grid-column: span 1 / span 1;
    display: flex;

    .el-form-item__label {
      flex-shrink: 0;
    }
  }

  .search-button {
    grid-column: 5 / span 1;
    justify-content: flex-end;
    user-select: none;
  }

  &:not(.search-expand) .el-form-item:not(:nth-of-type(-n+4),:last-of-type) {
    display: none;
  }
}
</style>
