<?php

/**
 * Class ContactSubmission
 *
 * @property string $Name
 * @property string $Email
 * @property string $Subject
 * @property string $Message
 * @property int $PageID
 * @method ContactPage Page()
 */
class ContactSubmission extends DataObject
{
    static $db = [
        'Name'          => 'Varchar(255)',
        'Email'         => 'Varchar(255)',
        'Subject'       => 'Varchar(255)',
        'Message'       => 'Text'
    ];

    private static $has_one = [
        'Page' => 'ContactPage'
    ];

    public static $summary_fields = [
        'Name'    => 'Name',
        'Email'   => 'Email'
    ];

}