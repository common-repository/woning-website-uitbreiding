<?php

namespace Tussendoor\PropertyWebsite\Realworks4;

use Wonen;

class Macros
{
    public static function load()
    {
        Wonen::get('woning.installatie.CVKetelCombiketel')->parser(function ($value) {
            if ($value == 'nee' || $value == 'Nee') {
                return null;
            }

            return $value;
        });

        Wonen::formatter('koopprijs', function ($value, $field) {
            return $field->formatter('money').' '.$field->post()->koopconditie;
        });

        Wonen::formatter('huurprijs', function ($value, $field) {
            return $field->formatter('money').' '.$field->post()->huurconditie;
        });

        Wonen::custom('woningprijs', function ($woning) {
            if ($woning->koopprijs->hasValue() && $woning->huurprijs->hasValue()) {
                return $woning->koopprijs.' of '.$woning->huurprijs;
            } elseif ($woning->koopprijs->hasValue()) {
                return $woning->koopprijs;
            } else {
                return $woning->huurprijs;
            }
        });

        Wonen::custom('oppervlakteHoofdtuin', function ($woning) {
            $string = '';

            if ($woning->woning->hoofdtuin->oppervlakte->hasValue()) {
                $string .= $woning->woning->hoofdtuin->oppervlakte;
            }
            if ($woning->woning->hoofdtuin->lengte->hasValue() && $woning->woning->hoofdtuin->breedte->hasValue()) {
                $lengte = ($woning->woning->hoofdtuin->lengte->value() / 100);
                $breedte = ($woning->woning->hoofdtuin->breedte->value() / 100);
                if (empty($string)) {
                    $string .= $lengte.'m bij '.$breedte.'m';
                } else {
                    $string .= ' ('.$lengte.'m bij '.$breedte.'m)';
                }
            }

            return !empty($string) ? $string : null;
        }, 'string');

        Wonen::custom('woonlagen', function ($woning) {
            if (!is_null($woning->woning->appartement) && $woning->woning->appartement->aantalWoonlagen->hasValue()) {
                return $woning->woning->appartement->aantalWoonlagen->value();
            }

            if ($woning->woning->verdiepingen->count() !== 0) {
                return $woning->woning->verdiepingen->count();
            }

            return null;
        });
    }
}
