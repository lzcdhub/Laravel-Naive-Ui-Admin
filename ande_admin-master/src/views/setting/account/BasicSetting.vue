<template>
  <n-grid cols="2 s:2 m:2 l:3 xl:3 2xl:3" responsive="screen">
    <n-grid-item>
      <n-form :label-width="80" :model="formValue" :rules="rules" ref="formRef">
        <n-form-item label="用户名" path="name">
          <n-input v-model:value="formValue.name" placeholder="请输入用户名" />
        </n-form-item>

        <n-form-item label="姓名" path="real_name">
          <n-input v-model:value="formValue.real_name" placeholder="请输入姓名" />
        </n-form-item>

        <n-form-item label="邮箱" path="email">
          <n-input placeholder="请输入邮箱" v-model:value="formValue.email" />
        </n-form-item>

        <n-form-item label="联系电话" path="phone">
          <n-input placeholder="请输入联系电话" v-model:value="formValue.phone" />
        </n-form-item>

        <n-form-item label="联系地址" path="address">
          <n-input v-model:value="formValue.address" type="textarea" placeholder="请输入联系地址" />
        </n-form-item>

        <div>
          <n-space>
            <n-button type="primary" @click="formSubmit">更新基本信息</n-button>
          </n-space>
        </div>
      </n-form>
    </n-grid-item>
  </n-grid>
</template>

<script lang="ts" setup>
  import { onMounted, reactive, ref } from 'vue';
  import { useMessage } from 'naive-ui';
  import { useUserStore } from '@/store/modules/user';
  import { editAdmin } from '@/api/system/admin';

  const rules = {
    name: {
      required: true,
      message: '请输入用户名',
      trigger: 'blur',
    },
  };
  const formRef: any = ref(null);
  const message = useMessage();

  const formValue = reactive({
    id: 0,
    name: '',
    phone: '',
    email: '',
    address: '',
    real_name: '',
  });

  onMounted(function () {
    let userInfo = useUserStore().getUserInfo;
    formValue.id = userInfo.id;
    formValue.name = userInfo.name;
    formValue.real_name = userInfo.real_name;
    formValue.phone = userInfo.phone;
    formValue.email = userInfo.email;
    formValue.address = userInfo.address;
  });

  function formSubmit() {
    formRef.value.validate((errors) => {
      if (!errors) {
        editAdmin(formValue, formValue.id);
      } else {
        message.error('验证失败，请填写完整信息');
      }
    });
  }
</script>
