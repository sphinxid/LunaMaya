<?php
/*
 * LunaMaya - Indonesian Article Rewriter
 *
 * index.php - example how to use LunaMaya.
 *
 * by sphinxid <firman@kodelatte.com>
 *
 * Changelog
 *
 * v0.0.1 - 04/27/2014
 * Initial code.
 * This is a 3 hours dirty code for doing article rewrite in Bahasa Indonesia using word replacement method.
 *
 */

  //ini_set('error_reporting', E_ALL);
  //ini_set('display_errors', '1');
?>
<html>
<head>
<title>LunaMaya - Article reWriter Bahasa Indonesia</title>
</head>

<body>

<form method="post">
<table style="vertical-align: text-top;">
<tr>
  <td>Excluded Words (comma, separrated, etc) </td>
  <td><input style="width: 700px;" type="text" name="userWords" value="not working yet" /></td>
</tr>

<tr>
<td><h2>Source Article</h2></td>
<td> <textarea name="text1" style="width: 700px; height: 500px;">
<?php if (!empty($_REQUEST['text1'])) echo rawurldecode($_REQUEST['text1']); ?></textarea></td>
</tr>

<tr>
<td></td>
<td><input type="submit" /></td>
</tr>
</table>
</form>

<?php

if (!empty($_REQUEST['text1']))
{
  require_once('lunamaya.php');
  $luna = new LunaMaya();

  $userWords = array(
                'Polda Metro Jaya',
                );

  $defaultWords = array(
                'dari',
                );

  $stopWords = array_merge($userWords, $defaultWords);

  $text1 = $_REQUEST['text1'];
  $text2 = $luna->reWriteIndo($text1, $stopWords, false);

  similar_text($text1, $text2, $percent);
  $percent = 100 - $percent;

  //printf("text: %s\n\ntext2: %s\n\n Uniqueness: %0.2f%%\n", $text1, $text2, $percent);

  if (!empty($text2))
  { ?>

<div style="margin-left: 260px;">
  <h2>Spun Article</h2>
  <div style="border: 2px solid; width: 700px;">
  <?php echo nl2br($text2); ?>
  </div>
</div>
<?php
  }
}
?>

&copy; <strong>sphinxid</strong> - kodelatte.com
</body>
</html>
