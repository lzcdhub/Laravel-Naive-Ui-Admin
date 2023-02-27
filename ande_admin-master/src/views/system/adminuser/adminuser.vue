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
        @update:checked-row-keys="onCheckedRow"
      >
        <template #tableTitle>
          <n-button type="primary" @click="addTable"> 新增用户 </n-button>
        </template>
      </BasicTable>
    </n-card>

    <CreateAdmin ref="showCreateAdmin" @reloadTable="reloadTable" />
  </div>
</template>

<script lang="ts" setup>
  import { reactive, ref, h, unref } from 'vue';
  import { BasicTable, TableAction } from '@/components/Table';
  import { admins, deleteAdmin } from '@/api/system/admin';
  import { columns } from './basicColumns';
  import { useDialog, useMessage } from 'naive-ui';
  import { DeleteOutlined, EditOutlined } from '@vicons/antd';
  import CreateAdmin from '@/views/system/adminuser/CreateAdmin.vue';
  import { getTableList } from '@/api/table/list';
  import { FormSchema, useForm } from '@/components/Form';

  const message = useMessage();
  const dialog = useDialog();
  const actionRef = ref();
  const showCreateAdmin = ref();

  const params = reactive({
    pageSize: 10,
  });

  const actionColumn = reactive({
    width: 150,
    title: '操作',
    key: 'action',
    fixed: 'right',
    align: 'center',
    render(record) {
      return h(TableAction as any, {
        style: 'button',
        actions: createActions(record),
      });
    },
  });

  function createActions(record) {
    return [
      {
        label: '编辑',
        onClick: handleEdit.bind(null, record),
      },
      {
        label: '删除',
        onClick: handleDelete.bind(null, record),
      },
    ];
  }

  //serach
  const schemas: FormSchema[] = [
    {
      field: 'name',
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
    return await admins({ ...res, ...params });
  };

  function addTable() {
    let childFun = unref(showCreateAdmin);
    childFun.showAddModal();
  }

  function onCheckedRow(rowKeys) {
    console.log(rowKeys);
  }

  function reloadTable() {
    actionRef.value.reload();
  }

  function handleDelete(record) {
    dialog.warning({
      title: '提示',
      content: `您想删除 ${record.name}`,
      positiveText: '确定',
      negativeText: '取消',
      onPositiveClick: () => {
        deleteAdmin(record.id).then(function () {
          reloadTable();
        });
      },
      onNegativeClick: () => {},
    });
  }

  function handleEdit(record) {
    let childFun = unref(showCreateAdmin);
    childFun.showAddModal(record, 'edit');
  }
</script>

<style lang="less" scoped></style>
