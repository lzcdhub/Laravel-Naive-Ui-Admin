import { http } from '@/utils/http/axios';

export function projects(params) {
  return http.request({
    url: '/news/project',
    method: 'GET',
    params,
  });
}

export function projectAdd(params) {
  return http.request(
    {
      url: '/news/project',
      method: 'post',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function projectDel(id) {
  return http.request(
    {
      url: '/news/project/' + id,
      method: 'DELETE',
    },
    {
      directOutput: true,
    }
  );
}

export function projectEdit(params, id) {
  return http.request(
    {
      url: '/news/project/' + id,
      method: 'PUT',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function projectDetail(id) {
  return http.request({
    url: '/news/project/' + id,
    method: 'get',
  });
}

export function projectState(id, params) {
  return http.request(
    {
      url: '/news/project_state/' + id,
      method: 'get',
      params,
    },
    {
      directOutput: true,
    }
  );
}

