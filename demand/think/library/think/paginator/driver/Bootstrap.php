<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zhangyajun <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think\paginator\driver;

use think\Paginator;

class Bootstrap extends Paginator
{

    /**
     * 上一页按钮
     * @param string $text
     * @return string
     */
    protected function getPreviousButton($text = "上一页")
    {

        if ($this->currentPage() <= 1) {
            return $this->getDisabledTextWrapper($text);
        }

        $url = $this->url(
            $this->currentPage() - 1
        );

        return $this->getPageLinkWrapper($url, $text);
    }

    /**
     * 下一页按钮
     * @param string $text
     * @return string
     */
    protected function getNextButton($text = '下一页')
    {
        if (!$this->hasMore) {
            return $this->getDisabledTextWrapper($text);
        }

        $url = $this->url($this->currentPage() + 1);

        return $this->getPageLinkWrapper($url, $text);
    }

    /**
     * 页码按钮
     * @return string
     */
    protected function getLinks()
    {
        if ($this->simple)
            return '';

        $block = [
            'first'  => null,
            'slider' => null,
            'last'   => null
        ];

        $side   = 3;
        $window = $side * 2;

        if ($this->lastPage < $window + 6) {
            $block['first'] = $this->getUrlRange(1, $this->lastPage);
        } elseif ($this->currentPage <= $window) {
            $block['first'] = $this->getUrlRange(1, $window + 2);
            $block['last']  = $this->getUrlRange($this->lastPage - 1, $this->lastPage);
        } elseif ($this->currentPage > ($this->lastPage - $window)) {
            $block['first'] = $this->getUrlRange(1, 2);
            $block['last']  = $this->getUrlRange($this->lastPage - ($window + 2), $this->lastPage);
        } else {
            $block['first']  = $this->getUrlRange(1, 2);
            $block['slider'] = $this->getUrlRange($this->currentPage - $side, $this->currentPage + $side);
            $block['last']   = $this->getUrlRange($this->lastPage - 1, $this->lastPage);
        }

        $html = '';

        if (is_array($block['first'])) {
            $html .= $this->getUrlLinks($block['first']);
        }

        if (is_array($block['slider'])) {
            $html .= $this->getDots();
            $html .= $this->getUrlLinks($block['slider']);
        }

        if (is_array($block['last'])) {
            $html .= $this->getDots();
            $html .= $this->getUrlLinks($block['last']);
        }

        return $html;
    }

    /**
     * 渲染分页html
     * @return mixed
     */
    public function render()
    {
        //if ($this->hasPages()) {
            if ($this->simple) {
                if($this->lastPage == 1){
                    return sprintf(
                        '<ul class="pagination">%s</ul>',
                        "<li style='color:#3979C7;font-weight: normal;'>共<b>" . $this->lastPage . "</b>页，当前第<b>" . $this->currentPage . "</b>页，合计<b>" . $this->total . "</b>条数据</li>&nbsp;&nbsp;"
                    );
                }else if($this->lastPage < 10){
                    return sprintf(
                        '<ul class="pager">%s %s</ul>',
                        "<li style='color:#3979C7;font-weight: normal;'>共<b>" . $this->lastPage . "</b>页，当前第<b>" . $this->currentPage . "</b>页，合计<b>" . $this->total . "</b>条数据</li>&nbsp;&nbsp;",
                        $this->getLinks()
                    );
                }else if($this->currentPage == 1) {
                    return sprintf(
                        '<ul class="pager">%s %s %s</ul>',
                        "<li style='color:#3979C7;font-weight: normal;'>共<b>" . $this->lastPage . "</b>页，当前第<b>" . $this->currentPage . "</b>页，合计<b>" . $this->total . "</b>条数据</li>&nbsp;&nbsp;",
                        $this->getLinks(),
                        $this->getNextButton()
                    );
                }else if($this->currentPage == $this->lastPage){
                    return sprintf(
                        '<ul class="pager">%s %s %s</ul>',
                        "<li style='color:#3979C7;font-weight: normal;'>共<b>" . $this->lastPage . "</b>页，当前第<b>" . $this->currentPage . "</b>页，合计<b>" . $this->total . "</b>条数据</li>&nbsp;&nbsp;",
                        $this->getPreviousButton(),
                        $this->getLinks()
                    );
                }else {
                    return sprintf(
                        '<ul class="pager">%s %s %s %s</ul>',
                        "<li style='color:#3979C7;font-weight: normal;'>共<b>" . $this->lastPage . "</b>页，当前第<b>" . $this->currentPage . "</b>页，合计<b>" . $this->total . "</b>条数据</li>&nbsp;&nbsp;",
                        $this->getPreviousButton(),
                        $this->getLinks(),
                        $this->getNextButton()
                    );
                }
            } else {
                if($this->lastPage == 1){
                    return sprintf(
                        '<ul class="pagination">%s</ul>',
                        "<li style='color:#3979C7;font-weight: normal;'>共<b>" . $this->lastPage . "</b>页，当前第<b>" . $this->currentPage . "</b>页，合计<b>" . $this->total . "</b>条数据</li>&nbsp;&nbsp;"
                    );
                }else if($this->lastPage < 10){
                    return sprintf(
                        '<ul class="pagination">%s %s</ul>',
                        "<li style='color:#3979C7;font-weight: normal;'>共<b>" . $this->lastPage . "</b>页，当前第<b>" . $this->currentPage . "</b>页，合计<b>" . $this->total . "</b>条数据</li>&nbsp;&nbsp;",
                        $this->getLinks()
                    );
                }else if($this->currentPage == 1) {
                    return sprintf(
                        '<ul class="pagination">%s %s %s</ul>',
                        "<li style='color:#3979C7;font-weight: normal;'>共<b>" . $this->lastPage . "</b>页，当前第<b>" . $this->currentPage . "</b>页，合计<b>" . $this->total . "</b>条数据</li>&nbsp;&nbsp;",
                        $this->getLinks(),
                        $this->getNextButton()
                    );
                }else if($this->currentPage == $this->lastPage){
                    return sprintf(
                        '<ul class="pagination">%s %s %s</ul>',
                        "<li style='color:#3979C7;font-weight: normal;'>共<b>" . $this->lastPage . "</b>页，当前第<b>" . $this->currentPage . "</b>页，合计<b>" . $this->total . "</b>条数据</li>&nbsp;&nbsp;",
                        $this->getPreviousButton(),
                        $this->getLinks()
                    );
                }else {
                    return sprintf(
                        '<ul class="pagination">%s %s %s %s</ul>',
                        "<li style='color:#3979C7;font-weight: normal;'>共<b>" . $this->lastPage . "</b>页，当前第<b>" . $this->currentPage . "</b>页，合计<b>" . $this->total . "</b>条数据</li>&nbsp;&nbsp;",
                        $this->getPreviousButton(),
                        $this->getLinks(),
                        $this->getNextButton()
                    );
                }
            }
        //}
    }

    /**
     * 生成一个可点击的按钮
     *
     * @param  string $url
     * @param  int    $page
     * @return string
     */
    protected function getAvailablePageWrapper($url, $page)
    {
        return '<li><a href="' . htmlentities($url) . '">' . $page . '</a></li>';
    }

    /**
     * 生成一个禁用的按钮
     *
     * @param  string $text
     * @return string
     */
    protected function getDisabledTextWrapper($text)
    {
        return '<li class="disabled"><span>' . $text . '</span></li>';
    }

    /**
     * 生成一个激活的按钮
     *
     * @param  string $text
     * @return string
     */
    protected function getActivePageWrapper($text)
    {
        return '<li class="active"><span>' . $text . '</span></li>';
    }

    /**
     * 生成省略号按钮
     *
     * @return string
     */
    protected function getDots()
    {
        return $this->getDisabledTextWrapper('...');
    }

    /**
     * 批量生成页码按钮.
     *
     * @param  array $urls
     * @return string
     */
    protected function getUrlLinks(array $urls)
    {
        $html = '';

        foreach ($urls as $page => $url) {
            $html .= $this->getPageLinkWrapper($url, $page);
        }

        return $html;
    }

    /**
     * 生成普通页码按钮
     *
     * @param  string $url
     * @param  int    $page
     * @return string
     */
    protected function getPageLinkWrapper($url, $page)
    {
        if ($page == $this->currentPage()) {
            return $this->getActivePageWrapper($page);
        }

        return $this->getAvailablePageWrapper($url, $page);
    }
}
