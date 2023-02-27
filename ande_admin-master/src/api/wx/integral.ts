import { http } from '@/utils/http/axios';

export function integrals(params) {
  return http.request({
    url: '/wx/integral',
    method: 'GET',
    params,
  });
}

export function integralsAdd(params) {
  return http.request(
    {
      url: '/wx/integral',
      method: 'post',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function integralsEdit(params, id) {
  return http.request(
    {
      url: '/wx/integral/' + id,
      method: 'PUT',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function integralsDel(id) {
  return http.request(
    {
      url: '/wx/integral/' + id,
      method: 'DELETE',
    },
    {
      directOutput: true,
    }
  );
}
