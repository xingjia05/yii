<?php

namespace backend\controllers;

use yii;

header('content-type:application:json;charset=utf8');  
header('Access-Control-Allow-Origin:*');  
header('Access-Control-Allow-Methods:POST');  
header('Access-Control-Allow-Headers:x-requested-with,content-type');  

/**
 * Image controller
 */
class ImageController extends BaseController {

    public $enableCsrfValidation = false;
    
    public static $imagePrefix = 'http://39.106.49.127:8081/';
    
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * upload image.
     */
    public function actionUpload() {
        file_put_contents('/tmp/11.log', json_encode($_FILES).date('Y-m-d H:i:s'));
        //检验post的最大上传的大小
        $POST_MAX_SIZE = ini_get('post_max_size');
        $unit = strtoupper(substr($POST_MAX_SIZE, -1));
        
        /* This will trigger an uploadError event in SWFUpload */
        $multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));
        if ((int) $_SERVER['CONTENT_LENGTH'] > $multiplier * (int) $POST_MAX_SIZE && $POST_MAX_SIZE) {
            header("HTTP/1.1 500 Internal Server Error");
            echo "fai:超过最大允许后的尺寸";
            exit(0);
        }
        
        /* 配置信息 */
        $filenameset = false; //此处设置保存文件的文件名，true为以月日和时间加随机数为文件名，其他为指定编号为文件名
        $upbool = 1;          //设置是否开始上传功能。0为关闭上传，其他为正常上传
        $tipmsg = "为节省空间暂时关闭演示程序上传功能，如有不周之处，还请原谅"; //设置关闭上传返回的信息
        $dir_file = date("Ymd"); //获取当前时间
//        $imgpath  = "upload/image/";  //图片保存的路径,其格式必须如此
        $imgpath  = "";  //图片保存的路径,其格式必须如此
        $rootfoldername = "null";   //如果你把整个文件夹(upfile)放到你网站中的话就无需更改此配置，如果你把upfile文件夹下文件和文件夹放到网站中请填写为:null 。例如：$rootfoldername="null".如果你更改了整个文件夹(upfile)的名称，请改为你更改的名称。
//        $save_path      = getcwd() . '/' . $imgpath . $dir_file . '/';    // 保存的路径
        $save_path      = '/home/work/image' . '/' . $dir_file . '/';    // 保存的路径;
        $upload_name    = "files";
        $max_file_size_in_bytes = 2147483647; //2GB in bytes 最大上传的文件大小为2G
        $extension_whitelist = array("jpg", "jpeg", "gif", "png", "bmp"); // 上传允许的文件扩展名称
        $valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-'; //允许在文件名字符(在一个正则表达式格式)
        
        $watermark     = FALSE; //是否启用水印，true开启，false关闭
        /* 
         * 水印位置，有10种状态，0为随机位置；
         * 1为顶端居左，2为顶端居中，3为顶端居右；
         * 4为中部居左，5为中部居中，6为中部居右；
         * 7为底端居左，8为底端居中，9为底端居右；
         */
        $waterPos       = 9;       
        $waterImage     = "./images/logo.png"; //图片水印，即作为水印的图片，暂只支持GIF,JPG,PNG格式；
        $waterText      = "";    //文字水印，即把文字作为为水印，支持ASCII码，不支持中文；
        $watertextFont  = 5;   //文字大小，值为1、2、3、4或5，默认为5；
        $watertextColor = "#FF0000";      //文字颜色，值为十六进制颜色值，默认为#FF0000(红色)；
        
        /* 其他的验证 */
        $maxFileNameLength = 260;
        $file_name         = "";
        $file_extension    = "";
        $uploadErrors = array(
            0 => "没有错误,文件上传有成效",
            1 => "上传的文件的upload_max_filesize指令在你只有超过",
            2 => "上传的文件的超过MAX_FILE_SIZE指示那个没有被指定在HTML表单",
            3 => "未竟的上传的文件上传",
            4 => "没有文件被上传",
            6 => "错过一个临时文件夹"
        );
        /* 关闭上传功能 */
        if ($upbool === 0) {
            $this->HandleError("fai:" . $tipmsg);
            exit(0);
        }
        /* 验证上传  */
        if (!isset($_FILES[$upload_name])) {
            $this->HandleError("fai:没有发现上传 \$_FILES for " . $upload_name);
            exit(0);
        } else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
            $this->HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
            exit(0);
        } else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
            $this->HandleError("fai:Upload failed is_uploaded_file test.");
            exit(0);
        } else if (!isset($_FILES[$upload_name]['name'])) {
            $this->HandleError("fai:文件没有名字.");
            exit(0);
        }
        /* 当不是一张合法图片时，$width、$height、$type、$attr 的值就全都为空，以此来判断图片的真实 */
        list($width, $height, $type, $attr) = getimagesize($_FILES[$upload_name]['tmp_name']);
        if (empty($width) || empty($height) || empty($type) || empty($attr)) {
            $this->HandleError("fai:上传图片为非法内容");
            exit(0);
        }
        /* 验证这个文件的大小(警告:最大的文件支持这个代码2 GB) */
        $file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
        if (!$file_size || $file_size > $max_file_size_in_bytes) {
            $this->HandleError("fai:超过最高允许的文件的大小");
            exit(0);
        }
        if ($file_size <= 0) {
            $this->HandleError("fai:超出文件的最小大小");
            exit(0);
        }

        /* 验证文件名称(对于我们而言我们只会删除无效字符) */
        $file_name = preg_replace('/[^' . $valid_chars_regex . ']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
        if (strlen($file_name) == 0 || strlen($file_name) > $maxFileNameLength) {
            $this->HandleError("fai:无效的文件");
            exit(0);
        }
        
        /* 创建目录 */
        if (!$this->create_folders($save_path)) {
            $this->HandleError("fai:文件夹创建失败");
            exit(0);
        }
        
        /* 确认我们不会地盖写现有的一个文件 */
        if (file_exists($save_path . $file_name)) {
            $this->HandleError("fai:这个名字的文件已经存在");
            exit(0);
        }
        
        /* 验证文件扩展名 */
        $path_info = pathinfo($_FILES[$upload_name]['name']);
        $file_extension = $path_info["extension"];
        $is_valid_extension = false;
        foreach ($extension_whitelist as $extension) {
            if (strcasecmp($file_extension, $extension) == 0) {
                $is_valid_extension = true;
                break;
            }
        }
        if (!$is_valid_extension) {
            $this->HandleError("fai:无效的扩展名");
            exit(0);
        }
        $resultstring = "";
        if (is_dir($save_path)) {
            $fileName = $filenameset ? $this->createdatefilename($file_extension) : $this->CreateNextName($file_extension, $save_path);
            if (!move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path . $fileName)) {
                $this->HandleError("fai:文件移动失败");
                exit(0);
            } else {
                if ($rootfoldername !== "null") {
                    $resultstring = $rootfoldername . "/" . $imgpath . $dir_file . "/";
                } else {
                    $resultstring = $imgpath . $dir_file . "/";
                }
            }
        } else {
            if (mkdir($save_path)) {
                $fileName = $filenameset ? $this->createdatefilename($file_extension) : $this->CreateFirstName($file_extension);
                if (!move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path . $fileName)) {
                    $this->HandleError("fai:文件移动失败");
                    exit(0);
                } else {
                    if ($rootfoldername !== "null") {
                        $resultstring = $rootfoldername . "/" . $imgpath . $dir_file . "/";
                    } else {
                        $resultstring = $imgpath . $dir_file . "/";
                    }
                }
            } else {
                $this->HandleError("fai:创建目录失败");
                exit(0);
            }
        }
        if ($watermark) {
//            $errstr = imageWaterMark($resultstring . $fileName, $waterPos, $waterImage, $waterText, $watertextFont, $watertextColor);
        }
        return array('image' => self::$imagePrefix . $resultstring . $fileName);
    }

    function HandleError($message) {
	echo trim($message);
    }

    //参数是文件的扩展名称
    function CreateFirstName($file_extension) {
        $num = date('mdHis') . rand(1, 100);
        $fileName = $num . "." . $file_extension;
        return $fileName;
    }

    /*
     * 参数是文件的扩展名称
     */
    function CreateNextName($file_extension, $file_dir) {
        //在文件的目录下找最大的;
        $fileName_arr = scandir($file_dir, 1);
        $fileName = $fileName_arr[0];
        $aa = floatval($fileName);
        $num = 0;
        $num = (1 + $aa);
        if (empty($aa)) {
            $num = date('mdHis') . rand(1, 100);
        }
        return $num . "." . $file_extension;
    }

    /*
     * 返回以时间组合的文件名
     */
    function createdatefilename($file_extension) {
        date_default_timezone_set('PRC');
        return date('mdHis') . rand(1, 100) . "." . $file_extension;
    }

    /* 
     * 判断是否存在目录，不存在递归创建目录
     */
    function create_folders($dir) {
        return is_dir($dir) or ( $this->create_folders(dirname($dir)) and mkdir($dir, 0777));
    }

}
