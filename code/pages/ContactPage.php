<?php


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
    private static $db = [
        'MailTo'           => 'Varchar(100)',
        'MailFrom'         => 'Varchar(100)',
        'NameFrom'         => 'Varchar(100)',
        'MailSubject'      => 'Varchar(255)',
        'SubmitButtonText' => 'Varchar(255)',
        'SubmitText'       => 'CustomHTMLText',
    ];

    private static $has_many = [
        'Submissions' => 'ContactSubmission'
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
        $fields->addFieldToTab("Root.OnSubmission", new CustomHTMLEditorField('SubmitText', 'Bedankt Tekst'));

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

        $editableColumns->setDisplayFields(array(
            'Name' => array(
                'title' => 'Name',
                'field' => 'ReadonlyField'
            ),
            'Email'    => array(
                'title' => 'Email',
                'field' => 'ReadonlyField'
            )
        ));

        return $fields;
    }
}

/**
 * Class ContactPage_Controller
 *
 * @property ContactPage dataRecord
 * @method ContactPage data()
 * @mixin ContactPage dataRecord
 */
class ContactPage_Controller extends Page_Controller
{
    private static $allowed_actions = array(
        'ContactForm'
    );

    public function ContactForm()
    {
        return ContactForm::create($this, 'ContactForm')
            ->addExtraClass('Contactform')->setAttribute('data-abide', 'data-abide');
    }

    public function Success()
    {
        return isset($_REQUEST['success']) && $_REQUEST['success'] == "1";
    }
}
