<template>
  <div>
    <n-modal
      ref="thisModal"
      style="width: 1200px"
      v-model:show="showAddAdminModal"
      class="custom-card"
      preset="card"
      :title="modalTitle"
      size="huge"
      :bordered="false"
      :on-after-leave="closeModal"
    >
      <n-scrollbar style="max-height: 80vh; padding-right: 15px">
        <n-form
          ref="formRef"
          :model="formParam"
          :rules="rules"
          label-placement="left"
          label-width="auto"
          require-mark-placement="right-hanging"
        >
          <n-form-item label="账号名" path="name">
            <n-input v-model:value="formParam.name" />
          </n-form-item>
          <n-form-item label="邮箱" path="email">
            <n-input v-model:value="formParam.email" />
          </n-form-item>
          <n-form-item label="密码" path="password">
            <n-input v-model:value="formParam.password" :placeholder="passPrompt" />
          </n-form-item>
          <n-form-item label="姓名" path="real_name">
            <n-input v-model:value="formParam.real_name" />
          </n-form-item>
          <n-form-item label="电话" path="phone">
            <n-input v-model:value="formParam.phone" />
          </n-form-item>
          <n-form-item label="地址" path="address">
            <n-input v-model:value="formParam.address" />
          </n-form-item>
          <n-form-item label="角色">
            <n-transfer ref="transfer" v-model:value="formParam.roleValue" :options="roleList" />
          </n-form-item>
          <n-form-item path="auth" style="margin-left: 100px">
            <n-space>
              <n-button type="primary" :loading="subLoading" @click="formSubmit">提交</n-button>
              <n-button @click="handleReset">重置</n-button>
            </n-space>
          </n-form-item>
        </n-form>
      </n-scrollbar>
    </n-modal>
  </div>
</template>

<script lang="ts" setup>
  import { defineExpose, onMounted, reactive, ref, unref } from 'vue';
  import { FormInst, FormItemRule, useMessage } from 'naive-ui';
  import { addAdmin, editAdmin } from '@/api/system/admin';
  import { getRoleList } from '@/api/system/role';

  const formRef = ref(null); //<FormInst | null>
  const message = useMessage();

  const emit = defineEmits(['reloadTable']);
  const showAddAdminModal = ref(false);
  const modalTitle = ref('新增用户');
  const oldFormParam = ref({});
  const subLoading = ref(false);
  const formParam = reactive({});
  const passPrompt = ref('');
  const roleList = ref([]);

  const rules = {
    name: {
      required: true,
      trigger: ['blur'],
      message: '请输入账号名(不少于5位)',
    },
    password: [
      {
        message: '密码不少于6位',
        validator(rule: FormItemRule, value: string) {
          if (value) {
            if (value.length < 6) {
              return false;
            }
          }
          return true;
        },
        trigger: ['blur', 'input'],
      },
    ],
  };

  function showAddModal(param = {}, type = 'add') {
    if (type == 'edit') {
      modalTitle.value = '编辑用户';
      passPrompt.value = '不修改密码不用填写';
      Object.assign(formParam, unref(param));
      oldFormParam.value = param;
      formParam.roleValue = param.roles.map((v) => v.id); //角色默认id
    } else {
      formParam.roleValue = [];
      modalTitle.value = '新增用户';
      passPrompt.value = '默认密码为admin123456';
    }
    showAddAdminModal.value = true;
  }

  function handleReset() {
    Object.assign(formParam, unref(oldFormParam));
  }

  function formSubmit() {
    formRef.value?.validate((errors) => {
      if (!errors) {
        if (formParam.id) {
          editAdmin(formParam, formParam.id).then(function () {
            emit('reloadTable');
            showAddAdminModal.value = false;
          });
        } else {
          addAdmin(formParam).then(function () {
            emit('reloadTable');
            showAddAdminModal.value = false;
          });
        }
      }
    });
  }

  function closeModal() {
    oldFormParam.value = {};
    Object.keys(formParam).map((key) => {
      delete formParam[key];
    });
  }

  onMounted(async () => {
    getRoleList().then(function (res) {
      roleList.value = res.list.map((v, i) => ({
        label: v.name,
        value: v.id,
      }));
    });
  });

  defineExpose({
    showAddModal,
  });
</script>
