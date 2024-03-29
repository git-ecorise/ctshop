<?php
/**
 * Created by IntelliJ IDEA.
 * User: codetwister
 * Date: 7/2/11
 * Time: 3:03 PM
 * To change this template use File | Settings | File Templates.
 */

class CategoryModel extends Activerecord\Model {
    static $table_name = 'categories';
    static $has_many = array(
        array('categories_lang', 'class_name' => 'CategoryLangModel', 'foreign_key' => 'category_id'),
    );
    static $fields = array('id', 'img', 'parent_id', 'order', 'appear_on_site');
    static $lang_ref = 'categories_lang';

    /**
     * returns the categories and subcategories with all the lang information as a nested array
     * + the lang values for the selected language as keys to the category object
     * @static
     * @param null $parent_id
     * @param null $language_id if this is left blank the default language will be selected to place in the category object
     * @param bool $rec if this is set to false it will not recurse (default true)
     * @return void
     */
    static function getAllCategoriesRecursive($parent_id = null, $language_id = null, $rec = true) {
        $categories = array();
        $categoryObjects = CategoryModel::all(array('conditions' => 'parent_id '.(($parent_id == null)?'is NULL':'= '.$parent_id), 'order' => '`order` ASC'));
        if ($categoryObjects !== null && is_array($categoryObjects)) {
            foreach ($categoryObjects as $catObj) {
                $cat = Activerecord::returnArrayWithLang($catObj, $language_id);
                // get subcategories
                if ($rec) {
                    $subcategories = CategoryModel::getAllCategoriesRecursive($cat["id"], $language_id);
                    if ($subcategories != null) {
                        $cat["subcategories"] = $subcategories;
                    }
                }
                $categories[] = $cat;
            }
            return $categories;
        }
        return null;
    }

    static function searchCategories($query, $limit = 10) {
//        $query = mysql_real_escape_string($query);
        $categoryObjects = CategoryModel::all(
            array(
                 'conditions' => 'categories_lang.name LIKE \'%'.$query.'%\'',
                 "limit" => $limit,
                 'order' => '`order` ASC, `id` ASC',
                 'joins' => array('categories_lang'),
                 'group' => 'id'
            )
        );

        $categories = array();
        foreach ($categoryObjects as $c) {
            $cat = Activerecord::returnArrayWithLang($c);
            $categories[] = $cat;
        }

        return $categories;
    }

    static function getCategoriesForParent($parent_id = null, $language_id = null) {
        return CategoryModel::getAllCategoriesRecursive($parent_id, $language_id, false);
    }

    static function getCategoryArrayWithLanguages($category_id, $language_id = null) {
        $categoryObj = CategoryModel::find_by_id($category_id);
        if ($categoryObj != null) {
            $cat = Activerecord::returnArrayWithLang($categoryObj, $language_id);
            return $cat;
        }
        return null;
    }

    function loadFromObject($category) {
        //        'id', 'parent_id', 'order', 'appear_on_site'
        if ((!isset($this->id) || $this->id == null) && isset($category->id) && $category->id != null) $this->id = $category->id;
        $this->parent_id = (isset($category->parent_id))?$category->parent_id:null;
        $this->order = (isset($category->order))?$category->order:0;
        $this->img = (isset($category->img))?$category->img:null;
        $this->appear_on_site = (isset($category->appear_on_site))?$category->appear_on_site:'n';
    }

    public function getParentCategory() {
        $parent_id = $this->parent_id;
        if ($parent_id != null && $parent_id > 0) {
            $parent = ProductCategoryModel::find_by_id($parent_id);
            return $parent;
        }
        return null;
    }

    public function getChildCategories() {
        if (!isset($this->id)) {
            return ProductCategoryModel::all(array('conditions' => 'parent_id IS NULL'));
        }
        return ProductCategoryModel::all(array('conditions' => 'parent_id = '.$this->id));
    }

    public function getProducts() {
        $prcList = ProductReCategoryModel::all(array('conditions' => 'category_id = '.$this->id));
        $products = array();
        foreach ($prcList as $prc) {
            $prod = ProductModel::find_by_id($prc->product_id);
            if ($prod != null) {
                $products[] = $prod;
            }
        }
        return $products;
    }

}
