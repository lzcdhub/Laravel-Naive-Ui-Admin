import { http } from '@/utils/http/axios';

export function medias(params) {
  return http.request({
    url: '/media_file',
    method: 'GET',
    params,
  });
}

export function mediaDel(id) {
  return http.request(
    {
      url: '/media_file/' + id,
      method: 'DELETE',
    },
    {
      directOutput: true,
    }
  );
}
