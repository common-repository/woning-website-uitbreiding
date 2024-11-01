<?php

namespace Tussendoor\PropertyWebsite\Module;

// use Realworks\Common\Post;

class Data
{
    protected $data = [];

    public function setProperties(PostProcessor $properties)
    {
        $this->data = array_merge($this->data, $properties->get());

        return $this;
    }

    public function setMedia(PostProcessor $media)
    {
        $this->data['media'] = $media->get();

        return $this;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
