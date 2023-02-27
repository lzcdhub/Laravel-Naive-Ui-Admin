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
        <n-scrollbar style="height: 60vh">
          <n-form
            ref="formRef"
            class="pad-top-10 add_form"
            :model="formData"
            :rules="rules"
            label-placement="left"
            label-width="auto"
            require-mark-placement="right-hanging"
          >
            <n-form-item label="父级分类" path="category_id">
              <n-tree-select
                filterable
                v-model:value="formData.category_id"
                :options="selectParentData"
                clearable
                key-field="id"
                label-field="name"
              />
            </n-form-item>
            <n-form-item label="名称" path="title">
              <n-input v-model:value="formData.title" />
            </n-form-item>
            <n-form-item label="副标题" path="subtitle">
              <n-input v-model:value="formData.subtitle" />
            </n-form-item>
            <n-form-item label="礼品编号" path="productno">
              <n-input v-model:value="formData.productno" />
            </n-form-item>
            <n-form-item label="品牌" path="pbrand">
              <n-input v-model:value="formData.pbrand" />
            </n-form-item>
            <n-form-item label="简单描述" path="intro">
              <n-input
                v-model:value="formData.intro"
                type="textarea"
                :autosize="{
                  minRows: 3,
                  maxRows: 5,
                }"
              />
            </n-form-item>
            <n-form-item label="计量单位" path="unit">
              <n-input v-model:value="formData.unit" />
            </n-form-item>
            <n-form-item label="价格" path="price">
              <n-input v-model:value="formData.price" />
            </n-form-item>
            <n-form-item label="积分" path="buyfen">
              <n-input v-model:value="formData.buyfen" />
            </n-form-item>
            <n-form-item label="库存" path="pmaxnum">
              <n-input v-model:value="formData.pmaxnum" />
            </n-form-item>
            <n-form-item label="状态" path="status_f">
              <n-radio-group v-model:value="formData.status_f" default-value="1">
                <n-radio-button value="1"> 上架</n-radio-button>
                <n-radio-button value="2"> 下架</n-radio-button>
              </n-radio-group>
            </n-form-item>
            <n-form-item label="产品规格">
              <n-switch
                style="margin: 0 10px 0 0"
                v-model:value="formData.specifications"
                checked-value="1"
                default-value="0"
              >
                <template #checked> 开启</template>
                <template #unchecked> 关闭</template>
              </n-switch>
              <n-button type="info" @click="specSet">多规格设置</n-button>
            </n-form-item>
            <n-form-item label="销售量" path="psalenum">
              <n-input v-model:value="formData.psalenum" disabled />
            </n-form-item>
            <n-form-item label="封面图片" path="pic">
              <BasicUpload
                :action="`${uploadUrl}/api/admin/upload`"
                :headers="uploadHeaders"
                :data="{ disk: 'product' }"
                name="files"
                :width="100"
                :height="100"
                :multiple="true"
                @uploadChange="uploadChange"
                @delete="deleteChange"
                v-model:value="formData.images"
              />
            </n-form-item>
            <n-form-item label="礼品详情" path="newstext">
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
        <productSpec ref="productSpecModel" :specData="specData" @saveSpec="saveSpec"></productSpec>
      </template>
    </basicModal>
  </div>
</template>

<script setup>
  import productSpec from '@/views/wx/shop/productSpec.vue';
  import { ref } from 'vue-demi';
  import {
    defineExpose,
    onMounted,
    reactive,
    nextTick,
    unref,
    h,
    shallowRef,
    onBeforeUnmount,
  } from 'vue';
  import { basicModal, useModal } from '@/components/Modal';
  import { NInput, useMessage } from 'naive-ui';
  import {
    addCategoryTree,
    productAddApi,
    productDetailApi,
    productEditApi,
  } from '@/api/wx/product';
  import { BasicUpload } from '@/components/Upload';
  import { useGlobSetting } from '@/hooks/setting';
  import { titleConfig } from '@/views/wx/shop/productColumns.ts';
  import '@wangeditor/editor/dist/css/style.css';
  import { Editor, Toolbar } from '@wangeditor/editor-for-vue';
  import { storage } from '@/utils/Storage';
  import { ACCESS_TOKEN } from '@/store/mutation-types';

  const globSetting = useGlobSetting();
  const { uploadUrl } = globSetting;

  const message = useMessage();
  const emit = defineEmits(['reloadProductTable', 'register']);

  const formRef = ref();
  const formData = reactive({});
  const rules = ref([]);
  const selectParentData = ref([]);
  const token = storage.get(ACCESS_TOKEN);
  const uploadHeaders = ref({ Authorization: token });

  const modalName = ref('');
  const [modalRegister, { openModal, closeModal, setSubLoading }] = useModal({ title: modalName });

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
          disk: 'producteditor',
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

  function uploadChange(list, data = null) {
    formData.images = unref(list);
    if (data) {
      if (!formData.imageIds) {
        formData.imageIds = [];
      }
      formData.imageIds.push(data.data.id);
    }
  }

  function deleteChange(list, index) {
    formData.imageIds.splice(index, 1);
  }

  function showAfterModal() {}

  function closeAfterModal() {
    Object.keys(formData).map((key) => {
      delete formData[key];
    });
  }

  function selectCategoryData() {
    addCategoryTree({}).then(function (res) {
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
    });
  }

  function getDetail(id) {
    productDetailApi(id).then(function (res) {
      Object.assign(formData, res);
      //详情
      valueHtml.value = formData.newstext;
      //规格
      specData.value = formData.extra_attributes;
    });
  }

  //产品规格 开始 //产品规格 开始 //产品规格 开始
  const productSpecModel = ref();
  const specData = ref([]);

  function specSet() {
    let aaa = unref(productSpecModel);
    aaa.openModal();
  }

  function saveSpec(data) {
    formData.extra_attributes = data;
  }

  //产品规格 结束 //产品规格 结束 //产品规格 结束
  async function okModal() {
    formRef.value?.validate((errors) => {
      if (!errors) {
        const editor = editorRef.value;
        if (editor) {
          formData.newstext = editor.getHtml();
        }
        if (formData.id) {
          productEditApi(formData, formData.id).then(function () {
            closeModal();
            setSubLoading(false);
            emit('reloadProductTable');
          });
        } else {
          productAddApi(formData).then(function () {
            closeModal();
            setSubLoading(false);
            emit('reloadProductTable');
          });
        }
      }
    });
    setTimeout(function () {
      setSubLoading(false);
    }, 2000);
  }

  onMounted(() => {
    nextTick(function () {
      selectCategoryData();
    });
  });

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

  .card-tabs .n-tabs-nav--bar-type {
    padding-left: 4px;
  }
</style>
