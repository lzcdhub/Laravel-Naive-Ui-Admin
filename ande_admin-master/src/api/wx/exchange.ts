import { http } from '@/utils/http/axios';

export function exchanges(params) {
  return http.request({
    url: '/wx/exchange',
    method: 'GET',
    params,
  });
}

export function exchangeAdd(params) {
  return http.request(
    {
      url: '/wx/exchange',
      method: 'post',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function exchangeEdit(params, id) {
  return http.request(
    {
      url: '/wx/exchange/' + id,
      method: 'PUT',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function exchangeDel(id) {
  return http.request(
    {
      url: '/wx/exchange/' + id,
      method: 'DELETE',
    },
    {
      directOutput: true,
    }
  );
}

export function exchangeReceive(id) {
  return http.request(
    {
      url: '/wx/pro_receive/' + id,
      method: 'get',
    },
    {
      directOutput: true,
    }
  );
}
