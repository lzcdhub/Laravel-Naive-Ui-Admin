<template>
  <div>
    <n-card :bordered="false" class="mt-4 proCard">
      <BasicTable
        ref="actionRef"
        :single-line="false"
        :columns="columns"
        :request="loadDataTable"
        :actionColumn="actionColumn"
      >
        <template #tableTitle>
          <n-button type="primary" @click="addAdmin"> 新增角色 </n-button>
        </template>

        <template #action>
          <TableAction />
        </template>
      </BasicTable>
    </n-card>

    <n-modal v-model:show="showModal" :show-icon="false" preset="dialog" :title="editRoleTitle">
      <div class="py-3 menu-list">
        <n-scrollbar style="max-height: 50vh">
          <n-tree
            ref="permissionTreeRef"
            block-line
            checkable
            cascade
            :virtual-scroll="true"
            :data="treeData"
            :expandedKeys="expandedKeys"
            :checked-keys="checkedKeys"
            style="max-height: 950px; overflow: hidden"
            @update:checked-keys="checkedTree"
            @update:expanded-keys="onExpandedKeys"
          />
        </n-scrollbar>
      </div>
      <template #action>
        <n-space>
          <n-button type="info" ghost icon-placement="left" @click="packHandle">
            全部{{ expandedKeys.length ? '收起' : '展开' }}
          </n-button>
          <n-button type="info" ghost icon-placement="left" @click="checkedAllHandle">
            全部{{ checkedAll ? '取消' : '选择' }}
          </n-button>
          <n-button type="primary" :loading="formBtnLoading" @click="confirmForm">提交</n-button>
        </n-space>
      </template>
    </n-modal>

    <n-modal v-model:show="showAdmin" :show-icon="false" preset="dialog" :title="addAdminTitle">
      <roledata :editAdminId="editAdminId" @closeEdit="closeEdit"></roledata>
    </n-modal>
  </div>
</template>

<script lang="ts" setup>
  import { reactive, ref, unref, h, onMounted, nextTick } from 'vue';
  import { useMessage, useDialog } from 'naive-ui';
  import { BasicTable, TableAction } from '@/components/Table';
  import { assignPermission, getRoleList, roleDel } from '@/api/system/role';
  import { getPermissionList } from '@/api/system/menu';
  import { columns } from './columns';
  import { PlusOutlined } from '@vicons/antd';
  import { getTreeAll } from '@/utils';
  import { useRouter } from 'vue-router';
  import Roledata from '@/views/system/role/roledata.vue';

  const router = useRouter();
  const formRef: any = ref(null);
  const message = useMessage();
  const dialog = useDialog();
  const actionRef = ref();

  const showModal = ref(false);
  const showAdmin = ref(false);
  const formBtnLoading = ref(false);
  const checkedAll = ref(false);
  const editRoleTitle = ref('');
  const addAdminTitle = ref('');
  const treeData = ref([]);
  const expandedKeys = ref([]);
  const checkedKeys = ref([]);
  const permissionTreeRef = ref();
  const editAdminId = ref();

  const params = reactive({
    pageSize: 10,
  });

  const actionColumn = reactive({
    width: 250,
    title: '操作',
    key: 'action',
    fixed: 'right',
    align: 'center',
    render(record) {
      return h(TableAction, {
        style: 'button',
        actions: [
          {
            label: '菜单权限',
            onClick: handleMenuAuth.bind(null, record),
            // 根据业务控制是否显示 isShow 和 auth 是并且关系
            ifShow: () => {
              return true;
            },
            // 根据权限控制是否显示: 有权限，会显示，支持多个
            // auth: ['basic_list'],
          },
          {
            label: '编辑',
            onClick: handleEdit.bind(null, record),
            ifShow: () => {
              return true;
            },
            // auth: ['basic_list'],
          },
          {
            label: '删除',
            onClick: handleDelete.bind(null, record),
            // 根据业务控制是否显示 isShow 和 auth 是并且关系
            ifShow: () => {
              return true;
            },
            // 根据权限控制是否显示: 有权限，会显示，支持多个
            // auth: ['basic_list'],
          },
        ],
      });
    },
  });

  const loadDataTable = async (res: any) => {
    let _params = {
      ...unref(params),
      ...res,
    };
    return await getRoleList(_params);
  };

  function onCheckedRow(rowKeys: any[]) {
    console.log(rowKeys);
  }

  function reloadTable() {
    actionRef.value.reload();
  }

  function handleEdit(record: Recordable) {
    editAdminId.value = record.id;
    showAdmin.value = true;
    addAdminTitle.value = '编辑角色';
    //router.push({ name: 'basic-info', params: { id: record.id } });
  }

  function closeEdit() {
    showAdmin.value = false;
    reloadTable();
  }

  function handleDelete(record: Recordable) {
    dialog.warning({
      title: '警告',
      content: '你确定要删除 ' + record.name + ' 吗？',
      positiveText: '确定',
      negativeText: '不确定',
      onPositiveClick: () => {
        roleDel(record.id).then(function () {
          reloadTable();
        });
      },
    });
  }

  function addAdmin() {
    showAdmin.value = true;
    addAdminTitle.value = '新增角色';
  }

  function handleMenuAuth(record: Recordable) {
    editAdminId.value = record.id;
    editRoleTitle.value = `分配 ${record.name} 的菜单权限`;
    checkedKeys.value = record.menu_keys;
    showModal.value = true;
  }

  function confirmForm(e: any) {
    formBtnLoading.value = true;
    const a = unref(permissionTreeRef).getCheckedData();
    const b = unref(permissionTreeRef).getIndeterminateData();
    let _params = {
      role_id: editAdminId.value,
      permissions: [...a.keys, ...b.keys],
    };
    assignPermission(_params).then(function (res) {
      message.success('操作成功');
      showModal.value = false;
      formBtnLoading.value = false;
      reloadTable();
    });
  }

  function checkedTree(key, option, menu) {
    checkedKeys.value = key;
  }

  function onExpandedKeys(keys) {
    expandedKeys.value = keys;
  }

  function packHandle() {
    if (expandedKeys.value.length) {
      expandedKeys.value = [];
    } else {
      expandedKeys.value = treeData.value.map((item: any) => item.key) as [];
    }
  }

  function checkedAllHandle() {
    if (!checkedAll.value) {
      checkedKeys.value = getTreeAll(treeData.value);
      checkedAll.value = true;
    } else {
      checkedKeys.value = [];
      checkedAll.value = false;
    }
  }

  onMounted(() => {
    setTimeout(function () {
      getTree(); //延迟获取权限树结构
    }, 500);
  });

  function getTree() {
    getPermissionList().then(function (treeMenuList) {
      expandedKeys.value = treeMenuList.list.map((item) => item.key);
      treeData.value = treeMenuList.list;
    });
  }
</script>

<style lang="less" scoped></style>
