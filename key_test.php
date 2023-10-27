<?php
var_dump($_ENV);
//echo("${{ secrets.GOOGLE_API_KEY }}");
foreach (getenv() as $settingKey => $settingValue) {
    echo($settingKey,$settingValue);
}
?>