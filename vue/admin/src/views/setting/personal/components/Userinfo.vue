<template>
  <el-form :model="user" size="small" style="width: 90%;margin: auto" label-width="150px">
    <el-form-item label="用户名">
      <span style="font-size: 14px;color: #909399">{{ user.username }}</span>
    </el-form-item>

    <el-form-item label="昵称" prop="nickname">
      <el-input v-model="user.nickname" placeholder="请输入昵称"></el-input>
    </el-form-item>

    <el-form-item label="头像" prop="avatar">
      <pl-upload-library v-model="user.avatar"/>
    </el-form-item>

    <el-form-item>
      <el-button v-permission="'userinfo@patch'"  type="primary" @click="submit" :loading="submitLoading">保存</el-button>
    </el-form-item>

  </el-form>
</template>

<script>
import {mapActions, mapMutations} from "vuex";

export default {
  name: "setting_personal_userinfo",
  data() {
    return {
      submitLoading: false,
      user: {
        username: '',
        nickname: '',
        avatar: ''
      }
    }
  },
  created() {
    this.getUser()
  },
  methods: {
    ...mapActions({getUserinfo: 'user/getInfo'}),
    ...mapMutations({setStateUser: 'user/SET_USER'}),
    getUser() {
      this.getUserinfo()
          .then(user => {
            this.user = user
          })
    },
    submit() {
      this.submitLoading = true
      this.$http.post('user.info/patch', this.user)
          .then(({data, message}) => {
            this.$message.success(message)
            this.setStateUser(data)
          })
          .finally(() => {
            this.submitLoading = false
          })
    }
  }
}
</script>

<style scoped>

</style>
