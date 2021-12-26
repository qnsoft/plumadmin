<template>
  <el-card class="app-layout-fixed" shadow="never">
    <!--表单-->
    <dialog-submit ref="form" v-model="form" label-width="150px" @submit="handleSubmit">
      <el-form-item label="规则类型" porp="type">
        <el-radio-group v-model="form.type">
          <el-radio :label="1">目录</el-radio>
          <el-radio :label="2">菜单</el-radio>
          <el-radio :label="3">权限</el-radio>
        </el-radio-group>
      </el-form-item>

      <el-form-item label="规则名称" prop="title"  :rules="{required:true,message:'请填写规则名称'}">
        <el-input placeholder="请填写规则名称" v-model="form.title"></el-input>
      </el-form-item>

      <el-form-item v-if="form.type===2" label="组件name值" prop="name"  :rules="{required:true,message:'请填写组件name值'}">
        <el-input placeholder="请填写组件name值" v-model="form.name"></el-input>
      </el-form-item>

      <el-form-item label="上级规则" prop="parent_id"  :rules="{required:true,message:'请选择上级规则'}">
        <menus-select v-model="form.parent_id"/>
      </el-form-item>

      <el-form-item label="外链" v-if="form.type===1">
        <el-input v-model="form.url" placeholder="请填写外链" />
      </el-form-item>

      <el-form-item v-if="form.type===3" label="权限集" prop="method" :rules="{required:true,message:'请选择权限集'}">
        <rule-tree-select v-model="form.method"/>
      </el-form-item>

      <el-form-item v-if="form.type===2" label="路由地址" prop="routes"  :rules="{required:true,message:'请填写路由地址'}">
        <el-input placeholder="请填写路由地址" v-model="form.routes"></el-input>
      </el-form-item>

      <el-form-item v-if="form.type===2" label="组件路径" prop="component" :rules="{required:true,message:'请填写组件路径'}">
        <components-select v-model="form.component"/>
      </el-form-item>

      <el-form-item v-if="form.type===1 ||form.type===2" label="图标" prop="icon" >
        <select-icon v-model="form.icon"/>
      </el-form-item>

      <el-form-item v-if="form.type===1 || form.type===2" label="隐藏菜单" prop="menu_hidden">
        <el-switch v-model="form.menu_hidden"/>
      </el-form-item>

      <el-form-item v-if="form.type===3" label="前端标识" prop="mark"  :rules="[{required:true,message:'请填写前端标识'}]">
        <el-input v-model="form.mark" placeholder="请输入前端标识"></el-input>
      </el-form-item>

      <el-form-item v-if="form.type===2" label="缓存路由" prop="keep_alive" >
        <el-switch v-model="form.keep_alive"/>
      </el-form-item>

      <el-form-item label="排序" prop="sort">
        <el-input-number style="width: 50%" v-model="form.sort" placeholder="请填写排序(升序)"></el-input-number>
      </el-form-item>

    </dialog-submit>


    <!--表格-->
    <pl-table ref="table" row-key="id" :columns="columns" :load="refresh" :page="false" default-expand-all>
      <template v-slot:toolbar>
        <el-button v-permission="'rule@list'" type="primary" size="mini" @click="$refs.form.open()">新增</el-button>
      </template>
      <template v-slot:type="{row}">
        <el-tag size="small" v-if="row.type===1">目录</el-tag>
        <el-tag size="small" v-if="row.type===2" type="success">菜单</el-tag>
      </template>
      <template v-slot:operation="{row}">
        <el-button v-permission="'rule@create'" size="mini" type="text" @click="$refs.form.open({type:row.type===1?2:3,parent_id:row.id})">新增</el-button>
        <el-divider direction="vertical"></el-divider>
        <el-button v-permission="'rule@update'" size="mini" type="text" @click="handleEdit(row.id)">编辑</el-button>
        <el-divider direction="vertical"></el-divider>
        <el-button v-permission="'rule@delete'" size="mini" type="text" @click="handleDelete([row.id])">删除</el-button>
      </template>
      <template v-slot:permission="{row}">
        <template v-if="row.permissions && row.permissions.length>0">
          <el-popover v-for="(item,index) in row.permissions" :key="index" placement="top">
            <div>权限:{{ item.mark }}</div>
            <div>
              <el-button v-permission="'rule@update'" size="mini" type="text" @click="handleEdit(item.id)">编辑</el-button>
              <el-button v-permission="'rule@delete'" size="mini" type="text" @click="handleDelete([item.id])">删除</el-button>
            </div>
            <template v-slot:reference>
              <el-tag size="small" style="margin:3px;user-select: none" type="warning">{{ item.title }}</el-tag>
            </template>
          </el-popover>
        </template>
      </template>
    </pl-table>
  </el-card>
</template>


<script>
import RuleTreeSelect from '@/views/rule/components/RuleTreeSelect'
import MenusSelect from '@/views/rule/components/MenusSelect'
import ComponentsSelect from '@/views/rule/components/ComponentsSelect'
import SelectIcon from '@/components/SelectIcon'

export default {
  name:'rule',
  components: { RuleTreeSelect, MenusSelect, ComponentsSelect, SelectIcon },
  data() {
    return {
      form: {
        method: [],
        title: '',
        name: '',
        parent_id: 0,
        type: 1,
        methods: [],
        routes: '',
        component: '',
        mark: '',
        icon: '',
        menu_hidden: false,
        keep_alive: true,
        sort: 200,
        url:''
      },
      columns: [
        { label: '规则名称', prop: 'title', 'width': '200px' },
        { label: '类型', prop: 'type', __slotName: 'type', width: '100px' },
        { label: '路由地址', prop: 'routes', 'width': '200px', 'show-overflow-tooltip': true },
        { label: '权限', prop: 'permission', 'min-width': '300px', __slotName: 'permission' },
        { label: '操作', prop: 'operation', __slotName: 'operation', fixed: 'right', width: '200px','header-align':'center',align:'center' }
      ]
    }
  },
  methods: {
    refresh(form, render) {
      this.$http.post('rule/tree', form)
          .then(({ data }) => {
            render(data)
          })
    },
    handleDelete(ids) {
      this.$confirm('确认删除该删除该规则及其子规则?', '提示')
          .then(() => {
            this.$http.post('rule/delete', { ids })
                .then(({ message }) => {
                  this.$message.success(message)
                  this.$refs.table.refresh()
                })
          })
    },
    handleSubmit(form, close) {
      const url = form.id ? 'rule/update' : 'rule/create'
      this.$http.post(url, form)
          .then(({ message }) => {
            this.$message.success(message)
            this.$refs.table.refresh()
            close()
          })
    },
    handleEdit(id) {
      this.$http.post('rule/detail', { id })
          .then(({ data }) => {
            this.$refs.form.open(data)
          })
    }
  }
}
</script>
