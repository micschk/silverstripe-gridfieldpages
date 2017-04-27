<?php
class GridFieldPage extends Page
{
    
    private static $can_be_root = false;
    private static $allowed_children = "none";
    
    private static $defaults = array(
       'ShowInMenus' => false,
    );
    
    private static $searchable_fields = array(
        'Title', 'MenuTitle'
    );
    
    private static $summary_fields = array(
        "Title", 'MenuTitle'
    );
    
    /**
     * add an arrow-overlay to this page's icon when open in the CMS
     */
    public function getTreeTitle()
    {
        return str_replace(
                'jstree-pageicon',
                'jstree-pageicon gridfieldpage-overlay',
                parent::getTreeTitle());
    }
    
    /*
     * Display status in the CMS grid
     */
    public function getStatus($cached = true)
    {
        $status = null;
        $statusflag = null;
        
        if ($this->hasMethod("isPublished")) {
            $published = $this->isPublished();
            
            if ($published) {
                $status = _t(
                    "GridFieldPage.StatusPublished",
                    '<i class="btn-icon btn-icon-accept"></i> Published on {date}',
                    "State for when a post is published.",
                    array(
                        "date" => $this->dbObject("LastEdited")->Nice()
                    )
                );
                //$status = 'Published';

                // Special case where sortorder changed
                $liveRecord = Versioned::get_by_stage(get_class($this), 'Live')->byID($this->ID);
                //return $this->Sort . ' - ' . $liveRecord->Sort;
                if ($liveRecord->Sort && $liveRecord->Sort != $this->Sort) {
                    // override published status
                    $status = _t(
                            "GridFieldPage.StatusDraftReordered",
                            '<i class="btn-icon btn-icon-arrow-circle-double"></i> Draft modified (reordered)',
                            "State for when a page has been reordered."
                        );
                    //$status = 'Draft modified (reordered)';
                }
                
                // Special case where deleted from draft
                if ($this->IsDeletedFromStage) {
                    // override published status
                    $statusflag = "<span class='modified'>"
                                . _t("GridFieldPage.StatusDraftDeleted", "draft deleted") . "</span>";
                    //$status = 'Draft deleted';
                }
                
                // If modified on stage, add 
                if ($this->IsModifiedOnStage) {
                    // add to published status
                    $statusflag = "<span class='modified'>"
                                . _t("GridFieldPage.StatusModified", "draft modified") . "</span>";
                    //$status = 'Draft modified';
                }
                
                // If same on stage...
                if ($this->IsSameOnStage) {
                    // leave as is
                }
            } else {
                if ($this->IsAddedToStage) {
                    $status = _t(
                            "GridFieldPage.StatusDraft",
                            '<i class="btn-icon btn-icon-pencil"></i> Saved as Draft on {date}',
                            "State for when a post is saved but not published.",
                            array(
                                "date" => $this->dbObject("LastEdited")->Nice()
                            )
                        );
                    //$status = 'Draft';
                }
            }
        }
        
        // allow for extensions
        $this->extend('updateStatus', $status, $statusflag);
        
        return DBField::create_field('HTMLVarchar', $status.$statusflag);
    }
    
    public function getCMSActions()
    {
        
        // hide delete-draft button if page is published 
        // (deleting from draft while having a published page, 
        // removes the page from the gridfield and makes it un-reachable from the CMS
        // The Gridfield gets records from draft only (AllChildrenIncludingDeleted breaks 
        // gridfield sorting & filtering)
        $actions = parent::getCMSActions();
        if ($this->isPublished()) {
            $actions->removeByName('action_delete');
        }
        return $actions;
    }
}
 
class GridFieldPage_Controller extends Page_Controller
{
}
