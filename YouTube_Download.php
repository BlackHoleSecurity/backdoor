<?php 
/** 
 * CodexWorld 
 * 
 * This Downloader class helps to download youtube video. 
 * 
 * @class       YouTubeDownloader 
 * @author      CodexWorld 
 * @link        http://www.codexworld.com 
 * @license     http://www.codexworld.com/license 
 */ 
class YouTubeDownloader { 

    private $video_id; 
      
    private $video_title; 
      
    private $video_url; 
     
    private $link_pattern; 
     
    public function __construct(){ 
        $this->link_pattern = "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed)\/))([^\?&\"'>]+)/"; 
    } 
     
    public function setUrl($url){ 
        $this->video_url = $url; 
    } 
     
    private function getVideoInfo(){ 
        return file_get_contents("https://www.youtube.com/get_video_info?video_id=".$this->extractVideoId($this->video_url)."&cpn=CouQulsSRICzWn5E&eurl&el=adunit"); 
    } 
      
    private function extractVideoId($video_url){ 
        $parsed_url = parse_url($video_url); 
        if($parsed_url["path"] == "youtube.com/watch"){ 
            $this->video_url = "https://www.".$video_url; 
        }elseif($parsed_url["path"] == "www.youtube.com/watch"){ 
            $this->video_url = "https://".$video_url; 
        } 
         
        if(isset($parsed_url["query"])){ 
            $query_string = $parsed_url["query"]; 
            parse_str($query_string, $query_arr); 
            if(isset($query_arr["v"])){ 
                return $query_arr["v"]; 
            } 
        }    
    } 
     
    public function getDownloader($url){ 
        if(preg_match($this->link_pattern, $url)){ 
            return $this; 
        } 
        return false; 
    } 
      
    public function getVideoDownloadLink(){ 
        parse_str($this->getVideoInfo(), $data); 
        $videoData = json_decode($data['player_response'], true); 
        $videoDetails = $videoData['videoDetails']; 
        $streamingData = $videoData['streamingData']; 
        $streamingDataFormats = $streamingData['formats']; 
          
        $this->video_title = $videoDetails["title"]; 
          
        $final_stream_map_arr = array(); 
          
        foreach($streamingDataFormats as $stream){ 
            $stream_data = $stream; 
            $stream_data["title"] = $this->video_title; 
            $stream_data["mime"] = $stream_data["mimeType"]; 
            $mime_type = explode(";", $stream_data["mime"]); 
            $stream_data["mime"] = $mime_type[0]; 
            $start = stripos($mime_type[0], "/"); 
            $format = ltrim(substr($mime_type[0], $start), "/"); 
            $stream_data["format"] = $format; 
            unset($stream_data["mimeType"]); 
            $final_stream_map_arr [] = $stream_data;          
        } 
        return $final_stream_map_arr; 
    } 
      
    public function hasVideo(){ 
        $valid = true; 
        parse_str($this->getVideoInfo(), $data); 
        if($data["status"] == "fail"){ 
            $valid = false; 
        }  
        return $valid; 
    } 
      
}
?>
<title>YouTube Downloader</title>
<style type="text/css">
	table {
		border-collapse:collapse;
		border-spacing:0;
	}
	td {
		padding: 5px;
	}
	input[type=text] {
		padding:5px;
		border-radius:5px;
		border:1px solid #ebebeb;
		background: #ebebeb;
		outline:none;
		color:#969696;
	}
	input[type=submit] {
		padding:5px;
		border-radius:5px;
		border:1px solid #ebebeb;
		background: #ebebeb;
		color:#969696;
	}
	input[type=submit]:hover {
		cursor: pointer;
	}
	table.p {
		width:42%;
	}
	td.p {
		border-radius:5px;
		border:1px solid #ebebeb;
		background: #ebebeb;
		outline:none;
		color:#969696;
	}
</style>
<table align="center" class="p">
<form method="post" action="?get=info">
	<tr>
		<td colspan="2">
			<input type="text" placeholder="URL : https://www.youtube.com/watch?v=xxxxxxx" name="youtubeURL" style="width:100%;">
		</td>
		<td>
			<input type="submit" name="submit" value="Get Info" style="width:100%;">
		</td>
	</tr>
</form>
</table>
<br>
<?php
error_reporting(0);
if ($_GET['get'] == "info") {
	if (isset($_POST['submit'])) {
$handler = new YouTubeDownloader(); 
 
$youtubeURL = $_POST['youtubeURL']; 
 
if(!empty($youtubeURL) && !filter_var($youtubeURL, FILTER_VALIDATE_URL) === false){ 
    $downloader = $handler->getDownloader($youtubeURL); 
     
    $downloader->setUrl($youtubeURL); 
     
    if($downloader->hasVideo()){ 
        $videoDownloadLink = $downloader->getVideoDownloadLink(); 
         
        $videoTitle = $videoDownloadLink[0]['title']; 
        $videoQuality = $videoDownloadLink[0]['qualityLabel']; 
        $videoFormat = $videoDownloadLink[0]['format']; 
        $videoFileName = strtolower(str_replace(' ', '_', $videoTitle)).'.'.$videoFormat; 
        $downloadURL = $videoDownloadLink[0]['url']; 
        $fileName = preg_replace('/[^A-Za-z0-9.\_\-]/', '', basename($videoFileName)); 
         
        if(!empty($downloadURL)){ 
            header("Cache-Control: public"); 
            header("Content-Description: File Transfer"); 
            header("Content-Disposition: attachment; filename=$fileName"); 
            header("Content-Type: application/zip"); 
            header("Content-Transfer-Encoding: binary"); 
             
            readfile($downloadURL); 
        } 
    }else{ 
        echo "The video is not found, please check YouTube URL."; 
    } 
}else{ 
    echo "Please provide valid YouTube URL."; 
} 
}
?>
<table align="center">
<tr>
	<td class="p" colspan="3">
		<center><?php print $videoTitle ?></center>
	</td>
</tr>
<tr>
	<td class="p">
		Quality
	</td>
	<td class="p"><center>:</center></td>
	<td class="p">
		<?php print $videoQuality ?>
	</td>
</tr>
<tr>
	<td class="p">
		Format
	</td>
	<td class="p">
		<center>:</center>
	</td>
	<td class="p">
		<?php print $videoFormat ?>
	</td>
</tr>
<tr>
	<td class="p">
		Filename
	</td>
	<td class="p">
		<center>:</center>
	</td>
	<td class="p">
		<?php print $videoFileName ?>
	</td>
</tr>
<tr>
	<td>
		<form method="post">
			<input type="hidden" name="url" value="<?php print $downloadURL ?>">
			<input type="submit" name="submit">
		</form>
	</td>
</tr>
</table>
<?php
}
