<template>
  <n-card :bordered="false" class="mt-2 proCard">
    <n-tabs
      class="card-tabs"
      default-value="signin"
      size="large"
      animated
      style="margin: 0 -4px"
      pane-style="padding-left: 4px; padding-right: 4px; box-sizing: border-box;"
    >
      <n-tab-pane name="signin" tab="登录">
        <n-form :model="specModel" label-placement="left">
          <n-grid x-gap="12" :cols="2">
            <n-gi v-for="(item, index) in specData">
              <n-card
                class="spec_item"
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
          <div class="spec_add">
            <n-button class="mr-2" type="info" @click="specAdd"> 添加属性</n-button>
            <n-button type="success" @click="specUpdate"> 保存属性</n-button>
          </div>
        </n-form>
        <n-data-table
          :single-line="false"
          :columns="specColumns"
          :data="spec_param"
          :pagination="false"
        />
      </n-tab-pane>
      <n-tab-pane name="signup" tab="注册"> 456 </n-tab-pane>
    </n-tabs>
  </n-card>
</template>

<script lang="ts" setup>
  import { defineComponent, ref, h, reactive } from 'vue';
  import { NInput } from 'naive-ui';
  import { cartesianApi } from '@/api/wx/product';

  const specModel = ref({});

  const specData = ref([
    { name: '颜色', tags: ['黑', '白'] },
    { name: '材质', tags: ['皮', '布'] },
    { name: '规格', tags: ['大', '中'] },
  ]);

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
            spec_param.value[index].price = v;
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
            spec_param.value[index].inventory = v;
          },
        });
      },
    },
  ]);

  const spec_param = ref([]);

  const specAdd = () => {
    specData.value.push({ name: '', tags: [] });
  };

  const handleSpecClose = (index) => {
    specData.value.splice(index, 1);
  };

  const specUpdate = () => {
    createColumns();
    cartesianApi({ specData: specData.value }).then(function (res) {
      spec_param.value = res;
    });
  };

  function tagsUpdate(val) {}

  function createColumns() {
    let res = [];
    let index = 0;
    specData.value.forEach(function (item, index) {
      if (item.tags.length) {
        let index_name = 'spec_' + index;
        let child = {
          title: item.name,
          key: index_name,
          align: 'center',
        };
        res[index] = child;
        index++;
      }
    });
    specColumns.value = [...res, ...specColumns.value.splice(-2, 2)];
  }
</script>
<style scoped>
  .spec_item {
    margin-bottom: 10px;
  }

  .spec_add {
    margin-bottom: 10px;
  }
</style>
