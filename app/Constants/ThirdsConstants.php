<?php

namespace App\Constants;

/**
 * Class ThirdsConstants
 * @package App\Constants
 */
class ThirdsConstants
{
    /**
     * define los valores que puede tener el campo identity_type
     */
    const VALUES_IDENTITY_TYPE = [
        'CC' => 'CC',
        'NIT' => 'NIT',
    ];

    /**
     * define los valores que puede tener el campo type_person
     */
    const VALUES_TYPE_PERSON = [
        'natural' => 'natural',
        'juridical' => 'juridical'
    ];

    /**
     * define los valores que puede tener el campo type
     */
    const VALUES_TYPE_REGISTER = [
        'client' => 'client',
        'provider' => 'provider',
        'other' => 'other'
    ];
}
