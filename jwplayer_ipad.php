// This depends on the JWPlayer plugin
if (function_exists('jwplayer_tag_callback')) {
    function video_tag_callback($the_content = '', $poster = false) {
        if (preg_match('/iP[ad|od|hone]/', $_SERVER['HTTP_USER_AGENT'])) {
            // We're on an iOS device, grab the MP4 from the playlist and just return that
            if (preg_match('/playlistid="(\d+)"/', $the_content, $matches)) {
                $playlist_items = explode(",", get_post_meta($matches[1], LONGTAIL_KEY. "playlist_items", true));

                foreach ($playlist_items as $playlist_item_id) {
                    $playlist_item = get_post($playlist_item_id);
                    if (preg_match('/mp4$/', $playlist_item->guid)) {
                        $the_content = preg_replace('/playlistid="\d+"/', 'file="' . $playlist_item->guid . '"', $the_content);
                    }
                }
            }
        }
        // Carry on as normal
        if ($poster) {
            return jwplayer_tag_callback(preg_replace('/\[jwplayer (.+?)\]/', '[jwplayer $1 image="' . $poster . '"]', $the_content));
        } else {
            return jwplayer_tag_callback($the_content);
        }
    }
}