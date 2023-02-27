import { h } from 'vue';
import { NTag } from 'naive-ui';

export const columns = [
  {
    title: 'id',
    key: 'id',
    width: 80,
    align: 'center',
  },
  {
    title: '账户名',
    key: 'name',
    width: 130,
    align: 'center',
  },
  {
    title: '姓名',
    key: 'real_name',
    width: 100,
    align: 'center',
  },
  {
    title: '电话',
    key: 'phone',
    width: 130,
    align: 'center',
  },
  {
    title: '角色',
    key: 'roles',
    render(row) {
      const tags = row.roles.map((data) => {
        return h(
          NTag,
          {
            style: {
              marginRight: '6px',
            },
            type: 'info',
          },
          {
            default: () => data.name,
          }
        );
      });
      return tags;
    },
  },
  {
    title: '新增时间',
    key: 'created_at',
    align: 'center',
    width: 180,
  },
];
