<?php

/**
 * Class ContactSubmission
 *
 * @property string $Name
 * @property string $SurName
 * @property string $LawFirm
 * @property string $PractiseSince
 * @property string $Address
 * @property string $Zip
 * @property string $City
 * @property string $Country
 * @property string $Phone
 * @property string $Email
 * @property int $PageID
 * @property int $CVID
 * @method ContactSubmissionPage Page()
 * @method File CV()
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

/**
 * Class ContactSubmission_Controller
 *
 * @property ContactSubmission dataRecord
 * @method ContactSubmission data()
 * @mixin ContactSubmission dataRecord
 */
class ContactSubmission_Controller extends Page_Controller
{
}
