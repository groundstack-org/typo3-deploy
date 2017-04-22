<?php
function escapeArray($array){
  $tmpArray = is_array($array) ? $array : array($array);
  foreach ($tmpArray as &$arr) {
    $tmp_str_replace_orig = array('"', "'", "<", ">", " ");
    $tmp_str_replace_target = array('', "", "", "", "");
    $arr = str_replace($tmp_str_replace_orig, $tmp_str_replace_target, htmlspecialchars(stripslashes(trim($arr))));
  }
  return $tmpArray;
}

print_r(escapeArray(array("       blub\\b/","fdjk adsjfk&","dsajkf '")));
