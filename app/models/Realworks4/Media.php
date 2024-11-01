<?php

namespace Tussendoor\PropertyWebsite\Realworks4;

use Tussendoor\PropertyWebsite\Module\PostProcessor;

class Media extends PostProcessor
{
    public function get()
    {
        $media = [];

        foreach ($this->post->fotos as $photo) {
            $media['images'][] = [
                'id'        => $photo->ID,
                'naam'      => get_the_title(),
                'url'       => get_the_permalink(),
                'source'    => wp_get_attachment_url($photo->ID),
            ];
        }

        foreach ($this->post->plattegronden as $photo) {
            $media['plans'][] = [
                'id'        => $photo->ID,
                'naam'      => get_the_title(),
                'url'       => get_the_permalink(),
                'source'    => wp_get_attachment_url($photo->ID),
            ];
        }

        foreach ($this->post->brochures as $photo) {
            $media['brochures'][] = [
                'id'        => $photo->ID,
                'naam'      => get_the_title(),
                'url'       => get_the_permalink(),
                'source'    => wp_get_attachment_url($photo->ID),
            ];
        }

        foreach ($this->post->groep('video') as $video) {
            $media['videos'][] = [
                'id'        => $video->ID,
                'naam'      => get_the_title(),
                'url'       => get_the_permalink(),
                'source'    => wp_get_attachment_url($video->ID),
            ];
        }

        foreach ($this->post->groep('connected_partner') as $panorama) {
            if (wp_attachment_is_image($panorama->ID)) {
                $media['panoramas'][] = [
                    'id'        => $panorama->ID,
                    'naam'      => get_the_title(),
                    'url'       => get_the_permalink(),
                    'source'    => wp_get_attachment_url($panorama->ID),
                ];
            }
        }

        return $media;
    }
}
