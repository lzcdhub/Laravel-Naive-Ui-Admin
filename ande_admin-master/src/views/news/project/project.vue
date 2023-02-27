<template>
  <div>
    <n-card :bordered="false" class="mt-2 proCard">
      <BasicForm
        @register="register"
        @submit="handleSubmit"
        @reset="handleReset"
        :showAdvancedButton="false"
      >
        <template #statusSlot="{ model, field }">
          <n-input v-model:value="model[field]" />
        </template>
      </BasicForm>
      <BasicTable
        ref="actionRef"
        :single-line="false"
        :columns="columns"
        :request="loadDataTable"
        :row-key="(row) => row.id"
        :actionColumn="actionColumn"
      >
        <template #tableTitle>
          <n-button type="primary" @click="addFun"> 新增项目</n-button>
        </template>
      </BasicTable>
    </n-card>
    <create ref="createRef" @reloadTable="reloadTable"></create>
  </div>
</template>

<script lang="ts" setup>
  import { h, reactive, unref, ref } from 'vue';
  import { projects, projectDel, projectState } from '@/api/news/project';
  import { BasicTable, TableAction } from '@/components/Table';
  import { NSwitch, useDialog } from 'naive-ui';
  import create from '@/views/news/project/create.vue';
  import { FormSchema, useForm } from '@/components/Form';

  const dialog = useDialog();
  const createRef = ref();

  const actionRef = ref();
  const params = reactive({
    pageSize: 10,
  });

  const columns = [
    {
      title: 'id',
      key: 'id',
      width: 80,
      align: 'center',
    },
    {
      title: '项目分类',
      key: 'category.name',
      width: 200,
    },
    {
      title: '标题',
      key: 'title',
    },
    {
      title: '状态',
      key: 'status_f',
      width: 120,
      render(row) {
        return h(
          NSwitch,
          {
            value: row.status_f == 1 ? true : false,
            onClick: handleSwitch.bind(null, row),
            round: false,
          },
          {
            checked: (value: boolean) => {
              return '上架';
            },
            unchecked: (value: boolean) => {
              return '下架';
            },
          }
        );
      },
      align: 'center',
    },
    {
      title: '新增时间',
      key: 'created_at',
      width: 160,
      align: 'center',
    },
  ];

  //serach
  const schemas: FormSchema[] = [
    {
      field: 'title',
      component: 'NInput',
      label: '标题',
    },
    {
      field: 'status_f',
      component: 'NSelect',
      componentProps: {
        options: [
          {
            label: '上架',
            value: '1',
          },
          {
            label: '下架',
            value: '2',
          },
        ],
      },
      label: '状态',
    },
    {
      field: 'create_at',
      component: 'NDatePicker',
      label: '新增时间',
      componentProps: {
        type: 'date',
        clearable: true,
      },
    },
  ];
  const [register, {}] = useForm({
    gridProps: { cols: '1 s:1 m:2 l:3 xl:4 2xl:4' },
    labelWidth: 80,
    schemas,
  });

  function handleSubmit(values: Recordable) {
    Object.assign(params, values);
    reloadTable();
  }

  function handleReset(values: Recordable) {
    Object.keys(params).map((key) => {
      delete params[key];
    });
    reloadTable();
  }

  //serach end

  function handleSwitch(row) {
    row.status_f = row.status_f == 1 ? 2 : 1;
    projectState(row.id, { status_f: row.status_f });
  }

  const loadDataTable = async (res) => {
    return await projects({ ...res, ...params });
  };

  function reloadTable() {
    actionRef.value.reload();
  }

  const actionColumn = reactive({
    width: 160,
    title: '操作',
    key: 'action',
    fixed: 'right',
    align: 'center',
    render(record) {
      return h(TableAction as any, {
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
      });
    },
  });

  function handleEdit(record) {
    let aaa = unref(createRef);
    aaa.modalName = '编辑项目';
    aaa.openModal();
    aaa.getDetail(record.id);
  }

  function handleDelete(record) {
    dialog.warning({
      title: '警告',
      content: '你确定要删除 ' + record.title + ' 这条数据吗？',
      positiveText: '确定',
      negativeText: '不确定',
      onPositiveClick: () => {
        projectDel(record.id).then(function () {
          reloadTable();
        });
      },
    });
  }

  function addFun() {
    let aaa = unref(createRef);
    aaa.modalName = '新增项目';
    aaa.openModal();
  }

  function addTable() {}
</script>

<style scoped></style>
