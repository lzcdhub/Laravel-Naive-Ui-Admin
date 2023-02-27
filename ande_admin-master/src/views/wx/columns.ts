import { h } from 'vue';
import { NTag } from 'naive-ui';

export const exchangeColumns = [
  {
    title: 'id',
    key: 'id',
    width: 80,
    align: 'center',
  },
  {
    title: '会员用户名',
    key: 'member.username',
    width: 130,
    align: 'center',
  },
  {
    title: '产品名称',
    key: 'product.title',
    resizable: true,
  },
  {
    title: '积分',
    key: 'buyfen',
    width: 100,
    align: 'center',
  },
  {
    title: '数量',
    key: 'num',
    width: 100,
    align: 'center',
  },
  {
    title: '新增时间',
    key: 'created_at',
    width: 180,
    align: 'center',
  },
  {
    title: '备注',
    key: 'remark',
    resizable: true,
  },
  {
    title: '领取状态',
    key: 'is_finish',
    render(row) {
      return h(
        NTag,
        {
          type: row.is_finish == 2 ? 'success' : 'error',
        },
        {
          default: () => (row.is_finish == 2 ? '已兑换' : '未兑换'),
        }
      );
    },
    width: 110,
    align: 'center',
  },
  {
    title: '领取日期',
    key: 'finish_time',
    width: 180,
    align: 'center',
  },
];

export const integralColumns = [
  {
    title: 'id',
    key: 'id',
    width: 80,
    align: 'center',
  },
  {
    title: '会员用户名',
    key: 'member.username',
    width: 130,
    align: 'center',
  },
  {
    title: '积分（原）',
    key: 'old_integral',
    width: 120,
    align: 'center',
    ifShow: true,
  },
  {
    title: '类型',
    key: 'integral_type',
    render(row) {
      return h(
        NTag,
        {
          type: row.integral_type == 1 ? 'success' : 'error',
        },
        {
          default: () => (row.integral_type == 1 ? '增加' : '减少'),
        }
      );
    },
    width: 80,
    align: 'center',
  },
  {
    title: '积分',
    key: 'integral',
    width: 120,
    align: 'center',
  },
  {
    title: '积分（结）',
    key: 'new_integral',
    width: 120,
    align: 'center',
  },
  {
    title: '备注',
    key: 'remark',
  },
  {
    title: '新增时间',
    key: 'created_at',
    width: 180,
    align: 'center',
  },
];
