<?php

namespace Webigniter\Libraries;

class GlobalFunctions
{
    public function getViewsList(): array
    {
        $views = [];

        $viewsList = directory_map('../app/Views', 1);

        if (($key = array_search('errors\\', $viewsList)) !== false) {
            unset($viewsList[$key]);
        }

        foreach($viewsList as $view)
        {
            $views[] = View::fromParams(['filename' => $view]);
        }

        return $views;
    }

}