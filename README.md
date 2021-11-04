<h2> Генерация краткого url по длинному </h2>
<p>
    После получения адреса, проверяю: есть ли он в таблице, если нету, генерируется краткая ссылка и сохраняется в бд (атрибуты сущности: id, full_link, short_link). 
    Далее при попытке перейти на страницу сайта проверяется есть ли такой url в таблице, если есть, осуществляется перенаправление
</p>

<p>
    <p> frontend/models/MainForm -  модель формы</p>
    <p> frontend/controllers/SiteController -  Контроллер </p>
    <p> frontend/viws/site/index.php - Вьюшка </p>
    <p> frontend/components/UrlRule -  Редирект</p>
    <p> common/models/Urllink  </p>
    <p style="margin-left:10px"> getFullLink() - получает полную ссылку по краткой</p>
    <p style="margin-left:10px"> getRandomToken() - Генерирует случайную строку (проверка на уникальность для исключения коллизии)</p>
</p>
