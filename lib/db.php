<?php
/** 数据库访问类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/27
 * Time: 10:24
 */

class Db
{
    private $pdo = null;
    private $table = '';
    private $field = '*';
    private $where = array();
    private $order = '';
    private $limit = 0;//0默认不限制，全部返回

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=myblog;charset=utf8', 'root', 'heyl1989715');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//捕获pdo错误日志
    }

    /** 指定表名称
     * @param $table
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**指定查询字段
     * @param $field
     * @return $this
     */
    public function field($field)
    {
        $this->field = $field;
        return $this;
    }


    /**指定where条件
     * @param $where
     */
    public function where($where)
    {
        $this->where = $where;
        return $this;
    }

    /**指定排序条件
     * @return $this
     */
    public function order($order)
    {
        $this->order = $order;
        return $this;
    }

    /**指定返回数据条数
     * @param $limit
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * 返回一条数据
     */
    public function item()
    {
        $sql = $this->_build_sql('select') . ' limit 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return isset($rows[0]) ? $rows[0] : false;
    }

    /**返回集合
     * @return bool
     */
    public function list()
    {
        $stmt = $this->pdo->prepare($this->_build_sql('select'));
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }


    /**返回条数
     * @param $count
     */
    public function count()
    {
        $stmt = $this->pdo->prepare($this->_build_sql('count'));
        $stmt->execute();
        $total = $stmt->fetchColumn(0);
        return $total;
    }

    /**分页
     * @param $page
     * @param int $pageSize
     * select * from cates where id>1 limit 0,10;
     * select * from cates where id>1 limit 10,10;
     */
    public function pages($page, $pageSize = 10, $path = '/')
    {
        $count = $this->count();
        $this->limit = ($page - 1) * $pageSize . ',' . $pageSize;
        $data = $this->list();
        $pages = $this->_subPages($page, $pageSize, $count, $path);
        return array('total' => $count, 'data' => $data, 'pages' => $pages);
    }

    /**生成分页html（bootstap）
     * @param $cur_page 当前页
     * @param $pageSize 每页多少条数据
     * @param $total 数据总数
     * @return string
     */
    private function _subPages($cur_page, $pageSize, $total, $path)
    {
        $symbol = '?';
        $index = strpos($path, '?');
        if (!$index === false && $index > 0) {
            $symbol = '&';
        }
        //分页数
        $page_count = ceil($total / $pageSize);
        //生成首页 生成上一页
        if ($cur_page > 1) {
            $html = "<li><a href='{$path}{$symbol}page=1'>首页</a></li>";
            $pre_page = $cur_page - 1;
            $html .= "<li><a href='{$path}{$symbol}page= $pre_page' >上一页</a></li>";
        }
        //生成数字页
        $start = $cur_page > ($page_count - 6) ? $page_count - 6 : $cur_page;
        $start = $start - 2;
        $start = $start <= 0 ? 1 : $start;
        $end = ($cur_page + 6) > $page_count ? $page_count : ($cur_page + 6);
        $end = $page_count > 6 ? $end - 2 : $end;
        if ($cur_page + 2 >= $end && $page_count > 6) {
            $start = $start + 2;
            $end = $end + 2;
        }
        for ($i = $start; $i <= $end; $i++) {
            $html .= $cur_page == $i ? "<li class='active'><a>{$i}</a></li>" : "<li><a href='{$path}{$symbol}page={$i}'>{$i}</a></li>";
        }
        //  //生成下一页 生成尾页
        if ($cur_page < $page_count) {
            $next_page = $cur_page + 1;
            $html .= "<li><a href='{$path}{$symbol}page=$next_page'>下一页</a></li>";
            $html .= "<li><a href='{$path}{$symbol}page={$page_count}'>尾页</a></li>";
        }
        $html = '<nav aria-label="Page navigation"><ul class="pagination">' . $html . '</ul></nav>';
        return $html;
    }

    /**添加数据
     * @return string
     */
    public function insert($data)
    {
        $stmt = $this->pdo->prepare($this->_build_sql('insert', $data));
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    /**删除数据
     * @return string
     */
    public function delete()
    {
        $stmt = $this->pdo->prepare($this->_build_sql('delete'));
        $stmt->execute();
        return $stmt->rowCount();//返回受影响的行数
    }

    public function update($data)
    {
        $stmt = $this->pdo->prepare($this->_build_sql('update', $data));
        $stmt->execute();
        return $stmt->rowCount();//返回受影响的行数
    }

    private function _build_sql($type, $data = null)
    {
        $sql = '';
        if ($type == 'select') {
            //查询
            $where = $this->_build_where();
            $sql = "select {$this->field} from {$this->table} {$where}";
            if ($this->order) {
                $sql .= " order by {$this->order}";
            }
            if ($this->limit) {
                $sql .= " limit {$this->limit}";
            }
        }
        if ($type == 'count') {
            //count
            $where = $this->_build_where();
            $field_list = explode(',', $this->field);
            $field = count($field_list) > 1 ? '*' : $this->field;
            $sql = "select count({$field}) from {$this->table} {$where}";
        }
        if ($type == 'insert') {
            //增加数据
            $sql = "INSERT INTO {$this->table}";
            $fields = $values = [];
            foreach ($data as $key => $val) {
                $fields[] = "`".$key."`";
                $values[] = is_string($val) ? "'" . $val . "'" : $val;
            }
            $sql .= "(" . implode(",", $fields) . ") VALUES (" . implode(",", $values) . ")";
        }
        if ($type == 'delete') {
            //删除数据
            $where = $this->_build_where();
            $sql = "DELETE FROM {$this->table} {$where}";
        }
        if ($type == 'update') {
            //修改数据
            $where = $this->_build_where();
            $str = '';
            foreach ($data as $key => $val) {
                $val = is_string($val) ? "'{$val}'" : $val;
                $str .= "{$key}={$val},";
            }
            $str = rtrim($str, ',');
            $sql = "UPDATE {$this->table} SET {$str} {$where}";
        }
        return $sql;
    }

    /**构建Where
     * @return array|string
     */
    private function _build_where()
    {
        $where = '';
        if (is_array($this->where)) {
            //数组方式
            foreach ($this->where as $key => $value) {
                $value = is_string($value) ? "'{$value}'" : $value;
                $where .= "`{$key}`={$value} and ";
            }
        } else {
            //字符串方式
            $where = $this->where;
        }
        $where = rtrim($where, 'and ');
        $where = $where == '' ? '' : "where {$where}";
        return $where;
    }


}