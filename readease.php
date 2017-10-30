<?php
require('DaveChild/TextStatistics/TextStatistics.php');
require('DaveChild/TextStatistics/Text.php');
require('DaveChild/TextStatistics/Maths.php');
require('DaveChild/TextStatistics/Syllables.php');
$statistics = new DaveChild\TextStatistics\TextStatistics;
$text = 'French fries at the college costs me six dollars, fuckin ridiculoud.';
echo 'Flesch-Kincaid Grade level: ' . $statistics->flesch_kincaid_grade_level($text);
?>