<?php

namespace App\Support;

class HtmlPurifier
{
    public static function clean(string $html): string
    {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,br,strong,em,ul,ol,li,h2,h3,h4,blockquote,a[href|title],code,pre');
        $config->set('HTML.ForbiddenElements', 'script,style,iframe,object,embed');
        $config->set('Cache.DefinitionImpl', null);

        $purifier = new \HTMLPurifier($config);

        return $purifier->purify($html);
    }
}
