<template>
  <el-card class="app-layout-fixed" shadow="never">

    <dialog-submit ref="form" v-model="form" label-width="150px" @submit="handleSubmit">
      <el-form-item label="账号" prop="username" :rules="{required:true,message:'请输入账号'}">
        <el-input v-model="form.username" placeholder="请输入账号"/>
      </el-form-item>

      <el-form-item label="密码" prop="password" :rules="{required:!this.form.id,message:'请输入密码'}">
        <el-input show-password v-model="form.password" placeholder="请输入密码"/>
      </el-form-item>

      <el-form-item label="昵称" prop="nickname" :rules="{required:true,message:'请输入昵称'}">
        <el-input v-model="form.nickname" placeholder="请输入昵称"></el-input>
      </el-form-item>

      <el-form-item label="头像" prop="avatar" :rules="{required:true,message:'请上传头像'}">
        <pl-upload-library v-model="form.avatar"/>
      </el-form-item>

      <el-form-item label="超级管理员">
        <el-switch v-model="form.is_super"/>
      </el-form-item>

      <el-form-item label="角色" prop="role_ids" :rules="{required:true,message:'请选择角色'}">
        <role-select v-model="form.role_ids"></role-select>
      </el-form-item>
    </dialog-submit>

    <pl-table ref="table" :columns="columns" :load="refresh" :search="search">
      <template #search>
        <el-form-item label="账号/昵称">
          <el-input v-model="search.keyword" placeholder="请搜索账号/昵称"/>
        </el-form-item>
      </template>

      <template #toolbar>
        <el-button v-permission="'user@create'" type="primary" size="mini" @click="$refs.form.open()">新增</el-button>
      </template>

      <template #user="{row}">
        <div style="display: flex;align-items: center">
          <el-avatar size="small" :src="row.avatar" @error="()=>{true}">
            <i class="el-icon-user-solid"></i>
          </el-avatar>
          <span style="padding-left: 5px">{{ row.nickname }}</span>
        </div>
      </template>

      <template #operation="{row}">
        <el-button  v-permission="'user@update'" type="text" size="mini" @click="handleUpdate(row.id)">编辑</el-button>
        <el-divider direction="vertical"></el-divider>
        <el-button  v-permission="'user@delete'" type="text" size="mini" @click="handleDelete([row.id])">删除</el-button>
      </template>

    </pl-table>

  </el-card>
</template>

<script>
import RoleSelect from '@/views/user/components/RoleSelect'

export default {
  name: 'user',
  components: {RoleSelect},
  data() {
    return {
      form: {
        username: '',
        password: '',
        nickname: '',
        avatar: '',
        role_ids: [],
        is_super: false
      },
      search: {
        keyword: ''
      },
      columns: [
        {label: '用户', prop: 'nickname', width: '200px', __slotName: 'user'},
        {label: '账号', prop: 'username'},
        {label: '创建时间', prop: 'create_time', width: '200px'},
        {label: '操作', prop: 'operation', width: '200px', fixed: 'right', __slotName: 'operation','header-align':'center',align:'center' }
      ]
    }
  },
  methods: {
    refresh(form, render) {
      this.$http.post('user/page', form)
          .then(({data}) => {
            render(data)
          })
    },
    handleUpdate(id) {
      this.$http.post('user/detail', {id})
          .then(({data}) => {
            this.$refs.form.open(data)
          })
          .catch(() => {
          })
    },
    handleSubmit(form, close) {
      const url = form.id ? 'user/update' : 'user/create'
      this.$http.post(url, form)
          .then(({message}) => {
            this.$message.success(message)
            this.$refs.table.refresh()
            close()
          })
    },
    handleDelete(ids) {
      this.$confirm('此操作将永久删除选中数据,是否继续?', '提示', {type: 'warning'})
          .then(() => {
            this.$http.post('user/delete', {ids})
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

<style scoped>

</style>
