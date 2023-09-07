// 侧边导航栏数据
// @ts-ignore
import { reactive } from "vue";

export const asideIcon = reactive<Array<leftobj>>([
  {
    title: '权限',
    icon: 'Key',
    index: '2',
    // @ts-ignore
    itemList: <Array<item>>[
      {
        title: '用户列表',
        icon: 'DocumentCopy',
        index: '2-1',
        path:'UserTable'
      },
      {
        title: '资源列表',
        icon: 'Suitcase',
        index: '2-2',
        path:'ResourceTable'
      },
      {
        title: '菜单列表',
        icon: 'Menu',
        index: '2-3',
        path:'MenuTable'
      },
      {
        title: '角色列表',
        icon: 'Avatar',
        index: '2-4',
        path:'RoleTable'
      }
    ]
  },
  {
    title: '商品',
    icon: 'Goods',
    index: '3',
    itemList: [
      {
        title: '添加商品',
        icon: 'CirclePlus',
        index: '3-1'
      },
      {
        title: '商品分类',
        icon: 'Menu',
        index: '3-2'
      },
      {
        title: '品牌管理',
        icon: 'PriceTag',
        index: '3-3'
      },
      {
        title: '属性管理',
        icon: 'Memo',
        index: '3-4'
      },
      {
        title: '商品列表',
        icon: 'List',
        index: '3-5'
      }
    ]
  },
  {
    title: '订单',
    icon: 'List',
    index: '4',
    itemList: [
      {
        title: '订单列表',
        icon: 'Menu',
        index: '4-1',
        path: 'OrderList'
      },
      {
        title: '订单设置',
        icon: 'Tools',
        index: '4-2'
      },
      {
        title: '退货申请处理',
        icon: 'RefreshLeft',
        index: '4-3',
        path: 'saleList'
      },
      {
        title: '退货原因处理',
        icon: 'Van',
        index: '4-4'
      }
    ]
  },
  {
    title: '营销',
    icon: 'TrendCharts',
    index: '5',
    itemList: [
      {
        title: '秒杀活动列表',
        icon: 'Bell',
        index: '5-1',
        path: 'Bell'
      },
      {
        title: '优惠券列表',
        icon: 'Discount',
        index: '5-2',
        path: 'Discount'
      },
      {
        title: '品牌推荐',
        icon: 'Apple',
        index: '5-3',
        path: 'Recommend'
      },
      {
        title: '新品推荐',
        icon: 'Document',
        index: '5-4',
        path: 'Recommendedgoods'
      },
      {
        title: '人气推荐',
        icon: 'Histogram',
        index: '5-5'
      },
      {
        title: '专题推荐',
        icon: 'Pointer',
        index: '5-6'
      },
      {
        title: '广告列表',
        icon: 'Menu',
        index: '5-7',
        path: 'AdvertisingList'
      }
    ]
  }
])