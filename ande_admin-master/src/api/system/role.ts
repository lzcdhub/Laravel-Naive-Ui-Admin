import { http } from '@/utils/http/axios';

/**
 * @description: 角色列表
 */
export function getRoleList(params) {
  return http.request({
    url: '/role',
    method: 'GET',
    params,
  });
}

/**
 * 角色权限分配
 * @param params
 */
export function assignPermission(params) {
  return http.request({
    url: '/assign_permission',
    method: 'post',
    params,
  });
}

export function roleEdit(params, id) {
  return http.request({
    url: '/role/' + id,
    method: 'put',
    params,
  });
}

export function roleDel(id) {
  return http.request({
    url: '/role/' + id,
    method: 'delete',
  });
}

export function roleCreate(params) {
  return http.request({
    url: '/role/',
    method: 'post',
    params,
  });
}
