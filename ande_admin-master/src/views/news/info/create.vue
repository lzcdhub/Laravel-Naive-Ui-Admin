<template>
  <div>
    <basicModal
      ref="modalRef"
      style="width: 1200px"
      class="basicModal"
      @register="modalRegister"
      @on-ok="okModal"
      @on-close="closeAfterModal"
      :on-after-enter="showAfterModal"
    >
      <template #default>
        <n-scrollbar style="max-height: 70vh">
          <n-form
            ref="formRef"
            class="pad-top-10 add_form"
            :model="formData"
            :rules="rules"
            label-placement="left"
            label-width="auto"
            require-mark-placement="right-hanging"
          >
            <n-form-item label="新闻分类" path="category_id">
              <n-tree-select
                filterable
                v-model:value="formData.category_id"
                :options="selectParentData"
                :loding="loadingRef"
                clearable
                key-field="id"
                label-field="name"
              />
            </n-form-item>
            <n-form-item label="标题" path="title">
              <n-input v-model:value="formData.title" />
            </n-form-item>
            <n-form-item label="副标题" path="ftitle">
              <n-input v-model:value="formData.ftitle" />
            </n-form-item>
            <n-form-item label="内容简介" path="smalltext">
              <n-input
                v-model:value="formData.smalltext"
                type="textarea"
                :autosize="{
                  minRows: 3,
                  maxRows: 5,
                }"
              />
            </n-form-item>
            <n-form-item label="作者" path="writer">
              <n-input v-model:value="formData.writer" />
            </n-form-item>
            <n-form-item label="信息来源" path="befrom">
              <n-input v-model:value="formData.befrom" />
            </n-form-item>
            <n-form-item label="状态" path="status_f">
              <n-radio-group v-model:value="formData.status_f" default-value="1">
                <n-radio-button value="1"> 上架</n-radio-button>
                <n-radio-button value="2"> 下架</n-radio-button>
              </n-radio-group>
            </n-form-item>
            <n-form-item label="新闻详情" path="newstext">
              <div class="p-0.5" style="border: 1px solid #ccc">
                <Toolbar
                  :editor="editorRef"
                  :defaultConfig="toolbarConfig"
                  mode="default"
                  style="border-bottom: 1px solid #ccc"
                />
                <Editor
                  :defaultConfig="editorConfig"
                  mode="default"
                  v-model="valueHtml"
                  style="height: 400px; overflow-y: hidden"
                  @onCreated="handleCreated"
                  @customAlert="customAlert"
                />
              </div>
            </n-form-item>
          </n-form>
        </n-scrollbar>
      </template>
    </basicModal>
  </div>
</template>

<script setup>
  import { titleConfig } from '@/views/wx/shop/productColumns.ts';
  import { ref } from 'vue-demi';
  import {
    defineExpose,
    onMounted,
    reactive,
    nextTick,
    unref,
    shallowRef,
    onBeforeUnmount,
  } from 'vue';
  import { useModal } from '@/components/Modal';
  import { useMessage } from 'naive-ui';
  import { newsEdit, newsAdd, newsDetail } from '@/api/news/info';
  import { newsCategorySelect } from '@/api/news/category';
  import '@wangeditor/editor/dist/css/style.css';
  import { Editor, Toolbar } from '@wangeditor/editor-for-vue';
  import { useGlobSetting } from '@/hooks/setting';
  import { storage } from '@/utils/Storage';
  import { ACCESS_TOKEN } from '@/store/mutation-types';

  const globSetting = useGlobSetting();
  const { uploadUrl } = globSetting;
  const message = useMessage();
  const token = storage.get(ACCESS_TOKEN);

  const modalName = ref('');
  const [modalRegister, { openModal, closeModal, setSubLoading }] = useModal({ title: modalName });
  const emit = defineEmits(['reloadTable', 'register']);

  const formRef = ref();
  const formData = reactive({});
  const rules = ref([]);
  const selectParentData = ref([]);
  const loadingRef = ref(false);

  //富文本编辑器 开始 富文本编辑器 开始 富文本编辑器 开始
  const editorRef = shallowRef();
  const valueHtml = ref('<p>hello</p>');
  const toolbarConfig = {};
  const editorConfig = {
    placeholder: '请输入内容...',
    MENU_CONF: {
      uploadImage: {
        fieldName: 'files',
        meta: {
          disk: 'newseditor',
        },
        headers: {
          Authorization: token,
        },
        server: uploadUrl + '/api/admin/upload',
        customInsert(res, insertFn) {
          if (res.code == 200) {
            insertFn(res.result.photo);
          }
        },
      },
    },
  };
  onBeforeUnmount(() => {
    const editor = editorRef.value;
    if (editor == null) return;
    editor.destroy();
  });
  const handleCreated = (editor) => {
    editorRef.value = editor;
  };
  const customAlert = (info, type) => {
    alert(`【自定义提示】${type} - ${info}`);
  };
  //富文本编辑器 结束 富文本编辑器 结束 富文本编辑器 结束

  async function okModal() {
    formRef.value?.validate((errors) => {
      if (!errors) {
        const editor = editorRef.value;
        if (editor) {
          formData.newstext = editor.getHtml();
        }
        if (formData.id) {
          newsEdit(formData, formData.id).then(function () {
            closeModal();
            setSubLoading(false);
            emit('reloadTable');
          });
        } else {
          newsAdd(formData).then(function () {
            closeModal();
            setSubLoading(false);
            emit('reloadTable');
          });
        }
      }
    });
    setTimeout(function () {
      setSubLoading(false);
    }, 1000);
  }

  function closeAfterModal() {
    Object.keys(formData).map((key) => {
      delete formData[key];
    });
  }

  function showAfterModal() {
    selectCategoryData();
  }

  function getDetail(id) {
    newsDetail(id).then(function (res) {
      Object.assign(formData, res);
      if (formData.newstext) valueHtml.value = formData.newstext;
    });
  }

  function selectCategoryData() {
    loadingRef.value = true;
    newsCategorySelect({ pid: 1 }).then(function (res) {
      function aaa(data) {
        data.forEach((v, i) => {
          if (v.children) {
            if (v.children.length < 1) {
              v.children = undefined;
            } else {
              aaa(v.children);
            }
          }
        });
        return data;
      }

      selectParentData.value = aaa(res.list);
      loadingRef.value = false;
    });
  }

  defineExpose({
    openModal,
    modalName,
    getDetail,
  });
</script>

<style scoped>
  .add_form {
    margin-right: 20px;
    margin-left: 20px;
  }
</style>
