<template>
  <div class="table-container">
    <!--搜索-->
    <div class="search-container" v-if="isSearchComponent">
      <search v-model="searchData" @search="refresh">
        <slot name="search"></slot>
      </search>
    </div>
    <!--工具-->
    <div class="toolbar-container">
      <div class="toolbar-left">
        <slot name="toolbar"></slot>
      </div>
      <div class="toolbar-right">
        <el-tooltip content="刷新" placement="top">
          <i class="el-icon-refresh table-toolbar-right-icon" @click="refresh"></i>
        </el-tooltip>
        <!--<el-tooltip content="密度" placement="top">-->
        <!--  <svg-icon icon-class="column-height" class-name="table-toolbar-right-icon"></svg-icon>-->
        <!--</el-tooltip>-->
        <!--<el-tooltip content="列设置" placement="top">-->
        <!--  <i class="el-icon-setting table-toolbar-right-icon"></i>-->
        <!--</el-tooltip>-->
      </div>
    </div>
    <!--表格-->
    <el-table
        style="flex-grow: 1"
        :data="data"
        v-loading="loading"
        v-bind="$attrs"
        v-on="$listeners"
        header-cell-class-name="table-header-gray"
        @sort-change="sortChange"
        height="100%"
    >
      <el-table-column v-for="(column,index) in columns" v-bind="column" :key="index">
        <template v-if="column.__slotName" v-slot="scope">
          <slot :name="column.__slotName" :row="scope.row" :$index="scope.$index" :column="scope.column"/>
        </template>
      </el-table-column>
    </el-table>

    <!--分页-->
    <el-pagination
        v-if="page"
        class="pagination-container"
        background
        layout="total,prev, pager, next,sizes,jumper"
        :current-page.sync="pagination.page"
        :total="pagination.total"
        :page-sizes="[15, 20, 50, 100]"
        :page-size.sync="pagination.size"
        @current-change="refresh(false)"
        @size-change="refresh()"
    />
  </div>
</template>

<script>
export default {
  name: 'PTable',
  props: {
    search: {
      type: Object,
      default() {
        return {}
      }
    },
    columns: {
      type: Array,
      default() {
        return []
      }
    },
    load: {
      type: Function
    },
    page: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      sort: undefined,
      order: undefined,
      searchData: {},
      data: [],
      loading: false,
      pagination: {
        page: 1,
        size: 15,
        total: 100
      }
    }
  },
  computed: {
    isSearchComponent() {
      return !this.$tools.isEmpty(this.search)
    },
    formData() {
      let data = {...this.search, sort: this.sort, order: this.order}
      if (this.page) {
        data.pagination = this.pagination.page
        data.size = this.pagination.size
      }
      return data
    }
  },
  created() {
    //加载
    this.refresh()
  },
  methods: {
    // 排序
    sortChange({prop, order}) {
      if (prop && order) {
        this.sort = prop
        this.order = order === 'descending' ? 'desc' : 'asc'
      } else {
        this.sort = undefined
        this.order = undefined
      }
    },
    //渲染
    render(data) {
      this.loading = false
      try {
        if (this.page) {
          this.data = data.list
          this.pagination = data.pagination
        } else {
          this.data = data
        }
      } catch {
        throw Error('数据格式错误')
      }

    },
    refresh(init = true) {
      if (init) {
        this.pagination.page = 1
      }
      if (this.$tools.isFunction(this.load)) {
        this.loading = true
        this.load(this.formData, this.render)
      }
    }
  },
  watch: {
    search: {
      immediate: true,
      deep: true,
      handler(val) {
        this.searchData = val
      }
    },
    searchData: {
      immediate: true,
      deep: true,
      handler(val) {
        //为了兼容search扩展,这里在原基础上做修改
        for (let key in val) {
          this.search[key] = val[key]
        }
      }
    }
  }
}
</script>


<style scoped lang="scss">
.table-container {
  display: flex;
  height: 100%;
  flex-direction: column;
}

.search-container {

}

.toolbar-container {
  display: flex;
  justify-content: space-between;
  padding-bottom: 16px;

  .table-toolbar-right-icon {
    margin-left: 12px;
    font-size: 18px;
    cursor: pointer;
    color: rgb(51, 54, 57);

    &:hover {
      color: #69c0ff;
    }
  }
}


.pagination-container {
  text-align: right;
  margin-top: 12px;
}

//滚动条,头部样式
.el-table {
  ::v-deep .el-table__fixed-right-patch {
    background: #fafafa;
  }

  ::v-deep th {
    background: #fafafa;
    color: rgba(0, 0, 0, .85);
    font-weight: normal;
    transition: background .3s ease;
  }
}
</style>
