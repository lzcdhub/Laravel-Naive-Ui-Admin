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
          <n-button type="primary" @click="addFun"> 新增会员 </n-button>
        </template>
      </BasicTable>
    </n-card>
    <create ref="createRef" @reloadTable="reloadTable"></create>
  </div>
</template>

<script lang="ts" setup>
  import { h, reactive, unref, ref } from 'vue';
  import { memberDel, members } from '@/api/system/member';
  import { BasicTable, TableAction } from '@/components/Table';
  import { NTag, useDialog } from 'naive-ui';
  import create from '@/views/system/member/create.vue';
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
      title: '用户名',
      key: 'username',
    },
    {
      title: '姓名',
      key: 'real_name',
      width: 150,
      align: 'center',
    },
    {
      title: '电话',
      key: 'phone',
      width: 150,
      align: 'center',
    },
    {
      title: '微信',
      key: 'wx_binding',
      width: 100,
      align: 'center',
      render(row) {
        return h(
          NTag,
          {
            type: row.wx_binding == 1 ? 'success' : 'error',
          },
          {
            default: () => (row.wx_binding == 1 ? '已绑定' : '未绑定'),
          }
        );
      },
    },
    {
      title: '新增时间',
      key: 'created_at',
      width: 180,
      align: 'center',
    },
  ];

  //serach
  const schemas: FormSchema[] = [
    {
      field: 'username',
      component: 'NInput',
      label: '用户名称',
    },
    {
      field: 'real_name',
      component: 'NInput',
      label: '姓名',
    },
    {
      field: 'phone',
      component: 'NInput',
      label: '电话',
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

  const loadDataTable = async (res) => {
    return await members({ ...res, ...params });
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
    aaa.getDetail(record.id);
    aaa.modalName = '编辑会员';
    aaa.openModal();
  }

  function handleDelete(record) {
    dialog.warning({
      title: '警告',
      content: '你确定要删除 ' + record.username + ' 吗？',
      positiveText: '确定',
      negativeText: '不确定',
      onPositiveClick: () => {
        memberDel(record.id).then(function () {
          reloadTable();
        });
      },
    });
  }

  function addFun() {
    let aaa = unref(createRef);
    aaa.modalName = '新增会员';
    aaa.openModal();
  }
</script>

<style scoped></style>
