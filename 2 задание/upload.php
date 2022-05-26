<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
</head>
<body>
    <form action="" enctype="multipart/form-data" method="post">
            <input type="file" name="userfile"> 
            <input type="submit" value="Отправить" >
  </form>

  <?php
        if (isset($_FILES['userfile'])){      // пришла информация $_FILES['userfile'] - начинается обработка
            $uploadsDir = 'upload';
            if (!file_exists($uploadsDir)){      //если нет папки 'upload' - создаю 
                mkdir($uploadsDir, 0777);
            }
    
            $fileTypes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');      //проверяю  тип файла
            if(in_array($_FILES['userfile']['type'], $fileTypes)) {                                  
                $file = new SplFileObject($_FILES['userfile']['tmp_name']);                         //на основе tmp_name создаю csv объект
                $file->setFlags(SplFileObject::READ_CSV);                                           // устанавливаю флаг на чтение
                $fileName = 1;                                            
                foreach ($file as $row) {                                                           //перебираю строки
                    foreach($row as $element){
                        if ($element != null){                                                      
                            $element =iconv('WINDOWS-1251','UTF-8', $element);                      //устанавливаю кодировку utf-8 для отображения кириллицы
                            if (strpos($element, ';')){                                             // ищу разделитель
                                $findSep = ';';
                            }else{
                                $findSep = ',';
                            }
                            list($cellName, $cellInfo )= str_getcsv($element, $findSep);             //возвращаю данные из строки csv по разделителю в массиве
                            $cellName =  $fileName .  substr($cellName, strrpos($cellName, '.'));   // извлекаю символы до точки и формирую имя файла
                            $new_file = fopen($uploadsDir . '\\' . $cellName, 'w+');                //на основе массива создаю файлы
                            fwrite($new_file, $cellInfo);
                            fclose($new_file);
                            $fileName++;
                        }
                    }             
                }
                echo 'Файл успешно добавлен';
            }else {
                echo "Некорректный тип файла. Загрузите файл .csv";
            }
        }       //Какие дыры это может создать?  - Я думаю инъекции, как в SQL. В CSV может быть написан вредоносный код, который мы по итогу сохраняем как файл. 
?>

</body>
</html>

