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

//ライブラリ読み込み
require_once('./LINEBotTiny.php');

//トークンなど
$channelAccessToken = '<Token>';
$channelSecret = '<Channel>';

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

                    //byeコマンド、主にリセット
                    if ($command == "bye") {
                        $text = "byebye";

                        $count = 0;
                        fseek($fp, 0);
                        fputs($fp, $count);
                        fclose($fp);
                    }

                    else if ($command == "シフト登録") {
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

                    //とりあえず今はまとめておく

                    else if ($command == "検索") {
                        $text = "調べたい内容を入力してください".PHP_EOL."Ex. リレーションシップ";
                        $count = 3;
                        fseek($fp, 0);
                        fputs($fp, $count);
                        fclose($fp);
                    }

                    else if ($command == "HELP" || $command == "help" || $command == "へるぷ" || $command == "ヘルプ" ) {  //そのうち自動化とかしたいよね
                      $text = "名前とHELP内容を自由に記載してください".PHP_EOL."Ex. ごろう,検証PCのリモートデスクトップのアドレスを教えてください";
                      $count = 4;
                      fseek($fp, 0);
                      fputs($fp, $count);
                      fclose($fp);
                    }

                    else {
                        switch ($count) {
                            case 1:
                                $text = "登録完了！";
                                $count = 9;
                                fseek($fp, 0);
                                fputs($fp, $count);
                                fclose($fp);

                                //ファイルへの書き込み処理
                                $month = substr($command, 0,5);  // 月取得
                                $array = array(substr($command, 6));
                                $file = fopen("../".$month.".csv", a);
                                if( $file ){
                                    var_dump( fputcsv($file, $array) );
                                }
                                break;

                            $str = "";
                            case 2:
                                $name = substr($command, 6);  //入力した値から名前のみを抽出したい
                                $month = substr($command, 0,5);  // 月取得
                                $file = new SplFileObject("../".$month.".csv");
                                $file->setFlags(SplFileObject::READ_CSV);
                                $i = 0;  //擬似ループ用
                                $successCode = 0;  //最終的な判断用
                                foreach($file as $f){
                                    $fields[] = $f;
                                    $str = $fields[$i][0];  //データ挿入

                                    if(strpos($str, $name)) {  //linesの中に入力した名前があったら
                                        $text = $fields[$i][0];
                                        $successCode = 200;
                                        break;  //caseを抜ける
                                    }

                                    else
                                      $successCode = 404;

                                    $i++;
                                }

                                if ($successCode == 404)
                                  $text = "見つかりませんでした";

                                //連続検索できるのでこれはない方がいい
//                                $count++;
//                                fseek($fp, 0);
//                                fputs($fp, $count);
//                                fclose($fp);
                                break;

                                //とりあえず今はまとめておく

                                case 3:
                                  $keyword = $command;
                                  $file = new SplFileObject("../knowledge.csv");
                                  $file->setFlags(SplFileObject::READ_CSV);
                                  $i = 0;  //擬似ループ用
                                  $successCode = 0;//最終的な判断用
                                  foreach($file as $f){
                                      $fields[] = $f;
                                      $str = $fields[$i][0];  //データ挿入
                                      // $str2 = $fields[$i][1];  //データ挿入

                                      if($str == $keyword) {  //linesの中に入力した名前があったら
                                          $text = $fields[$i][1];
                                          $successCode = 200;
                                          break;  //caseを抜ける
                                      }

                                      else
                                        $successCode = 404;

                                      $i++;
                                    }

                                    if ($successCode == 404)
                                      $text = "見つかりませんでした";

                                    break;

                                  case 4:  //メールとか、先輩のタスクリスト
                                    $text = "登録完了！";
                                    $count = 9;
                                    fseek($fp, 0);
                                    fputs($fp, $count);
                                    fclose($fp);

                                    //ファイルへの書き込み処理
                                    $array = array($command);
                                    $file = fopen("task.csv", a);
                                    if( $file ){
                                        var_dump( fputcsv($file, $array) );
                                    }
                                    break;

                                    //以下、メール機能の産物

                                    // mb_language("Japanese");
                                    // mb_internal_encoding("UTF-8");
                                    // $to = 'nttSpecialProduce2018@gmail.com';
                                    // $title = 'test';
                                    // $content = $command;
                                    // $headers = 'From: from@hoge.co.jp' . "\r\n";
                                    // if(mb_send_mail($to, $title, $content, $headers)){
                                    //   $text = "メールを送信しました";
                                    // }
                                    //
                                    // else {
                                    //   $text =  "メールの送信に失敗しました";
                                    // };

                                    // require_once ( 'PHPMailerAutoload.php' );
                                    // $subject = "タイトル";
                                    // $body = "メール本文";
                                    // $fromname = "誰から";
                                    // $from = "from@from.com";
                                    // $smtp_user = "nttSpecialProduce2018@gmail.com";
                                    // $smtp_password = "nttSpecial2018";
                                    //
                                    // $mail = new PHPMailer();
                                    // $mail->IsSMTP();
                                    // $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
                                    // $mail->SMTPAuth = true;
                                    // $mail->CharSet = 'utf-8';
                                    // $mail->SMTPSecure = 'tls';
                                    // $mail->Host = "smtp.gmail.com";
                                    // $mail->Port = 587;
                                    // $mail->IsHTML(false);
                                    // $mail->Username = $smtp_user;
                                    // $mail->Password = $smtp_password;
                                    // $mail->SetFrom($smtp_user);
                                    // $mail->From     = $fromaddress;
                                    // $mail->Subject = $subject;
                                    // $mail->Body = $body;
                                    // $mail->AddAddress($to);
                                    //
                                    // if( !$mail -> Send() ){
                                    //     $message  = "Message was not sent<br/ >";
                                    //     $message .= "Mailer Error: " . $mailer->ErrorInfo;
                                    // } else {
                                    //     $message  = "Message has been sent";
                                    // }
                                    //
                                    // echo $message;
                                    break;

                            default:
                                $text = "無効なコマンドです、やりなおしてください";
                                break;
                        }
                    }

                    //ここまで、これ以降は編集する必要なし
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $text
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
