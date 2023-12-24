<?php
define('QUADODO_IN_SYSTEM', true);
$local = $_SERVER['REMOTE_ADDR'] =='::1' ? 1 : 0;
require_once('includes/header.php');

echo '<title>Certificate Request</title>';

include 'start.php';
include 'vendor/Smarty-3.1.32/libs/Smarty.class.php';
include 'validators.php';
include_once ("includes/aws/client.php");

const FIELD_ERROR='Invalid Form Field';
const BCC ='notify@catechismclass.com';
//const BCC='catechismclass@gmail.com';

const TEST_VALIDATION=1;
const LOGGING_ACTIVATED=1;
const LOG_FILE='BaptismRequestLog.txt';

const BAPTISM_LESSON = 85;
const STANDARD_FEE = 598;
const EXPEDITED_FEE = 599;
const MAILING_FEE = 604;
const INTERNATIONAL_MAILING_FEE = 605;

// Wahyudi Edit (2022-07-08) Add Processing Fee
const PROCESSING_FEE = 608;

const TEMPLATE_DIR = 'templates/CertRequest';
$smarty = new Smarty();
$smarty->setTemplateDir(TEMPLATE_DIR);

const CERT_REQUEST_TYPES = [
    2 => ['name' => 'baptism', 'lesson' => 85, 'text' => 'Baptism'],
    5 => ['name' => 'natural-family-planning', 'series' => 9, 'text' => 'Natural Family Planning Preparation'],
    6 => ['name' => 'marriage_only', 'package' => 23, 'text' => 'Marriage Preparation - Pre Cana and NFP'],
    7 => ['name' => 'communion_godparent', 'lesson' => 725, 'text' => 'Godparent for First Communion'],
    8 => ['name' => 'confirmation_sponsor', 'lesson' => 724, 'text' => 'Confirmation Sponsor Preparation'],
    9 => ['name' => 'quinceanera_prep', 'series' => 59, 'text' => 'Quinceañera Preparation'],
    10 => ['name' => 'pre_cana', 'lesson' => 234, 'text' => 'Pre Cana Preparation']
];

error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($local) {
    $qls->user_info = fetch_user_info('kalinsteve');
}

if (LOGGING_ACTIVATED) {
    log_message('Request started at '  . date("Y-m-d H:i:s") . ' method: ' . $_SERVER['REQUEST_METHOD']);
}

do {
    if (!isset($qls) || !isset($qls->user_info) || !array_key_exists('username', $qls->user_info)) {  //user not logged in
        $smarty->assign('status', CurrentStatus::NotAuthorized);
        $smarty->display('backdrop.tpl');
        continue;
    }

    define('REQUEST_TYPE', getRequestType());
    if (!array_key_exists(REQUEST_TYPE, CERT_REQUEST_TYPES)) {
        $smarty->assign('status', CurrentStatus::InvalidRequestType);
        $smarty->display('backdrop.tpl');
        continue;
    }

    $smarty->assign('program', CERT_REQUEST_TYPES[REQUEST_TYPE]['text']);
    if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'])) {
        $smarty->assign('lesson', CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson']);
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['series'])) {
        $smarty->assign('series', CERT_REQUEST_TYPES[REQUEST_TYPE]['series']);
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['package'])) {
        $smarty->assign('package', CERT_REQUEST_TYPES[REQUEST_TYPE]['package']);
    } 

    define('COUNTRIES', getCountries());
    define('STATES', getStates('USA'));
    define('PROVINCES', getStates('CAN'));
    // define('HOLIDAY_MESSAGE', getHolidayMessage());

    /**
     * @var PURCHASED_ITEMS array
     */
    // Wahyudi Edit (2022-03-31) Add function to get fees
    $purchasedFees = getPurchasedFees($qls);
    define('PURCHASED_ITEMS', getPurchasedItems($qls, $purchasedFees));

    // Wahyudi Edit (2022-03-31) Assign fees to constant
    define('PURCHASED_FEES', $purchasedFees);
    define('QUIZZES', getQuizzes($qls));

    //  $purchasedItems=getPurchasesItems($qls);
    $status = getStatus($qls);
    if (LOGGING_ACTIVATED){
        log_message('function getStatus returned status ' . $status);
    }

    if ($status != CurrentStatus::TicketNotSubmitted)  {
        $smarty->assign('status', $status);
        $smarty->display('backdrop.tpl');
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET'){
        getHtmlData($smarty, $qls);
        $smarty->display('cert-request.tpl');
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $result = checkForm();
        if (LOGGING_ACTIVATED){
            log_message('data validation returned status ' . $status);
        }

        if ($result['status'] != CurrentStatus::ValidForm) {
            $smarty->assign('status',$result['status']);
            $smarty->assign('error_message',$result['message']);
            $smarty->display('backdrop.tpl');
            continue;
        }

        // Wahyudi Edit (2022-03-31) Add Qls variable as parameter
        $status=processCertRequest($qls);
        if (LOGGING_ACTIVATED){
            log_message('function processCertRequest returned status ' . $status);
        }

        if ($status == CurrentStatus::CertificateSubmitted) {
            $status = sendEmails();
            if (LOGGING_ACTIVATED){
                log_message('function sendEmails returned status ' . $status);
            }
        }

        //TODO
        if ($status == CurrentStatus::EmailSent) {
            $status = CurrentStatus::SuccessfulCompletion;
 //           $certificateSubmitted = $smarty->fetch('certificate_submitted.tpl');
        }

        $smarty->assign('status',$status);
        $smarty->display('backdrop.tpl');
        continue;
    }

} while (false);

include 'info_columns.php';
include 'bottom.php';
exit;

function getRequestType() {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $_SESSION['request_type'] = $_GET['nature'] ?? '';
    }
    return $_SESSION['request_type'];
}

function getHtmlData(Smarty $smarty, qls $qls) {    //TODO constants can be used directly
    $smarty->assign('user_info', $qls->user_info);
    $smarty->assign('countries', COUNTRIES);
    $smarty->assign('states', STATES);
    $smarty->assign('provinces', PROVINCES);
    
    $hide = 'style="display:none"';
    $country_id = $qls->user_info['country_id'];
    $display = [
        'all' => $hide,
        'usa' => $hide,
        'can' => $hide,
    ];
 
    if ($country_id == 'USA') {
        $display['all'] = $display['usa']= '';
    } elseif ($country_id == 'CAN') {
        $display['all'] = $display['can']= '';
    }

    $smarty->assign('north_america', $country_id == 'USA' || $country_id == 'CAN');
    $smarty->assign('display', $display);

    // Wahyudi Edit (2022-04-13) Add purchased fees that will be used
    $smarty->assign('purchased_fees', PURCHASED_FEES);
}

function getCountries() {
    $countries=array();
    $sql = 'SELECT country_name AS name, alpha3_code AS code
            FROM countries
            ORDER BY fav,alpha3_code';

    $result=getQls()->SQL->query($sql) or die(getQls()->SQL->error());
    while ($row = getQls()->SQL->fetch_assoc($result)) {
        $code = $row['code'];  
        $countries[$code]= $row['name'];
    }

    return $countries;
}

function getStates($country_code) {
    /**
     * @var qls $qls
     */
    $states=[];
    $qls=getQls();

    // Wahyudi Edit (2022-02-02) Escape variable
    $country_code = $qls->SQL->real_escape_string($country_code);
    $sql = "SELECT state_code, state_name 
            FROM states 
            WHERE country_code = '$country_code'";
    $result=$qls->SQL->query($sql);
    while ($row=$qls->SQL->fetch_assoc($result)) {
        $code = $row['state_code'];
        $states[$code]=$row['state_name'];
    }

    return $states;
}

function getHolidayMessage() {
    ob_start();
    include 'holiday_support.php';
    $holidayMessage = ob_get_contents();
    ob_end_clean();

    return $holidayMessage;
}

function getStatus($qls) {
    if (!array_key_exists(REQUEST_TYPE, CERT_REQUEST_TYPES)) {
        return CurrentStatus::InvalidRequestType;
    } else {
        $item = "";
        if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'])) {
            $item = "L".CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
        } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['series'])) {
            $item = "S".CERT_REQUEST_TYPES[REQUEST_TYPE]['series'];
        } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['package'])) {
            $item = "P".CERT_REQUEST_TYPES[REQUEST_TYPE]['package'];
        } 

        $ticket_status = getTicketStatus($qls);
        if ($ticket_status != CurrentStatus::TicketNotSubmitted) {
            return $ticket_status;
        } elseif (!array_key_exists($item, PURCHASED_ITEMS)) {
            return CurrentStatus::NotFoundPurchasedLesson;
        } elseif (!validQuizExists($qls)) {
            return CurrentStatus::NotFoundValidQuiz;
        } elseif (!deliveryFeeExists()) {
            return CurrentStatus::NotFoundDeliveryFee;
        }
    }

    return CurrentStatus::TicketNotSubmitted;
}

function getTicketStatus(qls $qls) {
    // Wahyudi Edit (2022-02-09) Check Last Purchase 
    $item = "";
    if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'])) {
        $item = "L".CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['series'])) {
        $item = "S".CERT_REQUEST_TYPES[REQUEST_TYPE]['series'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['package'])) {
        $item = "P".CERT_REQUEST_TYPES[REQUEST_TYPE]['package'];
    } 

    if (isset(PURCHASED_ITEMS[$item])) {
        $request_type = REQUEST_TYPE;
        $userid = $qls->user_info['id'];
        $lastPurchase = strtotime(PURCHASED_ITEMS[$item]["dt"]);
        $sql = "SELECT cert_time, UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 2 WEEK)) AS deadline
                FROM support_tickets
                WHERE user_id = '$userid'
                    AND nature_of_error = $request_type
                    AND (standard_fee != '' OR expedited_fee != '')
                    AND cert_time > '$lastPurchase'
                ORDER BY cert_time DESC
                LIMIT 1";
        $result=$qls->SQL->query($sql) or die($qls->SQL->error());
        if ($row=$qls->SQL->fetch_assoc($result)) {
            $cert_time = $row['cert_time'];
            $deadline = $row['deadline'];
            return ($cert_time > $deadline) ? CurrentStatus::TicketActive : CurrentStatus::ExpiredPurchase;
        } else {
            return CurrentStatus::TicketNotSubmitted;
        }
    }

    return CurrentStatus::NotFoundValidQuiz;
}

function validQuizExists($qls) {
    $pass_score = REQUEST_TYPE == 2 ? 1 : 0.9;
    $isQuizExists = $isPassed = false;
    if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'])) {
        $lesson = CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
        $isQuizExists = array_key_exists($lesson, QUIZZES);
        if ($isQuizExists) {
            $isPassed = QUIZZES[$lesson]['quiz_score'] >= $pass_score;
        }
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['series'])) {
        $isQuizExists = $isPassed = true;
        $series = CERT_REQUEST_TYPES[REQUEST_TYPE]['series'];
        $sql = "SELECT lesson_id FROM lessons_series WHERE series_id = $series";
        $result=$qls->SQL->query($sql) or die($qls->SQL->error());
        while (($row=$qls->SQL->fetch_assoc($result)) && $isQuizExists && $isPassed) {
            if ($isQuizExists = $isQuizExists && array_key_exists($row["lesson_id"], QUIZZES)) {
                $isPassed = QUIZZES[$row["lesson_id"]]['quiz_score'] >= $pass_score;
            }
        }
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['package'])) {
        $isQuizExists = $isPassed = true;
        $package = CERT_REQUEST_TYPES[REQUEST_TYPE]['package'];
        $sql = "SELECT lesson_id FROM lessons_packages WHERE package_id = $package";
        $result=$qls->SQL->query($sql) or die($qls->SQL->error());
        while (($row=$qls->SQL->fetch_assoc($result)) && $isQuizExists && $isPassed) {
            if ($isQuizExists = $isQuizExists && array_key_exists($row["lesson_id"], QUIZZES)) {
                $isPassed = QUIZZES[$row["lesson_id"]]['quiz_score'] >= $pass_score;
            }
        }

        if ($isQuizExists) {
            $series = array();
            $sql = "SELECT series_id FROM packages_series WHERE package_id = $package";
            $result=$qls->SQL->query($sql) or die($qls->SQL->error());
            while ($row=$qls->SQL->fetch_assoc($result)) {
                $series[] = $row["series_id"];
            }

            $sql = "SELECT lesson_id FROM lessons_series WHERE series_id != '' AND series_id IN ('".implode("', '", $series)."')";
            $result=$qls->SQL->query($sql) or die($qls->SQL->error());
            while (($row=$qls->SQL->fetch_assoc($result)) && $isQuizExists && $isPassed) {
                if ($isQuizExists = $isQuizExists && array_key_exists($row["lesson_id"], QUIZZES)) {
                    $isPassed = QUIZZES[$row["lesson_id"]]['quiz_score'] >= $pass_score;
                }
            }
        }
    } 

    if ($isQuizExists && $isPassed) {
        return true;
    }
            
    return false;
}

function deliveryFeeExists() {
    $item = "";
    if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'])) {
        $item = "L".CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['series'])) {
        $item = "S".CERT_REQUEST_TYPES[REQUEST_TYPE]['series'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['package'])) {
        $item = "P".CERT_REQUEST_TYPES[REQUEST_TYPE]['package'];
    } 

    switch ($item) {
        case 'L85': return true; break;
        case 'L724': 
        case 'L725': 
        case 'L234': 
        case 'S9': 
        case 'P23':
        case 'S59':  
            // Wahyudi Edit (2022-07-08) Check Processing Fee
            return array_key_exists(PROCESSING_FEE, PURCHASED_FEES); 
            break;
    }

    return false;
} 


// Wahyudi Edit (2022-03-31) Add pointer variable
function getPurchasedItems($qls, &$purchasedFees = array()) {
    $userid = $qls->user_info['id'];
    $condition = " AND 1!=1 ";
    if (REQUEST_TYPE == 2) {
        $condition = " AND ((item_type = 'Package' AND item_id IN (7, 14, 17, 18, 19, 20, 21, 22, 24)) 
            OR (item_type = 'Series' AND item_id IN (6, 64)) 
            OR (item_type = 'Lesson' AND item_id IN (9, 28, 85))
        ) ";
    } else {
        if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'])) {
            $item = CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
            $condition = " AND item_type = 'Lesson' AND item_id = $item ";
        } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['series'])) {
            $item = CERT_REQUEST_TYPES[REQUEST_TYPE]['series'];
            $condition = " AND item_type = 'Series' AND item_id = $item ";
        } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['package'])) {
            $item = CERT_REQUEST_TYPES[REQUEST_TYPE]['package'];
            $condition = " AND item_type = 'Package' AND item_id = $item ";
        } 
    }

    // Wahyudi Edit (2022-03-31) Remove fees from this query, they will be fetched in getPurchasedFees
    $purchasedItems = array();
    $sql = "SELECT item_type, item_id, COUNT(*) AS cnt, MAX(id) AS maxId, MAX(created_at) AS maxDt
            FROM purchases
            WHERE user_id = $userid
                AND DATE(created_at) >= '2018-10-01'
                $condition
            GROUP BY item_id";  // AND  created_at < DATE_SUB(NOW(), INTERVAL 2 WEEK)   
    $result = $qls->SQL->query($sql) or die($qls->SQL->error());
    while ($row=$qls->SQL->fetch_assoc($result)){
        $idx = "L";
        switch ($row["item_type"]) {
            case 'Series': 
                $idx = "S";
                break;
            
            case 'Package': 
                $idx = "P";
                break;
        }

        $idx .= $row["item_id"];
        $purchasedItems[$idx] = [
            'cnt' => $row['cnt'],
            'id' => $row['maxId'],
            'dt' => $row['maxDt']
        ];

        // Wahyudi Edit (2022-04-05) Replace purchases id for standard fee
        if ($idx == "L85") {
            // Wahyudi Edit (2022-04-13) Add lesson title
            $sql = "SELECT title FROM lessons WHERE id = 598 ";
            $rs = $qls->SQL->fetch_assoc($qls->SQL->query($sql));

            $purchasedFees[598] = [
                'cnt' => isset($purchasedFees[598]) ? $purchasedFees[598]['cnt'] : 1,
                'purchased_lessons_id' => isset($purchasedFees[598]) ? $purchasedFees[598]['purchased_lessons_id'] : '',
                'id' => $row["maxId"],
                'title' => $rs["title"],
            ];
        }
    }

    return $purchasedItems;
}

// Wahyudi Edit (2022-03-31) add function to fetch fees
function getPurchasedFees($qls) {
    $userid = $qls->user_info['id'];
    $item = "";
    if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'])) {
        $item = "L".CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['series'])) {
        $item = "S".CERT_REQUEST_TYPES[REQUEST_TYPE]['series'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['package'])) {
        $item = "P".CERT_REQUEST_TYPES[REQUEST_TYPE]['package'];
    } 

    switch ($item) {
        default:
        case 'L85': 
            $fees = ['598', '599', '604', '605'];
            break;

        case 'L724': 
        case 'L725': 
        case 'L234': 
        case 'S9': 
        case 'P23': 
        case 'S59': 
            $fees = ['599', '604', '605', '608'];
            break;
    }

    // Wahyudi Edit (2022-07-08) Add Processing Fee
    $condition = " AND lesson_id IN ('".implode("', '", $fees)."') ";
    $purchasedFees = array();
    $sql = "SELECT lesson_id, COUNT(id) AS cnt, MIN(id) AS minId
            FROM purchased_lessons
            WHERE user_id = $userid 
                AND lesson_id != ''
                $condition
            GROUP BY lesson_id";
    $result = $qls->SQL->query($sql);
    while ($row = $qls->SQL->fetch_assoc($result)){
        $limit = $row['cnt'] - 1;
        $res = $qls->SQL->fetch_assoc(
            $qls->SQL->query(
                "SELECT id
                FROM purchases
                WHERE user_id = $userid
                    AND item_type = 'Lesson'
                    AND item_id = $row[lesson_id] 
                ORDER BY created_at DESC
                LIMIT $limit, 1"
            )
        );

        // if (!$res && $row["lesson_id"] == 598) {
        //     $res = $qls->SQL->fetch_assoc(
        //         $qls->SQL->query(
        //             "SELECT id
        //             FROM purchases
        //             WHERE user_id = $userid
        //                 AND item_type = 'Lesson'
        //                 AND item_id = 85 
        //             ORDER BY created_at DESC
        //             LIMIT 0, 1"
        //         )
        //     );
        // }

        $purchasedFees[$row['lesson_id']] = [
            'cnt' => $row['cnt'],
            'purchased_lessons_id' => $row['minId'], // id for voided
            'id' => !empty($res) ? $res["id"] : '', // id for support_tickets table
            'title' => '',
        ];
    }

    // Wahyudi Edit (2022-04-13) Add lesson title
    $sql = "SELECT id, title FROM lessons WHERE id != '' AND id IN ('".implode("', '", array_keys($purchasedFees))."') ";
    $result = $qls->SQL->query($sql);
    while ($row = $qls->SQL->fetch_assoc($result)){
        $purchasedFees[$row['id']]["title"] = $row["title"];
    }

    return $purchasedFees;
}

function getQuizzes($qls) {
    $item = "";
    if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'])) {
        $item = "L".CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['series'])) {
        $item = "S".CERT_REQUEST_TYPES[REQUEST_TYPE]['series'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['package'])) {
        $item = "P".CERT_REQUEST_TYPES[REQUEST_TYPE]['package'];
    } 

    $quizzes=[];
    // Wahyudi Edit (2022-02-09) Check Last Purchase 
    if (isset(PURCHASED_ITEMS[$item])) {
        $user_id = $qls->user_info['id'];
        $lessons = [9, 28];
        if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'])) {
            $lessons[] = CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
        } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['series'])) {
            $series = CERT_REQUEST_TYPES[REQUEST_TYPE]['series'];
            $sql = "SELECT lesson_id FROM lessons_series WHERE series_id = $series";
            $result=$qls->SQL->query($sql) or die($qls->SQL->error());
            while ($row=$qls->SQL->fetch_assoc($result)) {
                $lessons[] = $row["lesson_id"];
            }
        } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['package'])) {
            $package = CERT_REQUEST_TYPES[REQUEST_TYPE]['package'];
            $sql = "SELECT lesson_id FROM lessons_packages WHERE package_id = $package";
            $result=$qls->SQL->query($sql) or die($qls->SQL->error());
            while ($row=$qls->SQL->fetch_assoc($result)) {
                $lessons[] = $row["lesson_id"];
            }

            $series = array();
            $sql = "SELECT series_id FROM packages_series WHERE package_id = $package";
            $result=$qls->SQL->query($sql) or die($qls->SQL->error());
            while ($row=$qls->SQL->fetch_assoc($result)) {
                $series[] = $row["series_id"];
            }

            $sql = "SELECT lesson_id FROM lessons_series WHERE series_id != '' AND series_id IN ('".implode("', '", $series)."')";
            $result=$qls->SQL->query($sql) or die($qls->SQL->error());
            while ($row=$qls->SQL->fetch_assoc($result)) {
                $lessons[] = $row["lesson_id"];
            }
        } 

        $condition = " AND lesson_id != '' AND lesson_id IN ('".implode("', '", $lessons)."') ";
        $lastPurchase = PURCHASED_ITEMS[$item]["dt"];
        $sql = "SELECT lesson_id, 
                    COUNT(id) AS cnt, 
                    MAX(id) AS id, 
                    MAX(quiz_score) AS quiz_score, 
                    MAX(doc_flag) AS doc_flag, 
                    MAX(created_at) AS created_at
                FROM (
                    SELECT lesson_id, id, score * 100 AS quiz_score, doc_flag, created_at
                    FROM quiz_results
                    WHERE user_id = $user_id 
                        AND created_at >= '$lastPurchase'
                        $condition
                    ORDER BY lesson_id, id DESC
                ) t
                GROUP BY lesson_id";
        $result = $qls->SQL->query($sql) or die($qls->SQL->error());
        while ($row=$qls->SQL->fetch_assoc($result)){
            $quizzes[$row['lesson_id']] = [
                'cnt'    => $row['cnt'],
                'quiz_id' => $row['id'],
                'quiz_score' => $row['quiz_score'],
                'doc_flag' => $row['doc_flag'],
                'reading_time' => '00:00:00'
            ];
        }

        // Wahyudi Edit (2022-02-18) get reading time
        $query = $qls->SQL->query("SELECT lesson_id, 
                SEC_TO_TIME(SUM(TIME_TO_SEC(reading_time))) AS total
            FROM reading_timed 
            WHERE user_id = $user_id 
                AND created_at >= '$lastPurchase'
                $condition
            GROUP BY lesson_id, user_id");
        while ($row = $qls->SQL->fetch_assoc($query)) {
            $quizzes[$row["lesson_id"]]["reading_time"] = explode(".", $row["total"])[0];
        }
    }

    return $quizzes;
}

function checkForm() {
    try {
        validated('set', 'from_email', getUserEmail());
        validated('set', 'phone', getCertReason());
        validated('set', 'nature_of_error', REQUEST_TYPE); 
        validated('set', 'user_id', getUserId());
        validated('set', 'cert_name', getCertName());
        validated('set', 'from_email', getUserEmail());
        validated('set', 'delivery', getDeliveryMethod());
        validated('set', 'user_street', getStreet('user'));
        validated('set', 'user_city', getCity('user'));
        validated('set', 'user_state', getState('user'));
        validated('set', 'user_zip', getZipcode('user'));
        validated('set', 'parish_name', getParishName());
        validated('set', 'priest_name', getPriestName());
        validated('set', 'parish_street', getStreet('parish'));
        validated('set', 'parish_city', getCity('parish'));
        validated('set', 'parish_state', getState('parish'));
        validated('set', 'parish_zip', getZipcode('parish'));
        validated('set', 'standard_fee', getStandardFee());
        validated('set', 'expedited_fee', getExpeditedFee());
        validated('set', 'cert_time', getCertTime());
        validated('set', 'user_country_id', getCountry('user'));
        validated('set', 'parish_country_id', getCountry('parish'));
        validated('set', 'text', getCertText());

        return array('status' => CurrentStatus::ValidForm, 'message' => '');
    } catch (Exception $e) {
       return array('status' => $e->getCode(), 'message' => $e->getMessage());
    }
}

// Wahyudi Edit (2022-03-31) Add Qls variable as parameter
function processCertRequest($qls) {
    set_sql_vars();
    processTicket();
    voidFees($qls);
    return CurrentStatus::CertificateSubmitted;
}

/**
 * @param $action
 * @param null $name
 * @param null $value
 * @return array
 */
function validated($action,$name=null,$value=null) {
    static $fields = [
        'id'                     =>  ['type'=>'i','value'=> null],         // generated automatically
        'from_email'             =>  ['type'=>'s','value'=> ''],           // from form
        'text'                   =>  ['type'=>'s','value'=> ''],           // from logic
        'created_at'             =>  ['type'=>'s','value'=> ''],           // current datetime
        'updated_at'             =>  ['type'=>'s','value'=> ''],           // current datetime
        'phone'                  =>  ['type'=>'s','value'=> ''],           // from form - reason
        'nature_of_error'        =>  ['type'=>'i','value'=> ''],           // constant
        'user_id'                =>  ['type'=>'i','value'=> ''],           // from user_info
        'cert_name'              =>  ['type'=>'s','value'=> ''],          // from form
        'delivery'               =>  ['type'=>'s','value'=> ''],           // from logic
        'user_street'            =>  ['type'=>'s','value'=> ''],           // from form
        'user_city'              =>  ['type'=>'s','value'=> ''],           // from form
        'user_state'             =>  ['type'=>'s','value'=> ''],           // from form
        'user_zip'               =>  ['type'=>'s','value'=> ''],           // from form
        'parish_name'            =>  ['type'=>'s','value'=> ''],           // from form
        'priest_name'            =>  ['type'=>'s','value'=> ''],           // from form
        'parish_street'          =>  ['type'=>'s','value'=> ''],           // from form
        'parish_city'            =>  ['type'=>'s','value'=> ''],           // from form
        'parish_state'           =>  ['type'=>'s','value'=> ''],           // from form
        'parish_zip'             =>  ['type'=>'s','value'=> ''],           // from form
        'standard_fee'           =>  ['type'=>'s','value'=> ''],           // from logic
        'expedited_fee'          =>  ['type'=>'s','value'=> ''],           // from logic
        'parish_rejected'        =>  ['type'=>'s','value'=> ''],           //  constant
        'cert_time'              =>  ['type'=>'i','value'=> ''],           //  current time
        'user_country_id'        =>  ['type'=>'s','value'=> ''],           //  from form
        'parish_country_id'      =>  ['type'=>'s','value'=> '']            //  from form
    ];

    if ($action == 'set') {
        $fields[$name]['value']=$value;
        return true;
    } else {
        return $fields;
    }
}

function getUserEmail() {
    $email=trim($_POST['user-email'] ?? '');
    
    if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
       return $email;
    } else {
       throw new Exception('Invalid Email', CurrentStatus::InvalidForm); 
    }
}

function getCertReason() {
    if (REQUEST_TYPE != 2) return '';

    $cert_reason=trim($_POST['reason'] ?? '');
    if ($cert_reason == 'Parent' || $cert_reason == 'GodParent') {
        return $cert_reason;
    } else {
        throw new Exception('Invalid Role', CurrentStatus::InvalidForm);
    }
}

function getUserId() {
    $qls = getQls();
    return $qls->user_info['id'];
}

function getCertName() {
    $qls = getQls();
    return $qls->user_info['firstname'] . ' ' . $qls->user_info['lastname'] ;
}

function getDeliveryMethod() {
    // Wahyudi Edit (2022-03-31) Remove Date Check, since we only used 1 fee
    if (array_key_exists(MAILING_FEE, PURCHASED_FEES)) {
        $country = getCountry('user');
        if ($country != "USA" && $country != "CAN") {
            if (!array_key_exists(INTERNATIONAL_MAILING_FEE, PURCHASED_FEES)) {
                throw new Exception('International Mailing_Fee Not Found', CurrentStatus::NotFoundInternationalMailingFee);
            }
        }

        return 'mail';
    }

    return null;
}

function getStreet($type) {
    $index = $type.'-street';
    $street=trim($_POST[$index] ?? '');
    if ($street != '' ) {
        return ucwords($street);
    } else {
        throw new Exception('Invalid Street Address:' . $type, CurrentStatus::InvalidForm);
    }
}

function getCity($type) {
    $index = $type.'-city';
    $city = trim($_POST[$index] ?? '');

    //Not allowing numeric values - City Validation - JuanB 7-27-2020
    $pattern = "/[0-9]/i";
    $hasnumber = preg_match($pattern, $city);
    if ($city != '' && $hasnumber != 1) {
        return ucwords($city);
    } else {
        throw new Exception('Invalid City:' . $type, CurrentStatus::InvalidForm);
    }
}

function getState($type) {
    $state = ($type == 'user') ? trim($_POST['ur']) : trim($_POST['pr']) ;
    $country = ($type == 'user') ? trim($_POST['uc']) : trim($_POST['pc']) ;
    
    if ($country == 'USA' && array_key_exists($state, STATES)) {
        return $state;
    } elseif($country == 'CAN' && array_key_exists($state, PROVINCES)) {
       return $state;
    } elseif ($country != 'USA' && $country != 'CAN' && $state=='') {
       return 'xx';
    } else {
       throw new Exception('Invalid State:' . $type, CurrentStatus::InvalidForm);
    }
}

function getZipcode($type) {
    $zipcode = ($type == 'user') ? trim($_POST['uz']) : trim($_POST['pz']) ;
    $country = ($type == 'user') ? trim($_POST['uc']) : trim($_POST['pc']) ;
   
    if ($country == 'USA') {
        if (strlen($zipcode) == 5 && ctype_digit($zipcode)) {
            return $zipcode;
        } else {
            throw new UnexpectedValueException('Invalid Zipcode:' . $type, CurrentStatus::InvalidForm);
        }
    } elseif($country == 'CAN') {
        $zipcode = strtoupper($zipcode);    
        if (preg_match('/^([A-Z][0-9][A-Z])\s*([0-9][A-Z][0-9])$/',$zipcode)) {
            return $zipcode;
        } else {
            throw new UnexpectedValueException('Invalid Zipcode:' . $type, CurrentStatus::InvalidForm);
        }
    } elseif($country != 'USA' && $country != 'CAN' && $zipcode =='') {
        return 'non_US';
    } else {
        throw new UnexpectedValueException('Invalid Zipcode:' . $type, CurrentStatus::InvalidForm);
    }
}

function getParishName() {
    $parishName = trim($_POST['parish-name'] ?? '');
    if ($parishName != '' ) {
        return ucwords($parishName);
    } else {
        throw new Exception('Invalid Parish Name:', CurrentStatus::InvalidForm);
    }
}

function getPriestName() {
    $priestName = trim($_POST['priest-name'] ?? '');
    if ($priestName != '' ) {
        return ucwords($priestName);
    } else {
        throw new Exception('Invalid Priest Name:', CurrentStatus::InvalidForm);
    }
}

function getStandardFee() {
    // Wahyudi Edit (2022-07-08) Check Processing Fee
    $item = "";
    if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'])) {
        $item = "L".CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['series'])) {
        $item = "S".CERT_REQUEST_TYPES[REQUEST_TYPE]['series'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['package'])) {
        $item = "P".CERT_REQUEST_TYPES[REQUEST_TYPE]['package'];
    } 

    switch ($item) {
        default: 
        case 'L85': 
            $fee = STANDARD_FEE; 
            break;

        case 'L724': 
        case 'L725': 
        case 'L234': 
        case 'S9': 
        case 'P23':
        case 'S59':  
            $fee = PROCESSING_FEE; 
            break;
    }

    // Wahyudi Edit (2022-03-31) Remove Date Check, since we only used 1 fee
    if (array_key_exists($fee, PURCHASED_FEES)) {
        return PURCHASED_FEES[$fee]['id'];
    }

    return null;
}

function getExpeditedFee() {
    // Wahyudi Edit (2022-03-31) Remove Date Check, since we only used 1 fee
    if (array_key_exists(EXPEDITED_FEE, PURCHASED_FEES)) {
        return PURCHASED_FEES[EXPEDITED_FEE]['id'];
    }

    return null;
}

function getCountry($type) {
    if ($type == 'user') {
        $country = trim($_POST['uc']) ?? '';
    } else {
        $country = trim($_POST['pc']) ?? '';
    }

    if (array_key_exists($country, COUNTRIES)) {
        return $country;
    } else {
        throw new Exception('Invalid Country Code:' . $type, CurrentStatus::InvalidForm);
    }
}

function getCertTime() {
    $currentTime = new DateTime();
    return $currentTime->getTimestamp();
}

function getCertText() {
    $smarty = new Smarty();
    $smarty->setTemplateDir(TEMPLATE_DIR);

    $qls = getQls();
    $quizzes = QUIZZES;
    /**
     * @var $fields array
     */
    $fields=validated('get');

    // Wahyudi Edit (2022-03-31) Remove Date Check, since we only used 1 fee
    $smarty->assign('item598', array_key_exists(STANDARD_FEE, PURCHASED_FEES) ? PURCHASED_FEES[STANDARD_FEE] : ['cnt'=>0]);
    $smarty->assign('item599', array_key_exists(EXPEDITED_FEE, PURCHASED_FEES) ? PURCHASED_FEES[EXPEDITED_FEE] : ['cnt'=>0]);
    $smarty->assign('item604', array_key_exists(MAILING_FEE, PURCHASED_FEES) ? PURCHASED_FEES[MAILING_FEE] : ['cnt'=>0]);

    // Wahyudi Edit (2022-07-08) Add Processing Fee
    $smarty->assign('item608', array_key_exists(PROCESSING_FEE, PURCHASED_FEES) ? PURCHASED_FEES[PROCESSING_FEE] : ['cnt'=>0]);

    $smarty->assign('user_id', $fields['user_id']['value']);
    $smarty->assign('cert_name', $fields['cert_name']['value']);
    $smarty->assign('created_at', date("Y-m-d H:i:s T"));
    $smarty->assign('user_info', $qls->user_info);
    $smarty->assign('from_email', $fields['from_email']['value']);
    $smarty->assign('reason', $fields['phone']['value']);
    $smarty->assign('from_email', $fields['from_email']['value']);
    $smarty->assign('user_state', $fields['user_state']['value'] == '' ? 'xx' : $fields['user_state']['value']);
    $smarty->assign('user_street', $fields['user_street']['value']);
    $smarty->assign('user_city', $fields['user_city']['value']);
    $smarty->assign('user_zipcode', $fields['user_zip']['value'] == '' ? 'non_US' : $fields['user_zip']['value']);
    $smarty->assign('user_country_name', COUNTRIES[$fields['user_country_id']['value']]);
    $smarty->assign('parish_state', $fields['parish_state']['value'] == '' ? 'xx' : $fields['parish_state']['value']);
    $smarty->assign('parish_street', $fields['parish_street']['value']);
    $smarty->assign('parish_city', $fields['parish_city']['value']);
    $smarty->assign('parish_zipcode', $fields['parish_zip']['value'] == '' ? 'non-US' : $fields['parish_zip']['value'] );
    $smarty->assign('priest_name', $fields['priest_name']['value']);
    $smarty->assign('parish_name', $fields['parish_name']['value']);
    $smarty->assign('parish_country_name', COUNTRIES[$fields['parish_country_id']['value']]);
    if (REQUEST_TYPE==2) {
        $lesson = CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
        $smarty->assign('bap_quiz_cnt', $quizzes[$lesson]['cnt']);
        $smarty->assign('bap_doc_flag', $quizzes[$lesson]['doc_flag']);
        $smarty->assign('item9', array_key_exists("L9", PURCHASED_ITEMS) && 
            array_key_exists("L".$lesson, PURCHASED_ITEMS) &&
            PURCHASED_ITEMS["L9"]["dt"] >= PURCHASED_ITEMS["L".$lesson]["dt"] ? PURCHASED_ITEMS["L9"] : ['cnt'=>0]);
        $smarty->assign('quiz9', array_key_exists(9, $quizzes) ? $quizzes[9] : ['cnt' => 0]);
        $smarty->assign('item28', array_key_exists("L28", PURCHASED_ITEMS) && 
            array_key_exists("L".$lesson, PURCHASED_ITEMS) &&
            PURCHASED_ITEMS["L28"]["dt"] >= PURCHASED_ITEMS["L".$lesson]["dt"] ? PURCHASED_ITEMS["L28"] : ['cnt'=>0]);
        $smarty->assign('quiz28', array_key_exists(28, $quizzes) ? $quizzes[28] : ['cnt' => 0]);
    }

    $lessons = array();
    if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'])) {
        $lessons[] = CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['series'])) {
        $series = CERT_REQUEST_TYPES[REQUEST_TYPE]['series'];
        $sql = "SELECT lesson_id FROM lessons_series WHERE series_id = $series";
        $result=$qls->SQL->query($sql) or die($qls->SQL->error());
        while ($row=$qls->SQL->fetch_assoc($result)) {
            $lessons[] = $row["lesson_id"];
        }
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['package'])) {
        $package = CERT_REQUEST_TYPES[REQUEST_TYPE]['package'];
        $sql = "SELECT lesson_id FROM lessons_packages WHERE package_id = $package";
        $result=$qls->SQL->query($sql) or die($qls->SQL->error());
        while ($row=$qls->SQL->fetch_assoc($result)) {
            $lessons[] = $row["lesson_id"];
        }

        $series = array();
        $sql = "SELECT series_id FROM packages_series WHERE package_id = $package";
        $result=$qls->SQL->query($sql) or die($qls->SQL->error());
        while ($row=$qls->SQL->fetch_assoc($result)) {
            $series[] = $row["series_id"];
        }

        $sql = "SELECT lesson_id FROM lessons_series WHERE series_id != '' AND series_id IN ('".implode("', '", $series)."')";
        $result=$qls->SQL->query($sql) or die($qls->SQL->error());
        while ($row=$qls->SQL->fetch_assoc($result)) {
            $lessons[] = $row["lesson_id"];
        }
    } 

    $count = count($lessons);
    if ($count > 1) {
        $quiz = ['cnt' => $count, "items" => []];
        foreach ($lessons as $lesson) {
            $quiz["items"][$lesson] = $quizzes[$lesson];
        }

        $smarty->assign('quizzes', $quiz);
    } else {
        // Wahyudi Edit (2022-02-09) Add Remedial Quiz data to smarty
        $lesson = CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
        $smarty->assign('quiz', array_key_exists($lesson, $quizzes) ? $quizzes[$lesson] : ['cnt' => 0]);
    }
    
    $smarty->assign('program', CERT_REQUEST_TYPES[REQUEST_TYPE]['text']);

    return $smarty->fetch('order_text.tpl');
}

function set_sql_vars() {
    /** @var $qls qls */
    $qls=getQls();
    $smarty = new Smarty();
    $smarty->setTemplateDir(TEMPLATE_DIR);

    $fields=validated('get');
    $fields['created_at']['value'] = date("Y-m-d H:i:s");
    $fields['updated_at']['value'] = '0000-00-00 00:00:00';
    $smarty->assign('fields',$fields);
    $sql = $smarty->fetch('sql_set.tpl');

    $types=implode(array_column($fields, 'type'));
    $values=array_column($fields,'value');
    /** @var mysqlie $db */
    $db = $qls->SQL->current_layer;
    $conn=$db->connection;
    $stmt = $conn->prepare($sql) or die($conn->error);
    $stmt->bind_param($types, ...$values) or die($conn->error);
    $stmt->execute() or die($conn->error);
}

function processTicket() {
    /**  @var $qls qls */
    $smarty = new Smarty();
    $smarty->setTemplateDir(TEMPLATE_DIR);

    $qls = getQls();
    $fields=validated('get');
    $db=$qls->SQL->current_layer;
    /** @var mysqlie $db */

    $conn=$db->connection;
    $smarty->assign('fields',$fields);
    $sql=$smarty->fetch('sql_insert.tpl');
    $conn->query($sql) or die($conn->error);
  
    $insert_id=$conn->insert_id;
    validated('set','id',$insert_id);
}

// Wahyudi Edit (2022-03-31) Add Qls variable as parameter
function voidFees($qls) {
    $user_id =  $qls->user_info['id'];

    // Wahyudi Edit (2022-03-31) Add Processing Fee
    $item = "";
    if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'])) {
        $item = "L".CERT_REQUEST_TYPES[REQUEST_TYPE]['lesson'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['series'])) {
        $item = "S".CERT_REQUEST_TYPES[REQUEST_TYPE]['series'];
    } else if (isset(CERT_REQUEST_TYPES[REQUEST_TYPE]['package'])) {
        $item = "P".CERT_REQUEST_TYPES[REQUEST_TYPE]['package'];
    } 

    switch ($item) {
        default:
        case 'L85': 
            $void = [
                '598' => '600',
                '599' => '601',
                '604' => '602',
                '605' => '606',
            ];

            break;

        case 'L724': 
        case 'L725': 
        case 'L234': 
        case 'S9': 
        case 'P23': 
        case 'S59':
            $void = [
                '599' => '601',
                '604' => '602',
                '605' => '606',
                '608' => '600',
            ];

            break;
    }

    // Wahyudi Edit (2022-03-31) generate query that use purchased_lessons_id to be voided
    $query = "";
    foreach (PURCHASED_FEES as $lesson_id => $value) {
        if ($value["purchased_lessons_id"] != "") {
            $voided = $void[$lesson_id];
            $query .= " WHEN $value[purchased_lessons_id] THEN $voided ";
        }
    }

    if ($query != "") {
        $query = "UPDATE purchased_lessons SET `lesson_id` = CASE id $query ELSE `lesson_id` END";
        $qls->SQL->query($query);
    }
}

function sendEmails() {
    $fields=validated('get');
    $user_email=$fields['from_email']['value'];
    $admin_email= 'automated@catechismclass.com';
    $backup_email = 'notify@catechismclass.com' ;
    //  $backup_email = 'support@catechismclass.com' ;
    $cert_email = 'certificates@catechismclass.com';   
    // used for hard copy cert request
   
    //send email to the user, copy to Steve
    if (sendEmailMessage($fields,[$user_email], [$backup_email]) != CurrentStatus::EmailSent) {    
        //    [$backup_email], [$test_email]) != CurrentStatus::EmailSent) {
        return CurrentStatus::EmailFailed;
    }

    //send email to Matthew
    if (sendEmailMessage($fields,array($admin_email), [],[$backup_email]) != CurrentStatus::EmailSent) { 
        return CurrentStatus::EmailFailed;
    }

    // Wahyudi Edit (2022-03-31) Remove Date Check, since we only used 1 fee
    if (array_key_exists(MAILING_FEE, PURCHASED_FEES) &&
        sendEmailMessage($fields,[$cert_email],[$backup_email]) != CurrentStatus::EmailSent) {
        return CurrentStatus::EmailFailed;
    }

    return CurrentStatus::EmailSent;
}

function sendEmailMessage($fields, array $to, array $cc=[] , array $bcc=[]) {
    $smarty = new Smarty();
    $smarty->setTemplateDir(TEMPLATE_DIR);
   /**
   * @var $qls qls
   */
    $qls = getQls();
    $text=$fields['text']['value'];
      
    $smarty->assign('program', CERT_REQUEST_TYPES[REQUEST_TYPE]['text']);
    $smarty->assign('ticket_id', $fields['id']['value']);
    $smarty->assign('user_info', $qls->user_info);
    $smarty->assign('from_email', $fields['from_email']['value']);

    // Wahyudi Edit (2022-03-31) Remove Date Check, since we only used 1 fee
    $smarty->assign('item599', array_key_exists(EXPEDITED_FEE, PURCHASED_FEES) ? PURCHASED_FEES[EXPEDITED_FEE] : ['cnt'=>0]);
    $smarty->assign('text', $text);
    $subject = $smarty->fetch('emailSubject.tpl');
    $message = $smarty->fetch('email_response.tpl');

    sendEmail($to, $subject, $message, $cc, $bcc);
    
    return CurrentStatus::EmailSent;
}

function log_message($message) {
    file_put_contents(LOG_FILE, $message."\r\n", FILE_APPEND);
}

function fetch_user_info($username) {           
    $result = getQls()->SQL->select('*',
        'users',
        array('username' => array(
                '=',
                $username
            )
        )
    );

    $row = getQls()->SQL->fetch_array($result);
    return $row;
}

class CurrentStatus
{
    const SuccessfulCompletion=0;
    const TicketExpired=1;
    const TicketActive=2;
    const NotAuthorized=3;
    const TicketNotSubmitted=4;
    const CertReqMet=5;
    const NotFoundValidQuiz=6;
    const CertificateSubmitted=7;
    const EmailSent=8;
    const EmailFailed=9;
    const ValidForm=10;
    const InvalidForm=11;
    const ExpiredPurchase=12;
    const InvalidRequestType=13;
    const NotFoundDeliveryFee=14;
    const NotFoundInternationalMailingFee=15;
}