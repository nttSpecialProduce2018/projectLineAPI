<!-- セルが空のときの対処 -->
<style type="text/css">
table.sample {
   border-collapse: separate; /* ※1 */
   empty-cells: hide; /* 空っぽのセルの枠線・背景を消す場合 */
}
</style>

<table class="sample" border="1" align="left"> <!-- css読み込み -->
<tr>
<td></td>
<td></td>
</tr>

<tr>
<td align="center">誰から</td>
<td align="center">内容</td>

<?php
//file読み込み
$filepath = 'task.csv';

//オブジェクトの生成
$file = new SplFileObject($filepath);

//CSVファイルの読み込み
$file->setFlags(SplFileObject::READ_CSV);

//1行ずつ値を取得する
foreach ($file as $line) {
  //1行の要素数を調べる
  $cnt = count($line);
  echo '<tr>';

  //リスト形式で出力する
  for($i = 0; $i < $cnt; $i++){
    $data = explode(",",$line[$i]);
    $cnt_data = count($data);
    for($j = 0; $j < $cnt_data; $j++){
      echo '<td>'.$data[$j].'</td>';
    }
  }
  echo '</tr>';
  echo '</tr>';
}

//1行ずつ値を取得する（読み込みold）
// foreach ($file as $line) {
//   //1行の要素数を調べる
//   $cnt = count($line);
//
//   echo '<tr>';
//   for($i = 0; $i < $cnt; $i++){
//       echo '<td>'.$line[$i].'</td>';
//   }
//   echo '</tr>';
//   echo '</tr>';
// }
?>
</table>
