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
            <n-form-item label="用户名称" path="member_id">
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
                @search="userSearch"
                :disabled="disabledRef"
                :render-label="renderLabel"
              />
            </n-form-item>
            <n-form-item label="礼品名称" path="product_id">
              <n-select
                v-model:value="formData.product_id"
                filterable
                placeholder="搜索礼品"
                :options="optionsTwoRef"
                :loading="loadingTwoRef"
                label-field="title"
                value-field="id"
                clearable
                remote
                @search="productSearch"
                :disabled="disabledRef"
                :render-label="renderProLabel"
              />
            </n-form-item>
            <n-form-item label="兑换数量" path="num">
              <n-input-number
                v-model:value="formData.num"
                default-value="1"
                clearable
                :disabled="disabledRef"
              />
            </n-form-item>
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
  import { exchangeAdd, exchangeEdit } from '@/api/wx/exchange';
  import { productListApi } from '@/api/wx/product';
  import { NTag, NTooltip } from 'naive-ui';

  const emit = defineEmits(['reloadTable', 'register']);

  const modalName = ref('');
  const [modalRegister, { openModal, closeModal, setSubLoading }] = useModal({ title: modalName });

  const formRef = ref();
  const formData = reactive({});
  const rules = ref([]);
  const loadingRef = ref(false);
  const optionsRef = ref([]);
  const loadingTwoRef = ref(false);
  const optionsTwoRef = ref([]);
  const disabledRef = ref(false);

  function userSearch(query) {
    if (!query.length) {
      optionsRef.value = [];
    } else if (query.length > 3) {
      getUserSelect(query);
    }
  }
  function productSearch(query) {
    if (!query.length) {
      return;
    }
    getProductSelect(query);
  }

  function okModal() {
    formRef.value?.validate((errors) => {
      if (!errors) {
        if (formData.id) {
          exchangeEdit(formData, formData.id).then(function () {
            closeModal();
            setSubLoading(false);
            emit('reloadTable');
          });
        } else {
          exchangeAdd(formData).then(function () {
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
    disabledRef.value = false;
    optionsRef.value = [];
    optionsTwoRef.value = [];
  }

  function showAfterModal() {
    console.log(formData);
    if (formData.id) {
      getUserSelect(formData.member.username);
      getProductSelect(formData.product.title);
    }
  }

  function getUserSelect(query = '') {
    loadingRef.value = true;
    members({ username: query, operate: 'select', pageSize: 20 }).then(function (res) {
      optionsRef.value = res.list;
      loadingRef.value = false;
    });
  }

  function getProductSelect(query = '') {
    loadingTwoRef.value = false;
    productListApi({ combination: query, operate: 'select', pageSize: 20 }).then(function (res) {
      optionsTwoRef.value = res;
      loadingTwoRef.value = false;
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

  function renderProLabel(row) {
    if (formData.id) {
      return row.title;
    }
    return h('div', [
      h(
        NTag,
        {
          style: {
            marginRight: '6px',
          },
          type: 'info',
        },
        { default: () => row.title }
      ),
      h(
        NTag,
        {
          style: {
            marginRight: '6px',
          },
          type: 'success',
        },
        {
          default: () => {
            return '积分:' + row.buyfen;
          },
        }
      ),
      h(
        NTag,
        {
          type: 'success',
        },
        {
          default: () => {
            return '剩余:' + row.pmaxnum;
          },
        }
      ),
    ]);
  }

  defineExpose({
    openModal,
    modalName,
    formData,
    disabledRef,
  });
</script>

<style scoped>
  .add_form {
    margin-right: 20px;
    margin-left: 20px;
  }
</style>
