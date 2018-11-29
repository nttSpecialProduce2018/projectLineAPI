<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once('./LINEBotTiny.php');

$channelAccessToken = '<Token>';

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {  //メッセージタイプの選択
                case 'text':
                    $text = "";  //最終的に送る（クライアントに）メッセージ
                    $command = $message['text'];  //LINEに書き込んだ内容
                    $filename = 'counter.dat'; //カウンタ読み込み作業
                    $fp = fopen($filename, "r+");
                    $count = fgets($fp,32);
                    fseek($fp, 0);
                    fputs($fp, $count);
                    
                    if ($command == "シフト登録") {
                        $text = "「◯◯月,」と入力した後、出勤可能な日付をカンマ区切りで入力し、最後に名前を入力してください".PHP_EOL."Ex.01月,1,2,3,4,5,こんどう";
                        $count = 1;
                        fseek($fp, 0);
                        fputs($fp, $count);
                        fclose($fp);
                    }
                    
                    else if ($command == "シフト確認") {
                        $text = "「◯◯月,」と入力した後、確認したい人の名前を入力してください".PHP_EOL."Ex.01月,こんどう";
                        
                        $count = 2;
                        fseek($fp, 0);
                        fputs($fp, $count);
                        fclose($fp);
                    }
                    
                    else {
                        switch ($count) {
                            case 1:
                                $text = "登録完了！";
                                $count++;
                                fseek($fp, 0);
                                fputs($fp, $count);
                                fclose($fp);
                                
                                //ファイルへの書き込み処理
                                $month = substr($command, 0,5);  // 月取得
                                $array = array(substr($command, 6));
                                $file = fopen($month.".csv", a);
                                if( $file ){
                                    var_dump( fputcsv($file, $array) );
                                }
                                break;
                            
                            $str = "";
                            case 2:
                                $name = substr($command, 6);  //入力した値から名前のみを抽出したい
                                $month = substr($command, 0,5);  // 月取得
                                $file = new SplFileObject($month.".csv");
                                $file->setFlags(SplFileObject::READ_CSV);
                                $i = 0;  //擬似ループ用
                                foreach($file as $f){
                                    $fields[] = $f;
                                    $str = $fields[$i][0];  //データ挿入
                                    
                                    if(strpos($str, $name)) {  //linesの中に入力した名前があったら
                                        $text = $fields[$i][0];
                                        $payload = array("text" => $text);
                                        echo json_encode($payload);
                                    }
                                    
                                    //                    else {
                                    //                        $text = "見つかりません";
                                    //                        $payload = array("text" => $text);
                                    //                        echo json_encode($payload);
                                    //                    }
                                    $i++;
                                }
                                
                                //連続検索できるのでこれはない方がいい
//                                $count++;
//                                fseek($fp, 0);
//                                fputs($fp, $count);
//                                fclose($fp);
                                break;
                                
                        default:
                                $text = "無効なコマンドです、最初からやりなおしてください";
                                $count = 0;
                                fseek($fp, 0);
                                fputs($fp, $count);
                                fclose($fp);
                                break;
                                
                        }
                    }
                  
                    //ここまで
                    //$Hello = "変数での返信テスト";  //ここを返信マスターにする？
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $text//$message['text']  //ここを書き換える?、ここを分岐でなんやかんや？
                            )
                        )
                    ));


                    break;
                default:
                    error_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};
