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
            <n-form-item label="项目分类" path="category_id">
              <n-tree-select
                filterable
                v-model:value="formData.category_id"
                :options="selectParentData"
                :loding="loadingRef"
                clearable
                key-field="id"
                label-field="name"
              />
            </n-form-item>
            <n-form-item label="标题" path="title">
              <n-input v-model:value="formData.title" />
            </n-form-item>
            <n-form-item label="地区" path="project_area">
              <n-cascader
                v-model:value="formData.project_area_text"
                :options="areaOptions"
                :check-strategy="checkStrategyIsChild ? 'child' : 'all'"
                remote
                :on-load="handleLoad"
                :on-update:value="handleUpdate"
                value-field="id"
                label-field="name"
              />
            </n-form-item>
            <n-form-item label="评级" path="project_rating">
              <n-input v-model:value="formData.project_rating" />
            </n-form-item>
            <n-form-item label="投向" path="money_invest">
              <n-radio-group
                name="radiogroup"
                v-model:value="formData.money_invest"
                default-value="1"
              >
                <n-space>
                  <n-radio v-for="song in projectRatingArr" :key="song.value" :value="song.value">
                    {{ song.label }}
                  </n-radio>
                </n-space>
              </n-radio-group>
            </n-form-item>
            <n-form-item label="期限" path="time_limit">
              <n-radio-group
                name="time_limit"
                v-model:value="formData.time_limit"
                default-value="1"
              >
                <n-space>
                  <n-radio v-for="song in projectimeLimitArr" :key="song.value" :value="song.value">
                    {{ song.label }}
                  </n-radio>
                </n-space>
              </n-radio-group>
            </n-form-item>
            <n-form-item label="规模" path="offering_size">
              <n-input v-model:value="formData.offering_size" />
            </n-form-item>
            <n-form-item label="起息时间" path="interest_start_date">
              <n-date-picker
                v-model:formatted-value="formData.interest_start_date"
                type="date"
                value-format="yyyy-MM-dd"
              />
            </n-form-item>
            <n-form-item label="付息方式" path="interest_paymet">
              <n-radio-group
                name="interest_paymet"
                v-model:value="formData.interest_paymet"
                default-value="1"
              >
                <n-space>
                  <n-radio v-for="song in interestPaymetArr" :key="song.value" :value="song.value">
                    {{ song.label }}
                  </n-radio>
                </n-space>
              </n-radio-group>
            </n-form-item>
            <n-form-item label="付息时间" path="interest_pay_date">
              <n-date-picker
                v-model:formatted-value="formData.interest_pay_date"
                type="date"
                value-format="yyyy-MM-dd"
              />
            </n-form-item>
            <n-form-item label="资金用途" path="use_funds">
              <n-input
                v-model:value="formData.use_funds"
                type="textarea"
                :autosize="{
                  minRows: 3,
                  maxRows: 5,
                }"
              />
            </n-form-item>
            <n-form-item label="城投公司Ⅰ介绍" path="publisher_introduce">
              <n-input
                v-model:value="formData.publisher_introduce"
                type="textarea"
                :autosize="{
                  minRows: 3,
                  maxRows: 5,
                }"
              />
            </n-form-item>
            <n-form-item label="城投公司Ⅱ介绍" path="guarantee_introduce">
              <n-input
                v-model:value="formData.guarantee_introduce"
                type="textarea"
                :autosize="{
                  minRows: 3,
                  maxRows: 5,
                }"
              />
            </n-form-item>
            <n-form-item label="地区介绍" path="area_introduce">
              <n-input
                v-model:value="formData.area_introduce"
                type="textarea"
                :autosize="{
                  minRows: 3,
                  maxRows: 5,
                }"
              />
            </n-form-item>
            <n-form-item label="状态" path="status_f">
              <n-radio-group v-model:value="formData.status_f" default-value="1">
                <n-radio-button value="1"> 上架</n-radio-button>
                <n-radio-button value="2"> 下架</n-radio-button>
              </n-radio-group>
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
  import { projectEdit, projectAdd, projectDetail } from '@/api/news/project';
  import { newsCategorySelect } from '@/api/news/category';
  import { areaApi } from '@/api/system/admin';

  const message = useMessage();

  const modalName = ref('');
  const [modalRegister, { openModal, closeModal, setSubLoading }] = useModal({ title: modalName });
  const emit = defineEmits(['reloadTable', 'register']);

  const formRef = ref();
  const formData = reactive({});
  const rules = ref([]);
  const selectParentData = ref([]);
  const loadingRef = ref(false);
  const value = ref();
  const areaOptions = ref([]);
  const checkStrategyIsChild = ref(false);
  const projectRatingArr = ref([
    { value: '1', label: '基础设施' },
    { value: '2', label: '房地产' },
    { value: '3', label: '工商企业' },
    { value: '4', label: '资金池' },
    { value: '5', label: '其它' },
  ]);
  const projectimeLimitArr = ref([
    { value: '1', label: '12个月以内' },
    { value: '2', label: '12个月' },
    { value: '3', label: '13-23个月' },
    { value: '4', label: '24个月' },
    { value: '5', label: '24个月以上' },
  ]);
  const interestPaymetArr = ref([
    { value: '1', label: '按月付息' },
    { value: '2', label: '按季付息' },
    { value: '3', label: '半年付息' },
    { value: '4', label: '按年付息' },
    { value: '5', label: '到期付息' },
  ]);

  async function okModal() {
    formRef.value?.validate((errors) => {
      if (!errors) {
        if (formData.id) {
          projectEdit(formData, formData.id).then(function () {
            closeModal();
            setSubLoading(false);
            emit('reloadTable');
          });
        } else {
          projectAdd(formData).then(function () {
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
    selectCategoryData();
    areaApi().then(function (res) {
      areaOptions.value = res;
    });
  }

  function handleLoad(option) {
    return new Promise((resolve) => {
      areaApi({ pid: option.id }).then(function (res) {
        option.children = res;
        resolve();
      });
    });
  }

  function handleUpdate(value, option, pathValues) {
    formData.project_area = value;
    let name = '';
    pathValues.forEach(function (v, i) {
      if (i === 0) {
        name += v.name;
      } else {
        name += ' / ' + v.name;
      }
    });
    formData.project_area_text = name;
  }

  function handleChange(e) {
    formData.money_invest = e.target.value;
  }

  function getDetail(id) {
    projectDetail(id).then(function (res) {
      //interest_pay_date interest_start_date
      if (!res.interest_pay_date) {
        res.interest_pay_date = null;
      }
      if (!res.interest_start_date) {
        res.interest_start_date = null;
      }
      Object.assign(formData, res);
      if (formData.newstext) setHtmlVal(formData.newstext);
    });
  }

  function selectCategoryData() {
    loadingRef.value = true;
    newsCategorySelect({ pid: 2 }).then(function (res) {
      function aaa(data) {
        data.forEach((v, i) => {
          if (v.children) {
            if (v.children.length < 1) {
              v.children = undefined;
            } else {
              aaa(v.children);
            }
          }
        });
        return data;
      }

      selectParentData.value = aaa(res.list);
      loadingRef.value = false;
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
