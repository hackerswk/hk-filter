<?php
/**
 * Input Validate class
 *
 * @author      Ann Tsai <anntsai@weblisher.com.tw>
 * @access      public
 * @version     Release: 1.0
 */

namespace Stanleysie\HkFilter;

class InputValidate
{
    public function __construct()
    {}

    /**
     * Email格式驗證
     * 
     * @param $email Email
     * @return bool
     */
    public function emailValidate($email)
    {
        $check = (bool)preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/', $email);
        //$check = filter_var($email, FILTER_VALIDATE_EMAIL);

        return $check;
    }

    /**
     * Email格式處理
     * 
     * @param $email Email
     * @param $mode 處理模式，0:不做格式驗證，1:做Email格式驗證
     * @return array
     */
    public function emailDataHandle($email, $mode = 0)
    {
        $response = array();
        //全形轉半形
        //$email = $this->conver_str($email);
        // 去除所有空白
        $response["email"] = $this->removeSpace($email);
        // 驗證
        if ($mode == 1) {
            $response["validation"] = $this->emailValidate($email);
        }

        return $response;
    }

    /**
     * 手機號碼格式驗證
     * 
     * @param $mobile 手機號碼
     * @return bool
     */
    public function mobileValidate($mobile)
    {
        $check = (bool)preg_match('/^([0-9]{10})$/',$mobile);

        return $check;
    }

    /**
     * 手機號碼格式處理
     * 
     * @param $mobile 手機號碼
     * @param $mode 處理模式，0:不做格式驗證，1:做mobile格式驗證
     * @return array
     */
    public function mobileDataHandle($mobile, $mode = 0)
    {
        $response = array();
        //全形轉半形
        //$mobile = $this->convert_str($mobile);
        // 去dash
        //$mobile = str_replace('-', '', $mobile);
        // 去除所有空白
        $response["mobile"] = $this->removeSpace($mobile);
        if ($mode == 1) {
            $response["validation"] = $this->mobileValidate($mobile);
        }

        return $response;
    }

    // 數字驗證
    public function numberValidate($number)
    {
        $check = (bool)preg_match('/^([0-9]*)$/',$number);
        if ($check && $len > 0) {
            $check = mb_strlen($number) <= $len;
        }
        return $check;
    }

    // 數字格式處理
    public function numberDataHandle($number, $mode = 0, $len = 0)
    {
        //全形轉半形
        $number = $this->conver_str($number);
        // 去除所有空白
        $response["number"] = $this->removeSpace($number);

        if ($mode == 1) {
            $response["validation"] = $this->numberValidate($number, $len);
        }

        return $response;
    }

    /**
     * 文字內容驗證
     * 
     * @param $text 文字內容
     * @param $type1 驗證類型，0:英數，1:英文，2:數字
     * @param $type2 允許符號，0:不允許，1:允許
     * @param $allowable_tags 允許的符號
     * @param $len 限制字串長度
     * @return bool
     */
    public function textValidate($text, $type1 = 0, $type2 = 0, $allowable_tags, $len=0)
    {
        $check = true;
        if ($len > 0)
        {
            $check = (mb_strlen($number) <= $len);
        }
        // 不允許傳入內容有特殊符號
        if ($check && $type2 == 0)
        {
            $check = (bool)preg_match('/^([0-9a-zA-Z]*)$/', $text);
        }
        elseif ($check && $type2 == 1)
        {
            // 引入不允許的特殊符號
            //include('inputValidate.conf.php');
            // 排除不允許的特殊符號
            $reject_text_tags = [
                '?','>',
            ];
            foreach ($reject_text_tags as $tag) {
                $allowable_tags = str_replace($tag, '', $allowable_tags);
            }
            // 排除傳入字串中允許的特殊符號
            $pattern = "/([\w".$allowable_tags."]*)/i";
            $str = preg_replace($pattern, "", $text);
            // 檢查字串中還有沒有特殊符號
            $check = !(bool)preg_match("/^([\W]*)$/", $str);
        }
        if ($check)
        {
            $pattern = [
                ['/^([0-9a-zA-Z]*)$/', '/^([0-9a-zA-Z\W]*)$/'],
                ['/^([a-zA-Z]*)$/', '/^([a-zA-Z\W]*)$/'],
                ['/^([0-9]*)$/', '/^([0-9\W]*)$/'],
            ][$type1][$type2];
            
            $check = (bool)preg_match($pattern, $text);
        }

        return $check;
    }

    /**
     * 文字內容格式處理
     * 
     * @param $text 文字內容
     * @param $type1 驗證類型，0:英數，1:英文，2:數字
     * @param $type2 允許符號，0:不允許，1:允許
     * @param $mode 處理模式，0:不做格式驗證，1:做mobile格式驗證
     * @return array
     */
    public function textDataHandle($text, $type1 = 0, $type2 = 0, $mode = 0)
    {
        //全形轉半形
        $text = $this->conver_str($text);
        // 去除所有空白
        $response["text"] = $this->removeSpace($text);

        if ($mode == 1) {
            $response["validation"] = $this->textValidate($text);
        }

        return $response;
    }

    /**
     * uri驗證
     * 
     * @param $url 連結
     * @return bool
     */
    public function urlValidate($url)
    {

        $check = (bool)preg_match("/http[s]?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is",$url);

        return $check;
    }

    /**
     * uri格式處理
     * 
     * @param $url 連結
     * @param $mode 處理模式，0:不做格式驗證，1:做mobile格式驗證
     * @return array
     */
    public function urlDataHandle($url, $mode = 0)
    {
        $response = array();
        //全形轉半形
        //$url = $this->convert_str($url);
        // 去除所有空白
        $response["url"] = $this->removeSpace($url);

        if ($mode == 1) {
            $response["validation"] = $this->urlValidate($url);
        }

        return $response;
    }

    /**
     * 補正HTML tags, 保留$allowable_tags
     * 
     * @param $content HTML內容
     * @param $allowable_tags 允許的html tags
     * @return bool
     */
    public function fixedHtmlTags($content = '', $allowable_tags = '')
    {
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $content, $result);
        $openedtags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $content, $result);

        $closedtags = $result[1];
        $len_opened = count($openedtags);

        if (count($closedtags) != $len_opened) {
            $openedtags = array_reverse($openedtags);
            for ($i=0; $i < $len_opened; $i++) {
                if (!in_array($openedtags[$i], $closedtags)) {
                    $content .= '</'.$openedtags[$i].'>';
                } else {
                    unset($closedtags[array_search($openedtags[$i], $closedtags)]);
                }
            }
        }
        // 引入不允許的html tags
        //include('inputValidate.conf.php');
        $reject_html_tags = [
            '<script>','<style>','<iframe>','<source>',
        ];
        foreach ($reject_html_tags as $tag) {
            $allowable_tags = str_replace($tag, '', $allowable_tags);
        }
        // 保留$allowable_tags
        $html = strip_tags($html, $allowable_tags);

        return $html;
    }

    /**
     * 去除所有空白
     * 
     * @param $content string
     * @return string
     */
    private function removeSpace($content = '')
    {
        // 全形空白轉半形空白
        $content = str_replace(array('　'), array(' '), $content);
        // 去空白
        if (mb_strlen($content) > 0) {
            $content = preg_replace('/\s(?=)/', '', $content);
        }

        return $content;
    }

    /**
     * 全形轉半形
     * 
     * @param $str string
     * @return string
     */
    private function convert_str($str = '')
    {
        $f = array(
            "Ａ","Ｂ","Ｃ","Ｄ","Ｅ","Ｆ","Ｇ","Ｈ","Ｉ","Ｊ",
            "Ｋ","Ｌ","Ｍ","Ｎ","Ｏ","Ｐ","Ｑ","Ｒ","Ｓ","Ｔ",
            "Ｕ","Ｖ","Ｗ","Ｘ","Ｙ","Ｚ",
            "ａ","ｂ","ｃ","ｄ","ｅ","ｆ","ｇ","ｈ","ｉ","ｊ",
            "ｋ","ｌ","ｍ","ｎ","ｏ","ｐ","ｑ","ｒ","ｓ","ｔ",
            "ｕ","ｖ","ｗ","ｘ","ｙ","ｚ",
            "０","１","２","３","４","５","６","７","８","９",
            "！","＠","＃","＄","％","︿","＆","＊","（","）",
            "－","＿","＋","＝","［","］","｛","｝","｜","＼",
            "＜","＞","，","．","？","／","～","　",
        );
        $h = array(
            "A","B","C","D","E","F","G","H","I","J",
            "K","L","M","N","O","P","Q","R","S","T",
            "U","V","W","X","Y","Z",
            "a","b","c","d","e","f","g","h","i","j",
            "k","l","m","n","o","p","q","r","s","t",
            "u","v","w","x","y","z",
            "0","1","2","3","4","5","6","7","8","9",
            "!","@","#","$","%","^","&","*","(",")",
            "-","_","+","=","[","]","{","}","|","\\",
            "<",">",",",".","?","/","~"," ",
        );

        //$str = mb_convert_kana($str, 'as');
        return trim($str) == ''? $str:str_replace($f, $h, $str);
    }

    /**
     * 全形英文轉半形英文
     * 
     * @param $str string
     * @return string
     */
    private function convert_en_str($str = '')
    {
        $f = array(
            "Ａ","Ｂ","Ｃ","Ｄ","Ｅ","Ｆ","Ｇ","Ｈ","Ｉ","Ｊ",
            "Ｋ","Ｌ","Ｍ","Ｎ","Ｏ","Ｐ","Ｑ","Ｒ","Ｓ","Ｔ",
            "Ｕ","Ｖ","Ｗ","Ｘ","Ｙ","Ｚ",
            "ａ","ｂ","ｃ","ｄ","ｅ","ｆ","ｇ","ｈ","ｉ","ｊ",
            "ｋ","ｌ","ｍ","ｎ","ｏ","ｐ","ｑ","ｒ","ｓ","ｔ",
            "ｕ","ｖ","ｗ","ｘ","ｙ","ｚ",
        );
        $h = array(
            "A","B","C","D","E","F","G","H","I","J",
            "K","L","M","N","O","P","Q","R","S","T",
            "U","V","W","X","Y","Z",
            "a","b","c","d","e","f","g","h","i","j",
            "k","l","m","n","o","p","q","r","s","t",
            "u","v","w","x","y","z",
        );

        return trim($str) == ''? $str:str_replace($f, $h, $str);
    }

    /**
     * 全形數字轉半形數字
     * 
     * @param $str string
     * @return string
     */
    private function convert_num_str($str = '')
    {
        $f = array("０","１","２","３","４","５","６","７","８","９");
        $h = array("0","1","2","3","4","5","6","7","8","9");

        return trim($str) == ''? $str:str_replace($f, $h, $str);
    }

    /**
     * 全形符號轉半形符號
     * 
     * @param $str string
     * @return string
     */
    private function convert_sign_str($str = '')
    {
        $f = array(
            "！","＠","＃","＄","％","︿","＆","＊","（","）",
            "－","＿","＋","＝","［","］","｛","｝","｜","＼",
            "＜","＞","，","．","？","／","～","　",
        );
        $h = array(
            "!","@","#","$","%","^","&","*","(",")",
            "-","_","+","=","[","]","{","}","|","\\",
            "<",">",",",".","?","/","~"," ",
        );

        return trim($str) == ''? $str:str_replace($f, $h, $str);
    }
}
