<?php

/**
 * @Project NUKEVIET BLOG 5.x
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2020 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Saturday, February 22, 2020 15:39:20 GMT+07:00
 */

namespace NukeViet\Module\blog;

use PDO;

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 * nv_mod_blog
 *
 * @package Blog Phan Tan Dung
 * @author PHAN TAN DUNG
 * @copyright 2014
 * @version 1.0
 * @access public
 */
class BlogInit
{
    public $author_email = 'writeblabla@gmail.com';

    private $lang_data = '';
    private $mod_data = '';
    private $mod_name = '';
    private $mod_file = '';
    private $db = null;
    private $cache = null;

    public $db_prefix = '';
    public $db_prefix_lang = "";
    public $table_prefix = "";

    public $setting = null;

    public $indexViewType = ["type_blog", "type_news"];
    public $catViewType = ["type_blog", "type_news"];
    public $tagsViewType = ["type_blog", "type_news"];
    public $blockTagsShowType = [
        "random",
        "latest",
        "popular"
    ];
    public $blogposttype = [0, 1, 2, 3, 4, 5, 6];
    public $blogMediaType = [0, 1, 2, 3, 4];
    public $blogExpMode = [0, 1, 2];
    public $blogStatus = [-2, -1, 0, 1, 2];
    public $commentType = [
        "none",
        "sys",
        "facebook",
        "disqus"
    ];
    public $postingMode = [
        'editor',
        'markdown'
    ];

    private $base_site_url = null;
    private $root_dir = null;
    private $upload_dir = null;
    private $assets_dir = null;
    private $currenttime = null;

    /**
     * nv_mod_blog::__construct()
     *
     * @param string $d
     * @param string $n
     * @param string $f
     * @param string $lang
     * @return
     */
    public function __construct($d = "", $n = "", $f = "", $lang = "")
    {
        global $module_data, $module_name, $module_file, $db_config, $db, $nv_Cache;

        // Ten CSDL
        if (!empty($d))
            $this->mod_data = $d;
        else
            $this->mod_data = $module_data;

        // Ten module
        if (!empty($n))
            $this->mod_name = $n;
        else
            $this->mod_name = $module_name;

        // Ten thu muc
        if (!empty($f))
            $this->mod_file = $f;
        else
            $this->mod_file = $module_file;

        // Ngon ngu
        if (!empty($lang))
            $this->lang_data = $lang;
        else
            $this->lang_data = NV_LANG_DATA;

        $this->db_prefix = $db_config['prefix'];
        $this->db_prefix_lang = $this->db_prefix . '_' . $this->lang_data;
        $this->table_prefix = $this->db_prefix_lang . '_' . $this->mod_data;
        $this->db = $db;
        $this->cache = $nv_Cache;

        $this->setting = $this->get_setting();

        $this->base_site_url = NV_BASE_SITEURL;
        $this->root_dir = NV_ROOTDIR;
        $this->upload_dir = NV_UPLOADS_DIR;
        $this->assets_dir = NV_ASSETS_DIR;
        $this->currenttime = NV_CURRENTTIME;

        // Chuẩn hóa cấu hình
        if (!empty($this->setting['sysDefaultImage'])) {
            if (preg_match("/^\//i", $this->setting['sysDefaultImage'])) {
                $this->setting['sysDefaultImage'] = $this->upload_dir . "/" . $this->mod_name . $this->setting['sysDefaultImage'];

                if (is_file($this->root_dir . '/' . $this->setting['sysDefaultImage'])) {
                    $this->setting['sysDefaultImage'] = $this->base_site_url . $this->setting['sysDefaultImage'];
                } else {
                    $this->setting['sysDefaultImage'] = '';
                }
            } elseif (!nv_is_url($this->setting['sysDefaultImage'])) {
                $this->setting['sysDefaultImage'] = '';
            }
        }

        // Check Execute Data
        if (!empty($this->setting['nextExecuteTime']) and $this->setting['nextExecuteTime'] <= $this->currenttime) {
            $this->executeData(true);
        }
    }

    /**
     * nv_mod_blog::handle_error()
     *
     * @param string $messgae
     * @return
     */
    private function handle_error($messgae = '')
    {
        trigger_error("Error! " . ($messgae ? (string )$messgae : "You are not allowed to access this feature now") . "!", 256);
    }

    /**
     * nv_mod_blog::check_admin()
     *
     * @return
     */
    private function check_admin()
    {
        if (!defined('NV_IS_MODADMIN'))
            $this->handle_error();
    }

    /**
     * nv_mod_blog::nl2br()
     *
     * @param mixed $string
     * @return
     */
    private function nl2br($string)
    {
        return nv_nl2br($string);
    }

    /**
     * nv_mod_blog::db_cache()
     *
     * @param mixed $sql
     * @param string $id
     * @param string $module_name
     * @return
     */
    private function db_cache($sql, $id = '', $module_name = '')
    {
        if (empty($module_name)) {
            $module_name = $this->mod_name;
        }
        return $this->cache->db($sql, $id, $module_name);
    }

    /**
     * nv_mod_blog::del_cache()
     *
     * @param mixed $module_name
     * @return
     */
    private function del_cache($module_name)
    {
        return $this->cache->delMod($module_name);
    }

    /**
     * nv_mod_blog::change_alias()
     *
     * @param mixed $alias
     * @return
     */
    private function change_alias($alias)
    {
        return change_alias($alias);
    }

    /**
     * nv_mod_blog::get_setting()
     *
     * @return
     */
    private function get_setting()
    {
        $sql = "SELECT config_name, config_value FROM " . $this->table_prefix . "_config";
        $result = $this->db_cache($sql);

        $array = [];
        foreach ($result as $values) {
            $array[$values['config_name']] = $values['config_value'];
        }

        return $array;
    }

    /**
     * Sắp xếp mảng dựa theo key, thứ tự key từ một mảng khác
     *
     * @param mixed $keys
     * @param mixed $array
     * @return
     */
    private function sortArrayFromArrayKeys($keys, $array)
    {
        $return = [];

        foreach ($keys as $key) {
            if (isset($array[$key])) {
                $return[$key] = $array[$key];
            }
        }
        return $return;
    }

    /**
     * nv_mod_blog::IdHandle()
     *
     * @param mixed $stroarr
     * @param string $defis
     * @return
     */
    private function IdHandle($stroarr, $defis = ",")
    {
        $return = [];

        if (is_array($stroarr)) {
            $return = array_filter(array_unique(array_map("intval", $stroarr)));
        } elseif (strpos($stroarr, $defis) !== false) {
            $return = array_map("intval", $this->string2array($stroarr, $defis));
        } else {
            $return = [intval($stroarr)];
        }

        return $return;
    }

    /**
     * nv_mod_blog::executeData()
     *
     * @param bool $rmCache
     * @return
     */
    public function executeData($rmCache = false)
    {
        // Cho dang nhung bai dang cho duyet
        $sql = "UPDATE " . $this->table_prefix . "_rows SET status=1 WHERE status=-1 AND pubtime<=" . $this->currenttime;
        $this->db->query($sql);

        // Xu ly cac bai viet het han
        $sql = "SELECT id, expmode FROM " . $this->table_prefix . "_rows WHERE status=1 AND exptime!=0 AND exptime<=" . $this->currenttime;
        $result = $this->db->query($sql);

        while ($row = $result->fetch()) {
            if ($row['expmode'] != 2) {
                $status = $row['expmode'] == 0 ? 0 : 2;

                $sql = "UPDATE " . $this->table_prefix . "_rows SET status=" . $status . " WHERE id=" . $row['id'];
                $result = $this->db->query($sql);
            } else {
                $this->delPost($row['id']);
            }
        }

        // Xac dinh lan thuc hien tiep theo
        $sql = "SELECT MIN(pubtime) AS nextpublic FROM " . $this->table_prefix . "_rows WHERE status=-1 AND pubtime>" . $this->currenttime;
        $result = $this->db->query($sql);
        list($nextpublic) = $result->fetch(3);

        $sql = "SELECT MIN(exptime) AS nextexpire FROM " . $this->table_prefix . "_rows WHERE status=1 AND exptime>" . $this->currenttime;
        $result = $this->db->query($sql);
        list($nextexpire) = $result->fetch(3);

        if (!empty($nextpublic) or !empty($nextexpire)) {
            if (empty($nextpublic)) {
                $nextime = intval($nextexpire);
            } elseif (empty($nextexpire)) {
                $nextime = intval($nextpublic);
            } elseif ($nextexpire > $nextpublic) {
                $nextime = intval($nextpublic);
            } else {
                $nextime = intval($nextexpire);
            }
        } else {
            $nextime = 0;
        }

        $sql = "UPDATE " . $this->table_prefix . "_config SET config_value='" . $nextime . "' WHERE config_name='nextExecuteTime'";
        $this->db->query($sql);

        // Xoa cache neu co
        if ($rmCache) {
            $this->del_cache($this->mod_name);
        }
    }

    /**
     * nv_mod_blog::string2array()
     *
     * @param mixed $str
     * @param string $defis
     * @param bool $unique
     * @param bool $empty
     * @return
     */
    public function string2array($str, $defis = ',', $unique = false, $empty = false)
    {
        if (empty($str)) {
            return [];
        }

        $str = array_map("trim", explode((string )$defis, (string )$str));

        if (!$unique) {
            $str = array_unique($str);
        }

        if (!$empty) {
            $str = array_filter($str);
        }

        return $str;
    }

    /**
     * nv_mod_blog::checkExistsAlias()
     *
     * @param string $alias
     * @param string $mode
     * @param integer $id
     * @return
     */
    public function checkExistsAlias($alias = "", $mode = "", $id = 0)
    {
        $this->check_admin();

        if ($mode == "cat") {
            $array_table_check = ["_rows", "_categories"];
            $mode = "_categories";
        } elseif ($mode == "tags") {
            $array_table_check = ["_tags"];
            $mode = "_tags";
        } else {
            $array_table_check = ["_rows", "_categories"];
            $mode = "_rows";
        }
        $id = intval($id);

        foreach ($array_table_check as $table_check) {
            $sql = "SELECT * FROM " . $this->table_prefix . $table_check . " WHERE alias=" . $this->db->quote($alias) . (($mode == $table_check and !empty($id)) ? " AND id!=" . $id : "");
            $result = $this->db->query($sql);
            if ($result->rowCount()) {
                return true;
            }
        }

        return false;
    }

    /**
     * nv_mod_blog::creatAlias()
     *
     * @param mixed $title
     * @param mixed $mode
     * @return
     */
    public function creatAlias($title, $mode)
    {
        if (empty($title))
            return "";

        $aliasRoot = $alias = strtolower($this->change_alias($title));
        $aliasAdd = 0;

        if ($mode == 'tags') {
            $array_fetch_table = ["_tags"];
        } else {
            $array_fetch_table = ["_rows", "_categories"];
        }

        foreach ($array_fetch_table as $table) {
            while (1) {
                $count = $this->db->query("SELECT COUNT(*) FROM " . $this->table_prefix . $table . " WHERE alias=" . $this->db->quote($alias))->fetchColumn();
                if (empty($count))
                    break;

                if (preg_match("/^(.*)\-(\d+)$/", $alias, $m)) {
                    $alias = $m[1] . "-" . ($m[2] + 1);
                } else {
                    $alias = $aliasRoot . "-" . (++$aliasAdd);
                }
            }
        }

        return $alias;
    }

    /**
     * @return array
     */
    public function listCat()
    {
        $sql = "SELECT * FROM " . $this->table_prefix . "_categories ORDER BY weight_all ASC";
        return $this->db_cache($sql, 'id', $this->mod_name);
    }

    public function updateCatStatistics($id)
    {
        if (empty($id)) {
            return false;
        }

        $ids = $this->IdHandle($id);

        foreach ($ids as $id) {
            // Lấy danh mục
            $sql = "SELECT * FROM " . $this->table_prefix . "_categories WHERE id=" . $id;
            $result = $this->db->query($sql);

            if (!$result->rowCount()) {
                return false;
            }

            $row = $result->fetch(PDO::FETCH_ASSOC);

            // Cập nhật số bài viết
            $numposts = $this->db->query("SELECT COUNT(*) FROM " . $this->table_prefix . "_rows WHERE " . $this->build_query_search_id($id, 'catids') . "AND status=1")->fetchColumn();
            $this->db->query("UPDATE " . $this->table_prefix . "_categories SET numposts=" . $numposts . " WHERE id=" . $id);

            $this->updateCatStatistics($row['parentid']);
        }

        return true;
    }

    /**
     * nv_mod_blog::fixTags()
     *
     * @param mixed $id
     * @return
     */
    public function fixTags($id)
    {
        if (empty($id))
            return;

        $ids = $this->IdHandle($id);

        foreach ($ids as $id) {
            // Lay thong tin cua tags
            $sql = "SELECT * FROM " . $this->table_prefix . "_tags WHERE id=" . $id;
            $result = $this->db->query($sql);

            if (!$result->rowCount())
                return;

            // Cap nhat so bai viet
            $numposts = $this->db->query("SELECT COUNT(*) FROM " . $this->table_prefix . "_rows WHERE " . $this->build_query_search_id($id, 'tagids') . " AND status=1")->fetchColumn();
            $this->db->query("UPDATE " . $this->table_prefix . "_tags SET numposts=" . $numposts . " WHERE id=" . $id);
        }

        return;
    }

    /**
     * nv_mod_blog::getTagsByID()
     * Lay tags tu id
     *
     * @param mixed $id
     * @param bool $sort
     * @return
     */
    public function getTagsByID($id, $sort = false)
    {
        $id = $this->IdHandle($id);

        if (empty($id)) {
            return [];
        }

        // Lay du lieu
        $tags = [];
        $result = $this->db->query("SELECT * FROM " . $this->table_prefix . "_tags WHERE id IN(" . implode(",", $id) . ")");

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $tags[$row['id']] = $row;
        }

        // Sap xep theo thu tu cua array
        if ($sort === true and sizeof($tags) > 1) {
            $tags = $this->sortArrayFromArrayKeys($id, $tags);
        }

        return $tags;
    }

    /**
     * nv_mod_blog::getPostByID()
     * Lay bai viet tu id
     *
     * @param mixed $id
     * @param bool $sort
     * @return
     */
    public function getPostByID($id, $sort = false)
    {
        $id = $this->IdHandle($id);

        if (empty($id)) {
            return [];
        }

        // Lay du lieu
        $posts = [];
        $result = $this->db->query("SELECT * FROM " . $this->table_prefix . "_rows WHERE id IN(" . implode(",", $id) . ")");

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $posts[$row['id']] = $row;
        }

        // Sap xep theo thu tu cua array
        if ($sort === true and sizeof($posts) > 1) {
            $posts = $this->sortArrayFromArrayKeys($id, $posts);
        }

        return $posts;
    }

    /**
     * nv_mod_blog::build_query_search_id()
     *
     * @param mixed $id
     * @param mixed $field
     * @param string $logic
     * @return
     */
    public function build_query_search_id($id, $field, $logic = 'OR')
    {
        if (empty($id)) {
            return $field . "=''";
        }

        $id = $this->IdHandle($id);

        $query = [];
        foreach ($id as $_id) {
            $query[] = "FIND_IN_SET(" . $_id . ", " . $field . ")";
        }
        $query = '(' . implode(' ' . $logic . ' ', $query) . ')';

        return $query;
    }

    /**
     * nv_mod_blog::delPost()
     *
     * @param mixed $id
     * @return
     */
    public function delPost($id)
    {
        // Lay thong tin cac bai viet
        $posts = $this->getPostByID($id);

        // Cac tags se fix
        $array_tags_fix = [];

        // Cac danh muc se fix
        $array_cat_fix = [];

        foreach ($posts as $row) {
            // Xoa bang chinh
            $sql = "DELETE FROM " . $this->table_prefix . "_rows WHERE id=" . $row['id'];
            $this->db->query($sql);

            // Xoa bang detail
            $sql = "DELETE FROM " . $this->table_prefix . "_rows_detail WHERE id=" . $row['id'];
            $this->db->query($sql);

            // Them cac danh muc can fix
            $row['catids'] = $this->string2array($row['catids']);
            foreach ($row['catids'] as $catid) {
                $array_cat_fix[$catid] = $catid;
            }

            // Them cac tags can fix
            $row['tagids'] = $this->string2array($row['tagids']);
            foreach ($row['tagids'] as $tagsid) {
                $array_tags_fix[$tagsid] = $tagsid;
            }

            // Xoa tien trinh gui email
            $sql = "DELETE FROM " . $this->table_prefix . "_send WHERE pid=" . $row['id'];
            $this->db->query($sql);
        }

        // Cap nhat tags
        $this->fixTags($array_tags_fix);

        // Cập nhật thống kê cho danh mục
        $this->updateCatStatistics($array_cat_fix);
    }

    /**
     * nv_mod_blog::BoldKeywordInStr()
     *
     * @param mixed $str
     * @param mixed $keyword
     * @return
     */
    public function BoldKeywordInStr($str, $keyword)
    {
        $tmp = explode(" ", $keyword);

        foreach ($tmp as $k) {
            $tp = nv_strtolower($k);
            $str = str_replace($tp, "<span class=\"highlight\">" . $tp . "</span>", $str);
            $tp = nv_strtoupper($k);
            $str = str_replace($tp, "<span class=\"highlight\">" . $tp . "</span>", $str);
            $k[0] = nv_strtoupper($k[0]);
            $str = str_replace($k, "<span class=\"highlight\">" . $k . "</span>", $str);
        }

        return $str;
    }

    /**
     * nv_mod_blog::GetAspectRatio()
     *
     * @param mixed $w
     * @param mixed $h
     * @return
     */
    public function GetAspectRatio($w, $h)
    {
        if (empty($w) or empty($h) or $h == $w) {
            return '1:1';
        }
        $reverse = ($w < $h);
        if ($reverse) {
            $w1 = $w;
            $w = $h;
            $h = $w1;
        }
        if ($w % $h == 0) {
            $w = $w / $h;
            $h = 1;
        } else {
            $ratio = round($w / $h, 1) * 10;
            $h = 10;
            $w = $ratio;
            while ($w % 2 == 0 and $h % 2 == 0) {
                $w = floor($w / 2);
                $h = floor($h / 2);
            }
        }
        return $reverse ? ($h . ':' . $w) : ($w . ':' . $h);
    }

    /**
     * @param number $parentid
     * @param number $order
     * @param number $lev
     * @return number|integer
     */
    public function fixCatOrder($parentid = 0, $weight_all = 0, $cat_level = 0)
    {
        /*
         * Lấy ra ID các chuyên mục trong cấp này
         * theo thứ tự tăng dần
         */
        $sql = 'SELECT id FROM ' . $this->table_prefix . '_categories WHERE parentid=' . $parentid . ' ORDER BY weight_level ASC';
        $array_cat_order = $this->db->query($sql)->fetchAll(PDO::FETCH_COLUMN);

        /*
         * Tính cấp của chuyên mục, bắt đầu từ cấp 1
         * Tính lại thứ tự của chuyên mục trong cấp này
         */
        $weight_level = 0;
        if ($parentid > 0) {
            $cat_level++;
        } else {
            $cat_level = 1;
        }
        foreach ($array_cat_order as $id_i) {
            $weight_all++;
            $weight_level++;
            $sql = 'UPDATE ' . $this->table_prefix . '_categories SET
                weight_level=' . $weight_level . ',
                weight_all=' . $weight_all . ',
                cat_level=' . $cat_level . '
            WHERE id=' . intval($id_i);
            $this->db->query($sql);
            $weight_all = $this->fixCatOrder($id_i, $weight_all, $cat_level);
        }

        /*
         * Tính số chuyên mục con bằng thứ tự cao nhất trong cấp
         */
        $subcat_num = $weight_level;
        if ($parentid > 0) {
            $sql = 'UPDATE ' . $this->table_prefix . '_categories SET subcat_num=' . $subcat_num;
            if ($subcat_num == 0) {
                $sql .= ", subcat_ids=''";
            } else {
                $sql .= ", subcat_ids='" . implode(',', $array_cat_order) . "'";
            }
            $sql .= ' WHERE id=' . intval($parentid);
            $this->db->query($sql);
        }

        return $weight_all;
    }
}
