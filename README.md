# Kirby Gilmour : Work Audio file ID3 Data

A small plugin to with ID3 tags on audio files.

Features:

* File method for querying the file directly and getting the info straight from the ID tag.
* File hook that pulls the most useful into into a file meta on upload. This is quicker to retrieve later.
* Capable of ripping the Album Cover image embedded in the tag and storing it as a file in the panel (Oh yes!).

****

## Commercial Usage

This plugin is free but if you use it in a commercial project please consider to
- [make a donation 🍻](https://paypal.me/hashandsalt?locale.x=en_GB) or
- [buy a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/36141?link=1170)

****

## Installation

### Download

Download and copy this repository to `/site/plugins/kirby-gilmour`.

### Composer

```
composer require hashandsalt/kirby-gilmour
```

## How to use Gilmour

First, of course, you need some audio files on your page, and optionally, setup a files blueprint to store data. You can use the one in the plugin by extending
it into your own blueprints, or just copy it and use it as a starting point.

The following information will be available:

* album
* artist
* title
* track
* composer
* genre
* year
* duration


## Usage

You can use the file method to get the data directly from an audio file:

```
<?= $file->id3('artist') ?>
```

Or you can use the data fetched by the hook:

```
<?= $file->artist() ?>
```

To get the cover art image:

```
<img src="<?= $file->id3('cover')?>">
```

Loop through some files getting the data directly:

```
<?php foreach($page->audio() as $mp3direct): ?>
<div class="album">

<?php if($coverimage = $mp3direct->cover()->tofile() ): ?>
  <img alt="<?= $mp3direct->title() ?> - <?= $mp3direct->album() ?>" src="<?= $coverimage->url()  ?>">
<?php endif ?>

  <ul>
    <li><?= $mp3direct->id3('album') ?></li>
    <li><?= $mp3direct->id3('artist') ?></li>
    <li><?= $mp3direct->id3('title') ?></li>
    <li><?= $mp3direct->id3('track') ?></li>
    <li><?= $mp3direct->id3('composer') ?></li>
    <li><?= $mp3direct->id3('genre') ?></li>
    <li><?= $mp3direct->id3('year') ?></li>
    <li><?= $mp3direct->id3('duration') ?></li>
  </ul>
</div>
<?php endforeach ?>
```

Loop through some files getting the data from meta:

```
<?php foreach($page->audio() as $mp3meta):?>
<div class="album">

<?php if($imagecover = $mp3meta->cover()->tofile() ): ?>
  <img alt="<?= $mp3meta->title() ?> - <?= $mp3meta->album() ?>" src="<?= $imagecover->url()  ?>">
<?php endif ?>

  <ul>
    <li><?= $mp3meta->album() ?></li>
    <li><?= $mp3meta->artist() ?></li>
    <li><?= $mp3meta->title() ?></li>
    <li><?= $mp3meta->track() ?></li>
    <li><?= $mp3meta->composer() ?></li>
    <li><?= $mp3meta->genre() ?></li>
    <li><?= $mp3meta->year() ?></li>
    <li><?= $mp3meta->duration() ?></li>
  </ul>
</div>
<?php endforeach ?>
```

## Why is it called Gilmour?

There is only one guitarist in the world and his name is [David Gilmour](https://en.wikipedia.org/wiki/David_Gilmour).

## License

MIT
