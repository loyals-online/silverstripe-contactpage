<?php

namespace Loyals\ContactPage\Model;

use Loyals\ContactPage\Pages\ContactPage;
use SilverStripe\ORM\DataObject;

/**
 * Class ContactSubmission
 *
 * @property string $Name
 * @property string $Email
 * @property string $Subject
 * @property string $Message
 * @property int    $PageID
 * @method ContactPage Page()
 */
class ContactSubmission extends DataObject
{
    private static $table_name = 'ContactSubmission';

    private static $db = [
        'Name'    => 'Varchar(255)',
        'Email'   => 'Varchar(255)',
        'Subject' => 'Varchar(255)',
        'Message' => 'Text',
    ];

    private static $has_one = [
        'Page' => ContactPage::class,
    ];

    private static $summary_fields = [
        'Name'  => 'Name',
        'Email' => 'Email',
    ];

}