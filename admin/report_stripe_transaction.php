<?php
    if (isset($_POST["type"])) {
        include_once "report_functions.php";
        switch ($_POST["type"]) {
            case 'download':
                $items = [
                    "L" => [],
                    "S" => [],
                    "P" => [],
                ];

                $year = isset($_POST["year"]) ? $_POST["year"] : date("Y");
                $month = isset($_POST["month"]) ? $_POST["month"] : date("m");
                $start = $year."-".$month."-01 00:00:00";
                $end = date("Y-m-t 23:59:59", strtotime($start));
                $filerun = date('y-m-d');

                $orderRef = $orders = $discount = $paypal = [];
                $q = getQls()->SQL->query("SELECT id, order_ref, name, customer_id, amount, email, discount_code, create_at, payment_type, paypal_response
                    FROM tbl_order 
                    WHERE (paypal_response != '' OR ISNULL(paypal_response))
                        AND order_ref != ''
                        AND create_at >= '$start'
                        AND create_at <= '$end'
                    ORDER BY create_at");
                while ($r = getQls()->SQL->fetch_assoc($q)) {
                    if (strtolower($r["payment_type"]) == "stripe") {
                        $orderRef[$r["order_ref"].$r["paypal_response"]] = $r["id"];
                    } else {
                        $paypal[] = $r["id"];
                    }
                    
                    $orders[$r["id"]] = $r;
                    $orders[$r["id"]]["items"] = [];
                    $orders[$r["id"]]["paypal_txn"] = [
                        "txn" => "-",
                        "content" => "-",
                        "ga_code" => "-",
                    ];

                    $discount[strtoupper($r["discount_code"])] = $discount[strtoupper($r["discount_code"]."-done")] = [];
                }

                $cond = "metadata_order_ref != '' AND balance_transaction_id != '' AND CONCAT(metadata_order_ref, balance_transaction_id) IN ('".implode("','", array_keys($orderRef))."')";
                $q = getQls()->SQL->query("SELECT created FROM import_stripe WHERE $cond ORDER BY created LIMIT 1");
                $importStripeStart = ($r = getQls()->SQL->fetch_assoc($q)) ? $r["created"] : $start;

                $q = getQls()->SQL->query("SELECT created FROM import_stripe WHERE $cond ORDER BY created DESC LIMIT 1");
                $importStripeEnd = ($r = getQls()->SQL->fetch_assoc($q)) && $r["created"] > $end ? $r["created"] : $end;
                
                $data = $mData = [];
                $q = getQls()->SQL->query("SELECT *
                    FROM import_stripe 
                    WHERE created >= '$importStripeStart' 
                        AND created <= '$importStripeEnd'
                    ORDER BY created");
                while ($r = getQls()->SQL->fetch_assoc($q)) {
                    foreach (["gross", "fee", "net", "metadata_amount", "metadata_payment_gross"] as $col) {
                        if (isset($r[$col])) {
                            $r[$col] = round($r[$col] / 100, 4);
                        }
                    }

                    if ($r["metadata_order_ref"] != "" && $r["balance_transaction_id"] != "" && isset($orderRef[$r["metadata_order_ref"].$r["balance_transaction_id"]])) {
                        $data[$r["metadata_order_ref"].$r["balance_transaction_id"]] = $r;
                    } else {
                        $mData[strtotime($r["created"])][] = $r;
                    }
                }

                if (!empty($paypal)) {
                    $cond = "SUBSTRING_INDEX(SUBSTRING_INDEX(content, ':', -1), '-', -1) IN ('".implode("', '", $paypal)."')";
                    $q = getQls()->SQL->query("SELECT txn, content, `date`, ga_code FROM paypal_txn WHERE $cond");
                    while ($r = getQls()->SQL->fetch_assoc($q)) {
                        $explode = explode(":", $r["content"]);
                        if (isset($explode[2])) {
                            $orderID = str_replace(date("m-d-y-", strtotime($r["date"])), "", $explode[2]);
                            if (isset($orders[$orderID])) {
                                $orders[$orderID]["paypal_txn"] = [
                                    "txn" => $r["txn"],
                                    "content" => $r["content"],
                                    "amount" => $r["amount"],
                                    "ga_code" => $r["ga_code"],
                                ];
                            }
                        }
                    }
                }

                $skuBulkChildren = ['S-10', 'S-11', 'S-12', 'S-13', 'S-45', 'S-15', 'S-16', 'S-17'];
                $skuBulkHighSchool = ['S-18', 'S-19', 'S-20', 'S-21', 'S-39', 'S-40', 'S-44', 'S-46'];
                $q = getQls()->SQL->query("SELECT order_id, product_type, product_id, item_price, quantity
                    FROM tbl_order_items 
                    WHERE order_id != '' 
                        AND order_id IN ('".implode("', '", array_keys($orders))."')");
                while ($r = getQls()->SQL->fetch_assoc($q)) {
                    $orders[$r["order_id"]]["items"][] = $r;

                    $totalItems = isset($orders[$r["order_id"]]["totalItems"]) ? $orders[$r["order_id"]]["totalItems"] : 0;
                    $orders[$r["order_id"]]["totalItems"] = $totalItems + $r["quantity"];

                    $type = "";
                    switch (strtolower($r["product_type"])) {
                        case 'lesson': $type = "L"; break;
                        case 'series': $type = "S"; break;
                        case 'package': $type = "P"; break;
                    }

                    $sku = $type.'-'.$r["product_id"];
                    if (in_array($sku, $skuBulkChildren)) {
                        $countingChild = isset($orders[$r["order_id"]]["cChildren"]) ? $orders[$r["order_id"]]["cChildren"] : 0;
                        $orders[$r["order_id"]]["cChildren"] = $countingChild + $r["quantity"];
                    } else if (in_array($sku, $skuBulkHighSchool)) {
                        $countingHS = isset($orders[$r["order_id"]]["cHighSchool"]) ? $orders[$r["order_id"]]["cHighSchool"] : 0;
                        $orders[$r["order_id"]]["cHighSchool"] = $countingHS + $r["quantity"];
                    }

                    $items[$type][$r["product_id"]] = "";
                }

                $sChildrenPrice = $sHighSchoolPrice = 0;
                $q = getQls()->SQL->query("SELECT id, price FROM series WHERE id IN ('10', '18')");
                while ($r = getQls()->SQL->fetch_assoc($q)) {
                    switch ($r["id"]) {
                        case '10': $sChildrenPrice = $r["price"]; break;
                        case '19': $sHighSchoolPrice = $r["price"]; break;
                    }
                }

                $conditionLesson = " id != '' AND id IN ('".implode("', '", array_keys($items["L"]))."') ";
                $conditionSeries = " id != '' AND id IN ('".implode("', '", array_keys($items["S"]))."') ";
                $conditionPackage = " id != '' AND id IN ('".implode("', '", array_keys($items["P"]))."') ";
                $q = getQls()->SQL->query("
                    SELECT id, title, 'L' AS type FROM lessons WHERE $conditionLesson UNION 
                    SELECT id, title, 'S' AS type FROM series WHERE $conditionSeries UNION
                    SELECT id, title, 'P' AS type FROM packages WHERE $conditionPackage");
                while ($r = getQls()->SQL->fetch_assoc($q)) {
                    $items[$r["type"]][$r["id"]] = $r["type"]."-".$r["id"].": ".$r["title"];
                }

                $q = getQls()->SQL->query("SELECT code, amount, discount_type, affected, on_everything
                    FROM discounts 
                    WHERE code != '' 
                        AND UPPER(code) IN ('".implode("', '", array_keys($discount))."')");
                while ($r = getQls()->SQL->fetch_assoc($q)) {
                    $r["affected"] = explode(",", $r["affected"]);
                    $discount[strtoupper($r["code"])] = $r;
                }

                $e = 0;
                $excel = [];
                $excel[$e++][] = ["value" => "Monthly Report ".date("F Y", strtotime($start))];
                $excel[$e++][] = ["border" => ""];
                $excel[$e++] = [
                    ["value" => "Date"],
                    ["value" => "TXN"],
                    ["value" => "Item"],
                    ["value" => "Unit Price"],
                    ["value" => "Discount Code"],
                    ["value" => "Discount Amount"],
                    ["value" => "Total Received"],
                    ["value" => "Order ID"],
                    ["value" => "User ID"],
                    ["value" => "User Email"],
                ];

                foreach ($orders as $order) {
                    $stripe = [
                        "balance_transaction_id" => $order["paypal_response"] == "" ? "-" : $order["paypal_response"],
                        "created" => "-",
                        "available_on" => "-",
                        "currency" => "-",
                        "gross" => "-",
                        "fee" => "-",
                        "net" => "-",
                        "reporting_category" => "-",
                        "description" => "-",
                        "automatic_payout_id" => "-",
                        "automatic_payout_effective_at_utc" => "-",
                        "metadata_amount" => "-",
                        "metadata_discount_code" => "-",
                        "metadata_order_ref" => "-",
                        "metadata_payment_gross" => "-",
                        "metadata_user_id" => "-",
                    ];

                    if (strtolower($order["payment_type"]) == "stripe") {
                        $temp = isset($data[$order["order_ref"].$order["paypal_response"]]) ? $data[$order["order_ref"].$order["paypal_response"]] : [];
                        $stripe = array_merge($stripe, $temp);
                        $lastCreatedDate = strtotime($order["create_at"]);
                        foreach ($mData as $created => $misses) {
                            if ($created > $lastCreatedDate) {
                                break;
                            } else {
                                foreach ($misses as $miss) {
                                    $excel[$e++] = processNoRelationStripeData($miss);
                                }
                            }

                            unset($mData[$created]);
                        }
                    } else {
                        $temp = [
                            "balance_transaction_id" => $order["paypal_txn"]["txn"],
                            "metadata_payment_gross" => $order["amount"],
                        ];

                        $explode = explode(":", $order["paypal_txn"]["content"]);
                        if (isset($explode[1])) {
                            $temp["metadata_discount_code"] = $explode[1];
                        }

                        if (isset($order["paypal_txn"]["amount"])) {
                            $temp["metadata_amount"] = $order["paypal_txn"]["amount"];
                        }

                        preg_match_all('/set.[^)]+/', $order["paypal_txn"]["ga_code"], $m);
                        if (isset($m[0]) && !empty($m[0])) {
                            foreach($m[0] as $match) {
                                $explode = explode(",", $match);
                                if (isset($explode[1]) && stripos($explode[1], "currencycode") !== false && isset($explode[2])) {
                                    $temp["currency"] = trim(str_replace(["'", '"'], "", $explode[2]));
                                    break;
                                }
                            }
                        }

                        $stripe = array_merge($stripe, $temp);
                    }

                    $discountChildren = $discountHighSchool = 0;
                    if (strtoupper($stripe["metadata_discount_code"]) == "BULK") {
                        if (isset($order["cChildren"])) {
                            if ($order["cChildren"] <= 9) {
                                $category = 1;
                            } else if ($order["cChildren"] <= 30) {
                                $category = 2;
                            } else if ($order["cChildren"] <= 100) {
                                $category = 3;
                            } else if ($order["cChildren"] <= 250) {
                                $category = 4;
                            } else if ($order["cChildren"] <= 500) {
                                $category = 5;
                            } else {
                                $category = 6;
                            }

                            $disc = [0, 0, 0.2, 0.3, 0.4, 0.5, 0.6][$category];
                            $discountChildren = round($disc * $sChildrenPrice / 100, 4);
                        }

                        if (isset($order["cHighSchool"])) {
                            if ($order["cHighSchool"] <= 99) {
                                $category = 1;
                            } else if ($order["cHighSchool"]<=399) {
                                $category = 2;
                            } else if ($order["cHighSchool"]<=799) {
                                $category = 3;
                            } else {
                                $category = 4;
                            }

                            $disc = [0, 0, 0.25, 0.4, 0.5][$category];
                            $discountHighSchool = round($disc * $sHighSchoolPrice / 100, 4);
                        }
                    }

                    foreach ($order["items"] as $key => $item) {
                        $type = "";
                        switch (strtolower($item["product_type"])) {
                            case 'lesson': $type = "L"; break;
                            case 'series': $type = "S"; break;
                            case 'package': $type = "P"; break;
                        }

                        $discountAmount = 0;
                        if ($stripe["metadata_amount"] != $stripe["metadata_payment_gross"] && $stripe["metadata_discount_code"] != "") {
                            $sku = $type.'-'.$item["product_id"];
                            if (strtoupper($stripe["metadata_discount_code"]) == "BULK") {
                                if (in_array($sku, $skuBulkChildren)) {
                                    $discountAmount = $discountChildren;
                                } else if (in_array($sku, $skuBulkHighSchool)) {
                                    $discountAmount = $discountHighSchool;
                                }
                            } else if (!empty($disc = $discount[strtoupper($stripe["metadata_discount_code"])]) || !empty($disc = $discount[strtoupper($stripe["metadata_discount_code"]."-done")])) {
                                $discountAmountTemp = $disc["discount_type"] == "Fixed" ? $disc["amount"] : round($item["item_price"] * $disc["amount"] / 100, 4);
                                if (!empty($disc["on_everything"])) {
                                    $discountAmount = $discountAmountTemp;
                                    if (isset($order["totalItems"]) && !empty($order["totalItems"])) {
                                        $discountAmount = round(($discountAmountTemp / $order["totalItems"]), 4);
                                    }
                                } else if (in_array($sku, $disc["affected"])) {
                                    $discountAmount = $discountAmountTemp;
                                }
                            }
                        }

                        for ($i = 0; $i < intval($item["quantity"]); $i++) { 
                            $gross = $fee = $net = $currency = $created = $available_on = $reporting_category = $automatic_payout_id = $automatic_payout_effective_at_utc = "";
                            if ($key == 0 && $i == 0) {
                                $gross = $stripe["gross"];
                                $fee = $stripe["fee"];
                                $net = $stripe["net"];
                                $currency = $stripe["currency"];
                                $created = $stripe["created"];
                                $available_on = $stripe["available_on"];
                                $reporting_category = $stripe["reporting_category"];
                                $automatic_payout_id = $stripe["automatic_payout_id"];
                                $automatic_payout_effective_at_utc = $stripe["automatic_payout_effective_at_utc"];
                            }

                            $excel[$e++] = [
                                ["value" => $order["create_at"]],
                                ["value" => $stripe["balance_transaction_id"]],
                                ["value" => $items[$type][$item["product_id"]]],
                                ["value" => $item["item_price"]],
                                ["value" => $stripe["metadata_discount_code"]],
                                ["value" => $discountAmount],
                                ["value" => round($item["item_price"] - $discountAmount, 4)],
                                ["value" => $order["id"]],
                                ["value" => $order["customer_id"]],
                                ["value" => $order["email"]],
                            ];
                        }
                    }
                }

                foreach ($mData as $misses) {
                    foreach ($misses as $miss) {
                        $excel[$e++] = processNoRelationStripeData($miss);
                    }
                }

                $filename = generateExcel($excel, [
                    "filename" => "temp/stripe_report_".$filerun.".xlsx"
                ]);     
                header_remove();
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($filename));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filename));
                readfile($filename);
                exit(); break;

            case 'import':
                $columnRelation = [
                    "balance_transaction_id" => "balance_transaction_id",
                    "created" => "created",
                    "available_on" => "available_on",
                    "currency" => "currency",
                    "gross" => "gross",
                    "fee" => "fee",
                    "net" => "net",
                    "reporting_category" => "reporting_category",
                    "description" => "description",
                    "automatic_payout_id" => "automatic_payout_id",
                    "automatic_payout_effective_at_utc" => "automatic_payout_effective_at_utc",
                    "payment_metadata[amount]" => "metadata_amount",
                    "payment_metadata[discount_code]" => "metadata_discount_code",
                    "payment_metadata[order_ref]" => "metadata_order_ref",
                    "payment_metadata[payment_gross]" => "metadata_payment_gross",
                    "payment_metadata[user_id]" => "metadata_user_id",
                ];

                $columnInteger = ["gross", "fee", "net", "metadata_amount", "metadata_payment_gross"];
                $columnRelationCount = count($columnRelation);
                $isError = true;
                $msg = "Cannot open File";
                if (isset($_FILES["stripe"]) && $_FILES['stripe']['name'] != '' && $_FILES['stripe']['size'] != 0 && ($fh = fopen($_FILES["stripe"]["tmp_name"], "r")) !== false) {
                    $isError = false;
                    $now = gmdate("Y-m-d H:i:s");
                    
                    $idx = $columnRelationFound = 0;
                    $data = $column = [];
                    while(($row = fgetcsv($fh, null, ",")) !== false) {
                        $temp = [];
                        for($i = 0; $i < count($row); $i++) { 
                            $value = $row[$i];
                            if ($idx == 0) {
                                if (isset($columnRelation[$value])) {
                                    $columnRelationFound++;
                                    $column[$i] = $columnRelation[$value];
                                }
                            } else if (isset($column[$i])) {
                                if (in_array($column[$i], $columnInteger)) {
                                    $value = round($value * 100, 4);
                                }

                                $temp[$i] = $value;
                            }
                        }
                        
                        if ($idx == 0) { 
                            if ($columnRelationFound != $columnRelationCount) {
                                $isError = true;
                                $msg = "Wrong CSV Data Format";
                                break;
                            }
                        } else if ($columnRelationFound == count($temp)) {
                            $temp[] = $now;
                            $temp[] = $now;

                            $data[] = "'".implode("', '", $temp)."'";
                        }

                        $idx++;
                    }

                    if (!$isError && !empty($column) && !empty($data)) {
                        $column[] = "created_at";
                        $column[] = "updated_at";

                        $cClause = "(".implode(", ", $column).")";
                        $vClause = "(".implode("), (", $data).")";
                        $qls->SQL->query("INSERT INTO import_stripe $cClause 
                            VALUES $vClause 
                            ON DUPLICATE KEY 
                            UPDATE updated_at = '$now'");
                        $msg = count($data)." Rows Data Inserted";
                    }
                }                

                $alert = '<div class="alert alert-'.($isError ? "danger" : "success").'" role="alert">'.$msg.'</div>';
                break;
        }
    }
?>