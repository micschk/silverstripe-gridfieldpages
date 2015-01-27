<?php
class GridFieldPage extends Page {
	
	private static $can_be_root = false;
	private static $allowed_children = "none";
	
	private static $defaults = array ( 
	   'ShowInMenus' => false,
	);
	
	/**
	 * add an arrow-overlay to this page's icon when open in the CMS
	 */
	public function getTreeTitle() {
		return str_replace(
				'jstree-pageicon', 
				'jstree-pageicon gridfieldpage-overlay', 
				parent::getTreeTitle());
	}
	
	/*
	 * Display status in the CMS grid
	 */
	public function getStatus($cached = true) {
		
		// Special case where sortorder changed
		$liveRecord = Versioned::get_by_stage(get_class($this), 'Live')->byID($this->ID);
		//return $this->Sort . ' - ' . $liveRecord->Sort;
		if($liveRecord->Sort && $liveRecord->Sort != $this->Sort){
			return 'Draft modified (reordered)';
		}
		
		if($this->IsDeletedFromStage) return 'Draft deleted';
		if($this->IsAddedToStage) return 'Draft';
		if($this->IsModifiedOnStage) return 'Draft modified';
		if($this->IsSameOnStage) return 'Published';
		// fallbacks
		if($this->isPublished()) return 'Published';
		return 'Draft';
		
//		$status = null;
//		if($this->hasMethod("isPublished")) {
//			
//			$published = $this->isPublished();
//			
//			if(!$published) {
//				$status = _t(
//					"GridFieldPage.StatusDraft", 
//					'<i class="btn-icon btn-icon-pencil"></i> Saved as Draft on {date}',
//					"State for when a post is saved but not published.", 
//					array(
//						"date" => $this->dbObject("LastEdited")->Nice()
//					)
//				);
//			} else {
//				$status = _t(
//					"GridFieldPage.StatusPublished", 
//					'<i class="btn-icon btn-icon-accept"></i> Published on {date}', 
//					"State for when a post is published.", 
//					array(
//						"date" => $this->dbObject("LastEdited")->Nice()
//					)
//				);
//			}
//			
//			if($this->isModifiedOnStage && $published) {
//				$status .= "<span class='modified'>" 
//						. _t("GridFieldPage.StatusModified", "modified") . "</span>";
//			} 
//		}
//		
//		// allow for extensions
//		$this->extend('updateStatus', $status);
//		
//		return DBField::create_field('HTMLVarchar', $status);
		
	}
	
}
 
class GridFieldPage_Controller extends Page_Controller {
}