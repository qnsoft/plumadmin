<template>
  <el-card shadow="never">
    <el-form :model="form" label-width="150px" size="small" style="width: 90%;margin: 0 auto;">
      <el-form-item label="上传方式" prop="default">
        <el-radio-group v-model="form.default">
          <el-radio label="local">本地</el-radio>
          <el-radio label="aliyun">阿里云</el-radio>
          <el-radio label="qiniu">七牛云</el-radio>
          <el-radio label="qcloud">腾讯云</el-radio>
        </el-radio-group>
      </el-form-item>

      <!--阿里云-->
      <template v-if="form.default === 'aliyun'">
        <el-form-item label="accessId">
          <el-input v-model="form.disks.aliyun.accessId"/>
        </el-form-item>
        <el-form-item label="accessSecret">
          <el-input v-model="form.disks.aliyun.accessSecret"/>
        </el-form-item>
        <el-form-item label="bucket">
          <el-input v-model="form.disks.aliyun.bucket"/>
        </el-form-item>
        <el-form-item label="endpoint">
          <el-input v-model="form.disks.aliyun.endpoint"/>
        </el-form-item>
        <el-form-item label="url">
          <el-input v-model="form.disks.aliyun.url"/>
        </el-form-item>
      </template>

      <!--七牛云-->
      <template v-if="form.default === 'qiniu'">
        <el-form-item label="accessKey">
          <el-input v-model="form.disks.qiniu.accessKey"/>
        </el-form-item>
        <el-form-item label="secretKey">
          <el-input v-model="form.disks.qiniu.secretKey"/>
        </el-form-item>
        <el-form-item label="bucket">
          <el-input v-model="form.disks.qiniu.bucket"/>
        </el-form-item>
        <el-form-item label="url">
          <el-input v-model="form.disks.qiniu.url"/>
        </el-form-item>
      </template>

      <!--腾讯云-->
      <template v-if="form.default === 'qcloud'">
        <el-form-item label="region">
          <el-input v-model="form.disks.qcloud.region"/>
        </el-form-item>
        <el-form-item label="appId">
          <el-input v-model="form.disks.qcloud.appId"/>
        </el-form-item>
        <el-form-item label="secretId">
          <el-input v-model="form.disks.qcloud.secretId"/>
        </el-form-item>
        <el-form-item label="secretKey">
          <el-input v-model="form.disks.qcloud.secretKey"/>
        </el-form-item>
        <el-form-item label="bucket">
          <el-input v-model="form.disks.qcloud.bucket"/>
        </el-form-item>
        <el-form-item label="cdn">
          <el-input v-model="form.disks.qcloud.cdn"/>
        </el-form-item>
      </template>

      <el-form-item label="上传规则">
        <template #label>
          <i  @click="form.valid.push({name:'',ext:[],size:0})"style="color: #409eff;padding-left: 5px" class="el-icon-circle-plus" />
          <span>上传规则</span>
        </template>
        <div
            style="display: flex;margin-bottom:20px;"
            v-for="(item,index) in form.valid"
        >
          <el-input
              style="flex:0 0 130px;margin-right: 5px"
              v-model="item.name"
              placeholder="请输入规则名称"
          ></el-input>
          <el-select
              placeholder="请选择扩展名"
              style="flex:1 0 auto;margin-right: 5px"
              v-model="item.ext"
              multiple
              filterable
              allow-create
              default-first-option
          >
            <el-option-group
                v-for="group in extOptions"
                :key="group.label"
                :label="group.label"
            >
              <el-option
                  v-for="item in group.options"
                  :key="item"
                  :label="item"
                  :value="item"
              >
              </el-option>
            </el-option-group>
          </el-select>
          <el-input-number
              style="flex:0 0 200px;margin-right: 5px"
              v-model="item.size"
          />
          <span style="color: #909399">限制上传大小单位(B)</span>
          <div style="flex:1 0 auto;text-align: right">
            <el-button size="mini" type="danger" @click="form.valid.splice(index,1)">删除</el-button>
          </div>
        </div>
      </el-form-item>

      <el-form-item>
        <el-button  v-permission="'systemconfig@set'" type="primary" @click="onSubmit">保存</el-button>
      </el-form-item>

    </el-form>
  </el-card>
</template>

<script>
export default {
  name: 'upload_config',
  created() {
    this.getData()
  },
  data() {
    return {
      loading: false,
      form: {
        default: 'local',
        disks: {
          aliyun: {
            type: 'aliyun',
            accessId: undefined,
            accessSecret: undefined,
            bucket: undefined,
            endpoint: undefined,
            url: undefined
          },
          qiniu: {
            type: 'qiniu',
            accessKey: undefined,
            secretKey: undefined,
            bucket: undefined,
            url: undefined//不要斜杠结尾，此处为URL地址域名。
          },
          qcloud: {
            type: 'qcloud',
            region: undefined, //bucket 所属区域 英文
            appId: undefined, // 域名中数字部分
            secretId: undefined,
            secretKey: undefined,
            bucket: undefined,
            cdn: undefined
          }
        },
        valid: []
      },
      extOptions: [
        {
          label: '文档类型',
          options: [
            'txt', 'doc', 'hlp', 'rtf', 'html', 'pdf'
          ]
        },
        {
          label: '图形类型',
          options: [
            'bmp', 'gif', 'jpg', 'pic', 'png', 'tif'
          ]
        },
        {
          label: '压缩类型',
          options: [
            'rar', 'zip', 'arj', 'gz', 'z'
          ]
        },
        {
          label: '声音类型',
          options: [
            'wav', 'aif', 'au', 'mp3', 'ram', 'wma', 'mmf', 'amr', 'aac', 'flac'
          ]
        },
        {
          label: '动画类型',
          options: [
            'avi', 'mpg', 'mov', 'swf'
          ]
        }
      ]
    }
  },
  methods: {
    getData() {
      this.loading = true
      this.$http.post('systemConfig/get', { key: 'filesystem' })
          .then(({ data }) => {
            this.form = data
          })
          .finally(() => {
            this.loading = false
          })
    },
    onSubmit() {
      this.$http.post('systemConfig/set', { key: 'filesystem', value: this.form })
          .then(({ message }) => {
            this.$message.success(message)
          })
    }
  }
}
</script>

<style scoped>

</style>
