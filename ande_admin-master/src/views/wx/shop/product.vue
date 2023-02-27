<template>
  <div>
    <n-card :bordered="false" class="mt-4 proCard">
      <BasicForm @register="register" @submit="handleSubmit" @reset="handleReset">
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
        :scroll-x="1800"
      >
        <template #tableTitle>
          <n-button type="primary" @click="addProductMoal"> 新增礼品 </n-button>
        </template>
        <template #action>
          <TableAction />
        </template>
      </BasicTable>
    </n-card>
    <createProduct ref="createDataModal" @reloadProductTable="reloadProductTable"></createProduct>
  </div>
</template>

<script lang="ts" setup>
  import CreateProduct from '@/views/wx/shop/createProduct.vue';
  import { BasicTable, TableAction } from '@/components/Table';
  import { productDelApi, productListApi, tabbingOrderApi } from '@/api/wx/product';
  import { BasicForm, FormSchema, useForm } from '@/components/Form/index';
  import { h, onMounted, reactive, unref } from 'vue';
  import { ref } from 'vue-demi';
  import { useDialog, NImage } from 'naive-ui';
  import { columns } from '@/views/wx/shop/productColumns';
  import { useRoute, useRouter } from 'vue-router';

  //public
  const dialog = useDialog();
  const createDataModal = ref();

  //table
  const actionRef = ref();
  const params = reactive({});

  //serach
  const schemas: FormSchema[] = [
    {
      field: 'name',
      component: 'NInput',
      label: '分类',
    },
    {
      field: 'title',
      component: 'NInput',
      label: '名称',
    },
    {
      field: 'status_f',
      component: 'NSelect',
      label: '状态',
      componentProps: {
        options: [
          { label: '上架', value: '1' },
          { label: '下架', value: '2' },
        ],
      },
    },
    {
      field: 'create_at',
      component: 'NDatePicker',
      label: '新增时间',
      componentProps: {
        type: 'date',
        clearable: true,
        onUpdateValue: (e: any) => {
          console.log(e);
        },
      },
    },
    {
      field: 'productno',
      component: 'NInput',
      label: '编号',
    },
    {
      field: 'pbrand',
      component: 'NInput',
      label: '品牌',
    },
  ];
  const [register, {}] = useForm({
    gridProps: { cols: '1 s:1 m:2 l:3 xl:4 2xl:4' },
    labelWidth: 90,
    schemas,
  });

  const loadDataTable = async (res: any) => {
    let _params = {
      ...unref(params),
      ...res,
    };
    return await productListApi(_params);
  };

  const router = useRouter();
  const route = useRoute();

  const actionColumn = reactive({
    width: 140,
    title: '操作',
    key: 'action',
    fixed: 'right',
    align: 'center',
    render(record) {
      //@ts-ignore
      return h(TableAction, {
        key: 'a',
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

  function handleDelete(record: Recordable) {
    dialog.warning({
      title: '警告',
      content: '你确定要删除 ' + record.title || record.name + '吗？',
      positiveText: '确定',
      negativeText: '不确定',
      onPositiveClick: () => {
        productDelApi(record.id).then(function () {
          reloadProductTable();
        });
      },
    });
  }

  function handleEdit(record: Recordable) {
    let aaa = unref(createDataModal);
    aaa.modalName = '编辑礼品';
    aaa.openModal();
    aaa.getDetail(record.id);
  }

  function addProductMoal() {
    let aaa = unref(createDataModal);
    aaa.modalName = '新增礼品';
    aaa.openModal();
  }

  function reloadProductTable() {
    actionRef.value.reload();
  }

  function handleSubmit(values: Recordable) {
    Object.assign(params, values);
    reloadProductTable();
  }

  function handleReset(values: Recordable) {
    Object.keys(params).map((key) => {
      delete params[key];
    });
    reloadProductTable();
  }

  onMounted(() => {});
</script>
