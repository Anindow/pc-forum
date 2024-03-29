<?php
/*
/**
 *  Created by PhpStorm.
 *  User: SATYAJIT
 *  Date: 10/03/2020
 *  Time: 2:13 AM

 *  Used to write in .env file
 *  @param
 *  $data as array of .env key & value
 *  @return nothing
 */

function envu($data = array())
{
    foreach ($data as $key => $value) {
        if (env($key) === $value) {
            unset($data[$key]);
        }
    }

    if (!count($data)) {
        return false;
    }

    // write only if there is change in content

    $env = file_get_contents(base_path() . '/.env');
    $env = explode("\n", $env);
    foreach ((array)$data as $key => $value) {
        foreach ($env as $env_key => $env_value) {
            $entry = explode("=", $env_value, 2);
            if ($entry[0] === $key) {
                $env[$env_key] = $key . "=" . (is_string($value) ? '"' . $value . '"' : $value);
            } else {
                $env[$env_key] = $env_value;
            }
        }
    }
    $env = implode("\n", $env);
    file_put_contents(base_path() . '/.env', $env);
    return true;
}


/**
 * @param $key
 * @return mixed
 */
function settings($key)
{
    static $settings;

    if (is_null($settings)) {
        $settings = \Illuminate\Support\Facades\Cache::remember('settings', 24 * 60, function () {
            return \App\Models\Setting::pluck('value', 'key')->toArray();
        });
    }

    return (is_array($key)) ? \Illuminate\Support\Arr::only($settings, $key) : $settings[$key];
}

///////////////// File helper function start ///////////////////////////

function imagePath()
{
//    $data['gateway'] = [
//        'path' => 'assets/images/gateway',
//        'size' => '800x800',
//    ];
//    $data['verify'] = [
//        'withdraw' => [
//            'path' => 'assets/images/verify/withdraw'
//        ],
//        'deposit' => [
//            'path' => 'assets/images/verify/deposit'
//        ]
//    ];
    $data['image'] = [
        'default' => 'assets/images/profile/default.png',
    ];
    $data['withdraw'] = [
        'method' => [
            'path' => 'assets/images/withdraw/method',
            'size' => '800x800',
        ]
    ];
    $data['language'] = [
        'path' => 'assets/images/lang',
        'size' => '64x64'
    ];
    $data['favicon'] = [
        'size' => '128x128',
    ];
    $data['profile'] = [
        'path' => 'assets/images/profile',
        'default' => 'assets/images/profile/default.png',
        'size' => '88x88',
    ];
    $data['brand'] = [
        'path' => 'assets/images/brand',
//        'size' => '200x200',
        'size' => '81x58',
    ];
    $data['slider'] = [
        'path' => 'assets/images/slider',
//        'size' => '200x200',
        'size' => '1400x600',
    ];
    $data['banner'] = [
        'path' => 'assets/images/banner',
//        'size' => '200x200',
        'size' => '470x332',
    ];
    $data['document'] = [
        'path' => 'assets/images/document',
    ];
    $data['product'] = [
        'path' => 'assets/images/product',
//        'default' => 'assets/images/profile/default.png',
        'size' => '679x731',
    ];

    return $data;
}

/**
 *
 * @param $image
 * @param string $clean
 * @return string
 */
function get_image($image, $clean = '')
{
    return file_exists($image) && is_file($image) ? asset($image) . $clean : asset('assets/images/default.png');
}

function getImage($image, $size = null)
{
//    return asset($image);
    $clean = '';
    $size = $size ? $size : 'undefined';
    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    } else {
        return 'http://placehold.it/' . $size;
//        return route('placeholderImage', $size);
    }
}

/**
 * Used to global image uploader
 *
 * @param $file
 * @param $location
 * @param null $size
 * @param null $old
 * @param null $thumb
 * @return string
 * @throws Exception
 */
function uploadImage($file, $location, $size = null, $old = null, $thumb = null)
{

    $path = makeDirectory($location);
    if (!$path) throw new Exception(trans('trans.file_could_not_been_created'));
    if (!empty($old)) {
        removeFile($location . '/' . $old);
        removeFile($location . '/thumb_' . $old);
    }

    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();


    $image = Image::make($file);

    if (!empty($size)) {
        $size = explode('x', strtolower($size));
        $image->resize($size[0], $size[1]);
    }
    $image->save($location . '/' . $filename);


    if (!empty($thumb)) {
        $thumb = explode('x', $thumb);
        Image::make($file)->resize($thumb[0], $thumb[1])->save($location . '/thumb_' . $filename);
    }
    return $filename;
}


function makeDirectory($path)
{
    if (file_exists($path)) return true;
    return mkdir($path, 0755, true);
}

function removeFile($path)
{
    return file_exists($path) && is_file($path) ? @unlink($path) : false;
}

/////////////////////////////////////////

/**
 * @param $image_name
 * @return string to concat
 */
function profileImagePath($image_name = null)
{
    return asset('images/profiles/' . $image_name);
}

/**
 * Use to find remove image path
 * @param string $path
 * @param null $image_name
 * @return string
 */
function removeImagePath($path = '/images/', $image_name = null)
{
    return getcwd() . $path . $image_name;
}


///////////////// File helper function end ///////////////////////////

///////////////// Date helper function start ///////////////////////////

/*
 *  Used to check whether date is valid or not
 *  @param
 *  $date as timestamp or date variable
 *  @return true if valid date, else if not
 */

function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

/*
 *  Used to get date with start midnight time
 *  @param
 *  $date as timestamp or date variable
 *  @return date with start midnight time
 */

function getStartOfDate($date)
{
    return date('Y-m-d', strtotime($date)) . ' 00:00';
}

/*
 *  Used to get date with end midnight time
 *  @param
 *  $date as timestamp or date variable
 *  @return date with end midnight time
 */

function getEndOfDate($date)
{
    return date('Y-m-d', strtotime($date)) . ' 23:59';
}

/*
 *  Used to get date in desired format
 *  @return date format
 */

function getDateFormat()
{
    if (config('config.date_format') === 'DD-MM-YYYY') {
        return 'd-m-Y';
    } elseif (config('config.date_format') === 'MM-DD-YYYY') {
        return 'm-d-Y';
    } elseif (config('config.date_format') === 'DD-MMM-YYYY') {
        return 'd-M-Y';
    } elseif (config('config.date_format') === 'MMM-DD-YYYY') {
        return 'M-d-Y';
    } else {
        return 'd-m-Y';
    }
}

/*
 *  Used to convert date for database
 *  @param
 *  $date as date
 *  @return date
 */

function toDate($date)
{
    if (!$date) {
        return;
    }

    return date('Y-m-d', strtotime($date));
}

/*
 *  Used to convert date in desired format
 *  @param
 *  $date as date
 *  @return date
 */

function showDate($date)
{
    if (!$date) {
        return;
    }

    $date_format = getDateFormat();
    return date($date_format, strtotime($date));
}

/*
 *  Used to convert time in desired format
 *  @param
 *  $datetime as datetime
 *  @return datetime
 */

function showDateTime($time = '')
{
    if (!$time) {
        return;
    }

    $date_format = getDateFormat();
    if (config('config.time_format') === 'H:mm') {
        return date($date_format . ',H:i', strtotime($time));
    } else {
        return date($date_format . ',h:i a', strtotime($time));
    }
}

/*
 *  Used to convert time in desired format
 *  @param
 *  $time as time
 *  @return time
 */

function showTime($time = '')
{
    if (!$time) {
        return;
    }

    if (config('config.time_format') === 'H:mm') {
        return date('H:i', strtotime($time));
    } else {
        return date('h:i a', strtotime($time));
    }
}

/*
 *  Used to convert date &time in desired format
 *  @param
 *  $time as time
 *  @return time
 */

function showDiffForHuman($time = '')
{
    if (!$time) {
        return;
    }
    return \Illuminate\Support\Carbon::parse($time)->diffForHumans();
}

/**
 * @param string $time
 * @return string|void
 */
function showDayTimeDate($time = '')
{
    if (!$time) {
        return;
    }
    return \Illuminate\Support\Carbon::parse($time)->isoFormat('LLLL'); // 'Tuesday, July 23, 2019 2:51 PM';
}

///////////////// Date helper function end ///////////////////////////

//////////////// String helper function start //////////////////////

/*
 *  Used to convert slugs into human readable words
 *  @param
 *  $word as string
 *  @return string
 */

function toWord($word)
{
    $word = str_replace('_', ' ', $word);
    $word = str_replace('-', ' ', $word);
    $word = ucwords($word);
    return $word;
}

/*
 *  Used to generate random string of certain lenght
 *  @param
 *  $length as numeric
 *  $type as optional param, can be token or password or username. Default is token
 *  @return random string
 */

function randomString($length, $type = 'token')
{
    if ($type === 'password') {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    } elseif ($type === 'username') {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    } else {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    }
    $token = substr(str_shuffle($chars), 0, $length);
    return $token;
}

/*
 *  Used to whether string contains unicode
 *  @param
 *  $string as string
 *  @return boolean
 */

function checkUnicode($string)
{
    if (strlen($string) != strlen(utf8_decode($string))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Used to generate slug from string
 *
 * @param $string
 * @return string
 */
function createSlug($string)
{
    return Illuminate\Support\Str::slug($string);
}

/**
 * Used long paragraph to short. default is 120
 *
 * @param $string
 * @param int $length
 * @return string
 */
function shortDescription($string, $length = 120)
{
    return Illuminate\Support\Str::limit($string, $length);
}

/*
 *  Used to remove script tag from input
 *
 *  @param
 *  $string as string
 *  @return slug
 */

function scriptStripper($string)
{
    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $string);
}

/////////////////////////////// String helper function end /////////////////////////////////////////////////////

/////////////////////////////// Select helper function starts //////////////////////////////////////////////////
/*
 *  Used to generate select option for vue.js multiselect plugin
 *  @param
 *  $data as array of key & value pair
 *  @return select options
 */

function generateSelectOption($data)
{
    $options = array();
    foreach ($data as $key => $value) {
        $options[] = ['name' => $value, 'id' => $key];
    }
    return $options;
}

/*
 *  Used to generate translated select option for vue.js multiselect plugin
 *  @param
 *  $data as array of key & value pair
 *  @return select options
 */

function generateTranslatedSelectOption($data)
{
    $options = array();
    foreach ($data as $key => $value) {
        $options[] = ['name' => trans('list.' . $value), 'id' => $value];
    }
    return $options;
}

/*
 *  Used to generate select option for default select box
 *  @param
 *  $data as array of key & value pair
 *  @return select options
 */

function generateNormalSelectOption($data)
{
    $options = array();
    foreach ($data as $key => $value) {
        $options[] = ['text' => $value, 'value' => $key];
    }
    return $options;
}

/*
 *  Used to generate select option for default select box where value is same as text
 *  @param
 *  $data as array of key & value pair
 *  @return select options
 */

function generateNormalSelectOptionValueOnly($data)
{
    $options = array();
    foreach ($data as $value) {
        $options[] = ['text' => $value, 'value' => $value];
    }
    return $options;
}

//////////////////////////////////////////////////////////////////////////////////// Select helper function ends

/*
 *  Used to round number
 *  @param
 *  $number as numeric value
 *  $decimal_place as integer for round precision
 *  @return number
 */

function formatNumber($number, $decimal_place = 2)
{
    return round($number, $decimal_place);
}

////////////////////////////////////////////////////////////////////////////////////// IP helper function starts

/*
 *  Used to check whether IP is in range
 */

function ipRange($network, $ip)
{
    $network = trim($network);
    $orig_network = $network;
    $ip = trim($ip);
    if ($ip === $network) {
        return true;
    }
    $network = str_replace(' ', '', $network);
    if (strpos($network, '*') != false) {
        if (strpos($network, '/') != false) {
            $asParts = explode('/', $network);
            $network = @ $asParts[0];
        }
        $nCount = substr_count($network, '*');
        $network = str_replace('*', '0', $network);
        if ($nCount === 1) {
            $network .= '/24';
        } elseif ($nCount === 2) {
            $network .= '/16';
        } elseif ($nCount === 3) {
            $network .= '/8';
        } elseif ($nCount > 3) {
            return true;
        }
    }

    $d = strpos($network, '-');
    if ($d === false) {
        $ip_arr = explode('/', $network);
        if (!preg_match("@\d*\.\d*\.\d*\.\d*@", $ip_arr[0], $matches)) {
            $ip_arr[0] .= ".0";
        }
        $network_long = ip2long($ip_arr[0]);
        $x = ip2long($ip_arr[1]);
        $mask = long2ip($x) === $ip_arr[1] ? $x : (0xffffffff << (32 - $ip_arr[1]));
        $ip_long = ip2long($ip);
        return ($ip_long & $mask) === ($network_long & $mask);
    } else {
        $from = trim(ip2long(substr($network, 0, $d)));
        $to = trim(ip2long(substr($network, $d + 1)));
        $ip = ip2long($ip);
        return ($ip >= $from and $ip <= $to);
    }
}

/*
 *  Used to check whether IP is valid or not
 *  @return boolean
 */

function validateIp($wl_ips)
{

    // $ip = getClientIp();
    $ip = '192.168.1.1';

    $allowedIps = array();
    foreach ($wl_ips as $wl_ip) {
        if ($wl_ip->end_ip) {
            $allowedIps[] = $wl_ip->start_ip . '-' . $wl_ip->end_ip;
        } else {
            $allowedIps[] = $wl_ip->start_ip;
        }
    }

    foreach ($allowedIps as $allowedIp) {
        if (strpos($allowedIp, '*')) {
            $range = [
                str_replace('*', '0', $allowedIp),
                str_replace('*', '255', $allowedIp)
            ];
            if (ipExistsInRange($range, $ip)) {
                return true;
            }
        } elseif (strpos($allowedIp, '-')) {
            $range = explode('-', str_replace(' ', '', $allowedIp));
            if (ipExistsInRange($range, $ip)) {
                return true;
            }
        } else {
            if (ip2long($allowedIp) === ip2long($ip)) {
                return true;
            }
        }
    }
    return false;
}

function ipExistsInRange(array $range, $ip)
{
    if (ip2long($ip) >= ip2long($range[0]) && ip2long($ip) <= ip2long($range[1])) {
        return true;
    }
    return false;
}

/*
 *  Used to get IP address of visitor
 *  @return date
 */

function getRemoteIPAddress()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'];
}

/*
 *  Used to get IP address of visitor
 *  @return IP address
 */

function getClientIp()
{
    $ips = getRemoteIPAddress();
    $ips = explode(',', $ips);
    return !empty($ips[0]) ? $ips[0] : \Request::getClientIp();
}

////////////////////////////////////////////////////////////////////////////////////////// IP helper function ends

/*
 *  Used to check mode
 *  @return boolean
 */

function isTestMode()
{
    if (!config('config.mode')) {
        return true;
    } else {
        return false;
    }
}

/*
 * get Maximum post size of server
 */

function getPostMaxSize()
{
    if (is_numeric($postMaxSize = ini_get('post_max_size'))) {
        return (int)$postMaxSize;
    }

    $metric = strtoupper(substr($postMaxSize, -1));
    $postMaxSize = (int)$postMaxSize;

    switch ($metric) {
        case 'K':
            return $postMaxSize * 1024;
        case 'M':
            return $postMaxSize * 1048576;
        case 'G':
            return $postMaxSize * 1073741824;
        default:
            return $postMaxSize;
    }
}

/*
 *  Used to get value-list json
 *  @return array
 */

function getVar($list)
{
    $file = resource_path('var/' . $list . '.json');

    return (\File::exists($file)) ? json_decode(file_get_contents($file), true) : [];
}

/*
 *  Used to get user type
 *  @return array
 */
function getProductStock($value) /////Extra
{
    switch ($value) {
        case '1':
            return 'In Stock';
        case '2':
            return 'Out of Stock';
        case '3':
            return 'Upcoming';
        case '4':
            return 'Discontinued';
        default:
            return 'Unknown Stock';
    }
}

function getUserFullName($user)
{
    return $user['first_name'] . ' ' . $user['last_name'];
}

function updateMinMaxProductPrice($product)
{
    if($product->productLinks){

        foreach ($product->productLinks as $productLink) {
            if($product->min_price == null || $product->min_price == 0){
                $product->update(['min_price' => $productLink->price]);
            }else{
                if ($productLink->price < $product->min_price) {
                    $product->update(['min_price' => $productLink->price]);
                }
            }

            if($product->max_price == null || $product->max_price == 0){
                $product->update(['max_price' => $productLink->price]);
            }else{
                if($productLink->price > $product->max_price){
                    $product->update(['max_price' => $productLink->price]);
                }
            }
        }

        return;
    }
}

/**
 * Check product links already has this shop
 *
 * @param $productLinks
 * @param $shopId
 * @return bool
 */
function checkShopUsed($existingShops, $shopId)
{
    foreach ($existingShops as $existingShop){
//        dd('$existingShop$existingShop$existingShop', $existingShop);
        if($existingShop != $shopId){
            return true;
        }
    }
    return false;
}

