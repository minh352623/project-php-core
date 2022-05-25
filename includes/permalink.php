<?php
function getLinkService($slug)
{
    return 'dichvu/' . $slug . '.html';
}
function getPrefixLinkService($module = "")
{
    $prefixArr = [
        'services' => 'dich-vu',
        'pages' => 'trang',
        'portfolios' => 'du-an',
        'blog_categories' => 'danh-muc',
        'blog' => 'blog'

    ];
    if (!empty($prefixArr[$module])) {

        return $prefixArr[$module];
    }
    return false;
}
