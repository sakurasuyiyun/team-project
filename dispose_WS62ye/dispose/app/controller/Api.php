<?php

namespace app\controller;

use think\Request;
use think\facade\Db;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use think\cache\driver\Redis as DriverRedis;
use think\facade\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

/* 
    权限设置
        -777：超级管理员权限
        -1：仅可访问权限
        -2：仅可访问商品
        -3：仅可访问订单
        -4：仅可访问营销
*/


class Api
{
    public function __construct(Request $request)
    {
        // 跨域请求
        header("Access-Control-Allow-Origin:*");
    }

    // 鉴权
    private function checkAuthor(Request $request)
    {
        $token = $request->param("token");
        // 判断cookie中的token是否为空,不为空才往下执行
        if (!empty($token)) {

            // 获取密钥
            $secretKey = config('jwt.secret_key');
            // echo json_encode(['t' => $secretKey]);
            // exit;
            try {
                // 解码 Token
                $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

                // 验证成功，可以根据需要获取 Token 中的数据
                $authority = $decoded->authority;
                $chmod = Db::name('user_authority')->where('_id', '=', $authority)->find();
                return $chmod['limit'];
                exit;
            } catch (\Exception $e) {
                // 验证失败，可能是 Token 无效或过期
                // 返回错误信息或进行其他处理
                echo json_encode(['token_err' => $e]);
                exit;
                return false;
                exit;
            }
        } else {
            echo json_encode(['token_err' => '无token']);
            exit;
            return false;
            exit;
        }
    }

    // 用户登录
    public function login(Request $request)
    {
        $ip = $request->ip();

        $username = $request->param('username');
        $password = $request->param('password');

        $find = Db::name('users')->where('username', '=', $username)->find();

        if (empty($find)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '查无此用户，请确认用户名是否输入正确'
            ]);
            exit;
        }

        if ($find['user_enable'] === 0) {
            echo json_encode([
                'errno' => 1,
                'msg' => '该用户尚未启用'
            ]);
            exit;
        }

        if ($find['password'] === md5($password) && $find['username'] === $username) {

            log::info('用户' . $username . '登陆成功');

            $update = Db::name('users_info')->where('username', '=', $username)->update([
                'last_login' => time() + 28800,
                'ip' => $ip
            ]);

            // 数据载荷
            $payload = [
                'iss' => 'sakura_suyiyun',  // 令牌的发行者
                'username' => $username,  // 用户名
                'nbf' => time(), // 令牌的生效时间,
                'exp' => time() + 3600 * 24, //令牌的过期时间
                'authority' => $find['character']
            ];

            // 获取密钥
            $secretKey = config('jwt.secret_key');

            // 设置token唯一标识符
            $kid = 'login';

            // 生成token
            $jwtToken = JWT::encode($payload, $secretKey, "HS256", $kid);

            // 设置token过期时间
            $expire = time() + 28800 + 3600 * 24;

            // 在服务器端设置 Cookie
            setcookie('token', $jwtToken, $expire, '/', '111.230.242.195', false, true);

            echo json_encode([
                'errno' => 0,
                'msg' => '登陆成功，欢迎回来',
                'token' => $jwtToken,
                'username' => $username
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 1,
            'msg' => '登陆失败，请检查账号密码后重试'
        ]);
        exit;
    }

    // 用户注册
    public function register(Request $request)
    {
    }

    // 获取商品销量
    public function getSalePie(Request $request)
    {
        $data = Db::name('sale_pie')->select()->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '获取数据失败'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 校验token
    private function checkToken($request)
    {
        $token = $request->param("token");

        // 判断cookie中的token是否为空,不为空才往下执行
        if (!empty($token)) {

            // 获取密钥
            $secretKey = config('jwt.secret_key');

            try {
                // 解码 Token
                $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

                // 验证成功，可以根据需要获取 Token 中的数据
                $username = $decoded->username;
                // 其他操作...

                // 返回验证结果或进行其他处理
                echo json_encode([
                    'errno' => 0,
                    'message' => 'Token 验证成功',
                    'username' => $username
                ]);
                exit;
            } catch (\Exception $e) {
                // 验证失败，可能是 Token 无效或过期
                // 返回错误信息或进行其他处理
                // echo json_encode([
                //     'errno' => 1,
                //     'message' => 'Token 验证失败'
                // ]);
                echo 'Token 验证失败' . $e;
                exit;
            }
        } else {
            echo json_encode([
                'errno' => 1,
                'message' => 'Token 为空'
            ]);
            exit;
        }
    }

    // 获取所有商品
    public function getProducts()
    {
        $data = Db::name('products')
            ->alias('a')
            ->join('shops b', 'a.shop_id = b._id')
            ->join('category c', 'a.product_category = c._id')
            ->select()
            ->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '获取数据失败'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取所有商品数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 获取所有商品分类
    public function getCategories()
    {
        $data = Db::name('category')
            ->select()
            ->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '获取数据失败'
            ]);
            exit;
        }

        $newData = [];

        foreach ($data as $item) {
            $_id = $item['_id'];

            $count = count(Db::name('products')->where('product_category', '=', $_id)->select()->toArray());

            $item['count'] = $count;

            array_push($newData, $item);
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $newData
        ]);
        exit;
    }

    // 获取所有店铺
    public function getShops(Request $request)
    {
        $data = Db::name('shops')
            ->select()
            ->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '获取数据失败',
            ]);
            exit;
        }

        $newData = [];

        foreach ($data as $item) {
            $count = count(Db::name('products')->where('shop_id', '=', $item['_id'])->select()->toArray());

            $item['count'] = $count;

            array_push($newData, $item);
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 添加属性 权限2 已修改
    public function addProductCategory(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '2') {
            $category_name = $request->param('categoryName');

            $add = Db::name('category')->save([
                'category_name' => $category_name
            ]);

            if (empty($add)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '添加失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '添加成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 添加品牌 权限2 已修改
    public function addShop(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '2') {
            $shop_name = $request->param('shopName');
            $isShow = $request->param('isShow');

            $add = Db::name('shops')->save([
                'shop_name' => $shop_name,
                'isShow' => $isShow === null ? 0 : $isShow
            ]);

            if (empty($add)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '添加失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '添加成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 添加商品-上传文件功能 权限2 已修改
    public function uploadFile(Request $request)
    {
        $conn = new \think\cache\driver\Redis(config('cache.stores.redis'));

        $file = $request->file('file');

        if ($file) {
            $ext = $file->getOriginalExtension();

            $fileName = uniqid() . '.' . $ext;

            $info = $file->move('upload/products', $fileName);

            if ($info) {
                $filePath = $info->getPathname();

                // 使用 Redis 连接进行操作
                $conn->set('file', $filePath);

                echo json_encode([
                    'errno' => 0,
                    'msg' => '上传文件成功',
                ]);
                exit;
            } else {
                echo json_encode([
                    'errno' => 1,
                    'message' => '上传文件失败'
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '上传文件为空'
            ]);
            exit;
        }
    }

    // 添加商品-配置数据功能 权限2 已修改
    public function addProducts(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '2') {
            $conn = new \think\cache\driver\Redis(config('cache.stores.redis'));

            $fileName = $conn->get('file');

            $product_name = $request->param('productName');
            $product_price = $request->param('productPrice');
            $shop_id = $request->param('shopId');
            $product_category = $request->param('productCategory');

            $count = count(Db::name('products')->select()->toArray());

            $data = [
                'product_name' => $product_name,
                'product_img' => 'http://111.230.242.195/' . $fileName,
                'product_price' => $product_price,
                'shop_id' => $shop_id,
                'create_at' => time() + 28800,
                'product_category' => $product_category,
                'product_id' => $count + 1
            ];

            $add = Db::name('products')->save($data);

            $product_rec = Db::name('product_recommendation')->save([
                'product_id' => $add,
            ]);

            if (empty($add)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '添加商品失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '添加商品成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 删除商品 权限2 已修改
    public function delProduct(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '2') {
            $product_id = $request->param('productId');

            $del = Db::name('products')->where('product_id', '=', $product_id)->delete();

            if (empty($del)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '删除失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '删除成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 删除品牌 权限2 已修改
    public function delShop(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '2') {
            $shop_id = $request->param('shopId');

            $del = Db::name('shops')->where('_id', '=', $shop_id)->delete();

            if (empty($del)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '删除失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '删除成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 删除属性 权限2 已修改
    public function delCategory(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '2') {
            $category_id = $request->param('categoryId');

            $del = Db::name('category')->where('_id', '=', $category_id)->delete();

            if (empty($del)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '删除失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '删除成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 获取品牌推荐列表
    public function getShopRecommend(Request $request)
    {
        $data = Db::name('shop_recommendation')
            ->alias('a')
            ->join('shops b', 'a.shop_id = b._id')
            ->select()
            ->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '获取数据失败'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 获取商品推荐列表
    public function getProductRecommend(Request $request)
    {
        $data = Db::name('product_recommendation')
            ->alias('a')
            ->join('products b', 'a.product_id = b._id')
            ->select()
            ->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '获取数据失败'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 获取广告列表
    public function getAdvertisement(Request $request)
    {
        $data = Db::name('advertisement')
            ->select()
            ->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '获取数据失败'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 添加广告-文件上传功能 权限4 已修改
    public function uploadAdvertisment(Request $request)
    {
        $conn = new \think\cache\driver\Redis(config('cache.stores.redis'));

        $file = $request->file('file');

        if ($file) {
            $ext = $file->getOriginalExtension();

            $fileName = uniqid() . '.' . $ext;

            $info = $file->move('upload/advertisement', $fileName);

            if ($info) {
                $filePath = $info->getPathname();

                // 使用 Redis 连接进行操作
                $conn->set('adv_file', $filePath);

                echo json_encode([
                    'errno' => 0,
                    'msg' => '上传文件成功',
                ]);
                exit;
            } else {
                echo json_encode([
                    'errno' => 1,
                    'message' => '上传文件失败'
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '上传文件为空'
            ]);
            exit;
        }
    }

    // 添加广告-配置数据功能 权限4 已修改
    public function addAdvertisement(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '4') {
            $conn = new \think\cache\driver\Redis(config('cache.stores.redis'));

            $fileName = $conn->get('adv_file');

            $advName = $request->param('advName');
            $advPosition = $request->param('advPosition');
            $advStart = $request->param('advStart');
            $advEnd = $request->param('advEnd');

            $data = [
                'adv_name' => $advName,
                'adv_position' => $advPosition,
                'adv_start_at' => $advStart,
                'adv_end_at' => $advEnd,
                'adv_imgUrl' => 'http://111.230.242.195/' . $fileName
            ];

            $add = Db::name('advertisement')->save($data);

            if (empty($add)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '添加广告失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '添加广告成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 删除广告 权限4 已修改
    public function delAddvertisment(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '4') {
            $id = $request->param('id');

            $del = Db::name('advertisement')
                ->where('_id', '=', $id)
                ->delete();

            if (empty($del)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '删除失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '删除成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 编辑商品图片 权限2
    public function editProductImg(Request $request)
    {
        $conn = new \think\cache\driver\Redis(config('cache.stores.redis'));

        $file = $request->file('file');

        $id = $request->param('productId');

        if ($file) {
            $ext = $file->getOriginalExtension();

            $fileName = uniqid() . '.' . $ext;

            $info = $file->move('upload/products', $fileName);

            if ($info) {
                $filePath = $info->getPathname();

                $data = ['productId' => $id, 'imgUrl' => $filePath];

                // 使用 Redis 连接进行操作
                $conn->set('changeFile', $data);

                echo json_encode([
                    'errno' => 0,
                    'msg' => '上传文件成功',
                ]);
                exit;
            } else {
                echo json_encode([
                    'errno' => 1,
                    'message' => '上传文件失败'
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '上传文件为空'
            ]);
            exit;
        }
    }

    // 编辑商品 权限2
    public function editProduct(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au == '777' || $au == '2') {
            $product_id = $request->param('product_id');

            $conn = new \think\cache\driver\Redis(config('cache.stores.redis'));

            $fileName = $conn->get('changeFile');

            if (!empty($fileName)) {
                $first = Db::name('products')->where('product_id', '=', $fileName['productId'])->update([
                    'product_img' => 'http://111.230.242.195/' . $fileName['imgUrl']
                ]);
            }

            $paramNames = ['product_name', 'product_price', 'product_inventory', 'product_sales', 'product_audit_status', 'shop_id', 'product_category'];
            $params = [];

            foreach ($paramNames as $paramName) {
                $paramValue = $request->param($paramName);
                if (!empty($paramValue)) {
                    $params[$paramName] = $paramValue;
                }
            }

            if (empty($params)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '修改内容为空'
                ]);
                exit;
            }

            $update = Db::name('products')->where('product_id', '=', $product_id)->update($params);

            if (empty($update)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '修改失败'
                ]);
                exit;
            }

            // $conn->rm('changeImg');

            echo json_encode([
                'errno' => 0,
                'msg' => '修改成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 删除商品分类 权限2 已修改
    public function delProductCategory(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '2') {
            $id = $request->param('id');

            $check = Db::name('products')
                ->where('product_category', '=', $id)
                ->select()
                ->toArray();

            if ($check) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '该分类下有商品，无法删除'
                ]);
                exit;
            }

            $del = Db::name('category')->where('_id', '=', $id)->delete();

            if (empty($del)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '删除失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '删除成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 获取用户列表
    public function getUserLists()
    {
        $data = Db::name('users')
            ->alias('a')
            ->join('users_info b', 'a.username = b.username')
            ->field('a._id, a.username, a.character, a.create_at, a.user_enable, b.email, b.user_nickname, b.last_login, b.ip')
            ->select()
            ->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '获取用户数据失败'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取用户数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 添加用户 权限1 已修改
    public function addUser(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '1') {
            $username = $request->param('username');
            $password = $request->param('password');
            // $userEnable = $request->param('isEnable');
            $email = $request->param('email');
            $ip = $request->ip();

            $check = Db::name('users')->where('username', '=', $username)->find();

            if ($check) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '已存在该用户，无法重复添加'
                ]);
                exit;
            }

            $data = [
                'username' => $username,
                'password' => md5($password),
                'create_at' => time() + 28800,
                // 'user_enable' => $userEnable
            ];

            // 开启数据库事务
            Db::startTrans();

            try {
                // 更新第一个表的数据
                $result1 = Db::name('users')->save($data);

                // 更新第二个表的数据
                $result2 = Db::name('users_info')->save([
                    'username' => $username,
                    'user_nickname' => $username,
                    'email' => $email,
                    'ip' => $ip,
                    'last_login' => time() + 28800
                ]);

                // 检查两个更新操作是否都成功
                if ($result1 == true && $result2 == true) {
                    // 提交事务
                    Db::commit();
                    // 数据库更新成功，可以进行其他操作
                    echo json_encode([
                        'errno' => 0,
                        'msg' => '添加用户成功'
                    ]);
                    exit;
                } else {
                    // 回滚事务
                    Db::rollback();
                    // 数据库更新失败，可以进行相应的错误处理
                    echo json_encode([
                        'errno' => 1,
                        'msg' => '添加用户失败'
                    ]);
                    exit;
                }
            } catch (\Exception $e) {
                // 捕获异常并回滚事务
                Db::rollback();
                // 处理异常，例如记录日志或返回错误信息
                echo json_encode([
                    'errno' => 1,
                    'msg' => '添加用户失败'
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 获取所有权限角色
    public function getUserAuthor()
    {
        $data = Db::name('user_authority')->field('_id,name,desc')->select()->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '获取数据失败'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 编辑用户信息 权限1 已修改
    public function editUserInfo(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au  === '1') {
            $oldUserName = $request->param('oldUserName');
            $username = $request->param('userName');
            $nickName = $request->param('nickName');
            $email = $request->param('email');
            $password = $request->param('password');
            $isEnable = $request->param('isEnable');

            // 开启数据库事务
            Db::startTrans();

            try {
                // 更新第一个表的数据
                $result1 = Db::name('users')->where('username', '=', $oldUserName)->update([
                    'username' => $username,
                    'password' => md5($password),
                    'user_enable' => $isEnable
                ]);

                // 更新第二个表的数据
                $result2 = Db::name('users_info')->where('username', '=', $oldUserName)->update([
                    'username' => $username,
                    'user_nickname' => $nickName,
                    'email' => $email,
                ]);

                // 检查两个更新操作是否都成功
                if ($result1 !== false && $result2 !== false) {
                    // 提交事务
                    Db::commit();
                    // 数据库更新成功，可以进行其他操作
                    echo json_encode([
                        'errno' => 0,
                        'msg' => "更新数据成功"
                    ]);
                    exit;
                } else {
                    // 回滚事务
                    Db::rollback();
                    // 数据库更新失败，可以进行相应的错误处理
                    echo json_encode([
                        'errno' => 1,
                        'msg' => "更新数据失败"
                    ]);
                    exit;
                }
            } catch (\Exception $e) {
                // 捕获异常并回滚事务
                Db::rollback();
                // 处理异常，例如记录日志或返回错误信息
                echo json_encode([
                    'errno' => 1,
                    'msg' => "更新数据失败"
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 修改角色权限 权限1 已修改
    public function editUserAuthor(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '1') {
            $username = $request->param('username');
            $author = $request->param('author');

            $update = Db::name('users')->where('username', '=', $username)->update([
                'character' => $author
            ]);

            if (empty($update)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '更改用户权限失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '更改用户权限成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 激活账户 权限1
    public function activateUsers(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '1') {
            $username = $request->param('username');
            $isEnable = $request->param('isEnable');

            $update = Db::name('users')->where('username', '=', $username)->update([
                'user_enable' => $isEnable
            ]);

            if (empty($update)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '更改用户权限失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '更改用户权限成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 获取资源管理列表
    public function getAssetsList()
    {
        $data = Db::name('assets')->select()->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '数据为空'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 获取订单列表
    public function getOrderList()
    {
        $data = Db::name('orders')
            // ->alias('a')
            // ->join('order_products b', 'a.order_num = b.order_id')
            // ->join('products c', 'b.product_id = c._id')
            // ->join('order_info d', 'a.order_num = d.order_id')
            // ->field('a._id, a.order_num, a.create_at, a.username, a.oreder_price, a.payment, a.order_from, a.order_status')
            ->select()->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '数据为空'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 获取订单详情 权限3 已修改
    public function getOrderDetail(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '3') {
            $order_id = $request->param('orderId');
            $data = Db::name('orders')
                ->alias('a')
                ->join('order_products b', 'a.order_num = b.order_id')
                ->join('products c', 'b.product_id = c._id')
                ->join('order_info d', 'a.order_num = d.order_id')
                ->join('user_address e', 'd.address_id = e._id')
                ->where('a.order_num', '=', $order_id)
                // ->field('a._id, a.order_num, a.create_at, a.username, a.oreder_price, a.payment, a.order_from, a.order_status')
                ->select()->toArray();

            if (empty($data)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '数据为空'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '获取数据成功',
                'data' => $data
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 删除订单 权限3 已修改
    public function delOrderList(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '3') {
            $order_id = $request->param('orderId');

            // 开启数据库事务
            Db::startTrans();

            try {
                // 更新第一个表的数据
                $del1 = Db::name('order_products')
                    ->where('order_id', '=', $order_id)
                    ->delete();

                // 更新第二个表的数据
                $del2 = Db::name('order_info')
                    ->where('order_id', '=', $order_id)
                    ->delete();

                //  更新第三个表的数据
                $del3 = Db::name('orders')
                    ->where('order_num', '=', $order_id)
                    ->delete();

                // 检查两个更新操作是否都成功
                if ($del1 == true && $del2 == true && $del3 == true) {
                    // 提交事务
                    Db::commit();
                    // 数据库更新成功，可以进行其他操作
                    echo json_encode([
                        'errno' => 0,
                        'msg' => '删除订单成功'
                    ]);
                    exit;
                } else {
                    // 回滚事务
                    Db::rollback();
                    // 数据库更新失败，可以进行相应的错误处理
                    echo json_encode([
                        'errno' => 1,
                        'msg' => '删除订单失败'
                    ]);
                    exit;
                }
            } catch (\Exception $e) {
                // 捕获异常并回滚事务
                Db::rollback();
                // 处理异常，例如记录日志或返回错误信息
                echo json_encode([
                    'errno' => 1,
                    'msg' => '删除订单失败'
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 获取退货申请列表
    public function getReturnList()
    {
        $data = Db::name('order_return')->select()->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '数据为空'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 获取退货原因列表
    public function getReturnReason()
    {
        $data = Db::name('order_return_reason')->select()->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '数据为空'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 添加退货原因 权限3 已修改
    public function addReturnReason(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '3') {
            $reasonName = $request->param('title');
            $isAvaila = $request->param('isAvaila');

            $data = [
                'name' => $reasonName,
                'isAvailable' => $isAvaila,
                'create_at' => time()
            ];

            $add = Db::name('order_return_reason')->save($data);

            if (empty($add)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '添加失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '添加成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 秒杀活动列表
    public function getSeckillList()
    {
        $data = Db::name('seckill_active_list')->select()->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '暂无数据'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 添加秒杀活动 权限4 已修改
    public function addSeckillList(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '4') {
            $title = $request->param('title');
            $active_state = $request->param('activeState');
            $active_start_time = $request->param('activeStartTime');
            $active_end_time = $request->param('activeEndTime');
            $isActive = $request->param('isActive');

            $data = [
                'title' => $title,
                'active_state' => $active_state,
                'active_start_time' => $active_start_time,
                'active_end_time' => $active_end_time,
                'isActive' => $isActive
            ];

            $add = Db::name('seckill_active_list')->save($data);

            if (empty($add)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '添加失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '添加成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 删除秒杀活动 权限4 已修改
    public function delSeckillList(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '4') {
            $id = $request->param('id');

            $del = Db::name('seckill_active_list')->where('_id', '=', $id)->delete();

            if (empty($del)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '删除失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '删除成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 获取优惠券列表
    /* 状态：
        0 -未启用 
        1 -启用中 
        2 -已过期
    */
    public function getCoupons()
    {
        $data = Db::name('coupons')->select()->toArray();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '优惠券列表为空'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 添加优惠券 权限4 已修改
    public function addCoupons(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '4') {
            $coupon_name = $request->param('couponName');
            $coupon_type = $request->param('couponType');
            $coupon_product = $request->param('couponProduct');
            $coupon_sill = $request->param('couponSill');
            $coupon_price = $request->param('couponPrice');
            $coupon_scope = $request->param('couponScope');
            $coupon_start_time = $request->param('couponStart');
            $coupon_end_time = $request->param('couponEnd');
            $coupon_status = $request->param('couponStatus');

            $data = [
                'coupon_name' => $coupon_name,
                'coupon_type' => $coupon_type,
                'coupon_product' => $coupon_product,
                'coupon_sill' => $coupon_sill,
                'coupon_price' => $coupon_price,
                'coupon_scope' => $coupon_scope,
                'coupon_start_time' => $coupon_start_time,
                'coupon_end_time' => $coupon_end_time,
                'coupon_status' => $coupon_status
            ];

            $add = Db::name('coupons')->save($data);

            if (empty($data)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '添加失败'
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 删除优惠券 权限4 已修改
    public function delCoupons(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '4') {
            $id = $request->param('id');

            $del = Db::name('coupons')->where('_id', '=', $id)->delete();

            if (empty($del)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '删除失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '删除成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 订单查询-条件 权限3
    public function orderSearch(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '1') {
            // 获取参数
            $paramNames = ['order_num', 'username', 'create_at', 'order_status', 'payment', 'order_from'];
            $params = [];

            foreach ($paramNames as $paramName) {
                $paramValue = $request->param($paramName);
                if (!empty($paramValue)) {
                    $params[$paramName] = $paramValue;
                }
            }

            if (empty($params)) {
                $data = Db::name('orders')->select()->toArray();

                if (empty($data)) {
                    echo json_encode([
                        'errno' => 1,
                        'msg' => '查询数据为空'
                    ]);
                    exit;
                }

                echo json_encode([
                    'errno' => 0,
                    'msg' => '查询数据成功',
                    'data' => $data
                ]);
                exit;
            } else {
                if (empty($params['create_at'])) {
                    $data = Db::name('orders')->where($params)->select()->toArray();
                } else {
                    $data = Db::name('orders')->select()->toArray();

                    $newData = [];

                    foreach ($data as $item) {
                        if (date('Y-m-d', $item['create_at']) == date('Y-m-d', ($params['create_at'] / 1000))) {
                            array_push($newData, $item);
                        }
                    }

                    if (empty($newData)) {
                        echo json_encode([
                            'errno' => 1,
                            'msg' => '查询数据为空'
                        ]);
                        exit;
                    }

                    echo json_encode([
                        'errno' => 0,
                        'msg' => '查询数据成功',
                        'data' => $newData
                    ]);
                    exit;
                }


                if (empty($data)) {
                    echo json_encode([
                        'errno' => 1,
                        'msg' => '查询数据为空'
                    ]);
                    exit;
                }

                echo json_encode([
                    'errno' => 0,
                    'msg' => '查询数据成功',
                    'data' => $data
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 随机下单功能
    public function addOrder(Request $request)
    {
        $min = 0;
        $product_count = mt_rand($min, 100);

        $osn = date('Y') . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));

        $user_data = Db::name('users')->select()->toArray();
        $user_max = count($user_data) - 1;

        $product = Db::name('products')->select()->toArray();
        $product_max = count($product) - 1;

        $payment = ['支付宝', '微信', '未支付'];
        $payment_max = count($payment) - 1;

        $from = ['小程序', 'APP订单'];
        $from_max = count($from) - 1;

        $random_user = $user_data[mt_rand($min, $user_max)];
        $random_product = $product[mt_rand($min, $product_max)];
        $random_payment = $payment[mt_rand($min, $payment_max)];
        $random_from = $from[mt_rand($min, $from_max)];

        $data1 = [
            'order_num' => $osn,
            'create_at' => time() + 28800,
            'username' => $random_user['username'],
            'order_price' => $random_product['product_price'] * $product_count,
            'payment' => $random_payment,
            'order_from' => $random_from
        ];

        $data2 = [
            'order_id' => $osn,
            'product_id' => $random_product['_id'],
            'product_count' => $product_count,
            'product_price' => $random_product['product_price'] * $product_count,
        ];

        $data3 = [
            'order_id' => $osn,
            'address_id' => 1
        ];

        // dd($data1,$data2,$data3);

        // 开启数据库事务
        Db::startTrans();

        try {
            // 更新第一个表的数据
            $add1 = Db::name('orders')->save($data1);

            // 更新第二个表的数据
            $add2 = Db::name('order_products')->save($data2);

            //  更新第三个表的数据
            $add3 = Db::name('order_info')->save($data3);

            // 检查两个更新操作是否都成功
            if ($add1 == true && $add2 == true && $add3 == true) {
                // 提交事务
                Db::commit();
                // 数据库更新成功，可以进行其他操作
                echo json_encode([
                    'errno' => 0,
                    'msg' => '添加订单成功'
                ]);
                exit;
            } else {
                // 回滚事务
                Db::rollback();
                // 数据库更新失败，可以进行相应的错误处理
                echo json_encode([
                    'errno' => 1,
                    'msg' => '添加订单失败'
                ]);
                exit;
            }
        } catch (\Exception $e) {
            // 捕获异常并回滚事务
            Db::rollback();
            // 处理异常，例如记录日志或返回错误信息
            echo json_encode([
                'errno' => 1,
                'msg' => '添加订单失败'
            ]);
            exit;
        }

        dd($random_payment);
    }

    // 角色编辑 权限1
    public function editAccount(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '1') {

            $id = $request->param('id');

            $paramNames = ['name', 'desc', 'isEnable'];
            $params = [];

            foreach ($paramNames as $paramName) {
                $paramValue = $request->param($paramName);
                if (!empty($paramValue)) {
                    $params[$paramName] = $paramValue;
                }
            }

            if (!empty($params['name']) && $params['name'] === 'admin') {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '此用户不可更改'
                ]);
                exit;
            }

            $update = Db::name('user_authority')
                ->where('_id', '=', $id)
                ->update($params);

            if (empty($update)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '需要修改的内容为空'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '修改成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 退货列表搜索 权限3
    public function orderReturnSearch(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '3') {
            $paramNames = ['_id', 'order_id', 'create_at', 'username', 'isFinished'];
            $params = [];

            foreach ($paramNames as $paramName) {
                $paramValue = $request->param($paramName);
                if (!empty($paramValue)) {
                    $params[$paramName] = $paramValue;
                }
            }

            if (empty($params)) {
                $data = Db::name('order_return')->select()->toArray();

                if (empty($data)) {
                    echo json_encode([
                        'errno' => 1,
                        'msg' => '查询数据为空'
                    ]);
                    exit;
                }

                echo json_encode([
                    'errno' => 0,
                    'msg' => '查询数据成功',
                    'data' => $data
                ]);
                exit;
            } else {
                if (empty($params['create_at'])) {
                    $data = Db::name('order_return')->where($params)->select()->toArray();
                } else {
                    $data = Db::name('order_return')->select()->toArray();

                    $newData = [];

                    foreach ($data as $item) {
                        if (date('Y-m-d', $item['create_at']) == date('Y-m-d', ($params['create_at'] / 1000))) {
                            array_push($newData, $item);
                        }
                    }

                    if (empty($newData)) {
                        echo json_encode([
                            'errno' => 1,
                            'msg' => '查询数据为空'
                        ]);
                        exit;
                    }

                    echo json_encode([
                        'errno' => 0,
                        'msg' => '查询数据成功',
                        'data' => $newData
                    ]);
                    exit;
                }


                if (empty($data)) {
                    echo json_encode([
                        'errno' => 1,
                        'msg' => '查询数据为空'
                    ]);
                    exit;
                }

                echo json_encode([
                    'errno' => 0,
                    'msg' => '查询数据成功',
                    'data' => $data
                ]);
                exit;
            }

            $search = Db::name('order_return')->where($params)->select()->toArray();
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 退货原因-删除 权限3
    public function delReturnReason(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '3') {
            $id = $request->param('id');

            $del = Db::name('order_return_reason')->where('_id', '=', $id)->delete();

            if (empty($del)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '删除失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '删除成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 退货原因-编辑 权限3
    public function editReturnReason(Request $request)
    {
        $au = $this->checkAuthor($request);

        if ($au === '777' || $au === '3') {

            $id = $request->param('id');
            $name = $request->param('name');

            if (empty($name)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '修改内容为空'
                ]);
                exit;
            }


            $del = Db::name('order_return_reason')->where('_id', '=', $id)->update([
                'name' => $name
            ]);

            if (empty($del)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '修改失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '修改成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 退货原因-启用 权限3
    public function changeReturnReasonStatus(Request $request)
    {
        $au = $this->checkAuthor($request);
        if ($au === '777' || $au === '3') {

            $id = $request->param('id');

            $data = Db::name('order_return_reason')->where('_id', '=', $id)->find();

            $update = Db::name('order_return_reason')->where('_id', '=', $id)->update([
                'isAvailable' => $data['isAvailable'] == 1 ? 0 : 1
            ]);

            if (empty($update)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '修改失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '修改成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 获取订单设置时间 
    public function getOrderTime(Request $request)
    {
        $data = Db::name('order_time_setting')->where('_id', '=', '1')->find();

        if (empty($data)) {
            echo json_encode([
                'errno' => 1,
                'msg' => '查询数据为空'
            ]);
            exit;
        }

        echo json_encode([
            'errno' => 0,
            'msg' => '查询数据成功',
            'data' => $data
        ]);
        exit;
    }

    // 订单时间设置 权限1
    public function orderTimeSetting(Request $request)
    {
        $au = $this->checkAuthor($request);
        if ($au === '777' || $au === '1') {

            $paramNames = ['seckill_oreder_time', 'normal_order_time', 'consignment_order_time', 'complete_order_time', 'exceed_order_time'];
            $params = [];

            foreach ($paramNames as $paramName) {
                $paramValue = $request->param($paramName);
                if (!empty($paramValue)) {
                    $params[$paramName] = $paramValue;
                }
            }

            if (empty($params)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '没有做出修改'
                ]);
                exit;
            }

            $update = Db::name("order_time_setting")->where('_id', '=', '1')->update($params);

            if (empty($update)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '修改失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '修改成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 修改秒杀活动状态 权限4
    public function editSeckillList(Request $request)
    {
        $au = $this->checkAuthor($request);
        if ($au === '777' || $au === '3') {

            $id = $request->param('id');

            $find = Db::name('seckill_active_list')->where('_id', '=', $id)->find();

            $update = Db::name('seckill_active_list')->where('_id', '=', $id)->update([
                'isActive' => $find['isActive'] == 0 ? 1 : 0
            ]);

            if (empty($update)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '更细失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '更新数据成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 添加品牌推荐 权限4
    public function addShopRecomend(Request $request)
    {
        $au = $this->checkAuthor($request);
        if ($au === '777' || $au === '4') {

            $shopId = $request->param('shopId');
            $isRecommend = $request->param('isRecommend');
            $sort = $request->param('sort');

            $check = Db::name('shop_recommendation')->where('shop_id', '=', $shopId)->find();

            if (!empty($check)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '该商品已经存在'
                ]);
                exit;
            }

            $add = Db::name('shop_recommendation')->save([
                'shop_id' => $shopId,
                'isRecommend' => $isRecommend === null ? 0 : $isRecommend,
                'sort' => $sort === null ? 0 : $sort
            ]);

            if (empty($add)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '添加数据失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 0,
                'msg' => '添加数据成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 修改品牌推荐状态 权限4
    public function editShopRecomend(Request $request)
    {
        $au = $this->checkAuthor($request);
        if ($au === '777' || $au === '4') {

            $shopId = $request->param('shopId');

            $find = Db::name('shop_recommendation')->where('shop_id', '=', $shopId)->find();

            if (empty($find)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '查无该品牌'
                ]);
                exit;
            }

            $update = Db::name('shop_recommendation')->where('shop_id', '=', $shopId)->update([
                'isRecommend' => $find['isRecommend'] == 0 ? 1 : 0
            ]);

            if (empty($update)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '修改数据失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 1,
                'msg' => '修改数据成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    // 删除品牌推荐 权限4
    public function delShopRecomend(Request $request)
    {
        $au = $this->checkAuthor($request);
        if ($au === '777' || $au === '4') {

            $shopId = $request->param('shopId');

            $del = Db::name('shop_recommendation')->where('shop_id', '=', $shopId)->delete();

            if (empty($del)) {
                echo json_encode([
                    'errno' => 1,
                    'msg' => '删除数据失败'
                ]);
                exit;
            }

            echo json_encode([
                'errno' => 1,
                'msg' => '删除数据成功'
            ]);
            exit;
        } else {
            echo json_encode([
                'errno' => 1,
                'msg' => '您没有操作该功能的权限'
            ]);
            exit;
        }
    }

    public function get()
    {
        // 设置表格的表头数据
        $header = ["A1" => "编号", "B1" => "姓名", "C1" => "年龄"];
        // 假设下面这个数组从数据库查询出的二维数组
        $data = [
            [1, '张耀', 18],
            [2, '柴彪', 19],
            [3, '冯宝杰', 22],
            [4, '戎飞', 19],
            [5, '海豹', 17]
        ];
        // 保存文件的类型
        $type = true;
        // 设置下载文件保存的名称
        $fileName = '1910A班信息导出' . time();
        // 调用方法导出excel
        // Excel::export($header, $type, $data, $fileName);


    }
}
