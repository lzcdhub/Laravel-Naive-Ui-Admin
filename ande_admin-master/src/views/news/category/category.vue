<template>
  <div>
    <n-card :bordered="false" class="mt-4 proCard">
      <BasicTable
        ref="actionRef"
        :single-line="false"
        :columns="columns"
        :request="loadDataTable"
        :pagination="false"
        :row-key="(row) => row.id"
        :actionColumn="actionColumn"
      >
        <template #tableTitle>
          <n-button type="primary" @click="addCategoryFun"> 新增分类</n-button>
        </template>
        <template #action>
          <TableAction />
        </template>
      </BasicTable>
    </n-card>

    <n-modal
      v-model:show="addCategory"
      preset="card"
      style="width: 800px"
      :show-icon="false"
      :title="addCategoryTitle"
      :on-after-leave="closeModal"
    >
      <n-form
        ref="formRef"
        :model="formData"
        :rules="rules"
        label-placement="left"
        label-width="auto"
        require-mark-placement="right-hanging"
      >
        <n-form-item label="父级分类" path="parent_id">
          <n-tree-select
            filterable
            v-model:value="formData.parent_id"
            :options="selectParentData"
            clearable
            key-field="id"
            label-field="name"
          />
        </n-form-item>
        <n-form-item label="名称" path="name">
          <n-input v-model:value="formData.name" placeholder="" />
        </n-form-item>
        <n-form-item path="auth" style="margin-left: 100px">
          <n-space>
            <n-button type="primary" :loading="subLoading" @click="formSubmit">提交</n-button>
            <n-button @click="handleReset">重置</n-button>
          </n-space>
        </n-form-item>
      </n-form>
    </n-modal>
  </div>
</template>

<script lang="ts" setup>
  import { BasicTable, TableAction } from '@/components/Table';
  import {
    newsCategorySelect,
    newsCategoryTree,
    newsCategoryDel,
    newsCategoryAdd,
    newsCategoryEdit,
    newsCategoryMove,
  } from '@/api/news/category';
  import { h, onMounted, reactive, unref } from 'vue';
  import { ref } from 'vue-demi';
  import { FormInst, FormItemRule, useMessage, useDialog } from 'naive-ui';
  import { addAdmin, editAdmin } from '@/api/system/admin';

  //public
  const dialog = useDialog();

  //table
  const actionRef = ref();
  const addCategory = ref(false);
  const addCategoryTitle = ref('');
  const params = reactive({});

  //form
  const formRef = ref();
  const formData = reactive({});
  const oldFormData = ref({});
  const rules = ref([]);
  const subLoading = ref(false);

  //create
  const selectParentData = ref([]);

  const loadDataTable = async (res: any) => {
    let _params = {
      ...unref(params),
      ...res,
    };
    return await newsCategoryTree(_params);
  };

  const columns = [
    {
      title: '序号',
      key: 'id',
      width: '200',
    },
    {
      title: '名称',
      key: 'name',
    },
  ];

  const actionColumn = reactive({
    width: 220,
    title: '操作',
    key: 'action',
    fixed: 'right',
    align: 'center',
    render(record) {
      return h(TableAction, {
        style: 'button',
        actions: [
          {
            label: '编辑',
            onClick: handleEdit.bind(null, record),
          },
          {
            label: '删除',
            onClick: handleDelete.bind(null, record),
          },
        ],
        dropDownActions: [
          {
            label: '上移',
            key: 'up',
          },
          {
            label: '下移',
            key: 'down',
          },
          {
            label: '新增',
            key: 'add',
          },
        ],
        select: (key) => {
          if (key == 'up' || key == 'down') {
            newsCategoryMove({ operate: key }, record.id).then(function () {
              reloadTable();
            });
          } else if (key == 'add') {
            Object.assign(formData, { parent_id: record.id });
            addCategoryFun();
          }
        },
      });
    },
  });

  function handleEdit(record: Recordable) {
    record.children = null;
    oldFormData.value = record;
    Object.assign(formData, record);
    formData.operate = 'edit';
    addCategory.value = true;
    addCategoryTitle.value = '编辑分类';
  }

  function handleDelete(record: Recordable) {
    dialog.warning({
      title: '警告',
      content: '你确定要删除 ' + record.name + ' 吗？',
      positiveText: '确定',
      negativeText: '不确定',
      onPositiveClick: () => {
        newsCategoryDel(record.id).then(function () {
          reloadTable();
        });
      },
    });
  }

  function addCategoryFun() {
    addCategory.value = true;
    addCategoryTitle.value = '新增分类';
  }

  function closeModal() {
    oldFormData.value = {};
    Object.keys(formData).map((key) => {
      delete formData[key];
    });
  }

  function reloadTable() {
    addCategory.value = false;
    actionRef.value.reload();
    selectCategoryData();
  }

  function formSubmit() {
    formRef.value?.validate((errors) => {
      if (!errors) {
        if (formData.id) {
          newsCategoryEdit(formData, formData.id).then(function () {
            reloadTable();
          });
        } else {
          newsCategoryAdd(formData).then(function () {
            reloadTable();
          });
        }
      }
    });
  }

  function handleReset() {
    Object.assign(formData, unref(oldFormData));
  }

  onMounted(() => {
    selectCategoryData();
  });

  function selectCategoryData() {
    newsCategorySelect({}).then(function (res) {
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
</script>
