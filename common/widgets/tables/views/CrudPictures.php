<?php

use common\widgets\forms\UploadFiles;

if (isset($item->name))
    echo "<h5>" . $item->name . "</h5>";

echo UploadFiles::widget([
    'path' => $picturesPath . '/' . $item->id,
    'internetpath' => $picturesInternetPath . '/' . $item->id,
]);

echo '<p><a href="' . $ListURL . '">Назад к списку</a>';
