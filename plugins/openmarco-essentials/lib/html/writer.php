<?php

namespace Essentials\Html;

/**
 * Class HTML Writer
 * Document metadata
 * @method Writer base(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer head(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer link(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer meta(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer style(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer title(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Content sectioning
 * @method Writer address(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer article(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer aside(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer footer(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer header(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer h1(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer h2(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer h3(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer h4(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer h5(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer h6(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer hgroup(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer nav(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Text content
 * @method Writer dd(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer div(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer dl(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer dt(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer figcaption(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer figure(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer hr(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer li(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer main(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer ol(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer p(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer pre(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer ul(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Inline text semantics
 * @method Writer a(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer abbr(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer b(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer bdi(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer bdo(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer br(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer cite(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer code(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer data(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer dfn(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer em(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer i(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer kbd(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer mark(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer q(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer rp(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer rt(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer rtc(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer ruby(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer s(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer samp(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer small(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer span(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer strong(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer sub(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer sup(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer time(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer u(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer var(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer wbr(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Image and multimedia
 * @method Writer area(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer audio(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer img(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer map(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer track(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer video(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Embedded content
 * @method Writer embed(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer object(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer param(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer source(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Scripting
 * @method Writer canvas(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer noscript(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer script(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Demarcating edits
 * @method Writer del(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer ins(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Table content
 * @method Writer caption(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer col(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer colgroup(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer table(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer tbody(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer td(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer tfoot(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer th(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer thead(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer tr(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Forms
 * @method Writer button(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer datalist(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer fieldset(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer form(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer input(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer label(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer legend(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer meter(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer optgroup(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer option(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer output(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer progress(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer select(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer textarea(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Interactive elements
 * @method Writer details(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer dialog(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer menu(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer menuitem(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer summary(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * Web Components
 * @method Writer content(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer element(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer shadow(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 * @method Writer template(string|array $attributes = '', string|bool $contentOrClose = '', bool $close = false)
 */
class Writer
{
    /**
     * Content
     * @var string
     */
    private $html = '';

    /**
     * Array of non-closed tags
     * @var array
     */
    private $closes = array();


    /**
     * Convert array of attributes to string
     * @param array $attributes attributes
     * @return string
     */
    public static function get_attributes_string($attributes)
    {
        $string = '';

        if (null !== $attributes) {
            foreach ($attributes as $key => $value) {
                $string .= ' ' . $key;

                if (null !== $value) {
                    $string .= '="' . $value . '"';
                }
            }
        }

        return $string;
    }

    /**
     * Create new element
     * @param string $tag Tag name
     * @param string|array $attributes Attributes
     * @param string $text
     * @param string $closed
     * @return $this
     */
    private function _element($tag, $attributes = '', $text, $closed)
    {
        $end_tag = "</$tag>";
        $slash = '';
        $close_tag = '';

        if ($closed) {
            if (isset($text)) {
                $close_tag = $end_tag;
            } else {
                $slash = '/';
            }
        } else {
            $this->closes[] = $end_tag;
        }

        $this->html .= "<$tag{$attributes}{$slash}>$text{$close_tag}";

        return $this;
    }

    /**
     * Create new instance of Html class
     * @return $this
     */
    public static function init()
    {
        return new Writer();
    }

    /**
     * Clear all
     * @return $this
     */
    public function clear()
    {
        $this->html = '';
        $this->closes = array();

        return $this;
    }

    /**
     * Close last opened tag or some number tags
     * @param boolean|int $all if true than all tags, if number than count, otherwise one
     * @return $this
     */
    public function end($all = false)
    {
        if ($all) {
            $all = is_int($all) ? $all : count($this->closes);

            while ($this->closes && $all--) {
                $this->html .= array_pop($this->closes);
            }
        } else if (!empty($this->closes)) {
            $this->html .= array_pop($this->closes);
        }

        return $this;
    }

    /**
     * Insert an html
     * @param array|\Essentials\Html\Writer $html an \UI\Html_Writer object or array of its
     * @return $this
     */
    public function html($html)
    {
        if (!is_array($html)) {
            $html = array($html);
        }

        foreach ($html as $part) {
            $part = (object)$part;

            $this->html .= (string)$part;
        }

        return $this;
    }

    /**
     * Insert text
     * @param string $text
     * @return $this
     */
    public function text($text)
    {
        $this->html .= $text;

        return $this;
    }

    /**
     * Return finalized html string
     * @return string
     */
    public function to_string()
    {
        return (string)$this;
    }

    /**
     * Render html
     */
    public function out() {
        echo (string)$this;
    }

    /**
     * Return finalized html string
     * @return string
     */
    public function __toString()
    {
        return $this->end(true)->html;
    }

    /**
     * Magic method used to generate HTML tags
     * @param string $name name of called function
     * @param array $arguments array of parameters
     * @return $this
     */
    public function __call($name, $arguments)
    {
        $last = end($arguments);

        $attributes = '';
        $closed_tag = false;
        $text = null;

        if ('/' == $last) {
            $closed_tag = true;
            array_pop($arguments);
        }

        $count = count($arguments);

        if ($count > 0) {
            if (is_array($arguments[0])) {
                if (isset($arguments[0]['text'])) {
                    $text = $arguments[0]['text'];
                    unset($arguments[0]['text']);
                }

                $attributes = self::get_attributes_string($arguments[0]);
            } else {
                $attributes = ' ' . $arguments[0];
            }

            if ($count > 1) {
                $text = (string)$arguments[1];
            }
        }

        $this->_element($name, $attributes, $text, $closed_tag);

        return $this;
    }
}