<?php
namespace jamesjara\TranscriptYoutube;

class TranscriptYoutube
{
	public $url = null;

	public $youtube_endpoint = 'http://www.youtube.com/get_video_info?&video_id=';

	public $matcher = "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#";

	public function proccessUrl($video_url)
		{
		if (isset($_GET['ct']))
			{
			$this->proccessCaptionTrack($_GET['ct']);
			return;
			}

		parse_str(str_replace('https', 'http', $video_url) , $video_info);
		$video_info_response = file_get_contents($this->youtube_endpoint . $video_info["http://www_youtube_com/watch?v"]);
		parse_str($video_info_response, $video_info_array);
		if (isset($video_info_array['caption_tracks']))
			{
			$caption_tracks = explode(',', $video_info_array['caption_tracks']);
			foreach($caption_tracks as $caption_track)
				{
				parse_str($caption_track, $caption_track_info);
				echo "<br />";
				echo sprintf("<b>%s</b>: <a target='_blank' href='%s'>--TRANSLATED--</a> \n\t <a target='_blank'  href='%s'>original</a> ", $caption_track_info['n'], basename(__FILE__) . '?ct=' . urlencode($caption_track_info['u']) , $caption_track_info['u']);
				}
			}

		return;
		}

	public function proccessCaptionTrack($caption_track_url)
		{
		$xml = file_get_contents(urldecode($caption_track_url));
		$xml = simplexml_load_string($xml);
		foreach($xml->text as $phrase)
			{
			$attrs = $phrase->attributes();
			echo '<table style="width:100%"><tr>';
			echo sprintf("<td style='width:50px;'><b>%s</b></td> <td  style='width: 50px;'>%s</td>  <td style='text-align: left;'>%s</td>", $attrs->start, $attrs->dur, $phrase[0]);
			echo '</tr></table>';
			}
		}
}


