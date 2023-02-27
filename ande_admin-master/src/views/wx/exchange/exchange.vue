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
        :columns="exchangeColumns"
        :request="loadDataTable"
        :row-key="(row) => row.id"
        :actionColumn="actionColumn"
      >
        <template #tableTitle>
          <n-button type="primary" @click="addFun"> 新增记录</n-button>
        </template>
      </BasicTable>
    </n-card>
    <create ref="createRef" @reloadTable="reloadTable"></create>
  </div>
</template>

<script lang="ts" setup>
  import { h, reactive, unref, ref } from 'vue';
  import { exchanges, exchangeDel, exchangeReceive } from '@/api/wx/exchange';
  import { BasicTable, TableAction } from '@/components/Table';
  import { exchangeColumns } from '@/views/wx/columns';
  import { useDialog } from 'naive-ui';
  import create from '@/views/wx/exchange/create.vue';
  import { FormSchema, useForm } from '@/components/Form';
  import { members } from '@/api/system/member';

  const dialog = useDialog();
  const createRef = ref();

  const actionRef = ref();
  const params = reactive({
    pageSize: 10,
  });

  //serach
  const userOptionsRef = ref([]);
  const userSelectLoadRef = ref(false);
  const schemas: FormSchema[] = [
    {
      field: 'member_id',
      component: 'NSelect',
      label: '用户名称',
      componentProps: {
        filterable: true,
        placeholder: '请输入手机号后四位',
        loading: userSelectLoadRef,
        options: userOptionsRef,
        labelField: 'username',
        valueField: 'id',
        onSearch: (value: string) => {
          handleSearch(value);
        },
      },
    },
    {
      field: 'remark',
      component: 'NInput',
      label: '备注',
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

  function handleSearch(query) {
    if (!query.length) {
      userOptionsRef.value = [];
    } else if (query.length > 3) {
      getUserSelect(query);
    }
  }

  function getUserSelect(query = '') {
    userSelectLoadRef.value = true;
    members({ username: query, operate: 'select', pageSize: 20 }).then(function (res) {
      userOptionsRef.value = res.list;
      userSelectLoadRef.value = false;
    });
  }

  //serach end

  const loadDataTable = async (res) => {
    return await exchanges({ ...res, ...params });
  };

  function reloadTable() {
    actionRef.value.reload();
  }

  const actionColumn = reactive({
    width: 210,
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
            ifShow: (row) => {
              if (record.is_finish == 1) {
                return true;
              }
              return false;
            },
          },
          {
            label: '删除',
            onClick: handleDelete.bind(null, record),
            ifShow: (row) => {
              if (record.is_finish == 1) {
                return true;
              }
              return false;
            },
          },
          {
            label: '领取',
            color: '#2080f0',
            ifShow: (row) => {
              if (record.is_finish == 1) {
                return true;
              }
              return false;
            },
            onClick: handleReceive.bind(null, record),
          },
        ],
      });
    },
  });

  function handleEdit(record) {
    let aaa = unref(createRef);
    aaa.modalName = '编辑';
    Object.assign(aaa.formData, unref(record));
    aaa.openModal();
    //aaa.disabledRef = true;
  }

  function handleDelete(record) {
    dialog.warning({
      title: '警告',
      content: '你确定要删除 id=' + record.id + ' 这条数据吗？',
      positiveText: '确定',
      negativeText: '不确定',
      onPositiveClick: () => {
        exchangeDel(record.id).then(function () {
          reloadTable();
        });
      },
    });
  }

  function handleReceive(record) {
    dialog.warning({
      title: '确认领取',
      positiveText: '确定',
      negativeText: '不确定',
      onPositiveClick: () => {
        exchangeReceive(record.id).then(function () {
          reloadTable();
        });
      },
    });
  }

  function addFun() {
    let aaa = unref(createRef);
    aaa.modalName = '新增记录';
    aaa.openModal();
  }

  function addTable() {}
</script>

<style scoped></style>
