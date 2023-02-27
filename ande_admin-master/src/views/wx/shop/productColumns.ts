import { h } from 'vue';
import { NImage, NSwitch } from 'naive-ui';
import { productState } from '@/api/wx/product';

export const columns = [
  {
    title: '序号',
    key: 'id',
    width: '80',
    align: 'center',
  },
  {
    title: '分类名称',
    key: 'category.name',
    width: 120,
    align: 'center',
    resizable: true,
  },
  {
    title: '礼品名称',
    key: 'title',
    minWidth: 160,
    resizable: true,
  },
  {
    title: '图片',
    key: 'pic',
    width: 80,
    render(row) {
      return h(NImage, {
        width: 50,
        src: row.pic,
      });
    },
    align: 'center',
  },
  {
    title: '价格',
    key: 'price',
    width: 80,
    align: 'center',
  },
  {
    title: '积分',
    key: 'buyfen',
    width: 80,
    align: 'center',
  },
  {
    title: '库存',
    key: 'pmaxnum',
    width: 80,
    align: 'center',
  },
  {
    title: '销售量',
    key: 'psalenum',
    width: 80,
    align: 'center',
  },
  {
    title: '新增时间',
    key: 'created_at',
    width: 160,
    align: 'center',
  },
  {
    title: '礼品编号',
    key: 'productno',
    width: 160,
    align: 'center',
    resizable: true,
  },
  {
    title: '品牌',
    key: 'pbrand',
    width: 160,
    align: 'center',
    resizable: true,
  },
  {
    title: '状态',
    key: 'status_f',
    fixed: 'right',
    width: 100,
    render(row) {
      return h(
        NSwitch,
        {
          value: row.status_f == 1 ? true : false,
          onClick: handleSwitch.bind(null, row),
          round: false,
        },
        {
          checked: () => {
            return '上架';
          },
          unchecked: () => {
            return '下架';
          },
        }
      );
    },
    align: 'center',
  },
];

function handleSwitch(row) {
  row.status_f = row.status_f == 1 ? 2 : 1;
  productState(row.id, { status_f: row.status_f });
}

// toolbar标题
export const titleConfig = [
  { Choice: '.ql-insertMetric', title: '跳转配置' },
  { Choice: '.ql-bold', title: '加粗' },
  { Choice: '.ql-italic', title: '斜体' },
  { Choice: '.ql-underline', title: '下划线' },
  { Choice: '.ql-header', title: '段落格式' },
  { Choice: '.ql-strike', title: '删除线' },
  { Choice: '.ql-blockquote', title: '块引用' },
  { Choice: '.ql-code', title: '插入代码' },
  { Choice: '.ql-code-block', title: '插入代码段' },
  { Choice: '.ql-font', title: '字体' },
  { Choice: '.ql-size', title: '字体大小' },
  { Choice: '.ql-list[value="ordered"]', title: '编号列表' },
  { Choice: '.ql-list[value="bullet"]', title: '项目列表' },
  { Choice: '.ql-direction', title: '文本方向' },
  { Choice: '.ql-header[value="1"]', title: 'h1' },
  { Choice: '.ql-header[value="2"]', title: 'h2' },
  { Choice: '.ql-align', title: '对齐方式' },
  { Choice: '.ql-color', title: '字体颜色' },
  { Choice: '.ql-background', title: '背景颜色' },
  { Choice: '.ql-image', title: '图像' },
  { Choice: '.ql-video', title: '视频' },
  { Choice: '.ql-link', title: '新增链接' },
  { Choice: '.ql-formula', title: '插入公式' },
  { Choice: '.ql-clean', title: '清除字体格式' },
  { Choice: '.ql-script[value="sub"]', title: '下标' },
  { Choice: '.ql-script[value="super"]', title: '上标' },
  { Choice: '.ql-indent[value="-1"]', title: '向左缩进' },
  { Choice: '.ql-indent[value="+1"]', title: '向右缩进' },
  { Choice: '.ql-header .ql-picker-label', title: '标题大小' },
  { Choice: '.ql-header .ql-picker-item[data-value="1"]', title: '标题一' },
  { Choice: '.ql-header .ql-picker-item[data-value="2"]', title: '标题二' },
  { Choice: '.ql-header .ql-picker-item[data-value="3"]', title: '标题三' },
  { Choice: '.ql-header .ql-picker-item[data-value="4"]', title: '标题四' },
  { Choice: '.ql-header .ql-picker-item[data-value="5"]', title: '标题五' },
  { Choice: '.ql-header .ql-picker-item[data-value="6"]', title: '标题六' },
  { Choice: '.ql-header .ql-picker-item:last-child', title: '标准' },
  { Choice: '.ql-size .ql-picker-item[data-value="small"]', title: '小号' },
  { Choice: '.ql-size .ql-picker-item[data-value="large"]', title: '大号' },
  { Choice: '.ql-size .ql-picker-item[data-value="huge"]', title: '超大号' },
  { Choice: '.ql-size .ql-picker-item:nth-child(2)', title: '标准' },
  { Choice: '.ql-align .ql-picker-item:first-child', title: '居左对齐' },
  { Choice: '.ql-align .ql-picker-item[data-value="center"]', title: '居中对齐' },
  { Choice: '.ql-align .ql-picker-item[data-value="right"]', title: '居右对齐' },
  { Choice: '.ql-align .ql-picker-item[data-value="justify"]', title: '两端对齐' },
];
