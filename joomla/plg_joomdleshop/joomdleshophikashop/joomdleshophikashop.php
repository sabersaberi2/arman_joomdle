<?php
/**
* @version		1.1.0
* @package		Joomdle - Hikashop
* @copyright	Qontori Pte Ltd
* @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class  plgJoomdleshopJoomdleshophikashop extends JPlugin
{
    private $shop_integration = 'hikashop';

    function integration_enabled ()
    {
        // Don't run if not configured as shop
        $params = JComponentHelper::getParams( 'com_joomdle' );
        $shop_integration = $params->get( 'shop_integration' );
        return  ($shop_integration == $this->shop_integration);
    }

    // Joomdle events
    public function onGetShop ()
    {
        $option['hikashop'] = "Hikashop";

        return $option;
    }

	function onGetShopCourses ()
    {
        if (!$this->integration_enabled ())
            return array ();

        $cursos = JoomdleHelperContent::getCourseList (0);
        $c = array ();
        $i = 0;

        if (!is_array ($cursos))
            return $c;

        foreach ($cursos as $curso)
        {
			$c[$i] = new JObject ();
            $c[$i]->id = $curso['remoteid'];
            $c[$i]->fullname = $curso['fullname'];
			$c[$i]->shortname = $curso['shortname'];
			$c[$i]->published = $this->is_course_on_sell ($curso['remoteid']);
            $i++;
        }

        return $c;
    }


    function is_course_on_sell ($course_id)
    {
        $db           = JFactory::getDBO();
        $query = 'SELECT product_code' .
                                ' FROM #__hikashop_product' .
                                ' WHERE product_code =';
        $query .= $db->Quote($course_id) . " and product_published=1";
        $db->setQuery($query);
        $products = $db->loadObjectList();
        if (count ($products))
            return 1;
        else
            return 0;
    }

	function onIsCourseOnSell ($course_id)
	{
        if (!$this->integration_enabled ())
            return false;

		return $this->is_course_on_sell ($course_id);
	}

    public function onSellCourses ($courses)
    {
        if (!$this->integration_enabled ())
            return false;

        require_once( JPATH_ADMINISTRATOR.'/components/com_hikashop/helpers/helper.php' );
        $config = hikashop_config();
		$allowed = $config->get('allowedfiles');
		$imageHelper = hikashop_get('helper.image');

		$file_class = hikashop_get('class.file');
		$uploadPath = $file_class->getPath('product','');


        $params = JComponentHelper::getParams( 'com_joomdle' );
        $courses_category = $params->get( 'courses_category' );

        $db           = JFactory::getDBO();

        foreach ($courses as $sku)
        {
            $query = 'SELECT product_code ' .
                    ' FROM #__hikashop_product' .
                    ' WHERE product_code =' . $db->Quote($sku);
            $db->setQuery($query);
            $products = $db->loadObjectList();
            if (count ($products))
            {
                /* Product already on Hikashop, just publish it */
                $query = "UPDATE  #__hikashop_product SET product_published = '1' where product_code = ". $db->Quote($sku);
                $db->setQuery($query);
                if (!$db->query()) {
                    return JError::raiseWarning( 500, $db->getError() );
                 }
                continue;
            }

            /* New product to add to Hikashop */
            if (strncmp ($sku, 'bundle_', 7) == 0)
            {
                //bundle
                $bundle_id = substr ($sku, 7);
                $bundle = JoomdleHelperShop::get_bundle_info ($bundle_id);
                $name = $bundle['name'];
                $desc = $bundle['description'];
                $cost = $bundle['cost'];
                $currency = $bundle['currency'];
            }
            else
            {
                //Course
                $course_info = JoomdleHelperContent::getCourseInfo ($sku);
                $name = $course_info['fullname'];
                $desc = $course_info['summary'];
                if (array_key_exists ('cost', $course_info))
                    $cost = $course_info['cost'];
                else $cost = 0;
                if (array_key_exists ('currency', $course_info))
                    $currency = $course_info['currency'];
                else $currency = '';
            }

            $product_class = hikashop_get('class.product');
            $element = new JObject ();
            $element->categories = array ($courses_category);
            $element->related = array();
            $element->options = array();
            $element->product_name = $name;
            $element->product_description = $desc;
            $element->product_code = $sku;
            $element->product_published = 1;

            $query = "SELECT category_id FROM #__hikashop_category WHERE category_namekey='default_tax'";
            $db->setQuery($query);
            $tax_id = $db->loadResult();
            if ($tax_id)
            {
                $element->product_tax_id = $tax_id;

                $query = "SELECT tax_namekey FROM #__hikashop_taxation WHERE category_namekey='default_tax'";
                $db->setQuery($query);
                $tax_namekey = $db->loadResult();

                $query = "SELECT tax_rate FROM #__hikashop_tax WHERE tax_namekey=".$db->Quote($tax_namekey);
                $db->setQuery($query);
                $tax_rate = $db->loadResult();

                $div = $tax_rate + 1;
                $price_without_tax = $cost / $div;
                $cost = $price_without_tax;
            }
            $element->prices[0] = new JObject ();
            $element->prices[0]->price_value = $cost;

            $query = "SELECT currency_id FROM #__hikashop_currency WHERE currency_code = " . $db->Quote ($currency);
            $db->setQuery($query);
            $currency_id = $db->loadResult();
            $element->prices[0]->price_currency_id = $currency_id;
            $element->prices[0]->price_min_quantity = 0;

            $status = $product_class->save($element);

            // Add images as product media
            foreach ($course_info['summary_files'] as $file)
            {
                $pic_url = $file['url'];

                if (strstr ($pic_url, '?forcedownload=1'))
                {
                    // Remove ?forcedownload=1
                    $pic_url = substr ($pic_url, 0, strlen ($pic_url) - strlen ('?forcedownload=1'));
                }

                // Check extension, only care about images
                $ext = substr ($pic_url, strlen ($pic_url) - 3);
                if (($ext != 'jpg') && ($ext != 'png') && ($ext != 'gif'))
                    continue;

                $pic = JoomdleHelperContent::get_file ($pic_url);

				$file = new stdClass();
				$file->file_name = '';
				$file->file_description = '';

				$filename = basename ($pic_url);
                $file_path = strtolower(JFile::makeSafe($filename));
                if(!preg_match('#\.('.str_replace(array(',','.'),array('|','\.'),$allowed).')$#Ui',$file_path,$extension) || preg_match('#\.(php.?|.?htm.?|pl|py|jsp|asp|sh|cgi)$#Ui',$file_path)){
                    $app->enqueueMessage(JText::sprintf( 'ACCEPTED_TYPE',substr($file_path,strrpos($file_path,'.')+1),$allowed), 'notice');
                    continue;
                }
                $file_path= str_replace(array('.',' '),'_',substr($file_path,0,strpos($file_path,$extension[0]))).$extension[0];

                file_put_contents ($uploadPath . $file_path,  $pic);

				$imageHelper->resizeImage($file_path);
				$imageHelper->generateThumbnail($file_path);

				$file->file_path = $file_path;
				$file->file_type = 'product';
				$file->file_ref_id = $status;

				$image_id = $file_class->save($file);
				$element->images[] = $image_id;
            }

            if ($status)
            {
                $product_class->updateCategories($element,$status);
                $product_class->updatePrices($element,$status);
				$product_class->updateFiles($element,$status,'images');
            }

        } // foreach courses
    }

    function onDontSellCourses ($courses)
    {
        if (!$this->integration_enabled ())
            return false;

        $db           = JFactory::getDBO();

        foreach ($courses as $sku)
        {
            $query = "UPDATE  #__hikashop_product SET product_published = '0' where product_code = " . $db->Quote($sku);
            $db->setQuery($query);
            if (!$db->query()) {
                return JError::raiseWarning( 500, $db->getError() );
             }
        }
    }

	function onReloadCourses ($courses)
	{
        if (!$this->integration_enabled ())
            return false;

        require_once( JPATH_ADMINISTRATOR.'/components/com_hikashop/helpers/helper.php' );
        $config = hikashop_config();
		$allowed = $config->get('allowedfiles');
		$imageHelper = hikashop_get('helper.image');

		$file_class = hikashop_get('class.file');
		$uploadPath = $file_class->getPath('product','');

        $params = JComponentHelper::getParams( 'com_joomdle' );
        $courses_category = $params->get( 'courses_category' );
        $db           = JFactory::getDBO();
        foreach ($courses as $sku)
        {
            // skip bundles
            if (strncmp ($sku, 'bundle_', 7) == 0)
                continue;

            $query = "SELECT product_id FROM #__hikashop_product WHERE product_code = ". $db->Quote($sku);
            $db->setQuery($query);
            $products = $db->loadObjectList();
            if (count ($products))
            {
                $product_id = $products[0]->product_id;
                $element->product_id = $product_id;

                $course_info = JoomdleHelperContent::getCourseInfo ($sku);
                $name = $course_info['fullname'];
                $desc = $course_info['summary'];
                $cost = $course_info['cost'];
                $currency = $course_info['currency'];

                $product_class = hikashop_get('class.product');
                $element->categories = $product_class->getCategories ($product_id);

                $element->categories[] = $courses_category;
                $element->related = array();
                $element->options = array();
                $element->product_name = $name;
                $element->product_description = $desc;
                $element->product_code = $sku;
                $element->product_published = 1;

                $query = "SELECT category_id FROM #__hikashop_category WHERE category_namekey='default_tax'";
                $db->setQuery($query);
                $tax_id = $db->loadResult();
                if ($tax_id)
                {
                    $element->product_tax_id = $tax_id;

                    $query = "SELECT tax_namekey FROM #__hikashop_taxation WHERE category_namekey='default_tax'";
                    $db->setQuery($query);
                    $tax_namekey = $db->loadResult();

                    $query = "SELECT tax_rate FROM #__hikashop_tax WHERE tax_namekey=".$db->Quote($tax_namekey);
                    $db->setQuery($query);
                    $tax_rate = $db->loadResult();

                    $div = $tax_rate + 1;
                    $price_without_tax = $cost / $div;
                    $cost = $price_without_tax;
                }
                $element->prices = array ();
                $element->prices[0]->price_value = $cost;

                $query = "SELECT currency_id FROM #__hikashop_currency WHERE currency_code = " . $db->Quote ($currency);
                $db->setQuery($query);
                $currency_id = $db->loadResult();
                $element->prices[0]->price_currency_id = $currency_id;
                $element->prices[0]->price_min_quantity = 0;

                $status = $product_class->save($element);

				// Add images as product media
				foreach ($course_info['summary_files'] as $file)
				{
					$pic_url = $file['url'];
                    if (strstr ($pic_url, '?forcedownload=1'))
                    {
                        // Remove ?forcedownload=1
                        $pic_url = substr ($pic_url, 0, strlen ($pic_url) - strlen ('?forcedownload=1'));
                    }

                    // Check extension, only care about images
                    $ext = substr ($pic_url, strlen ($pic_url) - 3);
                    if (($ext != 'jpg') && ($ext != 'png') && ($ext != 'gif'))
                        continue;

					$pic = JoomdleHelperContent::get_file ($pic_url);

					$file = new stdClass();
					$file->file_name = '';
					$file->file_description = '';

					$filename = basename ($pic_url);
					$file_path = strtolower(JFile::makeSafe($filename));
					if(!preg_match('#\.('.str_replace(array(',','.'),array('|','\.'),$allowed).')$#Ui',$file_path,$extension) || preg_match('#\.(php.?|.?htm.?|pl|py|jsp|asp|sh|cgi)$#Ui',$file_path)){
						$app->enqueueMessage(JText::sprintf( 'ACCEPTED_TYPE',substr($file_path,strrpos($file_path,'.')+1),$allowed), 'notice');
						continue;
					}
					$file_path= str_replace(array('.',' '),'_',substr($file_path,0,strpos($file_path,$extension[0]))).$extension[0];

					file_put_contents ($uploadPath . $file_path,  $pic);

					$imageHelper->resizeImage($file_path);
					$imageHelper->generateThumbnail($file_path);

					$file->file_path = $file_path;
					$file->file_type = 'product';
					$file->file_ref_id = $status;

					$image_id = $file_class->save($file);
					$element->images[] = $image_id;
				}

				if ($status)
				{
					$product_class->updateCategories($element,$status);
					$product_class->updatePrices($element,$status);
					$product_class->updateFiles($element,$status,'images');
				}

            }
            else JoomdleHelperShop::sell_courses (array($sku));
        }
    }


	function onDeleteCourses ($courses)
    {
        if (!$this->integration_enabled ())
            return false;

        require_once( JPATH_ADMINISTRATOR.'/components/com_hikashop/helpers/helper.php' );
        $db           = JFactory::getDBO();

        $ids = array();
        foreach ($courses as $sku)
        {
            $query = 'SELECT product_id' .
                    ' FROM #__hikashop_product' .
                    ' WHERE product_code =';
            $query .= $db->Quote($sku);
            $db->setQuery($query);
            $product_id = $db->loadResult();
            /* Product not on Hikashop, nothing to do */
            if (!$product_id)
                continue;

            $ids[] = $product_id;

        }
        $product_class = hikashop_get('class.product');
        $product_class->delete ($ids);
	}


	function onCreateBundle ($bundle)
	{
        if (!$this->integration_enabled ())
            return false;

        JoomdleHelperShop::sell_courses (array ('bundle_'.$bundle['id']));
	}

    function onGetSellUrl ($course_id)
    {
        if (!$this->integration_enabled ())
            return false;

        $db = JFactory::getDBO();
        $query = 'SELECT product_id' .
              ' FROM #__hikashop_product' .
              ' WHERE product_code =' . $db->Quote($course_id) .
              " and product_published='1'" ;
        $db->setQuery($query);
        $product_id = $db->loadResult();

        $url = "index.php?option=com_hikashop&ctrl=product&task=show&cid=$product_id";

        return $url;
    }

	function onGetShopCategories ()
    {
        if (!$this->integration_enabled ())
            return array ();

        $db = JFactory::getDBO();
        $query = 'SELECT category_id, category_name' .
              ' FROM #__hikashop_category' .
              " WHERE category_type ='product'";
        $db->setQuery($query);
        $cats = $db->loadAssocList();
        $c = array ();

        if (!is_array ($cats))
            return $c;

        foreach ($cats as $cat)
        {
			$co = array ();
            $co['id'] = $cat['category_id'];
            $co['name'] = $cat['category_name'];

            $c[] = $co;
        }

        return $c;
    }

	function onGetShopCurrencies ()
    {
        if (!$this->integration_enabled ())
            return array ();

        $db = JFactory::getDBO();
        $query = 'SELECT currency_name as name, currency_code as code' .
              ' FROM #__hikashop_currency';
        $query .= ' WHERE currency_displayed=1';
        $db->setQuery($query);
        $cs = $db->loadAssocList();

        if (!is_array ($cs))
            return array ();

        return $cs;
    }

}
