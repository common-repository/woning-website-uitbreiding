<?php

namespace Tussendoor\PropertyWebsite\Module;

use Exception;
use Throwable;
use Realworks\Common\Post;

abstract class PostProcessor
{
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    abstract public function get();

    protected function rescue($callback, $default = false)
    {
        try {
            return $callback();
        } catch (Exception $e) {
            return $default;
        } catch (Throwable $e) {
            return $default;
        }
    }
}
