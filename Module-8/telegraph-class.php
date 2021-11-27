<?php

class TelegraphText
{
    public string $title = '';
    public string $text = '';

    public string $author = '';
    public string $published = '';
    public string $slug = '';

    public function __construct(string $author)
    {
        $this->author = $author;
        $this->published = date('H:i:s j-M-y (l)');
        $this->slug = mb_strtolower(substr($author, 0, 3) .'-'. date('j-M-y'));
    }
}

$firstText = new TelegraphText('John');

print_r($firstText);
