<template>
  <el-form ref="form" :model="form" size="small" style="width: 90%;margin: auto" label-width="150px">
    <el-form-item label="旧密码" prop="old_password" :rules="{required:true,message:'请输入旧密码'}">
      <el-input clearable show-password v-model="form.old_password" placeholder="请输入旧密码"></el-input>
    </el-form-item>

    <el-form-item label="新密码" prop="password" :rules="{required:true,message:'请输入新密码'}">
      <el-input clearable show-password v-model="form.password" placeholder="请输入新密码"></el-input>
    </el-form-item>

    <el-form-item
        label="确认密码"
        prop="confirm_password"
        :rules="[
            {required:true,message:'请输入确认密码'},
            {validator:validateConfirmPassword}
        ]">
      <el-input clearable show-password v-model="form.confirm_password" placeholder="请输入确认密码"/>
    </el-form-item>

    <el-form-item>
      <el-button v-permission="'userinfo@password'"  type="primary" @click="submit" :loading="submitLoading">保存</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
export default {
  name: "Password",
  data() {
    return {
      submitLoading: false,
      form: {
        password: '',
        old_password: '',
        confirm_password: ''
      },
      validateConfirmPassword: (rule, value, callback) => {
        if (value !== this.form.password) {
          callback(new Error('新密码与确认密码不一致'))
        }
        callback()
      }
    }
  },
  methods: {
    submit() {
      this.$refs.form.validate(valid => {
        if (valid) {
          this.submitLoading = true
          this.$http.post('user.info/editPassword', this.form)
              .then(({message}) => {
                this.$message.success(message)
                this.$refs.form.clearValidate()
              })
              .finally(() => {
                this.submitLoading = false
              })
        }
      })
    }
  }
}
</script>

<style scoped>

</style>
