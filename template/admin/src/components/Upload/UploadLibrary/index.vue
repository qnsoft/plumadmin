<template>
  <div>
    <el-image-viewer v-if="imageViewerVisible" :initial-index="imageViewerIndex" :url-list="previewList"
                     :on-close="()=>{this.imageViewerVisible=false}"
    ></el-image-viewer>
    <!--展示 多图-->
    <vue-draggable v-if="multiple" class="preview-list-container" filter=".no-draggable" v-model="previewList">
      <div class="preview-list-item" v-for="(url,index) in previewList">
        <div class="preview-overlay">
          <i class="el-icon-delete" @click="previewList.splice(index,1)"></i>
          <i class="el-icon-zoom-in" @click="imageViewerIndex=index;imageViewerVisible=true"></i>
        </div>
        <img style="width: 100%;height: 100%;object-fit: cover" :src="url" alt="">
      </div>
      <div class="preview-list-item no-draggable upload" @click="libraryVisible=true"
           v-if="limit===0 || limit>previewList.length"
      >
        <i class="el-icon-upload"></i>
        <span v-if="limit>0 && multiple">({{ previewList.length }}/{{ limit }})</span>
      </div>
    </vue-draggable>
    <!--单图-->
    <div v-else class="preview-list-container">
      <div class="preview-list-item" v-for="(url,index) in previewList">
        <div class="preview-overlay" @click="libraryVisible=true">
          <i class="el-icon-delete" @click.stop="previewList.splice(index,1)"></i>
          <i class="el-icon-zoom-in" @click.stop="imageViewerIndex=index;imageViewerVisible=true"></i>
        </div>
        <img style="width: 100%;height: 100%;object-fit: cover" :src="url" alt="">
      </div>
      <div class="preview-list-item upload" @click="libraryVisible=true"
           v-if="previewList.length===0"
      >
        <i class="el-icon-upload"></i>
        <span v-if="limit>0 && multiple">({{ previewList.length }}/{{ limit }})</span>
      </div>
    </div>

    <!--素材库-->
    <el-dialog :visible.sync="libraryVisible" title="文件库" @closed="closeDialog" append-to-body>
      <div class="library-container">
        <!--工具栏-->

        <div class="library-toolbar">
          <el-input clearable prefix-icon="el-icon-search" v-model="keyword" size="small" class="search-input"
                    placeholder="请输入文件名称" @input="search"
          />

          <div style="display: flex;align-items: center">
            <el-button style="margin-right: 5px" type="danger" size="mini" icon="el-icon-delete"
                       @click="handleLibraryDelete"
            >删除
            </el-button>
            <upload-button @upload="uploadCallback"/>
          </div>

        </div>
        <!--列表-->
        <el-scrollbar wrap-style="overflow-x:hidden;" style="flex-grow:1;" v-loading="libraryListLoading">
          <div class="library-file-list" ref="fileList">
            <el-tooltip
                placement="top"
                :content="`${item.name} ${item.size_text}`"
                :class="{'checked':item.checked,'library-file-item':true}"
                :style="{height:fileItemHeight}"
                v-for="(item,index) in libraryList"
                :key="item.id"
            >
              <div @click="selectLibraryFile(index)">
                <div class="overlay-checked">
                  <i class="el-icon-check"></i>
                </div>
                <img
                    class="file-item-preview"
                    :src="item.url"
                    :alt="item.name"
                />
              </div>
            </el-tooltip>
          </div>
        </el-scrollbar>

        <!--分页-->
        <el-pagination
            class="library-pagination"
            background
            layout="total,prev, pager, next,sizes,jumper"
            :current-page.sync="pagination.page"
            :total="pagination.total"
            :page-sizes="[15, 20, 50, 100]"
            :page-size.sync="pagination.size"
            @current-change="getLibraryList(false)"
            @size-change="getLibraryList()"
        />
      </div>
      <template #footer>
        <el-button size="small" @click="libraryVisible = false">关闭</el-button>
        <el-button type="primary" size="small" @click="confirm">确认</el-button>
      </template>
    </el-dialog>
  </div>
</template>

<script>
import UploadButton from '@/components/Upload/UploadButton'
import ElImageViewer from 'element-ui/packages/image/src/image-viewer'
import * as _ from 'lodash'
import VueDraggable from 'vuedraggable'

export default {
  name: 'index',
  components: { UploadButton, VueDraggable, ElImageViewer },
  props: {
    value: {
      type: Array | String,
      default: ''
    },
    multiple: {
      type: Boolean
    },
    limit: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      imageViewerVisible: false,
      imageViewerIndex: 0,
      keyword: '',
      libraryVisible: false,
      libraryListLoading: false,
      libraryList: [],
      fileItemHeight: '150px',
      previewList: [],
      pagination: {
        page: 1,
        total: 0,
        size: 15
      }
    }
  },
  computed: {
    checkedLibraryList() {
      return this.libraryList.filter(e => e.checked)
    }
  },
  created() {
    this.getLibraryList()
  },
  methods: {
    // 上传回调
    uploadCallback() {
      this.getLibraryList()
    },
    //修改文件库列表高度
    setLibraryFileListHeight() {
      this.$nextTick(() => {
        const fileListEl = this.$refs?.fileList
        if (fileListEl && fileListEl.querySelector('.library-file-item')) {
          this.fileItemHeight = fileListEl.querySelector('.library-file-item').offsetWidth + 'px'
        }
      })
    },
    //获取当前用户的文件
    getLibraryList(refresh = true) {
      if (refresh) {
        this.pagination.page = 1
      }
      this.libraryListLoading = true
      this.$http.post('file/userFilePage', {
        name: this.keyword.trim(),
        page: this.pagination.page,
        size: this.pagination.size
      })
          .then(({ data }) => {
            this.libraryList = data.list
            this.pagination = data.pagination
          })
          .finally(() => {
            this.libraryListLoading = false
          })

    },
    selectLibraryFile(index) {
      if (!this.multiple && this.checkedLibraryList.length > 0) {
        let checkedIndex = this.libraryList.findIndex(e => e.checked)
        if (index !== checkedIndex) {
          this.$set(this.libraryList[checkedIndex], 'checked', false)
        }
      }
      this.$set(this.libraryList[index], 'checked', !this.libraryList[index].checked)
    },
    search: _.debounce(function() {
      if (this.keyword.trim() !== '') {
        this.getLibraryList()
      }
    }, 500),
    handleLibraryDelete() {
      let ids = this.checkedLibraryList.map(e => e.id)
      this.$confirm('此操作将永久删除选中数据,是否继续?', '提示', { type: 'warning' })
          .then(() => {
            this.$http.post('file/delete', { ids: ids })
                .then(({ message }) => {
                  this.$message.success(message)
                  this.getLibraryList()
                })
          })
    },
    confirm() {
      if (this.checkedLibraryList.length > 0) {
        let previewList = []
        if (this.multiple) {
          previewList = this.previewList.concat(this.checkedLibraryList)
          if (this.limit > 0) {
            previewList = previewList.slice(0, this.limit)
          }
        } else {
          previewList = [this.checkedLibraryList[0]]
        }
        previewList = previewList.map(e => {
          return this.$tools.isPlainObject(e) ? e.url : e
        })
        this.previewList = previewList
      }
      //关闭弹窗
      this.libraryVisible = false
    },
    closeDialog() {
      this.cancelChecked()
    },
    cancelChecked() {
      this.libraryList.forEach((item, index) => {
        if (item.checked) {
          this.$set(this.libraryList[index], 'checked', false)
        }
      })
    }
  },
  watch: {
    libraryList: {
      immediate: true,
      handler(val, old) {
        //监听列表变换,来更新列表的高度
        this.setLibraryFileListHeight()
      }
    },
    libraryVisible() {
      this.setLibraryFileListHeight()
    },
    value: {
      immediate: true,
      deep: true,
      handler(val) {
        if (this.multiple) {
          this.previewList = val
        } else {
          this.previewList = val ? [val] : []
        }
      }
    },
    previewList: {
      immediate: true,
      deep: true,
      handler(val) {
        if (this.multiple) {
          this.$emit('input', val)
        } else {
          this.$emit('input', this.previewList.length === 0 ? '' : this.previewList[0])
        }
      }
    }
  }

}
</script>

<style scoped lang="scss">
.library-container {
  height: calc(70vh - 100px);
  display: flex;
  flex-direction: column;

  .library-toolbar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;

    .search-input {
      width: 380px;
    }
  }

  .library-file-list {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-gap: 20px 20px;
    height: 100%;

    .library-file-item {
      overflow: hidden;
      border-radius: 5px;
      border: 1px dashed rgba(#d9d9d9, 0.5);
      position: relative;

      .file-item-preview {
        object-fit: cover;
        width: 100%;
        height: 100%;
      }

      &.checked .overlay-checked {
        display: flex;
      }

      .overlay-checked {
        display: none;
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(#000, .4);
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        color: #ffffff;
      }
    }
  }


  //分页
  .library-pagination {
    margin-top: 10px;
    text-align: right;
  }
}


.preview-list-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, 120px);
  grid-gap: 20px 20px;

  .preview-list-item {
    width: 100%;
    height: 120px;
    background: #fbfdff;
    border-radius: 6px;
    position: relative;
    overflow: hidden;
    font-size: 14px;
    color: #8c939d;
    box-shadow: 0 0 4px rgba(#bfbfbf, .15);

    &.upload {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      border: 1px dashed #c0ccda;
      box-sizing: border-box;

      &:hover {
        border-color: #20a0ff;
      }

      i {
        font-size: 28px;
      }
    }

    &:hover .preview-overlay {
      display: flex;
    }

    .preview-overlay {
      position: absolute;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(#000, .4);
      display: none;
      justify-content: center;
      align-items: center;

      i {
        margin: 5px;
        color: #ffffff;
        font-size: 20px;
      }
    }
  }
}

</style>
