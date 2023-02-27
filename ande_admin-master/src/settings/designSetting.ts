// app theme preset color
//import { storage } from "@/utils/Storage";

export const appThemeList: string[] = [
  '#2d8cf0',
  '#0960bd',
  '#0084f4',
  '#009688',
  '#536dfe',
  '#ff5c93',
  '#ee4f12',
  '#0096c7',
  '#9c27b0',
  '#ff9800',
  '#FF3D68',
  '#00C1D4',
  '#71EFA3',
  '#171010',
  '#78DEC7',
  '#1768AC',
  '#FB9300',
  '#FC5404',
];

const setting = {
  //深色主题
  darkTheme: false,
  //系统主题色
  appTheme: '#2d8cf0',
  //系统内置主题色列表
  appThemeList,
};

// const userDesignStore = storage.get('userDesignStore', '');
// if (userDesignStore) {
//   for (let index in setting) {
//     setting[index] = userDesignStore[index];
//   }
// }

export default setting;
