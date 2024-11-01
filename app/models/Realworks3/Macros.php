<?php

namespace Tussendoor\PropertyWebsite\Realworks3;

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
            if ($field->post()->prijsTonen->isTrue()) {
                return trim($field->formatter('money').' '.$field->post()->koopconditie);
            } else {
                return 'Prijs op aanvraag';
            }
        });

        Wonen::formatter('huurprijs', function ($value, $field) {
            if ($field->post()->prijsTonen->isTrue()) {
                return trim($field->formatter('money').' '.$field->post()->huurconditie);
            } else {
                return 'Prijs op aanvraag';
            }
        });

        Wonen::custom('aantalBadkamers', function ($woning) {
            $count = $woning->woning->kelder->badkamers->count()
                + $woning->woning->kelder->badkamers->count()
                + $woning->woning->beganeGrondOfFlat->badkamers->count()
                + $woning->woning->verdieping->badkamers->count()
                + $woning->woning->zolder->badkamers->count()
                + $woning->woning->vliering->badkamers->count();

            foreach ($woning->woning->verdiepingen as $verdieping) {
                $count += count($verdieping->badkamers);
            }

            return $count;
        }, 'int');

        Wonen::custom('woningprijs', function ($woning) {
            if ($woning->koopprijs->hasValue() && $woning->huurprijs->hasValue()) {
                return $woning->koopprijs.' of '.$woning->huurprijs;
            } elseif ($woning->koopprijs->hasValue()) {
                return $woning->koopprijs;
            } else {
                return $woning->huurprijs;
            }
        });

        Wonen::custom('soortObject', function ($woning) {
            $string = 'Woonhuis';

            if ($woning->woning->appartement->soort->hasValue()) {
                $string = 'Appartement';
            }

            return $string;
        }, 'string');

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
            if (!is_null($woning->aantalVerdiepingen) && $woning->aantalVerdiepingen->hasValue()) {
                return $woning->aantalVerdiepingen->value();
            }
            
            if (!is_null($woning->appartement) && $woning->appartement->aantalWoonlagen->hasValue()) {
                return $woning->appartement->aantalWoonlagen->value();
            }

            return null;
        });
    }
}
