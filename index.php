<html>

<head>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Russo+One|Source+Code+Pro:700" rel="stylesheet">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" crossorigin="anonymous">
</head>

<body>
    <ul>
        <li><a class="versus"  href="http://artist-vs-artist.com/"><img style="height:47px; cursor: pointer; padding-left: 12px; width:55px;" src="http://res.cloudinary.com/http-www-okvitae-com/image/upload/v1493016935/vsimg-red_akc1yv.png"></a></li>
        <li><a href="#about">About</a></li>
        <li style="float:right"><a class="roll" href="random.php">Random</a></li>
    </ul>
    
    <form class="form-wrapper animated fadeInUpBig" id="artistPage" action="index.php" method="post">
        <input type="text" name="artist" id="search" placeholder="https://genius.com/artists/Mf-doom" style="color:#8c8c8c;" required>
        <input class="subinput" type="submit" value="battle" style="font-weight: 700; font-size: 20px; letter-spacing: 1px; font-family: 'Source Code Pro', monospace;" id="submit">
        <input type="text" name="artist2" id="search2" placeholder="https://genius.com/artists/Kesha" style="color:#8c8c8c;" required>
    </form>
    <div class="loader loader--style3" title="2">
      <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
         width="50px" height="50px" viewBox="0 0 70 70" style="enable-background:new 0 0 50 50;" xml:space="preserve">
        <path fill="#000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
        <animateTransform attributeType="xml"
          attributeName="transform"
          type="rotate"
          from="0 25 25"
          to="360 25 25"
          dur="0.7s"
          repeatCount="indefinite"/>
        </path>
      </svg>
        <p class="floater">Scraping data. Loading may take up to 3 minutes.</p>
    </div>
    <script>
        $(document).ready(function(){
            $('.loader').hide();
            $('.roll').click(function(){
            $(".loader").show();
            });
            $('.subinput').click(function(){
                var val = document.getElementById('search').value;
                var val2 = document.getElementById('search2').value;
                if(val != 0 && val2 != 0){
                    $(".loader").show();
                }
            });    
        });
    </script>
<?php
if(isset($_POST['artist'])){
    
$filturl = $_POST['artist'];
$filturl2 = $_POST['artist2'];
        
if (filter_var($filturl, FILTER_VALIDATE_URL) === FALSE || filter_var($filturl2, FILTER_VALIDATE_URL) === FALSE) {
    die('Not a valid URL');
}
else 
{
$artistLink = $_POST['artist'];
$artistLink2 = $_POST['artist2'];
$id = get_meta_tags($artistLink);
$id2 = get_meta_tags($artistLink2);
    
$numberOfSongs = 45;
// 40 or lower in production    
    
echo "<script>$('#artistPage').hide();</script>";
echo "<script>$('.loader').hide();</script>";
    
include_once('simple_html_dom.php');
include_once('DaveChild/TextStatistics/Syllables.php');

$url2 = 'https://api.genius.com' . $id2['newrelic-resource-path'] . '/songs?per_page=' . $numberOfSongs . '&access_token=qxDc7oG8urUrl-U2Mm6J7U3Xf-K5vQG0IlTVMQjhb7CYkPwOJi9cU_o9eop2W99p';
$url = 'https://api.genius.com' . $id['newrelic-resource-path'] . '/songs?per_page=' . $numberOfSongs . '&access_token=qxDc7oG8urUrl-U2Mm6J7U3Xf-K5vQG0IlTVMQjhb7CYkPwOJi9cU_o9eop2W99p';

$content = file_get_contents($url);
$content2 = file_get_contents($url2);
    
$json = json_decode($content, true);
$json3 = json_decode($content2, true);
    
$allLyrics = '';
$allLyrics2 = '';

$artistInfo = 'https://api.genius.com' . $id['newrelic-resource-path'] . '?access_token=YOUR ACCESS TOKEN HERE';
$artistContent = file_get_contents($artistInfo);
$json2 = json_decode($artistContent, true);
    
$artistInfo2 = 'https://api.genius.com' . $id2['newrelic-resource-path'] . '?access_token=YOUR ACCESS TOKEN HERE';
$artistContent2 = file_get_contents($artistInfo2);
$json4 = json_decode($artistContent2, true);   
    
function stripLyrics($links) {
    $link = $links;
    $data = file_get_contents($link);
    $html = str_get_html($data);
    $lyr = $html->find('div.song_body-lyrics', 0);

    foreach($lyr ->find('p') as $item) {
        $string = $item;
        $string = strip_tags($string); // Removes ridiculous Genius <a> tags
        $string = preg_replace("/\[([^\[\]]++|(?R))*+\]/", "", $string); // Removes square brackets & text inside of them recursively
        $string = str_replace("<br>"," ", $string);
        $string = str_replace("-"," ", $string);
        $string = str_replace("!","", $string);
        $string = str_replace("?","", $string);
        $string = str_replace("%"," percent ", $string);
        $string = str_replace("$"," dollars ", $string);
        $string = str_replace("#"," hashtag ", $string);
        $string = str_replace("@"," at ", $string);
        $string = str_replace("&"," and ", $string);
        $string = str_replace(",","", $string);
        $string = str_replace("'","", $string);
        $string = str_replace("‘","", $string);
        $string = str_replace('”',"", $string);
        $string = str_replace('“',"", $string);
        $string = str_replace('…',"", $string);
        $string = str_replace("—"," ", $string);
        $string = str_replace("–"," ", $string);
        $string = str_replace("’","", $string);
        $string = str_replace("."," ", $string);
        $string = str_replace("(","", $string);
        $string = str_replace(")","", $string);
        $string = str_replace("/"," ", $string);
        $string = str_replace('"',"", $string);
        $string = str_replace(':'," ", $string);
        $string = str_replace(';'," ", $string);
        $string = str_replace('Ø',"o", $string);
        $string = str_replace('*',"", $string);
        $string = str_replace('_'," ", $string);
        $string = str_replace('{'," ", $string);
        $string = str_replace('}'," ", $string);
        $string = str_replace('1',"", $string);
        $string = str_replace('2',"", $string);
        $string = str_replace('3',"", $string);
        $string = str_replace('4',"", $string);
        $string = str_replace('5',"", $string);
        $string = str_replace('6',"", $string);
        $string = str_replace('7',"", $string);
        $string = str_replace('~'," ", $string);
        $string = str_replace('8',"", $string);
        $string = str_replace('9',"", $string);
        $string = str_replace('0',"", $string);
        $string = preg_replace('!\s+!', ' ', $string); // Single spaces all words
        $string = strtolower($string);
        $yeah = array(" ye ", " ayo ", "ayy", " yee ", " yaaaa ", " ay ", " yeeeeeeeeeeeeeeeeeeeeah ", " aye ", " yea ", " ya ", " yuh ", " yah ");
        $youAll = array(" ya'll ", " yall ", " yal ");
        $oh = array(" ooh ", " oooh ", " ooooh ", " oooooh ", " weareheremovement ", " foreverforeverforever ", " youknowwhatimsayin ", " oohoohoohoohoohoohooh ", " téléspectateurs ", " boooooohhhhoooohhhhh ", " ooooooh ", " ohh ", " ohhh ", " ohhhh ", " ohhhhh ", " charrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrgeaaawwwww ", " yeahhahhhahhh ", " youknowhatimsaying ", " lieutenantyeeuuuh ", " heeeeeeeeeeeeeeeeeeeeey ", " ehbludebleebudebuebludebiblumble ", " dadadanananananananananai ", " kiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiid ", " yeeeeeeeeeeeeeeeeeeeeah ", " baaaaannnnnnnkkkkkkk ", " baaaaaaaaaankroll ", " oahahhhoahhohhhhhh ", " haaaaaaaaaaaooooooohhh ", " rrrrrrrrrrrrrrrim ", " haaaaaaaaaaaooooooohhh ", " goodnightgoodnight ", "wubwjwubsjihksdjhfskdghiougklllshdlfhs", " kiiiiiiiiiiiiiiiiiiiiiiiiiiid ");
        $string = str_replace($youAll, " you all ", $string);
        $string = str_replace($yeah, " yeah ", $string);
        $string = str_replace($oh, " oh ", $string);
        $GLOBALS['allLyrics'] .= ' ' . $string . ' ';
        }
}
function stripLyrics2($links2) {
    $link2 = $links2;
    $data2 = file_get_contents($link2);
    $html2 = str_get_html($data2);
    $lyr2 = $html2->find('div.song_body-lyrics', 0);

    foreach($lyr2 ->find('p') as $item2) {
        $string2 = $item2;
        $string2 = strip_tags($string2); // Removes ridiculous Genius <a> tags
        $string2 = preg_replace("/\[([^\[\]]++|(?R))*+\]/", "", $string2); // Removes square brackets & text inside of them recursively
        $string2 = str_replace("<br>"," ", $string2);
        $string2 = str_replace("-"," ", $string2);
        $string2 = str_replace("!","", $string2);
        $string2 = str_replace("?","", $string2);
        $string2 = str_replace("%"," percent ", $string2);
        $string2 = str_replace("$"," dollars ", $string2);
        $string2 = str_replace("#"," hashtag ", $string2);
        $string2 = str_replace("@"," at ", $string2);
        $string2 = str_replace("&"," and ", $string2);
        $string2 = str_replace(",","", $string2);
        $string2 = str_replace("'","", $string2);
        $string2 = str_replace("‘","", $string2);
        $string2 = str_replace('”',"", $string2);
        $string2 = str_replace('“',"", $string2);
        $string2 = str_replace('…',"", $string2);
        $string2 = str_replace("—"," ", $string2);
        $string2 = str_replace("–"," ", $string2);
        $string2 = str_replace("’","", $string2);
        $string2 = str_replace("."," ", $string2);
        $string2 = str_replace("(","", $string2);
        $string2 = str_replace(")","", $string2);
        $string2 = str_replace("/"," ", $string2);
        $string2 = str_replace('"',"", $string2);
        $string2 = str_replace(':'," ", $string2);
        $string2 = str_replace(';'," ", $string2);
        $string2 = str_replace('Ø',"o", $string2);
        $string2 = str_replace('*',"", $string2);
        $string2 = str_replace('_'," ", $string2);
        $string2 = str_replace('{'," ", $string2);
        $string2 = str_replace('}'," ", $string2);
        $string2 = str_replace('1',"", $string2);
        $string2 = str_replace('2',"", $string2);
        $string2 = str_replace('3',"", $string2);
        $string2 = str_replace('4',"", $string2);
        $string2 = str_replace('5',"", $string2);
        $string2 = str_replace('6',"", $string2);
        $string2 = str_replace('7',"", $string2);
        $string2 = str_replace('~'," ", $string2);
        $string2 = str_replace('8',"", $string2);
        $string2 = str_replace('9',"", $string2);
        $string2 = str_replace('0',"", $string2);
        $string2 = preg_replace('!\s+!', ' ', $string2); // Single spaces all words
        $string2 = strtolower($string2);
        $yeah2 = array(" ye ", " ayo ", "ayy", " yee ", " yaaaa ", " ay ", " aye ", " yea ", " ya ", " yuh ", " yah ");
        $youAll2 = array(" ya'll ", " yall ", " yal ");
        $oh2 = array(" ooh ", " oooh ", " ooooh ", " oooooh ", " weareheremovement ", " foreverforeverforever ", " youknowwhatimsayin ", " oohoohoohoohoohoohooh ", " téléspectateurs ", " boooooohhhhoooohhhhh ", " ooooooh ", " ohh ", " ohhh ", " ohhhh ", " ohhhhh ", " charrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrgeaaawwwww ", " yeahhahhhahhh ", " youknowhatimsaying ", " lieutenantyeeuuuh ", " heeeeeeeeeeeeeeeeeeeeey ", " ehbludebleebudebuebludebiblumble ", " dadadanananananananananai ", " kiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiid ", " yeeeeeeeeeeeeeeeeeeeeah ", " baaaaannnnnnnkkkkkkk ", " baaaaaaaaaankroll ", " oahahhhoahhohhhhhh ", " haaaaaaaaaaaooooooohhh ", " rrrrrrrrrrrrrrrim ", " haaaaaaaaaaaooooooohhh ", " goodnightgoodnight ", "wubwjwubsjihksdjhfskdghiougklllshdlfhs", " kiiiiiiiiiiiiiiiiiiiiiiiiiiid ");
        $string2 = str_replace($youAll2, " you all ", $string2);
        $string2 = str_replace($yeah2, " yeah ", $string2);
        $string2 = str_replace($oh2, " oh ", $string2);
        $GLOBALS['allLyrics2'] .= ' ' . $string2 . ' ';
        }
}
    
$imgURL = ($json2['response']['artist']['image_url']);
$artistName = ($json2['response']['artist']['name']);

$imgURL2 = ($json4['response']['artist']['image_url']);
$artistName2 = ($json4['response']['artist']['name']);    

$forNumSongs = $numberOfSongs-2;
// -1 because of 0 indexing but only effects some artists?    
    
for ($i = 1; $i < $forNumSongs; $i++) {
    if(($json['response']['songs'][$i]['url']) === NULL || ($json3['response']['songs'][$i]['url']) === NULL){
        die('<div class="error">One of your artists does not have enough songs to pull data from :(</div>');
    } 
    else 
    {    
    $thisIsALink = ($json['response']['songs'][$i]['url']);
    $thisIsALink2 = ($json3['response']['songs'][$i]['url']);
    
    $func = 'stripLyrics2';
    $func($thisIsALink2);
        
    $func = 'stripLyrics';
    $func($thisIsALink);
    }
}
    
$words = explode(' ', $GLOBALS['allLyrics']);
$sylArr = join(' ', $words);
    
$words2 = explode(' ', $GLOBALS['allLyrics2']);
$sylArr2 = join(' ', $words2);    
    
$matchWords = array_intersect($words, $words2);
    
$numMatchWords = $words + $words2;
$newMatchWords = [];
function get_duplicates($array) {
    $GLOBALS['newMatchWords'] = array_unique( array_diff_assoc( $array, array_unique( $array ) ) );
}
$func = 'get_duplicates';
$func($numMatchWords);    
    
$longestMatWordLength = 0;
$longestMatWord = '';
    
foreach ($matchWords as $matWord) {
    if (strlen($matWord) > $longestMatWordLength) {
       $longestMatWordLength = strlen($matWord);
       $longestMatWord = $matWord;
    }
}    
    
$totalWords = str_word_count($sylArr);
$totalWords2 = str_word_count($sylArr2);    
    
$wordsPerSong = $totalWords / $numberOfSongs;
$wordsPerSong2 = $totalWords2 / $numberOfSongs;

$totalSyl = DaveChild\TextStatistics\Syllables::syllableCount($sylArr);
$totalSyl2 = DaveChild\TextStatistics\Syllables::syllableCount($sylArr2);

$sylPerWord = $totalSyl / $totalWords;
$sylPerWord2 = $totalSyl2 / $totalWords2;
    
$longestWordLength = 0;
$longestWord = '';

$longestWordLength2 = 0;
$longestWord2 = '';

foreach ($words as $word) {
    if (strlen($word) > $longestWordLength) {
       $longestWordLength = strlen($word);
       $longestWord = $word;
    }
}

foreach ($words2 as $word2) {
    if (strlen($word2) > $longestWordLength2) {
       $longestWordLength2 = strlen($word2);
       $longestWord2 = $word2;
    }
}
    
$stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $longestWord);
$stripped2 = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $longestWord2);
    
$uniqueWords = implode(' ',array_unique(explode(' ', $GLOBALS['allLyrics'])));
$uniqueWords2 = implode(' ',array_unique(explode(' ', $GLOBALS['allLyrics2'])));

$uniRatio = str_word_count($uniqueWords)/$totalWords;
$uniRatio2 = str_word_count($uniqueWords2)/$totalWords2;

$GLOBALS['allLyrics'] = implode(' ',array_unique(explode(' ', $GLOBALS['allLyrics'])));
$GLOBALS['allLyrics2'] = implode(' ',array_unique(explode(' ', $GLOBALS['allLyrics2'])));
//echo $GLOBALS['allLyrics'];
} 
}
?>
<script>
$(document).ready(function(){
    
var leftWordsPerSong = +$(".left-words-persong").text(); 
var rightWordsPerSong = +$(".right-words-persong").text(); 
if (leftWordsPerSong > rightWordsPerSong){
    $(".left-words-persong").css('color', 'green');
    $(".right-words-persong").css('color', '#c93b39');
} else if(leftWordsPerSong === rightWordsPerSong){
    $(".left-words-persong").css('color', '#39c7c9');
    $(".right-words-persong").css('color', '#39c7c9');
} else {
    $(".right-words-persong").css('color', 'green');
    $(".left-words-persong").css('color', '#c93b39');
}
    
var leftSylPerWord = +$(".left-syl-perword").text(); 
var rightSylPerWord = +$(".right-syl-perword").text(); 
if (leftSylPerWord > rightSylPerWord){
    $(".left-syl-perword").css('color', 'green');
    $(".right-syl-perword").css('color', '#c93b39');
} else if(leftSylPerWord === rightSylPerWord){
    $(".left-syl-perword").css('color', '#39c7c9');
    $(".right-syl-perword").css('color', '#39c7c9');
} else {
    $(".right-syl-perword").css('color', 'green');
    $(".left-syl-perword").css('color', '#c93b39');
}
    
var leftUniRatio = +$(".left-uni-ratio").text(); 
var rightUniRatio = +$(".right-uni-ratio").text(); 
if (leftUniRatio > rightUniRatio){
    $(".left-uni-ratio").css('color', 'green');
    $(".right-uni-ratio").css('color', '#c93b39');
} else if(leftUniRatio === rightUniRatio){
    $(".left-uni-ratio").css('color', '#39c7c9');
    $(".right-uni-ratio").css('color', '#39c7c9');
} else {
    $(".right-uni-ratio").css('color', 'green');
    $(".left-uni-ratio").css('color', '#c93b39');
}    
    
var longWordOne = $(".longwordone").text(); 
var longWordTwo = $(".longwordtwo").text(); 
if (longWordOne.length > longWordTwo.length){
    $(".longwordone").css('color', 'green');
    $(".longwordtwo").css('color', '#c93b39');
} else if (longWordOne.length === longWordTwo.length){
    $(".longwordone").css('color', '#39c7c9');
    $(".longwordtwo").css('color', '#39c7c9');
} else {
    $(".longwordtwo").css('color', 'green');
    $(".longwordone").css('color', '#c93b39');
}
  
var el = document.getElementById("one")
if (el.innerHTML.indexOf("KANYE WEST") !== -1) {
    $('.longwordone').empty().append("overintellectualization");   
}
var dl = document.getElementById("two")
if (dl.innerHTML.indexOf("KANYE WEST") !== -1) {
    $('.longwordtwo').empty().append("overintellectualization");   
}
});
</script>
<div class="wrapper-this">
    <div class="imagesandname">
        <?php
        if(isset($_POST['artist'])){
            if (filter_var($filturl, FILTER_VALIDATE_URL) === FALSE || filter_var($filturl2, FILTER_VALIDATE_URL) === FALSE) {
                die('Not a valid URL');
            }
            else 
            {
                $VSIMG = 'http://res.cloudinary.com/http-www-okvitae-com/image/upload/v1493016935/vsimg-red_akc1yv.png';
                echo '<div class="form-wrapper-left"><div class="left"><img class="artistImage" src="' . $imgURL . '">';
                echo '<h3 id="one">' . strtoupper($artistName) . '</h3>';
                echo '<p class="ptags lft">' . $totalWords . '</p>';
                echo '<p class="ptags lft left-words-persong">' . round($wordsPerSong,2) . '</p>';
                echo '<p class="ptags lft">' . $totalSyl . '</p>';
                echo '<p class="ptags lft left-syl-perword">' . round($sylPerWord,3) . '</p>';
                echo '<p class="ptags lft">' . str_word_count($uniqueWords) . '</p>';
                echo '<p class="ptags lft left-uni-ratio">' . round($uniRatio,3) . '</p>';
                echo '<p class="ptags lft longwordone">' . trim($stripped) . '</p>';
                echo '<span class="divide"></span></div>';

                echo '<div class="center"> <img style="padding-top:20px;" class="vs artistImage" src="' . $VSIMG . '">';
                echo '<p class="ptags">' . 'TOTAL WORDS' . '</p>';
                echo '<p class="ptags">' . 'AVERAGE WORDS PER SONG' . '</p>';
                echo '<p class="ptags">' . 'TOTAL SYLLABLES' . '</p>';
                echo '<p class="ptags">' . 'SYLLABLES PER WORD' . '</p>';
                echo '<p class="ptags">' . 'NUMBER OF UNIQUE WORDS' . '</p>';
                echo '<p class="ptags">' . 'UNIQUE WORD RATIO' . '</p>';
                echo '<p class="ptags">' . 'LONGEST WORD FOUND' . '</p>';
                echo '<span class="divide"></span>';
                echo '<p class="ptags">' . 'MATCHING WORDS: ' . count($newMatchWords) . '</p>';
                echo '<p class="ptags lmw">' . 'LONGEST MATCHING WORD: ' . $longestMatWord . '</p></div>';

                echo '<div class="right"> <img class="artistImage2" src="' . $imgURL2 . '">';
                echo '<h3 id="two">' . strtoupper($artistName2) . '</h3>';
                echo '<p class="ptags rght">' . $totalWords2 . '</p>';
                echo '<p class="ptags rght right-words-persong">' . round($wordsPerSong2,2) . '</p>';
                echo '<p class="ptags rght">' . $totalSyl2 . '</p>';
                echo '<p class="ptags rght right-syl-perword">' . round($sylPerWord2,3) . '</p>';
                echo '<p class="ptags rght">' . str_word_count($uniqueWords2) . '</p>';
                echo '<p class="ptags rght right-uni-ratio">' . round($uniRatio2,3) . '</p>';
                echo '<p class="ptags rght longwordtwo">' . trim($stripped2) . '</p>';
                echo '<span class="divide"></span></div></div>';

                echo '<script>';
                echo '$(document).ready(function(){$(".vs").addClass("animated flash").css("animation-delay", ".8s")});';
                echo '</script>';
            }
        }
        ?>  
    </div>
</div>
</body> 
</html>
