<?php
  function speakit_mbStrSplit ($string, $len = 1) { //对内容进行分割
    $start = 0;
    $strlen = mb_strlen($string);
    while ($strlen) {
      $array[] = mb_substr($string, $start, $len, "utf8");
      $string = mb_substr($string, $len, $strlen, "utf8");
      $strlen = mb_strlen($string);
    }
    return $array;
  }

  function speakit_match_chinese($chars, $encoding = 'utf8') { //过滤特殊字符串
    $pattern = ($encoding == 'utf8')?'/[\x{4e00}-\x{9fa5}a-zA-Z0-9,，。 ]/u':'/[\x80-\xFF]/';
    preg_match_all($pattern, $chars, $result);
    $temp = join('', $result[0]);
    return $temp;
  }
	
  function speakit_load_template_html($tts_uri, $ctx) {
    $template_html = '<video id="speakit_video" style="display:none">
        <source id="speakit_src" type="video/mp4">
      </video>
      <script type="text/javascript">
        var speakitOff = 0;
        var speakitUri = "'.$tts_uri.'";
        var speakitCtx = eval('.$ctx.');
        var speakitAud = document.getElementById("speakit_video");
        if (speakitCtx.length > 0) {
          speakitAud.src = speakitUri + speakitCtx[speakitOff];
        }
        function playSpeakItContent() {
          var speakitAudBtn = document.getElementById("speakit_btn");
          if (speakitAud.paused && speakitCtx.length > 0) {
            speakitAudBtn.src = "'.plugins_url('images/pause.png', __FILE__).'"; //暂停图片
            speakitAud.src = speakitUri + speakitCtx[speakitOff];
            speakitAud.onended = function() {
              speakitOff = speakitOff + 1;
              if (speakitOff < speakitCtx.length) { 
               speakitAud.src = speakitUri + speakitCtx[speakitOff];
               speakitAud.play();
              } else {
                if (!speakitAud.paused) {
                  speakitAud.pause();
                }
                speakitOff = 0;
                speakitAudBtn.src = "'.plugins_url('images/play.png', __FILE__).'"; //暂停图片
              }
			};
			speakitAud.play();
          } else {
            if (!speakitAud.paused) {
              speakitAud.pause();
            }
            speakitAudBtn.src = "'.plugins_url('images/play.png', __FILE__).'"; //播放图片
          }
        }
      </script>
      <span style="float: left; margin-right: 10px; cursor: pointer;">
        <a href="javascript:playSpeakItContent();"><img src="'.plugins_url('images/play.png', __FILE__).'" width="25" height="25" id="speakit_btn" border="0"></a>
      </span>';
			
    return $template_html;
  }

  function speakit_load_html($content) {
    $str = $content;
    $str = strip_tags($str);
    $str = str_replace("、", "，", $str); //保留顿号
    $str = speakit_match_chinese($str);
    $ctx_len = mb_strlen(preg_replace('/\s/', '', html_entity_decode(strip_tags($str))), 'UTF-8');
    $r = speakit_mbStrSplit($str, 900);
    $tts_uri = "https://tts.baidu.com/text2audio?cuid=baiduid&lan=zh&ctp=1&pdt=311&tex=";
    return speakit_load_template_html($tts_uri, json_encode($r));
  }
?>
