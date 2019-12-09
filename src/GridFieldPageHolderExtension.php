<?php
/**
 * Created by PhpStorm.
 * User: mic
 * Date: 2019-12-09
 * Time: 07:56
 */

namespace Restruct\Silverstripe\GridFieldPages;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\Deprecation;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldButtonRow;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use Restruct\Silverstripe\SiteTreeButtons\GridFieldAddNewSiteTreeItemButton;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use Restruct\Silverstripe\SiteTreeButtons\GridFieldEditSiteTreeItemButton;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\GridField\GridField;
use Restruct\Silverstripe\GridFieldPages\GridFieldPage;
use Symbiote\GridFieldExtensions\GridFieldTitleHeader;

class GridFieldPageHolderExtension
extends DataExtension
{
    // defaults, configurable via config
//    private static $allowed_children = [
//        '*'.GridFieldPage::class,
//    ];
//    private static $default_child = GridFieldPage::class;
    private static $add_default_gridfield = true;
//    private static $apply_sortable = true;
//    private static $subpage_tab = "";
//    private static $gridfield_title = "Manage Subpages";

    public function updateCMSFields(FieldList $fields)
    {
//        die('called');
        // GridFieldPage
        if($this->owner->config()->get('add_default_gridfield')) {
            $this->addPagesGridField($fields);
        }
    }

    public function addPagesGridField(&$fields, $tab='Root.Subpages', $gfTitle='Manage Subpages', $orderable=true)
    {
        $gridFieldConfig = GridFieldConfig::create()
            ->addComponents(
                # new GridFieldToolbarHeader(),
                new GridFieldButtonRow('before'),
                new GridFieldAddNewSiteTreeItemButton(),
                # new GridFieldTitleHeader(),
                # new GridFieldSortableHeader(),
                new GridFieldFilterHeader(),
                $dataColumns = new GridFieldDataColumns(),
                new GridFieldPaginator(20),
                new GridFieldEditSiteTreeItemButton()
            );

        // Orderable is optional, as often pages may be sorted by other means
        if ($orderable) {
            $gridFieldConfig->addComponent(new GridFieldOrderablePages());
        }

        $dataColumns->setDisplayFields([
            'TreeTitleAsHtml' => _t('SilverStripe\\CMS\\Model\\SiteTree.PAGETITLE', 'Page Title'),
            'singular_name' => _t('SilverStripe\\CMS\\Model\\SiteTree.PAGETYPE', 'Page Type'),
            'LastEdited' => _t('SilverStripe\\CMS\\Model\\SiteTree.LASTUPDATED', 'Last Updated'),
        ]);

        $gridField = GridField::create("Subpages",
                Config::inst()->get($this->owner->className, 'gridfield_title'),
                DataObject::get($this->owner->defaultChild(), 'ParentID = '.$this->owner->ID),
                $gridFieldConfig
            )
            ->setModelClass($this->owner->defaultChild());

        $fields->addFieldToTab($tab, $gridField);
    }

}