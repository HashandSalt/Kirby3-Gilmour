<?php
/**
 *
 * Gilmour - Audio File ID3 Plugin for Kirby 3
 *
 * @version   0.0.6
 * @author    James Steel <https://hashandsalt.com>
 * @copyright James Steel <https://hashandsalt.com>
 * @link      https://github.com/HashandSalt/gilmour
 * @license   MIT <http://opensource.org/licenses/MIT>
 */

require('vendor/james-heinrich/getid3/getid3/getid3.php');

Kirby::plugin('hashandsalt/gilmour', [

		// Blueprints

		'blueprints' => [
		  // FILES
		  'files/audio' => __DIR__ . '/blueprints/files/audio.yml',
		],

		'fileMethods' => [

		// Fetch info from the ID tag
		'getIDtag' => function() {
			$getID3 = new getID3;
			$fetchid = $getID3->analyze($this->root());
			getid3_lib::CopyTagsToComments($fetchid);

			$data = $fetchid;

			// Check existence...
			$basicinfo = $data['comments_html']?? [];

			// Check for Album Artwork
			if ($coverimg = $data['comments']['picture'][0]['data']?? []) {
				$coverbase64 = 'data:image/jpeg;base64,'.base64_encode($coverimg);
			}	else {
  			$coverbase64 = '';
			}

			// Duration and Artwork
			$otherinfo = array(
				'cover'			=> array($coverbase64),
				'duration' 	=> array($data['playtime_string']),
			);

			// Merge all the info
			$audioinfo = array_merge($basicinfo, $otherinfo);

			if ($audioinfo) {
				$audioinfo['track'] = $audioinfo['track_number'];
				unset($audioinfo['track_number']);
				$audioinfo['year'] = array(substr(implode('', $audioinfo['year']), 0, 4));
			}

			return $audioinfo;

		},

		// Work info from the ID tag
		'id3' => function ($mediainfo = 'title') {
			$audiodata = $this->getIDtag($this);
			return implode('', $audiodata[$mediainfo]?? []) ;
		},

		// Get the Cover art from the MP3 File
		'getIDart' => function() {

			// File Path
			$contentpath = $this->parent()->root();

			// image file
			$audioartfilename = substr($this->filename(), 0, -4) . '-art.jpg';
			$audioartfilepath = $contentpath.'/'.$audioartfilename;
			$audioart = explode( ',', $this->id3('cover'));
			$audioartdecode = base64_decode($audioart[1]);

			// Meta File
			$audioartmetafilename = substr($this->filename(), 0, -4) . '-art.jpg.txt';
			$audioartmetafilepath = $contentpath.'/'.$audioartmetafilename;
			$metacontent = 'template: imagefocus';

			// Create Meta
			F::write($audioartmetafilepath, $metacontent);

			// Create Cover File
			F::write($audioartfilepath, $audioartdecode);

			return $audioartfilename;
		},

	],

'hooks' => [

	'file.create:after' => function ($file) {

		if (strpos($file->filename(), '.mp3') !== false) {
				$cover = $file->getIDart();
				$file->update([
					 'title' => $file->id3('title'),
					 'artist' => $file->id3('artist'),
					 'album' => $file->id3('album'),
					 'genre' => $file->id3('genre'),
					 'year'  => $file->id3('year'),
					 'composer' => $file->id3('composer'),
					 'duration' => $file->id3('duration'),
					 'track' => $file->id3('track'),
					 'cover' => $cover,
				 ]);

			 }
	},
]

]);
