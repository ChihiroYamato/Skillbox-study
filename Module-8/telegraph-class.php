<?php

class TelegraphText
{
    private string $title = '';
    private string $text = '';
    private string $author = '';
    private string $published = '';
    private string $slug = '';

    private const DIRECTORY =  __DIR__ . '\publishing\\';


    public function __construct(string $author = '__default__')
    {
        if ($author !== '__default__') {
            $this->author = $author;
            $this->published = date('H:i:s j-M-y (l)');
            $this->slug = mb_strtolower(substr($author, 0, 3) . '-' . date('j-M-y'));
        }
    }

    public function storeText() : string|false
    {
        $storeTextArray = [
            'title' => $this->title,
            'text' => $this->text,
            'author' => $this->author,
            'published' => $this->published,
        ];
        if (false === file_put_contents(self::DIRECTORY . $this->slug, serialize($storeTextArray))) {
            return false;
        }
        return $this->slug;
    }

    public function loadText(string $slug) : string|false
    {
        if (!($loadTextArray = file_get_contents(self::DIRECTORY . $slug))) {
            return false;
        }
        $loadTextArray = unserialize($loadTextArray);
        if (!isset($loadTextArray['title'], $loadTextArray['text'], $loadTextArray['author'], $loadTextArray['published'])) {
            return false;
        }

        $this->title = $loadTextArray['title'];
        $this->text = $loadTextArray['text'];
        $this->author = $loadTextArray['author'];
        $this->published = $loadTextArray['published'];
        $this->slug = $slug;

        return $this->text;
    }

    public function editText (?string $text = null, ?string $title = null)
    {
        $this->text = trim($text) ?? $this->text;
        $this->title = trim($title) ?? $this->title;
    }
}

$firstPublication = new TelegraphText('John');
$firstPublication->editText('Hello word', 'Greeting');
$fSlug = $firstPublication->storeText();



$secondPublication = new TelegraphText();
$text = $secondPublication->loadText($fSlug);
echo $text;

