<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    $aResult = array();

    if( !isset($_POST['function']) ) { $aResult['error'] = 'No function name!'; }

    if( !isset($_POST['nodeID']) ) { $aResult['error'] = 'No node id!'; }

    if( !isset($_POST['date']) ) { $aResult['error'] = 'No date!'; }

    //if( !isset($_POST['arguments']) ) { $aResult['error'] = 'No function arguments!'; }

    if( !isset($aResult['error']) ) {

        switch($_POST['function']) {
            case "getPredictions":
                
                //LESS THAN TWO ARGUMENTS
               /*if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) ) {
                   $aResult['error'] = 'Error in arguments!';
               }
               else {*/
                   //$URL = "http://localhost/value1=".$lat."&value2=".$long;
                   $URL = "http://127.0.0.1/function=getPredictions&nodeID=".$_POST['nodeID']."&date=".$_POST['date'];

                   // Get cURL resource
                   $curl = curl_init();
                   // Set some options - we are passing in a useragent too here
                   curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => $URL,
                        CURLOPT_USERAGENT => 'Codular Sample cURL Request'
                   ));
                   curl_setopt($curl, CURLOPT_PORT, 8080);
                   // Send the request & save response to $resp
                   $resp = curl_exec($curl);
                   // Close request to clear up some resources
                   curl_close($curl);
                   $aResult['result'] = json_decode($resp);
                   //$aResult['result'] = $resp;
               //}
               break;

            default:
               $aResult['error'] = 'Not found function '.$_POST['functionname'].'!';
               break;
        }

    }
    echo json_encode($aResult);
?>