<?php
$arr = ['2int', 'string', 'i3nt', 'string', 'in2t', 'int3'];
$a = 'inputElement';
$length = count($arr);
$pattern = '/[2]+/';    //использовал регулярку, чтобы можно было поменять искомый элемент

for ($i = 0; $i < $length; $i++){
    if (preg_match($pattern, $arr[$i])) {
        $length++;                                  //добавляю единицу к длине, т.к. добавляю новый элемент
        for ($j = $length - 1; $j > $i; $j--) {     //перебираю элементы с конца
            $arr[$j] = $arr[$j-1];                  // сдвиг вправо (присваиваю новый индекс)
        }        
        $i++;                                       
        $arr[$i] = $a;                              //вставка значения перменной в массив
    }
}
echo '<pre>';
var_dump($arr);
echo '</pre>';
?>

