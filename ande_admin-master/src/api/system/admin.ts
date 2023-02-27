import { http } from '@/utils/http/axios';

/**
 * @description: 根据用户id获取用户菜单
 */
export function admins(params) {
  return http.request({
    url: '/admin',
    method: 'GET',
    params,
  });
}

/**
 *
 */
export function deleteAdmin(id) {
  return http.request(
    {
      url: '/admin/' + id,
      method: 'DELETE',
    },
    {
      directOutput: true,
    }
  );
}

export function editAdmin(params, id) {
  return http.request(
    {
      url: '/admin/' + id,
      method: 'PUT',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function addAdmin(params) {
  return http.request(
    {
      url: '/admin',
      method: 'POST',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function areaApi(params) {
  return http.request({
    url: '/area/area_list',
    method: 'POST',
    params,
  });
}
