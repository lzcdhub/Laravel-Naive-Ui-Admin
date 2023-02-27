<template>
  <div>
    <basicModal
      ref="modalRef"
      style="width: 1200px"
      class="basicModal"
      @register="modalRegister"
      @on-ok="okModal"
      @on-close="closeAfterModal"
      :on-after-enter="showAfterModal"
    >
      <template #default>
        <n-scrollbar style="max-height: 70vh">
          <n-form
            ref="formRef"
            class="pad-top-10 add_form"
            :model="formData"
            :rules="rules"
            label-placement="left"
            label-width="auto"
            require-mark-placement="right-hanging"
          >
            <n-form-item label="用户名" path="username">
              <n-input v-model:value="formData.username" />
            </n-form-item>
            <n-form-item label="密码" path="password">
              <n-input v-model:value="formData.password" :placeholder="passPrompt" />
            </n-form-item>
            <n-form-item label="姓名" path="real_name">
              <n-input v-model:value="formData.real_name" />
            </n-form-item>
            <n-form-item label="性别" path="gender">
              <n-radio-group v-model:value="formData.gender" default-value="0">
                <n-space>
                  <n-radio label="未知" value="0" />
                  <n-radio label="男" value="1" />
                  <n-radio label="女" value="2" />
                </n-space>
              </n-radio-group>
            </n-form-item>
            <n-form-item label="电话" path="phone">
              <n-input v-model:value="formData.phone" />
            </n-form-item>
          </n-form>
        </n-scrollbar>
      </template>
    </basicModal>
  </div>
</template>

<script setup>
  import { ref } from 'vue-demi';
  import { defineExpose, onMounted, reactive, nextTick, unref } from 'vue';
  import { useModal } from '@/components/Modal';
  import { useMessage } from 'naive-ui';
  import { memberEdit, memberAdd, memberDetail } from '@/api/system/member';

  const message = useMessage();

  const modalName = ref('');
  const [modalRegister, { openModal, closeModal, setSubLoading }] = useModal({ title: modalName });
  const emit = defineEmits(['reloadTable', 'register']);

  const formRef = ref();
  const formData = reactive({});
  const rules = ref([]);
  const selectParentData = ref([]);
  const loadingRef = ref(false);
  const passPrompt = ref('');

  async function okModal() {
    formRef.value?.validate((errors) => {
      if (!errors) {
        if (formData.id) {
          memberEdit(formData, formData.id).then(function () {
            closeModal();
            setSubLoading(false);
            emit('reloadTable');
          });
        } else {
          memberAdd(formData).then(function () {
            closeModal();
            setSubLoading(false);
            emit('reloadTable');
          });
        }
      }
    });
    setTimeout(function () {
      setSubLoading(false);
    }, 1000);
  }

  function closeAfterModal() {
    Object.keys(formData).map((key) => {
      delete formData[key];
    });
  }

  function showAfterModal() {
    if (modalName.value == '编辑会员') {
      passPrompt.value = '不修改密码不用填写';
    } else {
      passPrompt.value = '默认密码为admin123456';
    }
  }

  function getDetail(id) {
    memberDetail(id).then(function (res) {
      Object.assign(formData, res);
    });
  }

  defineExpose({
    openModal,
    modalName,
    getDetail,
  });
</script>

<style scoped>
  .add_form {
    margin-right: 20px;
    margin-left: 20px;
  }
</style>
