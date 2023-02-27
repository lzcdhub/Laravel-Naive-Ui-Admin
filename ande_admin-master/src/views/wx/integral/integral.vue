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
        :columns="integralColumns"
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
  import create from '@/views/wx/integral/create.vue';
  import { h, reactive, unref, ref } from 'vue';
  import { integrals, integralsDel } from '@/api/wx/integral';
  import { BasicTable, TableAction } from '@/components/Table';
  import { integralColumns } from '@/views/wx/columns';
  import { NImage, useDialog } from 'naive-ui';
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
    return await integrals({ ...res, ...params });
  };

  function reloadTable() {
    actionRef.value.reload();
  }

  const actionColumn = reactive({
    width: 90,
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
          // {
          //   label: '删除',
          //   onClick: handleDelete.bind(null, record),
          // },
        ],
      });
    },
  });

  function handleEdit(record) {
    let aaa = unref(createRef);
    aaa.modalName = '编辑';
    Object.assign(aaa.formData, unref(record));
    aaa.openModal();
  }

  function handleDelete(record) {
    dialog.warning({
      title: '警告',
      content: '你确定要删除 id=' + record.id + ' 这条数据吗？',
      positiveText: '确定',
      negativeText: '不确定',
      onPositiveClick: () => {
        integralsDel(record.id).then(function () {
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
</script>

<style scoped></style>
