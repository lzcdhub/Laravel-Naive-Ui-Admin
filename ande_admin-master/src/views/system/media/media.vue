<template>
  <div>
    <n-card :bordered="false" class="mt-2 proCard">
      <BasicTable
        ref="tableRef"
        :single-line="false"
        :columns="columns"
        :request="loadDataTable"
        :row-key="(row) => row.id"
        :actionColumn="actionColumn"
      >
      </BasicTable>
    </n-card>
  </div>
</template>

<script lang="ts" setup>
  import { h, reactive, unref, ref } from 'vue';
  import { medias, mediaDel } from '@/api/system/media';
  import { BasicTable, TableAction } from '@/components/Table';
  import { NImage, useDialog } from 'naive-ui';

  const dialog = useDialog();
  const createRef = ref();

  const tableRef = ref();
  const params = reactive({
    pageSize: 10,
  });

  const columns = ref([
    {
      title: 'id',
      key: 'id',
      width: 90,
      align: 'center',
    },
    {
      title: '模块',
      key: 'disk',
      width: 100,
      align: 'center',
    },
    {
      title: '目录',
      key: 'directory',
      align: 'center',
      width: 180,
    },
    {
      title: '图片',
      key: 'media_url',
      width: 100,
      render(row) {
        return h(NImage, {
          width: 50,
          src: row.media_url,
        });
      },
      align: 'center',
    },
    {
      title: '文件名',
      key: 'filename',
      align: 'left',
      render(row) {
        return row.filename + '.' + row.extension;
      },
    },
    {
      title: '文件大小',
      key: 'size',
      align: 'center',
      width: 120,
    },
    {
      title: '文件类型',
      key: 'mime_type',
      align: 'center',
      width: 120,
    },
    {
      title: '新增时间',
      key: 'created_at',
      width: 180,
      align: 'center',
    },
  ]);

  const loadDataTable = async (res) => {
    return await medias({ ...res, ...params });
  };

  function reloadTable() {
    tableRef.value.reload();
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
      content: '你确定要删除 ' + record.filename + '.' + record.extension + ' 吗？',
      positiveText: '确定',
      negativeText: '不确定',
      onPositiveClick: () => {
        mediaDel(record.id).then(function () {
          reloadTable();
        });
      },
    });
  }
</script>

<style scoped></style>
