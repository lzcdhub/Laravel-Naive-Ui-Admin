import { http } from "@/utils/http/axios";

export function newsCategoryTree(params) {
  return http.request({
    url: '/news/category',
    method: 'get',
    params,
  });
}

export function newsCategoryDel(id) {
  return http.request(
    {
      url: '/news/category/' + id,
      method: 'DELETE',
    },
    {
      directOutput: true,
    }
  );
}

export function newsCategoryAdd(params) {
  return http.request(
    {
      url: '/news/category',
      method: 'post',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function newsCategoryEdit(params, id) {
  return http.request(
    {
      url: '/news/category/' + id,
      method: 'PUT',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function newsCategoryMove(params, id) {
  return http.request({
    url: '/news/category/' + id,
    method: 'PUT',
    params,
  });
}

export function newsCategorySelect(params) {
  return http.request({
    url: '/news/category_select',
    method: 'get',
    params,
  });
}
