<?php

if (!function_exists('result_format')) {
    /**
     * 格式化成友好的返回值
     * @param mixed $data
     * @return mixed
     * @author mosquito <zwj1206_hi@163.com>
     */
    function result_format($data = null)
    {
        if (is_object($data)) {
            $data = get_object_vars($data);
            if (!$data) {
                return new \stdClass;
            }
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = result_format($value);
            }
        } elseif (is_bool($data)) {
            $data = strval(intval($data));
        } elseif (is_null($data)) {
            // $data = strval($data);
        } elseif (!is_string($data)) {
            $data = strval($data);
        }
        return $data;
    }
}

if (!function_exists('api_format')) {
    /**
     * api返回格式化 依赖 result_format
     * @param string $msg
     * @param string $code
     * @param mixed $data
     * @return array
     * @author mosquito <zwj1206_hi@163.com>
     */
    function api_format(string $msg = '', string $code = '0', $data = null)
    {
        $result = [
            'msg' => $msg,
            'code' => $code,
            'data' => $data,
        ];
        $result = result_format($result);
        return $result;
    }
}

if (!function_exists('trim_all')) {
    /**
     * 移除字符串全部指定字符
     * @param string $str
     * @param mixed $search
     * @return string
     * @author mosquito <zwj1206_hi@163.com>
     */
    function trim_all(string $str, $search = null)
    {
        is_null($search) && $search = [' ', '　', "\t", "\n", "\r"];
        return str_replace($search, '', $str);
    }
}

if (!function_exists('array_filter_values')) {
    /**
     * 数组过滤且返回值
     * @param mixed $arr
     * @param mixed $callback
     * @param int $flag
     * @return array
     * @author mosquito <zwj1206_hi@163.com>
     */
    function array_filter_values($arr, $callback = null, $flag = 0)
    {
        if (!is_array($arr)) {
            return [];
        }
        if (is_null($callback)) {
            $callback = [null, false, ''];
        }
        if (is_callable($callback)) {
            $arr = array_filter($arr, $callback, $flag);
        } else {
            $callback = (array) $callback;
            foreach ($arr as $key => $value) {
                if (in_array($value, $callback, true)) {
                    unset($arr[$key]);
                }
            }
        }
        return array_values($arr);
    }
}

if (!function_exists('array_combine_all')) {
    /**
     * 数组全组合数
     * @param array $arr
     * @param array $into
     * @return array
     * @author mosquito <zwj1206_hi@163.com>
     */
    function array_combine_all(array $arr, array $into = [])
    {
        $func = function (array $arr, $into = [], &$result = []) use (&$func) {
            foreach ($arr as $key => $value) {
                $current = $arr;
                unset($current[$key]);
                $current_into = is_array($into) ? $into : [];
                array_push($current_into, $value);
                if ($current) {
                    $func($current, $current_into, $result);
                } else {
                    $result[] = $current_into;
                }
            }
        };
        $result = [];
        $func($arr, $into, $result);
        return $result;
    }
}

if (!function_exists('is_json')) {
    /**
     * 是否是Json数据，是则返回格式后数据
     * @param mixed $json
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     * @return mixed
     * @author mosquito <zwj1206_hi@163.com>
     */
    function is_json($json, bool $assoc = false, int $depth = 512, int $options = 0)
    {
        if (!is_string($json)) {
            return false;
        }
        $result = json_decode($json, $assoc, $depth, $options);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        if (is_null($result)) {
            return false;
        }
        return $result;
    }
}

if (!function_exists('is_mobile')) {
    /**
     * 是否是手机号
     * @param string $mobile
     * @return bool
     * @author mosquito <zwj1206_hi@163.com>
     */
    function is_mobile(string $mobile)
    {
        if (preg_match('/^1\d{10}$/', $mobile)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('is_phone')) {
    /**
     * 是否是联系电话
     * @param string $phone
     * @return bool
     * @author mosquito <zwj1206_hi@163.com>
     */
    function is_phone(string $phone)
    {
        if (preg_match('/^\d{3}-?\d{8}$|^\d{4}-?\d{7}$/', $phone)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('is_url')) {
    /**
     * 是否是合法网址
     * @param string $url
     * @return bool
     * @author mosquito <zwj1206_hi@163.com>
     */
    function is_url(string $url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('is_email')) {
    /**
     * 是否是合法邮箱
     * @param string $email
     * @return bool
     * @author mosquito <zwj1206_hi@163.com>
     */
    function is_email(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('is_int_multi')) {
    /**
     * 是否是自然数的整数倍
     * @param float $num
     * @param int $unit
     * @return bool
     * @author mosquito <zwj1206_hi@163.com>
     */
    function is_int_multi(float $num, int $unit = 100)
    {
        if (floatval($num) > intval($num)) {
            return false;
        }
        return $num % $unit == 0 ? true : false;
    }
}

if (!function_exists('is_idcard')) {
    /**
     * 是否是身份证信息，是则返回格式后数据
     * @param string $idcard
     * @return bool|array
     * @author mosquito <zwj1206_hi@163.com>
     */
    function is_idcard(string $idcard)
    {
        if (strlen($idcard) != 18) {
            return false;
        }
        $temp = preg_match('/^[1-9]\d{5}((?:18|19|20)\d{2})((?:0[1-9]|10|11|12))((?:0[1-9]|[1-2]\d|30|31))\d{2}(\d{1})[\dXx]$/', $idcard, $match);
        if (!$temp) {
            return false;
        }
        $birth = implode('-', [$match[1], $match[2], $match[3]]);
        $sex = intval($match[4] % 2 ? 1 : 2);
        return [
            'idcard' => $idcard,
            'birth' => $birth,
            'sex' => $sex,
        ];
    }
}

if (!function_exists('num_random')) {
    /**
     * 数字随机
     * @param int $length
     * @return false|string
     * @author mosquito <zwj1206_hi@163.com>
     */
    function num_random(int $length = 6)
    {
        if ($length < 1) {
            return false;
        }
        $len = 8;
        $multi = floor($length / $len);
        $mol = $length % $len;
        $result = '';
        if ($multi > 0) {
            while ($multi--) {
                $result .= str_pad(random_int(1, pow(10, $len) - 1), $len, '0', STR_PAD_LEFT);
            }
        }
        if ($mol > 0) {
            $result .= str_pad(random_int(1, pow(10, $mol) - 1), $mol, '0', STR_PAD_LEFT);
        }
        return $result;
    }
}

if (!function_exists('str_random')) {
    /**
     * 字符串随机
     * @param int $length
     * @param int $model 可选: 0,1,2,3
     * @return false|string
     * @author mosquito <zwj1206_hi@163.com>
     */
    function str_random(int $length = 6, int $model = 0)
    {
        if ($length < 1) {
            return false;
        }
        $chars_arr = [
            'abcdefghijklmnopqrstuvwxyz',
            'abcdefghijklmnopqrstuvwxyz0123456789',
            'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ];
        $chars = $chars_arr[$model] ?? $chars_arr[0];
        $rand_max = strlen($chars) - 1;
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[random_int(0, $rand_max)];
        }
        return $result;
    }
}

if (!function_exists('round_str')) {
    /**
     * round()转字符串
     * @param mixed $val
     * @param int $precision
     * @param bool $symbol
     * @param int $mode
     * @return string
     * @author mosquito <zwj1206_hi@163.com>
     */
    function round_str($val, int $precision = 2, bool $symbol = false, int $mode = PHP_ROUND_HALF_UP)
    {
        $val = round(floatval($val), $precision, $mode);
        return sprintf(($symbol && $val > 0 ? '+' : '') . '%0.' . $precision . 'f', $val);
    }
}

if (!function_exists('price_format')) {
    /**
     * 价格格式化 依赖 round_str
     * @param mixed $val
     * @param int $precision
     * @param int $type
     * @param string $unit
     * @return string
     * @author mosquito <zwj1206_hi@163.com>
     */
    function price_format($val, int $precision = 2, int $type = 0, string $unit = '￥')
    {
        $val = round_str($val, $precision);
        $symbol = '';
        if ($val > 0) {
            $symbol = '+';
        } elseif ($val < 0) {
            $symbol = '-';
        }
        switch ($type) {
            case 0:
                //￥1.00，-￥1.00
                $result = ($val < 0 ? $symbol : '') . $unit . trim($val, $symbol);
                break;
            case 1:
                //1.00积分，-1.00积分
                $result = ($val < 0 ? $symbol : '') . trim($val, $symbol) . $unit;
                break;
            case 2:
                //+￥1.00，-￥1.00
                $result = $symbol . $unit . trim($val, $symbol);
                break;
            case 3:
                //+1.00积分，-1.00积分
                $result = $symbol . trim($val, $symbol) . $unit;
                break;
        }
        return $result;
    }
}

if (!function_exists('base64_image_format')) {
    /**
     * base64图片信息格式化
     * @param string $base64_str
     * @return false|array
     * @author mosquito <zwj1206_hi@163.com>
     */
    function base64_image_format(string $base64_str)
    {
        $image_arr = explode(',', $base64_str);
        $image_str = base64_decode(array_pop($image_arr));
        if (!$image_str) {
            return false;
        }
        $image_info = getimagesizefromstring($image_str);
        if (!$image_info) {
            return false;
        }
        //获取图片后缀
        $suffix_list = [
            1 => 'gif', 2 => 'jpg', 3 => 'png', 4 => 'swf', 5 => 'psd',
            6 => 'bmp', 7 => 'tiff', 8 => 'tiff', 9 => 'jpc', 10 => 'jp2',
            11 => 'jpf', 12 => 'jb2', 13 => 'swc', 14 => 'aiff', 15 => 'wbmp',
            16 => 'xbm',
        ];
        $suffix = $suffix_list[$image_info[2]] ?: 'jpg';

        return [
            'base64_str' => $base64_str,
            'image_str' => $image_str,
            'image_suffix' => $suffix,
            'image_w' => $image_info[0],
            'image_h' => $image_info[1],
            'image_size' => strlen($image_str),
        ];
    }
}

if (!function_exists('xml_to_array')) {
    /**
     * xml字符串转数组
     * @param string $str
     * @return array
     * @author mosquito <zwj1206_hi@163.com>
     */
    function xml_to_array(string $str)
    {
        return json_decode(json_encode(simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
}

if (!function_exists('array_to_xml')) {
    /**
     * 数组转xml字符串
     * @param array $arr
     * @return string
     * @author mosquito <zwj1206_hi@163.com>
     */
    function array_to_xml(array $arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<{$key}>{$val}</{$key}>";
            } else {
                $xml .= "<{$key}><![CDATA[{$val}]]></{$key}>";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}

if (!function_exists('spec_format')) {
    /**
     * 规格格式化，请注意格式
     * 例：颜色：黑色，白色，红色；大小：32GB，64GB，128GB
     * @param string $str
     * @return array|false
     * @author mosquito <zwj1206_hi@163.com>
     */
    function spec_format(string $str)
    {
        //转换成需要的规格数据
        $spec_str = trim($str);
        $spec_str = preg_replace([
            '/：/',
            '/，/',
            '/；/',
            '/\s+/u',
        ], [
            ':',
            ',',
            ';',
            '',
        ], $str);
        if (!preg_match_all('/(([^:;]+):([^:;]+))/u', $spec_str, $matches)) {
            return false;
        }
        if (!$matches[2] || !$matches[3]) {
            return false;
        }
        //
        $spec_arr = [];
        foreach ($matches[2] as $key => $value) {
            $spec_id = $key;
            $spec_value_str = $matches[3][$key];
            //
            $spec_values_func = function () use ($spec_id, $spec_value_str) {
                $spec_value_arr = explode(',', $spec_value_str);
                $spec_values = [];
                foreach ($spec_value_arr as $key => $value) {
                    $spec_value_id = $key;
                    $spec_values[] = [
                        'spec_id' => $spec_id,
                        'spec_value_id' => $spec_value_id,
                        'spec_value' => $value,
                    ];
                }
                return $spec_values;
            };
            $spec_arr[] = [
                'spec_id' => $spec_id,
                'spec_name' => $value,
                'spec_values' => $spec_values_func(),
            ];
        }
        //组合规格
        $group_spec_func = function ($spec_arr, &$back_arr, $group = []) use (&$group_spec_func) {
            $first = array_shift($spec_arr);
            if ($first) {
                foreach ($first['spec_values'] as $value) {
                    $value['key'] = implode(':', [$value['spec_id'], $value['spec_value_id']]);
                    $new_group_arr = $group;
                    $new_group_arr[] = $value;
                    if ($spec_arr) {
                        $group_spec_func($spec_arr, $back_arr, $new_group_arr);
                    } else {
                        $back_arr[] = [
                            'key' => implode('-', array_column($new_group_arr, 'key')),
                            'name' => implode('-', array_column($new_group_arr, 'spec_value')),
                        ];
                    }
                }
            }
        };
        $spec_group_arr = [];
        $group_spec_func($spec_arr, $spec_group_arr);
        return [
            'spec_str' => $spec_str,
            'spec_arr' => $spec_arr,
            'spec_group_arr' => $spec_group_arr,
        ];
    }
}

if (!function_exists('distance_sql')) {
    /**
     * 距离sql
     * @param float $longitude
     * @param float $latitude
     * @param string $lng_field
     * @param string $lat_field
     * @return string
     * @author mosquito <zwj1206_hi@163.com>
     */
    function distance_sql(float $longitude = 0, float $latitude = 0, $lng_field = 'longitude', $lat_field = 'latitude')
    {
        $longitude = round($longitude, 6);
        $latitude = round($latitude, 6);
        return "round(acos(sin(({$latitude} * 3.1415) / 180) * sin(({$lat_field} * 3.1415) / 180) + cos(({$latitude} * 3.1415) / 180) * cos(({$lat_field} * 3.1415) / 180) * cos( ({$longitude} * 3.1415) / 180 - ({$lng_field} * 3.1415) / 180)) * 6378.137, 3)";
    }
}

if (!function_exists('list_to_tree')) {
    /**
     * 列表转树
     * @param array $items
     * @param string $id
     * @param string $pid
     * @param string $children
     * @return array
     * @author mosquito <zwj1206_hi@163.com>
     */
    function list_to_tree(array $items, string $id = 'id', string $pid = 'parent_id', string $children = 'children')
    {
        $new_items = [];
        foreach ($items as $item) {
            $new_items[$item[$id]] = $item;
        }
        $items = $new_items;
        //
        $tree = [];
        foreach ($items as $key => $item) {
            if ((is_numeric($item[$pid]) || is_string($item[$pid])) && !isset($items[$item[$pid]])) {
                $tree[] = &$items[$key];
            } else {
                $items[$item[$pid]][$children][] = &$items[$key];
            }
        }
        return $tree;
    }
}

if (!function_exists('tree_to_list')) {
    /**
     * 树转列表
     * @param array $tree
     * @param string $children
     * @return array
     * @author mosquito <zwj1206_hi@163.com>
     */
    function tree_to_list(array $tree, string $children = 'children')
    {
        $func = function ($tree, $children, &$lists) use (&$func) {
            foreach ($tree as $value) {
                $item = $value;
                unset($item[$children]);
                $lists[] = $item;
                //
                if ($value[$children]) {
                    $func($value[$children], $children, $lists);
                }
            }
        };
        $lists = [];
        $func($tree, $children, $lists);
        return $lists;
    }
}

if (!function_exists('base64_urlencode')) {
    /**
     * base64_urlencode
     * @param string $str
     * @return string
     * @author mosquito <zwj1206_hi@163.com>
     */
    function base64_urlencode(string $str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
}

if (!function_exists('base64_urldecode')) {
    /**
     * base64_urldecode
     * @param string $str
     * @return string|false
     * @author mosquito <zwj1206_hi@163.com>
     */
    function base64_urldecode(string $str)
    {
        return base64_decode(str_pad(strtr($str, '-_', '+/'), strlen($str) % 4, '=', STR_PAD_RIGHT));
    }
}

if (!function_exists('split_name')) {
    /**
     * 分割姓名
     * @param string $name
     * @return bool|array
     * @author mosquito <zwj1206_hi@163.com>
     */
    function split_name(string $name)
    {
        $single_array = [
            '赵', '钱', '孙', '李', '周', '吴', '郑', '王', '冯', '陈', '楮', '卫', '蒋', '沈', '韩', '杨',
            '朱', '秦', '尤', '许', '何', '吕', '施', '张', '孔', '曹', '严', '华', '金', '魏', '陶', '姜',
            '戚', '谢', '邹', '喻', '柏', '水', '窦', '章', '云', '苏', '潘', '葛', '奚', '范', '彭', '郎',
            '鲁', '韦', '昌', '马', '苗', '凤', '花', '方', '俞', '任', '袁', '柳', '酆', '鲍', '史', '唐',
            '费', '廉', '岑', '薛', '雷', '贺', '倪', '汤', '滕', '殷', '罗', '毕', '郝', '邬', '安', '常',
            '乐', '于', '时', '傅', '皮', '卞', '齐', '康', '伍', '余', '元', '卜', '顾', '孟', '平', '黄',
            '和', '穆', '萧', '尹', '姚', '邵', '湛', '汪', '祁', '毛', '禹', '狄', '米', '贝', '明', '臧',
            '计', '伏', '成', '戴', '谈', '宋', '茅', '庞', '熊', '纪', '舒', '屈', '项', '祝', '董', '梁',
            '杜', '阮', '蓝', '闽', '席', '季', '麻', '强', '贾', '路', '娄', '危', '江', '童', '颜', '郭',
            '梅', '盛', '林', '刁', '锺', '徐', '丘', '骆', '高', '夏', '蔡', '田', '樊', '胡', '凌', '霍',
            '虞', '万', '支', '柯', '昝', '管', '卢', '莫', '经', '房', '裘', '缪', '干', '解', '应', '宗',
            '丁', '宣', '贲', '邓', '郁', '单', '杭', '洪', '包', '诸', '左', '石', '崔', '吉', '钮', '龚',
            '程', '嵇', '邢', '滑', '裴', '陆', '荣', '翁', '荀', '羊', '於', '惠', '甄', '麹', '家', '封',
            '芮', '羿', '储', '靳', '汲', '邴', '糜', '松', '井', '段', '富', '巫', '乌', '焦', '巴', '弓',
            '牧', '隗', '山', '谷', '车', '侯', '宓', '蓬', '全', '郗', '班', '仰', '秋', '仲', '伊', '宫',
            '宁', '仇', '栾', '暴', '甘', '斜', '厉', '戎', '祖', '武', '符', '刘', '景', '詹', '束', '龙',
            '叶', '幸', '司', '韶', '郜', '黎', '蓟', '薄', '印', '宿', '白', '怀', '蒲', '邰', '从', '鄂',
            '索', '咸', '籍', '赖', '卓', '蔺', '屠', '蒙', '池', '乔', '阴', '郁', '胥', '能', '苍', '双',
            '闻', '莘', '党', '翟', '谭', '贡', '劳', '逄', '姬', '申', '扶', '堵', '冉', '宰', '郦', '雍',
            '郤', '璩', '桑', '桂', '濮', '牛', '寿', '通', '边', '扈', '燕', '冀', '郏', '浦', '尚', '农',
            '温', '别', '庄', '晏', '柴', '瞿', '阎', '充', '慕', '连', '茹', '习', '宦', '艾', '鱼', '容',
            '向', '古', '易', '慎', '戈', '廖', '庾', '终', '暨', '居', '衡', '步', '都', '耿', '满', '弘',
            '匡', '国', '文', '寇', '广', '禄', '阙', '东', '欧', '殳', '沃', '利', '蔚', '越', '夔', '隆',
            '师', '巩', '厍', '聂', '晁', '勾', '敖', '融', '冷', '訾', '辛', '阚', '那', '简', '饶', '空',
            '曾', '毋', '沙', '乜', '养', '鞠', '须', '丰', '巢', '关', '蒯', '相', '查', '后', '荆', '红',
            '游', '竺', '权', '逑', '盖', '益', '桓', '公', '仉', '督', '晋', '楚', '阎', '法', '汝', '鄢',
            '涂', '钦', '岳', '帅', '缑', '亢', '况', '后', '有', '琴', '归', '海', '墨', '哈', '谯', '笪',
            '年', '爱', '阳', '佟', '商', '牟', '佘', '佴', '伯', '赏',
        ];
        $double_array = [
            '万俟', '司马', '上官', '欧阳', '夏侯', '诸葛', '闻人', '东方', '赫连', '皇甫', '尉迟', '公羊',
            '澹台', '公冶', '宗政', '濮阳', '淳于', '单于', '太叔', '申屠', '公孙', '仲孙', '轩辕', '令狐',
            '锺离', '宇文', '长孙', '慕容', '鲜于', '闾丘', '司徒', '司空', '丌官', '司寇', '子车', '微生',
            '颛孙', '端木', '巫马', '公西', '漆雕', '乐正', '壤驷', '公良', '拓拔', '夹谷', '宰父', '谷梁',
            '段干', '百里', '东郭', '南门', '呼延', '羊舌', '梁丘', '左丘', '东门', '西门', '南宫',
        ];

        //
        $double_name = mb_substr($name, 0, 2);
        if (in_array($double_name, $double_array)) {
            return [
                'double',
                $double_name,
                mb_substr($name, 2),
            ];
        }
        $single_name = mb_substr($name, 0, 1);
        if (in_array($single_name, $single_array)) {
            return [
                'single',
                $single_name,
                mb_substr($name, 1),
            ];
        }
        return false;
    }
}

if (!function_exists('gridview')) {
    /**
     * 九宫格坐标
     * @param int $grid_count
     * @param int $size
     * @param int $distance
     * @return array|false
     * @author mosquito <zwj1206_hi@163.com>
     */
    function gridview(int $grid_count = 9, int $size = 640, int $distance = 16)
    {
        if ($grid_count < 1 || $grid_count > 9) {
            return false;
        }
        $bg_w = $bg_h = $size;

        //
        $g_w_cmax = 1;
        $g_w_list = [];
        if ($grid_count == 1) {
            $g_w_cmax = 1;
        } elseif ($grid_count >= 2 && $grid_count <= 4) {
            $g_w_cmax = 2;
        } elseif ($grid_count >= 5 && $grid_count <= 9) {
            $g_w_cmax = 3;
        }
        $i = ceil($grid_count / $g_w_cmax);
        while ($i) {
            array_push($g_w_list, $i * $g_w_cmax <= $grid_count ? $g_w_cmax : $grid_count % $g_w_cmax);
            $i -= 1;
        }

        //
        $dst_point_arr = [];
        $g_w_count = count($g_w_list);
        $imgw = ($bg_w - ($g_w_cmax + 1) * $distance) / $g_w_cmax;
        $ih = ($bg_h - ($g_w_cmax - 1) * $distance - $g_w_count * $imgw) / 2;
        $g_w_c = 0;
        foreach ($g_w_list as $key => $value) {
            $temp_v = 0;
            while ($temp_v < $value) {
                $iw = ($bg_w - $value * $imgw - ($value - 1) * $distance) / 2;
                $dst_point_arr[] = [
                    'dst_x' => $iw + $temp_v * ($imgw + $distance),
                    'dst_y' => $ih + $g_w_c * ($imgw + $distance),
                    'dst_w' => $imgw,
                    'dst_h' => $imgw,
                ];
                $temp_v++;
            }
            $g_w_c += 1;
        }
        return $dst_point_arr;
    }
}

if (!function_exists('imageroundcorner')) {
    /**
     * 图片圆角处理
     * @param resource|GdImage $image
     * @param int $w
     * @param int $h
     * @param int $r
     * @author mosquito <zwj1206_hi@163.com>
     */
    function imageroundcorner($image, int $w, int $h, int $r)
    {
        $img = imagecreatetruecolor($w, $h);
        imagesavealpha($img, true);
        imagefill($img, 0, 0, imagecolorallocatealpha($img, 255, 255, 255, 127));
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb_color = imagecolorat($image, $x, $y); //获取像素索引
                if (($x >= $r && $x <= ($w - $r)) || ($y >= $r && $y <= ($h - $r))) {
                    //不在四角的范围内,直接画
                    imagesetpixel($img, $x, $y, $rgb_color);
                } else {
                    //在四角的范围内选择画
                    //上左
                    $y_x = $r; //圆心X坐标
                    $y_y = $r; //圆心Y坐标
                    if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
                        imagesetpixel($img, $x, $y, $rgb_color);
                    }
                    //上右
                    $y_x = $w - $r; //圆心X坐标
                    $y_y = $r; //圆心Y坐标
                    if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
                        imagesetpixel($img, $x, $y, $rgb_color);
                    }
                    //下左
                    $y_x = $r; //圆心X坐标
                    $y_y = $h - $r; //圆心Y坐标
                    if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
                        imagesetpixel($img, $x, $y, $rgb_color);
                    }
                    //下右
                    $y_x = $w - $r; //圆心X坐标
                    $y_y = $h - $r; //圆心Y坐标
                    if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
                        imagesetpixel($img, $x, $y, $rgb_color);
                    }
                }
            }
        }
        return $img;
    }
}

if (!function_exists('imagegridview')) {
    /**
     * 生成九宫格图片
     * @param array $img_arr 图片资源路径
     * @param int $size 图片大小
     * @param int $distance 小图间距
     * @param int $radius 小图圆角半径
     * @author mosquito <zwj1206_hi@163.com>
     */
    function imagegridview(array $img_arr, int $size = 640, int $distance = 16, int $radius = 8)
    {
        //生成九宫格坐标
        $point_arr = gridview(count($img_arr), $size, $distance);
        if ($point_arr === false) {
            throw new \Exception('生成九宫格坐标失败');
        }
        //背景画布
        $bg_image = imagecreatetruecolor($size, $size);
        if ($bg_image === false) {
            throw new \Exception('创建背景画布失败');
        }
        imagefill($bg_image, 0, 0, imagecolorallocatealpha($bg_image, 242, 242, 242, 0));
        //拼装图片
        foreach ($img_arr as $key => $imgurl) {
            $image_str = file_get_contents($imgurl);
            if ($image_str === false) {
                continue;
            }
            $base64_str = base64_encode($image_str);
            $image_info = base64_image_format($base64_str);
            if (!$image_info) {
                continue;
            }
            $func = 'imagecreatefrom' . ($image_info['image_suffix'] == 'jpg' ? 'jpeg' : $image_info['image_suffix']);
            if (!function_exists($func)) {
                continue;
            }
            $imgres = @$func($imgurl);
            if ($imgres === false) {
                continue;
            }
            if ($radius > 0) {
                $imgres = imageroundcorner($imgres, $image_info['image_w'], $image_info['image_h'], $radius);
            }
            $dst_point = array_shift($point_arr);
            imagecopyresampled($bg_image, $imgres, $dst_point['dst_x'], $dst_point['dst_y'], 0, 0, $dst_point['dst_w'], $dst_point['dst_h'], $image_info['image_w'], $image_info['image_h']);
            imagedestroy($imgres);
        }
        return $bg_image;
    }
}

if (!function_exists('mobile_encode')) {

    /**
     * 手机号加密
     * @param string $mobile
     * @return string
     * @author mosquito <zwj1206_hi@163.com>
     */
    function mobile_encode(string $mobile)
    {
        return substr_replace($mobile, '****', 3, 4);
    }
}

if (!function_exists('combine_page')) {
    /**
     * 获取指定数量组合分页信息
     * @param int $page
     * @param int $limit
     * @param array $counts
     * @author mosquito <zwj1206_hi@163.com>
     */
    function combine_page(int $page, int $limit, array $counts)
    {
        $page < 1 && $page = 1;
        $start = ($page - 1) * $limit;
        $end = $page * $limit - 1;
        $pageinfo = null;

        //
        $pre = 0;
        foreach ($counts as $key => $val) {
            $last = $pre + $val - 1;
            if ($pre <= $start) {
                if ($end <= $last) {
                    $count = ($end - $start) + 1;
                } else {
                    $count = ($last - $start) + 1;
                }
            } else {
                if ($end <= $last) {
                    $count = ($end - $pre) + 1;
                } else {
                    $count = ($last - $pre) + 1;
                }
            }
            $skip = $start - $pre;
            $skip < 0 && $skip = 0;
            if ($count > 0) {
                $pageinfo[$key] = [
                    'skip' => $skip,
                    'count' => $count,
                ];
            }
            $pre = $last + 1;
        }
        return $pageinfo;
    }
}

if (!function_exists('array_custom_sort')) {
    /**
     * 自定义数组排序（包含多维）
     * @param array $arr
     * @param string $func 支持部分函数，例如:sort,ksort,rsort,krsort
     * @author mosquito <zwj1206_hi@163.com
     */
    function array_custom_sort(array $arr, string $func = 'ksort')
    {
        $sort = function (array &$arr) use (&$sort, $func) {
            $func($arr);
            foreach ($arr as &$value) {
                if (is_array($value)) {
                    $sort($value);
                }
            }
        };
        $sort($arr);
        return $arr;
    }
}

if (!function_exists('rsa_private_key_format')) {
    /**
     * rsa私钥格式化
     * @param string $private_key
     * @author mosquito <zwj1206_hi@163.com
     */
    function rsa_private_key_format(string $private_key)
    {
        return "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($private_key, 64, "\n", true) . "\n-----END RSA PRIVATE KEY-----";
    }
}

if (!function_exists('rsa_public_key_format')) {
    /**
     * rsa公钥格式化
     * @param string $public_key
     * @author mosquito <zwj1206_hi@163.com
     */
    function rsa_public_key_format(string $public_key)
    {
        return "-----BEGIN PUBLIC KEY-----\n" . wordwrap($public_key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
    }
}
