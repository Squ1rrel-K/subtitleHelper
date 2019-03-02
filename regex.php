<?php
/** 获取原srt字幕文件的内容
 *  使用正则表达式匹配结尾为000的情况
 *  对匹配结果裁剪2个0并修改原内容
 *  二次匹配，将所有非序号为0的内容，开始时间补1
 *  生成副本，输出
 */

$content = file_get_contents("CHS_test.srt");
$pattern = '/[0-9]+\s[0-9][0-9]\:[0-9][0-9]\:[0-9][0-9]\,[0-9]/m';
$pattern_cut = '/[0-9]+\s[0-9][0-9]\:[0-9][0-9]\:[0-9][0-9]\,[0-9][0-9][0-9]/m';

preg_match_all($pattern_cut, $content, $match_cut);
$res_cut = $match_cut[0];

for ($i = 0; $i < sizeof($res_cut); $i++) {
    global $content;
    $content = str_replace($res_cut[$i], substr($res_cut[$i], 0, -2), $content);

}
preg_match_all($pattern, $content, $match);

foreach ($match[0] as $value) {
    $last_char=substr($value, strlen($value) - 1);
    $second=substr($value, strlen($value) - 3, 1);
    if($last_char==9){
        $last_char=0;
        $second=(int)$second+1;
    }
    else $last_char=$last_char+1;
    $tem = substr($value, 0, strlen($value) - 3).$second.','.$last_char.'00';
    global $content;
    $content = str_replace($value, $tem, $content);
}
$new_file = fopen("new_test.srt", "w");
fputs($new_file, $content);
fclose($new_file);
?>