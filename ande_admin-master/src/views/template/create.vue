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
            <n-form-item label="名称" path="title">
              <n-input v-model:value="formData.title" />
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

  const modalName = ref('');
  const [modalRegister, { openModal, closeModal, setSubLoading }] = useModal({ title: modalName });

  const formRef = ref();
  const formData = reactive({});
  const rules = ref([]);

  function okModal() {}

  function closeAfterModal() {}

  function showAfterModal() {}

  defineExpose({
    openModal,
    modalName,
  });
</script>

<style scoped>
  .add_form {
    margin-right: 20px;
    margin-left: 20px;
  }
</style>
