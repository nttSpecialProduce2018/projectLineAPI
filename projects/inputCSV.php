<table border="1" align="left">
<tr>
<td>01月</td>


<?php
$filepath = '01月.csv';

//オブジェクトの生成
$file = new SplFileObject($filepath);

//CSVファイルの読み込み
$file->setFlags(SplFileObject::READ_CSV);

//1行ずつ値を取得する
foreach ($file as $line) {
  //1行の要素数を調べる
  $cnt = count($line);

  echo '<tr>';
  for($i = 0; $i < $cnt; $i++){
      echo '<td>'.$line[$i].'</td>';
  }
  echo '</tr>';
  echo '</tr>';
}
?>
</table>

<table border="1">
<tr>
<td>02月</td>

<?php
$filepath2 = '02月.csv';
$file2 = new SplFileObject($filepath2);
$file2->setFlags(SplFileObject::READ_CSV);

foreach ($file2 as $line2) {
    //1行の要素数を調べる
    $cnt2 = count($line2);

    echo '<tr>';
    for($j = 0; $j < $cnt2; $j++){
        echo '<td>'.$line2[$j].'</td>';
    }
    echo '</tr>';
}
?>
</table>
