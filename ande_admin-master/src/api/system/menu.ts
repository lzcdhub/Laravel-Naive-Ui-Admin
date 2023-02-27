import { http } from '@/utils/http/axios';

/**
 * @description: 根据用户id获取用户菜单
 */
export function adminMenus() {
  return http.request({
    url: '/menus',
    method: 'GET',
  });
}

/**
 * 获取tree菜单列表
 * @param params
 */
export function getPermissionList(params) {
  return http.request({
    url: '/permission_tree',
    method: 'GET',
    params,
  });
}

/**
 * 添加菜单&权限
 * @param params
 */
export function addPermission(params) {
  return http.request({
    url: '/add_permission',
    method: 'POST',
    params,
  });
}

/**
 * 修改权限信息
 * @param params
 */
export function editPermission(params) {
  return http.request(
    {
      url: '/edit_permission',
      method: 'POST',
      params,
    },
    {
      directOutput: true,
    }
  );
}
