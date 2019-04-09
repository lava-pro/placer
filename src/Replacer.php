<?php

namespace Lava\Placer;

class Replacer
{
    /**
     * Placeholders
     *
     * @var array
     */
    protected $placeholders = [];

    /**
     * Replacements
     *
     * @var array
     */
    protected $replacements = [];

    /**
     * Open haystack, find and replace needles, save haystack
     *
     * @param  string|array  $placeholder  String or array to look for (the needles)
     * @param  string|array $replacement What to replace the needles for?
     * @return $this
     */
    public function setReplacements($placeholder, $replacement)
    {
        if (is_array($placeholder)) {
            $this->placeholders = array_merge($this->placeholders, $placeholder);
        } else {
            $this->placeholders[] = $placeholder;
        }

        if (is_array($replacement)) {
            $this->replacements = array_merge($this->replacements, $replacement);
        } else {
            $this->replacements[] = $replacement;
        }

        return $this;
    }

    /**
     * Fill all placeholders with their replacements
     *
     * @param  string $path The directory of the files containing placeholders
     * @return void
     */
    public function fill($path)
    {
        $templates = array_merge(
            glob($path . '/composer.json'),
            glob($path . '/*.md'),
            glob($path . '/*.php'),
            glob($path . '/src/*.php'),
            glob($path . '/src/Facades/*.php'),
            glob($path . '/config/*.php')
        );

        foreach ($templates as $file)
        {
            $this->fillInFile($file);
        }
    }

    /**
     * Fill placeholders in a single file
     *
     * @param  string $template     The file with the generic placeholders in it
     * @param  string|null $destiniation    Where to save, defaults to $template
     * @return $this
     */
    protected function fillInFile($template, $destination = null)
    {
        $destination = ($destination === null) ? $template : $destination;

        $fileBody = str_replace($this->placeholders, $this->replacements, file_get_contents($template));

        file_put_contents($destination, $fileBody);

        return $this;
    }

}
