<template>
  <div>
    <n-card :bordered="false" class="mt-2 proCard">
      <BasicTable
        ref="actionRef"
        :single-line="false"
        :columns="integralColumns"
        :request="loadDataTable"
        :row-key="(row) => row.id"
      >
        <template #tableTitle>
          <n-button type="primary" @click="addFun"> 添加 </n-button>
        </template>
      </BasicTable>
    </n-card>
    <create ref="createRef" @reloadTable="reloadTable"></create>
  </div>
</template>

<script lang="ts" setup>
import { h, reactive, unref, ref } from 'vue';
import { integrals } from '@/api/wx/integral';
import { BasicTable, TableAction } from '@/components/Table';
import { integralColumns } from '@/views/wx/columns';
import create from '@/views/wx/integral/create.vue';

const createRef = ref();

const actionRef = ref();
const params = reactive({
  pageSize: 10,
});

const loadDataTable = async (res) => {
  return await integrals({ ...res, ...params });
};
function reloadTable() {
  actionRef.value.reload();
}
function addFun() {
  let aaa = unref(createRef);
  aaa.modalName = '添加';
  aaa.openModal();
}

const actionColumn = reactive({
  width: 120,
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
  console.log(record);
}
function handleDelete(record) {
  console.log(record);
}

function addTable() {}
</script>

<style scoped></style>
