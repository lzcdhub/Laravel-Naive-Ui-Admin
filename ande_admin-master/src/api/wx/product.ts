import { http } from '@/utils/http/axios';

/**
 * 获取分类列表树
 * @param params
 */
export function categoryTree(params) {
  return http.request({
    url: '/wx/category',
    method: 'get',
    params,
  });
}

/**
 * 删除分类
 * @param id
 */
export function deleteCategory(id) {
  return http.request(
    {
      url: '/wx/category/' + id,
      method: 'DELETE',
    },
    {
      directOutput: true,
    }
  );
}

/**
 * 获取添加商品下拉分类树数据
 * @param params
 */
export function addCategoryTree(params) {
  return http.request({
    url: '/wx/category',
    method: 'get',
    params,
  });
}

/**
 * 添加商品分类
 * @param params
 */
export function addCategoryApi(params) {
  return http.request(
    {
      url: '/wx/category',
      method: 'post',
      params,
    },
    {
      directOutput: true,
    }
  );
}

/**
 * 修改分类
 * @param params
 * @param id
 */
export function editCategoryApi(params, id) {
  return http.request(
    {
      url: '/wx/category/' + id,
      method: 'PUT',
      params,
    },
    {
      directOutput: true,
    }
  );
}

/**
 * 修改分类 （上下移动）
 * @param params
 * @param id
 */
export function tabbingOrderApi(params, id) {
  return http.request({
    url: '/wx/category/' + id,
    method: 'PUT',
    params,
  });
}

/**
 * 获取商品列表数据
 * @param params
 */
export function productListApi(params) {
  return http.request({
    url: '/wx/product',
    method: 'get',
    params,
  });
}

/**
 * 添加商品
 * @param params
 */
export function productAddApi(params) {
  return http.request(
    {
      url: '/wx/product',
      method: 'POST',
      params,
    },
    {
      directOutput: true,
    }
  );
}

/**
 * 获取商品详情
 * @param id
 */
export function productDetailApi(id) {
  return http.request({
    url: '/wx/product/' + id,
    method: 'get',
  });
}

/**
 * 修改商品
 * @param params
 * @param id
 */
export function productEditApi(params, id) {
  return http.request(
    {
      url: '/wx/product/' + id,
      method: 'PUT',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function productDelApi(id) {
  return http.request(
    {
      url: '/wx/product/' + id,
      method: 'DELETE',
    },
    {
      directOutput: true,
    }
  );
}

export function productState(id, params) {
  return http.request(
    {
      url: '/wx/pro_state/' + id,
      method: 'get',
      params,
    },
    {
      directOutput: true,
    }
  );
}

export function cartesianApi(params) {
  return http.request({
    url: '/wx/pro_cartesian',
    method: 'post',
    params,
  });
}
