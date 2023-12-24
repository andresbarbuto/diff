<?php
    $isRequiredVariableExists = function_exists("getQls") && function_exists("post") && function_exists("getFileLocation") && isset($items) && isset($today);
    if ($isRequiredVariableExists) {
        $boom = explode(':', urldecode(post('custom')));
        $user_id = $boom[0];
        $discount_used = isset($boom[1]) ? $boom[1] : "";
        $payment_gross = str_replace(",", "", post("payment_gross"));

        $purchaseRecordsValues = $purchasesValues = $purchasedItemsValues = array();
        $purchasedLessonsValues = $purchasedSeriesValues = $purchasedPackagesValues = array();
        foreach ($items as $item) {
            if (isset($item["type"])) {
                for ($i = 0; $i < $item["qty"]; $i++) { 
                    $purchaseRecordsValues["query"][] = "('$today', '$item[title]', '$user_id', '$discount_used', '".post('payment_gross')."')";                    
                    $purchasesValues["query"][] = "('$item[type]', '$item[id]', '$payment_gross', '$today', '$today', '$user_id')";

                    $itemID = "";
                    switch ($item["type"]) {
                        case 'Lesson':
                            $itemID = "L".$item["id"];
                            $purchasedItemsValues["id"][] = $item["id"];
                            $purchasedItemsValues["query"][] = "('', '$user_id', '$item[id]', '', '')";

                            $pl_lesson_id = $item["id"];
                            if (in_array($item["id"], array('598','599','604','605')) && file_exists("paypal_verify_after_the_fact.php")) {
                                include "paypal_verify_after_the_fact.php";
                            }

                            $query = "('$user_id', '$pl_lesson_id', '0', '0', '')";
                            if ($item["id"] == 85) {
                                $query .= ", ('$user_id', '598', '0', '0', '')";
                            }

                            $purchasedLessonsValues["id"][] = $item["id"];
                            $purchasedLessonsValues["query"][] = $query;
                            
                            break;

                        case 'Series':
                            $itemID = "S".$item["id"];
                            $purchasedItemsValues["id"][] = $item["id"];
                            $purchasedItemsValues["query"][] = "('', '$user_id', '', '$item[id]', '')";

                            $purchasedSeriesValues["id"][] = $item["id"];
                            $purchasedSeriesValues["query"][] = "('$user_id', '$item[id]', '0', '0', '')";
                            break;

 case 'Package':
 $itemID = "P".$item["id"];
 $purchasedItemsValues["id"][] = $item["id"];

 $purchasedItemsValues["query"][] = "('', '$user_id', '', '', '$item[id]')";

 $purchasedPackagesValues["id"][] = $item["id"];
 $purchasedPackagesValues["query"][] = "('$user_id', '$item[id]', '0', '0', '')";



    break;
     }

                    $purchaseRecordsValues["id"][] = $purchasesValues["id"][] = $itemID;
                }
            }
        }

        if (isset($purchaseRecordsValues["query"]) && !empty($purchaseRecordsValues["query"])) {
            getQls()->SQL->query("INSERT INTO purchase_records VALUES ".implode(", ", $purchaseRecordsValues["query"]));
        }

        if (isset($purchasesValues["query"]) && !empty($purchasesValues["query"])) {
            getQls()->SQL->query("INSERT INTO purchases (item_type, item_id, amount, created_at, updated_at, user_id) VALUES ".implode(", ", $purchasesValues["query"]));
        }

        if (isset($purchasedItemsValues["query"]) && !empty($purchasedItemsValues["query"])) {
            getQls()->SQL->query("INSERT INTO purchased_items (id, user_id, lesson, series, package) VALUES ".implode(", ", $purchasedItemsValues["query"]));
        }

        if (isset($purchasedLessonsValues["query"]) && !empty($purchasedLessonsValues["query"])) {
            getQls()->SQL->query("INSERT INTO purchased_lessons VALUES ".implode(", ", $purchasedLessonsValues["query"]));
        } 

        if (isset($purchasedSeriesValues["query"]) && !empty($purchasedSeriesValues["query"])) {
            getQls()->SQL->query("INSERT INTO purchased_series VALUES ".implode(", ", $purchasedSeriesValues["query"]));
        }

        if (isset($purchasedPackagesValues["query"]) && !empty($purchasedPackagesValues["query"])) {
            getQls()->SQL->query("INSERT INTO purchased_packages VALUES ".implode(", ", $purchasedPackagesValues["query"]));
        }
    }
?>