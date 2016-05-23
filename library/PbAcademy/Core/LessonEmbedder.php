<?php

/**
 * Description of LessonEmbedder
 *
 * @author jfalkenstein
 */
class LessonEmbedder{
    
    public static function GetPreview($contentTypeId, $content, $imagePath){
        return self::switchOnId($contentTypeId, $content, $imagePath);
    }
    public static function EmbedLesson(Lesson $lesson){
        $content = $lesson->Content;
        $imagePath = $lesson->ImagePath;
        $ctId = $lesson->ContentType()->Id;
        return self::switchOnId($ctId, $content, $imagePath);
    }
    
    private static function switchOnId($ctId, $content, $imagePath){
        switch($ctId){
            case 1: //BrainShark -> Create embed code from brainshark Id
                return LessonEmbedder::BrainShark($content);
            case 2: //GenericVideo -> insert(or create?) embed code from content
                return LessonEmbedder::GenericVideo($content);
            case 3: //Download -> Create image of document that links to a file on our server
                return LessonEmbedder::DownloadLink($content, $imagePath);                
            case 4: //Youtube -> Create Youtube embed code from youtube link
                return LessonEmbedder::Youtube($content);                
            case 6: //Naz Media library --> create embed code from media library link
                return LessonEmbedder::NazMediaLibrary($content);                
            case 5: //Html -> Return raw html string from content, stripped of script tags.
                return LessonEmbedder::Html($content);                
            case 7: //Url Iframe -> Embeds external url in an iframe.
                return LessonEmbedder::UrlIframe($content);                
            default: 
                return "";
        }
    }
    
    public static function Html($htmlContent){
        $dom = new DOMDocument();
        $dom->loadHTML($htmlContent);
        $script = $dom->getElementsByTagName('script');
        $remove = [];
        foreach($script as $item){
            $remove[] = $item;
        }
        foreach($remove as $item){
            /* @var $item DOMNode */
            $item->parentNode->removeChild($item);
        }
        $code = '<div class="content-container">'
                . $dom->saveHTML()
                . '</div>';
        return $code;
    }
    
    /**
     * GenericVideo:
     * This method recieves an embed code from an online video source, wrapping it in 
     * a video-container div.
     * @param string $embedCode
     * @return string
     */
    public static function GenericVideo($embedCode){
        $code = '<div class="video-container">'
                  . $embedCode
                . '</div>';
        return $code;
    }
    
    /**
     * BrainShark:
     * This method receives the current BrainShark Address and will return an embed code
     * for that BrainShark to be used on a lesson Page.
     * 
     * @param string $bSharkAddress
     * @return string The embed code for the BrainShark.
     */
    public static function BrainShark($bSharkAddress){
        $width = 555;
        $height = 348;
        $code = '<div class="video-container" style="padding-bottom:' . (($height/$width)*100). '%">'
                . '<iframe src="' . $bSharkAddress 
                  . '?dm=5&pause=1&nrs=1" frameborder="0" '
                  . 'width="'. $width . '" height="' . $height . '" scrolling="no" '
                  . 'style="border:1px solid #999999">'
                . '</iframe>'
                . '</div>';
        return $code;
    }
    
    public static function Youtube($YouTubeURL){
        $width = 640;
        $height = 390;
        $youtubeID = self::extractYouTubeId($YouTubeURL);
        if($youtubeID){
            $vidDivStart = '<div class="video-container" style="padding-bottom:' . (($height/$width)*100). '%">';
            $divTag = '<div id="ytplayer">';
            $script = '<script>'
                    . ' /*Load the IFrame Player API code asyncrhonously.*/'
                    . ' var tag = document.createElement("script");'
                    . ' tag.src = "https://www.youtube.com/player_api";'
                    . ' var ytPlayerDiv = document.getElementById("ytplayer");'
                    . ' ytPlayerDiv.parentNode.insertBefore(tag, ytPlayerDiv);'
                    . ' '
                    . ' /*Replace the yplayer element with an <iframe> and'
                    . ' Youtube player after the API code downloads.*/'
                    . ' var player;'
                    . ' function onYouTubePlayerAPIReady(){'
                    . '    player = new YT.Player("ytplayer", {'
                    . '        height: "' . $height .'",'
                    . '        width: "'. $width . '",'
                    . '        videoId: "' . $youtubeID . '"'
                    . '    });'
                    . ' }'
                    . '</script>';
            $vidDivEnd = '</div>';
            return $vidDivStart . $divTag . $script . $vidDivEnd;
        }else{
            return "I'm sorry, but there seems to have been a problem with this lesson.";
        }
    }
    
    private static function extractYouTubeId($YouTubeURL){
        $pattern = 
            '%^             # Match any youtube URL
            (?:https?://)?  # Optional scheme. Either http or https
            (?:www\.)?      # Optional www subdomain
            (?:             # Group host alternatives
              youtu\.be/    # Either youtu.be,
            | youtube\.com  # or youtube.com
              (?:           # Group path alternatives
                /embed/     # Either /embed/
              | /v/         # or /v/
              | /watch\?v=  # or /watch\?v=
              )             # End path alternatives.
            )               # End host alternatives.
            ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
            $%x';
        $result = preg_match($pattern, $YouTubeURL, $matches);
        if ($result) {
            return $matches[1];
        }
        return false;
    }
    
    public static function NazMediaLibrary($url){
        $height = 225;
        $width = 400;
        $code = '<div class="video-container" style="padding-bottom:' . (($height/$width)*100). '%">'
                . '<iframe src="'. $url . '/embed_player?iframe=True" '
                    . 'webkitallowfullscreen="webkitallowfullscreen" '
                    . 'width="'. $width . '" '
                    . 'allowfullscreen="allowfullscreen" '
                    . 'height="' . $height . '" '
                    . 'mozallowfullscreen="mozallowfullscreen" '
                    . 'frameborder="0">'
                . '</iframe>';
        return $code;
    }
    
    public static function DownloadLink($link, $imgPath){
        $imagePath = self::getDocIcon($link);
        $code = '<div class="documentEmbed">'
                    . '<img src="' . $imgPath . '" id="coverImage">'
                    . '<br/><br/>'
                    . '<a href="' . $link . '">'
                        . '<img src="'. $imagePath . '" width="100px"/>'
                        . '<h3>View/Download this document</h3>'
                    . '</a>'
                . '</div>';
        return $code;
    }
    
    private static function getDocIcon($link){
        $extension = pathinfo($link,PATHINFO_EXTENSION);
        $imagePath = '/components/com_pbacademy/images/icons/';
        switch (true){
            case stristr($extension, 'pdf'):
                $imagePath.= 'pdf.png';
                break;
            case stristr($extension, 'doc'):
            case stristr($extension, 'docx'):
                $imagePath.= 'word.png';
                break;
            case stristr($extension, 'ppt'):
            case stristr($extension, 'pptx'):
                $imagePath.= 'powerpoint.png';
                break;
            case stristr($extension, 'xls'):
            case stristr($extension, 'xlsx'):
                $imagePath.= 'excel.png';
                break;
            default:
                $imagePath.= 'document.png';
                break;
        }
        return $imagePath;
        
    }
    
    public static function UrlIframe($url){
        $code = '<div id="urlEmbed">'
                .  '<iframe '
                    .  'src="' . $url . '" '
                    .  'width="555" '
                    .  'height="600" '
                    .  'scrolling="yes"'
                .  '</iframe>'
                .'</div>';
        return $code;
    }
}