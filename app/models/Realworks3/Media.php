<?php

namespace Tussendoor\PropertyWebsite\Realworks3;

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

        foreach ($this->post->media('Overig') as $overig) {
            if (get_post_mime_type($overig->ID) == 'video/mp4') {
                $media['videos'][] = [
                    'id'        => $overig->ID,
                    'naam'      => get_the_title(),
                    'url'       => get_the_permalink(),
                    'source'    => wp_get_attachment_url($overig->ID),
                ];
            }
        }

        foreach ($this->post->media('Overig') as $overig) {
            if (get_post_mime_type($overig->ID) == 'image/jpeg') {
                $media['panoramas'][] = [
                    'id'        => $overig->ID,
                    'naam'      => get_the_title(),
                    'url'       => get_the_permalink(),
                    'source'    => wp_get_attachment_url($overig->ID),
                ];
            }
        }

        return $media;
    }
}
