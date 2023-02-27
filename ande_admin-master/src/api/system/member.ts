import { http } from '@/utils/http/axios';

export function members(params) {
  return http.request({
    url: '/member',
    method: 'GET',
    params,
  });
}

export function memberAdd(params) {
  return http.request(
    {
      url: '/member',
      method: 'post',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function memberDel(id) {
  return http.request(
    {
      url: '/member/' + id,
      method: 'DELETE',
    },
    {
      directOutput: true,
    }
  );
}

export function memberEdit(params, id) {
  return http.request(
    {
      url: '/member/' + id,
      method: 'PUT',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function memberDetail(id) {
  return http.request({
    url: '/member/' + id,
    method: 'get',
  });
}

