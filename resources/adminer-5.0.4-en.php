<?php
/** Adminer - Compact database management
* @link https://www.adminer.org/
* @author Jakub Vrana, https://www.vrana.cz/
* @copyright 2007 Jakub Vrana
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 5.0.4
*/namespace
Adminer;$ia="5.0.4";error_reporting(6135);set_error_handler(function($Ac,$Cc){return!!preg_match('~^(Trying to access array offset on( value of type)? null|Undefined (array key|property))~',$Cc);},E_WARNING);$Xc=!preg_match('~^(unsafe_raw)?$~',ini_get("filter.default"));if($Xc||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$X){$Oi=filter_input_array(constant("INPUT$X"),FILTER_UNSAFE_RAW);if($Oi)$$X=$Oi;}}if(function_exists("mb_internal_encoding"))mb_internal_encoding("8bit");function
connection(){global$f;return$f;}function
adminer(){global$b;return$b;}function
version(){global$ia;return$ia;}function
idf_unescape($v){if(!preg_match('~^[`\'"[]~',$v))return$v;$qe=substr($v,-1);return
str_replace($qe.$qe,$qe,substr($v,1,-1));}function
escape_string($X){return
substr(q($X),1,-1);}function
number($X){return
preg_replace('~[^0-9]+~','',$X);}function
number_type(){return'((?<!o)int(?!er)|numeric|real|float|double|decimal|money)';}function
remove_slashes($ug,$Xc=false){if(function_exists("get_magic_quotes_gpc")&&get_magic_quotes_gpc()){while(list($y,$X)=each($ug)){foreach($X
as$ie=>$W){unset($ug[$y][$ie]);if(is_array($W)){$ug[$y][stripslashes($ie)]=$W;$ug[]=&$ug[$y][stripslashes($ie)];}else$ug[$y][stripslashes($ie)]=($Xc?$W:stripslashes($W));}}}}function
bracket_escape($v,$Fa=false){static$yi=array(':'=>':1',']'=>':2','['=>':3','"'=>':4');return
strtr($v,($Fa?array_flip($yi):$yi));}function
min_version($fj,$De="",$g=null){global$f;if(!$g)$g=$f;$ph=$g->server_info;if($De&&preg_match('~([\d.]+)-MariaDB~',$ph,$A)){$ph=$A[1];$fj=$De;}return$fj&&version_compare($ph,$fj)>=0;}function
charset($f){return(min_version("5.5.3",0,$f)?"utf8mb4":"utf8");}function
script($Bh,$xi="\n"){return"<script".nonce().">$Bh</script>$xi";}function
script_src($Ti){return"<script src='".h($Ti)."'".nonce()."></script>\n";}function
nonce(){return' nonce="'.get_nonce().'"';}function
target_blank(){return' target="_blank" rel="noreferrer noopener"';}function
h($P){return
str_replace("\0","&#0;",htmlspecialchars($P,ENT_QUOTES,'utf-8'));}function
nl_br($P){return
str_replace("\n","<br>",$P);}function
checkbox($B,$Y,$Za,$ne="",$vf="",$db="",$oe=""){$I="<input type='checkbox' name='$B' value='".h($Y)."'".($Za?" checked":"").($oe?" aria-labelledby='$oe'":"").">".($vf?script("qsl('input').onclick = function () { $vf };",""):"");return($ne!=""||$db?"<label".($db?" class='$db'":"").">$I".h($ne)."</label>":$I);}function
optionlist($Af,$hh=null,$Xi=false){$I="";foreach($Af
as$ie=>$W){$Bf=array($ie=>$W);if(is_array($W)){$I.='<optgroup label="'.h($ie).'">';$Bf=$W;}foreach($Bf
as$y=>$X)$I.='<option'.($Xi||is_string($y)?' value="'.h($y).'"':'').($hh!==null&&($Xi||is_string($y)?(string)$y:$X)===$hh?' selected':'').'>'.h($X);if(is_array($W))$I.='</optgroup>';}return$I;}function
html_select($B,$Af,$Y="",$uf="",$oe=""){return"<select name='".h($B)."'".($oe?" aria-labelledby='$oe'":"").">".optionlist($Af,$Y)."</select>".($uf?script("qsl('select').onchange = function () { $uf };",""):"");}function
html_radios($B,$Af,$Y=""){$I="";foreach($Af
as$y=>$X)$I.="<label><input type='radio' name='".h($B)."' value='".h($y)."'".($y==$Y?" checked":"").">".h($X)."</label>";return$I;}function
confirm($Oe="",$ih="qsl('input')"){return
script("$ih.onclick = function () { return confirm('".($Oe?js_escape($Oe):'Are you sure?')."'); };","");}function
print_fieldset($u,$ve,$ij=false){echo"<fieldset><legend>","<a href='#fieldset-$u'>$ve</a>",script("qsl('a').onclick = partial(toggle, 'fieldset-$u');",""),"</legend>","<div id='fieldset-$u'".($ij?"":" class='hidden'").">\n";}function
bold($Ma,$db=""){return($Ma?" class='active $db'":($db?" class='$db'":""));}function
js_escape($P){return
addcslashes($P,"\r\n'\\/");}function
ini_bool($Td){$X=ini_get($Td);return(preg_match('~^(on|true|yes)$~i',$X)||(int)$X);}function
sid(){static$I;if($I===null)$I=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$I;}function
set_password($ej,$M,$V,$E){$_SESSION["pwds"][$ej][$M][$V]=($_COOKIE["adminer_key"]&&is_string($E)?array(encrypt_string($E,$_COOKIE["adminer_key"])):$E);}function
get_password(){$I=get_session("pwds");if(is_array($I))$I=($_COOKIE["adminer_key"]?decrypt_string($I[0],$_COOKIE["adminer_key"]):false);return$I;}function
q($P){global$f;return$f->quote($P);}function
get_val($G,$n=0){global$f;return$f->result($G,$n);}function
get_vals($G,$d=0){global$f;$I=array();$H=$f->query($G);if(is_object($H)){while($J=$H->fetch_row())$I[]=$J[$d];}return$I;}function
get_key_vals($G,$g=null,$sh=true){global$f;if(!is_object($g))$g=$f;$I=array();$H=$g->query($G);if(is_object($H)){while($J=$H->fetch_row()){if($sh)$I[$J[0]]=$J[1];else$I[]=$J[0];}}return$I;}function
get_rows($G,$g=null,$m="<p class='error'>"){global$f;$tb=(is_object($g)?$g:$f);$I=array();$H=$tb->query($G);if(is_object($H)){while($J=$H->fetch_assoc())$I[]=$J;}elseif(!$H&&!is_object($g)&&$m&&(defined('Adminer\PAGE_HEADER')||$m=="-- "))echo$m.error()."\n";return$I;}function
unique_array($J,$x){foreach($x
as$w){if(preg_match("~PRIMARY|UNIQUE~",$w["type"])){$I=array();foreach($w["columns"]as$y){if(!isset($J[$y]))continue
2;$I[$y]=$J[$y];}return$I;}}}function
escape_key($y){if(preg_match('(^([\w(]+)('.str_replace("_",".*",preg_quote(idf_escape("_"))).')([ \w)]+)$)',$y,$A))return$A[1].idf_escape(idf_unescape($A[2])).$A[3];return
idf_escape($y);}function
where($Z,$o=array()){global$f;$I=array();foreach((array)$Z["where"]as$y=>$X){$y=bracket_escape($y,1);$d=escape_key($y);$I[]=$d.(JUSH=="sql"&&$o[$y]["type"]=="json"?" = CAST(".q($X)." AS JSON)":(JUSH=="sql"&&is_numeric($X)&&preg_match('~\.~',$X)?" LIKE ".q($X):(JUSH=="mssql"?" LIKE ".q(preg_replace('~[_%[]~','[\0]',$X)):" = ".unconvert_field($o[$y],q($X)))));if(JUSH=="sql"&&preg_match('~char|text~',$o[$y]["type"])&&preg_match("~[^ -@]~",$X))$I[]="$d = ".q($X)." COLLATE ".charset($f)."_bin";}foreach((array)$Z["null"]as$y)$I[]=escape_key($y)." IS NULL";return
implode(" AND ",$I);}function
where_check($X,$o=array()){parse_str($X,$Wa);remove_slashes(array(&$Wa));return
where($Wa,$o);}function
where_link($t,$d,$Y,$xf="="){return"&where%5B$t%5D%5Bcol%5D=".urlencode($d)."&where%5B$t%5D%5Bop%5D=".urlencode(($Y!==null?$xf:"IS NULL"))."&where%5B$t%5D%5Bval%5D=".urlencode($Y);}function
convert_fields($e,$o,$L=array()){$I="";foreach($e
as$y=>$X){if($L&&!in_array(idf_escape($y),$L))continue;$za=convert_field($o[$y]);if($za)$I.=", $za AS ".idf_escape($y);}return$I;}function
cookie($B,$Y,$ye=2592000){global$ba;return
header("Set-Cookie: $B=".urlencode($Y).($ye?"; expires=".gmdate("D, d M Y H:i:s",time()+$ye)." GMT":"")."; path=".preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]).($ba?"; secure":"")."; HttpOnly; SameSite=lax",false);}function
restart_session(){if(!ini_bool("session.use_cookies"))session_start();}function
stop_session($ed=false){$Wi=ini_bool("session.use_cookies");if(!$Wi||$ed){session_write_close();if($Wi&&@ini_set("session.use_cookies",false)===false)session_start();}}function&get_session($y){return$_SESSION[$y][DRIVER][SERVER][$_GET["username"]];}function
set_session($y,$X){$_SESSION[$y][DRIVER][SERVER][$_GET["username"]]=$X;}function
auth_url($ej,$M,$V,$j=null){global$ec;preg_match('~([^?]*)\??(.*)~',remove_from_uri(implode("|",array_keys($ec))."|username|".($j!==null?"db|":"").session_name()),$A);return"$A[1]?".(sid()?SID."&":"").($ej!="server"||$M!=""?urlencode($ej)."=".urlencode($M)."&":"")."username=".urlencode($V).($j!=""?"&db=".urlencode($j):"").($A[2]?"&$A[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($_e,$Oe=null){if($Oe!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($_e!==null?$_e:$_SERVER["REQUEST_URI"]))][]=$Oe;}if($_e!==null){if($_e=="")$_e=".";header("Location: $_e");exit;}}function
query_redirect($G,$_e,$Oe,$Cg=true,$Hc=true,$Rc=false,$li=""){global$f,$m,$b;if($Hc){$Kh=microtime(true);$Rc=!$f->query($G);$li=format_time($Kh);}$Eh="";if($G)$Eh=$b->messageQuery($G,$li,$Rc);if($Rc){$m=error().$Eh.script("messagesPrint();");return
false;}if($Cg)redirect($_e,$Oe.$Eh);return
true;}function
queries($G){global$f;static$yg=array();static$Kh;if(!$Kh)$Kh=microtime(true);if($G===null)return
array(implode("\n",$yg),format_time($Kh));$yg[]=(preg_match('~;$~',$G)?"DELIMITER ;;\n$G;\nDELIMITER ":$G).";";return$f->query($G);}function
apply_queries($G,$S,$Dc='Adminer\table'){foreach($S
as$Q){if(!queries("$G ".$Dc($Q)))return
false;}return
true;}function
queries_redirect($_e,$Oe,$Cg){list($yg,$li)=queries(null);return
query_redirect($yg,$_e,$Oe,$Cg,false,!$Cg,$li);}function
format_time($Kh){return
sprintf('%.3f s',max(0,microtime(true)-$Kh));}function
relative_uri(){return
str_replace(":","%3a",preg_replace('~^[^?]*/([^?]*)~','\1',$_SERVER["REQUEST_URI"]));}function
remove_from_uri($Tf=""){return
substr(preg_replace("~(?<=[?&])($Tf".(SID?"":"|".session_name()).")=[^&]*&~",'',relative_uri()."&"),0,-1);}function
pagination($D,$Ib){return" ".($D==$Ib?$D+1:'<a href="'.h(remove_from_uri("page").($D?"&page=$D".($_GET["next"]?"&next=".urlencode($_GET["next"]):""):"")).'">'.($D+1)."</a>");}function
get_file($y,$Rb=false,$Vb=""){$Wc=$_FILES[$y];if(!$Wc)return
null;foreach($Wc
as$y=>$X)$Wc[$y]=(array)$X;$I='';foreach($Wc["error"]as$y=>$m){if($m)return$m;$B=$Wc["name"][$y];$ti=$Wc["tmp_name"][$y];$xb=file_get_contents($Rb&&preg_match('~\.gz$~',$B)?"compress.zlib://$ti":$ti);if($Rb){$Kh=substr($xb,0,3);if(function_exists("iconv")&&preg_match("~^\xFE\xFF|^\xFF\xFE~",$Kh))$xb=iconv("utf-16","utf-8",$xb);elseif($Kh=="\xEF\xBB\xBF")$xb=substr($xb,3);}$I.=$xb;if($Vb)$I.=(preg_match("($Vb\\s*\$)",$xb)?"":$Vb)."\n\n";}return$I;}function
upload_error($m){$Ke=($m==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($m?'Unable to upload a file.'.($Ke?" ".sprintf('Maximum allowed file size is %sB.',$Ke):""):'File does not exist.');}function
repeat_pattern($dg,$we){return
str_repeat("$dg{0,65535}",$we/65535)."$dg{0,".($we%65535)."}";}function
is_utf8($X){return(preg_match('~~u',$X)&&!preg_match('~[\0-\x8\xB\xC\xE-\x1F]~',$X));}function
shorten_utf8($P,$we=80,$Qh=""){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{10FFFF}]",$we).")($)?)u",$P,$A))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$we).")($)?)",$P,$A);return
h($A[1]).$Qh.(isset($A[2])?"":"<i>…</i>");}function
format_number($X){return
strtr(number_format($X,0,".",','),preg_split('~~u','0123456789',-1,PREG_SPLIT_NO_EMPTY));}function
friendly_url($X){return
preg_replace('~\W~i','-',$X);}function
hidden_fields($ug,$Kd=array(),$ng=''){$I=false;foreach($ug
as$y=>$X){if(!in_array($y,$Kd)){if(is_array($X))hidden_fields($X,array(),$y);else{$I=true;echo'<input type="hidden" name="'.h($ng?$ng."[$y]":$y).'" value="'.h($X).'">';}}}return$I;}function
hidden_fields_get(){echo(sid()?'<input type="hidden" name="'.session_name().'" value="'.h(session_id()).'">':''),(SERVER!==null?'<input type="hidden" name="'.DRIVER.'" value="'.h(SERVER).'">':""),'<input type="hidden" name="username" value="'.h($_GET["username"]).'">';}function
table_status1($Q,$Sc=false){$I=table_status($Q,$Sc);return($I?:array("Name"=>$Q));}function
column_foreign_keys($Q){global$b;$I=array();foreach($b->foreignKeys($Q)as$q){foreach($q["source"]as$X)$I[$X][]=$q;}return$I;}function
enum_input($U,$Aa,$n,$Y,$uc=null){global$b;preg_match_all("~'((?:[^']|'')*)'~",$n["length"],$Fe);$I=($uc!==null?"<label><input type='$U'$Aa value='$uc'".((is_array($Y)?in_array($uc,$Y):$Y===$uc)?" checked":"")."><i>".'empty'."</i></label>":"");foreach($Fe[1]as$t=>$X){$X=stripcslashes(str_replace("''","'",$X));$Za=(is_array($Y)?in_array($X,$Y):$Y===$X);$I.=" <label><input type='$U'$Aa value='".h($X)."'".($Za?' checked':'').'>'.h($b->editVal($X,$n)).'</label>';}return$I;}function
input($n,$Y,$s){global$l,$b;$B=h(bracket_escape($n["field"]));echo"<td class='function'>";if(is_array($Y)&&!$s){$Y=json_encode($Y,128);$s="json";}$Mg=(JUSH=="mssql"&&$n["auto_increment"]);if($Mg&&!$_POST["save"])$s=null;$nd=(isset($_GET["select"])||$Mg?array("orig"=>'original'):array())+$b->editFunctions($n);$ac=stripos($n["default"],"GENERATED ALWAYS AS ")===0?" disabled=''":"";$Aa=" name='fields[$B]'$ac";$_c=$l->enumLength($n);if($_c){$n["type"]="enum";$n["length"]=$_c;}if($n["type"]=="enum")echo
h($nd[""])."<td>".$b->editInput($_GET["edit"],$n,$Aa,$Y);else{$zd=(in_array($s,$nd)||isset($nd[$s]));echo(count($nd)>1?"<select name='function[$B]'$ac>".optionlist($nd,$s===null||$zd?$s:"")."</select>".on_help("getTarget(event).value.replace(/^SQL\$/, '')",1).script("qsl('select').onchange = functionChange;",""):h(reset($nd))).'<td>';$Vd=$b->editInput($_GET["edit"],$n,$Aa,$Y);if($Vd!="")echo$Vd;elseif(preg_match('~bool~',$n["type"]))echo"<input type='hidden'$Aa value='0'>"."<input type='checkbox'".(preg_match('~^(1|t|true|y|yes|on)$~i',$Y)?" checked='checked'":"")."$Aa value='1'>";elseif($n["type"]=="set"){preg_match_all("~'((?:[^']|'')*)'~",$n["length"],$Fe);foreach($Fe[1]as$t=>$X){$X=stripcslashes(str_replace("''","'",$X));$Za=in_array($X,explode(",",$Y),true);echo" <label><input type='checkbox' name='fields[$B][$t]' value='".h($X)."'".($Za?' checked':'').">".h($b->editVal($X,$n)).'</label>';}}elseif(preg_match('~blob|bytea|raw|file~',$n["type"])&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$B'>";elseif(($ii=preg_match('~text|lob|memo~i',$n["type"]))||preg_match("~\n~",$Y)){if($ii&&JUSH!="sqlite")$Aa.=" cols='50' rows='12'";else{$K=min(12,substr_count($Y,"\n")+1);$Aa.=" cols='30' rows='$K'".($K==1?" style='height: 1.2em;'":"");}echo"<textarea$Aa>".h($Y).'</textarea>';}elseif($s=="json"||preg_match('~^jsonb?$~',$n["type"]))echo"<textarea$Aa cols='50' rows='12' class='jush-js'>".h($Y).'</textarea>';else{$Ii=$l->types();$Me=(!preg_match('~int~',$n["type"])&&preg_match('~^(\d+)(,(\d+))?$~',$n["length"],$A)?((preg_match("~binary~",$n["type"])?2:1)*$A[1]+($A[3]?1:0)+($A[2]&&!$n["unsigned"]?1:0)):($Ii[$n["type"]]?$Ii[$n["type"]]+($n["unsigned"]?0:1):0));if(JUSH=='sql'&&min_version(5.6)&&preg_match('~time~',$n["type"]))$Me+=7;echo"<input".((!$zd||$s==="")&&preg_match('~(?<!o)int(?!er)~',$n["type"])&&!preg_match('~\[\]~',$n["full_type"])?" type='number'":"")." value='".h($Y)."'".($Me?" data-maxlength='$Me'":"").(preg_match('~char|binary~',$n["type"])&&$Me>20?" size='40'":"")."$Aa>";}echo$b->editHint($_GET["edit"],$n,$Y);$Yc=0;foreach($nd
as$y=>$X){if($y===""||!$X)break;$Yc++;}if($Yc)echo
script("mixin(qsl('td'), {onchange: partial(skipOriginal, $Yc), oninput: function () { this.onchange(); }});");}}function
process_input($n){global$b,$l;if(stripos($n["default"],"GENERATED ALWAYS AS ")===0)return
null;$v=bracket_escape($n["field"]);$s=$_POST["function"][$v];$Y=$_POST["fields"][$v];if($n["type"]=="enum"||$l->enumLength($n)){if($Y==-1)return
false;if($Y=="")return"NULL";}if($n["auto_increment"]&&$Y=="")return
null;if($s=="orig")return(preg_match('~^CURRENT_TIMESTAMP~i',$n["on_update"])?idf_escape($n["field"]):false);if($s=="NULL")return"NULL";if($n["type"]=="set")$Y=implode(",",(array)$Y);if($s=="json"){$s="";$Y=json_decode($Y,true);if(!is_array($Y))return
false;return$Y;}if(preg_match('~blob|bytea|raw|file~',$n["type"])&&ini_bool("file_uploads")){$Wc=get_file("fields-$v");if(!is_string($Wc))return
false;return$l->quoteBinary($Wc);}return$b->processInput($n,$Y,$s);}function
fields_from_edit(){global$l;$I=array();foreach((array)$_POST["field_keys"]as$y=>$X){if($X!=""){$X=bracket_escape($X);$_POST["function"][$X]=$_POST["field_funs"][$y];$_POST["fields"][$X]=$_POST["field_vals"][$y];}}foreach((array)$_POST["fields"]as$y=>$X){$B=bracket_escape($y,1);$I[$B]=array("field"=>$B,"privileges"=>array("insert"=>1,"update"=>1,"where"=>1,"order"=>1),"null"=>1,"auto_increment"=>($y==$l->primary),);}return$I;}function
search_tables(){global$b,$f;$_GET["where"][0]["val"]=$_POST["query"];$kh="<ul>\n";foreach(table_status('',true)as$Q=>$R){$B=$b->tableName($R);if(isset($R["Engine"])&&$B!=""&&(!$_POST["tables"]||in_array($Q,$_POST["tables"]))){$H=$f->query("SELECT".limit("1 FROM ".table($Q)," WHERE ".implode(" AND ",$b->selectSearchProcess(fields($Q),array())),1));if(!$H||$H->fetch_row()){$qg="<a href='".h(ME."select=".urlencode($Q)."&where[0][op]=".urlencode($_GET["where"][0]["op"])."&where[0][val]=".urlencode($_GET["where"][0]["val"]))."'>$B</a>";echo"$kh<li>".($H?$qg:"<p class='error'>$qg: ".error())."\n";$kh="";}}}echo($kh?"<p class='message'>".'No tables.':"</ul>")."\n";}function
dump_headers($Hd,$Ve=false){global$b;$I=$b->dumpHeaders($Hd,$Ve);$Pf=$_POST["output"];if($Pf!="text")header("Content-Disposition: attachment; filename=".$b->dumpFilename($Hd).".$I".($Pf!="file"&&preg_match('~^[0-9a-z]+$~',$Pf)?".$Pf":""));session_write_close();ob_flush();flush();return$I;}function
dump_csv($J){foreach($J
as$y=>$X){if(preg_match('~["\n,;\t]|^0|\.\d*0$~',$X)||$X==="")$J[$y]='"'.str_replace('"','""',$X).'"';}echo
implode(($_POST["format"]=="csv"?",":($_POST["format"]=="tsv"?"\t":";")),$J)."\r\n";}function
apply_sql_function($s,$d){return($s?($s=="unixepoch"?"DATETIME($d, '$s')":($s=="count distinct"?"COUNT(DISTINCT ":strtoupper("$s("))."$d)"):$d);}function
get_temp_dir(){$I=ini_get("upload_tmp_dir");if(!$I){if(function_exists('sys_get_temp_dir'))$I=sys_get_temp_dir();else{$p=@tempnam("","");if(!$p)return
false;$I=dirname($p);unlink($p);}}return$I;}function
file_open_lock($p){$r=@fopen($p,"r+");if(!$r){$r=@fopen($p,"w");if(!$r)return;chmod($p,0660);}flock($r,LOCK_EX);return$r;}function
file_write_unlock($r,$Kb){rewind($r);fwrite($r,$Kb);ftruncate($r,strlen($Kb));flock($r,LOCK_UN);fclose($r);}function
password_file($h){$p=get_temp_dir()."/adminer.key";$I=@file_get_contents($p);if($I||!$h)return$I;$r=@fopen($p,"w");if($r){chmod($p,0660);$I=rand_string();fwrite($r,$I);fclose($r);}return$I;}function
rand_string(){return
md5(uniqid(mt_rand(),true));}function
select_value($X,$_,$n,$ki){global$b;if(is_array($X)){$I="";foreach($X
as$ie=>$W)$I.="<tr>".($X!=array_values($X)?"<th>".h($ie):"")."<td>".select_value($W,$_,$n,$ki);return"<table>$I</table>";}if(!$_)$_=$b->selectLink($X,$n);if($_===null){if(is_mail($X))$_="mailto:$X";if(is_url($X))$_=$X;}$I=$b->editVal($X,$n);if($I!==null){if(!is_utf8($I))$I="\0";elseif($ki!=""&&is_shortable($n))$I=shorten_utf8($I,max(0,+$ki));else$I=h($I);}return$b->selectVal($I,$_,$n,$X);}function
is_mail($rc){$_a='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$dc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$dg="$_a+(\\.$_a+)*@($dc?\\.)+$dc";return
is_string($rc)&&preg_match("(^$dg(,\\s*$dg)*\$)i",$rc);}function
is_url($P){$dc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return
preg_match("~^(https?)://($dc?\\.)+$dc(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$P);}function
is_shortable($n){return
preg_match('~char|text|json|lob|geometry|point|linestring|polygon|string|bytea~',$n["type"]);}function
count_rows($Q,$Z,$be,$sd){$G=" FROM ".table($Q).($Z?" WHERE ".implode(" AND ",$Z):"");return($be&&(JUSH=="sql"||count($sd)==1)?"SELECT COUNT(DISTINCT ".implode(", ",$sd).")$G":"SELECT COUNT(*)".($be?" FROM (SELECT 1$G GROUP BY ".implode(", ",$sd).") x":$G));}function
slow_query($G){global$b,$T,$l;$j=$b->database();$mi=$b->queryTimeout();$yh=$l->slowQuery($G,$mi);$g=null;if(!$yh&&support("kill")&&is_object($g=connect($b->credentials()))&&($j==""||$g->select_db($j))){$le=$g->result(connection_id());echo'<script',nonce(),'>
var timeout = setTimeout(function () {
	ajax(\'',js_escape(ME),'script=kill\', function () {
	}, \'kill=',$le,'&token=',$T,'\');
}, ',1000*$mi,');
</script>
';}ob_flush();flush();$I=@get_key_vals(($yh?:$G),$g,false);if($g){echo
script("clearTimeout(timeout);");ob_flush();flush();}return$I;}function
get_token(){$Ag=rand(1,1e6);return($Ag^$_SESSION["token"]).":$Ag";}function
verify_token(){list($T,$Ag)=explode(":",$_POST["token"]);return($Ag^$_SESSION["token"])==$T;}function
lzw_decompress($Ja){$Zb=256;$Ka=8;$fb=array();$Og=0;$Pg=0;for($t=0;$t<strlen($Ja);$t++){$Og=($Og<<8)+ord($Ja[$t]);$Pg+=8;if($Pg>=$Ka){$Pg-=$Ka;$fb[]=$Og>>$Pg;$Og&=(1<<$Pg)-1;$Zb++;if($Zb>>$Ka)$Ka++;}}$Yb=range("\0","\xFF");$I="";foreach($fb
as$t=>$eb){$qc=$Yb[$eb];if(!isset($qc))$qc=$tj.$tj[0];$I.=$qc;if($t)$Yb[]=$tj.$qc[0];$tj=$qc;}return$I;}function
on_help($nb,$vh=0){return
script("mixin(qsl('select, input'), {onmouseover: function (event) { helpMouseover.call(this, event, $nb, $vh) }, onmouseout: helpMouseout});","");}function
edit_form($Q,$o,$J,$Ri){global$b,$T,$m;$Wh=$b->tableName(table_status1($Q,true));page_header(($Ri?'Edit':'Insert'),$m,array("select"=>array($Q,$Wh)),$Wh);$b->editRowPrint($Q,$o,$J,$Ri);if($J===false){echo"<p class='error'>".'No rows.'."\n";return;}echo'<form action="" method="post" enctype="multipart/form-data" id="form">
';$Yc=0;if(!$o)echo"<p class='error'>".'You have no privileges to update this table.'."\n";else{echo"<table class='layout'>".script("qsl('table').onkeydown = editingKeydown;");foreach($o
as$B=>$n){echo"<tr><th>".$b->fieldName($n);$k=$_GET["set"][bracket_escape($B)];if($k===null){$k=$n["default"];if($n["type"]=="bit"&&preg_match("~^b'([01]*)'\$~",$k,$Ig))$k=$Ig[1];}$Y=($J!==null?($J[$B]!=""&&JUSH=="sql"&&preg_match("~enum|set~",$n["type"])&&is_array($J[$B])?implode(",",$J[$B]):(is_bool($J[$B])?+$J[$B]:$J[$B])):(!$Ri&&$n["auto_increment"]?"":(isset($_GET["select"])?false:$k)));if(!$_POST["save"]&&is_string($Y))$Y=$b->editVal($Y,$n);$s=($_POST["save"]?(string)$_POST["function"][$B]:($Ri&&preg_match('~^CURRENT_TIMESTAMP~i',$n["on_update"])?"now":($Y===false?null:($Y!==null?'':'NULL'))));if(!$_POST&&!$Ri&&$Y==$n["default"]&&preg_match('~^[\w.]+\(~',$Y))$s="SQL";if(preg_match("~time~",$n["type"])&&preg_match('~^CURRENT_TIMESTAMP~i',$Y)){$Y="";$s="now";}if($n["type"]=="uuid"&&$Y=="uuid()"){$Y="";$s="uuid";}if($n["auto_increment"]||$s=="now"||$s=="uuid")$Yc++;input($n,$Y,$s);echo"\n";}if(!support("table"))echo"<tr>"."<th><input name='field_keys[]'>".script("qsl('input').oninput = fieldChange;")."<td class='function'>".html_select("field_funs[]",$b->editFunctions(array("null"=>isset($_GET["select"]))))."<td><input name='field_vals[]'>"."\n";echo"</table>\n";}echo"<p>\n";if($o){echo"<input type='submit' value='".'Save'."'>\n";if(!isset($_GET["select"])){echo"<input type='submit' name='insert' value='".($Ri?'Save and continue edit':'Save and insert next')."' title='Ctrl+Shift+Enter'>\n",($Ri?script("qsl('input').onclick = function () { return !ajaxForm(this.form, '".'Saving'."…', this); };"):"");}}echo($Ri?"<input type='submit' name='delete' value='".'Delete'."'>".confirm()."\n":($_POST||!$o?"":script("focus(qsa('td', qs('#form'))[2*$Yc+1].firstChild);")));if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo'<input type="hidden" name="referer" value="',h(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"]),'">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="',$T,'">
</form>
';}if(isset($_GET["file"])){if($_SERVER["HTTP_IF_MODIFIED_SINCE"]){header("HTTP/1.1 304 Not Modified");exit;}header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: immutable");if($_GET["file"]=="favicon.ico"){header("Content-Type: image/x-icon");echo
lzw_decompress("\0\0\0` \0�\0\n @\0�C��\"\0`E�Q����?�tvM'�Jd�d\\�b0\0�\"��fӈ��s5����A�XPaJ�0���8�#R�T��z`�#.��c�X��Ȁ?�-\0�Im?�.�M��\0ȯ(̉��/(%�\0");}elseif($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo
lzw_decompress("\n1̇�ٌ�l7��B1�4vb0��fs���n2B�ѱ٘�n:�#(�b.\rDc)��a7E����l�ñ��i1̎s���-4��f�	��i7�����i2\r�1��-�H������GF#a��;:O�!�r0���t~�f�':��h�B�'c͔�:6T\rc�A�zrc�XK�g+��Z�Xk���v��M7����7_�\"��)�����{���}��ƣ���-4N�}:�rf�K)�b{�H(Ɠєt1�)t�}F�p0��8�\\82�D�>��N�Cy��8\0惫\0F��>���(�3�	\n�9)�`v�-Ao\r��&���X������n������*A\0`A�\0��q\0oC��=σ��\r��\\��#{����Ȍ�2��R�;0dBHL+�H�,�!oR�>��N�A�|\"�Kɼ�0�Pb�Jd^�ȑ�d��Р�=<���:J#�¶�ڮ��a�Б��>�Te�F�k�j�#�K6#��9�ET��1K��Ŵ��+C�F�I�	(��L|���jP��pf��EuLQG���Z����2�Υ�2�!sk[:�1�k���6%�Ypkf+W[޷\rr�L1���\0ҝ��8�=�c��T.���-�~����#sO��vG�+�y�O{�J�9C�O��ײ|`�+(�M�r\r�O�5\n�4��8��(	�-l�Cj�2[r5yK�y�)�¬�+A�k������2�g߳3iĔ���HS>��W��<�f�}���jfMiBϹ�l�IC�(�\\4�m�5�4�H�%	Pڏ��R\"��N�g_��̍#��8�����:�N�w\$u����uJ=�1��)��3դ�ݿR-����2������������r�6���H�/p.�0�:��?^�\rH�;ס�o�@0�9�u!sF8��KF���7����}����g��V��_a��>����l@0�@\\�y\r�l=�����SG��̕�VTTZOu%���D�A�apd���\$YAq�0�|Хd6\rә_N�x%jpÚ�\r\r���\\_CTI�|6F�i���Ed���Cp{��r��RA�Z`tKI��4�J��>�e,���k��fD��^�q���;���d��T���\"���eģ���T8��DI#%\\�pB��>��ZEC�=PЭ��L�R�&��yV)�.J\$�)Chee3�Ԫ��#\rѪW7�a&�3q(��*���o.����,�DN�3F��<���BE����S�3+�p�C�)/.3b{��_��#O�J��M �\n��Wsn����� (A��o3HXF���C����v\rsX2�ӒD�4����R�iLK��q�2M�0eQ�'�� � t\r���]�Bsti�Q&�r����b�I�É�O�⍫M��p�^Z�j�Ey��JÁz\r�%�gUC��'�.��֥ Ձ�r�2�w Q�J�:\n(Ȇ8pDI�=���7�1�qU�����A�G.P̨MU4�e�^L�R�,�Gme�K�t�%kzT�<Ř�\\X[���-�ݚ#I)b~j�Ʒ��c�����J71�����Y�{i2;���k)!��\$����P8�Q�j\rAa)��'pS{U+<�gy��ZEK\$\0���~���\$��ɺF��������\0>�;Q���\nT�=��d����P +��7���.6�\$��Sj����ޛ\0�1�o�Y�ǈ�:-ܮQ�1B��\\+������.�0-��F��G0j�b��N3�ܮ�Ԓ�O����5\rlN�9Y����L,B(s��em�8ĻczB>l��(gf�o��,�`=,YA�*\$��:Y�L0q\n����&Uf�9b�\"Y�,zcMW=<�45���jK�%R�f��ĺ̼ͭq�Р��=	0��7\riM�0XK� ��Ex,X�#]�~w_���0g�J2����X.��nϢ':�Hy�1Y��ÕW��S^ji6�s^�ʱ�+�w���ڋ��[T߷xyP�\\YȲmĐ��p����g���C�X��ܧ�-�6(�-����N���:RS6�bޠ�5�٣UD��T�]]�p8%X2� �W�oNT�A.��ώILY;=]2������_���f��Һ��˓ޏwaG������²M�4a�thm�^�vW��=��~�~�e���L�N�~*@�+�Ѣ�Es��ZT�x;��9@�B&�h�۠��=v~~��g��[��	���N<���vOײR����2d�eO#>3��=x#���m�h�S���,�n^��}�#߫ۀp,���x;��E\"�>M����Ǣb�o�u/��m��^��,�4����쌄\"���%��o��\0��H>��k��o^qP(�Q��4j�/g�]`�	�=n�&�^&��jj�\0pk\0Ro��o��p4�����K�5K�NB�L������&l�/�Ao����mh�P���QP����=/�\r�mo�\0�����o�\0�@϶�H��й\r����Ζ�����pZ&�&��@��М���N����0�ߐ��0��{� U*d��8�ff�N��L�d-'<8�T-�<��>9\0�\r�� �ډÈ8�M�V����qqz��b\0�\0�����u�ќ8��h��c`[1��L�ч��b�� ��h��\0fo,�����	��G�\r�����Q�1�@�IQ�U1�k2\r��!��!�\"(I\"k4�!�3!&���iK �[��``)��2I����)��Rb39\"j��d�(� �!��%�� Ra&Q�R�)2h)�tԩ|�����|�L�`ȸ��ޘ�\\��G%�j�r?rʊRH/ �#R,��i�(qc-�-!��-�-R�-b\r�ZUo+��1���%�Ě`��2�1��R�#r�-�O2��1�//��/�/s!01_0����~�)���\r�!,iT��S-�Y5�\"?s4�Q�.��1�\"��-\".2�3)3�37r�&�Tu\0�ah�/���:S �����;R�S�S�n�v����s�,3�`�4f|��e=R��;��<3�>��\"R�\"��>�K(����?�[?r/*`���(��?TA�	(4A��\r���T%��*t5B��B�Dq�/\0�[t;@RgD�#ET\r�M��0��F�E�E�wm�&Q�D23G�02\"�-�d�`�5*��IE)�F�꠳Q>M�6 �1�~ّ��\n�k�2��>B�C��\$��ٔ�0��tLѹHPc�SE*[L�b0��:\rSJ�vKk* ��L��DS� T�CB2�PP���S,u3�/2HIS#��9Q҅OTMS�E>r<T���)���F4i���P=��*P�uj\nmJ�����k����c3XC�{�0c\n0��&K�T\rä+�/F�^�Hv\r�fq�AV��W)f]5x�`�W�Xzp��R�");}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress("f:��gCI��\n8��3)��7���81��x:\nOg#)��r7\n\"��`�|2�gSi�H)N�S��\r��\"0��@�)�`(\$s6O!��V/=��' T4�=��iS��6IO�G#�X�VC��s��Z1.�hp8,�[�H�~Cz���2�l�c3���s���I�b�4\n�F8T��I���U*fz��r0�E����y���f�Y.:��I��(�c��΋!�_l��^�^(��N{S��)r�q�Y��l٦3�3�\n�+G���y���i���xV3w�uh�^r����a۔���c��\r���(.��Ch�<\r)�ѣ�`�7���43'm5���\n�P�:2�P����q ���C�}ī�����38�B�0�hR��r(�0��b\\0�Hr44��B�!�p�\$�rZZ�2܉.Ƀ(\\�5�|\nC(�\"��P���.��N�RT�Γ��>�HN��8HP�\\�7Jp~���2%��OC�1�.��C8·H��*�j����S(�i!Lr��D�# ȗ�`Bγ�u\\�i�B!x\\�c�m-K��X���38�A���\r�X���cH�7�#R�*/-̋�p�;�B \n�3!���z^�pΎ�m�R���t�m�I-\r��\0H��@k,�4����{�.��J�Ȭ�o�Vӷb?[�Q#>=ۖ�~�#\$%wB�>9d�0zW�wJ�D���2�9y��*��z,�NjIh\\9���N4���9�Ax^;�^m\n��r\"3���z7��N�\$����w���6�2�H�v9g���2��kG\n�-Ůp��1�C{\n����7��6������2ۭ�;�Y��4q�? �!pd��oW*��rR;�À�f��,�0��0M���0�\"�� ��\"�ħ���oF2:SH�� �/;������٩ri9��=�^�����z�͵W*�Z��dx՛��֡�ITqA�1��z�Y!u������~��.��P�(�p4�3���#hg-�	'�F�p�0���C+P�����, ����e���N~�y@fZK��O3�v\$�`�C�	N`�!�z�pdh\$6EJ�cBD��c8L��P� �66�OH�d	.�����Y#�t�H62���e��@��~]C�[��&=G��\\P(�2(յ����̐q�2�x�nÁJ�|2�)(�(eR���G�Q��Ty\n�!pΪ\0Q]��&�ޜS�^N`�_(\0R	�'\r*q�P����x�9,��-�);��]�/��w������C.e��y\0�,��	S787���5Hlj(�� �\0�մ����q�I�/=S�� �àD�<\r!�2+��A�� J�e ��!\r�m��NiD�������^ڈl7��z��gK�6��-ӵ�e��!\rEJ\ni*�\$@�RU0,\$U6 ?�6��:��un�(��k�p!���d`�>�5\n�<�\rp9�|ɹ�^fNg(r���TZU��S�jQ8n���y�d�\r�4:O�w>[͞�4�4G\"��7%������\\��P��hnB�i.0�۬��*j�s��	Ho^�}J2*	�J�W��Gjx�S8�F͊e��6�s���*�\r<�0wi-00o`��^�k����*A�,ɸ�䍺��i���nj��2索A\"����[;��n��B^��0-�����\n:<Ԉe�2���h-���2�n�/A�\r6����[o�-��c��R@U3\n�\n�T��=�R�j���7s\"���Y+�\"u�<fH��`a��z�E�����^7syo:!�V���k�m����if�ۻ�/�ڦ8;<e�N�2ͱS�W?e`�C*B��͔�ZB�]����:K�_7��Ċq��Q�)��/�:d�i����Z^�3��tꃥ��t*��\$��f�z50t�UJg���S\r�cX�����\rw7Z�N^`oxP���I��x?��T�ke�� �Jim)�x;�X�����C�=V=���<U��!�0��n��;���~AZ��7�����+Z�=n���{H��PURY����������4�Hǋ6'g��2K���~��|hT�A��1��V���>/�^��l.��SI�.�9g��~O��%ئ��̾�)A|��\n;-��n��[�t,�����Y�<>j\n��N�eP���O<��� q���(G!~����`_�\r~���`��.�>'H�O�2�yK����d:(�,�<�3�:�����+0nUYZ���^�)ww��!�����1����!����mG��ַgd�=���X�[ޢ�<��ߩW�����7���`�o�ҭ��������G���~`�i`��*@��v������\0)�ꐜ\$R#��������Ud�)KL��M*��@�@��O\0H��\\j�F\r����]�gK��i�\$�D�*g\0�\n��	��s� ��\$K0�&��	`{���6W`�x`�8�DG�*���eHV��8��\nmT�O�#P��@��������.�\r8�Y/&:D�	�Q�&%E�.�]��Я�.\"%&��n�\ny\0�-�RSO�B�0��	�v��D@�݂:��;\nDT��< �Q.\nc2��Ry@�m@���	��W����\n�L\r\0}�V����#����-�jE�Zt\\mFv���F��J�p�B���(����1� ��LX���	%t\nM���D���Z���r��Kg´C�[�ʴ	�� �\0Я�������R*-n�#j�#�����4�IW�\r\",�*�f��x�/���^��5&L��2p�L��7�^`����� V�`bS�v�i(�ev\n��|�RNj/%M�%���+�ƫ����߯�'���R�'''�W(r�(��)2�Қ���%�-%6���ˀ�J@�,��ֿN���Q\n�0ꆐ�g	��\$���*L��.n��Q%m�\"n*h�\0�w�B�O�\0\\FJ�Wg� f\$�C5dK5��5�aC��4H�(��.G���BF��8������ E����.��k3m�*)-*��[g,%��	��7�.��!\n�+ O<ȼ�C�+ϫ%�O=Rf����(���n�Y��ϲ�%��s�1�6�3;��ObE@�NSl#��|�4\0�U�G\"@�_ [7��S��@�\$DG���D�5=���K>r����\r ��Z��ֱ@���H�Ds��n\\�e)����b�'���BPGkx�Z���#TK:�w:�a2+�aeK�KR)\"�(4qGTxi	H�H@�&@%bZ����ܪ)3P�3f `�\r�I6G�%�/4�v�\\~�4�ݤ0�p���,��E�)PH8k\0�i��\$���3I4�P�V'F^� �'D��R���+Q�`�����8\n���D[V5,#�qW@�W�0�O2� �t�\rC6sY_6 �ZkZ@z3ryI�<5���.W���ҷ@5�Ģ#ꎄ5N �~��ȥu�\r����)3S]*g7�����ҕ�_ˉ�_�ĸV\nY�)a��1P���FI\r;u@/!![�e� �(CU�O�aS���KP��t3=5��O[�f:Q,_]o_�<�J*�\rg:_ �\r\"ZC��8XV}V2��3s8e��P�sF�SN~�S5U�5�z�ae	k�n�fOL�JV5��j�����Z��lE&]�1\rĢم5\rG� �uo���8<�U]3�2�%n�ַpr�5��\n\$\"O\rq��r)�f���7/Y���p�I#`��Kk;\"!t���h�usYj�[�R�\n{N5t�#NΜ�o6�X)�c6���e+.!��ߗ�\n	�b��ʒ�t�Ү��\n���j��(\0��2��4erEJ��d����@+x�\"\\@���� %v�����{`�����`��\n �	�oRi-IB�-���Nm\\q@�,`��Kz#��\r�?��՘6��<j��f��!�N�7���:��/�Tł\0�K\\�0�*_8L�m�^r���V�w��\"��кB��Q:5Kn���v\0��xt�;`�[��	�B�9!nv�<ۢ���pP�p� ���p����1i*B.�tY�>\r��S�*nJ涨��7{�=|R]��ռ��4�i�U�2�2���Y3�c>a,X��3���9�\$�<A�Q�&2wӭ3���1��/�i����j��sO�&� �M@�\\���گ���8&I��m�x\0�	j�k�ۛE���^�	���&l��Q��\\\"�c�	��\rBs�ɉ��	���BN`�7�*Co<��	 �\n�ν�hC�9�#˙ �Ue�WPDU�0Y�7}�c��8?hm�\$.#��\n`�\n���yD�@R�y���@|�Ǎ���P\0x�K� w�5�E�Le�@O��u���|�R�2��%�aA�cZ��:�<d�kZ��y{9Ȑ@ޕ\"B<R`�	�\n������QW(<��ʎ�革�q�j}`N���\$�[��@�ib����f�V%�(Wj:2�(�z��ś�N`��<� [Bښ:k���ʚ�]��piuC�,�����9���e�j&�Sl�h~��N�s;�;9��u@.<1����|�P�!���zC��	�	���{�`��Q!���5�4e�d�G�hr���P���}�{��FZrV:�����Ŀ�Z����|�P��WZ��:��d��~!�}�X��V)����p4���.\$\0C��V󁺩���{�@�\n`�	��<f��;dc'�\r��,\0t~x�N����y� ˽kEC�FK\"Z�@\\C�e�D.Gf�I�8�ͤ���CĥY��q9T�CU[��z�^*�J�K��VD�؊��&���b̷KK+��Ĳ�,C����,N!��\r3�Y�P�9�\$Z���n�\$S��5�\r��aK��E��n�71Z���3e��J؜x5�Q�.��\n@����ǣp��P�ѡֽn\r�r|*�r��% R��蔊�)��#��=W\0�B���z*�W���MC��_`�����P��T�5ۦWU(\0��\\W��&`��a�j)��V�W�ʧ�b�f�O�rU���Ǽ~#c�Ur�5�`���Gd����P��fW������Yj`��ǌ\n��G�>K�h���ǿ��[Mf�g̗�|�\"@s\r!��_c����\\�v�OG���nԹ���gO\\u�ie�[�mz͟Y9��^��\";�����µ'{r�*�\r���ԺY���b�\$��?N��!YH�&���)BB-S��/e&�>�SVvow�\r�e4�u8�8�sP�<^B���>�Z�5[\0d�y6W4�B���5�1a�\0�I\nC�9���-��;���3�:b��xd�~�Q#��;��:Yh��:Ǖ�Ӫ�V\r�2\$�J/{z����k��i�nG�?|ĕVu�.wʹ�=p�I��HĀX=�����t��� -��MJ��p�Tk�p!�FG��p��3dWC�F�Rv@��\0�� �GG\0���\nC��\0d��@��Ɵdp�0�.�j��@�7���tK��N��x���5�L<BX�Ԅ/YI���f����FpҟP)/|,*dK	��.�Ud\$��I\0�D���7\0Yv�0T�(AP�u\rpoF��3�Tc�T{�	��\\AM#�2k�G}��\0��d�� �T�@�!�\r0{&�F��?y���K�Yz�Ü/ĵA@�\0��0:!�0���~�%9��#���B��p�x8�D��D��]�	�nƀ��EN��\">Iŉ�r0�������oC����S0T�/p9����Z'>\$���/���3o�A�(`��H����J��Ņ՛I�p�a�h'\r2\n,��п��4��e\"��Mz�e���+C?E�G��/ib�P�b��񚧀#��ZB�Y\0X�OB4�� �7y	j�Dr\${֡ĥ\n���\0������l%5K������'DK^۸a�uc*4�����2�B��T����!Z���ME�K��*ʡE�X�T�`2\r���<)�����\"#B'PVe�ڳ���E�,I���wX��I�b'\0W�0\"�+(L )��aUJ�|��2�ǖ5�y\r�UI��09S��p��S�_�|�!�f���\$��08B}ap��Ɔ������+�M)dYb���h�\0:��%B����L�r�g��İ�j�6r�G�c\n��6�t#�j�4�\"�\0��j��l�����u��MH��<��(_�7�rL�×S�WC�u�m�\n�^����r�~���p\0�]f�b���\\�ȹr@P�S�ya@���a\r�#̰e��:=�P(����P\n%�2~��P!m�A��&��0I\rZC����Qc\"0��PVe���7!�P��R8�;�\$xX�\rWj�P�=L��f��!x�(:\"��\\W�|'���VĜS\"�G=Ό����`~�0P�|_��\\5����'����/����Y@��#�T�#��.�+��?Q,�|���M\n{) �JP}GԔPA�;籼	�1w���D��C�8�CF(��?(�KF�(Ш,zі�U���k����^N�p�2l�\n9y�`Т��@��Uy B\"5��\0�%*�`0Ӓ�,��TDR�)��&xV�M=�k/\\�\\�&���\\ϸ`t����7'�\\���c]#%C˙��8E�.��K�]��t�ȩ	����o��J\0���qNj\r:�٫}U�b ���iF%�dլNk�Q9!TV�Z<ɒi\"�[�<ySb��_�����)��>��M�d��\\f�� ���?����J�x��!�`/��\rc1�@D�!�8@ �=�D9�>��&� �Y�6�b�v7R㗈A�,ᕋ�]L�����i�@og̾�\"��b��|S�&�# ���r\"U�8f��3������Yg`	���:#\\DM���1}9����<�����i���(��6A-��Z�Θ�!�_X	\0b�Ή�.\r+D��ֳ�s�P�@��11�&�-G	/��6!D�Au<\0�[=HONz��H@Z�,s�	���3��=�3�WM'�1�Z|��1���ˡlP��'��*O�S�\n��B?��Ό�@#[JCB�уP^y!u�F2���P�E1E��/�/�\0��ք­��(@�9y�Nq�a�s;c��q4:+����xt &���!���kC�|� 	Έ�c�M'@0��qCp�ڨ�z-Ϭna���J����\nt�3K<���.����'@m���f�7UbQ�jaA�x�ckǎ��@����.FSFJ��RK��K�9�N��@R�`����0{S�0_�1\nm��\n�&|X��\$�6<�D#�,�X�PGrJ)RF���?��S�-:�eПQ*��-�<��JT~H�a�`P�K�A,�@yB\n`n֠����Ok�fD�M`96d�\"�NZt)v�����ZC\n∯Ie��TAQL��V^�s��:�{*�\r�P��N8)\"�d�JY�D�dQ@\r�pŚ}@���;S���x���R��B�P�����,�9��̢Jp�,�`/�YN�-J\"8��7�)���fS\"`��\$2�X��M��6�D1*� �@9�\$��)�2����̪�5TH%���U�Oyb����c����U�@���ʦ�e��jJ�\0�\0*%�`\"��Ph�V�+-Y�G�YָBH�����\06�?��ʺ�b����RJ� �I��SW�<��O4�֦��4'/�a0�*=j�ʳ�ݵ��\nyUd��X�m����s\0��m�ҶՈ��nD��򸩻��cH�[�j��#,���&�R�;+�Z�ӕ��1�2���t�1��jL�R�f@&t2��S��,���{S���m:cDt�P�I(b�В��I�U�g����m�!��hN1`�)ʨ�*��H��l|��XT���\$H^@�e�&�f ��ŔA�1���Di!	ɀ�{\r�T�)Y�(��T�\n�\$#��kN��P\nk)�6�B�w'�`\"��N��D�&\$	`E����0,B�\rgu8\n,`5�(�NOD�00T�P�����ͯ�k[�V�l\r�'\$ F��`ql)@J�\"��,�d�\n��(h/ �4HӼ��? v8�KGp� Q\0�`A��#��9i�\n�t%�ϩ�}�i�=�DV=R;��ck!(\r�:E��Fa`�H��yI����ˌV\0)�[4r?`凔v��ҧ�.����d�KY1?!���~���0�>Kv�����'��ɶβ@�,�x�\\(���5E����\"����+�/��\0'	���>B�Nu+�w*aT�΁�]ŵ�4(L��H@ڪ�K��A�E\0�=��΁��Ů4��\0tPm�cE���|�T5�� ��F5z�822\0���R�7��&�x�0/�\0`<��J_�B�aOB�Dʣ��iM�0Il���c�ʠ�8?�1��9�i2Z�Ѩ\\��Z`Y�v�BX�@Ώ�2#�xde��˦���]8%�P���^D�]b����NEs\0\0�a�C�n�\0�+H\rW@,��(o�p��t\rb+�I�����?��^��\$і�v�A/x�\n�Wh!�W�j�\$Ȅ�*cH�����{�^P\r�#�l��{'�&��@p�`C/�k`����z*+�\$���\r\n�w�O\nҀu|�����7�N���ֱ��S�c괬\$��p��Y�����ғ\\Ŋ1|���OP�E��e,q�G�w����oG��n�\${� F�'i�f��ǅ�<����+|�C`^M6��0>\n��{�,߂8����c��3r�Qv�6��\n8��W���n�G�W�|��5���||pfSf9\$l�e?_bF�#wh��P\0]�F�tr�\r_`C� e�4�:^t�^Ua��z���'nˣ`d��X���X��wi~��*q�5\0����|����ư��*�9_1\n�a�C� ��d��^.��g��Q�2�X�����ΣB�]�k�Li�{71>��z��0x�7�+�gt�3V�\r��	h�+*p���y%�8y#�����T�L���+0K�'�q�h@vL�� ` ��Ohk�\r�d�E�l\\��d�p^���4�d!�\\�X��XE����V�\"�8�υT��.%6�m����Ʈ�q��w�{c`d0<�ksY�	zb,��0\nȹr�2<l��� \n�)z��õ�<\rIe4A�'��5/��6�O���zh��6�W�pRn!�l�'7\n�@�p��5?�4B��(�z�@�\0}Ǡ��l���R��P��q���eW�I�4\\9]��Na�!d1���[��.ˈ�A��A�*�6� ��:����a�;�0C��DD�j����7��Y2x4K	!I��}��p<ZҒ\r��A��`�KҒ�*�\r ��%�WW�#!�h@\\ԺJ\0�9�3�X2\\Lޑᐤ���))#��Rc4f>fYRm�p��|Y��H�,}�Lf�_t��z��hJ7�, L\"\r����q���i�N��,�W�7��I�����f�7&����e�G� 6�h|BQ\$Ќ��6%C=~|q����`(���*}gr�K<�jb3ާmbH�2N�+��u���L���S�!U�>��\r�i��<�bup豅C�4Q'�E����e3��\rE�*᠗�g�8�8+G����,[�n���}��f����&;�ؖ}�Qt9�B���=g2��F��Ǻ\$1sb���),9`o`w!ͷ`L�&[�#2�巨S�ǲ˓+��{RA�Є]�FE%ѩ��T�����~'�u�m�p���ƣ�9�\0���Uʝ���5O���!���S���x�ڰ�2ZQ����9]f XDP��)��Q{4�Y��b�@m�SQ��~��%��8�C;F��&�18`�>�C����ߋ�%P�hX%����jGE�%��ל@��>{���d���\\�N�w_w�ַ-�(���am��\"�]JFZ��g�lPlB��� ����d�����[ШM4.u[c쌛bL�����_��[*�ނ1��#��}{Y��z����%��ei�q�\"R�媩������G��\r�	����G�몙�J�@FJ.2Em>��Q8��{�Q;n^����\\�>N�d\0ctʄ���?l%%�v��ƴ��Zx| y�PX/���v� �_�`���\$\n�Cm¾~̚@됔\r3f[�x���Ų����	�b��Z�J��o��2]+展�2��&�5�y �`�ڊh��	��g�����\rէ�>\0���nͽ�6��2�bWg='������^J�yHn�/�]�mob[^nV��~j\\M1�����Kv֎��k@-�:�.�}�0NΧUA�9hF_i���@Gt��\\���O�M���x!��h��#��9xV�=����\n��yI�,;\0�\"yP�3�\0.�N�s⃨J~�U����\$���\0��!y��טR��L�(^S��������S�VV&\\'�:��H�9P�~�E�	����p��R�4i�I|*����'�L���o(�:�glp�D���A���i�YCU�N����j�gpa~Y�vW�{\n}�u���	l�)�\"��g23�䆂3Ý\nL�nQ���s�p���B��@���ѫ�\n�6��p[N���ل��]\"�z\n=�1�)��P�~�Rw�RR�H�I�;d��|��g`g��݄���Q�^����>�PEq�1_���8����d���E���zi�*`<cꑻ�����I�Xst!\\�q��j��\r�s�,�܌h]j���t1w�n%�j�^  �9�`|��[����ާ�Z6��)����>-BC��]����H�+S\$��{�_GA��+��i��8wv�����R�:A��,9ej'Nl��y�ޫ����.zy��L&s9,����/f�+�����VY�����;>8S�{`��F���#�B��f�ڇ��Z�S@���ܗzBT+�}�N�r��\\ߡk��e��`(����H�@P�U�����D��������\0���<y�p<K�W��(�H]d\n�(=��Z��͆Mu=��q̟W�����^�\"��NBs����:��I6v��b���A�mϴ_�V��D��x0�I6pY	�@��W���e�?yr��e3�۷8�}�N�u:Y\"���d�?�c��I��d�RMIҳ�)1��ieal�w_>�����AE%�)��/��dĒ��g39\n��uϲ���Wǻb_�V���_���P5���\"��ԯ��`e�|�jIa�^r�A!\\��\0Y�]͎s%�o�ә��=H���2�LK�?����4�(���GSB^`�!�������������^BH��<ZE�%���]W/\rP�!P��(�.V�}�\0�(��W�05;\\\$+@�*<+w!��@bH[\na���a�*����.^�\$Ds\$�p���t3\\�z�p���@D�'�X�K�y?�01x�\$�b\\�뼰!Б�:nCS�>�K+)��*L���f��[�ذ���~���ق_�ۂ�����.~2K��n��Ch�a�I�������CC���u�)2x�L�A����)�91��]�KO�<�����ܲu`��{����ccQe�������gǚm�晒&dL�1��O)�s��G\"��	�.��ػ���Fm�ȸ�X��8��5b����Q��Z����P��c�u�����?3%���hR̢}0&h�� ݀�*}G�ĲPp����0��|Y�g_q��h�_���c_V�\\E��U�h _�O�qQ�K�5�t\r8D`Q��u����(*��&܎�:�%&c�J�Sݛ�RU%/�)�D۳~��S�q�ڀp���q~�I�4E������vpF�-ɬ�02�ʧ��gM�Lup;ܳ��}���Ү�\n�s� x�q��PөW\0\0X���p���U�UH������d\r[\"��ίףh^oBJ��`��W��0\r����-�g�)��J+Zϖ��7���\\B���*����\0�< ����F�\0�ܞ���5H.����^��ګFE�d�s��2��	��\n��J-9Jok �\0�?Ƥ0�:l!�|i���AT�;I���Q��AX&C裡M@rl���L��Mh��2�� ���0�@�HƂ�\$�)���:o��*k<Ǔ�v<A`�zy��E��B0����C��1�Z �w�����dˎ#�R�')�y�.v��>����h�SY���p!@r�\ro�	�հ\r�v�q��k@��H�O�U�G �İq0B\">F�C�QK�-��4��M�wPX����LQ����=<�Q�r���a�@@A\"_���h��	�\r����t8���%���b���@�\0x3Y�D�<@���f�\r:k:\rs��\\@OH�P=hQ��\\��U`M\$A������8&��Az>� ;��vA�J\n��Ν� �AH� �F�� ���!�0��8Ud\"pA�-���xBH��L������dt�Z�C0s���f��R���|\n4��<(6J�x~�����\0�.��'ά��9�*䆈<�Bp��l�܊�\n�����.0���y�if����m�XB�&�\$=XFA@4\\�3�xi}��Q[@CL0��\08�/�A*Z`g��\"�B���C\r8�Ķ�\n�Z>�v+���@�ւ�0R�����!�'���S��!hD|�?C^3K��p�b�L��!�>�\n9(a��Ć7 \0fyB����	q����H\\m?����N9	瀌41:	��J���� J�)��ѰD�����,=�p�&�%(�֎\"]�#P�жDK��XS��d��\n�O)\n�Xk�D����k�z�[�d{@���c���`\0sEpi�h2���P����H�(p��	@��&�1�v\0V���V'n(W`��q�9����[����ad�d��F��b����G'0��J��D��\0��dE`��X<�E��\0��\0\0��Ff1�jFIq���qD�:P��1���v/�t9`7l:\"a.�8�<��EjV�\\��#�>��C�swF3����� C����N�9�P�?���6�x( ");}elseif($_GET["file"]=="jush.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress("v0��F����==��FS	��_6MƳ���r:�E�CI��o:�C��Xc��\r�؄J(:=�E���a28�x�?�'�i�SANN���xs�NB��Vl0���S	��Ul�(D|҄��P��>�E�㩶yHch��-3Eb�� �b��pE�p�9.����~\n�?Kb�iw|�`��d.�x8EN��!��2��3���\r���Y���y6GFmY�8o7\n\r�0��\0�Dbc�!�Q7Шd8���~��N)�Eг`�Ns��`�S)�O���/�<�x�9�o�����3n��2�!r�:;�+�9�CȨ���\n<�`��b�\\�?�`�4\r#`�<�Be�B#�N ��\r.D`��j�4���p�ar��㢺�>�8�\$�c��1�c���c����{n7����A�N�RLi\r1���!�(�j´�+��62�X�8+����.\r����!x���h�'��6S�\0R����O�\n��1(W0���7q��:N�E:68n+��մ5_(�s�\r��/m�6P�@�EQ���9\n�V-���\"�.:�J��8we�q�|؇�X�]��Y X�e�zW�� �7��Z1��hQf��u�j�4Z{p\\AU�J<��k��@�ɍ��@�}&���L7U�wuYh��2��@�u� P�7�A�h����3Û��XEͅZ�]�l�@Mplv�)� ��HW���y>�Y�-�Y��/�������hC�[*��F�#~�!�`�\r#0P�C˝�f������\\���^�%B<�\\�f�ޱ�����&/�O��L\\jF��jZ�1�\\:ƴ>�N��XaF�A�������f�h{\"s\n�64������?�8�^p�\"띰�ȸ\\�e(�P�N��q[g��r�&�}Ph���W��*��r_s�P�h���\n���om������#���.�\0@�pdW �\$Һ�Q۽Tl0� ��HdH�)��ۏ��)P���H�g��U����B�e\r�t:��\0)\"�t�,�����[�(D�O\nR8!�Ƭ֚��lA�V��4�h��Sq<��@}���gK�]���]�=90��'����wA<����a�~��W��D|A���2�X�U2��yŊ��=�p)�\0P	�s��n�3�r�f\0�F���v��G��I@�%���+��_I`����\r.��N���KI�[�ʖSJ���aUf�Sz���M��%��\"Q|9��Bc�a�q\0�8�#�<a��:z1Uf��>�Z�l������e5#U@iUG��n�%Ұs���;gxL�pP�?B��Q�\\�b��龒Q�=7�:��ݡQ�\r:�t�:y(� �\n�d)���\n�X;����CaA�\r���P�GH�!���@�9\n\nAl~H���V\ns��ի�Ư�bBr���������3�\r�P�%�ф\r}b/�Α\$�5�P�C�\"w�B_��U�gAt��夅�^Q��U���j���Bvh졄4�)��+�)<�j^�<L��4U*���Bg�����*n�ʖ�-����	9O\$��طzyM�3�\\9���.o�����E(i������7	tߚ�-&�\nj!\r��y�y�D1g���]��yR�7\"������~����)TZ0E9M�YZtXe!�f�@�{Ȭyl	8�;���R{��8�Į�e�+UL�'�F�1���8PE5-	�_!�7��[2�J��;�HR��ǹ�8p痲݇@��0,ծpsK0\r�4��\$sJ���4�DZ��I��'\$cL�R��MpY&����i�z3G�zҚJ%��P�-��[�/x�T�{p��z�C�v���:�V'�\\��KJa��M�&���Ӿ\"�e�o^Q+h^��iT��1�OR�l�,5[ݘ\$��)��jLƁU`�S�`Z^�|��r�=��n登��TU	1Hyk��t+\0v�D�\r	<��ƙ��jG���t�*3%k�YܲT*�|\"C��lhE�(�\r�8r��{��0����D�_��.6и�;����rBj�O'ۜ���>\$��`^6��9�#����4X��mh8:��c��0��;�/ԉ����;�\\'(��t�'+�����̷�^�]��N�v��#�,�v���O�i�ϖ�>��<S�A\\�\\��!�3*tl`�u�\0p'�7�P�9�bs�{�v�{��7�\"{��r�a�(�^��E����g��/���U�9g���/��`�\nL\n�)���(A�a�\" ���	�&�P��@O\n師0�(M&�FJ'�! �0�<�H�������*�|��*�OZ�m*n/b�/�������.��o\0��dn�)����i�:R���P2�m�\0/v�OX���L� �\"�Ί�/���� �N�<M�{έ/p�ot�S\0�P���P^���τl�<������B�0	oz�����0b�Э���\$�p�П	���s�{\n�Ɛi\rod\roi\r��i	P��Х���Pj�p\r��.�n�F����b�i��q�.�̽\rNQP'�pFa�J���L�\n1<��\r��p��MP��	P��d����s�M�\\�\ng������\$QG�S��d���8\$��k�D�j֢Ԇ��&��������Ѭ�� {���{�\\�����Pؠ~ج6e���2%�x\"qu�ʾ`A!�� ��Zelf\0�Z), ,^�`ފ��� N��8�B�횙��rP�� ���kFJ��P>V��ԍp��l%2r�vm��+�@�G(�O�s\$��d�̜v�\"�p�w��6��}(V�Kˠ�K�L ¾���( ��(�.r2\r�6�̤ʀQ ��%���dJ��H�NxK:\n��	 �%fn��)��D�M� �[&�T\r��r�.�LL�&W/@h6@�E���LP�v�C��\"6O<Yh^mn6�n�j>7`z`N�\\�j\rg�\r�i5�\$\"@�[`�hM�6�q6��\0ֵ��ys\\`�D��\$\0�QOh1�&�\"~0��`��\nb�G�)	Y=�[>�dB�5R�؉*\r\0c?�K�|�8Ӑ�`���O7J5@��9 CA��W*	@�N<g�9�l7S�:s�B�{?�L�.3�D��\rŚ�x�%,(,r�\0o{3\0��OF�	��]3tm���\0��DTq�Vt�	�Q5G��HTtkT�%Q_Jt�AE�G�Ă\".s�Ӥ ��<g,V`SKl,|�j7#w;LTq��9�8l-4�P�m�q��\n@���5\0P!`\\\r@�\"C�-\0RR�tFH8�|N��-��d�g���\r��)F�*h�`���CN��5ʍkMORf@w7��3���2\"䌴�E4�MT�5�,\"��'��x��y�VB%V��V�T�5YOT�IFz	#XP�>��f��-W[͚�\n�pUJ�Հ�t`7@���,?��#@�#����}R��6�6U_Y\n�)�&���0o>>��:i�Lk�2	��u&�龩�rYi���-WU�7-\\�U%R�^�G>�ZQj���%Ď�i��MsW�S'ib	f��v����:�SB|i��Y¦��8	v�#�D�4`��.��bs~�M�cU��u��Vz`Z�J	ig��@Cim�e	\"m�e��6��M���D�T�CT�[�Z��І��p����Qv�m�7m��{���C�\r�L��Xj�� ��5�T��`��7UXT�@x�03e�	8���=���Ð#��jB�&��#�^ �#�o��Xf��\r �Jh�����5�t�|��m 3�/��oӬD�y���b�����{w�9����c��[��)�\r*R�pL�7�Η�&��l�Z-��w�~�r��@iU}Ϳ~�|Wȗm�SB�\r@� �*BD.7�,�3K\\V �<Xу���qh@�:@���+|x<��`�O`�̘��_c5�R�[�Qb�]��1]��p�f�w�\"�3XW~&n«M]�1^8��Q�?�?~�=��3��.Wi#��\"؞W��.2�L�~R�W5�VlO-�\0�ɍՅqjו���h�\r�qmS�t����o��0!�����н��OC됹-1@\r�;�� ��-��]�\$X̊���\\\0�0N���ц�'mH;�Xy��&�8��x�\r���Ʌl��y�WP�7�<�zSl�'L��Y����ι*Ϭ����ı�y�����\r�Ϡ����x:�֛�x07y?���YEz希�S��Y�}�yŖ4��cRIdBOk�5�������+M�]����o��3�������w��˗�����V1`9=�dAgyۆ؟�]�u/�B1��#��	��?��{#݋`R����ф�p��=�X{�x5�>\r�ՕU/��j����}�2FXߥ�����i��π�����%�����>�z���o�%�zL�Z��Z�pYs�yvʙ%���j�o�W��XN0��L�#�1g�M|��l�`q�~˚�b�J @�j��|5/kh�mz�[��	�i�Hr|�X`Sg\"ի\0˝�g7݋{U�����;c��hJ�\0�Ýr[@Ku� @�m���߰��`ɠ�ϟ8������I:�ۣ�[��z��#�imHq����Ix�+��~���y���@�E�����Wo8�Jط�rۖ�ݍ�}�����k������x��ݿ��.�=�-��9��=���\n�l�);7�2�|����ͤz�N�K�zO��-�F����r�w�p+U�L��:!�%�@��-R�}�/����<M�X���'�\\Y���&��\n{z������似��q�ON?�̕C��u�g�6�d��A�\$��8���x!�:��������������\r���9������n��8]�Y�8�)���@u6@S�WXܵ������؍�:��uA���?��̀C����9����xZ�`���U�<������]�]��=̙��=ʹ�l��隬�ҝy�9�(�ߔ.\n�6O]:{���|D�խd�q��\\]FI-�ŀ���a��l�õLn���v���y{��9\0�׈5��l�Ml�Rm�l�xb�k�LI�1���@����N��ܻ�&�ղ��H�KʦI�\r���x�\r��3���2,�s/����\$�������(��\r��J ~u��E��M�2��`������+�v�+��q㾓�8��|�侁�	����³�v��H��FpM>�m<�҅Oj����T1oun�fz�����J��Ge���+��q\nS%6\"IT5���R�����J`u�Tg���Y+�Ke�}�j��~�(��rO������T<����n~�\\\"ef�~=�����Z����)Y5g�\"'ZU���7\r�B)X1�;p.<�zM�L����]�y�9߰=�}�`�`��krU��)&V���D�b�1\\q�@^�\r�Bo�=�(SO|�L�����4D�.��\"?��/�}�` �'���g,A\r�ۓI.I[ENEL���=�J��x��K���M�\r�f`@E��P��`iJJ�R�#��&P\$N�{Ǿ�瘽UaP-y�֞8������=y#����~�u�Ohyb��RFԛ`���~�����#\0�a.~�����|��\ra�.�vS��~��Hz�>�T5��#pQzS�ޘ�7��zOO��T�12A1�PO}���J��yB\n�d�T'�+G���Kj\\\\Y�� �rl�dM��X�`]��}'Qq�n�a\r\$���=��2p���?�\ne�Q�޴�t)�\0;�1E�ae\\��U���aPt�������\"��|�1�CV���8!r�8]�A��.d�!�m��B咢���/a~RA)�P\n���Ꮌ�z�����\"᠚8i,���l!�a�XyC�*�h�ޛ�����Y�<C��p�����re��u���I����G�B����s���&�4^`mTCbR��\0�vL�A��е԰<e\0�Qa`���dZ���p%�D��@!���zzة1��lR��Lu�����tV��,G��,&w��L^a{=u��\"^��Ŝ���%�=6.��Ұ˭�`qI��(\n#%p5<DQ�ĺ�UC�%�j�4?�%g��&,��Z(@	�E�����#�4�)h@�#���ѯ����@\$�8\n\0U�ln�a(߁�4�O��8„7�ȍ(@��&((\n�D��m#��#�x\n�P��w#}P*	�D�yc���P�O|tc���P	�<m#�}��:>����\0����ťls#��GR�pp@��'�	`Q�}ctp(��B�eh\0��݁8\nr\"x�!c��>`N����Z)Dh\n*F�����z)A��6�\$�czL�2�\n>��ܐ\$�#��69�ю���!�����N����@\$�<	Ѳ�pV�Z���72>���cd0��\0��I�8�#P'�H	�o)�|@��*�\0APP��I1I�N1��l���&�JI�D���k��#p��~M2��d��(C��\rAR���C��&G	r���ң��RB@'�q�%)!�IV��	����B^/la.�^\0�<\0��	�<���=*@	��-{Mg�������'�X� �8���TK��\"��G��-�˞\\U��\0�,�s9rXn_����nS9j��`9���e�.�57^^�����/��K��Iv�R�O0��Zb� PX�Y�b��a�ȖR��.�\0�\0&��R\n�,�w\0�����N2�h �u	(@a2�i��c��`�D'���0\$�����k,ca����=\0���30�\r�+\na�z ��))*��8k�����˖|�E(�G7U�s!L��?5tF�rgGs����3��R�u��Ô�0�{l�\\M���I7ЙM��2��\\q8id��Zs����8���hMۜ�'-0ٻ�\"o\"|�Y�G�T��eT�B<\0�q*�s O'P/���윋BJ6��%�w�P��ހY<�L\\&C���v/�OO@Q�*��禓�/��&���X#7�.\0�xL�Bq<�oO��p	��g���\r���0��(a�?��OV��	�!ت�ފ6y�_��f/<��OH6f���'�@��P6xr저O'�A6KB��%)\0\\D���Z\r��\n̦��z�I��\\�g���Pʃ�����-z���NE@_9�mN�&�Up��!D�p��&4JK`� l��'���\0Q���\r��#�	:�ٶN����mO��F�p7��z�M��Pa�Eёi�N\r�؇(N��FQK���p+��Qv*�s�r(�%�2���٣9G��6�M\r���r��ZIS1a��zey\r��IT�ѹ:#����AfY��m��m'��p��1N�e�)KcFj>�_��.\r+�lzEц��0����8��'\0�T���(�I����.�{ѓŷ�;@5��-�G�5K1�.B0���{��&u���Tr�Q�G�)�vi��x��MJTR\\n4�/,�)�B����t�\"1M��HZt��4^}��FJMNb�����(�J����3�}��Uj+I���9�����:d\nP��ԣ(%-�K��El�ѧE g�`�T��HiV1���8ilj]Ku���+TܧXΣN�~SJ�+2p��P��U`E��D9Iv\\���U%֪%(�T:��\r5I�QUIV���^�@d�\"�\\sV�j�~�	��}@uW8��_�F�*MQ�fS�ud��6\n��	s�F���F��D�ЀeT����L �@{��� 2���+\$�*I6I�n�����I,��QUB��\n���.�Q��֥��^tV�L�h#lf���EXpZ�L��`n�H��\$7�V�Zu��i�E����B��-FI�B��Z`;�x�J�^�����T ��� \n��#\$��2M!'�����8�kIYf8���J\n�Hz�|\nB@=�,8U�:MX .�#�v�Ib����Ӂ�D&1��\$�,������;� le��*'�Q��>\n��8TڒQp�%rKj���'d�Q����U�X��;*v-5�ʶ��ʀ.7͘_�7�-h8�\$Y���f8�ͣ�|}��/g4��\n�Zu���d\0䁣-����g�4ِ�Va!�\0rn�4����f8�YἢS��uM�%;C�Eօ\r����aayZt\r�so�ɋMj�����\n�,�.�mV�z��^_�UQ���^��@Yk�U��J���j�`j�c���ēk�9���|��A�Uk�L�`g	��Ŧ`uL�fu��-�k\"D��6�*ո-�mZ�l��H�i�Adh4J�.��{G7�6�7�8�\n*I+ؾ�)Go,��a�Æ��I����j!;\"X�v�寭�L\"�.�6�)�#��G;�~��>\$hil?^Z�T^%��,WQ*.�RP��j��[��j��}!�J�\\����5}~k�K���WY'���B�J������c\0��R�5�ڋ@�	�v�� �������Ƒ�����m�b;��������6�\n�d���	�am���Y�K�����w�YO(�S�4�D\r��Yr-�@��-������n�x�������\n)��з v����&�/������\0�q��v5�b���q��H��N�}�\"5�ފZ�����*�3������%�nk^��R�B(���&|T�7�.ٕ�\$u��t�����v���\0�t`;کlU￲ٍՀK�.�����@�E���k��R��@7_� ���HC\0]` ���v��3���U�Z�S fCYl��`h\r���߁<��4����y����Fo�o���V��u��Yt� F��0ˑCY2�\$Z��r�����T\\\r�|(�f�(Y��vi��Ul�eG�Y�KR�P@@�HA'~�P�v��ar��0�m,@��N�\"�݀�)��Ѕ��YyAN�%��0��<<��\0t��xĈ1R�Y�|B� k���5�I�iR�.E�+ho!F{�l�Uˉ]�Xn9]p͈_�˘`���HB��^�=����\"z7�c��\\���b�x�.�fy޽�E��2��� p�1����X�T&7	+\\i��\r\"���<c��!�\$t�Dq�9��	O��ɏ�~-LM!�{�g�|#㸤�9��\\v�����ࡎ��%H(H��Ř��E����X���Ȃ0ۊ)unI�_K,�n���ƞ�x�l%[�KF��=���f@����0�{��#�0�XU�~T塔�7a�9ZZ�{�Qs�\0�j0�)��\\��Ao-�R/c�K���6�j�����|a�ˀ�@�x|���!a�?W�4\"?�5�+� ��˗qk�r�8�!�4ߺ����7~p��5���W��٢G&�;�vGWz+`��=�*\"�}�@H�\\�p���Ѐ;���fy�1�o瑜8��ᦝ��4СN(�*�~r�� 5g6{�{?���@4�QP^c����'�<�y�zv��?�f�`�Q�����;��Ϥ�����~��\\�Rt.��s���|�h!�H`��p.>�{3ށ�.;�����b���>ː	�1#B��kR�-�	O<aw�j���r�и\0\\����\\�Uh��(x���\n�y�l� 9��Ζu0���BY�Gy�g��O^wa:�B��7�&M�I�t���i\r�%��΀s��<�͡9�<|s�����?����]��@9�w�x�|y���G:;��g��\r+hP˚l�6�󓞺g���u��|�FP�j{O������A�P�D�����f�3Gڏ�H�H���#�h��*�f���h�J�UQ���M��K*|s�Иi�����n���O�|'�X�Z�99ȞV�(;��V�U�.}���]g?\\z��\0�;]J�wR�p�Μ�6b�;j{O����uI���  r֎��ߝ��gs\\:?ծ��խ���sS���5���%O�O��m�Oy���Bf��>��ǮU���^;ט�=��{lS��Ψv5��\0l�[�~�sᰁF�*{@,(�zA=�i�ki�Z������l�N��ٞ}5��-�i�:���р��>лH��n����\0�5�9�q�\0]cp=�+%9x�!�����Dx@^o�P��a���5O���A�m w�fI�g�@3��@�/�<\0���\rd>\0/��HF݀�p\0y�%�Imq-&�mx�X��6Q����n6<�гw#�}���	L\r�` ?���P��\"��X	��Nާb)M݄w�B�\0k��#�2���A���c���oM�\0/�������o\0.�b����-��zل�9���8�Ţ[����w˿M��{���Np-�o�*{��\0\0^��f����u6��e�Ϳe�y�'��݃�C�\r�v�|!��Ȥ��m����H�oB}������h��m�Kr����t7����~{�����-/�,e����u�<�oz;�ާ7��]�q#�[������|\nޯx��n,�+y�L�؞x��!q�{h�?��m��p|o߇w��~#o'�<k����%�9q�����ŮBm�P��W8���G�ۋ�V�d�M]�]��Aɹ�Hjv�[ 7���ݞ�����8�*�h����4��rA]�����\0��z6@A��Nc.Oi\0�es�B\0O�3?H�|�&�M�1y�/p�p�t\n�\"0�@�C<>���E����9\$�<v��l�7A�!�c&�������c�z<�8�C\$ND�����	�N�:N�<�2�WD�H~`(\$��Q�\0c��w��%`�tю�4+-�\n�CE\"�^�����E�R\"Or����N�\$­����	���N�����\n�a8��\0>�!��^�]w�4�z�x���<��`C�X�yŰ�u� �������`4��m�@:�e^��HD��j�/`>+X������� 0�8�R�X:��n��}����{)ӎF�+����%\0�ٺ����i���Vzp����lD�Jaq������kS����g�m�����gs�LC���\0Ѐ�\"���֏f)��b����׫I���V�pK\n|9}A2����6RH�:�TL�\$5N:�?�5��\r�+�c��v�~�@�hJb��}��zӆ7�L<��p�jyǧ�*���jA@d�\n\\�\"Q�YVz��o>�����\r��fYqN@+c���:lr�:��X�>1xvq��?tB�X1wM|!\"��5�@|��zI@��#���M%�I���p8�!'�]��@-�l�+亀� 8S���\r_N1�1�ԕI�T�9�}�	�|�y|���2:���8H2�&�#�����n�\$�����a@���aa� �*S�w@ّOHXjÞ��Y�=�P���\n��1ONW�Q<��f@c�!t@DF��C I�l�b[b]yf��r\0���f���}Z�2a@b�}��`��-��(�����p�o��a�/�\0�l\"�/�|��w���,�@	�״���S=c�;9��E���xh\"\"������z��w�8��|V�N��V��P\nה>�n��\r����~\nnMQEv���\0�}�C���0��C[�.3,r���)�P|�����%�G)��>S�\"G�vQ�?�?�W���#C_���� ����V�\"�6|���O���,��@6|�h�\n��������}=)�G�?Sz��頝�����s���������r�����5�\$��;u������~�AG��O�zE_��?�B�!	�#�4��_���m�\"U��X�-�Iu��x���_��������7�\$�������Df'1a���>B\"JG\$ձ��L̰����^�W���c�������=�]�_���� L�p�4>y��m��<�0�S�������꿋�O���n?����hzqf�c��(��N���\n��wB�����[*%S*@<�����\"F�	\0C*���n*Ԭ��	\0hO�@'\0����@��\0�\0��*���\r,�������\0��c�O@T����\0�6�;����82����נ��س�ס/�j�����V��T�*\0��	�d�@\0(	�g��		�z��/(\0�(��؊?�0\"��l0%n\n��5��\0=�<��K���!�h �\0��+7,Ъ�2�DhϏ�~�dN컀��ʮ�,�c���\0���H;��N�6p�)��5�#�-�s�H�2���,b��ϳ�P*n|��_𱎙�B�+���9�)h�ڢx����\ns+��s|��T�x*��n�z�R9!����z�\r�`��\0QK2`��	����>P\\��)Z�8r?A;V��t��H2>PcA���&���Q`&�'�(���`��@���):��!Ё\0����}p\r������(��@�%L	S�dh+:���/��C�L��jPx#���������\n�̂A���ψ�>D%�a�~��;\0ڂI�l�������>)0���>\\(�P��������).� n=A;��Bf`\ra�b��P=�\0E��7xk�Э����ʰ���	h`�1J\n��/\"��� ��dd-o�T3�ϬBf%S������B��0 6C\0��\r�>��¦A�/�=\0�\n���6B�Ź�ຖ�@>(~��@�\0��P*a/��9��@�\0�0�����������< .�>L�`5��\0����*r�xBn׊<\"jţ?�B�P���3�A�v�C߰��!�\r�\"��Ѕ��>���h`��U@H�:P���3���>������=�2��0���`>��\0��ஂ�B�C\$~�p��nŧ�{�`8\0����=����ICh����2A:�����?����`�ý��w+�hLA���\r[���7*cr�=�2�(0��/r+�\n���wӺ��Ch��h�\n3`HQQD�Zs��?#2Z\$\0�p)	�������&��ǀ,�T�	�	T�	���b 5�1��(.�A?��;.��\$Zsǡ	�b�6 =3�\nhBn�į�\n�!Ĳ��q.C�P���\$P12�y<N�5\0��M��E%�uq9���H���n1CD�	��E��p\"!�E=8\"QK?��灸�:��/��\n��\$O�T��)QC�I�P10�!�Q�3EU�X�!�+tWqF�3LVq4��곞���h���j;�Z0v���T �CcͱN%��G�\0���AXDy�3\0�!���#�h����fO*�T�!d���t]x2�[���E��)��l�Zk� Fc�(�����ȶ��E�l;^���n��\"� .�A�q#����!��h�l%\r�[���=�e�_�R�G\$0��9��p�K�C^�D�8�\0-�@'�2!�6*��9͢�%0\0���C�Dh���Y�\0�������K�f\$���kѱ?��2��Չ�<!�3�ʏU%�3�5����\rx8/`CZ�S����z�QEpH��\$�U<렣�>*���j9��;����.�����@������B�Ek(\"Q\$��C�Q�<��,HB=�\0�qՈ,]\0=���B\0001F����@�5��\0>\0F%X���!�f��4�2��'�ػOM�(@dB��y3�\n:N��<����0��\0��(���\n����O \"G�\n@��`[���\ni0���)�\0��T|)0\$��p\0�O`	�\"��O;�.\r!��)4�� |cG�(�3f1綠d3�!�����~����<�x�QG��lyMu6�ټy�\r��J�{@�&>z?��\$N?�#��(\n쀨>�	�܂���H&C�drH=�1�HV����%�t,P���!�U\$�\"�e �H(ڒ��C�/ ��!��ϔw��H��C�61!chmUy\"�M�H�Q���t(5\"�R>C����I\0��|�J(�Kl�%B����Xm[m�<���0\r��\0�x\r�K�۲��ܶ��w-�8g\$��J�.�hA�NI<d�\r���7 �����8Vo� 9\0[8��\\#|@,�9g��_�D0Ė�DɄE����Ix�����\r�4�iɠ����\0�&� �c�Y'��	��\n�F�g��&�Q\r7&�x�v�;'�g��<���I�xت��I%ܙ��%��e(��k\n\0�̈�Rm*�2i��\rܡ�9��'�h�=J+(��ҍ�/&�QAERԙ��/���zJ5),��_�H��K��@�rg�J+K~�ɞ�<�1(P���z��R�\0Nb@Ga<�U'�!��`hP�̍�&�c 5�U)���	����+�&� �#M\r�Q\$�&�8��+L5G�I�\$niF�|�\0�J*3�r�¢�p��	J�u��T��\r��t�r���o�d�K\n�+ <��+H%i��#r�#�`� ��1)\\��B>���d\0Z\\ܖ�<KG,�R��4Tc��8\0Z�K�{��҇%�pr��,���(����-㫲�8#.�eVK-�Y���).pZr�K�.�U�29U+4���Ks.����h][�`�2/.@���J�.d�\0ЁDI��/T�R���B=K����Q�+-42���,d��\r��+��3\0�U+����JnZ6I7�|pnY�B+K��'I�&|��\0\n�0�T�9�2�r�K���N�I�\$��B��jK�������|���L(��������M0\\�����)�'C�H�6�>\n��7L�2`��m:\0\0�B�Z����+�:\0+A���2��s/̵L�s̼�OЅ�2�̳6L��5C\0��3 ��3L�]��D�K��4��L�,4����(�ȓ���sD�,l�r�Kw,8Jr��\n,����1�B�O�%1��SML\r*T��8�.d�\"�K�9nSS�R�AQ�A2,�N0��,�sEMj��x�b��4���ZMT���!L�5���#d��*j�+��WM�-�\$�Ki6D�i�O0[�r��Ƞn@�K*0�ORك�;\0B\0�M�*��SU�5�aOb�e6�%R���l�H�7*K\0:M�2�sP���2�iK{7T��U̴&�^��@�/���@�7�#�}K��sy�aM`��ޅx��'�3�M�7��)�KR�t�L��8ی3|���(�kP\r��TM�/l�s��)s�3���/kR�K]+DW������ď&Q7�Q�m ��B;�L�T�U�\$ēRW�%���C��(S������G�D�:M�^L�ˌ3I}&�� �8�Ń/M/��NN�a�s���	JA�]J����D�����.|�\"��^M,ﳵ��<���8#Э,��)1\\�S��k<���s<�ų���0��6�\\A�2�N�;L���O<ğ��Kf��	\0�<d��� ���Oj�\\��x�%=���KOa>�S�ˏ>7`�);t�3�:��cϡ-!�k�y9����9T+H+�Z¬��˧>��S�Ϲ?,�3LO�<p�Sߣe>���O�*������?d�2�ϾD���K��C\"����@�SǓ�����CZ�d�tP;�є	�};;{���`����7@BA�-@����_>�\"��z1�c��/@�?��-@�:�A4�r�:xc�3!;��A�4OUA�4�	A��L;���J�d��.PN�A�����M:��s�I[;��Y�@Ԩ`��Q=l��#N���<P�B%��P�A\r#O����#MΡ���=��*��2��fkr\$-˞Q��>������2Nr��\0,Q��\\\n`[�\"��*F��>�\nTG\0V�U2�SE�#QJ()�Q\0�8	�<�X�h*�@�/Ee�FQB�[�耎��	(�\0�D�,`�\0�u���#���\0�?0�`&��E�5c��EFMTbQ,\r@)������H�!o�F�������	�%��:�[�+\$�?]���@\"�EE��+�\n>��D\0�8)\0�E:C\0!��D���Ѫ(��!Q�E��Q�D�9�J��D���5D�\n��R\">�\"`�GE#\0��+H�[�#Q���~ԑ%>��Q�H�%`�����!іL%\0RGI�\"�Q��E]�'�SE]��юJC��RZ�	�F%E8\nq��H]#��L�&T�QEF�\n	<��!�ѩJ\r(t��OI�|t�Q�KJ@�Wў�,4�QD��[��#U\rtQQ}FTm+F5�Q�?(\nTg��K%-tkR�IE0�RQ�J-�p\0V?�tq��\n�����\r�wR�KMR�>����ҏFU)4�ѕKM+�?�V>�\n���K*7\0)R�H�2 ��8 j�/�^M4}��G��\"�N] ���%HBC4��N�\0�Mt��G��`ӝH:4�S�HP	��RJ\nN�QGڎ`�4�G��LT�#q�&���K��&��[���7OH0���E%Ԫ�Ҏ�\n�&�T�@�+Ѻ�B7g��D��e	KP(�2�@�6��C��w\0�%-B�J�=4��x��>iT��I\rC�ҋO|}��G�I�#����C`T#Q]AT�S�R\rHu��O�[��TwO�#�+%MH�Q?K��\0��E�F#��?�.�QuP�I9�V�< '���J�S6>�c���N�H���|�| %��S���G�?�[���J�{��ў��U԰�D{�瀗S�5uF�4�A�H�Km�-�?䇃��?�0�L�H5L�\"�Џ�QT��N#�RgU5!�[ҳLUԉ�R)#V>�D��\n�;�M�S T�?�K�OT�%5�(Q3D��fҦu?.�U�\n�E��\0�JXc���=T�S��?Z�U�[W--uY��WA0�#��V�Y��Up>�\\�Ԁ��p�����	� \0��<1�ȇ�Տ�?�	��#�?�uT}J�'u~�2�P	@'��X	5B\$0}TG��Ih��a��TM��9I8�U�V:]PU��W����U�u�T�H3���#sV-X�N�VUTǵY�-u�VwW�a���S��	;�?�d���YRm?�M���֛P�h���M-g����\n����@\nTfT#E�8���}m`�S�K=[��T:�<��U�In���?||�Tܓ��������?��;[�h�iU�8�<��P�q�W�yT�Y5/փ\\eUU��9\\�*�{W[�C�cW1T�sU0�'D�	Չ�\\5#�)�[}tu�V��uu�֡R�G�*T�]�[���9T�w �T�bu��xMH�џ^-YUm�\rRjP5�W�Z�f���g^�4MשQ�x��׿^�xZ��K�x�+�r8 #�&?=�{Δ���W�Y�Qx�7^àU�\$T�\rblV%W�)�ã�R���\\��ć�����\r�\0��	�҂����&���j��H�_�L\0P�5%��R�L��W�[-|�����~Ψ��X��;��F�(�\0�?�CUW�K*82!��J���G�j}I��,��j��Q�\ne!6#R�R=�5֧N�75�X�\\l{��WRj5����Dz<iT�>�[�Un���#�[�>�X�Y�=c�X�b�w��S5O�u�*��T=dv:�'F	oR�M�5|�]I��Ȁ��\r\")-�hE?��ǹN�E��U��\n�\"�8�>��#Yd��}�caee�'T�t��R�AT�\0�O�\n�G�OdM�� ��d%/�#�P�MTdY.ZU4wXM�PYQf-(6[��>��|�f�\n٘���6Q�����R�H�F4�יd�84��DL �@*\0�`@��tRAH\n5�K�ܑ���F��4�\$�?-�4O��Q�����`��V}�\r�����f�0� ��H]�!�}Q�O��Q�Sm�C���?0��6�h�����Z�X����>���!C�V9T�`��Wb�d�/�4>�CW`�A�XkFE6�Q�7 �~�K�K��S��t��\\|~�v�ޓ����Ճj��������\$�Z�j݆��ڠ�zLTfZ����.Ux\r�t�Z�>�9��ZOD�4�H�Uխ�e��h�D�PZ�e�0��[V������>�^�f��c��c��`̇�N\0�iͯQ��\"\r�?�p0�2\0��e��U\0�>��~[F��کi5N��\0�_��u���Q`�t���S\\{�yWZ傠#���yt�@�j�\nV�>���mۉn5�C�[�nM���eGU+�uUKGUTuT�\"av�ڃZ��0�X��m� ����� �Sb	���D�G�%}��\$e\"U6��FRPN�T5YU� %�#m�C\0+ۀL-�N��wn,~�&\0�S�6ֶ��>���V?�X.��� ��ڍkJ8��Z�nE��m�AH���֍�N�[TcՔ�S�N} t�Ӧ��P��\0�\\#���;N5��|��F��4�\$�>��n\$L>��n�\\Crb8���x�}?�ז?�7՘�{I����SO���\0HX�g)\0\\d>��Ϗ�h���HyY՟��\$9N��խ�]b5��;Wh��/U>\r��0X�\\p��Z�qH��[�[|��T(?=+U*<�J�ʉ9�����to��I�Ȏ��3]�g�o���ԃ�3q��/֛k��\n����яt-��Q��\nc�R�uT~wV\\_�PV�]M��w\0�X�_u���g(�tfHsL}��bQKH=.�wQ�a%�4d[�J}*5�أ_�kљR����m�JU~�IR�\nV���GM݀,��`���zZOe�?V	Ӛ��>\0�>͸�&��w�#�T�OJ�\$�\"O@��[��]`1��l3Ω��o܃���#�ނ��\n\\���a+*�l�H:�ڃ��mg��;yL��H#�{���q�>�c,OT�S��%�/�@�!�<�:D&�xA�zXW�=�z\r�<���=�<���y��\0��6 4�z�㷱\0�z�L�3ޯ��7�� ����N\\�Hf���x�u��	�\r�΂�*��w�^���i��{�&���KQ�-����Y�W��ӯ����<��?%��\\�\n`+T�wm���S����5H�Lu��%�IN 0�&�kE�V���ѩ0W[�3���J\r�6���]�u\0�i}��7�X�~-p��TPx0���w[�����~C���_y~4��A�~�	�_uP��QQV�-5a�0����٭0�`�,y�7.�:��~��T�\08*\0004��7��+0K�a��`]���\"~�|I���۹�;;̲�5���Tn�CxM)8�Ŵ��D.���l\"O�#�,=|�oe�2�K\"��ag<\$��8�8!K�δ�d�\"	�Z���P%���.��?�0+<��6��&�;���{��M ��RZ#&1��Pm�ȿr���O�|�^���Sh�����S��A3�~�S�.��\\�HT�����Θ�4N�n��[;�>�U:h鳫M�r�N��t�&��<V�	N�|H�N����{ʯ�0�H.���p8���n\\����z��D����y�R���Z�-����nZb��O�I�����A�νᆈg���xg�t\0׆�ף�L�7�&��U9�	`5`4��BxM��!;ъ���\0ؽS����>�<7��D�f^ϞbDqn��P�4���(�!����k�?��!��2����/� ǡ�aj&#2Kakz8b^�ZY��އ��_W��&\$�@���ù8�U��X���Xʸ�	U�H&�]�NNj�^8�\0�Xg�o\0��v�mB�,D�M�2,D08Ta�^1��P�5�\"e���\r���n�@�۟i3�?�Kŉ�B\$Z,���� ��z�(�%�\0w�%x��W�+\0����'��A	�`68\$���kx����ٍ0%(�.[����!;�\"��X\"Ld��6��XM4yB�c������*CF@��� �5�\0��۱l�<���̱AHF��S�>�\r�J�л�\"�J������P㕆�Jx߆�M,�����T�g����RC�w��#�c��>�>\0�Mʸ��R��n��@/+�@;C�������+�<FȻ~-K�b�t+*��6�<�&����:aS��mf?�m^<>ᐹ�X��h��!���;L�w�P���\0��:�8�5�l�����\0XH�ǅ��;�κ��P��t�\$^�C��y����\0�+\0��#�\$����!@`�\"&	�G��d�e��\"8��s� �bx�I��*��:���� <\0��y5���X�E��!�+��]�;�d�cu�W�Gz��9���AB.^�<����:l�8:z�\n����t�^j\0�,��Sw�-���q����!o*���P74�D��YѬ�r�9!�Z�x�0���a6��;;y�\r��K3{���a�z��9a���c^�\n(6e^9��YΝ�o^Z9h&�z�Z`<��B\$[��Z�c幖�Z3|儚�E��勗FE�d]�d3�fe���ZR�aϗN^�je閰%y|eɗ�[�oe���]�q志�]�]_hc���W|(#��Am{�T�_��a��A��c!�F���E�3@͊�Q \\Ѡ���䏴D��QIEd�]f\r����c��]�!�7fjxFg��`�N\$G�E�!?v'^g2��_�\\t/�o�fhR��C����}�!14Y9f�d�#��󮙠湓S����f^kc�.A�#7�	Ff�D���Gi� r1K�Y��e��deN�u�6#�0��,�!Μf�	�i��F�I��C\0>q���zhA<���p\0��:(���g;h\r�\0�b�J��₞�y�2,/�lq������]�Ȧn�Of���\"���7��k�f�	�ٶʪ1y� 0�����7Ou�\$�Mw	-�NzUg�b�9��O��m,��ٜ�z8�מ�s�����\\Y�g��@ː��p��\r����Nm���H\"\0�����磟fw���^T���D3���	�\$@y���ڦv\$:U�#��]���4z	;:N���xށB8W������M.n�w�� H���P�!���l\$�@cÃ�v@x�*\0�{ܽ�O\n�A�`�J��+%\0���j���t�Z\$�KA�!�+\0�.V��i襝67�@�մ]z(慞�h�;O���B�赢ab�\nW��)�=077��M2Ø��ɚ�#Y��.a+zÁ��ヲ��w��>͢��e�L8T:/�j7�/�r ����79Ќ��hAh86���f��� �cH �j�*�7��F\$��5Z-�h�ZYh��+��g��PD9��7��ak鋞�B�磦d�:i��T�fk+�_�k��V��A���`�连�B���=*����`�V�S�5�v��ʢ#��C�cѠ֋X���S�:|�)�枚xc��v�X�ik��5����8h��A%��������,)�ޝٷ@�ل�ロx����f�񆣠���(\\�A.�HZ ����\n���@��7����-=}��&?�A�Z-S����[�N�^�X�^Ð\0N�Z�Sy&y�vh��]�!i�@��Q�N)4AP�(↋C|��ʚw�K���|��\0:ʚv��\r�3OW����Ν<,6�:����dt#`���a�j��&�z�j����'g���8	��	:|�������k1�p�\$�Bߪ����j\nn>�@��[�t��ui}�{\"�-K]�ٔ��u����Va�kK�M��㻇���a�\r��Q�j|�E��ɬ�@��@N�*h��C�B���r����l��-cN8C�*;�բ�\$/��#���|Z&����ݫƊ��9l�v��'c�w��aP�g�v�z���V��(hݢs�������	C�h�-�f���qI|25�dEڈK\"�P�\$�T��:F2��P5���Y����c_�\$�Ѽ>A�HE�5��,c�f:���60�lf6.��\r����\0j`d��X�u�9�g@��9C磀F�Y�ѱ��x�\\��R3�dt��+�1���@���!���L�36%X���:C{2�%V�X5�0���#G�[�a�쿪A���zT�	+4��4~?:�M�G��_��tfj�5�i�W�A�|����h����g�k湚��T�����`!�e�~+&b_B*�8����Sy@~�(c#�x\"9�������u��ʻ[�\"��#9񗻤\$_ӟ��5�gL϶���I�vv�@N=�����1��i��&2N��@N6ۂ�K���s�Q�\\��Z�]��{n`k��\nz0mߟfA���1&ۺ::Fg�\"J�N8Q 5����;h���-������A+�ѸP`Xia�?o��+�R ���kxMhǸ���]n!���y� ���h�<�[�L�2�������Fe�P~���e�q4��C������\r��f������<�x����zP����oD�!�VX�m%	��F�����{��	�~��n����N�n�^VR�h��I�l�����>S���-[l����Cfe����鉷�+���ʻ�nk��Y��\r�Y�Q�[�h+�@ݟ.ZGc��Emu	q�� F���;\\�Ʊ��{%�{N܏b�-��t��˶��;�l/�V���P�h�Ҳc�晻H����m���:f�|M.�!o����T�~&�Q�e��sZf���6��g�N����\$k�fX^Ͼv�8�=�~�4�p�y�|雿X	��2�_�Hm*> [|:2橚f�w�>��m��*��׈�K�.<Yi��PC�{⃿����p 	d_���?s�|�w����\$�J|�)�f���I�^�yع����tiKωIot\n�'8\$pQ����`�����v�&�<!4�������	쟬�K!p5c��\$���p����!���f�+�\0+�!D<�T�~F�g�ޙ�� m�6��ɬ	T-#+c�	�50�����g=�����ip�f�{��w;���U�yI=��.�E����b�g�.�`;���b�S����>�ƻn�Q�����n����:�[��M�_\rT�c�^z�����6:0G����c#<\$���׽\$����������.t���[��|;��O�~|r%.'�V��?L���+@����:�.cz	f&Q=��sۿ�џz�PIN�V��iY�D@z�Čf�d��/Zo�g���]��t�S�\rf���\\����a��+>8��K{�?�uo���|z�w(e\0�O^��' �r8T�����(3J���~�}I���^c�0��h�o��S�\r�\$��&e�q��p.�q��Ɔ�u��(�'˜�Z]1Z'):����ܨ��{�<����A?�)�d�`��\"Аԡrp`���%2�&ɳxDH��1\r�Ԋ�H/ˁ���|D�`��*|�~�Ї�/��DUB5�G���.M����e1\"n;7�ɚ0����r�\$_2<�!�h\rvs=�,��1�l��|��-�܃mN��!'01�sKck��H���&(\$4��P�G�Ȏ�H�h*�m�:a!\$��6����Z�iD�C7JKcl��9H�[�����<����o\0�G\$���K(p���Jk17=&��>x���K8H7>,��1�����:\$�3��?����?@3�Ou.�@��M)\n�A3�	�=��nj�a}�,�}ϣ1�CS�&�з?�%��@X�Y=�D��mN�C��M��/E3�c��hhM\r���k`n(tq'84ҳ�c>&P�3t[6/Gn�%%��s}�:�S�� g�+1��n��s��<\\��6�xi8��\rx��L�4x�*���R9�\0	@.�n��Bs?9bE�P���/(�7Z�h��y�F�:�!O�&�]A�u��6P\"�����N��҃�@@iV��:j��=s#���w%��wƅζeԡXmF\r't��k5X���w�R %Z%>�իsGtXW��V�\\�Y���M؁D�v���OV]U�sb'X�#��[�LV�S�]�Y�g��[\r�'S�c����Q��?�OX��?�]�_���r�b>�=U���\n�����g`�]��ږ>�[u6T�X�K�u���Z}}QAV�V�z�n?^�Au�c�L�r�R�xWGZ�׭�v�u�L�u=��-*5jv-�V5H��WMNݏ�_==���\"cU[֓ݣW!U�a���%��jm���T�i��ԐXjm�V��iet\0��f�����[joi]h݉D�UU�T���UU�Իګo���UIu���v��=V��ULL�Uvc��]��\">ժ�Uv+�\r�gҩT�h��]UP�ݎ��\\�kݾQoE����#WWWn�[W�K�5��W�/lU�w�-tI\\�N�W���gk�Wu`�[�5]ݤUy��iuxu�G__��ڴ�����W�\\��uoW/lr�W/l��U��]��r����n}����\\����l�sUovC�-]�UiWgqzw7G'e�r����u�S�W�C��WӱV��3]����o�QN�V]p\r�U�YOp�V7F��ml���YZ��:�ub(��%Z�?�5h�G����S����яQ��n�X`�JT����=�ԃׯw�WO�~����E�=�T�l�|���������x?��H<�i����@��W�n�P�lͼ���\r�%#�S�g�[�-\$�J��b'�f7X�cwe]�l��dՔvOYT-���Xok�:�\"��!�ގ�]�!����_�N\0Ha T�-���jE�U���fՕ���-xE���Hu�[o\ra	�ů���N�a(��a��Kla���D]�xQ\n��.�lq�IE:�a]��IZ�d��	Ak���C��@�Z�\"0{y9�z�ٸ`Afx\\�����ھo� 6L���d�������`�?�Kj�s붹���	�p�X`v����w��-���v��������i��B����p>k���`�EN��a��Td��k��m�[.�i��]�ك�#F팎.��c�-�#�D]���b��@ʸbt�N&�\0�뉼8��OžT�Rz��+�Ho��Ta�۰c����y�~	Hp)���~��k�^�<WD�>��S� ؤ����ޅ<.�yV v)��0�@��&;p-���SbUg������x�͌{^*��.��\$T\$ں��[���Y���LA�Վ��<k�:{n��L�mϦ^�X�z�����f�9�G�{q^�*@p�@����`:D\0��p����G0��s��>����^V�����M�&��{¨�?&�߰�����?��Ʊ�O�.�����+'^���׌^�#]��B��v���J3�4��;��ܘ��Ӭ>�lߏD�>��9��2�\0��o��d���HN�7{�������.��7���ydE��ma;�S�<I>��G�Y�`j�dv!�=z���p�\0����Ӹ^�;��pH���~hy�p���^�s@׿�`?�&<���<pI�r��<�4�n?*��	�~6�f����{�-��_'m�\"~=QȖGŻ�8L��6Л�ϟ����XU?�_M;���_�?�|_�f�|[���/황�p9��^���GgO,w�e�@m�\\�?(���t��I�ɿ#�1���}���`��&@m�?�_/a�.�\0숻����:q�^�o|���ib\rw�\$3K��D���u0+I��t�v�w-��D��&��qw��&KF�ޤ\r�����򇳱��m��w�]�ɏ7L\r@c��LC{�X=���XB�X�.��[w�+Wՠ5�E��.�^��?�C\0��~ �����'���'�y��i�������<��|5��p/���(�\r�>n��^���a���4��'�����-�]? �Ո�I�KN9��f[g��?�]}�1�|�\r|^�����һ�p���ʿ���F�Z>��v�_�~�H �5�'���Da�����c��\$�~C�O�|E��{���=�OS9��%�h[�EJ��^=�Og���Z���N47�AA��M��m��\"�t���.���wW�������2��E�'�����&(!���	�P%_�~��%�Pg<�̰�,q�`%��E����n�h̋�_�~���>��ooſ���~�1�I�[������q��kC,���Z��I`��?�� 1����<',��i�)�_���cEG�^��[�7~�x�@&��N|��Ɛ{�~�����hN�w��P��N3��Q�T(��mH�VFݾ�ћ���)���VH̾~�g\ry8J�ޅͻM��\n��J��,����\0����`㏝H�|,��/K�:���I\0����W�a��T�[�۝.�ZϾ\$����l��Y��t��y5�����<\\�ρ}�_�0rbx���AROf�6�\r���?)ա��D��e<��� ��o�!0k\0���ݰ���� ���<\0�ǀ�����ibTT��F2Q�\n(?o��4�xQ�\0h/T��t�s�S�N�補FƽM�7�\0���@Qp'Q����\0�~���n	}�q�`�����\"X�1xB���/�m#��2�^� ;@o�\r�\0\"xi�N7lnZ��U�E��h�SX?�r�2XK���->��y<��z��s\"�C6�0��0c�'ۚB\$��S4'\0h�2�zp��){���{�9&\0����㑄��[Z���m?����o���D��Y�+� ���6:�ԨQ>Q��HX7��u-�f�1@>8N��<�C~��Ε3�B6�M�k����\n�R�e�z:�7G\0���`j��������a6�l,cKzę��1�uٹ|v�9�+��g�����=��s܎���\$���Kwz/^��0��;;>�g�o��}�؄���7�̓??N��1��=������o�dP�QGH!���C��zXv�	��\0K鐆��e����X(�+��4��ʎ\nc|Vz/�`�3�i�E�`�������c�9��S��F�4	��ϋ���Y!��b8�z�������|�A�X��M��j�Kp��뒑x�'K�F��U�#��O��\$K���	�C�G���́��~;gЁ.>����l����f�+��AA����\r�g� ����Tܠ\n��5���.=�;���5����7�R�x�b�'�@8��o��Q�H8ß(���	4«��9���ރ�y1��9�x`��7��;��� W�8�t���`C�c֧�*��D)��SL�>\n��@P����5RkpL��F�� r��6S�l/�^h\0xOL�L��]K�vo �a�͒KJX��ڬ�uqx���c�w�&�.8(���[|�\$M�Z�7�F:�Q����\\�7���\rk��/-``% c�\n\0�O���/\n�Fq�G��ɂ,,�	�y4ف*�\$A�g�:l����?���9�p�L�P�\0g��B�1d�����2h��]��73��G��j(��Z`�H<\r[��!� ���\\'�lV���6Hy��l!�!���Ah>�|�\$�ۙ���i���V�����\"6l�&ݾ�fK�N��o�����G�[2AH\0��n�\n�TЪ�B�J��y��@�@'���B��:\n���GX%��`� �{����@H��\\���N�!Y˓�q��{�\$g5�\n4֒A��	\\֤l\0Zl���t���� \0\$'>�dp̧0ΩT�,U@���a�Ԇ`\\Ȕ8\r�Hӷ.��\0���\0�l�#BF�����9s_�!�;�i)�q9�H�\r\$�t2	!M���P�P�zI;����7 �D�\"t�I##�&�Tt�t1t렫RǥT����`��3�?�O�8�d���O�J\n�̒�3�	9�tC&�ja'bL�L���9%�87���8�ю0�\$L\rzd46��h�%�P�n�|0�_.p�<C�@8��B�+���(b1�\r��45wIP�D4�Y��|d|1w'I=SG�]��seC�.�`�a���14���\n�dhP�R�#V���G�� �D�VǑ�v��`�b�5����;�w��y�b�gy���*�S���n��q�*��.xU��}:��@�6,u����3��\n�ԡ;�S���>��g���S�Bw���Җ�^J����v�M�υ:�nԳ)�SԢ�B�u7j)ԲC�S~�]�B��v�X��C�Qk1M�=ut*<�+:�H����:�eM����HZ<�yt��Չk\$]�-%[���S���Dʤ�-=;�\r�{�Ū�Vy��Yئu|��E��T�t�	Z\0��j�����H�#p�� ��%��e��*3Y �\rlr��z�� D \0��MR����g������m�so�l�B;`[r��`��%��k���X\$��n���+jUP �[��,>��ż���Y*� �R�e����;AƱ�O���K2�s��u�4F�n���iQҷmGJ��P�@#��\\�F�K8Ő���o\$dQ�\"�WW�ї8�\n\\��v,;�^�R ,KH���>�\n�V��w�t	w:�w�*�{)A\"���Z��U��C�3H�Փ�%1sm��~��=T���R@�u��:%��TF{���ޝZ6����׎o��\"I�2�u�'m��]7�F�ؕ��IvЎ٧�~{����s��\0���V���9�v�\nA�FP(��كC��C��s��� �T���\n��y�C FBkPqm}�̕���X��\\��_���+��7�'z�G7qo\\�a�kGX�Oj�C�\0U��>6���[��4,x�5��w��B)6�Ù�4�pOuB����M���_�\"N ���xc%��92m\\��>&iUX5�,o&�Q��y8\r��B�2wT��t��o����TiJ��\\#�?��U!�hl{Q�;���b�@0�0�QQ�PI�Q����\n\r�iE\0�Hڮ�NZ��(袘0�[�q�JFҦ�lT#�\"�5��ɡ<W����s�m��w�0��m�H�0�+:O��]�P��WA���b}�y~e�\$!�\0��`�	�3x:)�i2���f�����E3?�j���ґ�������\0�|�Ѱ�k\0�b��P��p�@\r���\$2V~Y�	X�\0P�C}�N\n���)A�@@\0001\0n�6X8��\0�4\0g�.��`\rȇ&6��Z8� �z\0\0006��\0�/@x��~����\0p\0�/Ah qz�\0�0\0i�/�_��тb��\0���/T_X����/F	\0rav0�0�{b�7\0n\0��^x��w\0�2\0nq�.���@\r��F#��~1l^軀\r�\r\08����ta0����5�C�1La�ь�\09\0g�1t^`1�\"�E��7\"1�h#ѐ\"���\0aB0�^`�|�J +\0l\0�1�aȽ�~��9#ez0�`8�1��0\0002�s�/���Q���\\�e�0�d8� 	��f�\\\0�/TeH�1��,E�\0s�4,]�@\rcF.�L\0�3�a���\"��7��2/H\0���F��v0�i��Q���F|���0lb(ȑ��ƕ��\0Б8��#�v1��0<g�ɱ}@F����5�]�ϑ��F,�]�5,fX�Ă@1\0006�<ax��b��ѥ��F&���3�_��Q�cƜ\0p\0�0�g\0�cAF4�iF3�l�ѯ�;�%&��4\0X�h\n��\$�7�4m��q��@F���6\\b� \r#M���1�n��1y�UE�-\0��p�Q��Fp�5b4�^HҠ\rcu��\0�4�a��ѰcE��~5\$b��qɣ}���/��ۑ���F%�#�4<pؽ�����9ta0��#�3�9�6tmP�w#Ɛ��v2<nّ��^�:��~:�ih��c&Ɩ���7u��q��9���Z/�8Ǒ�#-���J6�`�q�#o�4��8�vX�Q�#\"G\"���2<q��q�c�\0cB8�nHױb�F�@W:\\u��Q��D����&/�qX�q���GO���9k���.�͍�.5Dq�ӀcyG��	�3D^�����G\n���2kx��Ɩ���;Dhxޱ��}Fw�<\0�<�c8���cx�ɋ�^;p�ܑ�#pF�@W��lq(�q�ct�;�E:8�H�q裺�ki�6�a�䑈c�F��X�wx���#�F��7�p���ڣ/����<�xx�QȀGڋ�\0�;�q����KG��3�<|n��Q�AGюGr8c����s�ڌ�\0�5T{��q��f�m���/������#	G-��ZA,n�1У�� �?D`h��ãp��n�^<�m�ձ����'�{�<�o(汯dG\0mj8|m��d��\r�F�!67�~��c%ǽ��HNA�h��r�I�8�}J4<wx�ѵ#GHO�+�@^���Gw��:~����*Ƅ��?4d�Q��F�B�2D<r���	\$+F�1�DL|h���\$F��B|~H�њ��G�\r�/lf��1}����\".D�_���?�(��\">3�y�	�\$EG�O�?\\v�����OǊ��\"�D�yϒ0c�GՏ!�>4th�1ڀH8�grFtm����cI�b���D|�x������ ��J4��Hƒ#;G׍�^F<����^H����@��yQ���ƌ��ZD\$_��c\r�4� �3�j����cFӍQ�>tn9	�\"�ǘ�i.B\$xx��C�F_��\n6\$g��!#H[�G\n=�n(���\$:���\"�>́��1��nF��i�6Lh���\rc�F�� 0�r�Q�����#�:���Ѩ�ȥ�5 21�hƑ�dR����F\\`y�}��F��\$��nX�\0�qF �O�1�x)&����֍�!�2\$_Xϑ�d�E�5�J�mX�rN#%����5\\g(��X�4IG�QB9e������A\$r1�yX��&c�G/��r7�X�rA������V4��X���Ɂȟ�%v2���N#��!��&N6�������,���G̀��\0\$b��W%�9�b	��c��� �7�i	\"����\"=dj�\$2ZcyI��\">7���Q�i�h�� zHi��LuI@���1������I2��\$N2<�����@1���&�JD���1�c�H<�� z7�(ɑڢ��C�IG�g(��٣�����/T�	!1�#j�Ύ�\$vN���Ȓ~dƖ��'�@,���J\$� +a�?�dH�r� ����F�����OcO�O�g2>Đ��Q�#�Ɛ�U*B���;Q��I����K4^��r�9F&��\nRR!���q�c�I��_�9�o(챇��H?��264�����\$�IO�/\$�2<v�\$�K\$I4��&J=�Yq������ �<���cI��%�8ܒ�ñ�dU�^��(�G̤�:�F�J��\$&K<�/Ѥ��G���)vO�iI#���F8��*S�`i:Ѣ#�ݓA)B9Ty	2����+�KR?��x�2�\r�Ք�\$�8\$b�2'e=FM�#*JO�hH��&Js��*�2�~�Ӓ7#i�eG�t)*��cMIJ�?�8,��Q�dJ)��:;L�����#\"�܌\"�;��8�qy�\$�x~e*��Du�c��Rһ�qx~ek�������dҧ���aV\"�{W�qb�u(2�U�+6Tܿ�W䯥J�y)>V��*X:��wdpe��sv����\n�5������w\0W�H4�U\$�-�.�U��Cť>��厬s�,�ջ�Ie�TN,M0@\nc\"��/�!�\0��iJ��(t+\$֖�l��Y��g��)\"�Hiٺ�%��Ԗ��v@\0�XB�%���@)�Yv�F��rƕ��K]�����%D��֝��RE�[{�U\n�e�:�e-ĕ����dp]���y���Nc�Q9��\n�9�I�5a�T���R�����+��r��ؗ(��Y�@�����]C��+�*�E�����Ú��r]8~9t�Z��˩��\r��9c2���%�������G{!��?�ϖ�.�L뽠��K�Yr\rQ����0��V�F\n��^B�X*��9;�\0���5E�*%��o�2��W�������V�ħ�d��u���\"����+����'�VJ&\$h�KB����'��w�tvZKT�h�Ɨ��LC�/�B��\njr�����\rQX��T�Ds��t�����@!*�\0��v_��E|k0Պ�BY��}���( R���g�^��%{��]�K�W�E�`�:\\�l�h��ݖ:�PU�aW)�H���X4��������^�-�bT�I�\nqa�#�T���N)BV*qU8��\n�0*��KV�(����bZ�� �V	�Vi1\$\n�ƕ�+�&2-L����`�;�CP�K)���2c�em��£Zrp��a��ɏ��U+b#�25FD�Ւa��|ux��xӢ�JK+��K�|=�;�t ��̤��a\"�Gq����X/��I���_�y�I�]�[t�u�\n���/���,>��U�k�®����F�\0000�%fc�R\0�w���k/\0�\"3V��eҜ٘H�����W����+�w^s.a�̫#PF�L4���ε�ເ�4���������N��&b��5f��]��n[���g��`fr;Q^��gj�wUjHIM	W*쬔B�\ne������r\\��u8�KZ���o���� �˳R �hں���D�KX�%4�h��E��C���Q�a2�H�#I��}f��7�n�ni˭�>k#&})SR�3	[��d3O՚*	R��<�ɨ�S���x49�t��J(�G(�vƥ~hr�ib�T��K������\nZ���_�4�j��EUS[��@�92�V��ɮ�Ԩ�}�Ⱦ�?�!鍒�f}�^W�49Zj��ݪ���؛3�k�)���^L�v�ht��K]f�͙\\&��l�53sH��rv��ctٕz�/U �x��1Bc�����TX��_o5�N¹�u�n&��_)5�W���j˥�-�f\0����u.���S����gu\$���L�X�3�ج�I���fx��u�-�b|��3<�r��SLꤓ�	���f���ȫ�\\r?����]�M��@D�7�4Ή��'3�h���\n;��<#�77�iK�\0\n�HU&ͤ�	7=����#ʵ&��Ӛ]7�p�#%������5�\r8uV�ʉ�{�<#����R|��u�m	?�ܚ�tJo\\��\n~���w78�ql�Y�\n���/����q��C�SX�<#��8^o\\֕ܪu���EW�5�c��>��g'�ɜ-5�o\\ו[r�*,�w�5�rr�ٰ/�LnV�6_t�ՙ����KU���o\\ݙ�S:��qv�7�۴牳�H`�zUY6np\\�%ғI'�S�£�cj���\n�ex�x�Wze��9�=��H�\$fm���jv!̩w�795ޒ�ɽspg9��[F���<޹�.��?��x5׬�iċ��9(�����n�u���IͰ�-:�t��0��&�:��̩bg����j�`��ת�~��e��+7�D�GU3��\r�����p:��j���\"]+:�_����U��N�[-e׬��3�U��:Q�8�w�i��&��\0�a;�R�﹪X'�*n�8�j��W`��]��!��r�b��xOS�9-E���^��'�ΰv/<ru`���-�[�G-^k��uK��Nd�Q9�~\$����ĉ��`��S��{N�'6O6�9�xS�i���\"4O��=W4�	���&�M��;�t\$�����;�1�p\\ϗ^��f�N��\r2�v�=�y�����a�Ʀ�zD�ed�U�T�H�V�buj�ٹ�#���z<>�w�djV(�hX�Z{�!�	j@V�O�\0�}Z�`��@\$ϊ��:�g��uE�TrO�����d|�i��UO�w�3�?��\n�C+\0V�g+�g*��²��%��\0�}���^�\0�-�]����J/İ o��7�\\7-9B���ç�*��]-:~2���e���[--�J�����Gf���\0�F�]i�m냧ĩ_��:~��Y�ʿU\0S\0�3F\\��	�Uj�៪H�֜���s�fܯ�Z�<q9�S��O򖈳����\nL��\0_Q�9uT�So��b�rx\$��}�������_':����5���U )�Z��d����3�h:�H���|�UY���)��C5)P����WP%Q�F�_�5�nh/��=7�It4g���\0��J\\��U�s�嬒&�V�Mi\\��	3D��M#�A(ʢh~T&�PN��Ai\\�)t4�*;+G�4L���qn���˟�@\$t�I�4\"<;�SA<�H��<�v�e���p�Yk4h(PfG�A�g\n�%vD�g멳���kD�uB�#�F\\��;z ���JP%�Ћ\\�~oU	Jst��N!U�.�n2�Z@\n(UN�R�/=`����,�*��V���=%��-����/!`��y�!�-&\\19*��\n���I@K�Y=3	N��%���N�P¡Y8���UӔ\n'�K�#X��`��@� T����R�V��0���r�.Xo8�f�\nĠWEL��F�cmuwȠh|��U�6�Z��}�*��,�Xx�MR\rW��g�-+�t�Ұ�tBV�P��igUY۫=\",�X�;N�}z#�;��O�Z�|�\reD4J��P�XD��	x{TL	[��W�D�d��`\nj����e0*����NhK�\r0A�ӦZ3۽QK�1A�\\>Łr�^(N�\0P�	\"���45����&m���T+�NDZ�?-I����A����D^H�f��Z�Vl\$�����)���h��!Y���`��BJDtu�6_���j1��г\0QD�uº�!�b�)�֢k@��\"��X�a��LK\0�Ό��U�3�N\"3����g*����d�\"*�S?^x����4c�`G\n2�EO��Z!jm�A:2��?T��8d���,W[=6�(\0\n���uf��]E�d�	PE#q�~���;j�٬��Gz���Ec�6�0��[^�nc\$:5�'(�KR��ou��0ā���c��jʰ!�-��س�H\$�u;*]h��v�WH�(~�xS�f�O�T�6~����~���R�SHEs�!U�*��\0��ܳ ZC����*��Frq��EB��v*TY�HD���2C�s��՟�C�Q�\"`\n�\n�M�Z������B�Q\0T��6���@���RG����W��JI�W|�\\t�UP*�u�A�RZRH���5HS����TN�����K���rOe��2�X�u�k��,���JF�=��K�h������Ɠ\n�5ڪ]��-\nTH�e#��n��PE�CYJyrD�/�\\)A#��ve�!ZI�R�&PE]�7����H��2�(�T ��Q�)�L���\r��@1]\n\n��IH��+@®�_������-�F�E(pg��1p.QkM������:\nI��-\0V0��y@����4p�-�1�L@�\$G�4m�3x�I\$��G=}K�f���;��2\0/\0��3\0b���`�\0.H\0�x)\"@Ia�&���Ԟ�!-����S\n�\0Ҙ[�*`�)�ƚ�\"�)�0�\$�\r���?�(��1�c4����8�ELL1xW�4���\$IL���b�+��)�!|���M\nbT�i�S\n�uLb�E3x���C��B\0kM�E3:c���S=�iM\"��3:d��)��N�WMR���e��)�S7B�MB��bZl׍��\\�b~33Jl�ͩ��9wL�<�=cQ)�S6�M���4\nk��)��o��M�S69@���E�M.��4��)�Ӆ�(��8�j�����W�%MM(\nlr�)��8�;���1\nq��#�ӄ�/Mv8M8�o4��S��#N���p�r��)��>��N��=;jnt�)��|��N򙴘zwt�i�ɇ�O�D�zo��)�ɇ�EN�Zt��)�ɇ�UO�,�Zlra���ا�NV��g�sT���ᧇM���6*|����˧�LRO\r<�z��i�����<�b�w�SЦ�O�=>��ϩ�S��Oʟ�����i��E�P�U@�}�����PR�U=*lu����P.�m@Zb�����NΠL�*|u�\n�|��PF��A�u���4��P���CJ�5j�èiP��ĕʆR~�!��'.��A�q�j!�7��P�/�D:�u\ncpT%��~DB�rr �#SA�Q&�T�:���A�N��Ln��:�u\$A�V�� �M>*�4�������P���C:����9�f��P��]�u�?�e��Q\n��DJ}a_*T	�Qv��Hʌ��FTc�R2��4��jK�%��L�0]9�	ojSy�CM�\\��U*R��h�X�Ξ�J��T�i�ԧ���n�%;z��.i�ԡ��R�T�\n��.j1Ծ��R���K�u.*`�V�}Rޥ\rH�o�4j^Ԥ0�S�T�ڛ+c^�۩a.��K:�5,�u�੗SF�uMjv`�4�%�)&U85M��u;c7F}��R�3N����}���S��~ʠ�jbԤ��T0�P���A�tԪ&T:Q\n��*�����R�>EQ\n��)#�U�TR�mJ��@�U\$�R�I�RJ��*�M�\$��R�?�RJ�5,#yU�ATڨ�J��uM��Ԥ��Tڨ�J��uM���:�gR�B}P8��R��԰��U*��J��uR��ԭ�?U*�-T��9cq���Uz�mK8�UW��ԭ�Uz��K��W���b��S��uP2�U\\����/Uʫ-K�u\\��Գ��UʫmWj��@c��\0\n�V�mO�4���UyK���]X�b�9\0����V��ez�4ޡW�WS�]GJ�ugj_��IV8�Zz�)|Ы�}�RM��5H*��x��U��8�&1-Y���n*EU��yT���M��q�j�Տ��W�[�q���Fn��Vެ]O��u?*����q�4v��5k���뫳VA]\n��Dj��K�\rTV�-Z���Fj��܋�Tv�-Y���x*��êKWګ�YX�5a������V��m_j��n*���\0F�\\��<�3*��;X�Hj�Q��Ǭ�wXB�L����cFU���V>��\\8�a���\"�����b*���kՋ��X��T��uR�����WZ�\\cJ��T��+��Xί�^:�u����c����5Vz�����G��Uήd���]�(�'��W���nJ�u�j�F���Y\r�3:�W+%�X�J�e�ӕd��Vf�!Y���fԿRW*��\\��r��[��2~�V\n��S\n�����R~��I���Yέ�g#�r~�:Ԇ�SJ�fj���k9�׭Y���h�еrkJV���Z>����ar�CM?2����D�_��'�N��,}g���ο��\0K��Z�r��)j��+W�!�<<R��L3�QF����Y\\�J���D���Q���G�����g��[��2zB�`	k�g�Л��Z�Qz�Y��fźМy=\"u��I���DmRA=M���Y���*'Q�<=N���pn�k|M��_Z�zD�W^����-�vi\$ʵw�k+OORD�\"[��*�3�f��(�!4:o����A��V��Z�ڬ����V���\\}s�O3@f�V��n�nq��i���UV������\n�)� e����[1t�������zMǮ�[:�MmTtU�UVԚ[^�t�z�U�&��S��G��d����ԧ��쮸�)`�؉���7��#6Bn;�U��+wL`��=�{��xt���Y��6�t�pS^ծ����6�󸤈3C�F,p��6������R�f���G�HeR��I��'H·WZ�Wt�:�4:��V�wO\\�E-'t�ɧ��[�b�T�z�˕TN=v�:r�J��ͫ��s�s[��*���r�G%S[\"������﫡WIH\r[Js�t��\$q+lW����W-m����p�a��1�y�vj�ӭ]�Lz�ذθ��*�N�'<N�\$�:js���r�ߔ�N�����u��qu�<#�ʫ�\"�'{��Q���x0\"�[��jPTU��\0�6ed��|��,�X��aJ�9���W�	������	��w�]��仕����-EWp�V�zʉ֪ӝtð��2�X)J�I�&@N7-��'�G�@2��l%��6\0�\\�20i�{��m��\r����b�@�q��8�A@��;=��M�����/���a�\$�,ЫmQ�_��B�y��A�v'�&(~�x%�t���/�z�I�Xk����XuJ���U�k6Mw�w��N��6\"��Xub�b)�5�VD�zP_�>db�\"`w��^�0yA~S����ZN����xm\\�����j7�d���x�ܠ�eLx� `M�1WKFRy���*�43��b�{�(ت���9b��\r�^]��FZe�<F\rcT0�Es�0�`�L�(�Ì���K��3����ϰq��D�,�50)Kͅ���0)`aP�X>�c�&(G�G��)���!Z��Ǡ�M��L�c\\���\r,G���`����k\",d�2h40�^sN��V'ly��%��9�K-,X-�7`�b̮X�d�l\$����#�+2S!��R�zh�d�Ë�8�|7�h�����E�LfaO�Xc%e1}�F6RFdc�aD+#�l�<������dN+����QEz�\r�P%A������½�}++,�Fl�4Q<�a03p������Yt{���xP�\r晈��l��˽��@�^l�Y!�\n!)���\0���lĸ:{ae���HD)��ℍz���5�%�)��֠Li�fZ̢�D����[\n+f]�Jࢭ&\0:A�Af�����(�Y�Y;����wT��ͭ��9p&B��f<{�}�APb˛O\n����h<��w�C:��%��X��\n�@�n��[{ygy���\$�VxA�Y�<rh��8tVc�*,����cz&8B�:��fB�c��\n%zD�[\$s!l���j�n��g��_�ؤ40�Sk%�J���aP\n�Aa!�!�,�V�\0C�kB����xd^#�1xǫC6����/o�h��RtJN@6���P�ϋ'����Z�J	5�h\$�@Рm�IJ�ѵ1G�\0��	ҰP�ۂG��Zd8�f�s�����ZT\0���\$��@J�m�+id�H*kK�}�� V!i`1}�f�\0Hl����n�Rvw�6�m,���Gi�ChS4֚,MZc��ހ+6�l�1�ɡ��\"���ʑ)Z�Oq��D�'�6}*��{j�+R����=(�2��Խ��b�q׭Z��g����VT���*#��R��!�9矯@��f�ij�\r�K-L��ڀJ��h`�M`Ga5��;����0�|�0鰅k-��(K����3kX޽��!�\$@�ؓ�<�A�\"�[�f�K	���\rRӲN{]\0Qiz*��%ȑh�W��mv96��\n�������[�E�;d Y]�Mmw�ϵ�0�n=��F�FmqZ�\$ ���% Tg\n�w�8<�̕���w��f�P�U*-c�h�д�e��H��6����ʹ��N�l8IG��\0 �2�|u���x�O\r��SK������3�(�WrI��49�q�����ʹ�N\0	�)��smEƹ+g�-���md�XԎ%�ixFȹ	�#6�S�[RL#Z�D=����EES<��!�5H� �����x��lJ�-�e�!׊w��{کz��ZL��aμ��+\$UF.�b9]");}else{header("Content-Type: image/gif");switch($_GET["file"]){case"plus.gif":echo"GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0!�����M��*)�o��) q��e���#��L�\0;";break;case"cross.gif":echo"GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0#�����#\na�Fo~y�.�_wa��1�J�G�L�6]\0\0;";break;case"up.gif":echo"GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0 �����MQN\n�}��a8�y�aŶ�\0��\0;";break;case"down.gif":echo"GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0 �����M��*)�[W�\\��L&ٜƶ�\0��\0;";break;case"arrow.gif":echo"GIF89a\0\n\0�\0\0������!�\0\0\0,\0\0\0\0\0\n\0\0�i������Ӳ޻\0\0;";break;}}exit;}if($_GET["script"]=="version"){$r=file_open_lock(get_temp_dir()."/adminer.version");if($r)file_write_unlock($r,serialize(array("signature"=>$_POST["signature"],"version"=>$_POST["version"])));exit;}global$b,$f,$l,$ec,$m,$ba,$ca,$pe,$fg,$Ad,$T,$_i,$ia;if(!$_SERVER["REQUEST_URI"])$_SERVER["REQUEST_URI"]=$_SERVER["ORIG_PATH_INFO"];if(!strpos($_SERVER["REQUEST_URI"],'?')&&$_SERVER["QUERY_STRING"]!="")$_SERVER["REQUEST_URI"].="?$_SERVER[QUERY_STRING]";if($_SERVER["HTTP_X_FORWARDED_PREFIX"])$_SERVER["REQUEST_URI"]=$_SERVER["HTTP_X_FORWARDED_PREFIX"].$_SERVER["REQUEST_URI"];$ba=($_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off"))||ini_bool("session.cookie_secure");@ini_set("session.use_trans_sid",false);if(!defined("SID")){session_cache_limiter("");session_name("adminer_sid");session_set_cookie_params(0,preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]),"",$ba,true);session_start();}remove_slashes(array(&$_GET,&$_POST,&$_COOKIE),$Xc);if(function_exists("get_magic_quotes_runtime")&&get_magic_quotes_runtime())set_magic_quotes_runtime(false);@set_time_limit(0);@ini_set("zend.ze1_compatibility_mode",false);@ini_set("precision",15);function
get_lang(){return'en';}function
lang($zi,$if=null){if(is_array($zi)){$ig=($if==1?0:1);$zi=$zi[$ig];}$zi=str_replace("%d","%s",$zi);$if=format_number($if);return
sprintf($zi,$if);}if(extension_loaded('pdo')){abstract
class
PdoDb{var$server_info,$affected_rows,$errno,$error;protected$pdo;private$result;function
dsn($kc,$V,$E,$Af=array()){$Af[\PDO::ATTR_ERRMODE]=\PDO::ERRMODE_SILENT;$Af[\PDO::ATTR_STATEMENT_CLASS]=array('Adminer\PdoDbStatement');try{$this->pdo=new
\PDO($kc,$V,$E,$Af);}catch(Exception$Fc){auth_error(h($Fc->getMessage()));}$this->server_info=@$this->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);}abstract
function
select_db($Mb);function
quote($P){return$this->pdo->quote($P);}function
query($G,$Ji=false){$H=$this->pdo->query($G);$this->error="";if(!$H){list(,$this->errno,$this->error)=$this->pdo->errorInfo();if(!$this->error)$this->error='Unknown error.';return
false;}$this->store_result($H);return$H;}function
multi_query($G){return$this->result=$this->query($G);}function
store_result($H=null){if(!$H){$H=$this->result;if(!$H)return
false;}if($H->columnCount()){$H->num_rows=$H->rowCount();return$H;}$this->affected_rows=$H->rowCount();return
true;}function
next_result(){if(!$this->result)return
false;$this->result->_offset=0;return@$this->result->nextRowset();}function
result($G,$n=0){$H=$this->query($G);if(!$H)return
false;$J=$H->fetch();return$J[$n];}}class
PdoDbStatement
extends
\PDOStatement{var$_offset=0,$num_rows;function
fetch_assoc(){return$this->fetch(\PDO::FETCH_ASSOC);}function
fetch_row(){return$this->fetch(\PDO::FETCH_NUM);}function
fetch_field(){$J=(object)$this->getColumnMeta($this->_offset++);$J->orgtable=$J->table;$J->orgname=$J->name;$J->charsetnr=(in_array("blob",(array)$J->flags)?63:0);return$J;}function
seek($C){for($t=0;$t<$C;$t++)$this->fetch();}}}$ec=array();function
add_driver($u,$B){global$ec;$ec[$u]=$B;}function
get_driver($u){global$ec;return$ec[$u];}abstract
class
SqlDriver{static$lg=array();static$he;protected$conn;protected$types=array();var$editFunctions=array();var$unsigned=array();var$operators=array();var$functions=array();var$grouping=array();var$onActions="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";var$inout="IN|OUT|INOUT";var$enumLength="'(?:''|[^'\\\\]|\\\\.)*'";var$generated=array();function
__construct($f){$this->conn=$f;}function
types(){return
call_user_func_array('array_merge',array_values($this->types));}function
structuredTypes(){return
array_map('array_keys',$this->types);}function
enumLength($n){}function
select($Q,$L,$Z,$sd,$Cf=array(),$z=1,$D=0,$qg=false){global$b;$be=(count($sd)<count($L));$G=$b->selectQueryBuild($L,$Z,$sd,$Cf,$z,$D);if(!$G)$G="SELECT".limit(($_GET["page"]!="last"&&$z!=""&&$sd&&$be&&JUSH=="sql"?"SQL_CALC_FOUND_ROWS ":"").implode(", ",$L)."\nFROM ".table($Q),($Z?"\nWHERE ".implode(" AND ",$Z):"").($sd&&$be?"\nGROUP BY ".implode(", ",$sd):"").($Cf?"\nORDER BY ".implode(", ",$Cf):""),($z!=""?+$z:null),($D?$z*$D:0),"\n");$Kh=microtime(true);$I=$this->conn->query($G);if($qg)echo$b->selectQuery($G,$Kh,!$I);return$I;}function
delete($Q,$zg,$z=0){$G="FROM ".table($Q);return
queries("DELETE".($z?limit1($Q,$G,$zg):" $G$zg"));}function
update($Q,$N,$zg,$z=0,$lh="\n"){$cj=array();foreach($N
as$y=>$X)$cj[]="$y = $X";$G=table($Q)." SET$lh".implode(",$lh",$cj);return
queries("UPDATE".($z?limit1($Q,$G,$zg,$lh):" $G$zg"));}function
insert($Q,$N){return
queries("INSERT INTO ".table($Q).($N?" (".implode(", ",array_keys($N)).")\nVALUES (".implode(", ",$N).")":" DEFAULT VALUES"));}function
insertUpdate($Q,$K,$F){return
false;}function
begin(){return
queries("BEGIN");}function
commit(){return
queries("COMMIT");}function
rollback(){return
queries("ROLLBACK");}function
slowQuery($G,$mi){}function
convertSearch($v,$X,$n){return$v;}function
convertOperator($xf){return$xf;}function
value($X,$n){return(method_exists($this->conn,'value')?$this->conn->value($X,$n):(is_resource($X)?stream_get_contents($X):$X));}function
quoteBinary($Zg){return
q($Zg);}function
warnings(){return'';}function
tableHelp($B,$ee=false){}function
hasCStyleEscapes(){return
false;}function
supportsIndex($R){return!is_view($R);}function
checkConstraints($Q){return
get_key_vals("SELECT c.CONSTRAINT_NAME, CHECK_CLAUSE
FROM INFORMATION_SCHEMA.CHECK_CONSTRAINTS c
JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS t ON c.CONSTRAINT_SCHEMA = t.CONSTRAINT_SCHEMA AND c.CONSTRAINT_NAME = t.CONSTRAINT_NAME
WHERE c.CONSTRAINT_SCHEMA = ".q($_GET["ns"]!=""?$_GET["ns"]:DB)."
AND t.TABLE_NAME = ".q($Q)."
AND CHECK_CLAUSE NOT LIKE '% IS NOT NULL'");}}$ec["sqlite"]="SQLite";if(isset($_GET["sqlite"])){define('Adminer\DRIVER',"sqlite");if(class_exists("SQLite3")){class
SqliteDb{var$extension="SQLite3",$server_info,$affected_rows,$errno,$error;private$link;function
__construct($p){$this->link=new
\SQLite3($p);$fj=$this->link->version();$this->server_info=$fj["versionString"];}function
query($G){$H=@$this->link->query($G);$this->error="";if(!$H){$this->errno=$this->link->lastErrorCode();$this->error=$this->link->lastErrorMsg();return
false;}elseif($H->numColumns())return
new
Result($H);$this->affected_rows=$this->link->changes();return
true;}function
quote($P){return(is_utf8($P)?"'".$this->link->escapeString($P)."'":"x'".reset(unpack('H*',$P))."'");}function
store_result(){return$this->result;}function
result($G,$n=0){$H=$this->query($G);if(!is_object($H))return
false;$J=$H->fetch_row();return$J?$J[$n]:false;}}class
Result{var$num_rows;private$result,$offset=0;function
__construct($H){$this->result=$H;}function
fetch_assoc(){return$this->result->fetchArray(SQLITE3_ASSOC);}function
fetch_row(){return$this->result->fetchArray(SQLITE3_NUM);}function
fetch_field(){$d=$this->offset++;$U=$this->result->columnType($d);return(object)array("name"=>$this->result->columnName($d),"type"=>$U,"charsetnr"=>($U==SQLITE3_BLOB?63:0),);}function
__desctruct(){return$this->result->finalize();}}}elseif(extension_loaded("pdo_sqlite")){class
SqliteDb
extends
PdoDb{var$extension="PDO_SQLite";function
__construct($p){$this->dsn(DRIVER.":$p","","");}function
select_db($j){return
false;}}}if(class_exists('Adminer\SqliteDb')){class
Db
extends
SqliteDb{function
__construct(){parent::__construct(":memory:");$this->query("PRAGMA foreign_keys = 1");}function
select_db($p){if(is_readable($p)&&$this->query("ATTACH ".$this->quote(preg_match("~(^[/\\\\]|:)~",$p)?$p:dirname($_SERVER["SCRIPT_FILENAME"])."/$p")." AS a")){parent::__construct($p);$this->query("PRAGMA foreign_keys = 1");$this->query("PRAGMA busy_timeout = 500");return
true;}return
false;}function
multi_query($G){return$this->result=$this->query($G);}function
next_result(){return
false;}}}class
Driver
extends
SqlDriver{static$lg=array("SQLite3","PDO_SQLite");static$he="sqlite";protected$types=array(array("integer"=>0,"real"=>0,"numeric"=>0,"text"=>0,"blob"=>0));var$editFunctions=array(array(),array("integer|real|numeric"=>"+/-","text"=>"||",));var$operators=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL");var$functions=array("hex","length","lower","round","unixepoch","upper");var$grouping=array("avg","count","count distinct","group_concat","max","min","sum");function
__construct($f){parent::__construct($f);if(min_version(3.31,0,$f))$this->generated=array("STORED","VIRTUAL");}function
structuredTypes(){return
array_keys($this->types[0]);}function
insertUpdate($Q,$K,$F){$cj=array();foreach($K
as$N)$cj[]="(".implode(", ",$N).")";return
queries("REPLACE INTO ".table($Q)." (".implode(", ",array_keys(reset($K))).") VALUES\n".implode(",\n",$cj));}function
tableHelp($B,$ee=false){if($B=="sqlite_sequence")return"fileformat2.html#seqtab";if($B=="sqlite_master")return"fileformat2.html#$B";}function
checkConstraints($Q){preg_match_all('~ CHECK *(\( *(((?>[^()]*[^() ])|(?1))*) *\))~',$this->conn->result("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q)),$Fe);return
array_combine($Fe[2],$Fe[2]);}}function
idf_escape($v){return'"'.str_replace('"','""',$v).'"';}function
table($v){return
idf_escape($v);}function
connect($Fb){list(,,$E)=$Fb;if($E!="")return'Database does not support password.';return
new
Db;}function
get_databases(){return
array();}function
limit($G,$Z,$z,$C=0,$lh=" "){return" $G$Z".($z!==null?$lh."LIMIT $z".($C?" OFFSET $C":""):"");}function
limit1($Q,$G,$Z,$lh="\n"){return(preg_match('~^INTO~',$G)||get_val("SELECT sqlite_compileoption_used('ENABLE_UPDATE_DELETE_LIMIT')")?limit($G,$Z,1,0,$lh):" $G WHERE rowid = (SELECT rowid FROM ".table($Q).$Z.$lh."LIMIT 1)");}function
db_collation($j,$jb){return
get_val("PRAGMA encoding");}function
engines(){return
array();}function
logged_user(){return
get_current_user();}function
tables_list(){return
get_key_vals("SELECT name, type FROM sqlite_master WHERE type IN ('table', 'view') ORDER BY (name = 'sqlite_sequence'), name");}function
count_tables($i){return
array();}function
table_status($B=""){$I=array();foreach(get_rows("SELECT name AS Name, type AS Engine, 'rowid' AS Oid, '' AS Auto_increment FROM sqlite_master WHERE type IN ('table', 'view') ".($B!=""?"AND name = ".q($B):"ORDER BY name"))as$J){$J["Rows"]=get_val("SELECT COUNT(*) FROM ".idf_escape($J["Name"]));$I[$J["Name"]]=$J;}foreach(get_rows("SELECT * FROM sqlite_sequence",null,"")as$J)$I[$J["name"]]["Auto_increment"]=$J["seq"];return($B!=""?$I[$B]:$I);}function
is_view($R){return$R["Engine"]=="view";}function
fk_support($R){return!get_val("SELECT sqlite_compileoption_used('OMIT_FOREIGN_KEY')");}function
fields($Q){$I=array();$F="";foreach(get_rows("PRAGMA table_".(min_version(3.31)?"x":"")."info(".table($Q).")")as$J){$B=$J["name"];$U=strtolower($J["type"]);$k=$J["dflt_value"];$I[$B]=array("field"=>$B,"type"=>(preg_match('~int~i',$U)?"integer":(preg_match('~char|clob|text~i',$U)?"text":(preg_match('~blob~i',$U)?"blob":(preg_match('~real|floa|doub~i',$U)?"real":"numeric")))),"full_type"=>$U,"default"=>(preg_match("~^'(.*)'$~",$k,$A)?str_replace("''","'",$A[1]):($k=="NULL"?null:$k)),"null"=>!$J["notnull"],"privileges"=>array("select"=>1,"insert"=>1,"update"=>1,"where"=>1,"order"=>1),"primary"=>$J["pk"],);if($J["pk"]){if($F!="")$I[$F]["auto_increment"]=false;elseif(preg_match('~^integer$~i',$U))$I[$B]["auto_increment"]=true;$F=$B;}}$Eh=get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q));$v='(("[^"]*+")+|[a-z0-9_]+)';preg_match_all('~'.$v.'\s+text\s+COLLATE\s+(\'[^\']+\'|\S+)~i',$Eh,$Fe,PREG_SET_ORDER);foreach($Fe
as$A){$B=str_replace('""','"',preg_replace('~^"|"$~','',$A[1]));if($I[$B])$I[$B]["collation"]=trim($A[3],"'");}preg_match_all('~'.$v.'\s.*GENERATED ALWAYS AS \((.+)\) (STORED|VIRTUAL)~i',$Eh,$Fe,PREG_SET_ORDER);foreach($Fe
as$A){$B=str_replace('""','"',preg_replace('~^"|"$~','',$A[1]));$I[$B]["default"]=$A[3];$I[$B]["generated"]=strtoupper($A[4]);}return$I;}function
indexes($Q,$g=null){global$f;if(!is_object($g))$g=$f;$I=array();$Eh=$g->result("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q));if(preg_match('~\bPRIMARY\s+KEY\s*\((([^)"]+|"[^"]*"|`[^`]*`)++)~i',$Eh,$A)){$I[""]=array("type"=>"PRIMARY","columns"=>array(),"lengths"=>array(),"descs"=>array());preg_match_all('~((("[^"]*+")+|(?:`[^`]*+`)+)|(\S+))(\s+(ASC|DESC))?(,\s*|$)~i',$A[1],$Fe,PREG_SET_ORDER);foreach($Fe
as$A){$I[""]["columns"][]=idf_unescape($A[2]).$A[4];$I[""]["descs"][]=(preg_match('~DESC~i',$A[5])?'1':null);}}if(!$I){foreach(fields($Q)as$B=>$n){if($n["primary"])$I[""]=array("type"=>"PRIMARY","columns"=>array($B),"lengths"=>array(),"descs"=>array(null));}}$Ih=get_key_vals("SELECT name, sql FROM sqlite_master WHERE type = 'index' AND tbl_name = ".q($Q),$g);foreach(get_rows("PRAGMA index_list(".table($Q).")",$g)as$J){$B=$J["name"];$w=array("type"=>($J["unique"]?"UNIQUE":"INDEX"));$w["lengths"]=array();$w["descs"]=array();foreach(get_rows("PRAGMA index_info(".idf_escape($B).")",$g)as$Yg){$w["columns"][]=$Yg["name"];$w["descs"][]=null;}if(preg_match('~^CREATE( UNIQUE)? INDEX '.preg_quote(idf_escape($B).' ON '.idf_escape($Q),'~').' \((.*)\)$~i',$Ih[$B],$Ig)){preg_match_all('/("[^"]*+")+( DESC)?/',$Ig[2],$Fe);foreach($Fe[2]as$y=>$X){if($X)$w["descs"][$y]='1';}}if(!$I[""]||$w["type"]!="UNIQUE"||$w["columns"]!=$I[""]["columns"]||$w["descs"]!=$I[""]["descs"]||!preg_match("~^sqlite_~",$B))$I[$B]=$w;}return$I;}function
foreign_keys($Q){$I=array();foreach(get_rows("PRAGMA foreign_key_list(".table($Q).")")as$J){$q=&$I[$J["id"]];if(!$q)$q=$J;$q["source"][]=$J["from"];$q["target"][]=$J["to"];}return$I;}function
view($B){return
array("select"=>preg_replace('~^(?:[^`"[]+|`[^`]*`|"[^"]*")* AS\s+~iU','',get_val("SELECT sql FROM sqlite_master WHERE type = 'view' AND name = ".q($B))));}function
collations(){return(isset($_GET["create"])?get_vals("PRAGMA collation_list",1):array());}function
information_schema($j){return
false;}function
error(){global$f;return
h($f->error);}function
check_sqlite_name($B){global$f;$Oc="db|sdb|sqlite";if(!preg_match("~^[^\\0]*\\.($Oc)\$~",$B)){$f->error=sprintf('Please use one of the extensions %s.',str_replace("|",", ",$Oc));return
false;}return
true;}function
create_database($j,$ib){global$f;if(file_exists($j)){$f->error='File exists.';return
false;}if(!check_sqlite_name($j))return
false;try{$_=new
SqliteDb($j);}catch(Exception$Fc){$f->error=$Fc->getMessage();return
false;}$_->query('PRAGMA encoding = "UTF-8"');$_->query('CREATE TABLE adminer (i)');$_->query('DROP TABLE adminer');return
true;}function
drop_databases($i){global$f;$f->__construct(":memory:");foreach($i
as$j){if(!@unlink($j)){$f->error='File exists.';return
false;}}return
true;}function
rename_database($B,$ib){global$f;if(!check_sqlite_name($B))return
false;$f->__construct(":memory:");$f->error='File exists.';return@rename(DB,$B);}function
auto_increment(){return" PRIMARY KEY AUTOINCREMENT";}function
alter_table($Q,$B,$o,$fd,$pb,$vc,$ib,$Da,$Zf){global$f;$Vi=($Q==""||$fd);foreach($o
as$n){if($n[0]!=""||!$n[1]||$n[2]){$Vi=true;break;}}$c=array();$Nf=array();foreach($o
as$n){if($n[1]){$c[]=($Vi?$n[1]:"ADD ".implode($n[1]));if($n[0]!="")$Nf[$n[0]]=$n[1][0];}}if(!$Vi){foreach($c
as$X){if(!queries("ALTER TABLE ".table($Q)." $X"))return
false;}if($Q!=$B&&!queries("ALTER TABLE ".table($Q)." RENAME TO ".table($B)))return
false;}elseif(!recreate_table($Q,$B,$c,$Nf,$fd,$Da))return
false;if($Da){queries("BEGIN");queries("UPDATE sqlite_sequence SET seq = $Da WHERE name = ".q($B));if(!$f->affected_rows)queries("INSERT INTO sqlite_sequence (name, seq) VALUES (".q($B).", $Da)");queries("COMMIT");}return
true;}function
recreate_table($Q,$B,$o,$Nf,$fd,$Da=0,$x=array(),$gc="",$pa=""){global$l;if($Q!=""){if(!$o){foreach(fields($Q)as$y=>$n){if($x)$n["auto_increment"]=0;$o[]=process_field($n,$n);$Nf[$y]=idf_escape($y);}}$pg=false;foreach($o
as$n){if($n[6])$pg=true;}$ic=array();foreach($x
as$y=>$X){if($X[2]=="DROP"){$ic[$X[1]]=true;unset($x[$y]);}}foreach(indexes($Q)as$je=>$w){$e=array();foreach($w["columns"]as$y=>$d){if(!$Nf[$d])continue
2;$e[]=$Nf[$d].($w["descs"][$y]?" DESC":"");}if(!$ic[$je]){if($w["type"]!="PRIMARY"||!$pg)$x[]=array($w["type"],$je,$e);}}foreach($x
as$y=>$X){if($X[0]=="PRIMARY"){unset($x[$y]);$fd[]="  PRIMARY KEY (".implode(", ",$X[2]).")";}}foreach(foreign_keys($Q)as$je=>$q){foreach($q["source"]as$y=>$d){if(!$Nf[$d])continue
2;$q["source"][$y]=idf_unescape($Nf[$d]);}if(!isset($fd[" $je"]))$fd[]=" ".format_foreign_key($q);}queries("BEGIN");}foreach($o
as$y=>$n){if(preg_match('~GENERATED~',$n[3]))unset($Nf[array_search($n[0],$Nf)]);$o[$y]="  ".implode($n);}$o=array_merge($o,array_filter($fd));foreach($l->checkConstraints($Q)as$Wa){if($Wa!=$gc)$o[]="  CHECK ($Wa)";}if($pa)$o[]="  CHECK ($pa)";$gi=($Q==$B?"adminer_$B":$B);if(!queries("CREATE TABLE ".table($gi)." (\n".implode(",\n",$o)."\n)"))return
false;if($Q!=""){if($Nf&&!queries("INSERT INTO ".table($gi)." (".implode(", ",$Nf).") SELECT ".implode(", ",array_map('Adminer\idf_escape',array_keys($Nf)))." FROM ".table($Q)))return
false;$Fi=array();foreach(triggers($Q)as$Di=>$ni){$Ci=trigger($Di);$Fi[]="CREATE TRIGGER ".idf_escape($Di)." ".implode(" ",$ni)." ON ".table($B)."\n$Ci[Statement]";}$Da=$Da?0:get_val("SELECT seq FROM sqlite_sequence WHERE name = ".q($Q));if(!queries("DROP TABLE ".table($Q))||($Q==$B&&!queries("ALTER TABLE ".table($gi)." RENAME TO ".table($B)))||!alter_indexes($B,$x))return
false;if($Da)queries("UPDATE sqlite_sequence SET seq = $Da WHERE name = ".q($B));foreach($Fi
as$Ci){if(!queries($Ci))return
false;}queries("COMMIT");}return
true;}function
index_sql($Q,$U,$B,$e){return"CREATE $U ".($U!="INDEX"?"INDEX ":"").idf_escape($B!=""?$B:uniqid($Q."_"))." ON ".table($Q)." $e";}function
alter_indexes($Q,$c){foreach($c
as$F){if($F[0]=="PRIMARY")return
recreate_table($Q,$Q,array(),array(),array(),0,$c);}foreach(array_reverse($c)as$X){if(!queries($X[2]=="DROP"?"DROP INDEX ".idf_escape($X[1]):index_sql($Q,$X[0],$X[1],"(".implode(", ",$X[2]).")")))return
false;}return
true;}function
truncate_tables($S){return
apply_queries("DELETE FROM",$S);}function
drop_views($hj){return
apply_queries("DROP VIEW",$hj);}function
drop_tables($S){return
apply_queries("DROP TABLE",$S);}function
move_tables($S,$hj,$ei){return
false;}function
trigger($B){if($B=="")return
array("Statement"=>"BEGIN\n\t;\nEND");$v='(?:[^`"\s]+|`[^`]*`|"[^"]*")+';$Ei=trigger_options();preg_match("~^CREATE\\s+TRIGGER\\s*$v\\s*(".implode("|",$Ei["Timing"]).")\\s+([a-z]+)(?:\\s+OF\\s+($v))?\\s+ON\\s*$v\\s*(?:FOR\\s+EACH\\s+ROW\\s)?(.*)~is",get_val("SELECT sql FROM sqlite_master WHERE type = 'trigger' AND name = ".q($B)),$A);$kf=$A[3];return
array("Timing"=>strtoupper($A[1]),"Event"=>strtoupper($A[2]).($kf?" OF":""),"Of"=>idf_unescape($kf),"Trigger"=>$B,"Statement"=>$A[4],);}function
triggers($Q){$I=array();$Ei=trigger_options();foreach(get_rows("SELECT * FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q))as$J){preg_match('~^CREATE\s+TRIGGER\s*(?:[^`"\s]+|`[^`]*`|"[^"]*")+\s*('.implode("|",$Ei["Timing"]).')\s*(.*?)\s+ON\b~i',$J["sql"],$A);$I[$J["name"]]=array($A[1],$A[2]);}return$I;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
begin(){return
queries("BEGIN");}function
last_id(){return
get_val("SELECT LAST_INSERT_ROWID()");}function
explain($f,$G){return$f->query("EXPLAIN QUERY PLAN $G");}function
found_rows($R,$Z){}function
types(){return
array();}function
create_sql($Q,$Da,$Oh){$I=get_val("SELECT sql FROM sqlite_master WHERE type IN ('table', 'view') AND name = ".q($Q));foreach(indexes($Q)as$B=>$w){if($B=='')continue;$I.=";\n\n".index_sql($Q,$w['type'],$B,"(".implode(", ",array_map('Adminer\idf_escape',$w['columns'])).")");}return$I;}function
truncate_sql($Q){return"DELETE FROM ".table($Q);}function
use_sql($Mb){}function
trigger_sql($Q){return
implode(get_vals("SELECT sql || ';;\n' FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q)));}function
show_variables(){$I=array();foreach(get_rows("PRAGMA pragma_list")as$J){$B=$J["name"];if($B!="pragma_list"&&$B!="compile_options"){foreach(get_rows("PRAGMA $B")as$J)$I[$B].=implode(", ",$J)."\n";}}return$I;}function
show_status(){$I=array();foreach(get_vals("PRAGMA compile_options")as$_f){list($y,$X)=explode("=",$_f,2);$I[$y]=$X;}return$I;}function
convert_field($n){}function
unconvert_field($n,$I){return$I;}function
support($Tc){return
preg_match('~^(check|columns|database|drop_col|dump|indexes|descidx|move_col|sql|status|table|trigger|variables|view|view_trigger)$~',$Tc);}}$ec["pgsql"]="PostgreSQL";if(isset($_GET["pgsql"])){define('Adminer\DRIVER',"pgsql");if(extension_loaded("pgsql")){class
Db{var$extension="PgSQL",$server_info,$affected_rows,$error,$timeout;private$link,$result,$string,$database=true;function
_error($Ac,$m){if(ini_bool("html_errors"))$m=html_entity_decode(strip_tags($m));$m=preg_replace('~^[^:]*: ~','',$m);$this->error=$m;}function
connect($M,$V,$E){global$b;$j=$b->database();set_error_handler(array($this,'_error'));$this->string="host='".str_replace(":","' port='",addcslashes($M,"'\\"))."' user='".addcslashes($V,"'\\")."' password='".addcslashes($E,"'\\")."'";$Jh=$b->connectSsl();if(isset($Jh["mode"]))$this->string.=" sslmode='".$Jh["mode"]."'";$this->link=@pg_connect("$this->string dbname='".($j!=""?addcslashes($j,"'\\"):"postgres")."'",PGSQL_CONNECT_FORCE_NEW);if(!$this->link&&$j!=""){$this->database=false;$this->link=@pg_connect("$this->string dbname='postgres'",PGSQL_CONNECT_FORCE_NEW);}restore_error_handler();if($this->link){$fj=pg_version($this->link);$this->server_info=$fj["server"];pg_set_client_encoding($this->link,"UTF8");}return(bool)$this->link;}function
quote($P){return
pg_escape_literal($this->link,$P);}function
value($X,$n){return($n["type"]=="bytea"&&$X!==null?pg_unescape_bytea($X):$X);}function
quoteBinary($P){return"'".pg_escape_bytea($this->link,$P)."'";}function
select_db($Mb){global$b;if($Mb==$b->database())return$this->database;$I=@pg_connect("$this->string dbname='".addcslashes($Mb,"'\\")."'",PGSQL_CONNECT_FORCE_NEW);if($I)$this->link=$I;return$I;}function
close(){$this->link=@pg_connect("$this->string dbname='postgres'");}function
query($G,$Ji=false){$H=@pg_query($this->link,$G);$this->error="";if(!$H){$this->error=pg_last_error($this->link);$I=false;}elseif(!pg_num_fields($H)){$this->affected_rows=pg_affected_rows($H);$I=true;}else$I=new
Result($H);if($this->timeout){$this->timeout=0;$this->query("RESET statement_timeout");}return$I;}function
multi_query($G){return$this->result=$this->query($G);}function
store_result(){return$this->result;}function
next_result(){return
false;}function
result($G,$n=0){$H=$this->query($G);return($H?$H->fetch_column($n):false);}function
warnings(){return
h(pg_last_notice($this->link));}}class
Result{var$num_rows;private$result,$offset=0;function
__construct($H){$this->result=$H;$this->num_rows=pg_num_rows($H);}function
fetch_assoc(){return
pg_fetch_assoc($this->result);}function
fetch_row(){return
pg_fetch_row($this->result);}function
fetch_column($n){return($this->num_rows?pg_fetch_result($this->result,0,$n):false);}function
fetch_field(){$d=$this->offset++;$I=new
\stdClass;if(function_exists('pg_field_table'))$I->orgtable=pg_field_table($this->result,$d);$I->name=pg_field_name($this->result,$d);$I->orgname=$I->name;$I->type=pg_field_type($this->result,$d);$I->charsetnr=($I->type=="bytea"?63:0);return$I;}function
__destruct(){pg_free_result($this->result);}}}elseif(extension_loaded("pdo_pgsql")){class
Db
extends
PdoDb{var$extension="PDO_PgSQL",$timeout;function
connect($M,$V,$E){global$b;$j=$b->database();$kc="pgsql:host='".str_replace(":","' port='",addcslashes($M,"'\\"))."' client_encoding=utf8 dbname='".($j!=""?addcslashes($j,"'\\"):"postgres")."'";$Jh=$b->connectSsl();if(isset($Jh["mode"]))$kc.=" sslmode='".$Jh["mode"]."'";$this->dsn($kc,$V,$E);return
true;}function
select_db($Mb){global$b;return($b->database()==$Mb);}function
quoteBinary($Zg){return
q($Zg);}function
query($G,$Ji=false){$I=parent::query($G,$Ji);if($this->timeout){$this->timeout=0;parent::query("RESET statement_timeout");}return$I;}function
warnings(){return'';}function
close(){}}}class
Driver
extends
SqlDriver{static$lg=array("PgSQL","PDO_PgSQL");static$he="pgsql";var$operators=array("=","<",">","<=",">=","!=","~","!~","LIKE","LIKE %%","ILIKE","ILIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL");var$functions=array("char_length","lower","round","to_hex","to_timestamp","upper");var$grouping=array("avg","count","count distinct","max","min","sum");function
__construct($f){parent::__construct($f);$this->types=array('Numbers'=>array("smallint"=>5,"integer"=>10,"bigint"=>19,"boolean"=>1,"numeric"=>0,"real"=>7,"double precision"=>16,"money"=>20),'Date and time'=>array("date"=>13,"time"=>17,"timestamp"=>20,"timestamptz"=>21,"interval"=>0),'Strings'=>array("character"=>0,"character varying"=>0,"text"=>0,"tsquery"=>0,"tsvector"=>0,"uuid"=>0,"xml"=>0),'Binary'=>array("bit"=>0,"bit varying"=>0,"bytea"=>0),'Network'=>array("cidr"=>43,"inet"=>43,"macaddr"=>17,"macaddr8"=>23,"txid_snapshot"=>0),'Geometry'=>array("box"=>0,"circle"=>0,"line"=>0,"lseg"=>0,"path"=>0,"point"=>0,"polygon"=>0),);if(min_version(9.2,0,$f)){$this->types['Strings']["json"]=4294967295;if(min_version(9.4,0,$f))$this->types['Strings']["jsonb"]=4294967295;}$this->editFunctions=array(array("char"=>"md5","date|time"=>"now",),array(number_type()=>"+/-","date|time"=>"+ interval/- interval","char|text"=>"||",));if(min_version(12,0,$f))$this->generated=array("STORED");}function
enumLength($n){$xc=$this->types['User types'][$n["type"]];return($xc?type_values($xc):"");}function
setUserTypes($Ii){$this->types['User types']=array_flip($Ii);}function
insertUpdate($Q,$K,$F){global$f;foreach($K
as$N){$Ri=array();$Z=array();foreach($N
as$y=>$X){$Ri[]="$y = $X";if(isset($F[idf_unescape($y)]))$Z[]="$y = $X";}if(!(($Z&&queries("UPDATE ".table($Q)." SET ".implode(", ",$Ri)." WHERE ".implode(" AND ",$Z))&&$f->affected_rows)||queries("INSERT INTO ".table($Q)." (".implode(", ",array_keys($N)).") VALUES (".implode(", ",$N).")")))return
false;}return
true;}function
slowQuery($G,$mi){$this->conn->query("SET statement_timeout = ".(1000*$mi));$this->conn->timeout=1000*$mi;return$G;}function
convertSearch($v,$X,$n){$ji="char|text";if(strpos($X["op"],"LIKE")===false)$ji.="|date|time(stamp)?|boolean|uuid|inet|cidr|macaddr|".number_type();return(preg_match("~$ji~",$n["type"])?$v:"CAST($v AS text)");}function
quoteBinary($Zg){return$this->conn->quoteBinary($Zg);}function
warnings(){return$this->conn->warnings();}function
tableHelp($B,$ee=false){$ze=array("information_schema"=>"infoschema","pg_catalog"=>($ee?"view":"catalog"),);$_=$ze[$_GET["ns"]];if($_)return"$_-".str_replace("_","-",$B).".html";}function
supportsIndex($R){return$R["Engine"]!="view";}function
hasCStyleEscapes(){static$Ra;if($Ra===null)$Ra=($this->conn->result("SHOW standard_conforming_strings")=="off");return$Ra;}}function
idf_escape($v){return'"'.str_replace('"','""',$v).'"';}function
table($v){return
idf_escape($v);}function
connect($Fb){$f=new
Db;if($f->connect($Fb[0],$Fb[1],$Fb[2])){if(min_version(9,0,$f))$f->query("SET application_name = 'Adminer'");return$f;}return$f->error;}function
get_databases(){return
get_vals("SELECT datname FROM pg_database
WHERE datallowconn = TRUE AND has_database_privilege(datname, 'CONNECT')
ORDER BY datname");}function
limit($G,$Z,$z,$C=0,$lh=" "){return" $G$Z".($z!==null?$lh."LIMIT $z".($C?" OFFSET $C":""):"");}function
limit1($Q,$G,$Z,$lh="\n"){return(preg_match('~^INTO~',$G)?limit($G,$Z,1,0,$lh):" $G".(is_view(table_status1($Q))?$Z:$lh."WHERE ctid = (SELECT ctid FROM ".table($Q).$Z.$lh."LIMIT 1)"));}function
db_collation($j,$jb){return
get_val("SELECT datcollate FROM pg_database WHERE datname = ".q($j));}function
engines(){return
array();}function
logged_user(){return
get_val("SELECT user");}function
tables_list(){$G="SELECT table_name, table_type FROM information_schema.tables WHERE table_schema = current_schema()";if(support("materializedview"))$G.="
UNION ALL
SELECT matviewname, 'MATERIALIZED VIEW'
FROM pg_matviews
WHERE schemaname = current_schema()";$G.="
ORDER BY 1";return
get_key_vals($G);}function
count_tables($i){global$f;$I=array();foreach($i
as$j){if($f->select_db($j))$I[$j]=count(tables_list());}return$I;}function
table_status($B=""){static$_d;if($_d===null)$_d=get_val("SELECT 'pg_table_size'::regproc");$I=array();foreach(get_rows("SELECT
	c.relname AS \"Name\",
	CASE c.relkind WHEN 'r' THEN 'table' WHEN 'm' THEN 'materialized view' ELSE 'view' END AS \"Engine\"".($_d?",
	pg_table_size(c.oid) AS \"Data_length\",
	pg_indexes_size(c.oid) AS \"Index_length\"":"").",
	obj_description(c.oid, 'pg_class') AS \"Comment\",
	".(min_version(12)?"''":"CASE WHEN c.relhasoids THEN 'oid' ELSE '' END")." AS \"Oid\",
	c.reltuples as \"Rows\",
	n.nspname
FROM pg_class c
JOIN pg_namespace n ON(n.nspname = current_schema() AND n.oid = c.relnamespace)
WHERE relkind IN ('r', 'm', 'v', 'f', 'p')
".($B!=""?"AND relname = ".q($B):"ORDER BY relname"))as$J)$I[$J["Name"]]=$J;return($B!=""?$I[$B]:$I);}function
is_view($R){return
in_array($R["Engine"],array("view","materialized view"));}function
fk_support($R){return
true;}function
fields($Q){$I=array();$wa=array('timestamp without time zone'=>'timestamp','timestamp with time zone'=>'timestamptz',);foreach(get_rows("SELECT a.attname AS field, format_type(a.atttypid, a.atttypmod) AS full_type, pg_get_expr(d.adbin, d.adrelid) AS default, a.attnotnull::int, col_description(c.oid, a.attnum) AS comment".(min_version(10)?", a.attidentity".(min_version(12)?", a.attgenerated":""):"")."
FROM pg_class c
JOIN pg_namespace n ON c.relnamespace = n.oid
JOIN pg_attribute a ON c.oid = a.attrelid
LEFT JOIN pg_attrdef d ON c.oid = d.adrelid AND a.attnum = d.adnum
WHERE c.relname = ".q($Q)."
AND n.nspname = current_schema()
AND NOT a.attisdropped
AND a.attnum > 0
ORDER BY a.attnum")as$J){preg_match('~([^([]+)(\((.*)\))?([a-z ]+)?((\[[0-9]*])*)$~',$J["full_type"],$A);list(,$U,$we,$J["length"],$qa,$ya)=$A;$J["length"].=$ya;$Ya=$U.$qa;if(isset($wa[$Ya])){$J["type"]=$wa[$Ya];$J["full_type"]=$J["type"].$we.$ya;}else{$J["type"]=$U;$J["full_type"]=$J["type"].$we.$qa.$ya;}if(in_array($J['attidentity'],array('a','d')))$J['default']='GENERATED '.($J['attidentity']=='d'?'BY DEFAULT':'ALWAYS').' AS IDENTITY';$J["generated"]=($J["attgenerated"]=="s"?"STORED":"");$J["null"]=!$J["attnotnull"];$J["auto_increment"]=$J['attidentity']||preg_match('~^nextval\(~i',$J["default"]);$J["privileges"]=array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1);if(preg_match('~(.+)::[^,)]+(.*)~',$J["default"],$A))$J["default"]=($A[1]=="NULL"?null:idf_unescape($A[1]).$A[2]);$I[$J["field"]]=$J;}return$I;}function
indexes($Q,$g=null){global$f;if(!is_object($g))$g=$f;$I=array();$Xh=$g->result("SELECT oid FROM pg_class WHERE relnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema()) AND relname = ".q($Q));$e=get_key_vals("SELECT attnum, attname FROM pg_attribute WHERE attrelid = $Xh AND attnum > 0",$g);foreach(get_rows("SELECT relname, indisunique::int, indisprimary::int, indkey, indoption, (indpred IS NOT NULL)::int as indispartial FROM pg_index i, pg_class ci WHERE i.indrelid = $Xh AND ci.oid = i.indexrelid ORDER BY indisprimary DESC, indisunique DESC",$g)as$J){$Jg=$J["relname"];$I[$Jg]["type"]=($J["indispartial"]?"INDEX":($J["indisprimary"]?"PRIMARY":($J["indisunique"]?"UNIQUE":"INDEX")));$I[$Jg]["columns"]=array();$I[$Jg]["descs"]=array();if($J["indkey"]){foreach(explode(" ",$J["indkey"])as$Qd)$I[$Jg]["columns"][]=$e[$Qd];foreach(explode(" ",$J["indoption"])as$Rd)$I[$Jg]["descs"][]=($Rd&1?'1':null);}$I[$Jg]["lengths"]=array();}return$I;}function
foreign_keys($Q){global$l;$I=array();foreach(get_rows("SELECT conname, condeferrable::int AS deferrable, pg_get_constraintdef(oid) AS definition
FROM pg_constraint
WHERE conrelid = (SELECT pc.oid FROM pg_class AS pc INNER JOIN pg_namespace AS pn ON (pn.oid = pc.relnamespace) WHERE pc.relname = ".q($Q)." AND pn.nspname = current_schema())
AND contype = 'f'::char
ORDER BY conkey, conname")as$J){if(preg_match('~FOREIGN KEY\s*\((.+)\)\s*REFERENCES (.+)\((.+)\)(.*)$~iA',$J['definition'],$A)){$J['source']=array_map('Adminer\idf_unescape',array_map('trim',explode(',',$A[1])));if(preg_match('~^(("([^"]|"")+"|[^"]+)\.)?"?("([^"]|"")+"|[^"]+)$~',$A[2],$Ee)){$J['ns']=idf_unescape($Ee[2]);$J['table']=idf_unescape($Ee[4]);}$J['target']=array_map('Adminer\idf_unescape',array_map('trim',explode(',',$A[3])));$J['on_delete']=(preg_match("~ON DELETE ($l->onActions)~",$A[4],$Ee)?$Ee[1]:'NO ACTION');$J['on_update']=(preg_match("~ON UPDATE ($l->onActions)~",$A[4],$Ee)?$Ee[1]:'NO ACTION');$I[$J['conname']]=$J;}}return$I;}function
view($B){return
array("select"=>trim(get_val("SELECT pg_get_viewdef(".get_val("SELECT oid FROM pg_class WHERE relnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema()) AND relname = ".q($B)).")")));}function
collations(){return
array();}function
information_schema($j){return
get_schema()=="information_schema";}function
error(){global$f;$I=h($f->error);if(preg_match('~^(.*\n)?([^\n]*)\n( *)\^(\n.*)?$~s',$I,$A))$I=$A[1].preg_replace('~((?:[^&]|&[^;]*;){'.strlen($A[3]).'})(.*)~','\1<b>\2</b>',$A[2]).$A[4];return
nl_br($I);}function
create_database($j,$ib){return
queries("CREATE DATABASE ".idf_escape($j).($ib?" ENCODING ".idf_escape($ib):""));}function
drop_databases($i){global$f;$f->close();return
apply_queries("DROP DATABASE",$i,'Adminer\idf_escape');}function
rename_database($B,$ib){global$f;$f->close();return
queries("ALTER DATABASE ".idf_escape(DB)." RENAME TO ".idf_escape($B));}function
auto_increment(){return"";}function
alter_table($Q,$B,$o,$fd,$pb,$vc,$ib,$Da,$Zf){$c=array();$yg=array();if($Q!=""&&$Q!=$B)$yg[]="ALTER TABLE ".table($Q)." RENAME TO ".table($B);$mh="";foreach($o
as$n){$d=idf_escape($n[0]);$X=$n[1];if(!$X)$c[]="DROP $d";else{$bj=$X[5];unset($X[5]);if($n[0]==""){if(isset($X[6]))$X[1]=($X[1]==" bigint"?" big":($X[1]==" smallint"?" small":" "))."serial";$c[]=($Q!=""?"ADD ":"  ").implode($X);if(isset($X[6]))$c[]=($Q!=""?"ADD":" ")." PRIMARY KEY ($X[0])";}else{if($d!=$X[0])$yg[]="ALTER TABLE ".table($B)." RENAME $d TO $X[0]";$c[]="ALTER $d TYPE$X[1]";$nh=$Q."_".idf_unescape($X[0])."_seq";$c[]="ALTER $d ".($X[3]?"SET".preg_replace('~GENERATED ALWAYS(.*) STORED~','EXPRESSION\1',$X[3]):(isset($X[6])?"SET DEFAULT nextval(".q($nh).")":"DROP DEFAULT"));if(isset($X[6]))$mh="CREATE SEQUENCE IF NOT EXISTS ".idf_escape($nh)." OWNED BY ".idf_escape($Q).".$X[0]";$c[]="ALTER $d ".($X[2]==" NULL"?"DROP NOT":"SET").$X[2];}if($n[0]!=""||$bj!="")$yg[]="COMMENT ON COLUMN ".table($B).".$X[0] IS ".($bj!=""?substr($bj,9):"''");}}$c=array_merge($c,$fd);if($Q=="")array_unshift($yg,"CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)");elseif($c)array_unshift($yg,"ALTER TABLE ".table($Q)."\n".implode(",\n",$c));if($mh)array_unshift($yg,$mh);if($pb!==null)$yg[]="COMMENT ON TABLE ".table($B)." IS ".q($pb);foreach($yg
as$G){if(!queries($G))return
false;}return
true;}function
alter_indexes($Q,$c){$h=array();$fc=array();$yg=array();foreach($c
as$X){if($X[0]!="INDEX")$h[]=($X[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($X[1]):"\nADD".($X[1]!=""?" CONSTRAINT ".idf_escape($X[1]):"")." $X[0] ".($X[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$X[2]).")");elseif($X[2]=="DROP")$fc[]=idf_escape($X[1]);else$yg[]="CREATE INDEX ".idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q)." (".implode(", ",$X[2]).")";}if($h)array_unshift($yg,"ALTER TABLE ".table($Q).implode(",",$h));if($fc)array_unshift($yg,"DROP INDEX ".implode(", ",$fc));foreach($yg
as$G){if(!queries($G))return
false;}return
true;}function
truncate_tables($S){return
queries("TRUNCATE ".implode(", ",array_map('Adminer\table',$S)));}function
drop_views($hj){return
drop_tables($hj);}function
drop_tables($S){foreach($S
as$Q){$O=table_status($Q);if(!queries("DROP ".strtoupper($O["Engine"])." ".table($Q)))return
false;}return
true;}function
move_tables($S,$hj,$ei){foreach(array_merge($S,$hj)as$Q){$O=table_status($Q);if(!queries("ALTER ".strtoupper($O["Engine"])." ".table($Q)." SET SCHEMA ".idf_escape($ei)))return
false;}return
true;}function
trigger($B,$Q){if($B=="")return
array("Statement"=>"EXECUTE PROCEDURE ()");$e=array();$Z="WHERE trigger_schema = current_schema() AND event_object_table = ".q($Q)." AND trigger_name = ".q($B);foreach(get_rows("SELECT * FROM information_schema.triggered_update_columns $Z")as$J)$e[]=$J["event_object_column"];$I=array();foreach(get_rows('SELECT trigger_name AS "Trigger", action_timing AS "Timing", event_manipulation AS "Event", \'FOR EACH \' || action_orientation AS "Type", action_statement AS "Statement" FROM information_schema.triggers '."$Z ORDER BY event_manipulation DESC")as$J){if($e&&$J["Event"]=="UPDATE")$J["Event"].=" OF";$J["Of"]=implode(", ",$e);if($I)$J["Event"].=" OR $I[Event]";$I=$J;}return$I;}function
triggers($Q){$I=array();foreach(get_rows("SELECT * FROM information_schema.triggers WHERE trigger_schema = current_schema() AND event_object_table = ".q($Q))as$J){$Ci=trigger($J["trigger_name"],$Q);$I[$Ci["Trigger"]]=array($Ci["Timing"],$Ci["Event"]);}return$I;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE","INSERT OR UPDATE","INSERT OR UPDATE OF","DELETE OR INSERT","DELETE OR UPDATE","DELETE OR UPDATE OF","DELETE OR INSERT OR UPDATE","DELETE OR INSERT OR UPDATE OF"),"Type"=>array("FOR EACH ROW","FOR EACH STATEMENT"),);}function
routine($B,$U){$K=get_rows('SELECT routine_definition AS definition, LOWER(external_language) AS language, *
FROM information_schema.routines
WHERE routine_schema = current_schema() AND specific_name = '.q($B));$I=$K[0];$I["returns"]=array("type"=>$I["type_udt_name"]);$I["fields"]=get_rows('SELECT parameter_name AS field, data_type AS type, character_maximum_length AS length, parameter_mode AS inout
FROM information_schema.parameters
WHERE specific_schema = current_schema() AND specific_name = '.q($B).'
ORDER BY ordinal_position');return$I;}function
routines(){return
get_rows('SELECT specific_name AS "SPECIFIC_NAME", routine_type AS "ROUTINE_TYPE", routine_name AS "ROUTINE_NAME", type_udt_name AS "DTD_IDENTIFIER"
FROM information_schema.routines
WHERE routine_schema = current_schema()
ORDER BY SPECIFIC_NAME');}function
routine_languages(){return
get_vals("SELECT LOWER(lanname) FROM pg_catalog.pg_language");}function
routine_id($B,$J){$I=array();foreach($J["fields"]as$n)$I[]=$n["type"];return
idf_escape($B)."(".implode(", ",$I).")";}function
last_id(){return
0;}function
explain($f,$G){return$f->query("EXPLAIN $G");}function
found_rows($R,$Z){if(preg_match("~ rows=([0-9]+)~",get_val("EXPLAIN SELECT * FROM ".idf_escape($R["Name"]).($Z?" WHERE ".implode(" AND ",$Z):"")),$Ig))return$Ig[1];return
false;}function
types(){return
get_key_vals("SELECT oid, typname
FROM pg_type
WHERE typnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema())
AND typtype IN ('b','d','e')
AND typelem = 0");}function
type_values($u){$_c=get_vals("SELECT enumlabel FROM pg_enum WHERE enumtypid = $u ORDER BY enumsortorder");return($_c?"'".implode("', '",array_map('addslashes',$_c))."'":"");}function
schemas(){return
get_vals("SELECT nspname FROM pg_namespace ORDER BY nspname");}function
get_schema(){return
get_val("SELECT current_schema()");}function
set_schema($bh,$g=null){global$f,$l;if(!$g)$g=$f;$I=$g->query("SET search_path TO ".idf_escape($bh));$l->setUserTypes(types());return$I;}function
foreign_keys_sql($Q){$I="";$O=table_status($Q);$cd=foreign_keys($Q);ksort($cd);foreach($cd
as$bd=>$ad)$I.="ALTER TABLE ONLY ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." ADD CONSTRAINT ".idf_escape($bd)." $ad[definition] ".($ad['deferrable']?'DEFERRABLE':'NOT DEFERRABLE').";\n";return($I?"$I\n":$I);}function
create_sql($Q,$Da,$Oh){global$l;$Rg=array();$oh=array();$O=table_status($Q);if(is_view($O)){$gj=view($Q);return
rtrim("CREATE VIEW ".idf_escape($Q)." AS $gj[select]",";");}$o=fields($Q);if(!$O||empty($o))return
false;$I="CREATE TABLE ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." (\n    ";foreach($o
as$n){$Wf=idf_escape($n['field']).' '.$n['full_type'].default_value($n).($n['attnotnull']?" NOT NULL":"");$Rg[]=$Wf;if(preg_match('~nextval\(\'([^\']+)\'\)~',$n['default'],$Fe)){$nh=$Fe[1];$Dh=reset(get_rows((min_version(10)?"SELECT *, cache_size AS cache_value FROM pg_sequences WHERE schemaname = current_schema() AND sequencename = ".q(idf_unescape($nh)):"SELECT * FROM $nh"),null,"-- "));$oh[]=($Oh=="DROP+CREATE"?"DROP SEQUENCE IF EXISTS $nh;\n":"")."CREATE SEQUENCE $nh INCREMENT $Dh[increment_by] MINVALUE $Dh[min_value] MAXVALUE $Dh[max_value]".($Da&&$Dh['last_value']?" START ".($Dh["last_value"]+1):"")." CACHE $Dh[cache_value];";}}if(!empty($oh))$I=implode("\n\n",$oh)."\n\n$I";$F="";foreach(indexes($Q)as$Od=>$w){if($w['type']=='PRIMARY'){$F=$Od;$Rg[]="CONSTRAINT ".idf_escape($Od)." PRIMARY KEY (".implode(', ',array_map('Adminer\idf_escape',$w['columns'])).")";}}foreach($l->checkConstraints($Q)as$ub=>$wb)$Rg[]="CONSTRAINT ".idf_escape($ub)." CHECK $wb";$I.=implode(",\n    ",$Rg)."\n) WITH (oids = ".($O['Oid']?'true':'false').");";if($O['Comment'])$I.="\n\nCOMMENT ON TABLE ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." IS ".q($O['Comment']).";";foreach($o
as$Vc=>$n){if($n['comment'])$I.="\n\nCOMMENT ON COLUMN ".idf_escape($O['nspname']).".".idf_escape($O['Name']).".".idf_escape($Vc)." IS ".q($n['comment']).";";}foreach(get_rows("SELECT indexdef FROM pg_catalog.pg_indexes WHERE schemaname = current_schema() AND tablename = ".q($Q).($F?" AND indexname != ".q($F):""),null,"-- ")as$J)$I.="\n\n$J[indexdef];";return
rtrim($I,';');}function
truncate_sql($Q){return"TRUNCATE ".table($Q);}function
trigger_sql($Q){$O=table_status($Q);$I="";foreach(triggers($Q)as$Bi=>$Ai){$Ci=trigger($Bi,$O['Name']);$I.="\nCREATE TRIGGER ".idf_escape($Ci['Trigger'])." $Ci[Timing] $Ci[Event] ON ".idf_escape($O["nspname"]).".".idf_escape($O['Name'])." $Ci[Type] $Ci[Statement];;\n";}return$I;}function
use_sql($Mb){return"\connect ".idf_escape($Mb);}function
show_variables(){return
get_key_vals("SHOW ALL");}function
process_list(){return
get_rows("SELECT * FROM pg_stat_activity ORDER BY ".(min_version(9.2)?"pid":"procpid"));}function
convert_field($n){}function
unconvert_field($n,$I){return$I;}function
support($Tc){return
preg_match('~^(check|database|table|columns|sql|indexes|descidx|comment|view|'.(min_version(9.3)?'materializedview|':'').'scheme|routine|processlist|sequence|trigger|type|variables|drop_col|kill|dump)$~',$Tc);}function
kill_process($X){return
queries("SELECT pg_terminate_backend(".number($X).")");}function
connection_id(){return"SELECT pg_backend_pid()";}function
max_connections(){return
get_val("SHOW max_connections");}}$ec["oracle"]="Oracle (beta)";if(isset($_GET["oracle"])){define('Adminer\DRIVER',"oracle");if(extension_loaded("oci8")){class
Db{var$extension="oci8",$server_info,$affected_rows,$errno,$error;var$_current_db;private$link,$result;function
_error($Ac,$m){if(ini_bool("html_errors"))$m=html_entity_decode(strip_tags($m));$m=preg_replace('~^[^:]*: ~','',$m);$this->error=$m;}function
connect($M,$V,$E){$this->link=@oci_new_connect($V,$E,$M,"AL32UTF8");if($this->link){$this->server_info=oci_server_version($this->link);return
true;}$m=oci_error();$this->error=$m["message"];return
false;}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($Mb){$this->_current_db=$Mb;return
true;}function
query($G,$Ji=false){$H=oci_parse($this->link,$G);$this->error="";if(!$H){$m=oci_error($this->link);$this->errno=$m["code"];$this->error=$m["message"];return
false;}set_error_handler(array($this,'_error'));$I=@oci_execute($H);restore_error_handler();if($I){if(oci_num_fields($H))return
new
Result($H);$this->affected_rows=oci_num_rows($H);oci_free_statement($H);}return$I;}function
multi_query($G){return$this->result=$this->query($G);}function
store_result(){return$this->result;}function
next_result(){return
false;}function
result($G,$n=0){$H=$this->query($G);return(is_object($H)?$H->fetch_column($n):false);}}class
Result{var$num_rows;private$result,$offset=1;function
__construct($H){$this->result=$H;}private
function
convert($J){foreach((array)$J
as$y=>$X){if(is_a($X,'OCI-Lob'))$J[$y]=$X->load();}return$J;}function
fetch_assoc(){return$this->convert(oci_fetch_assoc($this->result));}function
fetch_row(){return$this->convert(oci_fetch_row($this->result));}function
fetch_column($n){return(oci_fetch($this->result)?oci_result($this->result,$n+1):false);}function
fetch_field(){$d=$this->offset++;$I=new
\stdClass;$I->name=oci_field_name($this->result,$d);$I->orgname=$I->name;$I->type=oci_field_type($this->result,$d);$I->charsetnr=(preg_match("~raw|blob|bfile~",$I->type)?63:0);return$I;}function
__destruct(){oci_free_statement($this->result);}}}elseif(extension_loaded("pdo_oci")){class
Db
extends
PdoDb{var$extension="PDO_OCI";var$_current_db;function
connect($M,$V,$E){$this->dsn("oci:dbname=//$M;charset=AL32UTF8",$V,$E);return
true;}function
select_db($Mb){$this->_current_db=$Mb;return
true;}}}class
Driver
extends
SqlDriver{static$lg=array("OCI8","PDO_OCI");static$he="oracle";var$editFunctions=array(array("date"=>"current_date","timestamp"=>"current_timestamp",),array("number|float|double"=>"+/-","date|timestamp"=>"+ interval/- interval","char|clob"=>"||",));var$operators=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL");var$functions=array("length","lower","round","upper");var$grouping=array("avg","count","count distinct","max","min","sum");function
__construct($f){parent::__construct($f);$this->types=array('Numbers'=>array("number"=>38,"binary_float"=>12,"binary_double"=>21),'Date and time'=>array("date"=>10,"timestamp"=>29,"interval year"=>12,"interval day"=>28),'Strings'=>array("char"=>2000,"varchar2"=>4000,"nchar"=>2000,"nvarchar2"=>4000,"clob"=>4294967295,"nclob"=>4294967295),'Binary'=>array("raw"=>2000,"long raw"=>2147483648,"blob"=>4294967295,"bfile"=>4294967296),);}function
begin(){return
true;}function
insertUpdate($Q,$K,$F){global$f;foreach($K
as$N){$Ri=array();$Z=array();foreach($N
as$y=>$X){$Ri[]="$y = $X";if(isset($F[idf_unescape($y)]))$Z[]="$y = $X";}if(!(($Z&&queries("UPDATE ".table($Q)." SET ".implode(", ",$Ri)." WHERE ".implode(" AND ",$Z))&&$f->affected_rows)||queries("INSERT INTO ".table($Q)." (".implode(", ",array_keys($N)).") VALUES (".implode(", ",$N).")")))return
false;}return
true;}function
hasCStyleEscapes(){return
true;}}function
idf_escape($v){return'"'.str_replace('"','""',$v).'"';}function
table($v){return
idf_escape($v);}function
connect($Fb){$f=new
Db;if($f->connect($Fb[0],$Fb[1],$Fb[2]))return$f;return$f->error;}function
get_databases(){return
get_vals("SELECT DISTINCT tablespace_name FROM (
SELECT tablespace_name FROM user_tablespaces
UNION SELECT tablespace_name FROM all_tables WHERE tablespace_name IS NOT NULL
)
ORDER BY 1");}function
limit($G,$Z,$z,$C=0,$lh=" "){return($C?" * FROM (SELECT t.*, rownum AS rnum FROM (SELECT $G$Z) t WHERE rownum <= ".($z+$C).") WHERE rnum > $C":($z!==null?" * FROM (SELECT $G$Z) WHERE rownum <= ".($z+$C):" $G$Z"));}function
limit1($Q,$G,$Z,$lh="\n"){return" $G$Z";}function
db_collation($j,$jb){return
get_val("SELECT value FROM nls_database_parameters WHERE parameter = 'NLS_CHARACTERSET'");}function
engines(){return
array();}function
logged_user(){return
get_val("SELECT USER FROM DUAL");}function
get_current_db(){global$f;$j=$f->_current_db?:DB;unset($f->_current_db);return$j;}function
where_owner($ng,$Qf="owner"){if(!$_GET["ns"])return'';return"$ng$Qf = sys_context('USERENV', 'CURRENT_SCHEMA')";}function
views_table($e){$Qf=where_owner('');return"(SELECT $e FROM all_views WHERE ".($Qf?:"rownum < 0").")";}function
tables_list(){$gj=views_table("view_name");$Qf=where_owner(" AND ");return
get_key_vals("SELECT table_name, 'table' FROM all_tables WHERE tablespace_name = ".q(DB)."$Qf
UNION SELECT view_name, 'view' FROM $gj
ORDER BY 1");}function
count_tables($i){$I=array();foreach($i
as$j)$I[$j]=get_val("SELECT COUNT(*) FROM all_tables WHERE tablespace_name = ".q($j));return$I;}function
table_status($B=""){$I=array();$eh=q($B);$j=get_current_db();$gj=views_table("view_name");$Qf=where_owner(" AND ");foreach(get_rows('SELECT table_name "Name", \'table\' "Engine", avg_row_len * num_rows "Data_length", num_rows "Rows" FROM all_tables WHERE tablespace_name = '.q($j).$Qf.($B!=""?" AND table_name = $eh":"")."
UNION SELECT view_name, 'view', 0, 0 FROM $gj".($B!=""?" WHERE view_name = $eh":"")."
ORDER BY 1")as$J){if($B!="")return$J;$I[$J["Name"]]=$J;}return$I;}function
is_view($R){return$R["Engine"]=="view";}function
fk_support($R){return
true;}function
fields($Q){$I=array();$Qf=where_owner(" AND ");foreach(get_rows("SELECT * FROM all_tab_columns WHERE table_name = ".q($Q)."$Qf ORDER BY column_id")as$J){$U=$J["DATA_TYPE"];$we="$J[DATA_PRECISION],$J[DATA_SCALE]";if($we==",")$we=$J["CHAR_COL_DECL_LENGTH"];$I[$J["COLUMN_NAME"]]=array("field"=>$J["COLUMN_NAME"],"full_type"=>$U.($we?"($we)":""),"type"=>strtolower($U),"length"=>$we,"default"=>$J["DATA_DEFAULT"],"null"=>($J["NULLABLE"]=="Y"),"privileges"=>array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1),);}return$I;}function
indexes($Q,$g=null){$I=array();$Qf=where_owner(" AND ","aic.table_owner");foreach(get_rows("SELECT aic.*, ac.constraint_type, atc.data_default
FROM all_ind_columns aic
LEFT JOIN all_constraints ac ON aic.index_name = ac.constraint_name AND aic.table_name = ac.table_name AND aic.index_owner = ac.owner
LEFT JOIN all_tab_cols atc ON aic.column_name = atc.column_name AND aic.table_name = atc.table_name AND aic.index_owner = atc.owner
WHERE aic.table_name = ".q($Q)."$Qf
ORDER BY ac.constraint_type, aic.column_position",$g)as$J){$Od=$J["INDEX_NAME"];$mb=$J["DATA_DEFAULT"];$mb=($mb?trim($mb,'"'):$J["COLUMN_NAME"]);$I[$Od]["type"]=($J["CONSTRAINT_TYPE"]=="P"?"PRIMARY":($J["CONSTRAINT_TYPE"]=="U"?"UNIQUE":"INDEX"));$I[$Od]["columns"][]=$mb;$I[$Od]["lengths"][]=($J["CHAR_LENGTH"]&&$J["CHAR_LENGTH"]!=$J["COLUMN_LENGTH"]?$J["CHAR_LENGTH"]:null);$I[$Od]["descs"][]=($J["DESCEND"]&&$J["DESCEND"]=="DESC"?'1':null);}return$I;}function
view($B){$gj=views_table("view_name, text");$K=get_rows('SELECT text "select" FROM '.$gj.' WHERE view_name = '.q($B));return
reset($K);}function
collations(){return
array();}function
information_schema($j){return
get_schema()=="INFORMATION_SCHEMA";}function
error(){global$f;return
h($f->error);}function
explain($f,$G){$f->query("EXPLAIN PLAN FOR $G");return$f->query("SELECT * FROM plan_table");}function
found_rows($R,$Z){}function
auto_increment(){return"";}function
alter_table($Q,$B,$o,$fd,$pb,$vc,$ib,$Da,$Zf){$c=$fc=array();$Jf=($Q?fields($Q):array());foreach($o
as$n){$X=$n[1];if($X&&$n[0]!=""&&idf_escape($n[0])!=$X[0])queries("ALTER TABLE ".table($Q)." RENAME COLUMN ".idf_escape($n[0])." TO $X[0]");$If=$Jf[$n[0]];if($X&&$If){$mf=process_field($If,$If);if($X[2]==$mf[2])$X[2]="";}if($X)$c[]=($Q!=""?($n[0]!=""?"MODIFY (":"ADD ("):"  ").implode($X).($Q!=""?")":"");else$fc[]=idf_escape($n[0]);}if($Q=="")return
queries("CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)");return(!$c||queries("ALTER TABLE ".table($Q)."\n".implode("\n",$c)))&&(!$fc||queries("ALTER TABLE ".table($Q)." DROP (".implode(", ",$fc).")"))&&($Q==$B||queries("ALTER TABLE ".table($Q)." RENAME TO ".table($B)));}function
alter_indexes($Q,$c){$fc=array();$yg=array();foreach($c
as$X){if($X[0]!="INDEX"){$X[2]=preg_replace('~ DESC$~','',$X[2]);$h=($X[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($X[1]):"\nADD".($X[1]!=""?" CONSTRAINT ".idf_escape($X[1]):"")." $X[0] ".($X[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$X[2]).")");array_unshift($yg,"ALTER TABLE ".table($Q).$h);}elseif($X[2]=="DROP")$fc[]=idf_escape($X[1]);else$yg[]="CREATE INDEX ".idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q)." (".implode(", ",$X[2]).")";}if($fc)array_unshift($yg,"DROP INDEX ".implode(", ",$fc));foreach($yg
as$G){if(!queries($G))return
false;}return
true;}function
foreign_keys($Q){$I=array();$G="SELECT c_list.CONSTRAINT_NAME as NAME,
c_src.COLUMN_NAME as SRC_COLUMN,
c_dest.OWNER as DEST_DB,
c_dest.TABLE_NAME as DEST_TABLE,
c_dest.COLUMN_NAME as DEST_COLUMN,
c_list.DELETE_RULE as ON_DELETE
FROM ALL_CONSTRAINTS c_list, ALL_CONS_COLUMNS c_src, ALL_CONS_COLUMNS c_dest
WHERE c_list.CONSTRAINT_NAME = c_src.CONSTRAINT_NAME
AND c_list.R_CONSTRAINT_NAME = c_dest.CONSTRAINT_NAME
AND c_list.CONSTRAINT_TYPE = 'R'
AND c_src.TABLE_NAME = ".q($Q);foreach(get_rows($G)as$J)$I[$J['NAME']]=array("db"=>$J['DEST_DB'],"table"=>$J['DEST_TABLE'],"source"=>array($J['SRC_COLUMN']),"target"=>array($J['DEST_COLUMN']),"on_delete"=>$J['ON_DELETE'],"on_update"=>null,);return$I;}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($hj){return
apply_queries("DROP VIEW",$hj);}function
drop_tables($S){return
apply_queries("DROP TABLE",$S);}function
last_id(){return
0;}function
schemas(){$I=get_vals("SELECT DISTINCT owner FROM dba_segments WHERE owner IN (SELECT username FROM dba_users WHERE default_tablespace NOT IN ('SYSTEM','SYSAUX')) ORDER BY 1");return($I?:get_vals("SELECT DISTINCT owner FROM all_tables WHERE tablespace_name = ".q(DB)." ORDER BY 1"));}function
get_schema(){return
get_val("SELECT sys_context('USERENV', 'SESSION_USER') FROM dual");}function
set_schema($dh,$g=null){global$f;if(!$g)$g=$f;return$g->query("ALTER SESSION SET CURRENT_SCHEMA = ".idf_escape($dh));}function
show_variables(){return
get_key_vals('SELECT name, display_value FROM v$parameter');}function
process_list(){return
get_rows('SELECT sess.process AS "process", sess.username AS "user", sess.schemaname AS "schema", sess.status AS "status", sess.wait_class AS "wait_class", sess.seconds_in_wait AS "seconds_in_wait", sql.sql_text AS "sql_text", sess.machine AS "machine", sess.port AS "port"
FROM v$session sess LEFT OUTER JOIN v$sql sql
ON sql.sql_id = sess.sql_id
WHERE sess.type = \'USER\'
ORDER BY PROCESS
');}function
show_status(){$K=get_rows('SELECT * FROM v$instance');return
reset($K);}function
convert_field($n){}function
unconvert_field($n,$I){return$I;}function
support($Tc){return
preg_match('~^(columns|database|drop_col|indexes|descidx|processlist|scheme|sql|status|table|variables|view)$~',$Tc);}}$ec["mssql"]="MS SQL";if(isset($_GET["mssql"])){define('Adminer\DRIVER',"mssql");if(extension_loaded("sqlsrv")){class
Db{var$extension="sqlsrv",$server_info,$affected_rows,$errno,$error;private$link,$result;private
function
get_error(){$this->error="";foreach(sqlsrv_errors()as$m){$this->errno=$m["code"];$this->error.="$m[message]\n";}$this->error=rtrim($this->error);}function
connect($M,$V,$E){global$b;$vb=array("UID"=>$V,"PWD"=>$E,"CharacterSet"=>"UTF-8");$Jh=$b->connectSsl();if(isset($Jh["Encrypt"]))$vb["Encrypt"]=$Jh["Encrypt"];if(isset($Jh["TrustServerCertificate"]))$vb["TrustServerCertificate"]=$Jh["TrustServerCertificate"];$j=$b->database();if($j!="")$vb["Database"]=$j;$this->link=@sqlsrv_connect(preg_replace('~:~',',',$M),$vb);if($this->link){$Sd=sqlsrv_server_info($this->link);$this->server_info=$Sd['SQLServerVersion'];}else$this->get_error();return(bool)$this->link;}function
quote($P){$Ki=strlen($P)!=strlen(utf8_decode($P));return($Ki?"N":"")."'".str_replace("'","''",$P)."'";}function
select_db($Mb){return$this->query(use_sql($Mb));}function
query($G,$Ji=false){$H=sqlsrv_query($this->link,$G);$this->error="";if(!$H){$this->get_error();return
false;}return$this->store_result($H);}function
multi_query($G){$this->result=sqlsrv_query($this->link,$G);$this->error="";if(!$this->result){$this->get_error();return
false;}return
true;}function
store_result($H=null){if(!$H)$H=$this->result;if(!$H)return
false;if(sqlsrv_field_metadata($H))return
new
Result($H);$this->affected_rows=sqlsrv_rows_affected($H);return
true;}function
next_result(){return$this->result?sqlsrv_next_result($this->result):null;}function
result($G,$n=0){$H=$this->query($G);if(!is_object($H))return
false;$J=$H->fetch_row();return$J[$n];}}class
Result{var$num_rows;private$result,$offset=0,$fields;function
__construct($H){$this->result=$H;}private
function
convert($J){foreach((array)$J
as$y=>$X){if(is_a($X,'DateTime'))$J[$y]=$X->format("Y-m-d H:i:s");}return$J;}function
fetch_assoc(){return$this->convert(sqlsrv_fetch_array($this->result,SQLSRV_FETCH_ASSOC));}function
fetch_row(){return$this->convert(sqlsrv_fetch_array($this->result,SQLSRV_FETCH_NUMERIC));}function
fetch_field(){if(!$this->fields)$this->fields=sqlsrv_field_metadata($this->result);$n=$this->fields[$this->offset++];$I=new
\stdClass;$I->name=$n["Name"];$I->orgname=$n["Name"];$I->type=($n["Type"]==1?254:0);return$I;}function
seek($C){for($t=0;$t<$C;$t++)sqlsrv_fetch($this->result);}function
__destruct(){sqlsrv_free_stmt($this->result);}}}elseif(extension_loaded("pdo_sqlsrv")){class
Db
extends
PdoDb{var$extension="PDO_SQLSRV";function
connect($M,$V,$E){$this->dsn("sqlsrv:Server=".str_replace(":",",",$M),$V,$E);return
true;}function
select_db($Mb){return$this->query(use_sql($Mb));}}}elseif(extension_loaded("pdo_dblib")){class
Db
extends
PdoDb{var$extension="PDO_DBLIB";function
connect($M,$V,$E){$this->dsn("dblib:charset=utf8;host=".str_replace(":",";unix_socket=",preg_replace('~:(\d)~',';port=\1',$M)),$V,$E);return
true;}function
select_db($Mb){return$this->query(use_sql($Mb));}}}class
Driver
extends
SqlDriver{static$lg=array("SQLSRV","PDO_SQLSRV","PDO_DBLIB");static$he="mssql";var$editFunctions=array(array("date|time"=>"getdate",),array("int|decimal|real|float|money|datetime"=>"+/-","char|text"=>"+",));var$operators=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL");var$functions=array("len","lower","round","upper");var$grouping=array("avg","count","count distinct","max","min","sum");var$onActions="NO ACTION|CASCADE|SET NULL|SET DEFAULT";var$generated=array("PERSISTED","VIRTUAL");function
__construct($f){parent::__construct($f);$this->types=array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"int"=>10,"bigint"=>20,"bit"=>1,"decimal"=>0,"real"=>12,"float"=>53,"smallmoney"=>10,"money"=>20),'Date and time'=>array("date"=>10,"smalldatetime"=>19,"datetime"=>19,"datetime2"=>19,"time"=>8,"datetimeoffset"=>10),'Strings'=>array("char"=>8000,"varchar"=>8000,"text"=>2147483647,"nchar"=>4000,"nvarchar"=>4000,"ntext"=>1073741823),'Binary'=>array("binary"=>8000,"varbinary"=>8000,"image"=>2147483647),);}function
insertUpdate($Q,$K,$F){$o=fields($Q);$Ri=array();$Z=array();$N=reset($K);$e="c".implode(", c",range(1,count($N)));$Qa=0;$Wd=array();foreach($N
as$y=>$X){$Qa++;$B=idf_unescape($y);if(!$o[$B]["auto_increment"])$Wd[$y]="c$Qa";if(isset($F[$B]))$Z[]="$y = c$Qa";else$Ri[]="$y = c$Qa";}$cj=array();foreach($K
as$N)$cj[]="(".implode(", ",$N).")";if($Z){$Id=queries("SET IDENTITY_INSERT ".table($Q)." ON");$I=queries("MERGE ".table($Q)." USING (VALUES\n\t".implode(",\n\t",$cj)."\n) AS source ($e) ON ".implode(" AND ",$Z).($Ri?"\nWHEN MATCHED THEN UPDATE SET ".implode(", ",$Ri):"")."\nWHEN NOT MATCHED THEN INSERT (".implode(", ",array_keys($Id?$N:$Wd)).") VALUES (".($Id?$e:implode(", ",$Wd)).");");if($Id)queries("SET IDENTITY_INSERT ".table($Q)." OFF");}else$I=queries("INSERT INTO ".table($Q)." (".implode(", ",array_keys($N)).") VALUES\n".implode(",\n",$cj));return$I;}function
begin(){return
queries("BEGIN TRANSACTION");}function
tableHelp($B,$ee=false){$ze=array("sys"=>"catalog-views/sys-","INFORMATION_SCHEMA"=>"information-schema-views/",);$_=$ze[get_schema()];if($_)return"relational-databases/system-$_".preg_replace('~_~','-',strtolower($B))."-transact-sql";}}function
idf_escape($v){return"[".str_replace("]","]]",$v)."]";}function
table($v){return($_GET["ns"]!=""?idf_escape($_GET["ns"]).".":"").idf_escape($v);}function
connect($Fb){$f=new
Db;if($Fb[0]=="")$Fb[0]="localhost:1433";if($f->connect($Fb[0],$Fb[1],$Fb[2]))return$f;return$f->error;}function
get_databases(){return
get_vals("SELECT name FROM sys.databases WHERE name NOT IN ('master', 'tempdb', 'model', 'msdb')");}function
limit($G,$Z,$z,$C=0,$lh=" "){return($z!==null?" TOP (".($z+$C).")":"")." $G$Z";}function
limit1($Q,$G,$Z,$lh="\n"){return
limit($G,$Z,1,0,$lh);}function
db_collation($j,$jb){return
get_val("SELECT collation_name FROM sys.databases WHERE name = ".q($j));}function
engines(){return
array();}function
logged_user(){return
get_val("SELECT SUSER_NAME()");}function
tables_list(){return
get_key_vals("SELECT name, type_desc FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ORDER BY name");}function
count_tables($i){global$f;$I=array();foreach($i
as$j){$f->select_db($j);$I[$j]=get_val("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES");}return$I;}function
table_status($B=""){$I=array();foreach(get_rows("SELECT ao.name AS Name, ao.type_desc AS Engine, (SELECT value FROM fn_listextendedproperty(default, 'SCHEMA', schema_name(schema_id), 'TABLE', ao.name, null, null)) AS Comment
FROM sys.all_objects AS ao
WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ".($B!=""?"AND name = ".q($B):"ORDER BY name"))as$J){if($B!="")return$J;$I[$J["Name"]]=$J;}return$I;}function
is_view($R){return$R["Engine"]=="VIEW";}function
fk_support($R){return
true;}function
fields($Q){$rb=get_key_vals("SELECT objname, cast(value as varchar(max)) FROM fn_listextendedproperty('MS_DESCRIPTION', 'schema', ".q(get_schema()).", 'table', ".q($Q).", 'column', NULL)");$I=array();$Vh=get_val("SELECT object_id FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') AND name = ".q($Q));foreach(get_rows("SELECT c.max_length, c.precision, c.scale, c.name, c.is_nullable, c.is_identity, c.collation_name, t.name type, CAST(d.definition as text) [default], d.name default_constraint, i.is_primary_key
FROM sys.all_columns c
JOIN sys.types t ON c.user_type_id = t.user_type_id
LEFT JOIN sys.default_constraints d ON c.default_object_id = d.object_id
LEFT JOIN sys.index_columns ic ON c.object_id = ic.object_id AND c.column_id = ic.column_id
LEFT JOIN sys.indexes i ON ic.object_id = i.object_id AND ic.index_id = i.index_id
WHERE c.object_id = ".q($Vh))as$J){$U=$J["type"];$we=(preg_match("~char|binary~",$U)?$J["max_length"]/($U[0]=='n'?2:1):($U=="decimal"?"$J[precision],$J[scale]":""));$I[$J["name"]]=array("field"=>$J["name"],"full_type"=>$U.($we?"($we)":""),"type"=>$U,"length"=>$we,"default"=>(preg_match("~^\('(.*)'\)$~",$J["default"],$A)?str_replace("''","'",$A[1]):$J["default"]),"default_constraint"=>$J["default_constraint"],"null"=>$J["is_nullable"],"auto_increment"=>$J["is_identity"],"collation"=>$J["collation_name"],"privileges"=>array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1),"primary"=>$J["is_primary_key"],"comment"=>$rb[$J["name"]],);}foreach(get_rows("SELECT * FROM sys.computed_columns WHERE object_id = ".q($Vh))as$J){$I[$J["name"]]["generated"]=($J["is_persisted"]?"PERSISTED":"VIRTUAL");$I[$J["name"]]["default"]=$J["definition"];}return$I;}function
indexes($Q,$g=null){$I=array();foreach(get_rows("SELECT i.name, key_ordinal, is_unique, is_primary_key, c.name AS column_name, is_descending_key
FROM sys.indexes i
INNER JOIN sys.index_columns ic ON i.object_id = ic.object_id AND i.index_id = ic.index_id
INNER JOIN sys.columns c ON ic.object_id = c.object_id AND ic.column_id = c.column_id
WHERE OBJECT_NAME(i.object_id) = ".q($Q),$g)as$J){$B=$J["name"];$I[$B]["type"]=($J["is_primary_key"]?"PRIMARY":($J["is_unique"]?"UNIQUE":"INDEX"));$I[$B]["lengths"]=array();$I[$B]["columns"][$J["key_ordinal"]]=$J["column_name"];$I[$B]["descs"][$J["key_ordinal"]]=($J["is_descending_key"]?'1':null);}return$I;}function
view($B){return
array("select"=>preg_replace('~^(?:[^[]|\[[^]]*])*\s+AS\s+~isU','',get_val("SELECT VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = SCHEMA_NAME() AND TABLE_NAME = ".q($B))));}function
collations(){$I=array();foreach(get_vals("SELECT name FROM fn_helpcollations()")as$ib)$I[preg_replace('~_.*~','',$ib)][]=$ib;return$I;}function
information_schema($j){return
get_schema()=="INFORMATION_SCHEMA";}function
error(){global$f;return
nl_br(h(preg_replace('~^(\[[^]]*])+~m','',$f->error)));}function
create_database($j,$ib){return
queries("CREATE DATABASE ".idf_escape($j).(preg_match('~^[a-z0-9_]+$~i',$ib)?" COLLATE $ib":""));}function
drop_databases($i){return
queries("DROP DATABASE ".implode(", ",array_map('Adminer\idf_escape',$i)));}function
rename_database($B,$ib){if(preg_match('~^[a-z0-9_]+$~i',$ib))queries("ALTER DATABASE ".idf_escape(DB)." COLLATE $ib");queries("ALTER DATABASE ".idf_escape(DB)." MODIFY NAME = ".idf_escape($B));return
true;}function
auto_increment(){return" IDENTITY".($_POST["Auto_increment"]!=""?"(".number($_POST["Auto_increment"]).",1)":"")." PRIMARY KEY";}function
alter_table($Q,$B,$o,$fd,$pb,$vc,$ib,$Da,$Zf){$c=array();$rb=array();$Jf=fields($Q);foreach($o
as$n){$d=idf_escape($n[0]);$X=$n[1];if(!$X)$c["DROP"][]=" COLUMN $d";else{$X[1]=preg_replace("~( COLLATE )'(\\w+)'~",'\1\2',$X[1]);$rb[$n[0]]=$X[5];unset($X[5]);if(preg_match('~ AS ~',$X[3]))unset($X[1],$X[2]);if($n[0]=="")$c["ADD"][]="\n  ".implode("",$X).($Q==""?substr($fd[$X[0]],16+strlen($X[0])):"");else{$k=$X[3];unset($X[3]);unset($X[6]);if($d!=$X[0])queries("EXEC sp_rename ".q(table($Q).".$d").", ".q(idf_unescape($X[0])).", 'COLUMN'");$c["ALTER COLUMN ".implode("",$X)][]="";$If=$Jf[$n[0]];if(default_value($If)!=$k){if($If["default"]!==null)$c["DROP"][]=" ".idf_escape($If["default_constraint"]);if($k)$c["ADD"][]="\n $k FOR $d";}}}}if($Q=="")return
queries("CREATE TABLE ".table($B)." (".implode(",",(array)$c["ADD"])."\n)");if($Q!=$B)queries("EXEC sp_rename ".q(table($Q)).", ".q($B));if($fd)$c[""]=$fd;foreach($c
as$y=>$X){if(!queries("ALTER TABLE ".table($B)." $y".implode(",",$X)))return
false;}foreach($rb
as$y=>$X){$pb=substr($X,9);queries("EXEC sp_dropextendedproperty @name = N'MS_Description', @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table', @level1name = ".q($B).", @level2type = N'Column', @level2name = ".q($y));queries("EXEC sp_addextendedproperty @name = N'MS_Description', @value = ".$pb.", @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table', @level1name = ".q($B).", @level2type = N'Column', @level2name = ".q($y));}return
true;}function
alter_indexes($Q,$c){$w=array();$fc=array();foreach($c
as$X){if($X[2]=="DROP"){if($X[0]=="PRIMARY")$fc[]=idf_escape($X[1]);else$w[]=idf_escape($X[1])." ON ".table($Q);}elseif(!queries(($X[0]!="PRIMARY"?"CREATE $X[0] ".($X[0]!="INDEX"?"INDEX ":"").idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q):"ALTER TABLE ".table($Q)." ADD PRIMARY KEY")." (".implode(", ",$X[2]).")"))return
false;}return(!$w||queries("DROP INDEX ".implode(", ",$w)))&&(!$fc||queries("ALTER TABLE ".table($Q)." DROP ".implode(", ",$fc)));}function
last_id(){return
get_val("SELECT SCOPE_IDENTITY()");}function
explain($f,$G){$f->query("SET SHOWPLAN_ALL ON");$I=$f->query($G);$f->query("SET SHOWPLAN_ALL OFF");return$I;}function
found_rows($R,$Z){}function
foreign_keys($Q){$I=array();$tf=array("CASCADE","NO ACTION","SET NULL","SET DEFAULT");foreach(get_rows("EXEC sp_fkeys @fktable_name = ".q($Q).", @fktable_owner = ".q(get_schema()))as$J){$q=&$I[$J["FK_NAME"]];$q["db"]=$J["PKTABLE_QUALIFIER"];$q["ns"]=$J["PKTABLE_OWNER"];$q["table"]=$J["PKTABLE_NAME"];$q["on_update"]=$tf[$J["UPDATE_RULE"]];$q["on_delete"]=$tf[$J["DELETE_RULE"]];$q["source"][]=$J["FKCOLUMN_NAME"];$q["target"][]=$J["PKCOLUMN_NAME"];}return$I;}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($hj){return
queries("DROP VIEW ".implode(", ",array_map('Adminer\table',$hj)));}function
drop_tables($S){return
queries("DROP TABLE ".implode(", ",array_map('Adminer\table',$S)));}function
move_tables($S,$hj,$ei){return
apply_queries("ALTER SCHEMA ".idf_escape($ei)." TRANSFER",array_merge($S,$hj));}function
trigger($B){if($B=="")return
array();$K=get_rows("SELECT s.name [Trigger],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(s.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(s.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing],
c.text
FROM sysobjects s
JOIN syscomments c ON s.id = c.id
WHERE s.xtype = 'TR' AND s.name = ".q($B));$I=reset($K);if($I)$I["Statement"]=preg_replace('~^.+\s+AS\s+~isU','',$I["text"]);return$I;}function
triggers($Q){$I=array();foreach(get_rows("SELECT sys1.name,
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing]
FROM sysobjects sys1
JOIN sysobjects sys2 ON sys1.parent_obj = sys2.id
WHERE sys1.xtype = 'TR' AND sys2.name = ".q($Q))as$J)$I[$J["name"]]=array($J["Timing"],$J["Event"]);return$I;}function
trigger_options(){return
array("Timing"=>array("AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("AS"),);}function
schemas(){return
get_vals("SELECT name FROM sys.schemas");}function
get_schema(){if($_GET["ns"]!="")return$_GET["ns"];return
get_val("SELECT SCHEMA_NAME()");}function
set_schema($bh){$_GET["ns"]=$bh;return
true;}function
create_sql($Q,$Da,$Oh){global$l;if(is_view(table_status($Q))){$gj=view($Q);return"CREATE VIEW ".table($Q)." AS $gj[select]";}$o=array();$F=false;foreach(fields($Q)as$B=>$n){$X=process_field($n,$n);if($X[6])$F=true;$o[]=implode("",$X);}foreach(indexes($Q)as$B=>$w){if(!$F||$w["type"]!="PRIMARY"){$e=array();foreach($w["columns"]as$y=>$X)$e[]=idf_escape($X).($w["descs"][$y]?" DESC":"");$B=idf_escape($B);$o[]=($w["type"]=="INDEX"?"INDEX $B":"CONSTRAINT $B ".($w["type"]=="UNIQUE"?"UNIQUE":"PRIMARY KEY"))." (".implode(", ",$e).")";}}foreach($l->checkConstraints($Q)as$B=>$Wa)$o[]="CONSTRAINT ".idf_escape($B)." CHECK ($Wa)";return"CREATE TABLE ".table($Q)." (\n\t".implode(",\n\t",$o)."\n)";}function
foreign_keys_sql($Q){$o=array();foreach(foreign_keys($Q)as$fd)$o[]=ltrim(format_foreign_key($fd));return($o?"ALTER TABLE ".table($Q)." ADD\n\t".implode(",\n\t",$o).";\n\n":"");}function
truncate_sql($Q){return"TRUNCATE TABLE ".table($Q);}function
use_sql($Mb){return"USE ".idf_escape($Mb);}function
trigger_sql($Q){$I="";foreach(triggers($Q)as$B=>$Ci)$I.=create_trigger(" ON ".table($Q),trigger($B)).";";return$I;}function
convert_field($n){}function
unconvert_field($n,$I){return$I;}function
support($Tc){return
preg_match('~^(check|comment|columns|database|drop_col|dump|indexes|descidx|scheme|sql|table|trigger|view|view_trigger)$~',$Tc);}}$ec["mongo"]="MongoDB (alpha)";if(isset($_GET["mongo"])){define('Adminer\DRIVER',"mongo");if(class_exists('MongoDB\Driver\Manager')){class
Db{var$extension="MongoDB",$server_info=MONGODB_VERSION,$affected_rows,$error,$last_id;var$_link;var$_db,$_db_name;function
connect($Si,$Af){$this->_link=new
\MongoDB\Driver\Manager($Si,$Af);$this->executeDbCommand($Af["db"],array('ping'=>1));}function
executeCommand($nb){return$this->executeDbCommand($this->_db_name);}function
executeDbCommand($j,$nb){try{return$this->_link->executeCommand($j,new
\MongoDB\Driver\Command($nb));}catch(Exception$mc){$this->error=$mc->getMessage();return
array();}}function
executeBulkWrite($af,$Pa,$Cb){try{$Qg=$this->_link->executeBulkWrite($af,$Pa);$this->affected_rows=$Qg->$Cb();return
true;}catch(Exception$mc){$this->error=$mc->getMessage();return
false;}}function
query($G){return
false;}function
select_db($Mb){$this->_db_name=$Mb;return
true;}function
quote($P){return$P;}}class
Result{var$num_rows;private$rows=array(),$offset=0,$charset=array();function
__construct($H){foreach($H
as$fe){$J=array();foreach($fe
as$y=>$X){if(is_a($X,'MongoDB\BSON\Binary'))$this->charset[$y]=63;$J[$y]=(is_a($X,'MongoDB\BSON\ObjectID')?'MongoDB\BSON\ObjectID("'."$X\")":(is_a($X,'MongoDB\BSON\UTCDatetime')?$X->toDateTime()->format('Y-m-d H:i:s'):(is_a($X,'MongoDB\BSON\Binary')?$X->getData():(is_a($X,'MongoDB\BSON\Regex')?"$X":(is_object($X)||is_array($X)?json_encode($X,256):$X)))));}$this->rows[]=$J;foreach($J
as$y=>$X){if(!isset($this->rows[0][$y]))$this->rows[0][$y]=null;}}$this->num_rows=count($this->rows);}function
fetch_assoc(){$J=current($this->rows);if(!$J)return$J;$I=array();foreach($this->rows[0]as$y=>$X)$I[$y]=$J[$y];next($this->rows);return$I;}function
fetch_row(){$I=$this->fetch_assoc();if(!$I)return$I;return
array_values($I);}function
fetch_field(){$ke=array_keys($this->rows[0]);$B=$ke[$this->offset++];return(object)array('name'=>$B,'charsetnr'=>$this->charset[$B],);}}function
get_databases($dd){global$f;$I=array();foreach($f->executeCommand(array('listDatabases'=>1))as$Qb){foreach($Qb->databases
as$j)$I[]=$j->name;}return$I;}function
count_tables($i){$I=array();return$I;}function
tables_list(){global$f;$kb=array();foreach($f->executeCommand(array('listCollections'=>1))as$H)$kb[$H->name]='table';return$kb;}function
drop_databases($i){return
false;}function
indexes($Q,$g=null){global$f;$I=array();foreach($f->executeCommand(array('listIndexes'=>$Q))as$w){$Xb=array();$e=array();foreach(get_object_vars($w->key)as$d=>$U){$Xb[]=($U==-1?'1':null);$e[]=$d;}$I[$w->name]=array("type"=>($w->name=="_id_"?"PRIMARY":(isset($w->unique)?"UNIQUE":"INDEX")),"columns"=>$e,"lengths"=>array(),"descs"=>$Xb,);}return$I;}function
fields($Q){global$l;$o=fields_from_edit();if(!$o){$H=$l->select($Q,array("*"),null,null,array(),10);if($H){while($J=$H->fetch_assoc()){foreach($J
as$y=>$X){$J[$y]=null;$o[$y]=array("field"=>$y,"type"=>"string","null"=>($y!=$l->primary),"auto_increment"=>($y==$l->primary),"privileges"=>array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1,),);}}}}return$o;}function
found_rows($R,$Z){global$f;$Z=where_to_query($Z);$ui=$f->executeCommand(array('count'=>$R['Name'],'query'=>$Z))->toArray();return$ui[0]->n;}function
sql_query_where_parser($zg){$zg=preg_replace('~^\s*WHERE\s*~',"",$zg);while($zg[0]=="(")$zg=preg_replace('~^\((.*)\)$~',"$1",$zg);$rj=explode(' AND ',$zg);$sj=explode(') OR (',$zg);$Z=array();foreach($rj
as$pj)$Z[]=trim($pj);if(count($sj)==1)$sj=array();elseif(count($sj)>1)$Z=array();return
where_to_query($Z,$sj);}function
where_to_query($nj=array(),$oj=array()){global$b;$Kb=array();foreach(array('and'=>$nj,'or'=>$oj)as$U=>$Z){if(is_array($Z)){foreach($Z
as$Lc){list($gb,$wf,$X)=explode(" ",$Lc,3);if($gb=="_id"&&preg_match('~^(MongoDB\\\\BSON\\\\ObjectID)\("(.+)"\)$~',$X,$A)){list(,$db,$X)=$A;$X=new$db($X);}if(!in_array($wf,$b->operators))continue;if(preg_match('~^\(f\)(.+)~',$wf,$A)){$X=(float)$X;$wf=$A[1];}elseif(preg_match('~^\(date\)(.+)~',$wf,$A)){$Nb=new
\DateTime($X);$X=new
\MongoDB\BSON\UTCDatetime($Nb->getTimestamp()*1000);$wf=$A[1];}switch($wf){case'=':$wf='$eq';break;case'!=':$wf='$ne';break;case'>':$wf='$gt';break;case'<':$wf='$lt';break;case'>=':$wf='$gte';break;case'<=':$wf='$lte';break;case'regex':$wf='$regex';break;default:continue
2;}if($U=='and')$Kb['$and'][]=array($gb=>array($wf=>$X));elseif($U=='or')$Kb['$or'][]=array($gb=>array($wf=>$X));}}}return$Kb;}}class
Driver
extends
SqlDriver{static$lg=array("mongodb");static$he="mongo";var$editFunctions=array(array("json"));var$operators=array("=","!=",">","<",">=","<=","regex","(f)=","(f)!=","(f)>","(f)<","(f)>=","(f)<=","(date)=","(date)!=","(date)>","(date)<","(date)>=","(date)<=",);var$primary="_id";function
select($Q,$L,$Z,$sd,$Cf=array(),$z=1,$D=0,$qg=false){$L=($L==array("*")?array():array_fill_keys($L,1));if(count($L)&&!isset($L['_id']))$L['_id']=0;$Z=where_to_query($Z);$_h=array();foreach($Cf
as$X){$X=preg_replace('~ DESC$~','',$X,1,$Bb);$_h[$X]=($Bb?-1:1);}if(isset($_GET['limit'])&&is_numeric($_GET['limit'])&&$_GET['limit']>0)$z=$_GET['limit'];$z=min(200,max(1,(int)$z));$xh=$D*$z;try{return
new
Result($this->conn->_link->executeQuery($this->conn->_db_name.".$Q",new
\MongoDB\Driver\Query($Z,array('projection'=>$L,'limit'=>$z,'skip'=>$xh,'sort'=>$_h))));}catch(Exception$mc){$this->conn->error=$mc->getMessage();return
false;}}function
update($Q,$N,$zg,$z=0,$lh="\n"){$j=$this->conn->_db_name;$Z=sql_query_where_parser($zg);$Pa=new
\MongoDB\Driver\BulkWrite(array());if(isset($N['_id']))unset($N['_id']);$Kg=array();foreach($N
as$y=>$Y){if($Y=='NULL'){$Kg[$y]=1;unset($N[$y]);}}$Ri=array('$set'=>$N);if(count($Kg))$Ri['$unset']=$Kg;$Pa->update($Z,$Ri,array('upsert'=>false));return$this->conn->executeBulkWrite("$j.$Q",$Pa,'getModifiedCount');}function
delete($Q,$zg,$z=0){$j=$this->conn->_db_name;$Z=sql_query_where_parser($zg);$Pa=new
\MongoDB\Driver\BulkWrite(array());$Pa->delete($Z,array('limit'=>$z));return$this->conn->executeBulkWrite("$j.$Q",$Pa,'getDeletedCount');}function
insert($Q,$N){$j=$this->conn->_db_name;$Pa=new
\MongoDB\Driver\BulkWrite(array());if($N['_id']=='')unset($N['_id']);$Pa->insert($N);return$this->conn->executeBulkWrite("$j.$Q",$Pa,'getInsertedCount');}}function
table($v){return$v;}function
idf_escape($v){return$v;}function
table_status($B="",$Sc=false){$I=array();foreach(tables_list()as$Q=>$U){$I[$Q]=array("Name"=>$Q);if($B==$Q)return$I[$Q];}return$I;}function
create_database($j,$ib){return
true;}function
last_id(){global$f;return$f->last_id;}function
error(){global$f;return
h($f->error);}function
collations(){return
array();}function
logged_user(){global$b;$Fb=$b->credentials();return$Fb[1];}function
connect($Fb){global$b;$f=new
Db;list($M,$V,$E)=$Fb;if($M=="")$M="localhost:27017";$Af=array();if($V.$E!=""){$Af["username"]=$V;$Af["password"]=$E;}$j=$b->database();if($j!="")$Af["db"]=$j;if(($Ca=getenv("MONGO_AUTH_SOURCE")))$Af["authSource"]=$Ca;$f->connect("mongodb://$M",$Af);if($f->error)return$f->error;return$f;}function
alter_indexes($Q,$c){global$f;foreach($c
as$X){list($U,$B,$N)=$X;if($N=="DROP")$I=$f->_db->command(array("deleteIndexes"=>$Q,"index"=>$B));else{$e=array();foreach($N
as$d){$d=preg_replace('~ DESC$~','',$d,1,$Bb);$e[$d]=($Bb?-1:1);}$I=$f->_db->selectCollection($Q)->ensureIndex($e,array("unique"=>($U=="UNIQUE"),"name"=>$B,));}if($I['errmsg']){$f->error=$I['errmsg'];return
false;}}return
true;}function
support($Tc){return
preg_match("~database|indexes|descidx~",$Tc);}function
db_collation($j,$jb){}function
information_schema(){}function
is_view($R){}function
convert_field($n){}function
unconvert_field($n,$I){return$I;}function
foreign_keys($Q){return
array();}function
fk_support($R){}function
engines(){return
array();}function
alter_table($Q,$B,$o,$fd,$pb,$vc,$ib,$Da,$Zf){global$f;if($Q==""){$f->_db->createCollection($B);return
true;}}function
drop_tables($S){global$f;foreach($S
as$Q){$Ng=$f->_db->selectCollection($Q)->drop();if(!$Ng['ok'])return
false;}return
true;}function
truncate_tables($S){global$f;foreach($S
as$Q){$Ng=$f->_db->selectCollection($Q)->remove();if(!$Ng['ok'])return
false;}return
true;}}class
Adminer{var$operators;function
name(){return"<a href='https://www.adminer.org/'".target_blank()." id='h1'>Adminer</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_password());}function
connectSsl(){}function
permanentLogin($h=false){return
password_file($h);}function
bruteForceKey(){return$_SERVER["REMOTE_ADDR"];}function
serverName($M){return
h($M);}function
database(){return
DB;}function
databases($dd=true){return
get_databases($dd);}function
schemas(){return
schemas();}function
queryTimeout(){return
2;}function
headers(){}function
csp(){return
csp();}function
head(){return
true;}function
css(){$I=array();$p="adminer.css";if(file_exists($p))$I[]="$p?v=".crc32(file_get_contents($p));return$I;}function
loginForm(){global$ec;echo"<table class='layout'>\n",$this->loginFormField('driver','<tr><th>'.'System'.'<td>',html_select("auth[driver]",$ec,DRIVER,"loginDriver(this);")),$this->loginFormField('server','<tr><th>'.'Server'.'<td>','<input name="auth[server]" value="'.h(SERVER).'" title="hostname[:port]" placeholder="localhost" autocapitalize="off">'),$this->loginFormField('username','<tr><th>'.'Username'.'<td>','<input name="auth[username]" id="username" autofocus value="'.h($_GET["username"]).'" autocomplete="username" autocapitalize="off">'.script("qs('#username').form['auth[driver]'].onchange();")),$this->loginFormField('password','<tr><th>'.'Password'.'<td>','<input type="password" name="auth[password]" autocomplete="current-password">'),$this->loginFormField('db','<tr><th>'.'Database'.'<td>','<input name="auth[db]" value="'.h($_GET["db"]).'" autocapitalize="off">'),"</table>\n","<p><input type='submit' value='".'Login'."'>\n",checkbox("auth[permanent]",1,$_COOKIE["adminer_permanent"],'Permanent login')."\n";}function
loginFormField($B,$Cd,$Y){return$Cd.$Y."\n";}function
login($Ae,$E){if($E=="")return
sprintf('Adminer does not support accessing a database without a password, <a href="https://www.adminer.org/en/password/"%s>more information</a>.',target_blank());return
true;}function
tableName($Uh){return
h($Uh["Name"]);}function
fieldName($n,$Cf=0){return'<span title="'.h($n["full_type"]).'">'.h($n["field"]).'</span>';}function
selectLinks($Uh,$N=""){global$l;echo'<p class="links">';$ze=array("select"=>'Select data');if(support("table")||support("indexes"))$ze["table"]='Show structure';$ee=false;if(support("table")){$ee=is_view($Uh);if($ee)$ze["view"]='Alter view';else$ze["create"]='Alter table';}if($N!==null)$ze["edit"]='New item';$B=$Uh["Name"];foreach($ze
as$y=>$X)echo" <a href='".h(ME)."$y=".urlencode($B).($y=="edit"?$N:"")."'".bold(isset($_GET[$y])).">$X</a>";echo
doc_link(array(JUSH=>$l->tableHelp($B,$ee)),"?"),"\n";}function
foreignKeys($Q){return
foreign_keys($Q);}function
backwardKeys($Q,$Th){return
array();}function
backwardKeysPrint($Ga,$J){}function
selectQuery($G,$Kh,$Rc=false){global$l;$I="</p>\n";if(!$Rc&&($kj=$l->warnings())){$u="warnings";$I=", <a href='#$u'>".'Warnings'."</a>".script("qsl('a').onclick = partial(toggle, '$u');","")."$I<div id='$u' class='hidden'>\n$kj</div>\n";}return"<p><code class='jush-".JUSH."'>".h(str_replace("\n"," ",$G))."</code> <span class='time'>(".format_time($Kh).")</span>".(support("sql")?" <a href='".h(ME)."sql=".urlencode($G)."'>".'Edit'."</a>":"").$I;}function
sqlCommandQuery($G){return
shorten_utf8(trim($G),1000);}function
rowDescription($Q){return"";}function
rowDescriptions($K,$gd){return$K;}function
selectLink($X,$n){}function
selectVal($X,$_,$n,$Mf){$I=($X===null?"<i>NULL</i>":(preg_match("~char|binary|boolean~",$n["type"])&&!preg_match("~var~",$n["type"])?"<code>$X</code>":$X));if(preg_match('~blob|bytea|raw|file~',$n["type"])&&!is_utf8($X))$I="<i>".lang(array('%d byte','%d bytes'),strlen($Mf))."</i>";if(preg_match('~json~',$n["type"]))$I="<code class='jush-js'>$I</code>";return($_?"<a href='".h($_)."'".(is_url($_)?target_blank():"").">$I</a>":$I);}function
editVal($X,$n){return$X;}function
tableStructurePrint($o){global$l;echo"<div class='scrollable'>\n","<table class='nowrap odds'>\n","<thead><tr><th>".'Column'."<td>".'Type'.(support("comment")?"<td>".'Comment':"")."</thead>\n";$Nh=$l->structuredTypes();foreach($o
as$n){echo"<tr><th>".h($n["field"]);$U=h($n["full_type"]);echo"<td><span title='".h($n["collation"])."'>".(in_array($U,(array)$Nh['User types'])?"<a href='".h(ME.'type='.urlencode($U))."'>$U</a>":$U)."</span>",($n["null"]?" <i>NULL</i>":""),($n["auto_increment"]?" <i>".'Auto Increment'."</i>":"");$k=h($n["default"]);echo(isset($n["default"])?" <span title='".'Default value'."'>[<b>".($n["generated"]?"<code class='jush-".JUSH."'>$k</code>":$k)."</b>]</span>":""),(support("comment")?"<td>".h($n["comment"]):""),"\n";}echo"</table>\n","</div>\n";}function
tableIndexesPrint($x){echo"<table>\n";foreach($x
as$B=>$w){ksort($w["columns"]);$qg=array();foreach($w["columns"]as$y=>$X)$qg[]="<i>".h($X)."</i>".($w["lengths"][$y]?"(".$w["lengths"][$y].")":"").($w["descs"][$y]?" DESC":"");echo"<tr title='".h($B)."'><th>$w[type]<td>".implode(", ",$qg)."\n";}echo"</table>\n";}function
selectColumnsPrint($L,$e){global$l;print_fieldset("select",'Select',$L);$t=0;$L[""]=array();foreach($L
as$y=>$X){$X=$_GET["columns"][$y];$d=select_input(" name='columns[$t][col]'",$e,$X["col"],($y!==""?"selectFieldChange":"selectAddRow"));echo"<div>".($l->functions||$l->grouping?html_select("columns[$t][fun]",array(-1=>"")+array_filter(array('Functions'=>$l->functions,'Aggregation'=>$l->grouping)),$X["fun"]).on_help("getTarget(event).value && getTarget(event).value.replace(/ |\$/, '(') + ')'",1).script("qsl('select').onchange = function () { helpClose();".($y!==""?"":" qsl('select, input', this.parentNode).onchange();")." };","")."($d)":$d)."</div>\n";$t++;}echo"</div></fieldset>\n";}function
selectSearchPrint($Z,$e,$x){print_fieldset("search",'Search',$Z);foreach($x
as$t=>$w){if($w["type"]=="FULLTEXT"){echo"<div>(<i>".implode("</i>, <i>",array_map('Adminer\h',$w["columns"]))."</i>) AGAINST"," <input type='search' name='fulltext[$t]' value='".h($_GET["fulltext"][$t])."'>",script("qsl('input').oninput = selectFieldChange;",""),checkbox("boolean[$t]",1,isset($_GET["boolean"][$t]),"BOOL"),"</div>\n";}}$Ua="this.parentNode.firstChild.onchange();";foreach(array_merge((array)$_GET["where"],array(array()))as$t=>$X){if(!$X||("$X[col]$X[val]"!=""&&in_array($X["op"],$this->operators))){echo"<div>".select_input(" name='where[$t][col]'",$e,$X["col"],($X?"selectFieldChange":"selectAddRow"),"(".'anywhere'.")"),html_select("where[$t][op]",$this->operators,$X["op"],$Ua),"<input type='search' name='where[$t][val]' value='".h($X["val"])."'>",script("mixin(qsl('input'), {oninput: function () { $Ua }, onkeydown: selectSearchKeydown, onsearch: selectSearchSearch});",""),"</div>\n";}}echo"</div></fieldset>\n";}function
selectOrderPrint($Cf,$e,$x){print_fieldset("sort",'Sort',$Cf);$t=0;foreach((array)$_GET["order"]as$y=>$X){if($X!=""){echo"<div>".select_input(" name='order[$t]'",$e,$X,"selectFieldChange"),checkbox("desc[$t]",1,isset($_GET["desc"][$y]),'descending')."</div>\n";$t++;}}echo"<div>".select_input(" name='order[$t]'",$e,"","selectAddRow"),checkbox("desc[$t]",1,false,'descending')."</div>\n","</div></fieldset>\n";}function
selectLimitPrint($z){echo"<fieldset><legend>".'Limit'."</legend><div>";echo"<input type='number' name='limit' class='size' value='".h($z)."'>",script("qsl('input').oninput = selectFieldChange;",""),"</div></fieldset>\n";}function
selectLengthPrint($ki){if($ki!==null){echo"<fieldset><legend>".'Text length'."</legend><div>","<input type='number' name='text_length' class='size' value='".h($ki)."'>","</div></fieldset>\n";}}function
selectActionPrint($x){echo"<fieldset><legend>".'Action'."</legend><div>","<input type='submit' value='".'Select'."'>"," <span id='noindex' title='".'Full table scan'."'></span>","<script".nonce().">\n","var indexColumns = ";$e=array();foreach($x
as$w){$Jb=reset($w["columns"]);if($w["type"]!="FULLTEXT"&&$Jb)$e[$Jb]=1;}$e[""]=1;foreach($e
as$y=>$X)json_row($y);echo";\n","selectFieldChange.call(qs('#form')['select']);\n","</script>\n","</div></fieldset>\n";}function
selectCommandPrint(){return!information_schema(DB);}function
selectImportPrint(){return!information_schema(DB);}function
selectEmailPrint($sc,$e){}function
selectColumnsProcess($e,$x){global$l;$L=array();$sd=array();foreach((array)$_GET["columns"]as$y=>$X){if($X["fun"]=="count"||($X["col"]!=""&&(!$X["fun"]||in_array($X["fun"],$l->functions)||in_array($X["fun"],$l->grouping)))){$L[$y]=apply_sql_function($X["fun"],($X["col"]!=""?idf_escape($X["col"]):"*"));if(!in_array($X["fun"],$l->grouping))$sd[]=$L[$y];}}return
array($L,$sd);}function
selectSearchProcess($o,$x){global$f,$l;$I=array();foreach($x
as$t=>$w){if($w["type"]=="FULLTEXT"&&$_GET["fulltext"][$t]!="")$I[]="MATCH (".implode(", ",array_map('Adminer\idf_escape',$w["columns"])).") AGAINST (".q($_GET["fulltext"][$t]).(isset($_GET["boolean"][$t])?" IN BOOLEAN MODE":"").")";}foreach((array)$_GET["where"]as$y=>$X){if("$X[col]$X[val]"!=""&&in_array($X["op"],$this->operators)){$ng="";$sb=" $X[op]";if(preg_match('~IN$~',$X["op"])){$Md=process_length($X["val"]);$sb.=" ".($Md!=""?$Md:"(NULL)");}elseif($X["op"]=="SQL")$sb=" $X[val]";elseif($X["op"]=="LIKE %%")$sb=" LIKE ".$this->processInput($o[$X["col"]],"%$X[val]%");elseif($X["op"]=="ILIKE %%")$sb=" ILIKE ".$this->processInput($o[$X["col"]],"%$X[val]%");elseif($X["op"]=="FIND_IN_SET"){$ng="$X[op](".q($X["val"]).", ";$sb=")";}elseif(!preg_match('~NULL$~',$X["op"]))$sb.=" ".$this->processInput($o[$X["col"]],$X["val"]);if($X["col"]!="")$I[]=$ng.$l->convertSearch(idf_escape($X["col"]),$X,$o[$X["col"]]).$sb;else{$lb=array();foreach($o
as$B=>$n){if(isset($n["privileges"]["where"])&&(preg_match('~^[-\d.'.(preg_match('~IN$~',$X["op"])?',':'').']+$~',$X["val"])||!preg_match('~'.number_type().'|bit~',$n["type"]))&&(!preg_match("~[\x80-\xFF]~",$X["val"])||preg_match('~char|text|enum|set~',$n["type"]))&&(!preg_match('~date|timestamp~',$n["type"])||preg_match('~^\d+-\d+-\d+~',$X["val"])))$lb[]=$ng.$l->convertSearch(idf_escape($B),$X,$n).$sb;}$I[]=($lb?"(".implode(" OR ",$lb).")":"1 = 0");}}}return$I;}function
selectOrderProcess($o,$x){$I=array();foreach((array)$_GET["order"]as$y=>$X){if($X!="")$I[]=(preg_match('~^((COUNT\(DISTINCT |[A-Z0-9_]+\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\)|COUNT\(\*\))$~',$X)?$X:idf_escape($X)).(isset($_GET["desc"][$y])?" DESC":"");}return$I;}function
selectLimitProcess(){return(isset($_GET["limit"])?$_GET["limit"]:"50");}function
selectLengthProcess(){return(isset($_GET["text_length"])?$_GET["text_length"]:"100");}function
selectEmailProcess($Z,$gd){return
false;}function
selectQueryBuild($L,$Z,$sd,$Cf,$z,$D){return"";}function
messageQuery($G,$li,$Rc=false){global$l;restart_session();$Dd=&get_session("queries");if(!$Dd[$_GET["db"]])$Dd[$_GET["db"]]=array();if(strlen($G)>1e6)$G=preg_replace('~[\x80-\xFF]+$~','',substr($G,0,1e6))."\n…";$Dd[$_GET["db"]][]=array($G,time(),$li);$Gh="sql-".count($Dd[$_GET["db"]]);$I="<a href='#$Gh' class='toggle'>".'SQL command'."</a>\n";if(!$Rc&&($kj=$l->warnings())){$u="warnings-".count($Dd[$_GET["db"]]);$I="<a href='#$u' class='toggle'>".'Warnings'."</a>, $I<div id='$u' class='hidden'>\n$kj</div>\n";}return" <span class='time'>".@date("H:i:s")."</span>"." $I<div id='$Gh' class='hidden'><pre><code class='jush-".JUSH."'>".shorten_utf8($G,1000)."</code></pre>".($li?" <span class='time'>($li)</span>":'').(support("sql")?'<p><a href="'.h(str_replace("db=".urlencode(DB),"db=".urlencode($_GET["db"]),ME).'sql=&history='.(count($Dd[$_GET["db"]])-1)).'">'.'Edit'.'</a>':'').'</div>';}function
editRowPrint($Q,$o,$J,$Ri){}function
editFunctions($n){global$l;$I=($n["null"]?"NULL/":"");$Ri=isset($_GET["select"])||where($_GET);foreach($l->editFunctions
as$y=>$nd){if(!$y||(!isset($_GET["call"])&&$Ri)){foreach($nd
as$dg=>$X){if(!$dg||preg_match("~$dg~",$n["type"]))$I.="/$X";}}if($y&&!preg_match('~set|blob|bytea|raw|file|bool~',$n["type"]))$I.="/SQL";}if($n["auto_increment"]&&!$Ri)$I='Auto Increment';return
explode("/",$I);}function
editInput($Q,$n,$Aa,$Y){if($n["type"]=="enum")return(isset($_GET["select"])?"<label><input type='radio'$Aa value='-1' checked><i>".'original'."</i></label> ":"").($n["null"]?"<label><input type='radio'$Aa value=''".($Y!==null||isset($_GET["select"])?"":" checked")."><i>NULL</i></label> ":"").enum_input("radio",$Aa,$n,$Y,$Y===0?0:null);return"";}function
editHint($Q,$n,$Y){return"";}function
processInput($n,$Y,$s=""){if($s=="SQL")return$Y;$B=$n["field"];$I=q($Y);if(preg_match('~^(now|getdate|uuid)$~',$s))$I="$s()";elseif(preg_match('~^current_(date|timestamp)$~',$s))$I=$s;elseif(preg_match('~^([+-]|\|\|)$~',$s))$I=idf_escape($B)." $s $I";elseif(preg_match('~^[+-] interval$~',$s))$I=idf_escape($B)." $s ".(preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+\$~i",$Y)?$Y:$I);elseif(preg_match('~^(addtime|subtime|concat)$~',$s))$I="$s(".idf_escape($B).", $I)";elseif(preg_match('~^(md5|sha1|password|encrypt)$~',$s))$I="$s($I)";return
unconvert_field($n,$I);}function
dumpOutput(){$I=array('text'=>'open','file'=>'save');if(function_exists('gzencode'))$I['gz']='gzip';return$I;}function
dumpFormat(){return(support("dump")?array('sql'=>'SQL'):array())+array('csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpDatabase($j){}function
dumpTable($Q,$Oh,$ee=0){if($_POST["format"]!="sql"){echo"\xef\xbb\xbf";if($Oh)dump_csv(array_keys(fields($Q)));}else{if($ee==2){$o=array();foreach(fields($Q)as$B=>$n)$o[]=idf_escape($B)." $n[full_type]";$h="CREATE TABLE ".table($Q)." (".implode(", ",$o).")";}else$h=create_sql($Q,$_POST["auto_increment"],$Oh);set_utf8mb4($h);if($Oh&&$h){if($Oh=="DROP+CREATE"||$ee==1)echo"DROP ".($ee==2?"VIEW":"TABLE")." IF EXISTS ".table($Q).";\n";if($ee==1)$h=remove_definer($h);echo"$h;\n\n";}}}function
dumpData($Q,$Oh,$G){global$f;if($Oh){$He=(JUSH=="sqlite"?0:1048576);$o=array();$Jd=false;if($_POST["format"]=="sql"){if($Oh=="TRUNCATE+INSERT")echo
truncate_sql($Q).";\n";$o=fields($Q);if(JUSH=="mssql"){foreach($o
as$n){if($n["auto_increment"]){echo"SET IDENTITY_INSERT ".table($Q)." ON;\n";$Jd=true;break;}}}}$H=$f->query($G,1);if($H){$Wd="";$Oa="";$ke=array();$od=array();$Qh="";$Uc=($Q!=''?'fetch_assoc':'fetch_row');while($J=$H->$Uc()){if(!$ke){$cj=array();foreach($J
as$X){$n=$H->fetch_field();if($o[$n->name]['generated']){$od[$n->name]=true;continue;}$ke[]=$n->name;$y=idf_escape($n->name);$cj[]="$y = VALUES($y)";}$Qh=($Oh=="INSERT+UPDATE"?"\nON DUPLICATE KEY UPDATE ".implode(", ",$cj):"").";\n";}if($_POST["format"]!="sql"){if($Oh=="table"){dump_csv($ke);$Oh="INSERT";}dump_csv($J);}else{if(!$Wd)$Wd="INSERT INTO ".table($Q)." (".implode(", ",array_map('Adminer\idf_escape',$ke)).") VALUES";foreach($J
as$y=>$X){if($od[$y]){unset($J[$y]);continue;}$n=$o[$y];$J[$y]=($X!==null?unconvert_field($n,preg_match(number_type(),$n["type"])&&!preg_match('~\[~',$n["full_type"])&&is_numeric($X)?$X:q(($X===false?0:$X))):"NULL");}$Zg=($He?"\n":" ")."(".implode(",\t",$J).")";if(!$Oa)$Oa=$Wd.$Zg;elseif(strlen($Oa)+4+strlen($Zg)+strlen($Qh)<$He)$Oa.=",$Zg";else{echo$Oa.$Qh;$Oa=$Wd.$Zg;}}}if($Oa)echo$Oa.$Qh;}elseif($_POST["format"]=="sql")echo"-- ".str_replace("\n"," ",$f->error)."\n";if($Jd)echo"SET IDENTITY_INSERT ".table($Q)." OFF;\n";}}function
dumpFilename($Hd){return
friendly_url($Hd!=""?$Hd:(SERVER!=""?SERVER:"localhost"));}function
dumpHeaders($Hd,$Ve=false){$Pf=$_POST["output"];$Mc=(preg_match('~sql~',$_POST["format"])?"sql":($Ve?"tar":"csv"));header("Content-Type: ".($Pf=="gz"?"application/x-gzip":($Mc=="tar"?"application/x-tar":($Mc=="sql"||$Pf!="file"?"text/plain":"text/csv")."; charset=utf-8")));if($Pf=="gz"){ob_start(function($P){return
gzencode($P);},1e6);}return$Mc;}function
dumpFooter(){if($_POST["format"]=="sql")echo"-- ".gmdate("Y-m-d H:i:s e")."\n";}function
importServerPath(){return"adminer.sql";}function
homepage(){echo'<p class="links">'.($_GET["ns"]==""&&support("database")?'<a href="'.h(ME).'database=">'.'Alter database'."</a>\n":""),(support("scheme")?"<a href='".h(ME)."scheme='>".($_GET["ns"]!=""?'Alter schema':'Create schema')."</a>\n":""),($_GET["ns"]!==""?'<a href="'.h(ME).'schema=">'.'Database schema'."</a>\n":""),(support("privileges")?"<a href='".h(ME)."privileges='>".'Privileges'."</a>\n":"");return
true;}function
navigation($Ue){global$ia,$ec,$f;echo'<h1>
',$this->name(),'<span class="version">
',$ia,' <a href="https://www.adminer.org/#download"',target_blank(),' id="version">',(version_compare($ia,$_COOKIE["adminer_version"])<0?h($_COOKIE["adminer_version"]):""),'</a>
</span>
</h1>
';if($Ue=="auth"){$Pf="";foreach((array)$_SESSION["pwds"]as$ej=>$qh){foreach($qh
as$M=>$Zi){foreach($Zi
as$V=>$E){if($E!==null){$Qb=$_SESSION["db"][$ej][$M][$V];foreach(($Qb?array_keys($Qb):array(""))as$j)$Pf.="<li><a href='".h(auth_url($ej,$M,$V,$j))."'>($ec[$ej]) ".h($V.($M!=""?"@".$this->serverName($M):"").($j!=""?" - $j":""))."</a>\n";}}}}if($Pf)echo"<ul id='logins'>\n$Pf</ul>\n".script("mixin(qs('#logins'), {onmouseover: menuOver, onmouseout: menuOut});");}else{$S=array();if($_GET["ns"]!==""&&!$Ue&&DB!=""){$f->select_db(DB);$S=table_status('',true);}echo
script_src(preg_replace("~\\?.*~","",ME)."?file=jush.js&version=5.0.4");if(support("sql")){echo'<script',nonce(),'>
';if($S){$ze=array();foreach($S
as$Q=>$U)$ze[]=preg_quote($Q,'/');echo"var jushLinks = { ".JUSH.": [ '".js_escape(ME).(support("table")?"table=":"select=")."\$&', /\\b(".implode("|",$ze).")\\b/g ] };\n";foreach(array("bac","bra","sqlite_quo","mssql_bra")as$X)echo"jushLinks.$X = jushLinks.".JUSH.";\n";}$ph=$f->server_info;echo'bodyLoad(\'',(is_object($f)?preg_replace('~^(\d\.?\d).*~s','\1',$ph):""),'\'',(preg_match('~MariaDB~',$ph)?", true":""),');
</script>
';}$this->databasesPrint($Ue);$oa=array();if(DB==""||!$Ue){if(support("sql")){$oa[]="<a href='".h(ME)."sql='".bold(isset($_GET["sql"])&&!isset($_GET["import"])).">".'SQL command'."</a>";$oa[]="<a href='".h(ME)."import='".bold(isset($_GET["import"])).">".'Import'."</a>";}$oa[]="<a href='".h(ME)."dump=".urlencode(isset($_GET["table"])?$_GET["table"]:$_GET["select"])."' id='dump'".bold(isset($_GET["dump"])).">".'Export'."</a>";}$Nd=$_GET["ns"]!==""&&!$Ue&&DB!="";if($Nd)$oa[]='<a href="'.h(ME).'create="'.bold($_GET["create"]==="").">".'Create table'."</a>";echo($oa?"<p class='links'>\n".implode("\n",$oa)."\n":"");if($Nd){if($S)$this->tablesPrint($S);else
echo"<p class='message'>".'No tables.'."</p>\n";}}}function
databasesPrint($Ue){global$b,$f;$i=$this->databases();if(DB&&$i&&!in_array(DB,$i))array_unshift($i,DB);echo'<form action="">
<p id="dbs">
';hidden_fields_get();$Ob=script("mixin(qsl('select'), {onmousedown: dbMouseDown, onchange: dbChange});");echo"<span title='".'Database'."'>".'DB'."</span>: ".($i?html_select("db",array(""=>"")+$i,DB).$Ob:"<input name='db' value='".h(DB)."' autocapitalize='off' size='19'>\n"),"<input type='submit' value='".'Use'."'".($i?" class='hidden'":"").">\n";if(support("scheme")){if($Ue!="db"&&DB!=""&&$f->select_db(DB)){echo"<br>".'Schema'.": ".html_select("ns",array(""=>"")+$b->schemas(),$_GET["ns"]).$Ob;if($_GET["ns"]!="")set_schema($_GET["ns"]);}}foreach(array("import","sql","schema","dump","privileges")as$X){if(isset($_GET[$X])){echo"<input type='hidden' name='$X' value=''>";break;}}echo"</p></form>\n";}function
tablesPrint($S){echo"<ul id='tables'>".script("mixin(qs('#tables'), {onmouseover: menuOver, onmouseout: menuOut});");foreach($S
as$Q=>$O){$B=$this->tableName($O);if($B!=""){echo'<li><a href="'.h(ME).'select='.urlencode($Q).'"'.bold($_GET["select"]==$Q||$_GET["edit"]==$Q,"select")." title='".'Select data'."'>".'select'."</a> ",(support("table")||support("indexes")?'<a href="'.h(ME).'table='.urlencode($Q).'"'.bold(in_array($Q,array($_GET["table"],$_GET["create"],$_GET["indexes"],$_GET["foreign"],$_GET["trigger"])),(is_view($O)?"view":"structure"))." title='".'Show structure'."'>$B</a>":"<span>$B</span>")."\n";}}echo"</ul>\n";}}$b=(function_exists('adminer_object')?adminer_object():new
Adminer);$ec=array("server"=>"MySQL")+$ec;if(!defined('Adminer\DRIVER')){define('Adminer\DRIVER',"server");if(extension_loaded("mysqli")){class
Db
extends
\MySQLi{var$extension="MySQLi";function
__construct(){parent::init();}function
connect($M="",$V="",$E="",$Mb=null,$hg=null,$zh=null){global$b;mysqli_report(MYSQLI_REPORT_OFF);list($Fd,$hg)=explode(":",$M,2);$Jh=$b->connectSsl();if($Jh)$this->ssl_set($Jh['key'],$Jh['cert'],$Jh['ca'],'','');$I=@$this->real_connect(($M!=""?$Fd:ini_get("mysqli.default_host")),($M.$V!=""?$V:ini_get("mysqli.default_user")),($M.$V.$E!=""?$E:ini_get("mysqli.default_pw")),$Mb,(is_numeric($hg)?$hg:ini_get("mysqli.default_port")),(!is_numeric($hg)?$hg:$zh),($Jh?($Jh['verify']!==false?2048:64):0));$this->options(MYSQLI_OPT_LOCAL_INFILE,false);return$I;}function
set_charset($Va){if(parent::set_charset($Va))return
true;parent::set_charset('utf8');return$this->query("SET NAMES $Va");}function
result($G,$n=0){$H=$this->query($G);if(!$H)return
false;$J=$H->fetch_array();return$J[$n];}function
quote($P){return"'".$this->escape_string($P)."'";}}}elseif(extension_loaded("mysql")&&!((ini_bool("sql.safe_mode")||ini_bool("mysql.allow_local_infile"))&&extension_loaded("pdo_mysql"))){class
Db{var$extension="MySQL",$server_info,$affected_rows,$errno,$error;private$link,$result;function
connect($M,$V,$E){if(ini_bool("mysql.allow_local_infile")){$this->error=sprintf('Disable %s or enable %s or %s extensions.',"'mysql.allow_local_infile'","MySQLi","PDO_MySQL");return
false;}$this->link=@mysql_connect(($M!=""?$M:ini_get("mysql.default_host")),("$M$V"!=""?$V:ini_get("mysql.default_user")),("$M$V$E"!=""?$E:ini_get("mysql.default_password")),true,131072);if($this->link)$this->server_info=mysql_get_server_info($this->link);else$this->error=mysql_error();return(bool)$this->link;}function
set_charset($Va){if(function_exists('mysql_set_charset')){if(mysql_set_charset($Va,$this->link))return
true;mysql_set_charset('utf8',$this->link);}return$this->query("SET NAMES $Va");}function
quote($P){return"'".mysql_real_escape_string($P,$this->link)."'";}function
select_db($Mb){return
mysql_select_db($Mb,$this->link);}function
query($G,$Ji=false){$H=@($Ji?mysql_unbuffered_query($G,$this->link):mysql_query($G,$this->link));$this->error="";if(!$H){$this->errno=mysql_errno($this->link);$this->error=mysql_error($this->link);return
false;}if($H===true){$this->affected_rows=mysql_affected_rows($this->link);$this->info=mysql_info($this->link);return
true;}return
new
Result($H);}function
multi_query($G){return$this->result=$this->query($G);}function
store_result(){return$this->result;}function
next_result(){return
false;}function
result($G,$n=0){$H=$this->query($G);return($H?$H->fetch_column($n):false);}}class
Result{var$num_rows;private$result,$offset=0;function
__construct($H){$this->result=$H;$this->num_rows=mysql_num_rows($H);}function
fetch_assoc(){return
mysql_fetch_assoc($this->result);}function
fetch_row(){return
mysql_fetch_row($this->result);}function
fetch_column($n){return($this->num_rows?mysql_result($this->result,0,$n):false);}function
fetch_field(){$I=mysql_fetch_field($this->result,$this->offset++);$I->orgtable=$I->table;$I->orgname=$I->name;$I->charsetnr=($I->blob?63:0);return$I;}function
__destruct(){mysql_free_result($this->result);}}}elseif(extension_loaded("pdo_mysql")){class
Db
extends
PdoDb{var$extension="PDO_MySQL";function
connect($M,$V,$E){global$b;$Af=array(\PDO::MYSQL_ATTR_LOCAL_INFILE=>false);$Jh=$b->connectSsl();if($Jh){if($Jh['key'])$Af[\PDO::MYSQL_ATTR_SSL_KEY]=$Jh['key'];if($Jh['cert'])$Af[\PDO::MYSQL_ATTR_SSL_CERT]=$Jh['cert'];if($Jh['ca'])$Af[\PDO::MYSQL_ATTR_SSL_CA]=$Jh['ca'];if(isset($Jh['verify']))$Af[\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT]=$Jh['verify'];}$this->dsn("mysql:charset=utf8;host=".str_replace(":",";unix_socket=",preg_replace('~:(\d)~',';port=\1',$M)),$V,$E,$Af);return
true;}function
set_charset($Va){$this->query("SET NAMES $Va");}function
select_db($Mb){return$this->query("USE ".idf_escape($Mb));}function
query($G,$Ji=false){$this->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,!$Ji);return
parent::query($G,$Ji);}}}class
Driver
extends
SqlDriver{static$lg=array("MySQLi","MySQL","PDO_MySQL");static$he="sql";var$unsigned=array("unsigned","zerofill","unsigned zerofill");var$operators=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","REGEXP","IN","FIND_IN_SET","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL");var$functions=array("char_length","date","from_unixtime","lower","round","floor","ceil","sec_to_time","time_to_sec","upper");var$grouping=array("avg","count","count distinct","group_concat","max","min","sum");function
__construct($f){parent::__construct($f);$this->types=array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),'Date and time'=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),'Strings'=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),'Lists'=>array("enum"=>65535,"set"=>64),'Binary'=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),'Geometry'=>array("geometry"=>0,"point"=>0,"linestring"=>0,"polygon"=>0,"multipoint"=>0,"multilinestring"=>0,"multipolygon"=>0,"geometrycollection"=>0),);$this->editFunctions=array(array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1","date|time"=>"now",),array(number_type()=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",));if(min_version('5.7.8',10.2,$f))$this->types['Strings']["json"]=4294967295;if(min_version('',10.7,$f)){$this->types['Strings']["uuid"]=128;$this->editFunctions[0]['uuid']='uuid';}if(min_version(9,'',$f)){$this->types['Numbers']["vector"]=16383;$this->editFunctions[0]['vector']='string_to_vector';}if(min_version(5.7,10.2,$f))$this->generated=array("STORED","VIRTUAL");}function
insert($Q,$N){return($N?parent::insert($Q,$N):queries("INSERT INTO ".table($Q)." ()\nVALUES ()"));}function
insertUpdate($Q,$K,$F){$e=array_keys(reset($K));$ng="INSERT INTO ".table($Q)." (".implode(", ",$e).") VALUES\n";$cj=array();foreach($e
as$y)$cj[$y]="$y = VALUES($y)";$Qh="\nON DUPLICATE KEY UPDATE ".implode(", ",$cj);$cj=array();$we=0;foreach($K
as$N){$Y="(".implode(", ",$N).")";if($cj&&(strlen($ng)+$we+strlen($Y)+strlen($Qh)>1e6)){if(!queries($ng.implode(",\n",$cj).$Qh))return
false;$cj=array();$we=0;}$cj[]=$Y;$we+=strlen($Y)+2;}return
queries($ng.implode(",\n",$cj).$Qh);}function
slowQuery($G,$mi){if(min_version('5.7.8','10.1.2')){if(preg_match('~MariaDB~',$this->conn->server_info))return"SET STATEMENT max_statement_time=$mi FOR $G";elseif(preg_match('~^(SELECT\b)(.+)~is',$G,$A))return"$A[1] /*+ MAX_EXECUTION_TIME(".($mi*1000).") */ $A[2]";}}function
convertSearch($v,$X,$n){return(preg_match('~char|text|enum|set~',$n["type"])&&!preg_match("~^utf8~",$n["collation"])&&preg_match('~[\x80-\xFF]~',$X['val'])?"CONVERT($v USING ".charset($this->conn).")":$v);}function
warnings(){$H=$this->conn->query("SHOW WARNINGS");if($H&&$H->num_rows){ob_start();select($H);return
ob_get_clean();}}function
tableHelp($B,$ee=false){$Ce=preg_match('~MariaDB~',$this->conn->server_info);if(information_schema(DB))return
strtolower("information-schema-".($Ce?"$B-table/":str_replace("_","-",$B)."-table.html"));if(DB=="mysql")return($Ce?"mysql$B-table/":"system-schema.html");}function
hasCStyleEscapes(){static$Ra;if($Ra===null){$Hh=$this->conn->result("SHOW VARIABLES LIKE 'sql_mode'",1);$Ra=(strpos($Hh,'NO_BACKSLASH_ESCAPES')===false);}return$Ra;}}function
idf_escape($v){return"`".str_replace("`","``",$v)."`";}function
table($v){return
idf_escape($v);}function
connect($Fb){$f=new
Db;if($f->connect($Fb[0],$Fb[1],$Fb[2])){$f->set_charset(charset($f));$f->query("SET sql_quote_show_create = 1, autocommit = 1");return$f;}$I=$f->error;if(function_exists('iconv')&&!is_utf8($I)&&strlen($Zg=iconv("windows-1250","utf-8",$I))>strlen($I))$I=$Zg;return$I;}function
get_databases($dd){$I=get_session("dbs");if($I===null){$G="SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME";$I=($dd?slow_query($G):get_vals($G));restart_session();set_session("dbs",$I);stop_session();}return$I;}function
limit($G,$Z,$z,$C=0,$lh=" "){return" $G$Z".($z!==null?$lh."LIMIT $z".($C?" OFFSET $C":""):"");}function
limit1($Q,$G,$Z,$lh="\n"){return
limit($G,$Z,1,0,$lh);}function
db_collation($j,$jb){$I=null;$h=get_val("SHOW CREATE DATABASE ".idf_escape($j),1);if(preg_match('~ COLLATE ([^ ]+)~',$h,$A))$I=$A[1];elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$h,$A))$I=$jb[$A[1]][-1];return$I;}function
engines(){$I=array();foreach(get_rows("SHOW ENGINES")as$J){if(preg_match("~YES|DEFAULT~",$J["Support"]))$I[]=$J["Engine"];}return$I;}function
logged_user(){return
get_val("SELECT USER()");}function
tables_list(){return
get_key_vals("SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME");}function
count_tables($i){$I=array();foreach($i
as$j)$I[$j]=count(get_vals("SHOW TABLES IN ".idf_escape($j)));return$I;}function
table_status($B="",$Sc=false){$I=array();foreach(get_rows($Sc?"SELECT TABLE_NAME AS Name, ENGINE AS Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ".($B!=""?"AND TABLE_NAME = ".q($B):"ORDER BY Name"):"SHOW TABLE STATUS".($B!=""?" LIKE ".q(addcslashes($B,"%_\\")):""))as$J){if($J["Engine"]=="InnoDB")$J["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\1',$J["Comment"]);if(!isset($J["Engine"]))$J["Comment"]="";if($B!=""){$J["Name"]=$B;return$J;}$I[$J["Name"]]=$J;}return$I;}function
is_view($R){return$R["Engine"]===null;}function
fk_support($R){return
preg_match('~InnoDB|IBMDB2I~i',$R["Engine"])||(preg_match('~NDB~i',$R["Engine"])&&min_version(5.6));}function
fields($Q){global$f;$Ce=preg_match('~MariaDB~',$f->server_info);$I=array();foreach(get_rows("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ".q($Q)." ORDER BY ORDINAL_POSITION")as$J){$n=$J["COLUMN_NAME"];$U=$J["COLUMN_TYPE"];$pd=$J["GENERATION_EXPRESSION"];$Pc=$J["EXTRA"];preg_match('~^(VIRTUAL|PERSISTENT|STORED)~',$Pc,$od);preg_match('~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~',$U,$A);$k=$J["COLUMN_DEFAULT"];$de=preg_match('~text~',$A[1]);if(!$Ce&&$de)$k=preg_replace("~^(_\w+)?('.*')$~",'\2',stripslashes($k));if($Ce||$de){$k=preg_replace_callback("~^'(.*)'$~",function($A){return
stripslashes(str_replace("''","'",$A[1]));},$k);}$I[$n]=array("field"=>$n,"full_type"=>$U,"type"=>$A[1],"length"=>$A[2],"unsigned"=>ltrim($A[3].$A[4]),"default"=>($od?($Ce?$pd:stripslashes($pd)):($k!=""||preg_match("~char|set~",$A[1])?$k:null)),"null"=>($J["IS_NULLABLE"]=="YES"),"auto_increment"=>($Pc=="auto_increment"),"on_update"=>(preg_match('~\bon update (\w+)~i',$Pc,$A)?$A[1]:""),"collation"=>$J["COLLATION_NAME"],"privileges"=>array_flip(explode(",","$J[PRIVILEGES],where,order")),"comment"=>$J["COLUMN_COMMENT"],"primary"=>($J["COLUMN_KEY"]=="PRI"),"generated"=>($od[1]=="PERSISTENT"?"STORED":$od[1]),);}return$I;}function
indexes($Q,$g=null){$I=array();foreach(get_rows("SHOW INDEX FROM ".table($Q),$g)as$J){$B=$J["Key_name"];$I[$B]["type"]=($B=="PRIMARY"?"PRIMARY":($J["Index_type"]=="FULLTEXT"?"FULLTEXT":($J["Non_unique"]?($J["Index_type"]=="SPATIAL"?"SPATIAL":"INDEX"):"UNIQUE")));$I[$B]["columns"][]=$J["Column_name"];$I[$B]["lengths"][]=($J["Index_type"]=="SPATIAL"?null:$J["Sub_part"]);$I[$B]["descs"][]=null;}return$I;}function
foreign_keys($Q){global$l;static$dg='(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';$I=array();$Db=get_val("SHOW CREATE TABLE ".table($Q),1);if($Db){preg_match_all("~CONSTRAINT ($dg) FOREIGN KEY ?\\(((?:$dg,? ?)+)\\) REFERENCES ($dg)(?:\\.($dg))? \\(((?:$dg,? ?)+)\\)(?: ON DELETE ($l->onActions))?(?: ON UPDATE ($l->onActions))?~",$Db,$Fe,PREG_SET_ORDER);foreach($Fe
as$A){preg_match_all("~$dg~",$A[2],$Bh);preg_match_all("~$dg~",$A[5],$ei);$I[idf_unescape($A[1])]=array("db"=>idf_unescape($A[4]!=""?$A[3]:$A[4]),"table"=>idf_unescape($A[4]!=""?$A[4]:$A[3]),"source"=>array_map('Adminer\idf_unescape',$Bh[0]),"target"=>array_map('Adminer\idf_unescape',$ei[0]),"on_delete"=>($A[6]?:"RESTRICT"),"on_update"=>($A[7]?:"RESTRICT"),);}}return$I;}function
view($B){return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU','',get_val("SHOW CREATE VIEW ".table($B),1)));}function
collations(){$I=array();foreach(get_rows("SHOW COLLATION")as$J){if($J["Default"])$I[$J["Charset"]][-1]=$J["Collation"];else$I[$J["Charset"]][]=$J["Collation"];}ksort($I);foreach($I
as$y=>$X)asort($I[$y]);return$I;}function
information_schema($j){return($j=="information_schema")||(min_version(5.5)&&$j=="performance_schema");}function
error(){global$f;return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",$f->error));}function
create_database($j,$ib){return
queries("CREATE DATABASE ".idf_escape($j).($ib?" COLLATE ".q($ib):""));}function
drop_databases($i){$I=apply_queries("DROP DATABASE",$i,'Adminer\idf_escape');restart_session();set_session("dbs",null);return$I;}function
rename_database($B,$ib){$I=false;if(create_database($B,$ib)){$S=array();$hj=array();foreach(tables_list()as$Q=>$U){if($U=='VIEW')$hj[]=$Q;else$S[]=$Q;}$I=(!$S&&!$hj)||move_tables($S,$hj,$B);drop_databases($I?array(DB):array());}return$I;}function
auto_increment(){$Ea=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$w){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$w["columns"],true)){$Ea="";break;}if($w["type"]=="PRIMARY")$Ea=" UNIQUE";}}return" AUTO_INCREMENT$Ea";}function
alter_table($Q,$B,$o,$fd,$pb,$vc,$ib,$Da,$Zf){global$f;$c=array();foreach($o
as$n){if($n[1]){$k=$n[1][3];if(preg_match('~ GENERATED~',$k)){$n[1][3]=(preg_match('~MariaDB~',$f->server_info)?"":$n[1][2]);$n[1][2]=$k;}$c[]=($Q!=""?($n[0]!=""?"CHANGE ".idf_escape($n[0]):"ADD"):" ")." ".implode($n[1]).($Q!=""?$n[2]:"");}else$c[]="DROP ".idf_escape($n[0]);}$c=array_merge($c,$fd);$O=($pb!==null?" COMMENT=".q($pb):"").($vc?" ENGINE=".q($vc):"").($ib?" COLLATE ".q($ib):"").($Da!=""?" AUTO_INCREMENT=$Da":"");if($Q=="")return
queries("CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)$O$Zf");if($Q!=$B)$c[]="RENAME TO ".table($B);if($O)$c[]=ltrim($O);return($c||$Zf?queries("ALTER TABLE ".table($Q)."\n".implode(",\n",$c).$Zf):true);}function
alter_indexes($Q,$c){foreach($c
as$y=>$X)$c[$y]=($X[2]=="DROP"?"\nDROP INDEX ".idf_escape($X[1]):"\nADD $X[0] ".($X[0]=="PRIMARY"?"KEY ":"").($X[1]!=""?idf_escape($X[1])." ":"")."(".implode(", ",$X[2]).")");return
queries("ALTER TABLE ".table($Q).implode(",",$c));}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($hj){return
queries("DROP VIEW ".implode(", ",array_map('Adminer\table',$hj)));}function
drop_tables($S){return
queries("DROP TABLE ".implode(", ",array_map('Adminer\table',$S)));}function
move_tables($S,$hj,$ei){global$f;$Lg=array();foreach($S
as$Q)$Lg[]=table($Q)." TO ".idf_escape($ei).".".table($Q);if(!$Lg||queries("RENAME TABLE ".implode(", ",$Lg))){$Ub=array();foreach($hj
as$Q)$Ub[table($Q)]=view($Q);$f->select_db($ei);$j=idf_escape(DB);foreach($Ub
as$B=>$gj){if(!queries("CREATE VIEW $B AS ".str_replace(" $j."," ",$gj["select"]))||!queries("DROP VIEW $j.$B"))return
false;}return
true;}return
false;}function
copy_tables($S,$hj,$ei){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($S
as$Q){$B=($ei==DB?table("copy_$Q"):idf_escape($ei).".".table($Q));if(($_POST["overwrite"]&&!queries("\nDROP TABLE IF EXISTS $B"))||!queries("CREATE TABLE $B LIKE ".table($Q))||!queries("INSERT INTO $B SELECT * FROM ".table($Q)))return
false;foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")))as$J){$Ci=$J["Trigger"];if(!queries("CREATE TRIGGER ".($ei==DB?idf_escape("copy_$Ci"):idf_escape($ei).".".idf_escape($Ci))." $J[Timing] $J[Event] ON $B FOR EACH ROW\n$J[Statement];"))return
false;}}foreach($hj
as$Q){$B=($ei==DB?table("copy_$Q"):idf_escape($ei).".".table($Q));$gj=view($Q);if(($_POST["overwrite"]&&!queries("DROP VIEW IF EXISTS $B"))||!queries("CREATE VIEW $B AS $gj[select]"))return
false;}return
true;}function
trigger($B){if($B=="")return
array();$K=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($B));return
reset($K);}function
triggers($Q){$I=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")))as$J)$I[$J["Trigger"]]=array($J["Timing"],$J["Event"]);return$I;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
routine($B,$U){global$l;$wa=array("bool","boolean","integer","double precision","real","dec","numeric","fixed","national char","national varchar");$Ch="(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";$Hi="((".implode("|",array_merge(array_keys($l->types()),$wa)).")\\b(?:\\s*\\(((?:[^'\")]|$l->enumLength)++)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s,]+)['\"]?)?";$dg="$Ch*(".($U=="FUNCTION"?"":$l->inout).")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$Hi";$h=get_val("SHOW CREATE $U ".idf_escape($B),2);preg_match("~\\(((?:$dg\\s*,?)*)\\)\\s*".($U=="FUNCTION"?"RETURNS\\s+$Hi\\s+":"")."(.*)~is",$h,$A);$o=array();preg_match_all("~$dg\\s*,?~is",$A[1],$Fe,PREG_SET_ORDER);foreach($Fe
as$Tf)$o[]=array("field"=>str_replace("``","`",$Tf[2]).$Tf[3],"type"=>strtolower($Tf[5]),"length"=>preg_replace_callback("~$l->enumLength~s",'Adminer\normalize_enum',$Tf[6]),"unsigned"=>strtolower(preg_replace('~\s+~',' ',trim("$Tf[8] $Tf[7]"))),"null"=>1,"full_type"=>$Tf[4],"inout"=>strtoupper($Tf[1]),"collation"=>strtolower($Tf[9]),);return
array("fields"=>$o,"comment"=>get_val("SELECT ROUTINE_COMMENT FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = DATABASE() AND ROUTINE_NAME = ".q($B)),)+($U!="FUNCTION"?array("definition"=>$A[11]):array("returns"=>array("type"=>$A[12],"length"=>$A[13],"unsigned"=>$A[15],"collation"=>$A[16]),"definition"=>$A[17],"language"=>"SQL",));}function
routines(){return
get_rows("SELECT ROUTINE_NAME AS SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = DATABASE()");}function
routine_languages(){return
array();}function
routine_id($B,$J){return
idf_escape($B);}function
last_id(){return
get_val("SELECT LAST_INSERT_ID()");}function
explain($f,$G){return$f->query("EXPLAIN ".(min_version(5.1)&&!min_version(5.7)?"PARTITIONS ":"").$G);}function
found_rows($R,$Z){return($Z||$R["Engine"]!="InnoDB"?null:$R["Rows"]);}function
create_sql($Q,$Da,$Oh){$I=get_val("SHOW CREATE TABLE ".table($Q),1);if(!$Da)$I=preg_replace('~ AUTO_INCREMENT=\d+~','',$I);return$I;}function
truncate_sql($Q){return"TRUNCATE ".table($Q);}function
use_sql($Mb){return"USE ".idf_escape($Mb);}function
trigger_sql($Q){$I="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")),null,"-- ")as$J)$I.="\nCREATE TRIGGER ".idf_escape($J["Trigger"])." $J[Timing] $J[Event] ON ".table($J["Table"])." FOR EACH ROW\n$J[Statement];;\n";return$I;}function
show_variables(){return
get_key_vals("SHOW VARIABLES");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
show_status(){return
get_key_vals("SHOW STATUS");}function
convert_field($n){if(preg_match("~binary~",$n["type"]))return"HEX(".idf_escape($n["field"]).")";if($n["type"]=="bit")return"BIN(".idf_escape($n["field"])." + 0)";if(preg_match("~geometry|point|linestring|polygon~",$n["type"]))return(min_version(8)?"ST_":"")."AsWKT(".idf_escape($n["field"]).")";}function
unconvert_field($n,$I){if(preg_match("~binary~",$n["type"]))$I="UNHEX($I)";if($n["type"]=="bit")$I="CONVERT(b$I, UNSIGNED)";if(preg_match("~geometry|point|linestring|polygon~",$n["type"])){$ng=(min_version(8)?"ST_":"");$I=$ng."GeomFromText($I, $ng"."SRID($n[field]))";}return$I;}function
support($Tc){return!preg_match("~scheme|sequence|type|view_trigger|materializedview".(min_version(8)?"":"|descidx".(min_version(5.1)?"":"|event|partitioning")).(min_version('8.0.16','10.2.1')?"":"|check")."~",$Tc);}function
kill_process($X){return
queries("KILL ".number($X));}function
connection_id(){return"SELECT CONNECTION_ID()";}function
max_connections(){return
get_val("SELECT @@max_connections");}}define('Adminer\JUSH',Driver::$he);define('Adminer\SERVER',$_GET[DRIVER]);define('Adminer\DB',$_GET["db"]);define('Adminer\ME',preg_replace('~\?.*~','',relative_uri()).'?'.(sid()?SID.'&':'').(SERVER!==null?DRIVER."=".urlencode(SERVER).'&':'').(isset($_GET["username"])?"username=".urlencode($_GET["username"]).'&':'').(DB!=""?'db='.urlencode(DB).'&'.(isset($_GET["ns"])?"ns=".urlencode($_GET["ns"])."&":""):''));if(!ob_get_level())ob_start(null,4096);function
page_header($oi,$m="",$Na=array(),$pi=""){global$ca,$ia,$b,$ec;page_headers();if(is_ajax()&&$m){page_messages($m);exit;}$qi=$oi.($pi!=""?": $pi":"");$ri=strip_tags($qi.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".$b->name());echo'<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<meta name="viewport" content="width=device-width">
<title>',$ri,'</title>
<link rel="stylesheet" type="text/css" href="',h(preg_replace("~\\?.*~","",ME)."?file=default.css&version=5.0.4"),'">
',script_src(preg_replace("~\\?.*~","",ME)."?file=functions.js&version=5.0.4");if($b->head()){echo'<link rel="shortcut icon" type="image/x-icon" href="',h(preg_replace("~\\?.*~","",ME)."?file=favicon.ico&version=5.0.4"),'">
<link rel="apple-touch-icon" href="',h(preg_replace("~\\?.*~","",ME)."?file=favicon.ico&version=5.0.4"),'">
';foreach($b->css()as$Hb){echo'<link rel="stylesheet" type="text/css" href="',h($Hb),'">
';}}echo'
<body class="ltr nojs">
';$p=get_temp_dir()."/adminer.version";if(!$_COOKIE["adminer_version"]&&function_exists('openssl_verify')&&file_exists($p)&&filemtime($p)+86400>time()){$fj=unserialize(file_get_contents($p));$wg="-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwqWOVuF5uw7/+Z70djoK
RlHIZFZPO0uYRezq90+7Amk+FDNd7KkL5eDve+vHRJBLAszF/7XKXe11xwliIsFs
DFWQlsABVZB3oisKCBEuI71J4kPH8dKGEWR9jDHFw3cWmoH3PmqImX6FISWbG3B8
h7FIx3jEaw5ckVPVTeo5JRm/1DZzJxjyDenXvBQ/6o9DgZKeNDgxwKzH+sw9/YCO
jHnq1cFpOIISzARlrHMa/43YfeNRAm/tsBXjSxembBPo7aQZLAWHmaj5+K19H10B
nCpz9Y++cipkVEiKRGih4ZEvjoFysEOdRLj6WiD/uUNky4xGeA6LaJqh5XpkFkcQ
fQIDAQAB
-----END PUBLIC KEY-----
";if(openssl_verify($fj["version"],base64_decode($fj["signature"]),$wg)==1)$_COOKIE["adminer_version"]=$fj["version"];}echo'<script',nonce(),'>
mixin(document.body, {onkeydown: bodyKeydown, onclick: bodyClick',(isset($_COOKIE["adminer_version"])?"":", onload: partial(verifyVersion, '$ia', '".js_escape(ME)."', '".get_token()."')");?>});
document.body.className = document.body.className.replace(/ nojs/, ' js');
var offlineMessage = '<?php echo
js_escape('You are offline.'),'\';
var thousandsSeparator = \'',js_escape(','),'\';
</script>

<div id="help" class="jush-',JUSH,' jsonly hidden"></div>
',script("mixin(qs('#help'), {onmouseover: function () { helpOpen = 1; }, onmouseout: helpMouseout});"),'
<div id="content">
';if($Na!==null){$_=substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($_?:".").'">'.$ec[DRIVER].'</a> » ';$_=substr(preg_replace('~\b(db|ns)=[^&]*&~','',ME),0,-1);$M=$b->serverName(SERVER);$M=($M!=""?$M:'Server');if($Na===false)echo"$M\n";else{echo"<a href='".h($_)."' accesskey='1' title='Alt+Shift+1'>$M</a> » ";if($_GET["ns"]!=""||(DB!=""&&is_array($Na)))echo'<a href="'.h($_."&db=".urlencode(DB).(support("scheme")?"&ns=":"")).'">'.h(DB).'</a> » ';if(is_array($Na)){if($_GET["ns"]!="")echo'<a href="'.h(substr(ME,0,-1)).'">'.h($_GET["ns"]).'</a> » ';foreach($Na
as$y=>$X){$Wb=(is_array($X)?$X[1]:h($X));if($Wb!="")echo"<a href='".h(ME."$y=").urlencode(is_array($X)?$X[0]:$X)."'>$Wb</a> » ";}}echo"$oi\n";}}echo"<h2>$qi</h2>\n","<div id='ajaxstatus' class='jsonly hidden'></div>\n";restart_session();page_messages($m);$i=&get_session("dbs");if(DB!=""&&$i&&!in_array(DB,$i,true))$i=null;stop_session();define('Adminer\PAGE_HEADER',1);}function
page_headers(){global$b;header("Content-Type: text/html; charset=utf-8");header("Cache-Control: no-cache");header("X-Frame-Options: deny");header("X-XSS-Protection: 0");header("X-Content-Type-Options: nosniff");header("Referrer-Policy: origin-when-cross-origin");foreach($b->csp()as$Gb){$Bd=array();foreach($Gb
as$y=>$X)$Bd[]="$y $X";header("Content-Security-Policy: ".implode("; ",$Bd));}$b->headers();}function
csp(){return
array(array("script-src"=>"'self' 'unsafe-inline' 'nonce-".get_nonce()."' 'strict-dynamic'","connect-src"=>"'self'","frame-src"=>"https://www.adminer.org","object-src"=>"'none'","base-uri"=>"'none'","form-action"=>"'self'",),);}function
get_nonce(){static$ff;if(!$ff)$ff=base64_encode(rand_string());return$ff;}function
page_messages($m){$Si=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$Se=$_SESSION["messages"][$Si];if($Se){echo"<div class='message'>".implode("</div>\n<div class='message'>",$Se)."</div>".script("messagesPrint();");unset($_SESSION["messages"][$Si]);}if($m)echo"<div class='error'>$m</div>\n";}function
page_footer($Ue=""){global$b,$T;echo'</div>

<div id="menu">
';$b->navigation($Ue);echo'</div>

';if($Ue!="auth"){echo'<form action="" method="post">
<p class="logout">
<span>',h($_GET["username"])."\n",'</span>
<input type="submit" name="logout" value="Logout" id="logout">
<input type="hidden" name="token" value="',$T,'">
</p>
</form>
';}echo
script("setupSubmitHighlight(document);");}function
int32($Xe){while($Xe>=2147483648)$Xe-=4294967296;while($Xe<=-2147483649)$Xe+=4294967296;return(int)$Xe;}function
long2str($W,$jj){$Zg='';foreach($W
as$X)$Zg.=pack('V',$X);if($jj)return
substr($Zg,0,end($W));return$Zg;}function
str2long($Zg,$jj){$W=array_values(unpack('V*',str_pad($Zg,4*ceil(strlen($Zg)/4),"\0")));if($jj)$W[]=strlen($Zg);return$W;}function
xxtea_mx($vj,$uj,$Rh,$ie){return
int32((($vj>>5&0x7FFFFFF)^$uj<<2)+(($uj>>3&0x1FFFFFFF)^$vj<<4))^int32(($Rh^$uj)+($ie^$vj));}function
encrypt_string($Mh,$y){if($Mh=="")return"";$y=array_values(unpack("V*",pack("H*",md5($y))));$W=str2long($Mh,true);$Xe=count($W)-1;$vj=$W[$Xe];$uj=$W[0];$xg=floor(6+52/($Xe+1));$Rh=0;while($xg-->0){$Rh=int32($Rh+0x9E3779B9);$mc=$Rh>>2&3;for($Rf=0;$Rf<$Xe;$Rf++){$uj=$W[$Rf+1];$We=xxtea_mx($vj,$uj,$Rh,$y[$Rf&3^$mc]);$vj=int32($W[$Rf]+$We);$W[$Rf]=$vj;}$uj=$W[0];$We=xxtea_mx($vj,$uj,$Rh,$y[$Rf&3^$mc]);$vj=int32($W[$Xe]+$We);$W[$Xe]=$vj;}return
long2str($W,false);}function
decrypt_string($Mh,$y){if($Mh=="")return"";if(!$y)return
false;$y=array_values(unpack("V*",pack("H*",md5($y))));$W=str2long($Mh,false);$Xe=count($W)-1;$vj=$W[$Xe];$uj=$W[0];$xg=floor(6+52/($Xe+1));$Rh=int32($xg*0x9E3779B9);while($Rh){$mc=$Rh>>2&3;for($Rf=$Xe;$Rf>0;$Rf--){$vj=$W[$Rf-1];$We=xxtea_mx($vj,$uj,$Rh,$y[$Rf&3^$mc]);$uj=int32($W[$Rf]-$We);$W[$Rf]=$uj;}$vj=$W[$Xe];$We=xxtea_mx($vj,$uj,$Rh,$y[$Rf&3^$mc]);$uj=int32($W[0]-$We);$W[0]=$uj;$Rh=int32($Rh-0x9E3779B9);}return
long2str($W,true);}$f='';$Ad=$_SESSION["token"];if(!$Ad)$_SESSION["token"]=rand(1,1e6);$T=get_token();$fg=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$X){list($y)=explode(":",$X);$fg[$y]=$X;}}function
add_invalid_login(){global$b;$r=file_open_lock(get_temp_dir()."/adminer.invalid");if(!$r)return;$Zd=unserialize(stream_get_contents($r));$li=time();if($Zd){foreach($Zd
as$ae=>$X){if($X[0]<$li)unset($Zd[$ae]);}}$Yd=&$Zd[$b->bruteForceKey()];if(!$Yd)$Yd=array($li+30*60,0);$Yd[1]++;file_write_unlock($r,serialize($Zd));}function
check_invalid_login(){global$b;$Zd=unserialize(@file_get_contents(get_temp_dir()."/adminer.invalid"));$Yd=($Zd?$Zd[$b->bruteForceKey()]:array());$ef=($Yd[1]>29?$Yd[0]-time():0);if($ef>0)auth_error(lang(array('Too many unsuccessful logins, try again in %d minute.','Too many unsuccessful logins, try again in %d minutes.'),ceil($ef/60)));}$Ba=$_POST["auth"];if($Ba){session_regenerate_id();$ej=$Ba["driver"];$M=$Ba["server"];$V=$Ba["username"];$E=(string)$Ba["password"];$j=$Ba["db"];set_password($ej,$M,$V,$E);$_SESSION["db"][$ej][$M][$V][$j]=true;if($Ba["permanent"]){$y=base64_encode($ej)."-".base64_encode($M)."-".base64_encode($V)."-".base64_encode($j);$rg=$b->permanentLogin(true);$fg[$y]="$y:".base64_encode($rg?encrypt_string($E,$rg):"");cookie("adminer_permanent",implode(" ",$fg));}if(count($_POST)==1||DRIVER!=$ej||SERVER!=$M||$_GET["username"]!==$V||DB!=$j)redirect(auth_url($ej,$M,$V,$j));}elseif($_POST["logout"]&&(!$Ad||verify_token())){foreach(array("pwds","db","dbs","queries")as$y)set_session($y,null);unset_permanent();redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1),'Logout successful.'.' '.'Thanks for using Adminer, consider <a href="https://www.adminer.org/en/donation/">donating</a>.');}elseif($fg&&!$_SESSION["pwds"]){session_regenerate_id();$rg=$b->permanentLogin();foreach($fg
as$y=>$X){list(,$cb)=explode(":",$X);list($ej,$M,$V,$j)=array_map('base64_decode',explode("-",$y));set_password($ej,$M,$V,decrypt_string(base64_decode($cb),$rg));$_SESSION["db"][$ej][$M][$V][$j]=true;}}function
unset_permanent(){global$fg;foreach($fg
as$y=>$X){list($ej,$M,$V,$j)=array_map('base64_decode',explode("-",$y));if($ej==DRIVER&&$M==SERVER&&$V==$_GET["username"]&&$j==DB)unset($fg[$y]);}cookie("adminer_permanent",implode(" ",$fg));}function
auth_error($m){global$b,$Ad;$rh=session_name();if(isset($_GET["username"])){header("HTTP/1.1 403 Forbidden");if(($_COOKIE[$rh]||$_GET[$rh])&&!$Ad)$m='Session expired, please login again.';else{restart_session();add_invalid_login();$E=get_password();if($E!==null){if($E===false)$m.=($m?'<br>':'').sprintf('Master password expired. <a href="https://www.adminer.org/en/extension/"%s>Implement</a> %s method to make it permanent.',target_blank(),'<code>permanentLogin()</code>');set_password(DRIVER,SERVER,$_GET["username"],null);}unset_permanent();}}if(!$_COOKIE[$rh]&&$_GET[$rh]&&ini_bool("session.use_only_cookies"))$m='Session support must be enabled.';$Uf=session_get_cookie_params();cookie("adminer_key",($_COOKIE["adminer_key"]?:rand_string()),$Uf["lifetime"]);page_header('Login',$m,null);echo"<form action='' method='post'>\n","<div>";if(hidden_fields($_POST,array("auth")))echo"<p class='message'>".'The action will be performed after successful login with the same credentials.'."\n";echo"</div>\n";$b->loginForm();echo"</form>\n";page_footer("auth");exit;}if(isset($_GET["username"])&&!class_exists('Adminer\Db')){unset($_SESSION["pwds"][DRIVER]);unset_permanent();page_header('No extension',sprintf('None of the supported PHP extensions (%s) are available.',implode(", ",Driver::$lg)),false);page_footer("auth");exit;}stop_session(true);if(isset($_GET["username"])&&is_string(get_password())){list($Fd,$hg)=explode(":",SERVER,2);if(preg_match('~^\s*([-+]?\d+)~',$hg,$A)&&($A[1]<1024||$A[1]>65535))auth_error('Connecting to privileged ports is not allowed.');check_invalid_login();$f=connect($b->credentials());if(is_object($f)){$l=new
Driver($f);if($b->operators===null)$b->operators=$l->operators;}}$Ae=null;if(!is_object($f)||($Ae=$b->login($_GET["username"],get_password()))!==true){$m=(is_string($f)?nl_br(h($f)):(is_string($Ae)?$Ae:'Invalid credentials.'));auth_error($m.(preg_match('~^ | $~',get_password())?'<br>'.'There is a space in the input password which might be the cause.':''));}if($_POST["logout"]&&$Ad&&!verify_token()){page_header('Logout','Invalid CSRF token. Send the form again.');page_footer("db");exit;}if($Ba&&$_POST["token"])$_POST["token"]=$T;$m='';if($_POST){if(!verify_token()){$Td="max_input_vars";$Le=ini_get($Td);if(extension_loaded("suhosin")){foreach(array("suhosin.request.max_vars","suhosin.post.max_vars")as$y){$X=ini_get($y);if($X&&(!$Le||$X<$Le)){$Td=$y;$Le=$X;}}}$m=(!$_POST["token"]&&$Le?sprintf('Maximum number of allowed fields exceeded. Please increase %s.',"'$Td'"):'Invalid CSRF token. Send the form again.'.' '.'If you did not send this request from Adminer then close this page.');}}elseif($_SERVER["REQUEST_METHOD"]=="POST"){$m=sprintf('Too big POST data. Reduce the data or increase the %s configuration directive.',"'post_max_size'");if(isset($_GET["sql"]))$m.=' '.'You can upload a big SQL file via FTP and import it from server.';}function
select($H,$g=null,$Gf=array(),$z=0){$ze=array();$x=array();$e=array();$La=array();$Ii=array();$I=array();for($t=0;(!$z||$t<$z)&&($J=$H->fetch_row());$t++){if(!$t){echo"<div class='scrollable'>\n","<table class='nowrap odds'>\n","<thead><tr>";for($ge=0;$ge<count($J);$ge++){$n=$H->fetch_field();$B=$n->name;$Ff=$n->orgtable;$Ef=$n->orgname;$I[$n->table]=$Ff;if($Gf&&JUSH=="sql")$ze[$ge]=($B=="table"?"table=":($B=="possible_keys"?"indexes=":null));elseif($Ff!=""){if(!isset($x[$Ff])){$x[$Ff]=array();foreach(indexes($Ff,$g)as$w){if($w["type"]=="PRIMARY"){$x[$Ff]=array_flip($w["columns"]);break;}}$e[$Ff]=$x[$Ff];}if(isset($e[$Ff][$Ef])){unset($e[$Ff][$Ef]);$x[$Ff][$Ef]=$ge;$ze[$ge]=$Ff;}}if($n->charsetnr==63)$La[$ge]=true;$Ii[$ge]=$n->type;echo"<th".($Ff!=""||$n->name!=$Ef?" title='".h(($Ff!=""?"$Ff.":"").$Ef)."'":"").">".h($B).($Gf?doc_link(array('sql'=>"explain-output.html#explain_".strtolower($B),'mariadb'=>"explain/#the-columns-in-explain-select",)):"");}echo"</thead>\n";}echo"<tr>";foreach($J
as$y=>$X){$_="";if(isset($ze[$y])&&!$e[$ze[$y]]){if($Gf&&JUSH=="sql"){$Q=$J[array_search("table=",$ze)];$_=ME.$ze[$y].urlencode($Gf[$Q]!=""?$Gf[$Q]:$Q);}else{$_=ME."edit=".urlencode($ze[$y]);foreach($x[$ze[$y]]as$gb=>$ge)$_.="&where".urlencode("[".bracket_escape($gb)."]")."=".urlencode($J[$ge]);}}elseif(is_url($X))$_=$X;if($X===null)$X="<i>NULL</i>";elseif($La[$y]&&!is_utf8($X))$X="<i>".lang(array('%d byte','%d bytes'),strlen($X))."</i>";else{$X=h($X);if($Ii[$y]==254)$X="<code>$X</code>";}if($_)$X="<a href='".h($_)."'".(is_url($_)?target_blank():'').">$X</a>";echo"<td>$X";}}echo($t?"</table>\n</div>":"<p class='message'>".'No rows.')."\n";return$I;}function
referencable_primary($jh){$I=array();foreach(table_status('',true)as$Wh=>$Q){if($Wh!=$jh&&fk_support($Q)){foreach(fields($Wh)as$n){if($n["primary"]){if($I[$Wh]){unset($I[$Wh]);break;}$I[$Wh]=$n;}}}}return$I;}function
adminer_settings(){parse_str($_COOKIE["adminer_settings"],$th);return$th;}function
adminer_setting($y){$th=adminer_settings();return$th[$y];}function
set_adminer_settings($th){return
cookie("adminer_settings",http_build_query($th+adminer_settings()));}function
textarea($B,$Y,$K=10,$lb=80){echo"<textarea name='".h($B)."' rows='$K' cols='$lb' class='sqlarea jush-".JUSH."' spellcheck='false' wrap='off'>";if(is_array($Y)){foreach($Y
as$X)echo
h($X[0])."\n\n\n";}else
echo
h($Y);echo"</textarea>";}function
select_input($Aa,$Af,$Y="",$uf="",$gg=""){$di=($Af?"select":"input");return"<$di$Aa".($Af?"><option value=''>$gg".optionlist($Af,$Y,true)."</select>":" size='10' value='".h($Y)."' placeholder='$gg'>").($uf?script("qsl('$di').onchange = $uf;",""):"");}function
json_row($y,$X=null){static$Yc=true;if($Yc)echo"{";if($y!=""){echo($Yc?"":",")."\n\t\"".addcslashes($y,"\r\n\t\"\\/").'": '.($X!==null?'"'.addcslashes($X,"\r\n\"\\/").'"':'null');$Yc=false;}else{echo"\n}\n";$Yc=true;}}function
edit_type($y,$n,$jb,$hd=array(),$Qc=array()){global$l;$U=$n["type"];echo'<td><select name="',h($y),'[type]" class="type" aria-labelledby="label-type">';if($U&&!array_key_exists($U,$l->types())&&!isset($hd[$U])&&!in_array($U,$Qc))$Qc[]=$U;$Nh=$l->structuredTypes();if($hd)$Nh['Foreign keys']=$hd;echo
optionlist(array_merge($Qc,$Nh),$U),'</select><td><input
	name="',h($y),'[length]"
	value="',h($n["length"]),'"
	size="3"
	',(!$n["length"]&&preg_match('~var(char|binary)$~',$U)?" class='required'":"");echo'	aria-labelledby="label-length"><td class="options">',($jb?"<input list='collations' name='".h($y)."[collation]'".(preg_match('~(char|text|enum|set)$~',$U)?"":" class='hidden'")." value='".h($n["collation"])."' placeholder='(".'collation'.")'>":''),($l->unsigned?"<select name='".h($y)."[unsigned]'".(!$U||preg_match(number_type(),$U)?"":" class='hidden'").'><option>'.optionlist($l->unsigned,$n["unsigned"]).'</select>':''),(isset($n['on_update'])?"<select name='".h($y)."[on_update]'".(preg_match('~timestamp|datetime~',$U)?"":" class='hidden'").'>'.optionlist(array(""=>"(".'ON UPDATE'.")","CURRENT_TIMESTAMP"),(preg_match('~^CURRENT_TIMESTAMP~i',$n["on_update"])?"CURRENT_TIMESTAMP":$n["on_update"])).'</select>':''),($hd?"<select name='".h($y)."[on_delete]'".(preg_match("~`~",$U)?"":" class='hidden'")."><option value=''>(".'ON DELETE'.")".optionlist(explode("|",$l->onActions),$n["on_delete"])."</select> ":" ");}function
get_partitions_info($Q){global$f;$ld="FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = ".q(DB)." AND TABLE_NAME = ".q($Q);$H=$f->query("SELECT PARTITION_METHOD, PARTITION_EXPRESSION, PARTITION_ORDINAL_POSITION $ld ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");$I=array();list($I["partition_by"],$I["partition"],$I["partitions"])=$H->fetch_row();$ag=get_key_vals("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $ld AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION");$I["partition_names"]=array_keys($ag);$I["partition_values"]=array_values($ag);return$I;}function
process_length($we){global$l;$zc=$l->enumLength;return(preg_match("~^\\s*\\(?\\s*$zc(?:\\s*,\\s*$zc)*+\\s*\\)?\\s*\$~",$we)&&preg_match_all("~$zc~",$we,$Fe)?"(".implode(",",$Fe[0]).")":preg_replace('~^[0-9].*~','(\0)',preg_replace('~[^-0-9,+()[\]]~','',$we)));}function
process_type($n,$hb="COLLATE"){global$l;return" $n[type]".process_length($n["length"]).(preg_match(number_type(),$n["type"])&&in_array($n["unsigned"],$l->unsigned)?" $n[unsigned]":"").(preg_match('~char|text|enum|set~',$n["type"])&&$n["collation"]?" $hb ".(JUSH=="mssql"?$n["collation"]:q($n["collation"])):"");}function
process_field($n,$Gi){if($n["on_update"])$n["on_update"]=str_ireplace("current_timestamp()","CURRENT_TIMESTAMP",$n["on_update"]);return
array(idf_escape(trim($n["field"])),process_type($Gi),($n["null"]?" NULL":" NOT NULL"),default_value($n),(preg_match('~timestamp|datetime~',$n["type"])&&$n["on_update"]?" ON UPDATE $n[on_update]":""),(support("comment")&&$n["comment"]!=""?" COMMENT ".q($n["comment"]):""),($n["auto_increment"]?auto_increment():null),);}function
default_value($n){global$l;$k=$n["default"];$od=$n["generated"];return($k===null?"":(in_array($od,$l->generated)?(JUSH=="mssql"?" AS ($k)".($od=="VIRTUAL"?"":" $od")."":" GENERATED ALWAYS AS ($k) $od"):" DEFAULT ".(!preg_match('~^GENERATED ~i',$k)&&(preg_match('~char|binary|text|enum|set~',$n["type"])||preg_match('~^(?![a-z])~i',$k))?(JUSH=="sql"&&preg_match('~text~',$n["type"])?"(".q($k).")":q($k)):str_ireplace("current_timestamp()","CURRENT_TIMESTAMP",(JUSH=="sqlite"?"($k)":$k)))));}function
type_class($U){foreach(array('char'=>'text','date'=>'time|year','binary'=>'blob','enum'=>'set',)as$y=>$X){if(preg_match("~$y|$X~",$U))return" class='$y'";}}function
edit_fields($o,$jb,$U="TABLE",$hd=array()){global$l;$o=array_values($o);$Sb=(($_POST?$_POST["defaults"]:adminer_setting("defaults"))?"":" class='hidden'");$qb=(($_POST?$_POST["comments"]:adminer_setting("comments"))?"":" class='hidden'");echo'<thead><tr>
',($U=="PROCEDURE"?"<td>":""),'<th id="label-name">',($U=="TABLE"?'Column name':'Parameter name'),'<td id="label-type">Type<textarea id="enum-edit" rows="4" cols="12" wrap="off" style="display: none;"></textarea>',script("qs('#enum-edit').onblur = editingLengthBlur;"),'<td id="label-length">Length
<td>','Options';if($U=="TABLE"){echo'<td id="label-null">NULL
<td><input type="radio" name="auto_increment_col" value=""><abbr id="label-ai" title="Auto Increment">AI</abbr>',doc_link(array('sql'=>"example-auto-increment.html",'mariadb'=>"auto_increment/",'sqlite'=>"autoinc.html",'pgsql'=>"datatype-numeric.html#DATATYPE-SERIAL",'mssql'=>"t-sql/statements/create-table-transact-sql-identity-property",)),'<td id="label-default"',$Sb,'>Default value
',(support("comment")?"<td id='label-comment'$qb>".'Comment':"");}echo'<td>',"<input type='image' class='icon' name='add[".(support("move_col")?0:count($o))."]' src='".h(preg_replace("~\\?.*~","",ME)."?file=plus.gif&version=5.0.4")."' alt='+' title='".'Add next'."'>".script("row_count = ".count($o).";"),'</thead>
<tbody>
',script("mixin(qsl('tbody'), {onclick: editingClick, onkeydown: editingKeydown, oninput: editingInput});");foreach($o
as$t=>$n){$t++;$Hf=$n[($_POST?"orig":"field")];$bc=(isset($_POST["add"][$t-1])||(isset($n["field"])&&!$_POST["drop_col"][$t]))&&(support("drop_col")||$Hf=="");echo'<tr',($bc?"":" style='display: none;'"),'>
',($U=="PROCEDURE"?"<td>".html_select("fields[$t][inout]",explode("|",$l->inout),$n["inout"]):"")."<th>";if($bc){echo'<input name="fields[',$t,'][field]" value="',h($n["field"]),'" data-maxlength="64" autocapitalize="off" aria-labelledby="label-name">
';}echo'<input type="hidden" name="fields[',$t,'][orig]" value="',h($Hf),'">';edit_type("fields[$t]",$n,$jb,$hd);if($U=="TABLE"){echo'<td>',checkbox("fields[$t][null]",1,$n["null"],"","","block","label-null"),'<td><label class="block"><input type="radio" name="auto_increment_col" value="',$t,'"',($n["auto_increment"]?" checked":""),' aria-labelledby="label-ai"></label><td',$Sb,'>',($l->generated?html_select("fields[$t][generated]",array_merge(array("","DEFAULT"),$l->generated),$n["generated"])." ":checkbox("fields[$t][generated]",1,$n["generated"],"","","","label-default")),'<input name="fields[',$t,'][default]" value="',h($n["default"]),'" aria-labelledby="label-default">',(support("comment")?"<td$qb><input name='fields[$t][comment]' value='".h($n["comment"])."' data-maxlength='".(min_version(5.5)?1024:255)."' aria-labelledby='label-comment'>":"");}echo"<td>",(support("move_col")?"<input type='image' class='icon' name='add[$t]' src='".h(preg_replace("~\\?.*~","",ME)."?file=plus.gif&version=5.0.4")."' alt='+' title='".'Add next'."'> "."<input type='image' class='icon' name='up[$t]' src='".h(preg_replace("~\\?.*~","",ME)."?file=up.gif&version=5.0.4")."' alt='↑' title='".'Move up'."'> "."<input type='image' class='icon' name='down[$t]' src='".h(preg_replace("~\\?.*~","",ME)."?file=down.gif&version=5.0.4")."' alt='↓' title='".'Move down'."'> ":""),($Hf==""||support("drop_col")?"<input type='image' class='icon' name='drop_col[$t]' src='".h(preg_replace("~\\?.*~","",ME)."?file=cross.gif&version=5.0.4")."' alt='x' title='".'Remove'."'>":"");}}function
process_fields(&$o){$C=0;if($_POST["up"]){$qe=0;foreach($o
as$y=>$n){if(key($_POST["up"])==$y){unset($o[$y]);array_splice($o,$qe,0,array($n));break;}if(isset($n["field"]))$qe=$C;$C++;}}elseif($_POST["down"]){$jd=false;foreach($o
as$y=>$n){if(isset($n["field"])&&$jd){unset($o[key($_POST["down"])]);array_splice($o,$C,0,array($jd));break;}if(key($_POST["down"])==$y)$jd=$n;$C++;}}elseif($_POST["add"]){$o=array_values($o);array_splice($o,key($_POST["add"]),0,array(array()));}elseif(!$_POST["drop_col"])return
false;return
true;}function
normalize_enum($A){return"'".str_replace("'","''",addcslashes(stripcslashes(str_replace($A[0][0].$A[0][0],$A[0][0],substr($A[0],1,-1))),'\\'))."'";}function
grant($qd,$tg,$e,$rf){if(!$tg)return
true;if($tg==array("ALL PRIVILEGES","GRANT OPTION"))return($qd=="GRANT"?queries("$qd ALL PRIVILEGES$rf WITH GRANT OPTION"):queries("$qd ALL PRIVILEGES$rf")&&queries("$qd GRANT OPTION$rf"));return
queries("$qd ".preg_replace('~(GRANT OPTION)\([^)]*\)~','\1',implode("$e, ",$tg).$e).$rf);}function
drop_create($fc,$h,$hc,$hi,$jc,$_e,$Re,$Pe,$Qe,$of,$cf){if($_POST["drop"])query_redirect($fc,$_e,$Re);elseif($of=="")query_redirect($h,$_e,$Qe);elseif($of!=$cf){$Eb=queries($h);queries_redirect($_e,$Pe,$Eb&&queries($fc));if($Eb)queries($hc);}else
queries_redirect($_e,$Pe,queries($hi)&&queries($jc)&&queries($fc)&&queries($h));}function
create_trigger($rf,$J){$ni=" $J[Timing] $J[Event]".(preg_match('~ OF~',$J["Event"])?" $J[Of]":"");return"CREATE TRIGGER ".idf_escape($J["Trigger"]).(JUSH=="mssql"?$rf.$ni:$ni.$rf).rtrim(" $J[Type]\n$J[Statement]",";").";";}function
create_routine($Vg,$J){global$l;$N=array();$o=(array)$J["fields"];ksort($o);foreach($o
as$n){if($n["field"]!="")$N[]=(preg_match("~^($l->inout)\$~",$n["inout"])?"$n[inout] ":"").idf_escape($n["field"]).process_type($n,"CHARACTER SET");}$Tb=rtrim($J["definition"],";");return"CREATE $Vg ".idf_escape(trim($J["name"]))." (".implode(", ",$N).")".($Vg=="FUNCTION"?" RETURNS".process_type($J["returns"],"CHARACTER SET"):"").($J["language"]?" LANGUAGE $J[language]":"").(JUSH=="pgsql"?" AS ".q($Tb):"\n$Tb;");}function
remove_definer($G){return
preg_replace('~^([A-Z =]+) DEFINER=`'.preg_replace('~@(.*)~','`@`(%|\1)',logged_user()).'`~','\1',$G);}function
format_foreign_key($q){global$l;$j=$q["db"];$gf=$q["ns"];return" FOREIGN KEY (".implode(", ",array_map('Adminer\idf_escape',$q["source"])).") REFERENCES ".($j!=""&&$j!=$_GET["db"]?idf_escape($j).".":"").($gf!=""&&$gf!=$_GET["ns"]?idf_escape($gf).".":"").idf_escape($q["table"])." (".implode(", ",array_map('Adminer\idf_escape',$q["target"])).")".(preg_match("~^($l->onActions)\$~",$q["on_delete"])?" ON DELETE $q[on_delete]":"").(preg_match("~^($l->onActions)\$~",$q["on_update"])?" ON UPDATE $q[on_update]":"");}function
tar_file($p,$si){$I=pack("a100a8a8a8a12a12",$p,644,0,0,decoct($si->size),decoct(time()));$bb=8*32;for($t=0;$t<strlen($I);$t++)$bb+=ord($I[$t]);$I.=sprintf("%06o",$bb)."\0 ";echo$I,str_repeat("\0",512-strlen($I));$si->send();echo
str_repeat("\0",511-($si->size+511)%512);}function
ini_bytes($Td){$X=ini_get($Td);switch(strtolower(substr($X,-1))){case'g':$X=(int)$X*1024;case'm':$X=(int)$X*1024;case'k':$X=(int)$X*1024;}return$X;}function
doc_link($cg,$ii="<sup>?</sup>"){global$f;$ph=$f->server_info;$fj=preg_replace('~^(\d\.?\d).*~s','\1',$ph);$Ui=array('sql'=>"https://dev.mysql.com/doc/refman/$fj/en/",'sqlite'=>"https://www.sqlite.org/",'pgsql'=>"https://www.postgresql.org/docs/$fj/",'mssql'=>"https://learn.microsoft.com/en-us/sql/",'oracle'=>"https://www.oracle.com/pls/topic/lookup?ctx=db".preg_replace('~^.* (\d+)\.(\d+)\.\d+\.\d+\.\d+.*~s','\1\2',$ph)."&id=",);if(preg_match('~MariaDB~',$ph)){$Ui['sql']="https://mariadb.com/kb/en/";$cg['sql']=(isset($cg['mariadb'])?$cg['mariadb']:str_replace(".html","/",$cg['sql']));}return($cg[JUSH]?"<a href='".h($Ui[JUSH].$cg[JUSH].(JUSH=='mssql'?"?view=sql-server-ver$fj":""))."'".target_blank().">$ii</a>":"");}function
db_size($j){global$f;if(!$f->select_db($j))return"?";$I=0;foreach(table_status()as$R)$I+=$R["Data_length"]+$R["Index_length"];return
format_number($I);}function
set_utf8mb4($h){global$f;static$N=false;if(!$N&&preg_match('~\butf8mb4~i',$h)){$N=true;echo"SET NAMES ".charset($f).";\n\n";}}if(isset($_GET["status"]))$_GET["variables"]=$_GET["status"];if(isset($_GET["import"]))$_GET["sql"]=$_GET["import"];if(!(DB!=""?$f->select_db(DB):isset($_GET["sql"])||isset($_GET["dump"])||isset($_GET["database"])||isset($_GET["processlist"])||isset($_GET["privileges"])||isset($_GET["user"])||isset($_GET["variables"])||$_GET["script"]=="connect"||$_GET["script"]=="kill")){if(DB!=""||$_GET["refresh"]){restart_session();set_session("dbs",null);}if(DB!=""){header("HTTP/1.1 404 Not Found");page_header('Database'.": ".h(DB),'Invalid database.',true);}else{if($_POST["db"]&&!$m)queries_redirect(substr(ME,0,-1),'Databases have been dropped.',drop_databases($_POST["db"]));page_header('Select database',$m,false);echo"<p class='links'>\n";foreach(array('database'=>'Create database','privileges'=>'Privileges','processlist'=>'Process list','variables'=>'Variables','status'=>'Status',)as$y=>$X){if(support($y))echo"<a href='".h(ME)."$y='>$X</a>\n";}echo"<p>".sprintf('%s version: %s through PHP extension %s',$ec[DRIVER],"<b>".h($f->server_info)."</b>","<b>$f->extension</b>")."\n","<p>".sprintf('Logged as: %s',"<b>".h(logged_user())."</b>")."\n";$i=$b->databases();if($i){$dh=support("scheme");$jb=collations();echo"<form action='' method='post'>\n","<table class='checkable odds'>\n",script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"),"<thead><tr>".(support("database")?"<td>":"")."<th>".'Database'.(get_session("dbs")!==null?" - <a href='".h(ME)."refresh=1'>".'Refresh'."</a>":"")."<td>".'Collation'."<td>".'Tables'."<td>".'Size'." - <a href='".h(ME)."dbsize=1'>".'Compute'."</a>".script("qsl('a').onclick = partial(ajaxSetHtml, '".js_escape(ME)."script=connect');","")."</thead>\n";$i=($_GET["dbsize"]?count_tables($i):array_flip($i));foreach($i
as$j=>$S){$Ug=h(ME)."db=".urlencode($j);$u=h("Db-".$j);echo"<tr>".(support("database")?"<td>".checkbox("db[]",$j,in_array($j,(array)$_POST["db"]),"","","",$u):""),"<th><a href='$Ug' id='$u'>".h($j)."</a>";$ib=h(db_collation($j,$jb));echo"<td>".(support("database")?"<a href='$Ug".($dh?"&amp;ns=":"")."&amp;database=' title='".'Alter database'."'>$ib</a>":$ib),"<td align='right'><a href='$Ug&amp;schema=' id='tables-".h($j)."' title='".'Database schema'."'>".($_GET["dbsize"]?$S:"?")."</a>","<td align='right' id='size-".h($j)."'>".($_GET["dbsize"]?db_size($j):"?"),"\n";}echo"</table>\n",(support("database")?"<div class='footer'><div>\n"."<fieldset><legend>".'Selected'." <span id='selected'></span></legend><div>\n"."<input type='hidden' name='all' value=''>".script("qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^db/)); };")."<input type='submit' name='drop' value='".'Drop'."'>".confirm()."\n"."</div></fieldset>\n"."</div></div>\n":""),"<input type='hidden' name='token' value='$T'>\n","</form>\n",script("tableCheck();");}}page_footer("db");exit;}if(support("scheme")){if(DB!=""&&$_GET["ns"]!==""){if(!isset($_GET["ns"]))redirect(preg_replace('~ns=[^&]*&~','',ME)."ns=".get_schema());if(!set_schema($_GET["ns"])){header("HTTP/1.1 404 Not Found");page_header('Schema'.": ".h($_GET["ns"]),'Invalid schema.',true);page_footer("ns");exit;}}}class
TmpFile{private$handler,$size;function
__construct(){$this->handler=tmpfile();}function
write($yb){$this->size+=strlen($yb);fwrite($this->handler,$yb);}function
send(){fseek($this->handler,0);fpassthru($this->handler);fclose($this->handler);}}if(isset($_GET["select"])&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])$_GET["edit"]=$_GET["select"];if(isset($_GET["callf"]))$_GET["call"]=$_GET["callf"];if(isset($_GET["function"]))$_GET["procedure"]=$_GET["function"];if(isset($_GET["download"])){$a=$_GET["download"];$o=fields($a);header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$_GET["where"])).".".friendly_url($_GET["field"]));$L=array(idf_escape($_GET["field"]));$H=$l->select($a,$L,array(where($_GET,$o)),$L);$J=($H?$H->fetch_row():array());echo$l->value($J[0],$o[$_GET["field"]]);exit;}elseif(isset($_GET["table"])){$a=$_GET["table"];$o=fields($a);if(!$o)$m=error();$R=table_status1($a,true);$B=$b->tableName($R);page_header(($o&&is_view($R)?$R['Engine']=='materialized view'?'Materialized view':'View':'Table').": ".($B!=""?$B:h($a)),$m);$Tg=array();foreach($o
as$y=>$n)$Tg+=$n["privileges"];$b->selectLinks($R,(isset($Tg["insert"])||!support("table")?"":null));$pb=$R["Comment"];if($pb!="")echo"<p class='nowrap'>".'Comment'.": ".h($pb)."\n";if($o)$b->tableStructurePrint($o);if(support("indexes")&&$l->supportsIndex($R)){echo"<h3 id='indexes'>".'Indexes'."</h3>\n";$x=indexes($a);if($x)$b->tableIndexesPrint($x);echo'<p class="links"><a href="'.h(ME).'indexes='.urlencode($a).'">'.'Alter indexes'."</a>\n";}if(!is_view($R)){if(fk_support($R)){echo"<h3 id='foreign-keys'>".'Foreign keys'."</h3>\n";$hd=foreign_keys($a);if($hd){echo"<table>\n","<thead><tr><th>".'Source'."<td>".'Target'."<td>".'ON DELETE'."<td>".'ON UPDATE'."<td></thead>\n";foreach($hd
as$B=>$q){echo"<tr title='".h($B)."'>","<th><i>".implode("</i>, <i>",array_map('Adminer\h',$q["source"]))."</i>","<td><a href='".h($q["db"]!=""?preg_replace('~db=[^&]*~',"db=".urlencode($q["db"]),ME):($q["ns"]!=""?preg_replace('~ns=[^&]*~',"ns=".urlencode($q["ns"]),ME):ME))."table=".urlencode($q["table"])."'>".($q["db"]!=""&&$q["db"]!=DB?"<b>".h($q["db"])."</b>.":"").($q["ns"]!=""&&$q["ns"]!=$_GET["ns"]?"<b>".h($q["ns"])."</b>.":"").h($q["table"])."</a>","(<i>".implode("</i>, <i>",array_map('Adminer\h',$q["target"]))."</i>)","<td>".h($q["on_delete"]),"<td>".h($q["on_update"]),'<td><a href="'.h(ME.'foreign='.urlencode($a).'&name='.urlencode($B)).'">'.'Alter'.'</a>',"\n";}echo"</table>\n";}echo'<p class="links"><a href="'.h(ME).'foreign='.urlencode($a).'">'.'Add foreign key'."</a>\n";}if(support("check")){echo"<h3 id='checks'>".'Checks'."</h3>\n";$Xa=$l->checkConstraints($a);if($Xa){echo"<table>\n";foreach($Xa
as$y=>$X){echo"<tr title='".h($y)."'>","<td><code class='jush-".JUSH."'>".h($X),"<td><a href='".h(ME.'check='.urlencode($a).'&name='.urlencode($y))."'>".'Alter'."</a>","\n";}echo"</table>\n";}echo'<p class="links"><a href="'.h(ME).'check='.urlencode($a).'">'.'Create check'."</a>\n";}}if(support(is_view($R)?"view_trigger":"trigger")){echo"<h3 id='triggers'>".'Triggers'."</h3>\n";$Fi=triggers($a);if($Fi){echo"<table>\n";foreach($Fi
as$y=>$X)echo"<tr valign='top'><td>".h($X[0])."<td>".h($X[1])."<th>".h($y)."<td><a href='".h(ME.'trigger='.urlencode($a).'&name='.urlencode($y))."'>".'Alter'."</a>\n";echo"</table>\n";}echo'<p class="links"><a href="'.h(ME).'trigger='.urlencode($a).'">'.'Add trigger'."</a>\n";}}elseif(isset($_GET["schema"])){page_header('Database schema',"",array(),h(DB.($_GET["ns"]?".$_GET[ns]":"")));$Yh=array();$Zh=array();$ea=($_GET["schema"]?:$_COOKIE["adminer_schema-".str_replace(".","_",DB)]);preg_match_all('~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~',$ea,$Fe,PREG_SET_ORDER);foreach($Fe
as$t=>$A){$Yh[$A[1]]=array($A[2],$A[3]);$Zh[]="\n\t'".js_escape($A[1])."': [ $A[2], $A[3] ]";}$vi=0;$Ia=-1;$bh=array();$Gg=array();$ue=array();foreach(table_status('',true)as$Q=>$R){if(is_view($R))continue;$ig=0;$bh[$Q]["fields"]=array();foreach(fields($Q)as$B=>$n){$ig+=1.25;$n["pos"]=$ig;$bh[$Q]["fields"][$B]=$n;}$bh[$Q]["pos"]=($Yh[$Q]?:array($vi,0));foreach($b->foreignKeys($Q)as$X){if(!$X["db"]){$se=$Ia;if($Yh[$Q][1]||$Yh[$X["table"]][1])$se=min(floatval($Yh[$Q][1]),floatval($Yh[$X["table"]][1]))-1;else$Ia-=.1;while($ue[(string)$se])$se-=.0001;$bh[$Q]["references"][$X["table"]][(string)$se]=array($X["source"],$X["target"]);$Gg[$X["table"]][$Q][(string)$se]=$X["target"];$ue[(string)$se]=true;}}$vi=max($vi,$bh[$Q]["pos"][0]+2.5+$ig);}echo'<div id="schema" style="height: ',$vi,'em;">
<script',nonce(),'>
qs(\'#schema\').onselectstart = function () { return false; };
var tablePos = {',implode(",",$Zh)."\n",'};
var em = qs(\'#schema\').offsetHeight / ',$vi,';
document.onmousemove = schemaMousemove;
document.onmouseup = partialArg(schemaMouseup, \'',js_escape(DB),'\');
</script>
';foreach($bh
as$B=>$Q){echo"<div class='table' style='top: ".$Q["pos"][0]."em; left: ".$Q["pos"][1]."em;'>",'<a href="'.h(ME).'table='.urlencode($B).'"><b>'.h($B)."</b></a>",script("qsl('div').onmousedown = schemaMousedown;");foreach($Q["fields"]as$n){$X='<span'.type_class($n["type"]).' title="'.h($n["full_type"].($n["null"]?" NULL":'')).'">'.h($n["field"]).'</span>';echo"<br>".($n["primary"]?"<i>$X</i>":$X);}foreach((array)$Q["references"]as$fi=>$Hg){foreach($Hg
as$se=>$Dg){$te=$se-$Yh[$B][1];$t=0;foreach($Dg[0]as$Bh)echo"\n<div class='references' title='".h($fi)."' id='refs$se-".($t++)."' style='left: $te"."em; top: ".$Q["fields"][$Bh]["pos"]."em; padding-top: .5em;'><div style='border-top: 1px solid Gray; width: ".(-$te)."em;'></div></div>";}}foreach((array)$Gg[$B]as$fi=>$Hg){foreach($Hg
as$se=>$e){$te=$se-$Yh[$B][1];$t=0;foreach($e
as$ei)echo"\n<div class='references' title='".h($fi)."' id='refd$se-".($t++)."' style='left: $te"."em; top: ".$Q["fields"][$ei]["pos"]."em; height: 1.25em; background: url(".h(preg_replace("~\\?.*~","",ME)."?file=arrow.gif) no-repeat right center;&version=5.0.4")."'>"."<div style='height: .5em; border-bottom: 1px solid Gray; width: ".(-$te)."em;'></div>"."</div>";}}echo"\n</div>\n";}foreach($bh
as$B=>$Q){foreach((array)$Q["references"]as$fi=>$Hg){foreach($Hg
as$se=>$Dg){$Te=$vi;$Je=-10;foreach($Dg[0]as$y=>$Bh){$jg=$Q["pos"][0]+$Q["fields"][$Bh]["pos"];$kg=$bh[$fi]["pos"][0]+$bh[$fi]["fields"][$Dg[1][$y]]["pos"];$Te=min($Te,$jg,$kg);$Je=max($Je,$jg,$kg);}echo"<div class='references' id='refl$se' style='left: $se"."em; top: $Te"."em; padding: .5em 0;'><div style='border-right: 1px solid Gray; margin-top: 1px; height: ".($Je-$Te)."em;'></div></div>\n";}}}echo'</div>
<p class="links"><a href="',h(ME."schema=".urlencode($ea)),'" id="schema-link">Permanent link</a>
';}elseif(isset($_GET["dump"])){$a=$_GET["dump"];if($_POST&&!$m){$Ab="";foreach(array("output","format","db_style","types","routines","events","table_style","auto_increment","triggers","data_style")as$y)$Ab.="&$y=".urlencode($_POST[$y]);cookie("adminer_export",substr($Ab,1));$S=array_flip((array)$_POST["tables"])+array_flip((array)$_POST["data"]);$Mc=dump_headers((count($S)==1?key($S):DB),(DB==""||count($S)>1));$ce=preg_match('~sql~',$_POST["format"]);if($ce){echo"-- Adminer $ia ".$ec[DRIVER]." ".str_replace("\n"," ",$f->server_info)." dump\n\n";if(JUSH=="sql"){echo"SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
".($_POST["data_style"]?"SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
":"")."
";$f->query("SET time_zone = '+00:00'");$f->query("SET sql_mode = ''");}}$Oh=$_POST["db_style"];$i=array(DB);if(DB==""){$i=$_POST["databases"];if(is_string($i))$i=explode("\n",rtrim(str_replace("\r","",$i),"\n"));}foreach((array)$i
as$j){$b->dumpDatabase($j);if($f->select_db($j)){if($ce&&preg_match('~CREATE~',$Oh)&&($h=get_val("SHOW CREATE DATABASE ".idf_escape($j),1))){set_utf8mb4($h);if($Oh=="DROP+CREATE")echo"DROP DATABASE IF EXISTS ".idf_escape($j).";\n";echo"$h;\n";}if($ce){if($Oh)echo
use_sql($j).";\n\n";$Of="";if($_POST["types"]){foreach(types()as$u=>$U){$_c=type_values($u);if($_c)$Of.=($Oh!='DROP+CREATE'?"DROP TYPE IF EXISTS ".idf_escape($U).";;\n":"")."CREATE TYPE ".idf_escape($U)." AS ENUM ($_c);\n\n";else$Of.="-- Could not export type $U\n\n";}}if($_POST["routines"]){foreach(routines()as$J){$B=$J["ROUTINE_NAME"];$Vg=$J["ROUTINE_TYPE"];$h=create_routine($Vg,array("name"=>$B)+routine($J["SPECIFIC_NAME"],$Vg));set_utf8mb4($h);$Of.=($Oh!='DROP+CREATE'?"DROP $Vg IF EXISTS ".idf_escape($B).";;\n":"")."$h;\n\n";}}if($_POST["events"]){foreach(get_rows("SHOW EVENTS",null,"-- ")as$J){$h=remove_definer(get_val("SHOW CREATE EVENT ".idf_escape($J["Name"]),3));set_utf8mb4($h);$Of.=($Oh!='DROP+CREATE'?"DROP EVENT IF EXISTS ".idf_escape($J["Name"]).";;\n":"")."$h;;\n\n";}}echo($Of&&JUSH=='sql'?"DELIMITER ;;\n\n$Of"."DELIMITER ;\n\n":$Of);}if($_POST["table_style"]||$_POST["data_style"]){$hj=array();foreach(table_status('',true)as$B=>$R){$Q=(DB==""||in_array($B,(array)$_POST["tables"]));$Kb=(DB==""||in_array($B,(array)$_POST["data"]));if($Q||$Kb){if($Mc=="tar"){$si=new
TmpFile;ob_start(array($si,'write'),1e5);}$b->dumpTable($B,($Q?$_POST["table_style"]:""),(is_view($R)?2:0));if(is_view($R))$hj[]=$B;elseif($Kb){$o=fields($B);$b->dumpData($B,$_POST["data_style"],"SELECT *".convert_fields($o,$o)." FROM ".table($B));}if($ce&&$_POST["triggers"]&&$Q&&($Fi=trigger_sql($B)))echo"\nDELIMITER ;;\n$Fi\nDELIMITER ;\n";if($Mc=="tar"){ob_end_flush();tar_file((DB!=""?"":"$j/")."$B.csv",$si);}elseif($ce)echo"\n";}}if(function_exists('Adminer\foreign_keys_sql')){foreach(table_status('',true)as$B=>$R){$Q=(DB==""||in_array($B,(array)$_POST["tables"]));if($Q&&!is_view($R))echo
foreign_keys_sql($B);}}foreach($hj
as$gj)$b->dumpTable($gj,$_POST["table_style"],1);if($Mc=="tar")echo
pack("x512");}}}$b->dumpFooter();exit;}page_header('Export',$m,($_GET["export"]!=""?array("table"=>$_GET["export"]):array()),h(DB));echo'
<form action="" method="post">
<table class="layout">
';$Pb=array('','USE','DROP+CREATE','CREATE');$ai=array('','DROP+CREATE','CREATE');$Lb=array('','TRUNCATE+INSERT','INSERT');if(JUSH=="sql")$Lb[]='INSERT+UPDATE';parse_str($_COOKIE["adminer_export"],$J);if(!$J)$J=array("output"=>"text","format"=>"sql","db_style"=>(DB!=""?"":"CREATE"),"table_style"=>"DROP+CREATE","data_style"=>"INSERT");if(!isset($J["events"])){$J["routines"]=$J["events"]=($_GET["dump"]=="");$J["triggers"]=$J["table_style"];}echo"<tr><th>".'Output'."<td>".html_radios("output",$b->dumpOutput(),$J["output"])."\n","<tr><th>".'Format'."<td>".html_radios("format",$b->dumpFormat(),$J["format"])."\n",(JUSH=="sqlite"?"":"<tr><th>".'Database'."<td>".html_select('db_style',$Pb,$J["db_style"]).(support("type")?checkbox("types",1,$J["types"],'User types'):"").(support("routine")?checkbox("routines",1,$J["routines"],'Routines'):"").(support("event")?checkbox("events",1,$J["events"],'Events'):"")),"<tr><th>".'Tables'."<td>".html_select('table_style',$ai,$J["table_style"]).checkbox("auto_increment",1,$J["auto_increment"],'Auto Increment').(support("trigger")?checkbox("triggers",1,$J["triggers"],'Triggers'):""),"<tr><th>".'Data'."<td>".html_select('data_style',$Lb,$J["data_style"]),'</table>
<p><input type="submit" value="Export">
<input type="hidden" name="token" value="',$T,'">

<table>
',script("qsl('table').onclick = dumpClick;");$og=array();if(DB!=""){$Za=($a!=""?"":" checked");echo"<thead><tr>","<th style='text-align: left;'><label class='block'><input type='checkbox' id='check-tables'$Za>".'Tables'."</label>".script("qs('#check-tables').onclick = partial(formCheck, /^tables\\[/);",""),"<th style='text-align: right;'><label class='block'>".'Data'."<input type='checkbox' id='check-data'$Za></label>".script("qs('#check-data').onclick = partial(formCheck, /^data\\[/);",""),"</thead>\n";$hj="";$bi=tables_list();foreach($bi
as$B=>$U){$ng=preg_replace('~_.*~','',$B);$Za=($a==""||$a==(substr($a,-1)=="%"?"$ng%":$B));$qg="<tr><td>".checkbox("tables[]",$B,$Za,$B,"","block");if($U!==null&&!preg_match('~table~i',$U))$hj.="$qg\n";else
echo"$qg<td align='right'><label class='block'><span id='Rows-".h($B)."'></span>".checkbox("data[]",$B,$Za)."</label>\n";$og[$ng]++;}echo$hj;if($bi)echo
script("ajaxSetHtml('".js_escape(ME)."script=db');");}else{echo"<thead><tr><th style='text-align: left;'>","<label class='block'><input type='checkbox' id='check-databases'".($a==""?" checked":"").">".'Database'."</label>",script("qs('#check-databases').onclick = partial(formCheck, /^databases\\[/);",""),"</thead>\n";$i=$b->databases();if($i){foreach($i
as$j){if(!information_schema($j)){$ng=preg_replace('~_.*~','',$j);echo"<tr><td>".checkbox("databases[]",$j,$a==""||$a=="$ng%",$j,"","block")."\n";$og[$ng]++;}}}else
echo"<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";}echo'</table>
</form>
';$Yc=true;foreach($og
as$y=>$X){if($y!=""&&$X>1){echo($Yc?"<p>":" ")."<a href='".h(ME)."dump=".urlencode("$y%")."'>".h($y)."</a>";$Yc=false;}}}elseif(isset($_GET["privileges"])){page_header('Privileges');echo'<p class="links"><a href="'.h(ME).'user=">'.'Create user'."</a>";$H=$f->query("SELECT User, Host FROM mysql.".(DB==""?"user":"db WHERE ".q(DB)." LIKE Db")." ORDER BY Host, User");$qd=$H;if(!$H)$H=$f->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");echo"<form action=''><p>\n";hidden_fields_get();echo"<input type='hidden' name='db' value='".h(DB)."'>\n",($qd?"":"<input type='hidden' name='grant' value=''>\n"),"<table class='odds'>\n","<thead><tr><th>".'Username'."<th>".'Server'."<th></thead>\n";while($J=$H->fetch_assoc())echo'<tr><td>'.h($J["User"])."<td>".h($J["Host"]).'<td><a href="'.h(ME.'user='.urlencode($J["User"]).'&host='.urlencode($J["Host"])).'">'.'Edit'."</a>\n";if(!$qd||DB!="")echo"<tr><td><input name='user' autocapitalize='off'><td><input name='host' value='localhost' autocapitalize='off'><td><input type='submit' value='".'Edit'."'>\n";echo"</table>\n","</form>\n";}elseif(isset($_GET["sql"])){if(!$m&&$_POST["export"]){dump_headers("sql");$b->dumpTable("","");$b->dumpData("","table",$_POST["query"]);$b->dumpFooter();exit;}restart_session();$Ed=&get_session("queries");$Dd=&$Ed[DB];if(!$m&&$_POST["clear"]){$Dd=array();redirect(remove_from_uri("history"));}page_header((isset($_GET["import"])?'Import':'SQL command'),$m);if(!$m&&$_POST){$r=false;if(!isset($_GET["import"]))$G=$_POST["query"];elseif($_POST["webfile"]){$Fh=$b->importServerPath();$r=@fopen((file_exists($Fh)?$Fh:"compress.zlib://$Fh.gz"),"rb");$G=($r?fread($r,1e6):false);}else$G=get_file("sql_file",true,";");if(is_string($G)){if(function_exists('memory_get_usage')&&($Ne=ini_bytes("memory_limit"))!="-1")@ini_set("memory_limit",max($Ne,2*strlen($G)+memory_get_usage()+8e6));if($G!=""&&strlen($G)<1e6){$xg=$G.(preg_match("~;[ \t\r\n]*\$~",$G)?"":";");if(!$Dd||reset(end($Dd))!=$xg){restart_session();$Dd[]=array($xg,time());set_session("queries",$Ed);stop_session();}}$Ch="(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";$Vb=";";$C=0;$uc=true;$g=connect($b->credentials());if(is_object($g)&&DB!=""){$g->select_db(DB);if($_GET["ns"]!="")set_schema($_GET["ns"],$g);}$ob=0;$Bc=array();$Vf='[\'"'.(JUSH=="sql"?'`#':(JUSH=="sqlite"?'`[':(JUSH=="mssql"?'[':''))).']|/\*|-- |$'.(JUSH=="pgsql"?'|\$[^$]*\$':'');$wi=microtime(true);parse_str($_COOKIE["adminer_export"],$ra);$lc=$b->dumpFormat();unset($lc["sql"]);while($G!=""){if(!$C&&preg_match("~^$Ch*+DELIMITER\\s+(\\S+)~i",$G,$A)){$Vb=$A[1];$G=substr($G,strlen($A[0]));}else{preg_match('('.preg_quote($Vb)."\\s*|$Vf)",$G,$A,PREG_OFFSET_CAPTURE,$C);list($jd,$ig)=$A[0];if(!$jd&&$r&&!feof($r))$G.=fread($r,1e5);else{if(!$jd&&rtrim($G)=="")break;$C=$ig+strlen($jd);if($jd&&rtrim($jd)!=$Vb){$Sa=$l->hasCStyleEscapes()||(JUSH=="pgsql"&&($ig>0&&strtolower($G[$ig-1])=="e"));$dg=($jd=='/*'?'\*/':($jd=='['?']':(preg_match('~^-- |^#~',$jd)?"\n":preg_quote($jd).($Sa?"|\\\\.":""))));while(preg_match("($dg|\$)s",$G,$A,PREG_OFFSET_CAPTURE,$C)){$Zg=$A[0][0];if(!$Zg&&$r&&!feof($r))$G.=fread($r,1e5);else{$C=$A[0][1]+strlen($Zg);if(!$Zg||$Zg[0]!="\\")break;}}}else{$uc=false;$xg=substr($G,0,$ig);$ob++;$qg="<pre id='sql-$ob'><code class='jush-".JUSH."'>".$b->sqlCommandQuery($xg)."</code></pre>\n";if(JUSH=="sqlite"&&preg_match("~^$Ch*+ATTACH\\b~i",$xg,$A)){echo$qg,"<p class='error'>".'ATTACH queries are not supported.'."\n";$Bc[]=" <a href='#sql-$ob'>$ob</a>";if($_POST["error_stops"])break;}else{if(!$_POST["only_errors"]){echo$qg;ob_flush();flush();}$Kh=microtime(true);if($f->multi_query($xg)&&is_object($g)&&preg_match("~^$Ch*+USE\\b~i",$xg))$g->query($xg);do{$H=$f->store_result();if($f->error){echo($_POST["only_errors"]?$qg:""),"<p class='error'>".'Error in query'.($f->errno?" ($f->errno)":"").": ".error()."\n";$Bc[]=" <a href='#sql-$ob'>$ob</a>";if($_POST["error_stops"])break
2;}else{$li=" <span class='time'>(".format_time($Kh).")</span>".(strlen($xg)<1000?" <a href='".h(ME)."sql=".urlencode(trim($xg))."'>".'Edit'."</a>":"");$ta=$f->affected_rows;$kj=($_POST["only_errors"]?"":$l->warnings());$lj="warnings-$ob";if($kj)$li.=", <a href='#$lj'>".'Warnings'."</a>".script("qsl('a').onclick = partial(toggle, '$lj');","");$Jc=null;$Kc="explain-$ob";if(is_object($H)){$z=$_POST["limit"];$Gf=select($H,$g,array(),$z);if(!$_POST["only_errors"]){echo"<form action='' method='post'>\n";$hf=$H->num_rows;echo"<p>".($hf?($z&&$hf>$z?sprintf('%d / ',$z):"").lang(array('%d row','%d rows'),$hf):""),$li;if($g&&preg_match("~^($Ch|\\()*+SELECT\\b~i",$xg)&&($Jc=explain($g,$xg)))echo", <a href='#$Kc'>Explain</a>".script("qsl('a').onclick = partial(toggle, '$Kc');","");$u="export-$ob";echo", <a href='#$u'>".'Export'."</a>".script("qsl('a').onclick = partial(toggle, '$u');","")."<span id='$u' class='hidden'>: ".html_select("output",$b->dumpOutput(),$ra["output"])." ".html_select("format",$lc,$ra["format"])."<input type='hidden' name='query' value='".h($xg)."'>"." <input type='submit' name='export' value='".'Export'."'><input type='hidden' name='token' value='$T'></span>\n"."</form>\n";}}else{if(preg_match("~^$Ch*+(CREATE|DROP|ALTER)$Ch++(DATABASE|SCHEMA)\\b~i",$xg)){restart_session();set_session("dbs",null);stop_session();}if(!$_POST["only_errors"])echo"<p class='message' title='".h($f->info)."'>".lang(array('Query executed OK, %d row affected.','Query executed OK, %d rows affected.'),$ta)."$li\n";}echo($kj?"<div id='$lj' class='hidden'>\n$kj</div>\n":"");if($Jc){echo"<div id='$Kc' class='hidden explain'>\n";select($Jc,$g,$Gf);echo"</div>\n";}}$Kh=microtime(true);}while($f->next_result());}$G=substr($G,$C);$C=0;}}}}if($uc)echo"<p class='message'>".'No commands to execute.'."\n";elseif($_POST["only_errors"]){echo"<p class='message'>".lang(array('%d query executed OK.','%d queries executed OK.'),$ob-count($Bc))," <span class='time'>(".format_time($wi).")</span>\n";}elseif($Bc&&$ob>1)echo"<p class='error'>".'Error in query'.": ".implode("",$Bc)."\n";}else
echo"<p class='error'>".upload_error($G)."\n";}echo'
<form action="" method="post" enctype="multipart/form-data" id="form">
';$Hc="<input type='submit' value='".'Execute'."' title='Ctrl+Enter'>";if(!isset($_GET["import"])){$xg=$_GET["sql"];if($_POST)$xg=$_POST["query"];elseif($_GET["history"]=="all")$xg=$Dd;elseif($_GET["history"]!="")$xg=$Dd[$_GET["history"]][0];echo"<p>";textarea("query",$xg,20);echo
script(($_POST?"":"qs('textarea').focus();\n")."qs('#form').onsubmit = partial(sqlSubmit, qs('#form'), '".js_escape(remove_from_uri("sql|limit|error_stops|only_errors|history"))."');"),"<p>$Hc\n",'Limit rows'.": <input type='number' name='limit' class='size' value='".h($_POST?$_POST["limit"]:$_GET["limit"])."'>\n";}else{echo"<fieldset><legend>".'File upload'."</legend><div>";$wd=(extension_loaded("zlib")?"[.gz]":"");echo(ini_bool("file_uploads")?"SQL$wd (&lt; ".ini_get("upload_max_filesize")."B): <input type='file' name='sql_file[]' multiple>\n$Hc":'File uploads are disabled.'),"</div></fieldset>\n";$Ld=$b->importServerPath();if($Ld){echo"<fieldset><legend>".'From server'."</legend><div>",sprintf('Webserver file %s',"<code>".h($Ld)."$wd</code>"),' <input type="submit" name="webfile" value="'.'Run file'.'">',"</div></fieldset>\n";}echo"<p>";}echo
checkbox("error_stops",1,($_POST?$_POST["error_stops"]:isset($_GET["import"])||$_GET["error_stops"]),'Stop on error')."\n",checkbox("only_errors",1,($_POST?$_POST["only_errors"]:isset($_GET["import"])||$_GET["only_errors"]),'Show only errors')."\n","<input type='hidden' name='token' value='$T'>\n";if(!isset($_GET["import"])&&$Dd){print_fieldset("history",'History',$_GET["history"]!="");for($X=end($Dd);$X;$X=prev($Dd)){$y=key($Dd);list($xg,$li,$pc)=$X;echo'<a href="'.h(ME."sql=&history=$y").'">'.'Edit'."</a>"." <span class='time' title='".@date('Y-m-d',$li)."'>".@date("H:i:s",$li)."</span>"." <code class='jush-".JUSH."'>".shorten_utf8(ltrim(str_replace("\n"," ",str_replace("\r","",preg_replace('~^(#|-- ).*~m','',$xg)))),80,"</code>").($pc?" <span class='time'>($pc)</span>":"")."<br>\n";}echo"<input type='submit' name='clear' value='".'Clear'."'>\n","<a href='".h(ME."sql=&history=all")."'>".'Edit all'."</a>\n","</div></fieldset>\n";}echo'</form>
';}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$o=fields($a);$Z=(isset($_GET["select"])?($_POST["check"]&&count($_POST["check"])==1?where_check($_POST["check"][0],$o):""):where($_GET,$o));$Ri=(isset($_GET["select"])?$_POST["edit"]:$Z);foreach($o
as$B=>$n){if(!isset($n["privileges"][$Ri?"update":"insert"])||$b->fieldName($n)==""||$n["generated"])unset($o[$B]);}if($_POST&&!$m&&!isset($_GET["select"])){$_e=$_POST["referer"];if($_POST["insert"])$_e=($Ri?null:$_SERVER["REQUEST_URI"]);elseif(!preg_match('~^.+&select=.+$~',$_e))$_e=ME."select=".urlencode($a);$x=indexes($a);$Mi=unique_array($_GET["where"],$x);$_g="\nWHERE $Z";if(isset($_POST["delete"]))queries_redirect($_e,'Item has been deleted.',$l->delete($a,$_g,!$Mi));else{$N=array();foreach($o
as$B=>$n){$X=process_input($n);if($X!==false&&$X!==null)$N[idf_escape($B)]=$X;}if($Ri){if(!$N)redirect($_e);queries_redirect($_e,'Item has been updated.',$l->update($a,$N,$_g,!$Mi));if(is_ajax()){page_headers();page_messages($m);exit;}}else{$H=$l->insert($a,$N);$re=($H?last_id():0);queries_redirect($_e,sprintf('Item%s has been inserted.',($re?" $re":"")),$H);}}}$J=null;if($_POST["save"])$J=(array)$_POST["fields"];elseif($Z){$L=array();foreach($o
as$B=>$n){if(isset($n["privileges"]["select"])){$za=($_POST["clone"]&&$n["auto_increment"]?"''":convert_field($n));$L[]=($za?"$za AS ":"").idf_escape($B);}}$J=array();if(!support("table"))$L=array("*");if($L){$H=$l->select($a,$L,array($Z),$L,array(),(isset($_GET["select"])?2:1));if(!$H)$m=error();else{$J=$H->fetch_assoc();if(!$J)$J=false;}if(isset($_GET["select"])&&(!$J||$H->fetch_assoc()))$J=null;}}if(!support("table")&&!$o){if(!$Z){$H=$l->select($a,array("*"),$Z,array("*"));$J=($H?$H->fetch_assoc():false);if(!$J)$J=array($l->primary=>"");}if($J){foreach($J
as$y=>$X){if(!$Z)$J[$y]=null;$o[$y]=array("field"=>$y,"null"=>($y!=$l->primary),"auto_increment"=>($y==$l->primary));}}}edit_form($a,$o,$J,$Ri);}elseif(isset($_GET["create"])){$a=$_GET["create"];$Xf=array();foreach(array('HASH','LINEAR HASH','KEY','LINEAR KEY','RANGE','LIST')as$y)$Xf[$y]=$y;$Fg=referencable_primary($a);$hd=array();foreach($Fg
as$Wh=>$n)$hd[str_replace("`","``",$Wh)."`".str_replace("`","``",$n["field"])]=$Wh;$Jf=array();$R=array();if($a!=""){$Jf=fields($a);$R=table_status($a);if(!$R)$m='No tables.';}$J=$_POST;$J["fields"]=(array)$J["fields"];if($J["auto_increment_col"])$J["fields"][$J["auto_increment_col"]]["auto_increment"]=true;if($_POST)set_adminer_settings(array("comments"=>$_POST["comments"],"defaults"=>$_POST["defaults"]));if($_POST&&!process_fields($J["fields"])&&!$m){if($_POST["drop"])queries_redirect(substr(ME,0,-1),'Table has been dropped.',drop_tables(array($a)));else{$o=array();$xa=array();$Vi=false;$fd=array();$If=reset($Jf);$va=" FIRST";foreach($J["fields"]as$y=>$n){$q=$hd[$n["type"]];$Gi=($q!==null?$Fg[$q]:$n);if($n["field"]!=""){if(!$n["generated"])$n["default"]=null;$vg=process_field($n,$Gi);$xa[]=array($n["orig"],$vg,$va);if(!$If||$vg!==process_field($If,$If)){$o[]=array($n["orig"],$vg,$va);if($n["orig"]!=""||$va)$Vi=true;}if($q!==null)$fd[idf_escape($n["field"])]=($a!=""&&JUSH!="sqlite"?"ADD":" ").format_foreign_key(array('table'=>$hd[$n["type"]],'source'=>array($n["field"]),'target'=>array($Gi["field"]),'on_delete'=>$n["on_delete"],));$va=" AFTER ".idf_escape($n["field"]);}elseif($n["orig"]!=""){$Vi=true;$o[]=array($n["orig"]);}if($n["orig"]!=""){$If=next($Jf);if(!$If)$va="";}}$Zf="";if(support("partitioning")){if(isset($Xf[$J["partition_by"]])){$Uf=array_filter($J,function($y){return
preg_match('~^partition~',$y);},ARRAY_FILTER_USE_KEY);foreach($Uf["partition_names"]as$y=>$B){if($B==""){unset($Uf["partition_names"][$y]);unset($Uf["partition_values"][$y]);}}if($Uf!=get_partitions_info($a)){$ag=array();if($Uf["partition_by"]=='RANGE'||$Uf["partition_by"]=='LIST'){foreach($Uf["partition_names"]as$y=>$B){$Y=$Uf["partition_values"][$y];$ag[]="\n  PARTITION ".idf_escape($B)." VALUES ".($Uf["partition_by"]=='RANGE'?"LESS THAN":"IN").($Y!=""?" ($Y)":" MAXVALUE");}}$Zf.="\nPARTITION BY $Uf[partition_by]($Uf[partition])";if($ag)$Zf.=" (".implode(",",$ag)."\n)";elseif($Uf["partitions"])$Zf.=" PARTITIONS ".(+$Uf["partitions"]);}}elseif(preg_match("~partitioned~",$R["Create_options"]))$Zf.="\nREMOVE PARTITIONING";}$Oe='Table has been altered.';if($a==""){cookie("adminer_engine",$J["Engine"]);$Oe='Table has been created.';}$B=trim($J["name"]);queries_redirect(ME.(support("table")?"table=":"select=").urlencode($B),$Oe,alter_table($a,$B,(JUSH=="sqlite"&&($Vi||$fd)?$xa:$o),$fd,($J["Comment"]!=$R["Comment"]?$J["Comment"]:null),($J["Engine"]&&$J["Engine"]!=$R["Engine"]?$J["Engine"]:""),($J["Collation"]&&$J["Collation"]!=$R["Collation"]?$J["Collation"]:""),($J["Auto_increment"]!=""?number($J["Auto_increment"]):""),$Zf));}}page_header(($a!=""?'Alter table':'Create table'),$m,array("table"=>$a),h($a));if(!$_POST){$Ii=$l->types();$J=array("Engine"=>$_COOKIE["adminer_engine"],"fields"=>array(array("field"=>"","type"=>(isset($Ii["int"])?"int":(isset($Ii["integer"])?"integer":"")),"on_update"=>"")),"partition_names"=>array(""),);if($a!=""){$J=$R;$J["name"]=$a;$J["fields"]=array();if(!$_GET["auto_increment"])$J["Auto_increment"]="";foreach($Jf
as$n){$n["generated"]=$n["generated"]?:(isset($n["default"])?"DEFAULT":"");$J["fields"][]=$n;}if(support("partitioning")){$J+=get_partitions_info($a);$J["partition_names"][]="";$J["partition_values"][]="";}}}$jb=collations();$wc=engines();foreach($wc
as$vc){if(!strcasecmp($vc,$J["Engine"])){$J["Engine"]=$vc;break;}}echo'
<form action="" method="post" id="form">
<p>
';if(support("columns")||$a==""){echo'Table name: <input name="name"',($a==""&&!$_POST?" autofocus":""),' data-maxlength="64" value="',h($J["name"]),'" autocapitalize="off">
',($wc?html_select("Engine",array(""=>"(".'engine'.")")+$wc,$J["Engine"]).on_help("getTarget(event).value",1).script("qsl('select').onchange = helpClose;"):""),' ';if($jb){echo"<datalist id='collations'>".optionlist($jb)."</datalist>",(preg_match("~sqlite|mssql~",JUSH)?"":"<input list='collations' name='Collation' value='".h($J["Collation"])."' placeholder='(".'collation'.")'>");}echo' <input type="submit" value="Save">
';}echo'
';if(support("columns")){echo'<div class="scrollable">
<table id="edit-fields" class="nowrap">
';edit_fields($J["fields"],$jb,"TABLE",$hd);echo'</table>
',script("editFields();"),'</div>
<p>
Auto Increment: <input type="number" name="Auto_increment" class="size" value="',h($J["Auto_increment"]),'">
',checkbox("defaults",1,($_POST?$_POST["defaults"]:adminer_setting("defaults")),'Default values',"columnShow(this.checked, 5)","jsonly");$rb=($_POST?$_POST["comments"]:adminer_setting("comments"));echo(support("comment")?checkbox("comments",1,$rb,'Comment',"editingCommentsClick(this, true);","jsonly").' '.(preg_match('~\n~',$J["Comment"])?"<textarea name='Comment' rows='2' cols='20'".($rb?"":" class='hidden'").">".h($J["Comment"])."</textarea>":'<input name="Comment" value="'.h($J["Comment"]).'" data-maxlength="'.(min_version(5.5)?2048:60).'"'.($rb?"":" class='hidden'").'>'):''),'<p>
<input type="submit" value="Save">
';}echo'
';if($a!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$a));}if(support("partitioning")){$Yf=preg_match('~RANGE|LIST~',$J["partition_by"]);print_fieldset("partition",'Partition by',$J["partition_by"]);echo'<p>
',html_select("partition_by",array(""=>"")+$Xf,$J["partition_by"]).on_help("getTarget(event).value.replace(/./, 'PARTITION BY \$&')",1).script("qsl('select').onchange = partitionByChange;"),'(<input name="partition" value="',h($J["partition"]),'">)
Partitions: <input type="number" name="partitions" class="size',($Yf||!$J["partition_by"]?" hidden":""),'" value="',h($J["partitions"]),'">
<table id="partition-table"',($Yf?"":" class='hidden'"),'>
<thead><tr><th>Partition name<th>Values</thead>
';foreach($J["partition_names"]as$y=>$X){echo'<tr>','<td><input name="partition_names[]" value="'.h($X).'" autocapitalize="off">',($y==count($J["partition_names"])-1?script("qsl('input').oninput = partitionNameChange;"):''),'<td><input name="partition_values[]" value="'.h($J["partition_values"][$y]).'">';}echo'</table>
</div></fieldset>
';}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["indexes"])){$a=$_GET["indexes"];$Pd=array("PRIMARY","UNIQUE","INDEX");$R=table_status($a,true);if(preg_match('~MyISAM|M?aria'.(min_version(5.6,'10.0.5')?'|InnoDB':'').'~i',$R["Engine"]))$Pd[]="FULLTEXT";if(preg_match('~MyISAM|M?aria'.(min_version(5.7,'10.2.2')?'|InnoDB':'').'~i',$R["Engine"]))$Pd[]="SPATIAL";$x=indexes($a);$F=array();if(JUSH=="mongo"){$F=$x["_id_"];unset($Pd[0]);unset($x["_id_"]);}$J=$_POST;if($J)set_adminer_settings(array("index_options"=>$J["options"]));if($_POST&&!$m&&!$_POST["add"]&&!$_POST["drop_col"]){$c=array();foreach($J["indexes"]as$w){$B=$w["name"];if(in_array($w["type"],$Pd)){$e=array();$xe=array();$Xb=array();$N=array();ksort($w["columns"]);foreach($w["columns"]as$y=>$d){if($d!=""){$we=$w["lengths"][$y];$Wb=$w["descs"][$y];$N[]=idf_escape($d).($we?"(".(+$we).")":"").($Wb?" DESC":"");$e[]=$d;$xe[]=($we?:null);$Xb[]=$Wb;}}$Ic=$x[$B];if($Ic){ksort($Ic["columns"]);ksort($Ic["lengths"]);ksort($Ic["descs"]);if($w["type"]==$Ic["type"]&&array_values($Ic["columns"])===$e&&(!$Ic["lengths"]||array_values($Ic["lengths"])===$xe)&&array_values($Ic["descs"])===$Xb){unset($x[$B]);continue;}}if($e)$c[]=array($w["type"],$B,$N);}}foreach($x
as$B=>$Ic)$c[]=array($Ic["type"],$B,"DROP");if(!$c)redirect(ME."table=".urlencode($a));queries_redirect(ME."table=".urlencode($a),'Indexes have been altered.',alter_indexes($a,$c));}page_header('Indexes',$m,array("table"=>$a),h($a));$o=array_keys(fields($a));if($_POST["add"]){foreach($J["indexes"]as$y=>$w){if($w["columns"][count($w["columns"])]!="")$J["indexes"][$y]["columns"][]="";}$w=end($J["indexes"]);if($w["type"]||array_filter($w["columns"],'strlen'))$J["indexes"][]=array("columns"=>array(1=>""));}if(!$J){foreach($x
as$y=>$w){$x[$y]["name"]=$y;$x[$y]["columns"][]="";}$x[]=array("columns"=>array(1=>""));$J["indexes"]=$x;}$xe=(JUSH=="sql"||JUSH=="mssql");$uh=($_POST?$_POST["options"]:adminer_setting("index_options"));echo'
<form action="" method="post">
<div class="scrollable">
<table class="nowrap">
<thead><tr>
<th id="label-type">Index Type
<th><input type="submit" class="wayoff">','Column'.($xe?"<span class='idxopts".($uh?"":" hidden")."'> (".'length'.")</span>":"");if($xe||support("descidx"))echo
checkbox("options",1,$uh,'Options',"indexOptionsShow(this.checked)","jsonly")."\n";echo'<th id="label-name">Name
<th><noscript>',"<input type='image' class='icon' name='add[0]' src='".h(preg_replace("~\\?.*~","",ME)."?file=plus.gif&version=5.0.4")."' alt='+' title='".'Add next'."'>",'</noscript>
</thead>
';if($F){echo"<tr><td>PRIMARY<td>";foreach($F["columns"]as$y=>$d){echo
select_input(" disabled",$o,$d),"<label><input disabled type='checkbox'>".'descending'."</label> ";}echo"<td><td>\n";}$ge=1;foreach($J["indexes"]as$w){if(!$_POST["drop_col"]||$ge!=key($_POST["drop_col"])){echo"<tr><td>".html_select("indexes[$ge][type]",array(-1=>"")+$Pd,$w["type"],($ge==count($J["indexes"])?"indexesAddRow.call(this);":""),"label-type"),"<td>";ksort($w["columns"]);$t=1;foreach($w["columns"]as$y=>$d){echo"<span>".select_input(" name='indexes[$ge][columns][$t]' title='".'Column'."'",($o?array_combine($o,$o):$o),$d,"partial(".($t==count($w["columns"])?"indexesAddColumn":"indexesChangeColumn").", '".js_escape(JUSH=="sql"?"":$_GET["indexes"]."_")."')"),"<span class='idxopts".($uh?"":" hidden")."'>",($xe?"<input type='number' name='indexes[$ge][lengths][$t]' class='size' value='".h($w["lengths"][$y])."' title='".'Length'."'>":""),(support("descidx")?checkbox("indexes[$ge][descs][$t]",1,$w["descs"][$y],'descending'):""),"</span> </span>";$t++;}echo"<td><input name='indexes[$ge][name]' value='".h($w["name"])."' autocapitalize='off' aria-labelledby='label-name'>\n","<td><input type='image' class='icon' name='drop_col[$ge]' src='".h(preg_replace("~\\?.*~","",ME)."?file=cross.gif&version=5.0.4")."' alt='x' title='".'Remove'."'>".script("qsl('input').onclick = partial(editingRemoveRow, 'indexes\$1[type]');");}$ge++;}echo'</table>
</div>
<p>
<input type="submit" value="Save">
<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["database"])){$J=$_POST;if($_POST&&!$m&&!isset($_POST["add_x"])){$B=trim($J["name"]);if($_POST["drop"]){$_GET["db"]="";queries_redirect(remove_from_uri("db|database"),'Database has been dropped.',drop_databases(array(DB)));}elseif(DB!==$B){if(DB!=""){$_GET["db"]=$B;queries_redirect(preg_replace('~\bdb=[^&]*&~','',ME)."db=".urlencode($B),'Database has been renamed.',rename_database($B,$J["collation"]));}else{$i=explode("\n",str_replace("\r","",$B));$Ph=true;$qe="";foreach($i
as$j){if(count($i)==1||$j!=""){if(!create_database($j,$J["collation"]))$Ph=false;$qe=$j;}}restart_session();set_session("dbs",null);queries_redirect(ME."db=".urlencode($qe),'Database has been created.',$Ph);}}else{if(!$J["collation"])redirect(substr(ME,0,-1));query_redirect("ALTER DATABASE ".idf_escape($B).(preg_match('~^[a-z0-9_]+$~i',$J["collation"])?" COLLATE $J[collation]":""),substr(ME,0,-1),'Database has been altered.');}}page_header(DB!=""?'Alter database':'Create database',$m,array(),h(DB));$jb=collations();$B=DB;if($_POST)$B=$J["name"];elseif(DB!="")$J["collation"]=db_collation(DB,$jb);elseif(JUSH=="sql"){foreach(get_vals("SHOW GRANTS")as$qd){if(preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\.\*)?~',$qd,$A)&&$A[1]){$B=stripcslashes(idf_unescape("`$A[2]`"));break;}}}echo'
<form action="" method="post">
<p>
',($_POST["add_x"]||strpos($B,"\n")?'<textarea autofocus name="name" rows="10" cols="40">'.h($B).'</textarea><br>':'<input name="name" autofocus value="'.h($B).'" data-maxlength="64" autocapitalize="off">')."\n".($jb?html_select("collation",array(""=>"(".'collation'.")")+$jb,$J["collation"]).doc_link(array('sql'=>"charset-charsets.html",'mariadb'=>"supported-character-sets-and-collations/",'mssql'=>"relational-databases/system-functions/sys-fn-helpcollations-transact-sql",)):""),'<input type="submit" value="Save">
';if(DB!="")echo"<input type='submit' name='drop' value='".'Drop'."'>".confirm(sprintf('Drop %s?',DB))."\n";elseif(!$_POST["add_x"]&&$_GET["db"]=="")echo"<input type='image' class='icon' name='add' src='".h(preg_replace("~\\?.*~","",ME)."?file=plus.gif&version=5.0.4")."' alt='+' title='".'Add next'."'>\n";echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["scheme"])){$J=$_POST;if($_POST&&!$m){$_=preg_replace('~ns=[^&]*&~','',ME)."ns=";if($_POST["drop"])query_redirect("DROP SCHEMA ".idf_escape($_GET["ns"]),$_,'Schema has been dropped.');else{$B=trim($J["name"]);$_.=urlencode($B);if($_GET["ns"]=="")query_redirect("CREATE SCHEMA ".idf_escape($B),$_,'Schema has been created.');elseif($_GET["ns"]!=$B)query_redirect("ALTER SCHEMA ".idf_escape($_GET["ns"])." RENAME TO ".idf_escape($B),$_,'Schema has been altered.');else
redirect($_);}}page_header($_GET["ns"]!=""?'Alter schema':'Create schema',$m);if(!$J)$J["name"]=$_GET["ns"];echo'
<form action="" method="post">
<p><input name="name" autofocus value="',h($J["name"]),'" autocapitalize="off">
<input type="submit" value="Save">
';if($_GET["ns"]!="")echo"<input type='submit' name='drop' value='".'Drop'."'>".confirm(sprintf('Drop %s?',$_GET["ns"]))."\n";echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["call"])){$da=($_GET["name"]?:$_GET["call"]);page_header('Call'.": ".h($da),$m);$Vg=routine($_GET["call"],(isset($_GET["callf"])?"FUNCTION":"PROCEDURE"));$Md=array();$Of=array();foreach($Vg["fields"]as$t=>$n){if(substr($n["inout"],-3)=="OUT")$Of[$t]="@".idf_escape($n["field"])." AS ".idf_escape($n["field"]);if(!$n["inout"]||substr($n["inout"],0,2)=="IN")$Md[]=$t;}if(!$m&&$_POST){$Ta=array();foreach($Vg["fields"]as$y=>$n){if(in_array($y,$Md)){$X=process_input($n);if($X===false)$X="''";if(isset($Of[$y]))$f->query("SET @".idf_escape($n["field"])." = $X");}$Ta[]=(isset($Of[$y])?"@".idf_escape($n["field"]):$X);}$G=(isset($_GET["callf"])?"SELECT":"CALL")." ".table($da)."(".implode(", ",$Ta).")";$Kh=microtime(true);$H=$f->multi_query($G);$ta=$f->affected_rows;echo$b->selectQuery($G,$Kh,!$H);if(!$H)echo"<p class='error'>".error()."\n";else{$g=connect($b->credentials());if(is_object($g))$g->select_db(DB);do{$H=$f->store_result();if(is_object($H))select($H,$g);else
echo"<p class='message'>".lang(array('Routine has been called, %d row affected.','Routine has been called, %d rows affected.'),$ta)." <span class='time'>".@date("H:i:s")."</span>\n";}while($f->next_result());if($Of)select($f->query("SELECT ".implode(", ",$Of)));}}echo'
<form action="" method="post">
';if($Md){echo"<table class='layout'>\n";foreach($Md
as$y){$n=$Vg["fields"][$y];$B=$n["field"];echo"<tr><th>".$b->fieldName($n);$Y=$_POST["fields"][$B];if($Y!=""){if($n["type"]=="set")$Y=implode(",",$Y);}input($n,$Y,(string)$_POST["function"][$B]);echo"\n";}echo"</table>\n";}echo'<p>
<input type="submit" value="Call">
<input type="hidden" name="token" value="',$T,'">
</form>

<pre>
';function
pre_tr($Zg){return
preg_replace('~^~m','<tr>',preg_replace('~\|~','<td>',preg_replace('~\|$~m',"",rtrim($Zg))));}$Q='(\+--[-+]+\+\n)';$J='(\| .* \|\n)';echo
preg_replace_callback("~^$Q?$J$Q?($J*)$Q?~m",function($A){$Zc=pre_tr($A[2]);return"<table>\n".($A[1]?"<thead>$Zc</thead>\n":$Zc).pre_tr($A[4])."\n</table>";},preg_replace('~(\n(    -|mysql)&gt; )(.+)~',"\\1<code class='jush-sql'>\\3</code>",preg_replace('~(.+)\n---+\n~',"<b>\\1</b>\n",h($Vg['comment']))));echo'</pre>
';}elseif(isset($_GET["foreign"])){$a=$_GET["foreign"];$B=$_GET["name"];$J=$_POST;if($_POST&&!$m&&!$_POST["add"]&&!$_POST["change"]&&!$_POST["change-js"]){if(!$_POST["drop"]){$J["source"]=array_filter($J["source"],'strlen');ksort($J["source"]);$ei=array();foreach($J["source"]as$y=>$X)$ei[$y]=$J["target"][$y];$J["target"]=$ei;}if(JUSH=="sqlite")$H=recreate_table($a,$a,array(),array(),array(" $B"=>($J["drop"]?"":" ".format_foreign_key($J))));else{$c="ALTER TABLE ".table($a);$H=($B==""||queries("$c DROP ".(JUSH=="sql"?"FOREIGN KEY ":"CONSTRAINT ").idf_escape($B)));if(!$J["drop"])$H=queries("$c ADD".format_foreign_key($J));}queries_redirect(ME."table=".urlencode($a),($J["drop"]?'Foreign key has been dropped.':($B!=""?'Foreign key has been altered.':'Foreign key has been created.')),$H);if(!$J["drop"])$m="$m<br>".'Source and target columns must have the same data type, there must be an index on the target columns and referenced data must exist.';}page_header('Foreign key',$m,array("table"=>$a),h($a));if($_POST){ksort($J["source"]);if($_POST["add"])$J["source"][]="";elseif($_POST["change"]||$_POST["change-js"])$J["target"]=array();}elseif($B!=""){$hd=foreign_keys($a);$J=$hd[$B];$J["source"][]="";}else{$J["table"]=$a;$J["source"]=array("");}echo'
<form action="" method="post">
';$Bh=array_keys(fields($a));if($J["db"]!="")$f->select_db($J["db"]);if($J["ns"]!=""){$Kf=get_schema();set_schema($J["ns"]);}$Eg=array_keys(array_filter(table_status('',true),'Adminer\fk_support'));$ei=array_keys(fields(in_array($J["table"],$Eg)?$J["table"]:reset($Eg)));$uf="this.form['change-js'].value = '1'; this.form.submit();";echo"<p>".'Target table'.": ".html_select("table",$Eg,$J["table"],$uf)."\n";if(support("scheme")){$ch=array_filter($b->schemas(),function($bh){return!preg_match('~^information_schema$~i',$bh);});echo'Schema'.": ".html_select("ns",$ch,$J["ns"]!=""?$J["ns"]:$_GET["ns"],$uf);if($J["ns"]!="")set_schema($Kf);}elseif(JUSH!="sqlite"){$Qb=array();foreach($b->databases()as$j){if(!information_schema($j))$Qb[]=$j;}echo'DB'.": ".html_select("db",$Qb,$J["db"]!=""?$J["db"]:$_GET["db"],$uf);}echo'<input type="hidden" name="change-js" value="">
<noscript><p><input type="submit" name="change" value="Change"></noscript>
<table>
<thead><tr><th id="label-source">Source<th id="label-target">Target</thead>
';$ge=0;foreach($J["source"]as$y=>$X){echo"<tr>","<td>".html_select("source[".(+$y)."]",array(-1=>"")+$Bh,$X,($ge==count($J["source"])-1?"foreignAddRow.call(this);":""),"label-source"),"<td>".html_select("target[".(+$y)."]",$ei,$J["target"][$y],"","label-target");$ge++;}echo'</table>
<p>
ON DELETE: ',html_select("on_delete",array(-1=>"")+explode("|",$l->onActions),$J["on_delete"]),' ON UPDATE: ',html_select("on_update",array(-1=>"")+explode("|",$l->onActions),$J["on_update"]),doc_link(array('sql'=>"innodb-foreign-key-constraints.html",'mariadb'=>"foreign-keys/",'pgsql'=>"sql-createtable.html#SQL-CREATETABLE-REFERENCES",'mssql'=>"t-sql/statements/create-table-transact-sql",'oracle'=>"SQLRF01111",)),'<p>
<input type="submit" value="Save">
<noscript><p><input type="submit" name="add" value="Add column"></noscript>
';if($B!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$B));}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["view"])){$a=$_GET["view"];$J=$_POST;$Lf="VIEW";if(JUSH=="pgsql"&&$a!=""){$O=table_status($a);$Lf=strtoupper($O["Engine"]);}if($_POST&&!$m){$B=trim($J["name"]);$za=" AS\n$J[select]";$_e=ME."table=".urlencode($B);$Oe='View has been altered.';$U=($_POST["materialized"]?"MATERIALIZED VIEW":"VIEW");if(!$_POST["drop"]&&$a==$B&&JUSH!="sqlite"&&$U=="VIEW"&&$Lf=="VIEW")query_redirect((JUSH=="mssql"?"ALTER":"CREATE OR REPLACE")." VIEW ".table($B).$za,$_e,$Oe);else{$gi=$B."_adminer_".uniqid();drop_create("DROP $Lf ".table($a),"CREATE $U ".table($B).$za,"DROP $U ".table($B),"CREATE $U ".table($gi).$za,"DROP $U ".table($gi),($_POST["drop"]?substr(ME,0,-1):$_e),'View has been dropped.',$Oe,'View has been created.',$a,$B);}}if(!$_POST&&$a!=""){$J=view($a);$J["name"]=$a;$J["materialized"]=($Lf!="VIEW");if(!$m)$m=error();}page_header(($a!=""?'Alter view':'Create view'),$m,array("table"=>$a),h($a));echo'
<form action="" method="post">
<p>Name: <input name="name" value="',h($J["name"]),'" data-maxlength="64" autocapitalize="off">
',(support("materializedview")?" ".checkbox("materialized",1,$J["materialized"],'Materialized view'):""),'<p>';textarea("select",$J["select"]);echo'<p>
<input type="submit" value="Save">
';if($a!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$a));}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["event"])){$aa=$_GET["event"];$Xd=array("YEAR","QUARTER","MONTH","DAY","HOUR","MINUTE","WEEK","SECOND","YEAR_MONTH","DAY_HOUR","DAY_MINUTE","DAY_SECOND","HOUR_MINUTE","HOUR_SECOND","MINUTE_SECOND");$Lh=array("ENABLED"=>"ENABLE","DISABLED"=>"DISABLE","SLAVESIDE_DISABLED"=>"DISABLE ON SLAVE");$J=$_POST;if($_POST&&!$m){if($_POST["drop"])query_redirect("DROP EVENT ".idf_escape($aa),substr(ME,0,-1),'Event has been dropped.');elseif(in_array($J["INTERVAL_FIELD"],$Xd)&&isset($Lh[$J["STATUS"]])){$ah="\nON SCHEDULE ".($J["INTERVAL_VALUE"]?"EVERY ".q($J["INTERVAL_VALUE"])." $J[INTERVAL_FIELD]".($J["STARTS"]?" STARTS ".q($J["STARTS"]):"").($J["ENDS"]?" ENDS ".q($J["ENDS"]):""):"AT ".q($J["STARTS"]))." ON COMPLETION".($J["ON_COMPLETION"]?"":" NOT")." PRESERVE";queries_redirect(substr(ME,0,-1),($aa!=""?'Event has been altered.':'Event has been created.'),queries(($aa!=""?"ALTER EVENT ".idf_escape($aa).$ah.($aa!=$J["EVENT_NAME"]?"\nRENAME TO ".idf_escape($J["EVENT_NAME"]):""):"CREATE EVENT ".idf_escape($J["EVENT_NAME"]).$ah)."\n".$Lh[$J["STATUS"]]." COMMENT ".q($J["EVENT_COMMENT"]).rtrim(" DO\n$J[EVENT_DEFINITION]",";").";"));}}page_header(($aa!=""?'Alter event'.": ".h($aa):'Create event'),$m);if(!$J&&$aa!=""){$K=get_rows("SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = ".q(DB)." AND EVENT_NAME = ".q($aa));$J=reset($K);}echo'
<form action="" method="post">
<table class="layout">
<tr><th>Name<td><input name="EVENT_NAME" value="',h($J["EVENT_NAME"]),'" data-maxlength="64" autocapitalize="off">
<tr><th title="datetime">Start<td><input name="STARTS" value="',h("$J[EXECUTE_AT]$J[STARTS]"),'">
<tr><th title="datetime">End<td><input name="ENDS" value="',h($J["ENDS"]),'">
<tr><th>Every<td><input type="number" name="INTERVAL_VALUE" value="',h($J["INTERVAL_VALUE"]),'" class="size"> ',html_select("INTERVAL_FIELD",$Xd,$J["INTERVAL_FIELD"]),'<tr><th>Status<td>',html_select("STATUS",$Lh,$J["STATUS"]),'<tr><th>Comment<td><input name="EVENT_COMMENT" value="',h($J["EVENT_COMMENT"]),'" data-maxlength="64">
<tr><th><td>',checkbox("ON_COMPLETION","PRESERVE",$J["ON_COMPLETION"]=="PRESERVE",'On completion preserve'),'</table>
<p>';textarea("EVENT_DEFINITION",$J["EVENT_DEFINITION"]);echo'<p>
<input type="submit" value="Save">
';if($aa!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$aa));}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["procedure"])){$da=($_GET["name"]?:$_GET["procedure"]);$Vg=(isset($_GET["function"])?"FUNCTION":"PROCEDURE");$J=$_POST;$J["fields"]=(array)$J["fields"];if($_POST&&!process_fields($J["fields"])&&!$m){$Hf=routine($_GET["procedure"],$Vg);$gi="$J[name]_adminer_".uniqid();drop_create("DROP $Vg ".routine_id($da,$Hf),create_routine($Vg,$J),"DROP $Vg ".routine_id($J["name"],$J),create_routine($Vg,array("name"=>$gi)+$J),"DROP $Vg ".routine_id($gi,$J),substr(ME,0,-1),'Routine has been dropped.','Routine has been altered.','Routine has been created.',$da,$J["name"]);}page_header(($da!=""?(isset($_GET["function"])?'Alter function':'Alter procedure').": ".h($da):(isset($_GET["function"])?'Create function':'Create procedure')),$m);if(!$_POST&&$da!=""){$J=routine($_GET["procedure"],$Vg);$J["name"]=$da;}$jb=get_vals("SHOW CHARACTER SET");sort($jb);$Wg=routine_languages();echo($jb?"<datalist id='collations'>".optionlist($jb)."</datalist>":""),'
<form action="" method="post" id="form">
<p>Name: <input name="name" value="',h($J["name"]),'" data-maxlength="64" autocapitalize="off">
',($Wg?'Language'.": ".html_select("language",$Wg,$J["language"])."\n":""),'<input type="submit" value="Save">
<div class="scrollable">
<table class="nowrap">
';edit_fields($J["fields"],$jb,$Vg);if(isset($_GET["function"])){echo"<tr><td>".'Return type';edit_type("returns",$J["returns"],$jb,array(),(JUSH=="pgsql"?array("void","trigger"):array()));}echo'</table>
',script("editFields();"),'</div>
<p>';textarea("definition",$J["definition"]);echo'<p>
<input type="submit" value="Save">
';if($da!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$da));}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["sequence"])){$fa=$_GET["sequence"];$J=$_POST;if($_POST&&!$m){$_=substr(ME,0,-1);$B=trim($J["name"]);if($_POST["drop"])query_redirect("DROP SEQUENCE ".idf_escape($fa),$_,'Sequence has been dropped.');elseif($fa=="")query_redirect("CREATE SEQUENCE ".idf_escape($B),$_,'Sequence has been created.');elseif($fa!=$B)query_redirect("ALTER SEQUENCE ".idf_escape($fa)." RENAME TO ".idf_escape($B),$_,'Sequence has been altered.');else
redirect($_);}page_header($fa!=""?'Alter sequence'.": ".h($fa):'Create sequence',$m);if(!$J)$J["name"]=$fa;echo'
<form action="" method="post">
<p><input name="name" value="',h($J["name"]),'" autocapitalize="off">
<input type="submit" value="Save">
';if($fa!="")echo"<input type='submit' name='drop' value='".'Drop'."'>".confirm(sprintf('Drop %s?',$fa))."\n";echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["type"])){$ga=$_GET["type"];$J=$_POST;if($_POST&&!$m){$_=substr(ME,0,-1);if($_POST["drop"])query_redirect("DROP TYPE ".idf_escape($ga),$_,'Type has been dropped.');else
query_redirect("CREATE TYPE ".idf_escape(trim($J["name"]))." $J[as]",$_,'Type has been created.');}page_header($ga!=""?'Alter type'.": ".h($ga):'Create type',$m);if(!$J)$J["as"]="AS ";echo'
<form action="" method="post">
<p>
';if($ga!=""){$Ii=$l->types();$_c=type_values($Ii[$ga]);if($_c)echo"<code class='jush-".JUSH."'>ENUM (".h($_c).")</code>\n<p>";echo"<input type='submit' name='drop' value='".'Drop'."'>".confirm(sprintf('Drop %s?',$ga))."\n";}else{echo'Name'.": <input name='name' value='".h($J['name'])."' autocapitalize='off'>\n",doc_link(array('pgsql'=>"datatype-enum.html",),"?");textarea("as",$J["as"]);echo"<p><input type='submit' value='".'Save'."'>\n";}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["check"])){$a=$_GET["check"];$B=$_GET["name"];$J=$_POST;if($J&&!$m){if(JUSH=="sqlite")$H=recreate_table($a,$a,array(),array(),array(),0,array(),$B,($J["drop"]?"":$J["clause"]));else{$H=($B==""||queries("ALTER TABLE ".table($a)." DROP CONSTRAINT ".idf_escape($B)));if(!$J["drop"])$H=queries("ALTER TABLE ".table($a)." ADD".($J["name"]!=""?" CONSTRAINT ".idf_escape($J["name"]):"")." CHECK ($J[clause])");}queries_redirect(ME."table=".urlencode($a),($J["drop"]?'Check has been dropped.':($B!=""?'Check has been altered.':'Check has been created.')),$H);}page_header(($B!=""?'Alter check'.": ".h($B):'Create check'),$m,array("table"=>$a));if(!$J){$ab=$l->checkConstraints($a);$J=array("name"=>$B,"clause"=>$ab[$B]);}echo'
<form action="" method="post">
<p>';if(JUSH!="sqlite")echo'Name'.': <input name="name" value="'.h($J["name"]).'" data-maxlength="64" autocapitalize="off"> ';echo
doc_link(array('sql'=>"create-table-check-constraints.html",'mariadb'=>"constraint/",'pgsql'=>"ddl-constraints.html#DDL-CONSTRAINTS-CHECK-CONSTRAINTS",'mssql'=>"relational-databases/tables/create-check-constraints",'sqlite'=>"lang_createtable.html#check_constraints",),"?"),'<p>';textarea("clause",$J["clause"]);echo'<p><input type="submit" value="Save">
';if($B!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$B));}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["trigger"])){$a=$_GET["trigger"];$B=$_GET["name"];$Ei=trigger_options();$J=(array)trigger($B,$a)+array("Trigger"=>$a."_bi");if($_POST){if(!$m&&in_array($_POST["Timing"],$Ei["Timing"])&&in_array($_POST["Event"],$Ei["Event"])&&in_array($_POST["Type"],$Ei["Type"])){$rf=" ON ".table($a);$fc="DROP TRIGGER ".idf_escape($B).(JUSH=="pgsql"?$rf:"");$_e=ME."table=".urlencode($a);if($_POST["drop"])query_redirect($fc,$_e,'Trigger has been dropped.');else{if($B!="")queries($fc);queries_redirect($_e,($B!=""?'Trigger has been altered.':'Trigger has been created.'),queries(create_trigger($rf,$_POST)));if($B!="")queries(create_trigger($rf,$J+array("Type"=>reset($Ei["Type"]))));}}$J=$_POST;}page_header(($B!=""?'Alter trigger'.": ".h($B):'Create trigger'),$m,array("table"=>$a));echo'
<form action="" method="post" id="form">
<table class="layout">
<tr><th>Time<td>',html_select("Timing",$Ei["Timing"],$J["Timing"],"triggerChange(/^".preg_quote($a,"/")."_[ba][iud]$/, '".js_escape($a)."', this.form);"),'<tr><th>Event<td>',html_select("Event",$Ei["Event"],$J["Event"],"this.form['Timing'].onchange();"),(in_array("UPDATE OF",$Ei["Event"])?" <input name='Of' value='".h($J["Of"])."' class='hidden'>":""),'<tr><th>Type<td>',html_select("Type",$Ei["Type"],$J["Type"]),'</table>
<p>Name: <input name="Trigger" value="',h($J["Trigger"]),'" data-maxlength="64" autocapitalize="off">
',script("qs('#form')['Timing'].onchange();"),'<p>';textarea("Statement",$J["Statement"]);echo'<p>
<input type="submit" value="Save">
';if($B!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$B));}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["user"])){$ha=$_GET["user"];$tg=array(""=>array("All privileges"=>""));foreach(get_rows("SHOW PRIVILEGES")as$J){foreach(explode(",",($J["Privilege"]=="Grant option"?"":$J["Context"]))as$zb)$tg[$zb][$J["Privilege"]]=$J["Comment"];}$tg["Server Admin"]+=$tg["File access on server"];$tg["Databases"]["Create routine"]=$tg["Procedures"]["Create routine"];unset($tg["Procedures"]["Create routine"]);$tg["Columns"]=array();foreach(array("Select","Insert","Update","References")as$X)$tg["Columns"][$X]=$tg["Tables"][$X];unset($tg["Server Admin"]["Usage"]);foreach($tg["Tables"]as$y=>$X)unset($tg["Databases"][$y]);$bf=array();if($_POST){foreach($_POST["objects"]as$y=>$X)$bf[$X]=(array)$bf[$X]+(array)$_POST["grants"][$y];}$rd=array();$pf="";if(isset($_GET["host"])&&($H=$f->query("SHOW GRANTS FOR ".q($ha)."@".q($_GET["host"])))){while($J=$H->fetch_row()){if(preg_match('~GRANT (.*) ON (.*) TO ~',$J[0],$A)&&preg_match_all('~ *([^(,]*[^ ,(])( *\([^)]+\))?~',$A[1],$Fe,PREG_SET_ORDER)){foreach($Fe
as$X){if($X[1]!="USAGE")$rd["$A[2]$X[2]"][$X[1]]=true;if(preg_match('~ WITH GRANT OPTION~',$J[0]))$rd["$A[2]$X[2]"]["GRANT OPTION"]=true;}}if(preg_match("~ IDENTIFIED BY PASSWORD '([^']+)~",$J[0],$A))$pf=$A[1];}}if($_POST&&!$m){$qf=(isset($_GET["host"])?q($ha)."@".q($_GET["host"]):"''");if($_POST["drop"])query_redirect("DROP USER $qf",ME."privileges=",'User has been dropped.');else{$df=q($_POST["user"])."@".q($_POST["host"]);$bg=$_POST["pass"];if($bg!=''&&!$_POST["hashed"]&&!min_version(8)){$bg=get_val("SELECT PASSWORD(".q($bg).")");$m=!$bg;}$Eb=false;if(!$m){if($qf!=$df){$Eb=queries((min_version(5)?"CREATE USER":"GRANT USAGE ON *.* TO")." $df IDENTIFIED BY ".(min_version(8)?"":"PASSWORD ").q($bg));$m=!$Eb;}elseif($bg!=$pf)queries("SET PASSWORD FOR $df = ".q($bg));}if(!$m){$Sg=array();foreach($bf
as$jf=>$qd){if(isset($_GET["grant"]))$qd=array_filter($qd);$qd=array_keys($qd);if(isset($_GET["grant"]))$Sg=array_diff(array_keys(array_filter($bf[$jf],'strlen')),$qd);elseif($qf==$df){$nf=array_keys((array)$rd[$jf]);$Sg=array_diff($nf,$qd);$qd=array_diff($qd,$nf);unset($rd[$jf]);}if(preg_match('~^(.+)\s*(\(.*\))?$~U',$jf,$A)&&(!grant("REVOKE",$Sg,$A[2]," ON $A[1] FROM $df")||!grant("GRANT",$qd,$A[2]," ON $A[1] TO $df"))){$m=true;break;}}}if(!$m&&isset($_GET["host"])){if($qf!=$df)queries("DROP USER $qf");elseif(!isset($_GET["grant"])){foreach($rd
as$jf=>$Sg){if(preg_match('~^(.+)(\(.*\))?$~U',$jf,$A))grant("REVOKE",array_keys($Sg),$A[2]," ON $A[1] FROM $df");}}}queries_redirect(ME."privileges=",(isset($_GET["host"])?'User has been altered.':'User has been created.'),!$m);if($Eb)$f->query("DROP USER $df");}}page_header((isset($_GET["host"])?'Username'.": ".h("$ha@$_GET[host]"):'Create user'),$m,array("privileges"=>array('','Privileges')));$J=$_POST;if($J)$rd=$bf;else{$J=$_GET+array("host"=>get_val("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)"));$J["pass"]=$pf;if($pf!="")$J["hashed"]=true;$rd[(DB==""||$rd?"":idf_escape(addcslashes(DB,"%_\\"))).".*"]=array();}echo'<form action="" method="post">
<table class="layout">
<tr><th>Server<td><input name="host" data-maxlength="60" value="',h($J["host"]),'" autocapitalize="off">
<tr><th>Username<td><input name="user" data-maxlength="80" value="',h($J["user"]),'" autocapitalize="off">
<tr><th>Password<td><input name="pass" id="pass" value="',h($J["pass"]),'" autocomplete="new-password">
',($J["hashed"]?"":script("typePassword(qs('#pass'));")),(min_version(8)?"":checkbox("hashed",1,$J["hashed"],'Hashed',"typePassword(this.form['pass'], this.checked);")),'</table>

';echo"<table class='odds'>\n","<thead><tr><th colspan='2'>".'Privileges'.doc_link(array('sql'=>"grant.html#priv_level"));$t=0;foreach($rd
as$jf=>$qd){echo'<th>'.($jf!="*.*"?"<input name='objects[$t]' value='".h($jf)."' size='10' autocapitalize='off'>":"<input type='hidden' name='objects[$t]' value='*.*' size='10'>*.*");$t++;}echo"</thead>\n";foreach(array(""=>"","Server Admin"=>'Server',"Databases"=>'Database',"Tables"=>'Table',"Columns"=>'Column',"Procedures"=>'Routine',)as$zb=>$Wb){foreach((array)$tg[$zb]as$sg=>$pb){echo"<tr><td".($Wb?">$Wb<td":" colspan='2'").' lang="en" title="'.h($pb).'">'.h($sg);$t=0;foreach($rd
as$jf=>$qd){$B="'grants[$t][".h(strtoupper($sg))."]'";$Y=$qd[strtoupper($sg)];if($zb=="Server Admin"&&$jf!=(isset($rd["*.*"])?"*.*":".*"))echo"<td>";elseif(isset($_GET["grant"]))echo"<td><select name=$B><option><option value='1'".($Y?" selected":"").">".'Grant'."<option value='0'".($Y=="0"?" selected":"").">".'Revoke'."</select>";else{echo"<td align='center'><label class='block'>","<input type='checkbox' name=$B value='1'".($Y?" checked":"").($sg=="All privileges"?" id='grants-$t-all'>":">".($sg=="Grant option"?"":script("qsl('input').onclick = function () { if (this.checked) formUncheck('grants-$t-all'); };"))),"</label>";}$t++;}}}echo"</table>\n",'<p>
<input type="submit" value="Save">
';if(isset($_GET["host"])){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',"$ha@$_GET[host]"));}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["processlist"])){if(support("kill")){if($_POST&&!$m){$me=0;foreach((array)$_POST["kill"]as$X){if(kill_process($X))$me++;}queries_redirect(ME."processlist=",lang(array('%d process has been killed.','%d processes have been killed.'),$me),$me||!$_POST["kill"]);}}page_header('Process list',$m);echo'
<form action="" method="post">
<div class="scrollable">
<table class="nowrap checkable odds">
',script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});");$t=-1;foreach(process_list()as$t=>$J){if(!$t){echo"<thead><tr lang='en'>".(support("kill")?"<th>":"");foreach($J
as$y=>$X)echo"<th>$y".doc_link(array('sql'=>"show-processlist.html#processlist_".strtolower($y),'pgsql'=>"monitoring-stats.html#PG-STAT-ACTIVITY-VIEW",'oracle'=>"REFRN30223",));echo"</thead>\n";}echo"<tr>".(support("kill")?"<td>".checkbox("kill[]",$J[JUSH=="sql"?"Id":"pid"],0):"");foreach($J
as$y=>$X)echo"<td>".((JUSH=="sql"&&$y=="Info"&&preg_match("~Query|Killed~",$J["Command"])&&$X!="")||(JUSH=="pgsql"&&$y=="current_query"&&$X!="<IDLE>")||(JUSH=="oracle"&&$y=="sql_text"&&$X!="")?"<code class='jush-".JUSH."'>".shorten_utf8($X,100,"</code>").' <a href="'.h(ME.($J["db"]!=""?"db=".urlencode($J["db"])."&":"")."sql=".urlencode($X)).'">'.'Clone'.'</a>':h($X));echo"\n";}echo'</table>
</div>
<p>
';if(support("kill")){echo($t+1)."/".sprintf('%d in total',max_connections()),"<p><input type='submit' value='".'Kill'."'>\n";}echo'<input type="hidden" name="token" value="',$T,'">
</form>
',script("tableCheck();");}elseif(isset($_GET["select"])){$a=$_GET["select"];$R=table_status1($a);$x=indexes($a);$o=fields($a);$hd=column_foreign_keys($a);$lf=$R["Oid"];parse_str($_COOKIE["adminer_import"],$sa);$Tg=array();$e=array();$fh=array();$Df=array();$ki=null;foreach($o
as$y=>$n){$B=$b->fieldName($n);$Ye=html_entity_decode(strip_tags($B),ENT_QUOTES);if(isset($n["privileges"]["select"])&&$B!=""){$e[$y]=$Ye;if(is_shortable($n))$ki=$b->selectLengthProcess();}if(isset($n["privileges"]["where"])&&$B!="")$fh[$y]=$Ye;if(isset($n["privileges"]["order"])&&$B!="")$Df[$y]=$Ye;$Tg+=$n["privileges"];}list($L,$sd)=$b->selectColumnsProcess($e,$x);$L=array_unique($L);$sd=array_unique($sd);$be=count($sd)<count($L);$Z=$b->selectSearchProcess($o,$x);$Cf=$b->selectOrderProcess($o,$x);$z=$b->selectLimitProcess();if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$Ni=>$J){$za=convert_field($o[key($J)]);$L=array($za?:idf_escape(key($J)));$Z[]=where_check($Ni,$o);$I=$l->select($a,$L,$Z,$L);if($I)echo
reset($I->fetch_row());}exit;}$F=$Pi=null;foreach($x
as$w){if($w["type"]=="PRIMARY"){$F=array_flip($w["columns"]);$Pi=($L?$F:array());foreach($Pi
as$y=>$X){if(in_array(idf_escape($y),$L))unset($Pi[$y]);}break;}}if($lf&&!$F){$F=$Pi=array($lf=>0);$x[]=array("type"=>"PRIMARY","columns"=>array($lf));}if($_POST&&!$m){$qj=$Z;if(!$_POST["all"]&&is_array($_POST["check"])){$ab=array();foreach($_POST["check"]as$Wa)$ab[]=where_check($Wa,$o);$qj[]="((".implode(") OR (",$ab)."))";}$qj=($qj?"\nWHERE ".implode(" AND ",$qj):"");if($_POST["export"]){cookie("adminer_import","output=".urlencode($_POST["output"])."&format=".urlencode($_POST["format"]));dump_headers($a);$b->dumpTable($a,"");$ld=($L?implode(", ",$L):"*").convert_fields($e,$o,$L)."\nFROM ".table($a);$ud=($sd&&$be?"\nGROUP BY ".implode(", ",$sd):"").($Cf?"\nORDER BY ".implode(", ",$Cf):"");$G="SELECT $ld$qj$ud";if(is_array($_POST["check"])&&!$F){$Li=array();foreach($_POST["check"]as$X)$Li[]="(SELECT".limit($ld,"\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$o).$ud,1).")";$G=implode(" UNION ALL ",$Li);}$b->dumpData($a,"table",$G);$b->dumpFooter();exit;}if(!$b->selectEmailProcess($Z,$hd)){if($_POST["save"]||$_POST["delete"]){$H=true;$ta=0;$N=array();if(!$_POST["delete"]){foreach($_POST["fields"]as$B=>$X){$X=process_input($o[$B]);if($X!==null&&($_POST["clone"]||$X!==false))$N[idf_escape($B)]=($X!==false?$X:idf_escape($B));}}if($_POST["delete"]||$N){if($_POST["clone"])$G="INTO ".table($a)." (".implode(", ",array_keys($N)).")\nSELECT ".implode(", ",$N)."\nFROM ".table($a);if($_POST["all"]||($F&&is_array($_POST["check"]))||$be){$H=($_POST["delete"]?$l->delete($a,$qj):($_POST["clone"]?queries("INSERT $G$qj"):$l->update($a,$N,$qj)));$ta=$f->affected_rows;}else{foreach((array)$_POST["check"]as$X){$mj="\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$o);$H=($_POST["delete"]?$l->delete($a,$mj,1):($_POST["clone"]?queries("INSERT".limit1($a,$G,$mj)):$l->update($a,$N,$mj,1)));if(!$H)break;$ta+=$f->affected_rows;}}}$Oe=lang(array('%d item has been affected.','%d items have been affected.'),$ta);if($_POST["clone"]&&$H&&$ta==1){$re=last_id();if($re)$Oe=sprintf('Item%s has been inserted.'," $re");}queries_redirect(remove_from_uri($_POST["all"]&&$_POST["delete"]?"page":""),$Oe,$H);if(!$_POST["delete"]){$mg=(array)$_POST["fields"];edit_form($a,array_intersect_key($o,$mg),$mg,!$_POST["clone"]);page_footer();exit;}}elseif(!$_POST["import"]){if(!$_POST["val"])$m='Ctrl+click on a value to modify it.';else{$H=true;$ta=0;foreach($_POST["val"]as$Ni=>$J){$N=array();foreach($J
as$y=>$X){$y=bracket_escape($y,1);$N[idf_escape($y)]=(preg_match('~char|text~',$o[$y]["type"])||$X!=""?$b->processInput($o[$y],$X):"NULL");}$H=$l->update($a,$N," WHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($Ni,$o),!$be&&!$F," ");if(!$H)break;$ta+=$f->affected_rows;}queries_redirect(remove_from_uri(),lang(array('%d item has been affected.','%d items have been affected.'),$ta),$H);}}elseif(!is_string($Wc=get_file("csv_file",true)))$m=upload_error($Wc);elseif(!preg_match('~~u',$Wc))$m='File must be in UTF-8 encoding.';else{cookie("adminer_import","output=".urlencode($sa["output"])."&format=".urlencode($_POST["separator"]));$H=true;$lb=array_keys($o);preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~',$Wc,$Fe);$ta=count($Fe[0]);$l->begin();$lh=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));$K=array();foreach($Fe[0]as$y=>$X){preg_match_all("~((?>\"[^\"]*\")+|[^$lh]*)$lh~",$X.$lh,$Ge);if(!$y&&!array_diff($Ge[1],$lb)){$lb=$Ge[1];$ta--;}else{$N=array();foreach($Ge[1]as$t=>$gb)$N[idf_escape($lb[$t])]=($gb==""&&$o[$lb[$t]]["null"]?"NULL":q(preg_match('~^".*"$~s',$gb)?str_replace('""','"',substr($gb,1,-1)):$gb));$K[]=$N;}}$H=(!$K||$l->insertUpdate($a,$K,$F));if($H)$l->commit();queries_redirect(remove_from_uri("page"),lang(array('%d row has been imported.','%d rows have been imported.'),$ta),$H);$l->rollback();}}}$Wh=$b->tableName($R);if(is_ajax()){page_headers();ob_start();}else
page_header('Select'.": $Wh",$m);$N=null;if(isset($Tg["insert"])||!support("table")){$Uf=array();foreach((array)$_GET["where"]as$X){if(isset($hd[$X["col"]])&&count($hd[$X["col"]])==1&&($X["op"]=="="||(!$X["op"]&&(is_array($X["val"])||!preg_match('~[_%]~',$X["val"])))))$Uf["set"."[".bracket_escape($X["col"])."]"]=$X["val"];}$N=$Uf?"&".http_build_query($Uf):"";}$b->selectLinks($R,$N);if(!$e&&support("table"))echo"<p class='error'>".'Unable to select the table'.($o?".":": ".error())."\n";else{echo"<form action='' id='form'>\n","<div style='display: none;'>";hidden_fields_get();echo(DB!=""?'<input type="hidden" name="db" value="'.h(DB).'">'.(isset($_GET["ns"])?'<input type="hidden" name="ns" value="'.h($_GET["ns"]).'">':""):"");echo'<input type="hidden" name="select" value="'.h($a).'">',"</div>\n";$b->selectColumnsPrint($L,$e);$b->selectSearchPrint($Z,$fh,$x);$b->selectOrderPrint($Cf,$Df,$x);$b->selectLimitPrint($z);$b->selectLengthPrint($ki);$b->selectActionPrint($x);echo"</form>\n";$D=$_GET["page"];if($D=="last"){$kd=get_val(count_rows($a,$Z,$be,$sd));$D=floor(max(0,$kd-1)/$z);}$gh=$L;$td=$sd;if(!$gh){$gh[]="*";$_b=convert_fields($e,$o,$L);if($_b)$gh[]=substr($_b,2);}foreach($L
as$y=>$X){$n=$o[idf_unescape($X)];if($n&&($za=convert_field($n)))$gh[$y]="$za AS $X";}if(!$be&&$Pi){foreach($Pi
as$y=>$X){$gh[]=idf_escape($y);if($td)$td[]=idf_escape($y);}}$H=$l->select($a,$gh,$Z,$td,$Cf,$z,$D,true);if(!$H)echo"<p class='error'>".error()."\n";else{if(JUSH=="mssql"&&$D)$H->seek($z*$D);$tc=array();echo"<form action='' method='post' enctype='multipart/form-data'>\n";$K=array();while($J=$H->fetch_assoc()){if($D&&JUSH=="oracle")unset($J["RNUM"]);$K[]=$J;}if($_GET["page"]!="last"&&$z!=""&&$sd&&$be&&JUSH=="sql")$kd=get_val(" SELECT FOUND_ROWS()");if(!$K)echo"<p class='message'>".'No rows.'."\n";else{$Ha=$b->backwardKeys($a,$Wh);echo"<div class='scrollable'>","<table id='table' class='nowrap checkable odds'>",script("mixin(qs('#table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true), onkeydown: editingKeydown});"),"<thead><tr>".(!$sd&&$L?"":"<td><input type='checkbox' id='all-page' class='jsonly'>".script("qs('#all-page').onclick = partial(formCheck, /check/);","")." <a href='".h($_GET["modify"]?remove_from_uri("modify"):$_SERVER["REQUEST_URI"]."&modify=1")."'>".'Modify'."</a>");$Ze=array();$nd=array();reset($L);$Bg=1;foreach($K[0]as$y=>$X){if(!isset($Pi[$y])){$X=$_GET["columns"][key($L)];$n=$o[$L?($X?$X["col"]:current($L)):$y];$B=($n?$b->fieldName($n,$Bg):($X["fun"]?"*":h($y)));if($B!=""){$Bg++;$Ze[$y]=$B;$d=idf_escape($y);$Gd=remove_from_uri('(order|desc)[^=]*|page').'&order%5B0%5D='.urlencode($y);$Wb="&desc%5B0%5D=1";$Ah=isset($n["privileges"]["order"]);echo"<th id='th[".h(bracket_escape($y))."]'>".script("mixin(qsl('th'), {onmouseover: partial(columnMouse), onmouseout: partial(columnMouse, ' hidden')});","");$md=apply_sql_function($X["fun"],$B);echo($Ah?'<a href="'.h($Gd.($Cf[0]==$d||$Cf[0]==$y||(!$Cf&&$be&&$sd[0]==$d)?$Wb:'')).'">'."$md</a>":$md);echo"<span class='column hidden'>";if($Ah)echo"<a href='".h($Gd.$Wb)."' title='".'descending'."' class='text'> ↓</a>";if(!$X["fun"]&&isset($n["privileges"]["where"])){echo'<a href="#fieldset-search" title="'.'Search'.'" class="text jsonly"> =</a>',script("qsl('a').onclick = partial(selectSearch, '".js_escape($y)."');");}echo"</span>";}$nd[$y]=$X["fun"];next($L);}}$xe=array();if($_GET["modify"]){foreach($K
as$J){foreach($J
as$y=>$X)$xe[$y]=max($xe[$y],min(40,strlen(utf8_decode($X))));}}echo($Ha?"<th>".'Relations':"")."</thead>\n";if(is_ajax())ob_end_clean();foreach($b->rowDescriptions($K,$hd)as$Xe=>$J){$Mi=unique_array($K[$Xe],$x);if(!$Mi){$Mi=array();foreach($K[$Xe]as$y=>$X){if(!preg_match('~^(COUNT\((\*|(DISTINCT )?`(?:[^`]|``)+`)\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\(`(?:[^`]|``)+`\))$~',$y))$Mi[$y]=$X;}}$Ni="";foreach($Mi
as$y=>$X){if((JUSH=="sql"||JUSH=="pgsql")&&preg_match('~char|text|enum|set~',$o[$y]["type"])&&strlen($X)>64){$y=(strpos($y,'(')?$y:idf_escape($y));$y="MD5(".(JUSH!='sql'||preg_match("~^utf8~",$o[$y]["collation"])?$y:"CONVERT($y USING ".charset($f).")").")";$X=md5($X);}$Ni.="&".($X!==null?urlencode("where[".bracket_escape($y)."]")."=".urlencode($X===false?"f":$X):"null%5B%5D=".urlencode($y));}echo"<tr>".(!$sd&&$L?"":"<td>".checkbox("check[]",substr($Ni,1),in_array(substr($Ni,1),(array)$_POST["check"])).($be||information_schema(DB)?"":" <a href='".h(ME."edit=".urlencode($a).$Ni)."' class='edit'>".'edit'."</a>"));foreach($J
as$y=>$X){if(isset($Ze[$y])){$n=$o[$y];$X=$l->value($X,$n);if($X!=""&&(!isset($tc[$y])||$tc[$y]!=""))$tc[$y]=(is_mail($X)?$Ze[$y]:"");$_="";if(preg_match('~blob|bytea|raw|file~',$n["type"])&&$X!="")$_=ME.'download='.urlencode($a).'&field='.urlencode($y).$Ni;if(!$_&&$X!==null){foreach((array)$hd[$y]as$q){if(count($hd[$y])==1||end($q["source"])==$y){$_="";foreach($q["source"]as$t=>$Bh)$_.=where_link($t,$q["target"][$t],$K[$Xe][$Bh]);$_=($q["db"]!=""?preg_replace('~([?&]db=)[^&]+~','\1'.urlencode($q["db"]),ME):ME).'select='.urlencode($q["table"]).$_;if($q["ns"])$_=preg_replace('~([?&]ns=)[^&]+~','\1'.urlencode($q["ns"]),$_);if(count($q["source"])==1)break;}}}if($y=="COUNT(*)"){$_=ME."select=".urlencode($a);$t=0;foreach((array)$_GET["where"]as$W){if(!array_key_exists($W["col"],$Mi))$_.=where_link($t++,$W["col"],$W["val"],$W["op"]);}foreach($Mi
as$ie=>$W)$_.=where_link($t++,$ie,$W);}$X=select_value($X,$_,$n,$ki);$u=h("val[$Ni][".bracket_escape($y)."]");$Y=$_POST["val"][$Ni][bracket_escape($y)];$oc=!is_array($J[$y])&&is_utf8($X)&&$K[$Xe][$y]==$J[$y]&&!$nd[$y]&&!$n["generated"];$ii=preg_match('~text|lob~',$n["type"]);echo"<td id='$u'";if(($_GET["modify"]&&$oc)||$Y!==null){$xd=h($Y!==null?$Y:$J[$y]);echo">".($ii?"<textarea name='$u' cols='30' rows='".(substr_count($J[$y],"\n")+1)."'>$xd</textarea>":"<input name='$u' value='$xd' size='$xe[$y]'>");}else{$Be=strpos($X,"<i>…</i>");echo" data-text='".($Be?2:($ii?1:0))."'".($oc?"":" data-warning='".h('Use edit link to modify this value.')."'").">$X";}}}if($Ha)echo"<td>";$b->backwardKeysPrint($Ha,$K[$Xe]);echo"</tr>\n";}if(is_ajax())exit;echo"</table>\n","</div>\n";}if(!is_ajax()){if($K||$D){$Gc=true;if($_GET["page"]!="last"){if($z==""||(count($K)<$z&&($K||!$D)))$kd=($D?$D*$z:0)+count($K);elseif(JUSH!="sql"||!$be){$kd=($be?false:found_rows($R,$Z));if($kd<max(1e4,2*($D+1)*$z))$kd=reset(slow_query(count_rows($a,$Z,$be,$sd)));else$Gc=false;}}$Sf=($z!=""&&($kd===false||$kd>$z||$D));if($Sf){echo(($kd===false?count($K)+1:$kd-$D*$z)>$z?'<p><a href="'.h(remove_from_uri("page")."&page=".($D+1)).'" class="loadmore">'.'Load more data'.'</a>'.script("qsl('a').onclick = partial(selectLoadMore, ".(+$z).", '".'Loading'."…');",""):''),"\n";}}echo"<div class='footer'><div>\n";if($K||$D){if($Sf){$Ie=($kd===false?$D+(count($K)>=$z?2:1):floor(($kd-1)/$z));echo"<fieldset>";if(JUSH!="simpledb"){echo"<legend><a href='".h(remove_from_uri("page"))."'>".'Page'."</a></legend>",script("qsl('a').onclick = function () { pageClick(this.href, +prompt('".'Page'."', '".($D+1)."')); return false; };"),pagination(0,$D).($D>5?" …":"");for($t=max(1,$D-4);$t<min($Ie,$D+5);$t++)echo
pagination($t,$D);if($Ie>0){echo($D+5<$Ie?" …":""),($Gc&&$kd!==false?pagination($Ie,$D):" <a href='".h(remove_from_uri("page")."&page=last")."' title='~$Ie'>".'last'."</a>");}}else{echo"<legend>".'Page'."</legend>",pagination(0,$D).($D>1?" …":""),($D?pagination($D,$D):""),($Ie>$D?pagination($D+1,$D).($Ie>$D+1?" …":""):"");}echo"</fieldset>\n";}echo"<fieldset>","<legend>".'Whole result'."</legend>";$cc=($Gc?"":"~ ").$kd;$vf="var checked = formChecked(this, /check/); selectCount('selected', this.checked ? '$cc' : checked); selectCount('selected2', this.checked || !checked ? '$cc' : checked);";echo
checkbox("all",1,0,($kd!==false?($Gc?"":"~ ").lang(array('%d row','%d rows'),$kd):""),$vf)."\n","</fieldset>\n";if($b->selectCommandPrint()){echo'<fieldset',($_GET["modify"]?'':' class="jsonly"'),'><legend>Modify</legend><div>
<input type="submit" value="Save"',($_GET["modify"]?'':' title="'.'Ctrl+click on a value to modify it.'.'"'),'>
</div></fieldset>
<fieldset><legend>Selected <span id="selected"></span></legend><div>
<input type="submit" name="edit" value="Edit">
<input type="submit" name="clone" value="Clone">
<input type="submit" name="delete" value="Delete">',confirm(),'</div></fieldset>
';}$id=$b->dumpFormat();foreach((array)$_GET["columns"]as$d){if($d["fun"]){unset($id['sql']);break;}}if($id){print_fieldset("export",'Export'." <span id='selected2'></span>");$Pf=$b->dumpOutput();echo($Pf?html_select("output",$Pf,$sa["output"])." ":""),html_select("format",$id,$sa["format"])," <input type='submit' name='export' value='".'Export'."'>\n","</div></fieldset>\n";}$b->selectEmailPrint(array_filter($tc,'strlen'),$e);}echo"</div></div>\n";if($b->selectImportPrint()){echo"<div>","<a href='#import'>".'Import'."</a>",script("qsl('a').onclick = partial(toggle, 'import');",""),"<span id='import'".($_POST["import"]?"":" class='hidden'").">: ","<input type='file' name='csv_file'> ",html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$sa["format"])," <input type='submit' name='import' value='".'Import'."'>","</span>","</div>";}echo"<input type='hidden' name='token' value='$T'>\n","</form>\n",(!$sd&&$L?"":script("tableCheck();"));}}}if(is_ajax()){ob_end_clean();exit;}}elseif(isset($_GET["variables"])){$O=isset($_GET["status"]);page_header($O?'Status':'Variables');$dj=($O?show_status():show_variables());if(!$dj)echo"<p class='message'>".'No rows.'."\n";else{echo"<table>\n";foreach($dj
as$y=>$X){echo"<tr>","<th><code class='jush-".JUSH.($O?"status":"set")."'>".h($y)."</code>","<td>".nl_br(h($X));}echo"</table>\n";}}elseif(isset($_GET["script"])){header("Content-Type: text/javascript; charset=utf-8");if($_GET["script"]=="db"){$Sh=array("Data_length"=>0,"Index_length"=>0,"Data_free"=>0);foreach(table_status()as$B=>$R){json_row("Comment-$B",h($R["Comment"]));if(!is_view($R)){foreach(array("Engine","Collation")as$y)json_row("$y-$B",h($R[$y]));foreach($Sh+array("Auto_increment"=>0,"Rows"=>0)as$y=>$X){if($R[$y]!=""){$X=format_number($R[$y]);if($X>=0)json_row("$y-$B",($y=="Rows"&&$X&&$R["Engine"]==(JUSH=="pgsql"?"table":"InnoDB")?"~ $X":$X));if(isset($Sh[$y]))$Sh[$y]+=($R["Engine"]!="InnoDB"||$y!="Data_free"?$R[$y]:0);}elseif(array_key_exists($y,$R))json_row("$y-$B");}}}foreach($Sh
as$y=>$X)json_row("sum-$y",format_number($X));json_row("");}elseif($_GET["script"]=="kill")$f->query("KILL ".number($_POST["kill"]));else{foreach(count_tables($b->databases())as$j=>$X){json_row("tables-$j",$X);json_row("size-$j",db_size($j));}json_row("");}exit;}else{$ci=array_merge((array)$_POST["tables"],(array)$_POST["views"]);if($ci&&!$m&&!$_POST["search"]){$H=true;$Oe="";if(JUSH=="sql"&&$_POST["tables"]&&count($_POST["tables"])>1&&($_POST["drop"]||$_POST["truncate"]||$_POST["copy"]))queries("SET foreign_key_checks = 0");if($_POST["truncate"]){if($_POST["tables"])$H=truncate_tables($_POST["tables"]);$Oe='Tables have been truncated.';}elseif($_POST["move"]){$H=move_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$Oe='Tables have been moved.';}elseif($_POST["copy"]){$H=copy_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$Oe='Tables have been copied.';}elseif($_POST["drop"]){if($_POST["views"])$H=drop_views($_POST["views"]);if($H&&$_POST["tables"])$H=drop_tables($_POST["tables"]);$Oe='Tables have been dropped.';}elseif(JUSH=="sqlite"&&$_POST["check"]){foreach((array)$_POST["tables"]as$Q){foreach(get_rows("PRAGMA integrity_check(".q($Q).")")as$J)$Oe.="<b>".h($Q)."</b>: ".h($J["integrity_check"])."<br>";}}elseif(JUSH!="sql"){$H=(JUSH=="sqlite"?queries("VACUUM"):apply_queries("VACUUM".($_POST["optimize"]?"":" ANALYZE"),$_POST["tables"]));$Oe='Tables have been optimized.';}elseif(!$_POST["tables"])$Oe='No tables.';elseif($H=queries(($_POST["optimize"]?"OPTIMIZE":($_POST["check"]?"CHECK":($_POST["repair"]?"REPAIR":"ANALYZE")))." TABLE ".implode(", ",array_map('Adminer\idf_escape',$_POST["tables"])))){while($J=$H->fetch_assoc())$Oe.="<b>".h($J["Table"])."</b>: ".h($J["Msg_text"])."<br>";}queries_redirect(substr(ME,0,-1),$Oe,$H);}page_header(($_GET["ns"]==""?'Database'.": ".h(DB):'Schema'.": ".h($_GET["ns"])),$m,true);if($b->homepage()){if($_GET["ns"]!==""){echo"<h3 id='tables-views'>".'Tables and views'."</h3>\n";$bi=tables_list();if(!$bi)echo"<p class='message'>".'No tables.'."\n";else{echo"<form action='' method='post'>\n";if(support("table")){echo"<fieldset><legend>".'Search data in tables'." <span id='selected2'></span></legend><div>","<input type='search' name='query' value='".h($_POST["query"])."'>",script("qsl('input').onkeydown = partialArg(bodyKeydown, 'search');","")," <input type='submit' name='search' value='".'Search'."'>\n","</div></fieldset>\n";if($_POST["search"]&&$_POST["query"]!=""){$_GET["where"][0]["op"]=$l->convertOperator("LIKE %%");search_tables();}}echo"<div class='scrollable'>\n","<table class='nowrap checkable odds'>\n",script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"),'<thead><tr class="wrap">','<td><input id="check-all" type="checkbox" class="jsonly">'.script("qs('#check-all').onclick = partial(formCheck, /^(tables|views)\[/);",""),'<th>'.'Table','<td>'.'Engine'.doc_link(array('sql'=>'storage-engines.html')),'<td>'.'Collation'.doc_link(array('sql'=>'charset-charsets.html','mariadb'=>'supported-character-sets-and-collations/')),'<td>'.'Data Length'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT','oracle'=>'REFRN20286')),'<td>'.'Index Length'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT')),'<td>'.'Data Free'.doc_link(array('sql'=>'show-table-status.html')),'<td>'.'Auto Increment'.doc_link(array('sql'=>'example-auto-increment.html','mariadb'=>'auto_increment/')),'<td>'.'Rows'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'catalog-pg-class.html#CATALOG-PG-CLASS','oracle'=>'REFRN20286')),(support("comment")?'<td>'.'Comment'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-info.html#FUNCTIONS-INFO-COMMENT-TABLE')):''),"</thead>\n";$S=0;foreach($bi
as$B=>$U){$gj=($U!==null&&!preg_match('~table|sequence~i',$U));$u=h("Table-".$B);echo'<tr><td>'.checkbox(($gj?"views[]":"tables[]"),$B,in_array($B,$ci,true),"","","",$u),'<th>'.(support("table")||support("indexes")?"<a href='".h(ME)."table=".urlencode($B)."' title='".'Show structure'."' id='$u'>".h($B).'</a>':h($B));if($gj){echo'<td colspan="6"><a href="'.h(ME)."view=".urlencode($B).'" title="'.'Alter view'.'">'.(preg_match('~materialized~i',$U)?'Materialized view':'View').'</a>','<td align="right"><a href="'.h(ME)."select=".urlencode($B).'" title="'.'Select data'.'">?</a>';}else{foreach(array("Engine"=>array(),"Collation"=>array(),"Data_length"=>array("create",'Alter table'),"Index_length"=>array("indexes",'Alter indexes'),"Data_free"=>array("edit",'New item'),"Auto_increment"=>array("auto_increment=1&create",'Alter table'),"Rows"=>array("select",'Select data'),)as$y=>$_){$u=" id='$y-".h($B)."'";echo($_?"<td align='right'>".(support("table")||$y=="Rows"||(support("indexes")&&$y!="Data_length")?"<a href='".h(ME."$_[0]=").urlencode($B)."'$u title='$_[1]'>?</a>":"<span$u>?</span>"):"<td id='$y-".h($B)."'>");}$S++;}echo(support("comment")?"<td id='Comment-".h($B)."'>":""),"\n";}echo"<tr><td><th>".sprintf('%d in total',count($bi)),"<td>".h(JUSH=="sql"?get_val("SELECT @@default_storage_engine"):""),"<td>".h(db_collation(DB,collations()));foreach(array("Data_length","Index_length","Data_free")as$y)echo"<td align='right' id='sum-$y'>";echo"\n","</table>\n","</div>\n";if(!information_schema(DB)){echo"<div class='footer'><div>\n";$aj="<input type='submit' value='".'Vacuum'."'> ".on_help("'VACUUM'");$zf="<input type='submit' name='optimize' value='".'Optimize'."'> ".on_help(JUSH=="sql"?"'OPTIMIZE TABLE'":"'VACUUM OPTIMIZE'");echo"<fieldset><legend>".'Selected'." <span id='selected'></span></legend><div>".(JUSH=="sqlite"?$aj."<input type='submit' name='check' value='".'Check'."'> ".on_help("'PRAGMA integrity_check'"):(JUSH=="pgsql"?$aj.$zf:(JUSH=="sql"?"<input type='submit' value='".'Analyze'."'> ".on_help("'ANALYZE TABLE'").$zf."<input type='submit' name='check' value='".'Check'."'> ".on_help("'CHECK TABLE'")."<input type='submit' name='repair' value='".'Repair'."'> ".on_help("'REPAIR TABLE'"):"")))."<input type='submit' name='truncate' value='".'Truncate'."'> ".on_help(JUSH=="sqlite"?"'DELETE'":"'TRUNCATE".(JUSH=="pgsql"?"'":" TABLE'")).confirm()."<input type='submit' name='drop' value='".'Drop'."'>".on_help("'DROP TABLE'").confirm()."\n";$i=(support("scheme")?$b->schemas():$b->databases());if(count($i)!=1&&JUSH!="sqlite"){$j=(isset($_POST["target"])?$_POST["target"]:(support("scheme")?$_GET["ns"]:DB));echo"<p>".'Move to other database'.": ",($i?html_select("target",$i,$j):'<input name="target" value="'.h($j).'" autocapitalize="off">')," <input type='submit' name='move' value='".'Move'."'>",(support("copy")?" <input type='submit' name='copy' value='".'Copy'."'> ".checkbox("overwrite",1,$_POST["overwrite"],'overwrite'):""),"\n";}echo"<input type='hidden' name='all' value=''>";echo
script("qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^(tables|views)\[/));".(support("table")?" selectCount('selected2', formChecked(this, /^tables\[/) || $S);":"")." }"),"<input type='hidden' name='token' value='$T'>\n","</div></fieldset>\n","</div></div>\n";}echo"</form>\n",script("tableCheck();");}echo'<p class="links"><a href="'.h(ME).'create=">'.'Create table'."</a>\n",(support("view")?'<a href="'.h(ME).'view=">'.'Create view'."</a>\n":"");if(support("routine")){echo"<h3 id='routines'>".'Routines'."</h3>\n";$Xg=routines();if($Xg){echo"<table class='odds'>\n",'<thead><tr><th>'.'Name'.'<td>'.'Type'.'<td>'.'Return type'."<td></thead>\n";foreach($Xg
as$J){$B=($J["SPECIFIC_NAME"]==$J["ROUTINE_NAME"]?"":"&name=".urlencode($J["ROUTINE_NAME"]));echo'<tr>','<th><a href="'.h(ME.($J["ROUTINE_TYPE"]!="PROCEDURE"?'callf=':'call=').urlencode($J["SPECIFIC_NAME"]).$B).'">'.h($J["ROUTINE_NAME"]).'</a>','<td>'.h($J["ROUTINE_TYPE"]),'<td>'.h($J["DTD_IDENTIFIER"]),'<td><a href="'.h(ME.($J["ROUTINE_TYPE"]!="PROCEDURE"?'function=':'procedure=').urlencode($J["SPECIFIC_NAME"]).$B).'">'.'Alter'."</a>";}echo"</table>\n";}echo'<p class="links">'.(support("procedure")?'<a href="'.h(ME).'procedure=">'.'Create procedure'.'</a>':'').'<a href="'.h(ME).'function=">'.'Create function'."</a>\n";}if(support("sequence")){echo"<h3 id='sequences'>".'Sequences'."</h3>\n";$oh=get_vals("SELECT sequence_name FROM information_schema.sequences WHERE sequence_schema = current_schema() ORDER BY sequence_name");if($oh){echo"<table class='odds'>\n","<thead><tr><th>".'Name'."</thead>\n";foreach($oh
as$X)echo"<tr><th><a href='".h(ME)."sequence=".urlencode($X)."'>".h($X)."</a>\n";echo"</table>\n";}echo"<p class='links'><a href='".h(ME)."sequence='>".'Create sequence'."</a>\n";}if(support("type")){echo"<h3 id='user-types'>".'User types'."</h3>\n";$Yi=types();if($Yi){echo"<table class='odds'>\n","<thead><tr><th>".'Name'."</thead>\n";foreach($Yi
as$X)echo"<tr><th><a href='".h(ME)."type=".urlencode($X)."'>".h($X)."</a>\n";echo"</table>\n";}echo"<p class='links'><a href='".h(ME)."type='>".'Create type'."</a>\n";}if(support("event")){echo"<h3 id='events'>".'Events'."</h3>\n";$K=get_rows("SHOW EVENTS");if($K){echo"<table>\n","<thead><tr><th>".'Name'."<td>".'Schedule'."<td>".'Start'."<td>".'End'."<td></thead>\n";foreach($K
as$J){echo"<tr>","<th>".h($J["Name"]),"<td>".($J["Execute at"]?'At given time'."<td>".$J["Execute at"]:'Every'." ".$J["Interval value"]." ".$J["Interval field"]."<td>$J[Starts]"),"<td>$J[Ends]",'<td><a href="'.h(ME).'event='.urlencode($J["Name"]).'">'.'Alter'.'</a>';}echo"</table>\n";$Ec=get_val("SELECT @@event_scheduler");if($Ec&&$Ec!="ON")echo"<p class='error'><code class='jush-sqlset'>event_scheduler</code>: ".h($Ec)."\n";}echo'<p class="links"><a href="'.h(ME).'event=">'.'Create event'."</a>\n";}if($bi)echo
script("ajaxSetHtml('".js_escape(ME)."script=db');");}}}page_footer();