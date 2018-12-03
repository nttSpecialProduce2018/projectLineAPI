<!-- 今は使ってない

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
$channelSecret = '<channel>';

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

                    // if ($command == "bye") {
                    //     $text = "byebye";
                    //     $count = 0;
                    //     fseek($fp, 0);
                    //     fputs($fp, $count);
                    //     fclose($fp);
                    // }

                    else if ($command == "検索") {
                        $text = "調べたいカテゴリの番号と内容を入力してください".PHP_EOL."1.キーワード".PHP_EOL."2.パスワード".PHP_EOL."3.準備中。。。".PHP_EOL."Ex. 1.リレーションシップ";
                        $count = 2;
                        fseek($fp, 0);
                        fputs($fp, $count);
                        fclose($fp);
                    }

                    else if ($command == "HELP" || $command == "help" || $command == "へるぷ") {  //そのうち自動化とかしたいよね
                      $text = "HELP内容を自由に記載してください";
                      $count = 3;
                      fseek($fp, 0);
                      fputs($fp, $count);
                      fclose($fp);
                    }

                    else {
                        switch ($count) {
                          $str = "";
                          case 2:
                            $keyword = "リレーションシップ";
                            $file = new SplFileObject("../knowledge.csv");
                            $file->setFlags(SplFileObject::READ_CSV);
                            $i = 0;  //擬似ループ用
                            foreach($file as $f){
                              $fields[] = $f;
                              $str = $fields[$i][0];  //データ挿入

                              if(strpos($str, $keyword)) {  //linesの中に入力した名前があったら
                                $text = $fields[$i][1];
                                $payload = array("text" => $text);
                                echo json_encode($payload);
                              }
                                $i++;
                              }
                              break;

                            // case 3:  //メール
                            //   mb_language("Japanese");
                            //   mb_internal_encoding("UTF-8");
                            //   $to = 'nttSpecialProduce2018@gmail.com';
                            //   $title = 'test';
                            //   $content = $command;
                            //   $headers = 'From: from@hoge.co.jp' . "\r\n";
                            //   if(mb_send_mail($to, $title, $content, $headers)){
                            //     $text = "メールを送信しました";
                            //   }
                            //
                            //   else {
                            //     $text =  "メールの送信に失敗しました";
                            //   };
                            //   break;

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
}; -->
