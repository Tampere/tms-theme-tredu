<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields;

use Exception;
use Geniem\ACF\Field;
use TMS\Plugin\ContactImporter;
use TMS\Theme\Tredu\Formatters\ContactFormatter;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType\Contact;

/**
 * Class ContactsFields
 *
 * @package TMS\Theme\Tredu\ACF\Fields
 */
class ContactsFields extends \Geniem\ACF\Field\Group {

    /**
     * The constructor for field.
     *
     * @param string $label Label.
     * @param null   $key   Key.
     * @param null   $name  Name.
     */
    public function __construct( $label = '', $key = null, $name = null ) {
        parent::__construct( $label, $key, $name );

        try {
            $this->add_fields( $this->sub_fields() );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        if ( is_admin() ) {
            add_filter(
                'acf/load_field/name=api_contacts',
                [ $this, 'fill_api_contacts_field_choices' ]
            );
        }
    }

    /**
     * This returns all sub fields of the parent groupable.
     *
     * @return array
     * @throws \Geniem\ACF\Exception In case of invalid ACF option.
     */
    protected function sub_fields() : array {
        $strings = [
            'title'        => [
                'label'        => 'Otsikko',
                'instructions' => '',
            ],
            'description'  => [
                'label'        => 'Kuvaus',
                'instructions' => '',
            ],
            'contacts'     => [
                'label'        => 'Yhteystiedot',
                'instructions' => '',
            ],
            'api_contacts' => [
                'label'        => 'Tampere-sivuston yhteystiedot',
                'instructions' => '',
            ],
            'fields'       => [
                'label'         => 'Näytettävät kentät',
                'instructions'  => '',
                'choices'       => [
                    'title'                     => 'Titteli',
                    'first_name'                => 'Etunimi',
                    'last_name'                 => 'Sukunimi',
                    'phone_repeater'            => 'Puhelinnumero',
                    'email'                     => 'Sähköpostiosoite',
                    'office'                    => 'Toimipiste',
                    'domain'                    => 'Toimialue',
                    'unit'                      => 'Yksikkö',
                    'visiting_address_street'   => 'Käyntiosoite: Katuosoite ja numero / PL',
                    'visiting_address_zip_code' => 'Käyntiosoite: Postinumero',
                    'visiting_address_city'     => 'Käyntiosoite: Postitoimipaikka',
                    'mail_address_street'       => 'Postiosoite: Katuosoite ja numero / PL',
                    'mail_address_zip_code'     => 'Postiosoite: Postinumero',
                    'mail_address_city'         => 'Postiosoite: Postitoimipaikka',
                    'additional_info_top'       => 'Lisätieto 1',
                    'additional_info_bottom'    => 'Lisätieto 2',
                ],
                'default_value' => [
                    'title',
                    'first_name',
                    'last_name',
                    'phone_repeater',
                    'email',
                ],
            ],
        ];

        $key = $this->get_key();

        $title_field = ( new Field\Text( $strings['title']['label'] ) )
            ->set_key( "{$key}_title" )
            ->set_name( 'title' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['title']['instructions'] );

        $description_field = ( new Field\Textarea( $strings['description']['label'] ) )
            ->set_key( "{$key}_description" )
            ->set_name( 'description' )
            ->set_rows( 4 )
            ->set_new_lines( 'wpautop' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['description']['instructions'] );

        $api_contacts_field = ( new Field\Select( $strings['api_contacts']['label'] ) )
            ->set_key( "{$key}_api_contacts" )
            ->set_name( 'api_contacts' )
            ->allow_multiple()
            ->allow_null()
            ->use_ajax()
            ->use_ui()
            ->redipress_include_search( function ( $contacts ) {
                if ( empty( $contacts ) ) {
                    return '';
                }

                $contacts_map = ( new ContactFormatter() )->map_api_contacts(
                    $contacts,
                    [
                        'first_name',
                        'last_name',
                    ]
                );

                $results = [];

                foreach ( $contacts_map as $contact ) {
                    if ( ! empty( $contact['first_name'] ) ) {
                        $results[] = $contact['first_name'];
                    }

                    if ( ! empty( $contact['last_name'] ) ) {
                        $results[] = $contact['last_name'];
                    }
                }

                return implode( ' ', $results );
            } )
            ->set_instructions( $strings['api_contacts']['instructions'] );

        $contacts_field = ( new Field\Relationship( $strings['contacts']['label'] ) )
            ->set_key( "{$key}_contacts" )
            ->set_name( 'contacts' )
            ->set_filters( [ 'search' ] )
            ->redipress_include_search( function ( $contacts ) {
                if ( empty( $contacts ) ) {
                    return '';
                }

                $results = [];

                foreach ( $contacts as $contact_id ) {
                    $results[] = get_field( 'first_name', $contact_id );
                    $results[] = get_field( 'last_name', $contact_id );
                }

                return implode( ' ', $results );
            } )
            ->set_post_types( [ Contact::SLUG ] )
            ->set_return_format( 'id' )
            ->set_instructions( $strings['contacts']['instructions'] );

        $fields_field = ( new Field\Checkbox( $strings['fields']['label'] ) )
            ->set_key( "{$key}_fields" )
            ->set_name( 'fields' )
            ->set_choices( $strings['fields']['choices'] )
            ->set_default_value( $strings['fields']['default_value'] )
            ->set_instructions( $strings['fields']['instructions'] );

        return [
            $title_field,
            $description_field,
            $api_contacts_field,
            $contacts_field,
            $fields_field,
        ];
    }

    /**
     * Fill API contacts field choices
     *
     * @param array $field ACF field.
     *
     * @return array
     */
    public function fill_api_contacts_field_choices( array $field ) : array {
        $contacts = ( new ContactImporter\PersonApiController() )->get_results();

        if ( empty( $contacts ) ) {
            return $field;
        }

        foreach ( $contacts as $contact ) {
            $field['choices'][ $contact['id'] ] = sprintf(
                '%s %s',
                $contact['first_name'],
                $contact['last_name']
            );
        }

        return $field;
    }
}
