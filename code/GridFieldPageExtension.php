<?php
require_once(__DIR__ . '/GridFieldPageTrait.php');

/**
 * Applies grid field functionality for pages as a SilverStripe Data Extension instead of requiring you to extend the
 * GridFieldPage object. This is useful in case you don't wish to change existing class inheritance but still have this
 * functionality.
 *
 * @author	Patrick Nelson, pat@catchyour.com
 * @since	2017-04-26
 */

class GridFieldPageExtension extends DataExtension {
	use GridFieldPageTrait;
}
