<?php
class GridFieldPage extends Page {
	
	public static $db = array(
		//'Date' => 'Date',
	); 
	
	private static $can_be_root = false;
	//public static $icon = 'mysite/images/camera';
	static $allowed_children = "none";
	
	public function getStatus($cached = true) {
		if($this->IsDeletedFromStage) return 'Draft deleted';
		if($this->IsAddedToStage) return 'Draft';
		if($this->IsModifiedOnStage) return 'Draft modified';
		if($this->IsSameOnStage) return 'Published';
		// fallbacks
		if($this->isPublished()) return 'Published';
		return 'Draft';
	}
	
//	public function formattedPublishDate(){
//		return $this->obj('Date')->Format('Y-m-d'); 
//	} 

//	public function populateDefaults() {
//		$this->Date = date('dd-MM-yyyy');
//		parent::populateDefaults();
//	}
	// M: END newsgrid

//	public function getCMSFields() {
//		$fields = parent::getCMSFields();
//
////		$Datepckr = new DateField('Date');
////		$Datepckr->setConfig('dateformat', 'dd-MM-yyyy'); // global setting
////		$Datepckr->setConfig('showcalendar', 1); // field-specific setting
////		$fields->addFieldToTab("Root.Main", $Datepckr, 'Content');
//
//		return $fields;
//	}
}
 
class GridFieldPage_Controller extends Page_Controller {
}