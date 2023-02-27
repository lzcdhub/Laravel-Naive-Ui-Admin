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
            <n-form-item label="用户" path="member_id">
              <n-select
                v-model:value="formData.member_id"
                filterable
                placeholder="搜索用户"
                :options="optionsRef"
                :loading="loadingRef"
                label-field="username"
                value-field="id"
                clearable
                remote
                @search="handleSearch"
                :render-label="renderLabel"
              />
            </n-form-item>
            <n-form-item label="积分" path="integral">
              <n-input-number v-model:value="formData.integral" clearable />
            </n-form-item>
            <div v-if="modalName == '编辑'">
              <n-form-item label="类型" path="integral_type">
                <n-radio-group v-model:value="formData.integral_type">
                  <n-space>
                    <n-radio label="增加" value="1" />
                    <n-radio label="减少" value="2" />
                  </n-space>
                </n-radio-group>
              </n-form-item>
              <n-form-item label="积分(原)" path="old_integral">
                <n-input-number v-model:value="formData.old_integral" clearable />
              </n-form-item>
              <n-form-item label="积分(结)" path="new_integral">
                <n-input-number v-model:value="formData.new_integral" clearable />
              </n-form-item>
            </div>
            <n-form-item label="备注" path="remark">
              <n-input v-model:value="formData.remark" type="textarea" />
            </n-form-item>
          </n-form>
        </n-scrollbar>
      </template>
    </basicModal>
  </div>
</template>

<script setup>
  import { ref } from 'vue-demi';
  import { defineExpose, onMounted, reactive, nextTick, unref, h } from 'vue';
  import { useModal } from '@/components/Modal';
  import { members } from '@/api/system/member';
  import { integralsAdd, integralsEdit } from '@/api/wx/integral';
  import { NTag } from 'naive-ui';

  const emit = defineEmits(['reloadTable', 'register']);

  const modalName = ref('');
  const [modalRegister, { openModal, closeModal, setSubLoading }] = useModal({ title: modalName });

  const formRef = ref();
  const formData = reactive({});
  const rules = ref([]);
  const loadingRef = ref(false);
  const optionsRef = ref([]);

  function handleSearch(query) {
    if (!query.length) {
      optionsRef.value = [];
    } else if (query.length > 3) {
      getUserSelect(query);
    }
  }
  function okModal() {
    formRef.value?.validate((errors) => {
      if (!errors) {
        if (formData.id) {
          integralsEdit(formData, formData.id).then(function () {
            closeModal();
            setSubLoading(false);
            emit('reloadTable');
          });
        } else {
          integralsAdd(formData).then(function () {
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
    optionsRef.value = [];
  }

  function showAfterModal() {
    if (formData.id) {
      getUserSelect(formData.member.username);
    }
  }

  function getUserSelect(query = '') {
    loadingRef.value = true;
    members({ username: query, operate: 'select', pageSize: 20 }).then(function (res) {
      optionsRef.value = res.list;
      loadingRef.value = false;
    });
  }

  function renderLabel(row) {
    if (formData.id) {
      return row.username;
    }
    return h('div', [
      h(
        NTag,
        {
          style: {
            marginRight: '6px',
            width: '105px',
          },
          type: 'info',
        },
        { default: () => row.username }
      ),
      h(
        NTag,
        {
          style: {
            marginRight: '6px',
            width: '120px',
          },
          type: 'success',
          isShow: false,
        },
        {
          default: () => {
            if (row.actual_integral) {
              return '积分:' + row.actual_integral;
            }
            return '积分:' + 0;
          },
        }
      ),
    ]);
  }

  defineExpose({
    openModal,
    modalName,
    formData,
  });
</script>

<style scoped>
  .add_form {
    margin-right: 20px;
    margin-left: 20px;
  }
</style>
