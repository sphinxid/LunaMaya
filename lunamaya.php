<?php

/*
 * LunaMaya - Indonesian Article Rewriter
 *
 * lunamaya.php
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

class LunaMaya
{

  private $_rawdata = array();

function __construct()
{
  $this->_init();
}

private function _init()
{
  if (empty($this->_rawdata))
  {
    $this->_rawdata = file_get_contents('data/id.txt');
    $this->_rawdata = explode("\n", $this->_rawdata);
  }
}

function reWriteIndo($string, $stopWords = array(), $debug = false)
{
  if (!empty($stopWords))
  {
    foreach($stopWords as &$word)
    {
      $word2 = str_replace(' ', '_', $word);
      $word2 = "#".$word2."#";
      $stopWords2[] = $word2;

      $string = str_replace($word, $word2, $string);
    }
  }

  $string = $this->doRewriteIndo($string, $stopWords, $debug);

  if (!empty($stopWords2))
  {
    $i = 0;
    foreach($stopWords2 as &$word2)
    {
      $string = str_replace($word2, $stopWords[$i++], $string);
    }
  }

  return $string;
}

function doReWriteIndo($string, $stopWords = array(), $debug = false)
{

  // TODO: stop words rewrite filter

  //$words = explode(". ,!", $string);

  $delim = ". ,!;";
  $tok = strtok($string, $delim);
  while ($tok !== false) {
    $words[] = trim($tok);
    $tok = strtok($delim);
  }

  //$words = array_unique($words);

  foreach($words as &$word)
  {
    // kata berawalan huruf kecil
    if (ctype_lower($word[0]))
    {
      $newWord = $this->getRandomIndoSyn($word, 0);
    }
    // kata berawalan huruf besar
    else
    {
      $newWord = $this->getRandomIndoSyn(strtolower($word));
      if (!empty($newWord[0]))
	$newWord[0] = ucwords($newWord[0]);
    }

    // echo $word." - ".$newWord."\n";
    if (!empty($newWord[0]))
    {
      if ($debug)
	$newWord[0] = $newWord[0]."[".$newWord[1]."]";

      // if array unique
      // $string = str_replace($word, $newWord[0], $string);

      $string = str_replace2($word, $newWord[0], $string);
    }
  }

  $string = str_replace("  ", " ", $string);

  return $string;
}

function getRandomIndoSyn($word, $random = -1, $debug = false)
{
  $data = $this->getIndoSyn($word);

  // TODO: customized every word preferred word group.
  // preferred_word_group
  $pwg = 0;

  //$word = $data[$word][$pwg][mt_rand(0, count($data[$word][$pwg])-1)];
  if ($random == -1)
    $pwg = mt_rand(0, (count($data[$word])-1));

  $word = $data[$word][$pwg][mt_rand(0, count($data[$word][$pwg])-1)];

  $data[0] = $word;
  $data[1] = $pwg;

  return $data;
}

function getIndoSyn($word)
{

$x = array();
foreach($this->_rawdata as &$d)
{
  $e = explode("\t", $d);

  if(!empty($e[1]))
  {
    $e[0] = trim($e[0]);

    //print_r($word)." ".print_r($e[0]);

    if(strcmp($word, $e[0]) == 0)
    {

      $f2 = explode("\\n\\n", $e[1]);

      //filter: 1), 2), etc..
      $f2 = preg_replace('/[0-9]\)/s', '', $f2);
      $f2 = preg_replace('/\[.+?\]/s', '', $f2);
      $f2 = preg_replace('/\(.*?\)/s', '', $f2);

      foreach($f2 as &$f3)
      {
        $f4 = explode(",", $f3);
	foreach($f4 as &$f4b)
	{
	  $f4b = str_replace(';', '', $f4b);
	  $f4b = trim($f4b);
	}
	$f5[] = $f4;
      }

      $x[$e[0]] = $f5;
      return $x;
    }
  }
}
  return false;
}
}

function str_replace2($find, $replacement, $subject, $limit = 1) {
    $pattern = '/\b'.$find.'\b/';
    return @preg_replace($pattern, $replacement, $subject, $limit);
}
