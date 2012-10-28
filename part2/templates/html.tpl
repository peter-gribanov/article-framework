<!DOCTYPE html>
<html lang="ru">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title><?if(isset($page_title)):?><?=$page_title?> &mdash; <?endif?>Example PHP Framework</title>
  <?if(isset($keywords) && is_array($keywords)):?>
    <meta name="keywords" content="<?=self::escape(implode(', ', $keywords))?>" />
  <?endif;?>
  <?if(isset($meta_description)):?>
    <meta name="description" content="<?=self::escape($meta_description)?>" />
  <?endif;?>
</head>
  <body>
    <?=$content?>
  </body>
</html>