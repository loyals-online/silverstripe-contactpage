<?php

namespace Loyals\ContactPage\Pages;

use Page;
use Loyals\ContactPage\Controllers\ContactPageController;
use Loyals\ContactPage\Model\ContactSubmission;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldButtonRow;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use Symbiote\GridFieldExtensions\GridFieldAddExistingSearchButton;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\ORM\DataList;

/**
 * Class ContactSubmissionPage
 *
 * @property string $MailTo
 * @property string $MailFrom
 * @property string $MailSubject
 * @property string $SubmitButtonText
 * @property string $SubmitText
 * @method DataList|ContactSubmission[] Submissions()
 */
class ContactPage extends Page
{
    private static $table_name = 'ContactPage';

    private static $db = [
        'MailTo'           => 'Varchar(100)',
        'MailFrom'         => 'Varchar(100)',
        'NameFrom'         => 'Varchar(100)',
        'MailSubject'      => 'Varchar(255)',
        'SubmitButtonText' => 'Varchar(255)',
        'SubmitText'       => 'HTMLText',
    ];

    private static $has_many = [
        'Submissions' => ContactSubmission::class,
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $config = GridFieldConfig_RecordEditor::create()
            ->removeComponentsByType('GridFieldDeleteAction')
            ->addComponent(new GridFieldDeleteAction(false))
            ->addComponent(new GridFieldOrderableRows('SortOrder'));

        $fields->addFieldToTab("Root.OnSubmission", new TextField('MailTo', 'Stuur inzendingen naar (E-mail)'));
        $fields->addFieldToTab("Root.OnSubmission", new TextField('MailFrom', 'Afzender (E-mail)'));
        $fields->addFieldToTab("Root.OnSubmission", new TextField('NameFrom', 'Afzender (Naam)'));
        $fields->addFieldToTab("Root.OnSubmission", new TextField('MailSubject', 'Email onderwerp'));
        $fields->addFieldToTab("Root.OnSubmission", new TextField('SubmitButtonText', 'Verzendknop Tekst'));
        $fields->addFieldToTab("Root.OnSubmission", new HTMLEditorField('SubmitText', 'Bedankt Tekst'));

        $gridFieldConfig = GridFieldConfig::create()->addComponents(
            new GridFieldToolbarHeader(),
            new GridFieldSortableHeader(),
            new GridFieldDataColumns(),
            new GridFieldPaginator(50),
            new GridFieldEditButton(),
            new GridFieldDeleteAction(),
            new GridFieldDetailForm()
        );

        /* Submissions */
        $submissionGridField = new GridField("ContactSubmission", "Submission", $this->Submissions(), $gridFieldConfig,
            $this);

        $fields->addFieldToTab("Root.Submissions", $submissionGridField); // add the grid field to a tab in the CMS

        $config = GridFieldConfig::create();
        $config->addComponent(new GridFieldToolbarHeader());
        $config->addComponent(new GridFieldButtonRow('before'));
        $config->addComponent(new GridFieldAddExistingSearchButton('toolbar-header-right'));
        $config->addComponent($editableColumns = new GridFieldEditableColumns());
        $config->addComponent(new GridFieldDeleteAction(true));
        $config->addComponent(new GridFieldOrderableRows('SortOrder'));

        $editableColumns->setDisplayFields([
            'Name'  => [
                'title' => 'Name',
                'field' => 'ReadonlyField',
            ],
            'Email' => [
                'title' => 'Email',
                'field' => 'ReadonlyField',
            ],
        ]);

        return $fields;
    }

    public function getControllerName()
    {
        return ContactPageController::class;
    }
}
