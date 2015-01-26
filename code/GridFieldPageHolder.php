<?php
 
class GridFieldPageHolder extends Page {

    private static $allowed_children = array('*GridFieldPage');
	private static $default_child = "GridFieldPage";
	private static $add_default_gridfield = true;
	
	public static function setAddDefaultGridField(Boolean $val){
		Deprecation::notice('1.0', 'setAddDefaultGridField is deprecated please use Config instead');
		self::config()->update('add_default_gridfield', $val);
	}
     
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		
		// GridFieldPage
		$fields->addFieldToTab('Root.Subpages', new HeaderField('GridfieldPages', 'Subpages of this page'));
		
		if( self::config()->get('add_default_gridfield') ){
			$gridFieldConfig = GridFieldConfig::create()->addComponents(
				new GridFieldToolbarHeader(),
				new GridFieldAddNewSiteTreeItemButton('toolbar-header-right'),
				new GridFieldSortableHeader(),
				new GridFieldFilterHeader(),
				$dataColumns = new GridFieldDataColumns(),
				new GridFieldPaginator(20),
				new GridFieldEditSiteTreeItemButton(),
				new GridFieldOrderableRows() // default 'Sort' is equal to page sort field...
			);
			$dataColumns->setDisplayFields(array(
				'Title' => 'Title',
				'URLSegment'=> 'URL',
				'getStatus' => 'Status',
				'LastEdited' => 'Changed',
			));

			// include both live and stage versions of pages
			//$pages = $this->AllChildrenIncludingDeleted();

			// use gridfield as normal
			$gridField = new GridField("Subpages", 
					"Manage " . singleton($this->defaultChild())->i18n_plural_name(),
					DataObject::get($this->defaultChild(), 'ParentID = '.$this->ID),
					$gridFieldConfig);
			
			$gridField->setModelClass($this->defaultChild());
			
			$fields->addFieldToTab("Root.Subpages", $gridField);
		}
		
		return $fields;
	}
	
	// @TODO: A page still needs to be published for the sortorder to be updated, 
	// we need some kind of warning/info to inform CMS users about this
	public function SortedChildren(){
		//return DataObject::get($this->defaultChild(), 'ParentID = '.$this->ID);
		//return SiteTree::get()->filter('ParentID', $this->ID)->sort('Sort');
		$pagetype = $this->defaultChild();
		return $pagetype::get()->filter('ParentID', $this->ID);
	}
		
}
 
class GridFieldPageHolder_Controller extends Page_Controller {
}
