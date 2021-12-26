<template>
  <el-card class="app-layout-fixed" shadow="never">
    <!--表单-->
    <dialogSubmit v-model="form" ref="form" @submit="handleSubmit" label-width="100px">
      <el-form-item prop="name" label="角色名称" :rules="{required:true,message:'请输入角色名称'}">
        <el-input placeholder="请输入角色名称" v-model="form.name"/>
      </el-form-item>

      <el-form-item prop="rule_ids" label="规则" :rules="{required:true,message:'请选择规则'}">
        <select-rule v-model="form.rule_ids"></select-rule>
      </el-form-item>

      <el-form-item prop="remark" label="备注">
        <el-input type="textarea" placeholder="请输入备注" v-model="form.remark"/>
      </el-form-item>

      <el-form-item prop="sort" label="排序">
        <el-input-number style="width: 50%" v-model="form.sort"/>
      </el-form-item>
    </dialogSubmit>


    <pl-table ref="table" :columns="columns" :load="refresh" :search="search">

      <template v-slot:search>
        <el-form-item label="角色名称">
          <el-input placeholder="请输入角色名称" v-model="search.name"></el-input>
        </el-form-item>
      </template>

      <template v-slot:toolbar>
        <el-button v-permission="'role@create'" size="mini" type="primary" @click="$refs.form.open()">新增</el-button>
      </template>

      <template #operation="{row}">
        <el-button v-permission="'role@update'" size="mini" type="text" @click="handleUpdate(row.id)">编辑</el-button>
        <el-divider direction="vertical"></el-divider>
        <el-button  v-permission="'role@delete'"size="mini" type="text" @click="handleDelete([row.id])">删除</el-button>
      </template>

    </pl-table>
  </el-card>
</template>

<script>
import SelectRule from './components/SelectRule'

export default {
  name: 'role',
  components: {SelectRule},
  data() {
    return {
      form: {
        name: '',
        remark: '',
        rule_ids: [],
        sort: 200
      },
      search: {
        name: ''
      },
      columns: [
        {label: '角色名称', prop: 'name', width: '200px'},
        {label: '备注', 'show-overflow-tooltip': true, prop: 'remark'},
        {label: '创建时间', prop: 'create_time', width: '200px'},
        {
          label: '操作',
          prop: 'operation',
          width: '200px',
          __slotName: 'operation',
          fixed: 'right',
          'header-align': 'center',
          align: 'center'
        }
      ]
    }
  },
  methods: {
    handleSubmit(form, close) {
      const url = form.id ? 'role/update' : 'role/create'
      this.$http.post(url, form)
          .then(({message}) => {
            this.$message.success(message)
            close()
            this.$refs.table.refresh()
          })
    },
    refresh(form, render) {
      this.$http.post('role/page', form)
          .then(({data}) => {
            render(data)
          })
    },
    handleUpdate(id) {
      this.$http.post('role/detail', {id})
          .then(({data}) => {
            this.$refs.form.open(data)
          })
    },
    handleDelete(ids) {
      this.$confirm('此操作将永久删除选中数据,是否继续?', '提示', {type: 'warning'})
          .then(() => {
            this.$http.post('role/delete', {ids})
                .then(({message}) => {
                  this.$message.success(message)
                  this.$refs.table.refresh()
                })
          })
          .catch(() => {
          })
    }
  }
}
</script>


