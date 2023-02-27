<template>
  <div>
    <basicModal
      ref="modalRef"
      style="width: 1100px"
      class="basicModal"
      @register="modalRegister"
      @on-ok="okModal"
      :on-after-enter="showAfterModal"
    >
      <template #default>
        <n-scrollbar style="height: 55vh">
          <div class="mb-2">
            <div class="mb-1">规格参数列表</div>
            <n-data-table
              :single-line="false"
              :columns="specColumns"
              :data="specParam"
              :pagination="false"
            />
          </div>
          <div class="mb-1">设置规格参数</div>
          <n-form label-placement="left">
            <n-grid x-gap="12" :cols="2">
              <n-gi v-for="(item, index) in specTitle">
                <n-card
                  class="mb-2 spec_item"
                  content-style="padding: 20px 20px 0;"
                  closable
                  @close="handleSpecClose(index)"
                >
                  <n-form-item label="规格名" path="name">
                    <n-input v-model:value="item.name" autosize style="min-width: 200px" />
                  </n-form-item>
                  <n-form-item label="规格值" path="tags">
                    <n-dynamic-tags v-model:value="item.tags" @update-value="tagsUpdate" />
                  </n-form-item>
                </n-card>
              </n-gi>
            </n-grid>
            <div>
              <n-button class="mr-2" type="info" @click="specAdd">添加规格</n-button>
              <n-button type="success" @click="specUpdate">更新规格列表</n-button>
            </div>
          </n-form>
        </n-scrollbar>
      </template>
    </basicModal>
  </div>
</template>

<script lang="ts" setup>
  import { ref, h, defineProps, defineEmits, onMounted, defineExpose } from 'vue';
  import { NInput } from 'naive-ui';
  import { cartesianApi } from '@/api/wx/product';
  import { useModal } from '@/components/Modal';
  import { toRef } from 'vue-demi';

  const props = defineProps(['specData']);
  const emits = defineEmits(['saveSpec', 'register']);

  const [modalRegister, { openModal, closeModal, setSubLoading }] = useModal({ title: '规格设置' });

  const specTitle = ref([]);

  const specParam = ref([]);

  const specColumns = ref([
    {
      title: '积分',
      key: 'price',
      align: 'center',
      render(row, index) {
        return h(NInput, {
          type: 'number',
          inputProps: { min: 0 },
          value: row.price,
          onUpdateValue(v) {
            specParam.value[index].price = v;
          },
        });
      },
    },
    {
      title: '库存',
      key: 'inventory',
      align: 'center',
      render(row, index) {
        return h(NInput, {
          type: 'number',
          inputProps: { min: 0 },
          value: row.inventory,
          onUpdateValue(v) {
            specParam.value[index].inventory = v;
          },
        });
      },
    },
  ]);

  const specAdd = () => {
    specTitle.value.push({ name: '', tags: [] });
  };

  const handleSpecClose = (index) => {
    specTitle.value.splice(index, 1);
  };

  const specUpdate = () => {
    createColumns();
    cartesianApi({ spec_data: specTitle.value }).then(function (res) {
      specParam.value = res;
    });
  };

  function tagsUpdate(val) {}

  function createColumns() {
    let res = [];
    let index = 0;
    specTitle.value.forEach(function (item, index) {
      if (item.tags.length) {
        let index_name = 'spec_' + index;
        let child = {
          title: item.name,
          key: index_name,
          align: 'center',
          render(row, index) {
            return h(NInput, {
              type: 'text',
              value: row[index_name],
              onUpdateValue(v) {
                specParam.value[index][index_name] = v;
              },
            });
          },
        };
        res[index] = child;
        index++;
      }
    });
    specColumns.value = [...res, ...specColumns.value.splice(-2, 2)];
  }

  function okModal() {
    emits('saveSpec', {
      spec_title: specTitle.value,
      spec_param: specParam.value,
    });
    closeModal();
    setTimeout(function () {
      setSubLoading(false);
    }, 1000);
  }

  function showAfterModal() {
    if (props.specData) {
      if (props.specData.spec_title) specTitle.value = props.specData.spec_title;
      createColumns();
      if (props.specData.spec_param) specParam.value = props.specData.spec_param;
    }
  }

  onMounted(() => {});

  defineExpose({
    openModal,
  });
</script>
<style scoped>
  .spec_item {
    margin-bottom: 10px;
  }

  .spec_add {
    margin-bottom: 10px;
  }

  :deep(.spec_item .n-card-header) {
    position: absolute;
    right: 0;
    z-index: 1;
  }
</style>
