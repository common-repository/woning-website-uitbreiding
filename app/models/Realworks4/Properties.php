<?php

namespace Tussendoor\PropertyWebsite\Realworks4;

use Tussendoor\PropertyWebsite\Module\PostProcessor;

class Properties extends PostProcessor
{
    public $sources = [
        'virtualtours' => [
            'matterport.com',
            'virres.com',
            'roundme.com',
            'vastgoedmedia.com',
            'teken-visie.nl',
            'zien24.nl',
        ],
        'embedvideos' => [
            'youtube.com',
            'youtu.be',
            'vimeo.com',
        ],
        'floorplanners' => [
            'floorplanner.com',
        ],
    ];

    protected function getThirdPartyMedia()
    {
        $thirdpartymedia = [];

        foreach ($this->post->medialijst as $media) {
            // Continue early if it is not a third party url
            if (strpos($media->url, 'realworks.nl') !== false) continue;

            foreach ($this->sources as $type => $sources) {
                foreach ($sources as $source) {
                    if (strpos($media->url, $source) !== false) {

                        $thirdpartymedia[$type][] = [
                            'naam'         => $media->titel->render(),
                            'omschrijving' => $media->omschrijving->render(),
                            'url'          => $media->url->render(),
                        ];

                        // We found a match go to next media item
                        continue 3;
                    }
                }
            }

        }

        return $thirdpartymedia;
    }

    public function get()
    {
        $baseAttributes = $this->post->getAttributes();

        $additional = [
            'straatnaam'            => $this->post->adresNederlands->straatnaam->render(),
            'huisnummer'            => $this->post->adresNederlands->huisnummer->render(),
            'huisnummerToevoeging'  => $this->post->adresNederlands->huisnummerToevoeging->render(),
            'postcode'              => $this->post->adresNederlands->postcode->render(),
            'plaats'                => $this->post->adresNederlands->plaats->render(),
            'land'                  => $this->post->adresNederlands->land->render(),
            'latitude'              => $this->post->latitude->value(),
            'longitude'             => $this->post->longitude->value(),

            // // Basic data
            'isKoop'                => $this->post->isKoop,
            'isHuur'                => $this->post->isHuur,
            'soortObject'           => $this->post->woning->objecttype->render(),
            'bouwjaar'              => $this->post->woning->bouwjaar->value(),
            'energieklasse'         => $this->post->woning->energieklasse->render(),
            'woonhuis.soort'        => $this->post->woning->woonhuis->soort->render(),
            'status'                => $this->post->status->render(),
            'prijs'                 => $this->post->prijs->value(),
            'woningprijs'           => $this->post->woningprijs->value(),
            'koopprijs'             => $this->post->koopprijs->render(),
            'huurprijs'             => $this->post->huurprijs->render(),

            'appartement.soort'     => $this->post->woning->appartement->soort->render(),
            'bouwjaarPeriode'       => $this->post->woning->bouwjaarPeriode->value(),
            'bouwvorm'              => $this->post->bouwvorm->value(),
            'liggingen'             => $this->post->woning->liggingen->value(),
            'woonlagen'             => $this->post->woonlagen->value(),

            // Lot and room data
            'perceelOppervlakte'    => $this->post->woning->perceelOppervlakte->value(),
            'gebruiksoppervlakteWoonfunctie' => $this->post->woning->gebruiksoppervlakteWoonfunctie->value(),
            'inhoud'                => $this->post->woning->inhoud->value(),
            'aantalKamers'          => $this->post->woning->aantalKamers->value(),
            'aantalSlaapkamers'     => $this->post->woning->aantalSlaapkamers->value(),
            'aantalBadkamers'       => $this->post->woning->aantalBadkamers->value(),
            'appartement.aantalWoonlagen' => $this->post->woning->appartement->aantalWoonlagen->value(),
            'garage.totaalAantalGarages' => $this->post->woning->garage->totaalAantalGarages->value(),
            'appartement.woonlaag'  => $this->post->woning->appartement->woonlaag->value(),
            'appartement.aantalWoonlagen' => $this->post->woning->appartement->aantalWoonlagen->value(),
            'buitenruimtesGebouwGebondenOfVrijstaand' => $this->post->woning->buitenruimtesGebouwgebondenOfVrijstaand->value(),

            // Installation
            'installatie.soortenVerwarming' => $this->post->woning->installatie->soortenVerwarming->value(),
            'installatie.soortenWarmWater' => $this->post->woning->installatie->soortenWarmWater->value(),
            'installatie.CVKetelType'       => $this->post->woning->installatie->CVKetelType->value(),
            'installatie.CVKetelBouwjaar'   => $this->post->woning->installatie->CVKetelBouwjaar->value(),
            'installatie.CVKetelCombiketel' => $this->post->woning->installatie->CVKetelCombiketel->value(),
            'installatie.CVKetelBrandstof' => $this->post->woning->installatie->CVKetelBrandstof->value(),
            'installatie.CVKetelEigendom' => $this->post->woning->installatie->CVKetelEigendom->value(),

            'woning.isolatievormen'         => $this->post->woning->isolatievormen->value(),

            // Garden
            'tuin.tuintypen'        => $this->post->woning->tuin->tuintypen->value(),
            'tuin.kwaliteit'        => $this->post->woning->tuin->kwaliteit->value(),
            'tuin.totaleOppervlakte' => $this->post->woning->tuin->totaleOppervlakte->value(),
            'tuin.oppervlakteHoofdtuin' => $this->post->oppervlakteHoofdtuin->value(),

            // Parking
            'parkeerFaciliteiten'   => $this->post->woning->parkeerFaciliteiten->value(),
            'parkerenToelichting'   => $this->post->woning->parkerenToelichting->value(),

            // Roof
            'dakType'               => $this->post->woning->dakType->value(),
            'dakMaterialen'         => $this->post->woning->dakMaterialen->value(),
            'dakToelichting'        => $this->post->woning->dakToelichting->value(),

            // Usuage
            'permanenteBewoning'    => $this->post->woning->permanenteBewoning->value(),
            'onderhoudBinnen.waardering' => $this->post->woning->onderhoudBinnen->waardering->value(),
            'onderhoudBuiten.waardering' => $this->post->woning->onderhoudBuiten->waardering->value(),
            'huidigGebruik'         => $this->post->woning->huidigGebruik->value(),
            'huidigeBestemming'     => $this->post->woning->huidigeBestemming->value(),
            'voorzieningen'         => $this->post->woning->voorzieningen->value(),
        ];

        return array_merge($baseAttributes, $additional, $this->getThirdPartyMedia());
    }
}
