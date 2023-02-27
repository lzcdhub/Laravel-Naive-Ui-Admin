<template>
  <div>
    <n-card :bordered="false" title="工作台">
      <n-grid cols="2 s:1 m:1 l:2 xl:2 2xl:2" responsive="screen">
        <n-gi>
          <div class="flex items-center">
            <div>
              <n-avatar circle :size="64" :src="schoolboy" />
            </div>
            <div>
              <p class="px-4 text-xl">{{ getTimeState() }} {{ username }}，开始您一天的工作吧！</p>
              <p class="px-4 text-gray-400">{{ weather_str }}</p>
            </div>
          </div>
        </n-gi>
        <n-gi>
          <div class="flex justify-end w-full">
            <div class="flex flex-col justify-center text-center notice-num-item">
              <span class="text-secondary">项目数</span>
              <span class="text-2xl">16</span>
            </div>
            <div class="flex flex-col justify-center text-center notice-num-item">
              <span class="text-secondary">待办</span>
              <span class="text-2xl">3/15</span>
            </div>
            <div class="flex flex-col justify-center text-center notice-num-item">
              <span class="text-secondary">消息</span>
              <span class="text-2xl">35</span>
            </div>
          </div>
        </n-gi>
      </n-grid>
    </n-card>
    <n-card
      :segmented="{ content: true }"
      content-style="padding: 0;"
      :bordered="false"
      size="small"
      title="快捷操作"
      class="mt-4"
    >
      <div class="flex flex-wrap project-card">
        <n-card size="small" class="cursor-pointer project-card-item" hoverable>
          <div class="flex flex-col justify-center text-gray-500">
            <span class="text-center">
              <n-icon size="30" color="#68c755">
                <DashboardOutlined />
              </n-icon>
            </span>
            <span class="text-lx text-center">主控台</span>
          </div>
        </n-card>
        <n-card size="small" class="cursor-pointer project-card-item" hoverable>
          <div class="flex flex-col justify-center text-gray-500">
            <span class="text-center">
              <n-icon size="30" color="#fab251">
                <ProfileOutlined />
              </n-icon>
            </span>
            <span class="text-lx text-center">列表</span>
          </div>
        </n-card>
        <n-card size="small" class="cursor-pointer project-card-item" hoverable>
          <div class="flex flex-col justify-center text-gray-500">
            <span class="text-center">
              <n-icon size="30" color="#1890ff">
                <FileProtectOutlined />
              </n-icon>
            </span>
            <span class="text-lx text-center">表单</span>
          </div>
        </n-card>
        <n-card size="small" class="cursor-pointer project-card-item" hoverable>
          <div class="flex flex-col justify-center text-gray-500">
            <span class="text-center">
              <n-icon size="30" color="#f06b96">
                <ApartmentOutlined />
              </n-icon>
            </span>
            <span class="text-lx text-center">权限管理</span>
          </div>
        </n-card>
        <n-card size="small" class="cursor-pointer project-card-item" hoverable>
          <div class="flex flex-col justify-center text-gray-500">
            <span class="text-center">
              <n-icon size="30" color="#7238d1">
                <SettingOutlined />
              </n-icon>
            </span>
            <span class="text-lx text-center">系统管理</span>
          </div>
        </n-card>
      </div>
    </n-card>
    <n-grid class="mt-4" cols="2 s:1 m:1 l:2 xl:2 2xl:2" responsive="screen" :x-gap="12" :y-gap="9">
      <n-gi>
        <n-card
          :segmented="{ content: true }"
          content-style="padding-top: 0;padding-bottom: 0;"
          :bordered="false"
          size="small"
          title="动态"
        >
          <template #header-extra><a href="javascript:;">更多</a></template>
          <n-list>
            <n-list-item>
              <template #prefix>
                <n-avatar circle :size="40" :src="schoolboy" />
              </template>
              <n-thing title="Ah Jung 刚才把工作台页面随便写了一些，凑合能看了！">
                <template #description
                  ><p class="text-xs text-gray-500">2021-07-04 22:37:16</p></template
                >
              </n-thing>
            </n-list-item>
            <n-list-item>
              <template #prefix>
                <n-avatar circle :size="40" :src="schoolboy" />
              </template>
              <n-thing title="Ah Jung 在 开源组 创建了项目 naive-ui-admin？">
                <template #description
                  ><p class="text-xs text-gray-500">2021-07-04 09:37:16</p></template
                >
              </n-thing>
            </n-list-item>
            <n-list-item>
              <template #prefix>
                <n-avatar circle :size="40" :src="schoolboy" />
              </template>
              <n-thing title="@It界风清扬，向naive-ui-admin提交了一个bug，抽时间看看吧！">
                <template #description
                  ><p class="text-xs text-gray-500">2021-07-04 22:37:16</p></template
                >
              </n-thing>
            </n-list-item>
            <n-list-item>
              <template #prefix>
                <n-avatar circle :size="40" :src="schoolboy" />
              </template>
              <n-thing title="技术部那几位童鞋，再次警告，不要摸鱼，不要摸鱼，不要摸鱼啦！">
                <template #description
                  ><p class="text-xs text-gray-500">2021-07-04 09:37:16</p></template
                >
              </n-thing>
            </n-list-item>
            <n-list-item>
              <template #prefix>
                <n-avatar circle :size="40" :src="schoolboy" />
              </template>
              <n-thing title="上班不摸鱼，和咸鱼有什么区别（这话真不是我说的哈）！">
                <template #description
                  ><p class="text-xs text-gray-500">2021-07-04 20:37:16</p></template
                >
              </n-thing>
            </n-list-item>
            <n-list-item>
              <template #prefix>
                <n-avatar circle :size="40" :src="schoolboy" />
              </template>
              <n-thing title="页面切换其实也支持缓存，只是加了过度效果，看起来像是重新渲染了">
                <template #description>
                  <p class="text-gray-400">
                    <n-input type="text" placeholder="不信，输点文字试试" />
                  </p>
                </template>
              </n-thing>
            </n-list-item>
          </n-list>
        </n-card>
      </n-gi>
      <n-gi>
        <n-card :segmented="{ content: true }" :bordered="false" size="small">
          <img src="~@/assets/images/Business.svg" class="w-full" />
        </n-card>
      </n-gi>
    </n-grid>
  </div>
</template>

<script lang="ts" setup>
  import { ref } from 'vue-demi';
  import { closeWebSocket, sendWebSocket } from '@/utils/websocket';
  import schoolboy from '@/assets/images/schoolboy.png';
  import {
    GithubOutlined,
    DashboardOutlined,
    ProfileOutlined,
    FileProtectOutlined,
    SettingOutlined,
    ApartmentOutlined,
    Html5Outlined,
  } from '@vicons/antd';
  import { LogoVue, LogoAngular, LogoReact, LogoJavascript } from '@vicons/ionicons5';
  import { getWeather } from '@/api/dashboard/console';
  import { onMounted } from 'vue';
  import { useUserStore } from '@/store/modules/user';
  import { storage } from '@/utils/Storage';
  import { ACCESS_TOKEN } from '@/store/mutation-types';

  const weather_str = ref('');
  const username = ref('');

  onMounted(() => {
    weather();
    let userInfo = useUserStore().getUserInfo;
    let token = storage.get(ACCESS_TOKEN);

    if (userInfo.real_name) {
      username.value = userInfo.real_name;
    } else {
      username.value = userInfo.name;
    }

    //sendWebSocket(JSON.stringify({ type: 'login', token: token }));
  });

  function getTimeState() {
    // 获取当前时间
    let timeNow = new Date();
    // 获取当前小时
    let hours = timeNow.getHours();
    // 设置默认文字
    let state = ``;
    // 判断当前时间段
    if (hours >= 0 && hours <= 10) {
      state = `早上好！`;
    } else if (hours > 10 && hours <= 13) {
      state = `中午好！`;
    } else if (hours > 13 && hours <= 18) {
      state = `下午好！`;
    } else if (hours > 18 && hours <= 24) {
      state = `晚上好！`;
    }
    return state;
  }

  function weather() {
    getWeather().then(function (res) {
      weather_str.value = res;
    });
  }
</script>

<style lang="less" scoped>
  .project-card {
    margin-right: -9px;

    &-item {
      margin: -1px;
      width: 20%;
    }
  }
  .notice-num-item {
    width: 150px;
  }
</style>
