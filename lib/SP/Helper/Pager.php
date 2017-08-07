<?php

/**
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class SP_Helper_Pager
{
    protected $on_page = 20;
    protected $pager_count = 5;
    protected $total;
    protected $href_tmpl;           // some-list/%page
    protected $href_page_part;      // page/%page_number
    protected $link_tmpl;           // <a href="%href">%text</a>
    protected $current_page_tmpl;   // <span>%text</span>
    protected $layout_tmpl;         // <div class="pager">%text</div>
    protected $first_page_text;
    protected $last_page_text;
    protected $current;
    protected $data;

    public function __construct($params = array())
    {
        $this->init($params);
    }

    public function init($params = array())
    {
        if (is_array($params)) {
            foreach ($params as $name => $param) {
                $this->$name = $param;
            }
        }
    }

    public function process($current)
    {
        $total = $this->total;
        $on_page = $this->on_page;
        $pager_count = $this->pager_count;
        $plus = 0;

        if ($total <= $on_page) {
            return false;
        }

        if ($total % $on_page) {
            $plus = 1;
        }
        $out['last_page'] = (int) (floor($total / $on_page)) + $plus;

        $out['prev'] = $current - 1;

        if ($current <= 1) {
            $out['is_first'] = true;
            $current = 1;
            unset($out['prev']);
        }
        $out['next'] = $current + 1;

        if ($current >= $out['last_page']) {
            $out['is_last'] = true;
            $current = $out['last_page'];
            unset($out['next']);
        }

        $out['current'] = $current;

        if ($pager_count) {
            if ($out['last_page'] <= $pager_count) {
                $before = 1;
                $after = $out['last_page'];
            } else {
                $near_count = (int) floor($pager_count / 2);
                $before = $current - $near_count;
                $after = $current + $near_count;
                $before_check = $before - 1;
                if ($before_check < 0) {
                    $before = 1;
                    $after = $after - $before_check;
                    if ($after > $out['last_page']) {
                        $after = $out['last_page'];
                    }
                }

                $after_check = $after - $out['last_page'];
                if ($after_check > 0) {
                    $after = $out['last_page'];
                    $before = $before - $after_check;
                    if ($before < 1) {
                        $before = 1;
                    }
                }
            }

            for ($i = $before; $i <= $after; $i++) {
                $out['pages'][] = $i;
            }
        }
        $this->data = $out;
        return true;
    }

    public function getFirst()
    {
        return 1;
    }

    public function getLast()
    {
        return $this->data['last_page'];
    }

    public function getPrev()
    {
        return $this->data['prev'];
    }

    public function getCurrent()
    {
        return $this->data['current'];
    }

    public function getNext()
    {
        return $this->data['next'];
    }

    public function isLast()
    {
        return $this->data['is_last'];
    }

    public function isFirst()
    {
        return $this->data['is_first'];
    }

    public function getPages()
    {
        return $this->data['pages'];
    }

    public function getPagerCount()
    {
        return $this->pager_count;
    }

    public function renderHref($href, $current, $replaces = array())
    {
        if (!$href) {
            $href = $this->href_tmpl;
        }
        if (!$current) {
            $current = $this->getCurrent();
        }
        if (is_array($replaces) and count($replaces) > 0) {
            $keys = array_keys($replaces);
            $values = array_values($replaces);
        }
        $keys[] = '%page';
        var_dump($this->href_page_part);
        $values[] = $this->href_page_part ? str_replace('%page_number', $current, $this->href_page_part) : $current;
        return str_replace($keys, $values, $href);
    }

    public function set($name, $value)
    {
        $this->$name = $value;
        return $this;
    }

    public function get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }
    }

    public function render()
    {
        return $this->display($this->current, array(), true);
    }

    public function display($current, $replaces = array(), $return = false)
    {
        if (!$this->process($current)) {
            return '';
        }
        
        $content = '';

        foreach ($this->getPages() as $page) {
            if ($page == $this->getCurrent()) {
                $text = str_replace('%text', $page, $this->current_page_tmpl);
            } else {
                $page_part = $this->href_page_part ? str_replace('%page_number', $page, $this->href_page_part) : $page;
                $href = str_replace('%page', $page_part, $this->href_tmpl);
                $text = str_replace(array('%href', '%text'), array($href, $page), $this->link_tmpl);
            }
            $content.= $text;
        }

        if ($this->getLast() > $this->getPagerCount()) {
            $info = 'Страница&nbsp;' . $this->getCurrent() . '&nbsp;из&nbsp;' . $this->getLast() . '<br/>';
        } else {
            $info = '';
        }

        $out = str_replace(array('%info', '%text'), array($info, $content), $this->layout_tmpl);
        
        if ($return) {
            return $out;
        }
        
        echo $out;
    }
}
