import { http } from '@/utils/http/axios';

export function news(params) {
  return http.request({
    url: '/news/info',
    method: 'GET',
    params,
  });
}

export function newsAdd(params) {
  return http.request(
    {
      url: '/news/info',
      method: 'post',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function newsDel(id) {
  return http.request(
    {
      url: '/news/info/' + id,
      method: 'DELETE',
    },
    {
      directOutput: true,
    }
  );
}

export function newsEdit(params, id) {
  return http.request(
    {
      url: '/news/info/' + id,
      method: 'PUT',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function newsDetail(id) {
  return http.request({
    url: '/news/info/' + id,
    method: 'get',
  });
}

export function newsState(id, params) {
  return http.request(
    {
      url: '/news/state/' + id,
      method: 'get',
      params,
    },
    {
      directOutput: true,
    }
  );
}

