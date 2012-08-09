<?php

namespace EricClemmons\Bundle\StaticBundle\Parser;

use Knp\Bundle\MarkdownBundle\Parser\Preset\Max;

class MarkdownParser extends Max
{
    // Originally from MarkdownParser
    protected function _doCodeBlocks_callback($matches)
    {
        $codeblock = $matches[1];

        $codeblock = $this->outdent($codeblock);
        $codeblock = htmlspecialchars($codeblock, ENT_NOQUOTES);

        # trim leading newlines and trailing newlines
        $codeblock = preg_replace('/\A\n+|\n+\z/', '', $codeblock);

        $codeblock = "<pre><code class=\"prettyprint linenums\">$codeblock\n</code></pre>";
        return "\n\n".$this->hashBlock($codeblock)."\n\n";
    }
}
