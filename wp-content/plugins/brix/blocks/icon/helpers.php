<?php if ( ! defined( 'BRIX' ) ) die( 'Forbidden' );

/**
 * Include Font Awesome as an available icon font.
 *
 * @since 1.0.0
 * @param array $fonts
 * @return array
 */
function brix_fontawesome_icon_font( $fonts ) {
	$fonts['font-awesome'] = array(
		'label'   => 'Font Awesome',
		'name'    => 'font-awesome',
		'path'    => BRIX_FOLDER . 'assets/icon_packs/font-awesome',
		'url'     => BRIX_URI . 'assets/icon_packs/font-awesome',
		'prefix'  => 'fa',
		'mapping' => array()
	);

	return $fonts;
}

add_filter( 'brix_get_icon_fonts', 'brix_fontawesome_icon_font' );

/**
 * Return a list of aliases for the icons.
 *
 * @since 1.1.2
 * @param array $aliases An array of aliases
 * @return array
 */
function brix_styles_get_icon_aliases() {
	$aliases = array();

	$aliases['glass'] = array( 'glass' );
	$aliases['music'] = array( 'music, note, song, sound' );
	$aliases['search'] = array( 'search, find' );
	$aliases['mail'] = array( 'mail, email, envelope-o' );
	$aliases['mail-alt'] = array( 'mail, email' );
	$aliases['mail-squared'] = array( 'mail, email' );
	$aliases['heart'] = array( 'heart' );
	$aliases['heart-empty'] = array( 'heart' );
	$aliases['star'] = array( 'star' );
	$aliases['star-empty'] = array( 'star, star-o' );
	$aliases['star-half'] = array( 'star' );
	$aliases['star-half-alt'] = array( 'star' );
	$aliases['torso'] = array( 'profile, contact, user, torso' );
	$aliases['user'] = array( 'profile, contact, user, torso' );
	$aliases['user-plus'] = array( 'user, plus, add' );
	$aliases['user-times'] = array( 'user, remove, delete' );
	$aliases['users'] = array( 'users, contacts, friends, torso' );
	$aliases['male'] = array( 'male' );
	$aliases['female'] = array( 'female' );
	$aliases['child'] = array( 'child, people' );
	$aliases['user-secret'] = array( 'secret, private, user' );
	$aliases['video'] = array( 'movie, video, film' );
	$aliases['videocam'] = array( 'facetime, movie, video, film, video-camera' );
	$aliases['picture'] = array( 'image, picture, photo' );
	$aliases['camera'] = array( 'camera, photo' );
	$aliases['camera-alt'] = array( 'camera, photo' );
	$aliases['th-large'] = array( 'image, list, thumbnails' );
	$aliases['th'] = array( 'image, list, thumbnails' );
	$aliases['th-list'] = array( 'list' );
	$aliases['ok'] = array( 'ok, yes, check, mark' );
	$aliases['ok-circled'] = array( 'ok, yes, check, mark' );
	$aliases['ok-circled2'] = array( 'ok, yes, check, mark, check-circle-o' );
	$aliases['ok-squared'] = array( 'ok, check' );
	$aliases['cancel'] = array( 'close, cancel, reject, times' );
	$aliases['cancel-circled'] = array( 'close, cancel, reject, times-circle' );
	$aliases['cancel-circled2'] = array( 'close, cancel, reject' );
	$aliases['plus'] = array( 'plus' );
	$aliases['plus-circled'] = array( 'plus' );
	$aliases['plus-squared'] = array( 'plus, plus-square' );
	$aliases['plus-squared-alt'] = array( 'plus' );
	$aliases['plus-squared-small'] = array( 'plus, expand' );
	$aliases['minus'] = array( 'minus' );
	$aliases['minus-circled'] = array( 'minus' );
	$aliases['minus-squared'] = array( 'minus' );
	$aliases['minus-squared-alt'] = array( 'minus' );
	$aliases['minus-squared-small'] = array( 'minus, collapse' );
	$aliases['help'] = array( 'help' );
	$aliases['help-circled'] = array( 'help, question, question-circle' );
	$aliases['info-circled'] = array( 'info, info-circle' );
	$aliases['info'] = array( 'info' );
	$aliases['home'] = array( 'home' );
	$aliases['link'] = array( 'link, chain' );
	$aliases['unlink'] = array( 'unlink, chain-broken' );
	$aliases['link-ext'] = array( 'link, url, external, external-link' );
	$aliases['link-ext-alt'] = array( 'link, url, external' );
	$aliases['attach'] = array( 'attach, clip, paperclip' );
	$aliases['lock'] = array( 'lock' );
	$aliases['lock-open'] = array( 'lock, open, unlock' );
	$aliases['lock-open-alt'] = array( 'lock, unlock, open' );
	$aliases['pin'] = array( 'pin, pushpin' );
	$aliases['eye'] = array( 'eye, subscribe' );
	$aliases['eye-off'] = array( 'eye, unsubscribe' );
	$aliases['tag'] = array( 'tag, category, price, offer' );
	$aliases['tags'] = array( 'tag, category, price, offer' );
	$aliases['bookmark'] = array( 'bookmark' );
	$aliases['bookmark-empty'] = array( 'bookmark' );
	$aliases['flag'] = array( 'flag' );
	$aliases['flag-empty'] = array( 'flag' );
	$aliases['flag-checkered'] = array( 'flag' );
	$aliases['thumbs-up'] = array( 'thumbs, vote, up, like, love, thumbs-o-up' );
	$aliases['thumbs-down'] = array( 'thumbs, vote, down, unlike, dislike, thumbs-o-down' );
	$aliases['thumbs-up-alt'] = array( 'thumbs, vote, up, like, love' );
	$aliases['thumbs-down-alt'] = array( 'thumbs, vote, down, dislike, unlike' );
	$aliases['download'] = array( 'download' );
	$aliases['upload'] = array( 'upload' );
	$aliases['download-cloud'] = array( 'download, cloud' );
	$aliases['upload-cloud'] = array( 'upload, cloud' );
	$aliases['reply'] = array( 'reply' );
	$aliases['reply-all'] = array( 'reply' );
	$aliases['forward'] = array( 'forward' );
	$aliases['quote-left'] = array( 'quote' );
	$aliases['quote-right'] = array( 'quote' );
	$aliases['code'] = array( 'code' );
	$aliases['export'] = array( 'export, share, share-permalink' );
	$aliases['export-alt'] = array( 'export, share' );
	$aliases['share'] = array( 'share' );
	$aliases['share-squared'] = array( 'share' );
	$aliases['pencil'] = array( 'pencil, write, reply, edit' );
	$aliases['pencil-squared'] = array( 'pencil, edit' );
	$aliases['edit'] = array( 'pencil, write, reply, edit' );
	$aliases['print'] = array( 'print' );
	$aliases['retweet'] = array( 'retweet, twitter' );
	$aliases['keyboard'] = array( 'keyboard, keyboard-o' );
	$aliases['gamepad'] = array( 'gamepad' );
	$aliases['comment'] = array( 'comment, reply, write' );
	$aliases['chat'] = array( 'chat, talk' );
	$aliases['comment-empty'] = array( 'comment, reply, write, comment-o' );
	$aliases['chat-empty'] = array( 'chat, talk' );
	$aliases['bell'] = array( 'alert, bell, jingle, bell-o' );
	$aliases['bell-alt'] = array( 'alert, bell, jingle' );
	$aliases['bell-off'] = array( 'bell' );
	$aliases['bell-off-empty'] = array( 'bell' );
	$aliases['attention-alt'] = array( 'attention, warning, alert' );
	$aliases['attention'] = array( 'attention, warning, alert' );
	$aliases['attention-circled'] = array( 'attention, warning, alert' );
	$aliases['location'] = array( 'location, mark, marker' );
	$aliases['direction'] = array( 'direction, location, location-arrow' );
	$aliases['compass'] = array( 'compass' );
	$aliases['trash'] = array( 'trash, trash-o' );
	$aliases['trash-empty'] = array( 'trash, delete' );
	$aliases['doc'] = array( 'doc, file, article' );
	$aliases['docs'] = array( 'doc, file, article, copy' );
	$aliases['doc-text'] = array( 'doc, file, article, file-text-o' );
	$aliases['doc-inv'] = array( 'doc, file, article' );
	$aliases['doc-text-inv'] = array( 'doc, file, article' );
	$aliases['file-pdf'] = array( 'file, pdf, file-pdf-o' );
	$aliases['file-word'] = array( 'file, word' );
	$aliases['file-excel'] = array( 'file, excel' );
	$aliases['file-powerpoint'] = array( 'file, powerpoint' );
	$aliases['file-image'] = array( 'file, image, picture' );
	$aliases['file-archive'] = array( 'file, archive, file-zip-o' );
	$aliases['file-audio'] = array( 'file, audio, sound' );
	$aliases['file-video'] = array( 'file, video, movie' );
	$aliases['file-code'] = array( 'file, code' );
	$aliases['folder'] = array( 'folder' );
	$aliases['folder-open'] = array( 'folder' );
	$aliases['folder-empty'] = array( 'folder' );
	$aliases['folder-open-empty'] = array( 'folder' );
	$aliases['box'] = array( 'box' );
	$aliases['rss'] = array( 'rss' );
	$aliases['rss-squared'] = array( 'rss' );
	$aliases['phone'] = array( 'phone, telephone, call' );
	$aliases['phone-squared'] = array( 'phone' );
	$aliases['fax'] = array( 'fax' );
	$aliases['menu'] = array( 'menu' );
	$aliases['cog'] = array( 'settings, cog, gear, params' );
	$aliases['cog-alt'] = array( 'settings, cog, gear, params' );
	$aliases['wrench'] = array( 'wrench, tools, settings, params' );
	$aliases['sliders'] = array( 'sliders, equalizer, settings' );
	$aliases['basket'] = array( 'basket, shopping, cart, shopping-cart' );
	$aliases['cart-plus'] = array( 'cart, basket' );
	$aliases['cart-arrow-down'] = array( 'cart, basket' );
	$aliases['calendar'] = array( 'calendar, date' );
	$aliases['calendar-empty'] = array( 'calendar' );
	$aliases['login'] = array( 'login, signin, enter, sign-in' );
	$aliases['logout'] = array( 'logout, signout, exit, sign-out' );
	$aliases['mic'] = array( 'mic' );
	$aliases['mute'] = array( 'mic, mute' );
	$aliases['volume-off'] = array( 'volume, sound, mute' );
	$aliases['volume-down'] = array( 'volume, sound' );
	$aliases['volume-up'] = array( 'volume, sound' );
	$aliases['headphones'] = array( 'sound, music, headphones' );
	$aliases['clock'] = array( 'clock, time, clock-o' );
	$aliases['lightbulb'] = array( 'idea, lamp, light, lightbulb-o' );
	$aliases['block'] = array( 'block, deny' );
	$aliases['resize-full'] = array( 'resize, fullscreen' );
	$aliases['resize-full-alt'] = array( 'resize, fullscreen' );
	$aliases['resize-small'] = array( 'resize' );
	$aliases['resize-vertical'] = array( 'resize' );
	$aliases['resize-horizontal'] = array( 'resize' );
	$aliases['move'] = array( 'move' );
	$aliases['zoom-in'] = array( 'zoom, scale, in' );
	$aliases['zoom-out'] = array( 'zoom, scale, out' );
	$aliases['down-circled2'] = array( 'arrow, down, download' );
	$aliases['up-circled2'] = array( 'arrow, up, upload' );
	$aliases['left-circled2'] = array( 'arrow, left' );
	$aliases['right-circled2'] = array( 'arrow, right, arrow-circle-o-right' );
	$aliases['down-dir'] = array( 'down, arrow' );
	$aliases['up-dir'] = array( 'up, arrow' );
	$aliases['left-dir'] = array( 'left, arrow' );
	$aliases['right-dir'] = array( 'right, arrow' );
	$aliases['down-open'] = array( 'arrow, down' );
	$aliases['left-open'] = array( 'arrow, left' );
	$aliases['right-open'] = array( 'arrow, right' );
	$aliases['up-open'] = array( 'arrow, up' );
	$aliases['angle-right'] = array( 'angle, right' );
	$aliases['angle-up'] = array( 'angle, up' );
	$aliases['angle-down'] = array( 'angle, down' );
	$aliases['angle-circled-left'] = array( 'angle, left, chevron-circle-left' );
	$aliases['angle-circled-right'] = array( 'angle, right, chevron-circle-right' );
	$aliases['angle-circled-up'] = array( 'angle, up, chevron-circle-up' );
	$aliases['angle-circled-down'] = array( 'angle, down, chevron-circle-down' );
	$aliases['angle-double-left'] = array( 'angle, left' );
	$aliases['angle-double-right'] = array( 'angle, right' );
	$aliases['angle-double-up'] = array( 'angle, up' );
	$aliases['angle-double-down'] = array( 'angle, down' );
	$aliases['down'] = array( 'arrow, down' );
	$aliases['left'] = array( 'arrow, left' );
	$aliases['right'] = array( 'arrow, right' );
	$aliases['up'] = array( 'arrow, up' );
	$aliases['down-big'] = array( 'arrow, down, arrow-down' );
	$aliases['left-big'] = array( 'arrow, left, arrow-left' );
	$aliases['right-big'] = array( 'arrow, right, arrow-right' );
	$aliases['up-big'] = array( 'arrow, up, arrow-up' );
	$aliases['right-hand'] = array( 'hand, right, arrow' );
	$aliases['left-hand'] = array( 'hand, left, arrow' );
	$aliases['up-hand'] = array( 'hand, up, arrow' );
	$aliases['down-hand'] = array( 'hand, down, arrow' );
	$aliases['left-circled'] = array( 'left, arrow' );
	$aliases['right-circled'] = array( 'right, arrow' );
	$aliases['up-circled'] = array( 'up, arrow' );
	$aliases['down-circled'] = array( 'down, arrow' );
	$aliases['cw'] = array( 'reload, redo, repeat' );
	$aliases['ccw'] = array( 'reload, undo, arrow' );
	$aliases['arrows-cw'] = array( 'reload, refresh, update, sync' );
	$aliases['level-up'] = array( 'arrow, level, up' );
	$aliases['level-down'] = array( 'arrow, level, down' );
	$aliases['shuffle'] = array( 'shuffle' );
	$aliases['exchange'] = array( 'exchange, swap, switch, arrows' );
	$aliases['history'] = array( 'history' );
	$aliases['expand'] = array( 'expand' );
	$aliases['collapse'] = array( 'collapse' );
	$aliases['expand-right'] = array( 'expand' );
	$aliases['collapse-left'] = array( 'collapse' );
	$aliases['play'] = array( 'play, player' );
	$aliases['play-circled'] = array( 'player, play' );
	$aliases['play-circled2'] = array( 'play, player' );
	$aliases['stop'] = array( 'stop, player' );
	$aliases['pause'] = array( 'pause, player' );
	$aliases['to-end'] = array( 'right, player' );
	$aliases['to-end-alt'] = array( 'right, player' );
	$aliases['to-start'] = array( 'left, player' );
	$aliases['to-start-alt'] = array( 'left, player' );
	$aliases['fast-fw'] = array( 'right, player' );
	$aliases['fast-bw'] = array( 'left, player' );
	$aliases['eject'] = array( 'eject, player' );
	$aliases['target'] = array( 'target' );
	$aliases['signal'] = array( 'broadcast, wifi, signal' );
	$aliases['wifi'] = array( 'wifi, signal' );
	$aliases['award'] = array( 'top, trophy, prize, award' );
	$aliases['desktop'] = array( 'desktop, screen, monitor' );
	$aliases['laptop'] = array( 'laptop, notebook' );
	$aliases['tablet'] = array( 'tablet' );
	$aliases['mobile'] = array( 'mobile, phone' );
	$aliases['inbox'] = array( 'inbox, stack' );
	$aliases['globe'] = array( 'globe, world' );
	$aliases['sun'] = array( 'sun, sun-o' );
	$aliases['cloud'] = array( 'cloud' );
	$aliases['flash'] = array( 'flash, bolt' );
	$aliases['moon'] = array( 'moon' );
	$aliases['umbrella'] = array( 'umbrella' );
	$aliases['flight'] = array( 'flight, plane, airplane' );
	$aliases['fighter-jet'] = array( 'flight, plane, airplane, fly, jet' );
	$aliases['paper-plane'] = array( 'flight, plane, airplane, fly' );
	$aliases['paper-plane-empty'] = array( 'flight, plane, airplane, fly' );
	$aliases['space-shuttle'] = array( 'space, shuttle' );
	$aliases['leaf'] = array( 'leaf' );
	$aliases['font'] = array( 'editor, font' );
	$aliases['bold'] = array( 'editor, bold' );
	$aliases['medium'] = array( 'medium' );
	$aliases['italic'] = array( 'editor, italic' );
	$aliases['header'] = array( 'header' );
	$aliases['paragraph'] = array( 'paragraph' );
	$aliases['text-height'] = array( 'editor, text, height' );
	$aliases['text-width'] = array( 'editor, text, width' );
	$aliases['align-left'] = array( 'editor, align, left' );
	$aliases['align-center'] = array( 'editor, align, center' );
	$aliases['align-right'] = array( 'editor, align, right' );
	$aliases['align-justify'] = array( 'editor, align, justify' );
	$aliases['list'] = array( 'editor, list' );
	$aliases['indent-left'] = array( 'editor, indent' );
	$aliases['indent-right'] = array( 'editor, indent' );
	$aliases['list-bullet'] = array( 'list, list-ul' );
	$aliases['list-numbered'] = array( 'list' );
	$aliases['strike'] = array( 'strike' );
	$aliases['underline'] = array( 'underline' );
	$aliases['superscript'] = array( 'editor, superscript' );
	$aliases['subscript'] = array( 'editor, subscript' );
	$aliases['table'] = array( 'table' );
	$aliases['columns'] = array( 'columns' );
	$aliases['crop'] = array( 'crop' );
	$aliases['scissors'] = array( 'scissors, cut' );
	$aliases['paste'] = array( 'paste' );
	$aliases['briefcase'] = array( 'briefcase' );
	$aliases['suitcase'] = array( 'suitcase' );
	$aliases['ellipsis'] = array( 'ellipsis' );
	$aliases['ellipsis-vert'] = array( 'ellipsis' );
	$aliases['off'] = array( 'off' );
	$aliases['road'] = array( 'road' );
	$aliases['list-alt'] = array( 'list' );
	$aliases['qrcode'] = array( 'qrcode' );
	$aliases['barcode'] = array( 'barcode' );
	$aliases['book'] = array( 'book' );
	$aliases['adjust'] = array( 'adjust, contrast' );
	$aliases['tint'] = array( 'tint' );
	$aliases['toggle-off'] = array( 'toggle, switch, off, disable' );
	$aliases['toggle-on'] = array( 'toggle, switch, on, enable' );
	$aliases['check'] = array( 'check, check-square-o' );
	$aliases['check-empty'] = array( 'check, empty, square, square-o' );
	$aliases['circle'] = array( 'circle' );
	$aliases['circle-empty'] = array( 'circle, radio' );
	$aliases['circle-thin'] = array( 'circle' );
	$aliases['circle-notch'] = array( 'circle, notch, circle-o-notch' );
	$aliases['dot-circled'] = array( 'dot, radio' );
	$aliases['asterisk'] = array( 'asterisk' );
	$aliases['gift'] = array( 'gift, present, package, box' );
	$aliases['fire'] = array( 'fire' );
	$aliases['magnet'] = array( 'magnet' );
	$aliases['chart-bar'] = array( 'chart, bar, diagram, bar-chart' );
	$aliases['chart-area'] = array( 'chart, area-chart' );
	$aliases['chart-pie'] = array( 'chart, pie, pie-chart' );
	$aliases['chart-line'] = array( 'chart, line-chart' );
	$aliases['ticket'] = array( 'ticket' );
	$aliases['credit-card'] = array( 'card, plastic, credit' );
	$aliases['floppy'] = array( 'floppy, save' );
	$aliases['megaphone'] = array( 'megaphone, bullhorn' );
	$aliases['hdd'] = array( 'hdd, drive, disk' );
	$aliases['key'] = array( 'key' );
	$aliases['fork'] = array( 'fork, junction, flow' );
	$aliases['rocket'] = array( 'rocket' );
	$aliases['bug'] = array( 'bug' );
	$aliases['certificate'] = array( 'certificate' );
	$aliases['tasks'] = array( 'tasks' );
	$aliases['filter'] = array( 'filter' );
	$aliases['beaker'] = array( 'beaker, flask' );
	$aliases['magic'] = array( 'magic' );
	$aliases['cab'] = array( 'cab, auto, car' );
	$aliases['taxi'] = array( 'taxi, auto, car' );
	$aliases['truck'] = array( 'truck, auto, car' );
	$aliases['bus'] = array( 'bus, auto' );
	$aliases['bicycle'] = array( 'bicycle' );
	$aliases['motorcycle'] = array( 'motorcycle' );
	$aliases['train'] = array( 'train' );
	$aliases['subway'] = array( 'subway' );
	$aliases['ship'] = array( 'ship, boat, swim' );
	$aliases['money'] = array( 'money, banknote' );
	$aliases['euro'] = array( 'euro, money' );
	$aliases['pound'] = array( 'pound, gpb, money' );
	$aliases['dollar'] = array( 'dollar, usd, money' );
	$aliases['rupee'] = array( 'rupee, inr, money' );
	$aliases['yen'] = array( 'yen, jpy, renminbi, cny, money' );
	$aliases['rouble'] = array( 'rouble, rub, rur, ruble, money' );
	$aliases['shekel'] = array( 'ils, shekel, sheqel' );
	$aliases['try'] = array( 'try, lira' );
	$aliases['won'] = array( 'won, krw, money' );
	$aliases['bitcoin'] = array( 'bitcoin, money' );
	$aliases['viacoin'] = array( 'viacoin' );
	$aliases['sort'] = array( 'sort' );
	$aliases['sort-down'] = array( 'sort' );
	$aliases['sort-up'] = array( 'sort' );
	$aliases['sort-alt-up'] = array( 'sort' );
	$aliases['sort-alt-down'] = array( 'sort' );
	$aliases['sort-name-up'] = array( 'sort' );
	$aliases['sort-name-down'] = array( 'sort' );
	$aliases['sort-number-up'] = array( 'sort' );
	$aliases['sort-number-down'] = array( 'sort' );
	$aliases['hammer'] = array( 'hammer, legal, gavel' );
	$aliases['gauge'] = array( 'gauge, indicator, dashboard, tachometer' );
	$aliases['sitemap'] = array( 'sitemap' );
	$aliases['spinner'] = array( 'spinner' );
	$aliases['coffee'] = array( 'coffee, cup' );
	$aliases['food'] = array( 'food, eat' );
	$aliases['beer'] = array( 'beer' );
	$aliases['user-md'] = array( 'medic' );
	$aliases['stethoscope'] = array( 'stethoscope' );
	$aliases['heartbeat'] = array( 'heartbeat' );
	$aliases['ambulance'] = array( 'ambulance' );
	$aliases['medkit'] = array( 'medkit' );
	$aliases['h-sigh'] = array( 'hospital' );
	$aliases['bed'] = array( 'bed, sleep' );
	$aliases['hospital'] = array( 'hospital      ' );
	$aliases['building'] = array( 'building' );
	$aliases['building-filled'] = array( 'building, building-o' );
	$aliases['bank'] = array( 'bank, university, institution, temple, columns' );
	$aliases['smile'] = array( 'emoticon, sniley, smile, happy, smile-o' );
	$aliases['frown'] = array( 'emoticon, sniley, frown, frown-o' );
	$aliases['meh'] = array( 'emoticon, sniley, meh' );
	$aliases['anchor'] = array( 'anchor' );
	$aliases['terminal'] = array( 'terminal, prompt' );
	$aliases['eraser'] = array( 'eraser' );
	$aliases['puzzle'] = array( 'puzzle' );
	$aliases['shield'] = array( 'shield, chest' );
	$aliases['extinguisher'] = array( 'extinguisher' );
	$aliases['bullseye'] = array( 'bullseye' );
	$aliases['wheelchair'] = array( 'wheelchair, accessibility' );
	$aliases['language'] = array( 'language' );
	$aliases['graduation-cap'] = array( 'graduation cap' );
	$aliases['paw'] = array( 'paw' );
	$aliases['spoon'] = array( 'spoon' );
	$aliases['cube'] = array( 'cube' );
	$aliases['cubes'] = array( 'cubes' );
	$aliases['recycle'] = array( 'recycle' );
	$aliases['tree'] = array( 'tree' );
	$aliases['database'] = array( 'database' );
	$aliases['server'] = array( 'server' );
	$aliases['lifebuoy'] = array( 'lifebuoy, support' );
	$aliases['rebel'] = array( 'rebel' );
	$aliases['empire'] = array( 'empire' );
	$aliases['bomb'] = array( 'bomb' );
	$aliases['soccer-ball'] = array( 'soccer, ball' );
	$aliases['tty'] = array( 'tty, teletype' );
	$aliases['binoculars'] = array( 'binoculars ' );
	$aliases['plug'] = array( 'plug' );
	$aliases['newspaper'] = array( 'newspaper' );
	$aliases['calc'] = array( 'calculator' );
	$aliases['copyright'] = array( 'copyright' );
	$aliases['at'] = array( 'at' );
	$aliases['eyedropper'] = array( 'eyedropper, pipette' );
	$aliases['brush'] = array( 'brush' );
	$aliases['birthday'] = array( 'cake, birthday' );
	$aliases['diamond'] = array( 'diamond' );
	$aliases['street-view'] = array( 'street, view' );
	$aliases['venus'] = array( 'venus, astrology' );
	$aliases['mars'] = array( 'mars, astrology' );
	$aliases['mercury'] = array( 'mercury, astrology' );
	$aliases['transgender'] = array( 'transgender, astrology' );
	$aliases['transgender-alt'] = array( 'transgender, astrology' );
	$aliases['venus-double'] = array( 'venus, astrology' );
	$aliases['mars-double'] = array( 'mars, astrology' );
	$aliases['venus-mars'] = array( 'venus, mars, astrology' );
	$aliases['mars-stroke'] = array( 'mars, astrology' );
	$aliases['mars-stroke-v'] = array( 'mars, astrology' );
	$aliases['mars-stroke-h'] = array( 'mars, astrology' );
	$aliases['neuter'] = array( 'neuter, astrology' );
	$aliases['cc-visa'] = array( 'visa, card' );
	$aliases['cc-mastercard'] = array( 'mastercard, card' );
	$aliases['cc-discover'] = array( 'discover, card' );
	$aliases['cc-amex'] = array( 'amex, card' );
	$aliases['cc-paypal'] = array( 'paypal, card' );
	$aliases['cc-stripe'] = array( 'stripe, card' );
	$aliases['adn'] = array( 'adn' );
	$aliases['android'] = array( 'android' );
	$aliases['angellist'] = array( 'angellist' );
	$aliases['apple'] = array( 'apple' );
	$aliases['behance'] = array( 'behance' );
	$aliases['behance-squared'] = array( 'behance' );
	$aliases['bitbucket'] = array( 'bitbucket' );
	$aliases['bitbucket-squared'] = array( 'bitbucket' );
	$aliases['buysellads'] = array( 'buysellads' );
	$aliases['cc'] = array( 'cc' );
	$aliases['codeopen'] = array( 'codeopen' );
	$aliases['connectdevelop'] = array( 'connectdevelop' );
	$aliases['css3'] = array( 'css3' );
	$aliases['dashcube'] = array( 'dashcube' );
	$aliases['delicious'] = array( 'delicious' );
	$aliases['deviantart'] = array( 'deviantart' );
	$aliases['digg'] = array( 'digg' );
	$aliases['dribbble'] = array( 'dribbble' );
	$aliases['dropbox'] = array( 'dropbox' );
	$aliases['drupal'] = array( 'drupal' );
	$aliases['facebook'] = array( 'facebook' );
	$aliases['facebook-squared'] = array( 'facebook' );
	$aliases['facebook-official'] = array( 'facebook' );
	$aliases['flickr'] = array( 'flickr' );
	$aliases['forumbee'] = array( 'forumbee' );
	$aliases['foursquare'] = array( 'foursquare' );
	$aliases['git-squared'] = array( 'git' );
	$aliases['git'] = array( 'git' );
	$aliases['github'] = array( 'github' );
	$aliases['github-squared'] = array( 'github' );
	$aliases['github-circled'] = array( 'github' );
	$aliases['gittip'] = array( 'gittip' );
	$aliases['google'] = array( 'google' );
	$aliases['gplus'] = array( 'google, plus' );
	$aliases['gplus-squared'] = array( 'google, plus' );
	$aliases['gwallet'] = array( 'google wallet' );
	$aliases['hacker-news'] = array( 'hacker news, hn' );
	$aliases['html5'] = array( 'html5' );
	$aliases['instagram'] = array( 'instagram' );
	$aliases['ioxhost'] = array( 'ioxhost ' );
	$aliases['joomla'] = array( 'joomla' );
	$aliases['jsfiddle'] = array( 'jsfiddle' );
	$aliases['lastfm'] = array( 'lastfm' );
	$aliases['lastfm-squared'] = array( 'lastfm' );
	$aliases['leanpub'] = array( 'leanpub' );
	$aliases['linkedin-squared'] = array( 'linkedin' );
	$aliases['linux'] = array( 'linux' );
	$aliases['linkedin'] = array( 'linkedin' );
	$aliases['maxcdn'] = array( 'maxcdn' );
	$aliases['meanpath'] = array( 'meanpath' );
	$aliases['openid'] = array( 'openid' );
	$aliases['pagelines'] = array( 'pagelines' );
	$aliases['paypal'] = array( 'paypal' );
	$aliases['pied-piper-squared'] = array( 'pied-piper' );
	$aliases['pied-piper-alt'] = array( 'pied-piper' );
	$aliases['pinterest'] = array( 'pinterest' );
	$aliases['pinterest-circled'] = array( 'pinterest' );
	$aliases['pinterest-squared'] = array( 'pinterest' );
	$aliases['qq'] = array( 'qq' );
	$aliases['reddit'] = array( 'reddit' );
	$aliases['reddit-squared'] = array( 'reddit' );
	$aliases['renren'] = array( 'renren' );
	$aliases['sellsy'] = array( 'sellsy' );
	$aliases['shirtsinbulk'] = array( 'shirtsinbulk' );
	$aliases['simplybuilt'] = array( 'simplybuilt' );
	$aliases['skyatlas'] = array( 'skyatlas' );
	$aliases['skype'] = array( 'skype' );
	$aliases['slack'] = array( 'slack' );
	$aliases['slideshare'] = array( 'slideshare' );
	$aliases['soundcloud'] = array( 'soundcloud' );
	$aliases['spotify'] = array( 'spotify' );
	$aliases['stackexchange'] = array( 'stackexchange' );
	$aliases['stackoverflow'] = array( 'stackoverflow' );
	$aliases['steam'] = array( 'steam' );
	$aliases['steam-squared'] = array( 'steam' );
	$aliases['stumbleupon'] = array( 'stumbleupon' );
	$aliases['stumbleupon-circled'] = array( 'stumbleupon' );
	$aliases['tencent-weibo'] = array( 'tencent-weibo' );
	$aliases['trello'] = array( 'trello' );
	$aliases['tumblr'] = array( 'tumblr' );
	$aliases['tumblr-squared'] = array( 'tumblr' );
	$aliases['twitch'] = array( 'twitch' );
	$aliases['twitter-squared'] = array( 'twitter' );
	$aliases['twitter'] = array( 'twitter' );
	$aliases['vimeo-squared'] = array( 'vimeo' );
	$aliases['vine'] = array( 'vine' );
	$aliases['vkontakte'] = array( 'vkontakte' );
	$aliases['whatsapp'] = array( 'whatsapp' );
	$aliases['wechat'] = array( 'wechat' );
	$aliases['weibo'] = array( 'weibo' );
	$aliases['windows'] = array( 'windows' );
	$aliases['wordpress'] = array( 'wordpress' );
	$aliases['xing'] = array( 'xing' );
	$aliases['xing-squared'] = array( 'xing' );
	$aliases['yelp'] = array( 'yelp' );
	$aliases['youtube'] = array( 'youtube' );
	$aliases['yahoo'] = array( 'yahoo' );
	$aliases['youtube-squared'] = array( 'youtube' );
	$aliases['youtube-play'] = array( 'youtube, video' );
	$aliases['blank'] = array( 'blank, square' );
	$aliases['lemon'] = array(  );
	$aliases['y-combinator'] = array( 'yc' );
	$aliases['battery-4'] = array( 'battery-full' );
	$aliases['battery-3'] = array( 'battery-three-quarters' );
	$aliases['battery-2'] = array( 'battery-half' );
	$aliases['battery-1'] = array( 'battery-quarter' );
	$aliases['battery-0'] = array( 'battery-empty' );
	$aliases['hourglass-1'] = array( 'hourglass-start' );
	$aliases['hourglass-2'] = array( 'hourglass-half' );
	$aliases['hourglass-3'] = array( 'hourglass-end' );
	$aliases['hand-grab-o'] = array( 'hand-rock-o' );
	$aliases['hand-paper-o'] = array( 'hand-stop-o' );
	$aliases['television'] = array( 'tv' );
	$aliases['asl-interpreting'] = array( 'deaf, deafness, hard-of-hearing' );
	$aliases['sign-language'] = array( 'signing' );
	$aliases['google-plus-circle'] = array( 'google-plus-official' );
	$aliases['font-awesome'] = array( 'fa' );

	return $aliases;
}