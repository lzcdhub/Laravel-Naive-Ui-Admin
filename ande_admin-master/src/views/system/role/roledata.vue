<template>
  <div>
    <n-card :bordered="false" class="mt-4 proCard">
      <BasicForm
        submit-button-text="提交"
        layout="horizontal"
        :grid-props="{ cols: 1 }"
        :schemas="schemas"
        @submit="handleSubmit"
        @reset="handleReset"
      ></BasicForm>
    </n-card>
  </div>
</template>

<script lang="ts" setup>
  import { BasicForm, FormSchema } from '@/components/Form';
  import { useMessage } from 'naive-ui';
  import { defineProps } from 'vue';
  import { roleEdit, roleCreate } from '@/api/system/role';

  const parentData = defineProps({
    editAdminId: Number,
  });

  const emit = defineEmits(['closeEdit']);

  const message = useMessage();

  const schemas: FormSchema[] = [
    {
      field: 'name',
      component: 'NInput',
      defaultValue: '',
      label: '角色名',
      componentProps: {
        placeholder: '请输入角色名',
      },
    },
  ];

  function handleSubmit(values: Recordable) {
    if (parentData.editAdminId) {
      roleEdit(values, parentData.editAdminId).then(function (value) {
        console.log(value);
        emit('closeEdit');
        message.success('操作成功');
      });
    } else {
      roleCreate(values).then(function (value) {
        console.log(value);
        emit('closeEdit');
        message.success('操作成功');
      });
    }
  }

  function handleReset(values: Recordable) {
    console.log(values);
  }
</script>

<style scoped></style>
