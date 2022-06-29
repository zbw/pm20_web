<?php
/** nbt, 2019-04-08
 *
 * Params:
 * set (h1/h2/k1/k2)
 * collection (co/sh/wa/(pe))
 * film (dirname)
 * optional: img (four-digit)
 * optional: lang (default de, en NYI)
 */


// fixed texts

$ip_hints = file_get_contents('/pm20/web/templates/fragments/ip_hints.de.html.frag');

// get actual dir name
$fs_root = dirname(getcwd());
$web_root = '/film/';
$fast_skip = 50;

// parse query string
parse_str($_SERVER['QUERY_STRING'], $query_parts);

// assign variables from query
// TODO: remove defaults introduced for development
// TODO: clean and validate args
$set = $query_parts['set'] ?: 'k1';
$collection = $query_parts['collection'] ?: 'sh';
$film = $query_parts['film'] ?: '0200';
$img = $query_parts['img'] ?: '';
$lang = 'de';

require "prev_next_film.${set}_$collection.inc";

$filmpath = "$web_root$set/$collection/$film";
if (!is_dir("$fs_root$filmpath")) {
  header("HTTP/1.0 404 Not Found");
  echo "\nFilm path $filmpath not found\n";
  exit;
}

// get a list of all accessible files in the film directory
if (getenv("PM20_INTERNAL") == 1) {
  // access to all files is free
  // assuming glob returns files in correct sequence
  $files = glob($fs_root . $filmpath . '/' . "*.jpg");
} else {
  // all access is forbidden via Apache mod_authz
  // here we check if some files are allowed via .htaccess, and display links
  // only for these
  $files = [];
  $htaccess = $fs_root . $filmpath . '/.htaccess';
  if (file_exists($htaccess)) {
    $lines = explode("\n", file_get_contents($htaccess));
    foreach ($lines as $line) {
      if (preg_match('/^SetEnvIf Request_URI "(\S+\.jpg)\$" allowedURL$/', $line, $matches)) {
        array_push($files, $fs_root . $filmpath . '/' . $matches[1]);
      }
    }
  }
  if (empty($files)) {
    header("HTTP/1.0 403 Not Found");
    echo "\nNo publicly accessible files in $filmpath\n";
    exit;
  }
}

// assuming all files have the same type of filenames
$fn_type = get_filename_type($files[0]);

// set first file as default, if param omitted
if (empty($img)) {
  $img = extract_imgname($files[0], $film, $fn_type);
}

// construct file name from image number
$cur_file = build_filepath($filmpath, $img, $fn_type);
if (!file_exists("$fs_root$cur_file")) {
  header("HTTP/1.0 404 Not Found");
  echo "\nImage file  $cur_file not found\n";
  exit;
}

// TODO optimize - use only prebuilt link fragment (or prebuild complete page html)
// construct link list
$links = '';
$count = 0;
$cur_key = 0;
foreach ($files as $key => $file) {
  $count++;
  $tmp_img = extract_imgname($file, $film, $fn_type);
  $tmp_path = "$filmpath/$tmp_img";
  if ("$fs_root$cur_file" == $file) {
    $cur_key = $key;
    $links = $links . '<b><a id="img_' . $tmp_img . '">' . $tmp_img . '</a></b> &#160;' . "\n";
  } else {
    $links = $links . '<a id="img_' . $tmp_img . '" href="' . $tmp_path . '">' . $tmp_img . '</a> &#160;' . "\n";
  }
}
// overwrite if link fragment file exists
$links_file = "$fs_root$filmpath/links.de.html.frag";
if (file_exists($links_file)) {
  $links = file_get_contents($links_file);
  $cur_img = extract_imgname($cur_file, $film, $fn_type);
  $links = preg_replace("/(id='img_$cur_img')/s", "$1 class='current'", $links);
}

// initialize main navigation paths
if (array_key_exists($cur_key - 1, $files)) {
  $prev_file = $files[$cur_key - 1];
  $prev_img = extract_imgname($prev_file, $film, $fn_type);
  $prev_path = "$filmpath/$prev_img";
}
if (array_key_exists($cur_key + 1, $files)) {
  $next_file = $files[$cur_key + 1];
  $next_img = extract_imgname($next_file, $film, $fn_type);
  $next_path = "$filmpath/$next_img";
}
// fast navigation -not yet invoked
// TODO restrict to actual length - read array from 'web' directory
if (array_key_exists($cur_key - $fast_skip, $files)) {
  $fprev_file = $files[$cur_key - $fast_skip];
  $fprev_img = extract_imgname($fprev_file, $film, $fn_type);
  $fprev_path = "$filmpath/$fprev_img";
} else {
  $fprev_file = $files[0];
  $fprev_img = extract_imgname($fprev_file, $film, $fn_type);
  $fprev_path = "$filmpath/$fprev_img";
}
if (array_key_exists($cur_key + $fast_skip, $files)) {
  $fnext_file = $files[$cur_key + $fast_skip];
  $fnext_img = extract_imgname($fnext_file, $film, $fn_type);
  $fnext_path = "$filmpath/$fnext_img";
} else {
  $fnext_file = end($files);
  $fnext_img = extract_imgname($fnext_file, $film, $fn_type);
  $fnext_path = "$filmpath/$fnext_img";
}

// set header variables
$title = $set . ' / ' . $collection . ' / ' . $film . ' / ' . $img . ' | PM20 Filmviewer ';
$canonical_link = build_canonical_link($filmpath, $img);

$film_nav = film_nav($film, $set, $collection);


function get_filename_type($file): string
{
  $fn_type = 0;
  $fn = basename($file);
  if (preg_match("/^S\d{8}K\.jpg$/", $fn)) {
    // k1
    $fn_type = 1;
  }
  if (preg_match("/^S\d{8}H\.jpg$/", $fn)) {
    // h1
    $fn_type = 2;
  }
  if (preg_match("/^W\d{8}H\.jpg$/", $fn)) {
    // h1
    $fn_type = 3;
  }
  if (preg_match("/^F\d{8}H\.jpg$/", $fn)) {
    // h1
    $fn_type = 4;
  }
  if (preg_match("/^A\d{8}H\.jpg$/", $fn)) {
    // h1
    $fn_type = 5;
  }
  if ($fn_type == 0) {
    header("HTTP/1.0 400 Bad request");
    echo "\nUnknown type of filename: $file\n";
    exit;
  } else {
    return $fn_type;
  }
}

function build_filepath($filmpath, $img, $fn_type): string
{
  $film = basename($filmpath);
  switch ($fn_type) {
    case 1:
      $fn = "S$film${img}K.jpg";
      break;
    case 2:
      $film_short = substr($film, 1, 4);
      $fn = "S$film_short${img}H.jpg";
      break;
    case 3:
      $film_short = substr($film, 1, 4);
      $fn = "W$film_short${img}H.jpg";
      break;
    case 4:
      $film_short = substr($film, 1, 4);
      $fn = "F$film_short${img}H.jpg";
      break;
    case 5:
      $film_short = substr($film, 1, 4);
      $fn = "A$film_short${img}H.jpg";
      break;
    default:
      header("HTTP/1.0 400 Bad request");
      echo "\nUnknown fn_type: $fn_type\n";
      exit;
  }
  return "$filmpath/$fn";
}

function extract_imgname($file, $film, $fn_type): string
{
  switch ($fn_type) {
    case 1:
      $img = str_replace('S' . $film, '', basename($file, 'K.jpg'));
      break;
    case 2:
      $film_short = substr($film, 1, 4);
      $img = str_replace('S' . $film_short, '', basename($file, 'H.jpg'));
      break;
    case 3:
      $film_short = substr($film, 1, 4);
      $img = str_replace('W' . $film_short, '', basename($file, 'H.jpg'));
      break;
    case 4:
      $film_short = substr($film, 1, 4);
      $img = str_replace('F' . $film_short, '', basename($file, 'H.jpg'));
      break;
    case 5:
      $film_short = substr($film, 1, 4);
      $img = str_replace('A' . $film_short, '', basename($file, 'H.jpg'));
      break;
    default:
      header("HTTP/1.0 400 Bad request");
      echo "\nUnknown fn_type: $fn_type\n";
      exit;
  }
  return $img;
}

function build_canonical_link($filmpath, $img): string
{
  return "https://pm20.zbw.eu$filmpath/$img";
  # annotating a film by page number does not work due to changing anchors
}

function film_nav($film, $set, $collection): string
{
  [$prev_film, $next_film] = prev_next_film($film);
  $nav = '<div><br />';
  if (!empty($prev_film)) {
    $nav .= "<a href=\"../$prev_film\">voriger Film</a> &#160;&#160;";
  }
  if (!empty($next_film)) {
    $nav .= "<a href=\"../$next_film\">nächster Film</a> &#160;&#160;";
  }
  $nav .= "<a href=\"/film/${set}_$collection.de.html\">zurück zum Filmverzeichnis</a></div>";
  return $nav;
}

?><!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
  <style>
    * {
      font-family: Arial, sans-serif;
    }

    html {
      font-size: 12px;
    }

    a:hover, a:visited, a:link, a:active {
      text-decoration: none;
    }

    .current {
      background-color: silver;
    }

    .unrecognized {
      color: gray;
      font-style: italic;
    }

    .date-limit {
      color: gray;
    }

    .imgview-full {
      width: 97vw;
      height: 98vh;
      object-fit: contain;
      object-position: center center;
    }

    .imgview-pos {
      position: relative;
    }

    .imgview-pos a {
      display: block;
      position: absolute;
    }

    @media print {
      .no-print, .no-print * {
        display: none !important;
      }
    }
  </style>
  <script type="application/json" class="js-hypothesis-config">
    {
      "openSidebar": false
    }
  </script>

  <script src="https://hypothes.is/embed.js" async></script>

  <script>
    function leftArrowPressed() {
      <?php if (!empty($prev_path)): ?>window.open("<?= $prev_path ?>", "_self");<?php endif; ?>
    }

    function rightArrowPressed() {
      <?php if (!empty($next_path)): ?>window.open("<?= $next_path ?>", "_self");<?php endif; ?>
    }

    function ctrlLeftArrowPressed() {
      window.open("<?= $fprev_path ?>", "_self");
    }

    function ctrlRightArrowPressed() {
      window.open("<?= $fnext_path ?>", "_self");
    }

    document.onkeydown = function (evt) {
      evt = evt || window.event;
      switch (evt.keyCode) {
        case 37:
          leftArrowPressed();
          break;
        case 39:
          rightArrowPressed();
          break;
      }
    };
  </script>

  <link rel="canonical" href="<?= $canonical_link ?>"/>
  <meta name="dc.identifier" content="<?= $filmpath ?>/<?= $img ?>">
  <meta name="dc.relation.ispartof" content="pm20.zbw.eu">
</head>
<body>

<div class="imgview-pos">
  <img id="img-id" class="imgview-full" src="<?= $cur_file ?>" alt="Image"/>
  <?php if (!empty($prev_path)): ?><a href="<?= $prev_path ?>"
                                      style="top: 0%; left: 0%; width: 25%; height: 100%;"></a><?php endif; ?>
  <a href="<?= $cur_file ?>" style="top: 0%; left: 35%; width: 40%; height: 100%;"></a>
  <?php if (!empty($next_path)): ?><a href="<?= $next_path ?>"
                                      style="top: 0%; left: 75%; width: 25%; height: 100%;"></a><?php endif; ?>
</div>

<div class="no-print" style="margin-top: 1.5em;">

  <img src="/images/zbw_pm20.de.jpg" alt="ZBW PM20 Logo" usemap="#logomap" width="500px">

  <map name="logomap">
    <area shape="rect" coords="0, 0, 166, 73" href="https://www.zbw.eu/de" alt="logo section zbw">
    <area shape="rect" coords="180, 0, 1041, 73" href="/about.de.html" alt="logo section pm20">
  </map>

  <h1>PM20 Filmviewer</h1>
  <h2>Verfilmung <?= $set ?> - Archiv <?= $collection ?> - Film <?= $film ?> - Bild <?= $img ?> von <?= $count ?></h2>

  <?= $film_nav ?>

  <div>&#160;<br/>
    <?= $links ?>
  </div>

  <?php prev_next_film($film) ?>


  <h2>Bedienung</h2>

  <ul>
    <li><b>Vor-/Zurückblättern</b>: Pfeil rechts/links, oder Mausklick im rechten/linken Bildviertel (im
      Filmviewer-Modus)
    </li>
    <li><b>Springen im Film/zurück in die Filmliste</b>: Herunterblättern, um die Navigation anzuzeigen (im
      Filmviewer-Modus)
    </li>
    <li><b>Einzelbildanzeige</b>: Mausklick in Bildmitte</li>
    <li><b>Vergrößern</b>: mit Mauszeiger (Lupen-Symbol) auf gewünschten Bereich klicken (im Einzelbild-Modus)</li>
    <li><b>Einzelbild-Modus verlassen</b>: Alt-Pfeil links</li>
  </ul>

  <?= $ip_hints ?>

</div>

<footer><p><br/><a href="https://www.zbw.eu/de/impressum/">Impressum</a> &nbsp; <a
        href="https://www.zbw.eu/de/datenschutz/">Datenschutz</a></p></footer>

</body>
</html>

