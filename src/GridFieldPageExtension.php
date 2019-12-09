<?php

namespace Restruct\Silverstripe\GridFieldPages;

use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBHTMLVarchar;
use SilverStripe\ORM\Hierarchy\Hierarchy;

/**
 * Applies grid field functionality for pages as a SilverStripe Data Extension instead of requiring you to extend the
 * GridFieldPage object. This is useful in case you don't wish to change existing class inheritance but still have this
 * functionality.
 */

class GridFieldPageExtension
    extends DataExtension
{
    private static $can_be_root = false;
    private static $allowed_children = "none";

    private static $defaults = [
        'ShowInMenus' => false,
    ];

    public function updateStatusFlags(&$flags)
    {
        // we add an empty status-flag to hook some CSS into (gets applied as a class on li.jstree-leaf as well)
        $flags['gfpage-extension'] = [ 'text' => '', 'title' => '' ];
    }

    public function getTreeTitleAsHtml()
    {
        $statusClasses = 'status-' . implode(' status-', array_keys( $this->owner->getStatusFlags() ) );
        return DBHTMLVarchar::create()->setValue("<span class='$statusClasses'>{$this->owner->getTreeTitle()}</span>");
    }

}
